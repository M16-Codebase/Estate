<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Event for DX_Auth
// You can use DX_Auth_Event to extend DX_Auth to fullfil your needs
// For example: you can use event below to PM user when he already activated the account, etc.

class DX_Auth_Event
{
	private $ci;
	
	function DX_Auth_Event()
	{
		$this->ci =& get_instance();
        
        $this->ci->load->language('auth/auth_mod');
        
        $this->confi = new stdClass; 
        $foreach = $this->ci->load->config('auth/auth', true);           
        foreach($foreach as $key=>$с)
        {
            // заносим записи языкового файла
            $this->confi->$key = $с;
        }    
        
        $this->confi->DX_autologin_cookie_name = 'autologin';   
        $this->confi->DX_autologin_cookie_life = 5356800;
        $this->confi->DX_captcha_expire = 180;
        $this->confi->DX_captcha_case_sensitive = 1;      
        $this->confi->DX_email_activation_expire = 172800;
        
        // Создаем объект языкового файла
        $this->lang = new stdClass;
        $foreach = $this->ci->load->language('auth/auth_mod', '', true);          
        foreach($foreach as $key=>$l)
        {
            // заносим записи языкового файла
            $this->lang->$key = $l['name'];
        }
	}
			
	// Активация
    function user_activated($user_id, $mas = NULL)
	{
		// грузим модель
		$this->ci->load->model('auth/dx_auth/user_profile', 'user_profile');
		
		// Создание профиля
		$this->ci->user_profile->create_profile($user_id);
        if($mas != NULL){
            $this->ci->user_profile->set_profile($user_id,$mas);
        }
	}
	
	// Событие происходит когда пользователь авторизировался
	function user_logged_in($user_id)
	{	   	                 
	}
	
	// Событие происходит после завершения сеанса    
	function user_logging_out($user_id)
	{
	}
	
	// Событие происходит после смены пароля    
	function user_changed_password($user_id, $new_password)
	{
	}
	
	// Событие после удаление аккаунта    
	function user_canceling_account($user_id)
	{
		// грузим модель
		$this->ci->load->model('auth/dx_auth/user_profile', 'user_profile');
		
		// удаляем профиль
		$this->ci->user_profile->delete_profile($user_id);
	}
	
	// This event occurs when check_uri_permissions() function in DX_Auth is called
	// after checking if user role is allowed or not to access URI, this event will be triggered
	// $allowed is result of the check before, it's possible to alter the value since it's passed by reference
    /** доступ ?????? */
	function checked_uri_permissions($user_id, &$allowed)
	{	
	   
	}
	
	// This event occurs when get_permission_value() function in DX_Auth is called	
	/** ???????? */
    function got_permission_value($user_id, $key)
	{	
	}
	
	// This event occurs when get_permissions_value() function in DX_Auth is called	
	/** ????????? */
    function got_permissions_value($user_id, $key)
	{	
	}
	
	// Отправка сообщения на email об аккаунте / без активации
    function sending_account_email($data, &$content)
	{				
		// Создание контента	
		$content = sprintf($this->lang->auth_account_content, 
			$this->confi->DX_website_name, 
			$data['username'], 
			$data['email'], 
			$data['password'], 
			site_url('auth/login/'),
			$this->confi->DX_website_name);
	}
	

    // Отправка сообщения на email активацию
	function sending_activation_email($data, &$content)
	{
		// Создание контента
		$content = sprintf($this->lang->auth_activate_content, 
			$this->confi->DX_website_name, 
			$data['activate_url'],
			$this->confi->DX_email_activation_expire / 60 / 60,
			$data['username'], 
			$data['email'],
			$data['password'],
			$this->confi->DX_website_name);
	}
	
    	
	// Отправка сообщения на email забытого пароля
    function sending_forgot_password_email($data, &$content)
	{
		// Создание контента
		$content = sprintf(htmlspecialchars_decode($this->lang->auth_forgot_password_content), 
			$this->confi->DX_website_name, 
			$data['reset_password_uri'],
			$data['password'],
			$data['key'],
			$this->confi->DX_webmaster_email,
			$this->confi->DX_website_name);
	}
}

?>