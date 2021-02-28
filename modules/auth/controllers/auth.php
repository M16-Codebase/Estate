<?php
class Auth extends MY_Controller
{
	// Для валидации логина и пароля
	public $min_username = 4; // минимальная длина логина
	public $max_username = 20; // максимальная длина логина
	public $min_password = 4; // минимальная длина пароля
	public $max_password = 20; // максимальная длина пароля    

 	function __construct()
	{
		parent::__construct();
        
		$this->load->library('form_validation', array('ci'=>$this)); // библиотека валидации        		        
        
        // Создаем объект языкового файла
        $this->lang = new stdClass;

        // подгрузка языка 
        $k = $this->load->language('auth/auth_mod','',true);       
        foreach($k as $key=>$l)
        {
            // заносим записи языкового файла
            $this->lang->$key = $l['name'];
        }
        
        $this->data['lang'] = $this->lang;                                                        
	}
	    
    // главный контроллер 
	function index()
	{		                
        // запускаем авторизацию        
        $this->login();        
	}
  

    // Вход 
    function login()  
    {                                  
        if ( ! $this->dx_auth->is_logged_in())  
        {                          
            // передаем объекст валидации в переменную
            $val = $this->form_validation;  
               
            // Устанавливаем правила проверки формы
            $val->set_rules('username', "{$this->lang->form_input_text_username}", 'trim|required|xss_clean');  
            $val->set_rules('password', "{$this->lang->form_input_text_password}", 'trim|required|xss_clean');  
            $val->set_rules('remember', "{$this->lang->form_input_text_remember}", 'integer');  
  
            // Устанавливаем правило для включения captcha, если количество попыток входа больше максимально установленного в конфигурации
            if ($this->dx_auth->is_max_login_attempts_exceeded())  
            {  
                $val->set_rules('captcha', "{$this->lang->form_input_text_captcha}", 'trim|required|xss_clean|callback_captcha_check');  
            }  
                   
            if ($val->run() AND $this->dx_auth->login($val->set_value('username'), $val->set_value('password'), $val->set_value('remember')))  
            {                  
                // Авторизация успешно пройдена                                
                if(ses_data('adminPage'))
                {
                    $this->session->unset_userdata('adminPage'); // удаляем переменную сесиии если была проба авторизации с админки                    
                    redirect(BASEURL.'/'.$this->session->userdata('url'), 'location'); // Пересылаем на нужную страницу         
                }
                else
                {
                    // Пересылаем на домашнюю страницу  
                    redirect('', 'location');         
                }
                
                
                         
            }  
            else 
            {  
                // Проверяем почему пользователь не смог войти. Потому что его заблокировали или нет?
                if ($this->dx_auth->is_banned())  
                {  
                    // Пересылаем на страницу с сообщением о заблокированной учётной записи
                    $this->dx_auth->deny_access('banned');  
                }  
                else 
                {                         
                    // По умолчанию не показываем captcha пока пользователь не исчерпал количество попыток входа установленное в конфигурации
                    $this->data['show_captcha'] = FALSE;  
                   
                    // Показываем captcha, если количество попыток входа достигло максимума установленного в конфигурации
                    if ($this->dx_auth->is_max_login_attempts_exceeded())  
                    {  
                        // Создаём catpcha                         
                        $this->dx_auth->captcha();  
                           
                        // Включаем отображение captcha в файле отображения
                        $this->data['show_captcha'] = TRUE;  
                    }  
                    
                    /*if($this->input->is_ajax_request())
                    {                        
                        echo json_encode(array(
                            'ok' => $this->load->view($this->dx_auth->login_view, $this->data, true)
                        ));
                    }
                    else
                    {  */ 
                        
                        // Загружаем отображение страницы входа
                        if(ses_data('adminPage'))
                        {
                            $this->load->helper('disp');
                            $this->data['data'] = $this->load->view('admin/admin','',true);
                            echo auth_display($this->data);
                        }
                        elseif(ses_data('assignmentPage'))
                        {
                            $this->load->helper('disp');
                            $this->data['data'] = $this->load->view('assignments/assignments/index','',true);
                            echo auth_display_assignments($this->data);
                        }
                        else
                        {
                            $this->data['template'] = $this->render($this->dx_auth->login_view, $this->data);
                            $this->viewPage($this->data);
                        }
                    //}  
                }  
            }  
        }  
        else 
        {  
            $this->data['auth_message'] = 'Вы уже вошли.';  
            $this->viewPage($this->data);            
        }  
    }

    // Вход
    function loginAss()
    {
        if ( ! $this->dx_auth->is_logged_in())
        {
            // передаем объекст валидации в переменную
            $val = $this->form_validation;

            // Устанавливаем правила проверки формы
            $val->set_rules('username', "{$this->lang->form_input_text_username}", 'trim|required|xss_clean');
            $val->set_rules('password', "{$this->lang->form_input_text_password}", 'trim|required|xss_clean');
            $val->set_rules('remember', "{$this->lang->form_input_text_remember}", 'integer');

            // Устанавливаем правило для включения captcha, если количество попыток входа больше максимально установленного в конфигурации
            if ($this->dx_auth->is_max_login_attempts_exceeded())
            {
                $val->set_rules('captcha', "{$this->lang->form_input_text_captcha}", 'trim|required|xss_clean|callback_captcha_check');
            }

            if ($val->run() AND $this->dx_auth->login($val->set_value('username'), $val->set_value('password'), $val->set_value('remember')))
            {
                // Авторизация успешно пройдена
                if(ses_data('adminPage'))
                {
                    $this->session->unset_userdata('adminPage'); // удаляем переменную сесиии если была проба авторизации с админки
                    redirect(BASEURL.'/'.$this->session->userdata('url'), 'location'); // Пересылаем на нужную страницу
                }
                else
                {
                    // Пересылаем на домашнюю страницу
                    redirect('', 'location');
                }



            }
            else
            {
                // Проверяем почему пользователь не смог войти. Потому что его заблокировали или нет?
                if ($this->dx_auth->is_banned())
                {
                    // Пересылаем на страницу с сообщением о заблокированной учётной записи
                    $this->dx_auth->deny_access('banned');
                }
                else
                {
                    // По умолчанию не показываем captcha пока пользователь не исчерпал количество попыток входа установленное в конфигурации
                    $this->data['show_captcha'] = FALSE;

                    // Показываем captcha, если количество попыток входа достигло максимума установленного в конфигурации
                    if ($this->dx_auth->is_max_login_attempts_exceeded())
                    {
                        // Создаём catpcha
                        $this->dx_auth->captcha();

                        // Включаем отображение captcha в файле отображения
                        $this->data['show_captcha'] = TRUE;
                    }

                    /*if($this->input->is_ajax_request())
                    {
                        echo json_encode(array(
                            'ok' => $this->load->view($this->dx_auth->login_view, $this->data, true)
                        ));
                    }
                    else
                    {  */

                    if(ses_data('assignmentPage'))
                    {
                        $this->load->helper('disp');
                        $this->data['data'] = $this->load->view('assignments/assignments/index','',true);
                        echo auth_display_assignments($this->data);
                    }
                    
                    //}
                }
            }
        }
        else
        {
            $this->data['auth_message'] = 'Вы уже вошли.';
            $this->viewPage($this->data);
        }
    }

    // Вход ajax (adminka)
    function loginajax()  
    {                                              
            $username = $this->input->post('username',true);
            $password = $this->input->post('password',true);
            $remember = $this->input->post('remember',true);
                                             
            $ban_reason = '';
            
            if ($this->dx_auth->login($username, $password, $remember))  
            {                  
                 $logged = true;         
            }  
            else 
            {                   
                 // грузим модель
        		$this->load->model($this->module.'/dx_auth/users', 'users');
        		$this->load->model($this->module.'/dx_auth/user_temp', 'user_temp');
        		$this->load->model($this->module.'/dx_auth/login_attempts', 'login_attempts');
                
                if ($query = $this->users->get_login($username) AND $query->num_rows() == 1)
                {
                    // Get user record
    				$row = $query->row();
    
    				// Check if user is banned or not
    				if ($row->banned > 0)
    				{    										
    					if(empty($row->ban_reason))
                        {
                            $row->ban_reason = 'Sorry, but your profile is suspended';
                        }
                        // Set ban reason
    					$ban_reason = str_replace("\n", '<br />', $row->ban_reason);
    				}
                }
                else
                {
                    $logged = false;
                }                
            }  
          
            echo json_encode(array(
                    'logged' => $logged,
                    'ban' => $ban_reason
                ));
    } 
    
    // Выход 
    function logout()  
    {  
        $this->dx_auth->logout();  
        redirect('','location');   
        //$this->data['auth_message'] = 'Вы вышли.';          
        //$this->load->view($this->dx_auth->logout_view, $this->data);  
    }  
 
    // Регистрация пользователя 
    function register()  
    {                         
        $this->headSeo($this->lang->form_input_text_register);
        
        if ( ! $this->dx_auth->is_logged_in() AND $this->dx_auth->allow_registration)  
        {     
            $val = $this->form_validation;                                      
            
            // задаем поля формы
            $fields = array(
                'username' => 
                            array(                
                                'label' => $this->lang->form_input_text_username,
                                'rules' => "trim|required|xss_clean|min_length[$this->min_username]|max_length[$this->max_username]|callback_username_check"
                                ),
                'password' =>
                            array(                
                                'label' => $this->lang->form_input_text_password,
                                'rules' => "trim|required|xss_clean|min_length[$this->min_password]|max_length[$this->max_password]|matches[confirm_password]"
                                ),
                'confirm_password' =>
                            array(                
                                'label' => $this->lang->form_input_text_confirm_password,
                                'rules' => 'trim|required|xss_clean'
                                ),
                'email' =>
                            array(                
                                'label' => $this->lang->form_input_text_email,
                                'rules' => 'trim|required|xss_clean|valid_email|callback_email_check'
                                ),
                'confirm_password' =>
                            array(                
                                'label' => $this->lang->form_input_text_confirm_password,
                                'rules' => 'trim|required|xss_clean'
                                )                                                                            
            );
                                                                                  
            // Устанавливаем правила проверки формы
            foreach($fields as $key=>$value)
            {
                $val->set_rules($key,$value['label'],$value['rules']);
                $this->data['form'][$key] = array(
                                'name'  => $key,
                                'id'    => $key,                                                                
                                'label' => $value['label']                                
                            );
            }

            // Если используется captcha то выводим ее   
            if ($this->dx_auth->captcha_registration)  
            {  
                $val->set_rules('captcha', "{$this->lang->form_input_text_captcha}", 'trim|xss_clean|required|callback_captcha_check');  
            }  
            
            // данные которые не нужно передавать в профиль
            $ar = array('username','password','confirm_password','email','captcha','register');
            $mas = NULL; // начальное значение массива который передает данные в профиль
            foreach($_POST as $k=>$p)
            {
                if(!in_array($k,$ar))
                {
                    // заносим данные которые передаются в таблицу профиль
                    $mas[$k] = $p;
                }
            }         
            
            // Выполняем проверку формы и регистрируем пользователя, если он прошёл проверку
            if ($val->run($this))  
            {                   
                $username_chek = $this->username_check($val->set_value('username'));
                $email_chek = $this->email_check($val->set_value('email'));
                
                if($username_chek AND $email_chek)
                {
                    if($this->dx_auth->register($val->set_value('username'), $val->set_value('password'), $val->set_value('email'), $mas))
                    {
                            // Устанавливаем сообщение об успешной регистрации
                            if ($this->dx_auth->email_activation)  
                            {  
                                // если была включена активация по email
                                $this->data['auth_message'] = 'Вы успешно зарегистрировались. Проверьте вашу электронную почту для активации учётной записи.';  
                            }  
                            else 
                            {                     
                                // если активация отключена
                                $this->data['auth_message'] = 'Вы успешно зарегистрировались. '.anchor(site_url($this->dx_auth->login_uri), 'Login');  
                            }  
                           
                            // Загружаем отображение страницы успешной регистрации                            
                            $this->viewPage($this->data);
                    }
                }
                else
                {
                        // Если при регистрации используется captcha
                        if ($this->dx_auth->captcha_registration)  
                        {                      
                            $this->dx_auth->captcha();                                          
                        }  
          
                        // Загружаем отображение страницы регистрации
                        $this->data['template'] = $this->render($this->dx_auth->register_view, $this->data);
                        $this->viewPage($this->data);
                }          
            }  
            else // если проверка не прошла и выявились ошибки
            {  
                    // Если при регистрации используется captcha
                    if ($this->dx_auth->captcha_registration)  
                    {                      
                        $this->dx_auth->captcha();                                          
                    }  
      
                    // Загружаем отображение страницы регистрации
                    $this->data['template'] = $this->render($this->dx_auth->register_view, $this->data);
                    $this->viewPage($this->data);  
            }  
        }  
        elseif ( ! $this->dx_auth->allow_registration) // если регистрация отключена
        {  
            $this->data['auth_message'] = 'Регистрация отключена.';  
            $this->viewPage($this->data);  
        }  
        else // если пользователь авторизован
        {  
            $this->data['auth_message'] = 'Перед тем как зарегистрироваться, необходимо выйти.';  
            $this->viewPage($this->data); 
        }  
    }  
    
    
    /** Callback(возвращающая) function */ 
    
    // Существует ли пользователь	    
	function username_check($username)
	{		        
        $result = $this->dx_auth->is_username_available($username);
		if ( ! $result)
		{
			$this->session->set_userdata(array('username_check'=>$this->lang->username_alredy_exist));            
		}
				
		return $result;
	}
    
    // Существует ли email
	function email_check($email)
	{
		$result = $this->dx_auth->is_email_available($email);
		if ( ! $result)
		{			
            $this->session->set_userdata(array('email_check'=>$this->lang->email_alredy_exist));
		}
				
		return $result;
	}
    
    // Проверка captcha 
    function captcha_check($code)  
    {  
        $result = TRUE;  
           
        if ($this->dx_auth->is_captcha_expired())  
        {  
            // Это сообщение можно изменить с помощью $lang
            $this->form_validation->set_message('captcha_check', "{$this->lang->captcha_expire_exist}");              
            $result = FALSE;  
        }  
        elseif ( ! $this->dx_auth->is_captcha_match($code))  
        {  
            $this->form_validation->set_message('captcha_check', "{$this->lang->captcha_enter_error}");             
            $result = FALSE;  
        }  
  
        return $result;  
    }             
    /** Конец функций обратного вызова */  
    
     
    // Активация учётной записи 
    function activate()  
    {  
        // Получаем имя пользователя и ключ
        $username = $this->uri->segment(3);  
        $key = $this->uri->segment(4);  
  
        // Активируем учётную запись пользователя
        if ($this->dx_auth->activate($username, $key))   
        {  
            $this->data['auth_message'] = 'Ваша учётная запись успешно активирована. '.anchor(site_url($this->dx_auth->login_uri), 'Login');                
        }  
        else 
        {  
            $this->data['auth_message'] = 'Код активации введён не правильно. Пожалуйста проверьте вашу электронную почту ещё раз.';               
        }  
        
        $this->viewPage($this->data);
    }  
     
    // Восстановление забытого пароля 
    function forgot_password()  
    {  
        $val = $this->form_validation;  
           
        // Устанавливаем правила проверки формы
        $val->set_rules('login', $this->lang->form_input_text_forgot_password_text, 'trim|required|xss_clean');  
  
        // Выполняем проверку формы и вызываем функцию восстановления забытого пароля
        if ($val->run() AND $this->dx_auth->forgot_password($val->set_value('login')))  
        {  
            $this->data['auth_message'] = 'Сообщение с инструкцией активации вашего нового пароля, было выслано на ваш адрес электронной почты.';  
            $this->viewPage($this->data);  
        }  
        else 
        {  
            $this->data['template'] = $this->render($this->dx_auth->forgot_password_view, $this->data);  
            $this->viewPage($this->data);
        }  
    } 
    
    // Восстановление забытого пароля 
    function forgot_password_ajax()  
    {          
        $login = $this->input->post('login',true);
        
        // Выполняем проверку формы и вызываем функцию восстановления забытого пароля
        if ($this->dx_auth->forgot_password($login))  
        {  
            $logged = true;  
        }  
        else 
        {  
            $logged = false;
        } 
        
        echo json_encode(array(
                    'logged' => $logged
                )); 
    } 
      
    // Сброс пароля 
    function reset_password()  
    {  
        // Получаем имя пользователя и ключ
        $username = $this->uri->segment(3);  
        $key = $this->uri->segment(4);  
  
        // Сбрасываем пароль пользователя
        if ($this->dx_auth->reset_password($username, $key))  
        {  
            $this->data['auth_message'] = 'Ваш пароль был сброшен, '.anchor(site_url($this->dx_auth->login_uri), 'Login');  
            $this->viewPage($this->data);  
        }  
        else 
        {  
            $this->data['auth_message'] = 'Ошибка сброса пароля. Имя пользователя и ключ не неправильные. Пожалуйста проверьте вашу электронную почту ещё раз и следуйте инструкциям.';  
            $this->viewPage($this->data); 
        }  
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
                $this->viewPage($this->data);  
            }  
            else 
            {  
                $this->data['template'] = $this->load->view($this->dx_auth->change_password_view, '', true);
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