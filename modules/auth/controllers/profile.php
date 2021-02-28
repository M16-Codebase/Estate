<?php
class Profile extends MY_Controller
{  
 	// Для валидации логина и пароля
	public $min_username = 4; // минимальная длина логина
	public $max_username = 20; // максимальная длина логина
	public $min_password = 4; // минимальная длина пароля
	public $max_password = 20; // максимальная длина пароля 
    public $links = ''; // ссылка внутри профиля
    
    function __construct()
	{
		parent::__construct();        
        $this->load->library('form_validation', array('ci'=>$this)); // библиотека валидации  
        $this->links = BASEURL.'/auth/profile/';      		                                                        
	}
	    
    // Страница профиля 
	function index($user_id = '')
	{		                
	    if(empty($user_id))
        {
            $user_id = $this->session->userdata('DX_user_id');
            
            if(empty($user_id))
            {
                redirect('/');
            }
        }                
        $pageName = 'Страница профиля';
        $this->addVar('title', $pageName);
        
        $this->load->model('dx_auth/user_profile');
        $profile = $this->user_profile->get_profile($user_id)->row_object();               
                
        $template[] = '<img src="'.$profile->avatar.'" />';
        $template[] = 'Идентификатор: '.$user_id;
        $template[] = 'Имя: '.$profile->name;
        $template[] = 'Фамилия: '.$profile->surname;
        $template[] = 'Город: '.$profile->city;
        
        $template[] = '<br />Ссылки:';
        $template[] = '<a href="'.$this->links.'change_password  ">Изменить пароль</a>';
        $template[] = '<a href="'.$this->links.'delete_account  ">Удалить профиль</a>';
        
        $this->addVar('template', implode('<br />', $template));
        $this->viewPage();
	}    
     
    // Смена пароля 
    function change_password()  
    {  
        // Check if user logged in or not  
        if ($this->dx_auth->is_logged_in())  
        {             
            $val = $this->form_validation;  
               
            // Устанавливаем правила проверки формы  
            $val->set_rules('old_password', 'Текущий пароль', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']');  
            $val->set_rules('new_password', 'Новый пароль', 'trim|required|xss_clean|min_length['.$this->min_password.']|max_length['.$this->max_password.']|matches[confirm_new_password]');  
            $val->set_rules('confirm_new_password', 'Подтвердить новый пароль', 'trim|required|xss_clean');  
               
            // Выполняем проверку форы и меняем пароль
            if ($val->run() AND $this->dx_auth->change_password($val->set_value('old_password'), $val->set_value('new_password')))  
            {  
                $this->data['auth_message'] = 'Ваш пароль изменён.';  
                $this->viewPage();  
            }  
            else 
            {  
                $this->data['template'] = $this->load->view($this->dx_auth->change_password_view, '', true);
                $this->viewPage();  
            }  
        }  
        else 
        {  
            // Пересылаем на страницу входа 
            $this->dx_auth->deny_access('login');  
        }  
    }     
     
    // Удаление учётной записи 
    function delete_account()  
    {  
        // Проверяем вошёл пользователь или нет
        if ($this->dx_auth->is_logged_in())  
        {             
            $val = $this->form_validation;  
               
            // Устанавливаем правила проверки формы
            $val->set_rules('password', 'Текущий пароль', "trim|required|xss_clean");  
               
            // Выполняем проверку и удаляем учётную запись пользователя 
            if ($val->run() AND $this->dx_auth->cancel_account($val->set_value('password')))  
            {  
                // Пересылаем на домашнюю страницу 
                redirect('', 'location');  
            }  
            else 
            {  
                $this->data['template'] = $this->load->view($this->dx_auth->cancel_account_view, '', true); 
                $this->viewPage($this->data); 
            }  
        }  
        else 
        {  
            // Пересылаем на страницу входа  
            $this->dx_auth->deny_access('login');  
        }  
    }     
  
}
?>