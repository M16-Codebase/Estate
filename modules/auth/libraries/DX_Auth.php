<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * DX Auth Class - класс авторизации
 *
 * Authentication library for Code Igniter.
 *
 * @author		Dexcell
 * @version		1.0.6
 * @based on	CL Auth by Jason Ashdown (http://http://www.jasonashdown.co.uk/)
 * @link			http://dexcell.shinsengumiteam.com/dx_auth
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 * @credits		http://dexcell.shinsengumiteam.com/dx_auth/general/credits.html
 * 
 */
 
class DX_Auth extends MX_Controller
{
	private $_banned; // забанен
	private $_ban_reason; // причина бана
	private $_auth_error;	// Ошибки пользователя при входе
	private $_captcha_image; // картинка каптчи
    private $_captcha_images; // картинка каптчи (путь к картинке)
	protected $confi; // настройки
    public $lang; // текст
    public $module; // название модуля
    
	function __construct()
	{
		// конструктор
		parent::__construct();
		        
        $this->module = 'auth';
        
		$this->confi = new stdClass; 
        $foreach = $this->load->config('auth/auth', true);           
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
        $this->confi->DX_reset_password_uri = 'auth/reset_password/';
        $this->confi->DX_login_record_ip = true;
        $this->confi->DX_login_record_time = true;
        
        // Создаем объект языкового файла
        $this->lang = new stdClass;
        $foreach = $this->load->language('auth/auth_mod', '', true);          
        foreach($foreach as $key=>$l)
        {
            // заносим записи языкового файла
            $this->lang->$key = $l['name'];
        }
		
		// подгрузка библиотеки событий
		$this->load->library('auth/DX_Auth_Event');
		
		// инициализация
		$this->_init();
	}

	/* Private function */
    // инициализация настроек из конфига
	function _init()
	{
		// Автологинимся
		//$this->autologin();
		
		// Загружаем конфигурационные файлы
		$this->email_activation = $this->confi->DX_email_activation;
		
		$this->allow_registration = $this->confi->DX_allow_registration;
		$this->captcha_registration = $this->confi->DX_captcha_registration;
		
		$this->captcha_login = $this->confi->DX_captcha_login;
		
		// URIs
		$this->banned_uri = 'auth/banned/';
		$this->deny_uri = 'auth/deny/';
		$this->login_uri = 'auth/login/';
		$this->logout_uri = 'auth/logout/';
		$this->register_uri = 'auth/register/';
		$this->activate_uri = '/auth/activate/';
		$this->forgot_password_uri = 'auth/forgot_password/';
		$this->reset_password_uri = 'auth/reset_password/';
		$this->change_password_uri = 'auth/change_password/';	
		$this->cancel_account_uri = 'auth/cancel_account/';	
		
		// Forms view
		$this->login_view = 'auth/auth/login_form';
		$this->register_view = 'auth/auth/register_form';
		$this->forgot_password_view = 'auth/auth/forgot_password_form';
		$this->change_password_view = 'auth/auth/change_password_form';
		$this->cancel_account_view = 'auth/auth/cancel_account_form';
		
		// Pages view
		$this->deny_view = 'auth/auth/general_message';
		$this->banned_view = 'auth/auth/general_message';
		$this->logged_in_view = 'auth/auth/general_message';
		$this->logout_view = 'auth/auth/general_message';		
		
		$this->register_success_view = 'auth/auth/general_message';
		$this->activate_success_view = 'auth/auth/general_message';
		$this->forgot_password_success_view = 'auth/auth/general_message';
		$this->reset_password_success_view = 'auth/auth/general_message';
		$this->change_password_success_view = 'auth/auth/general_message';
		
		$this->register_disabled_view = 'auth/auth/general_message';
		$this->activate_failed_view = 'auth/auth/general_message';
		$this->reset_password_failed_view = 'auth/auth/general_message';
	}
	
    // генератор пароля
	function _gen_pass($len = 8)
	{
		$pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$str = '';
		for ($i = 0; $i < $len; $i++)
		{
			$str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
		}

		return $str;
	}	

	/*
	* Function: _encode
	* Modified for DX_Auth
	* Original Author: FreakAuth_light 1.1
	*/
    
    // разкодировка если в конфиге указан ключ шифрование DX_salt
	function _encode($password)
	{
		$majorsalt = ''; //$this->config->item('DX_salt');
		
		// if PHP5
		if (function_exists('str_split'))
		{
			$_pass = str_split($password);
		}
		// if PHP4
		else
		{
			$_pass = array();
			if (is_string($password))
			{
				for ($i = 0; $i < strlen($password); $i++)
				{
					array_push($_pass, $password[$i]);
				}
			}
		}

		// encrypts every single letter of the password
		foreach ($_pass as $_hashpass)
		{
			$majorsalt .= md5($_hashpass);
		}

		// encrypts the string combinations of every single encrypted letter
		// and finally returns the encrypted password
		return md5($majorsalt);
	}
    
    
   	function encodepass($password)
	{
		$majorsalt = ''; //$this->config->item('DX_salt');
		
		// if PHP5
		if (function_exists('str_split'))
		{
			$_pass = str_split($password);
		}
		// if PHP4
		else
		{
			$_pass = array();
			if (is_string($password))
			{
				for ($i = 0; $i < strlen($password); $i++)
				{
					array_push($_pass, $password[$i]);
				}
			}
		}

		// encrypts every single letter of the password
		foreach ($_pass as $_hashpass)
		{
			$majorsalt .= md5($_hashpass);
		}

		// encrypts the string combinations of every single encrypted letter
		// and finally returns the encrypted password
		return md5($majorsalt);
	}
	
    // Поиск значения - массив = массив
	function _array_in_array($needle, $haystack) 
	{
    // Задаем масив поиска
    if( ! is_array($needle)) 
		{
			$needle = array($needle);
		}
    
		// Для каждого значения в $needle, возвращаем TRUE в $haystack
    foreach ($needle as $pin)
		{
			if (in_array($pin, $haystack)) 
				return TRUE;
		}
    // Вернуть false если нече не найдено
    return FALSE;
	}

	
    // отправка мыла 
    // кому. от кого. тема. сообщение
	function _email($to, $from, $subject, $message)
	{
		$this->load->library('Email');
		$email = $this->email;

		$email->from($from);
		$email->to($to);
		$email->subject($subject);
		$email->message($message);

		return $email->send();
	}
		
    // заносим в БД IP и дату пользователя который вошел последний раз
	function _set_last_ip_and_last_login($user_id)
	{
		$data = array();
		
        // проверям нужно ли заносить IP в базу
		if ($this->confi->DX_login_record_ip)
		{	
			$data['last_ip'] = $this->input->ip_address();
		}
		
        // проверям нужно ли заносить дату и время входа в базу
		if ($this->confi->DX_login_record_time)
		{
			$data['last_login'] = date('Y-m-d H:i:s', time());
		}
		
		if ( ! empty($data))
		{
			// грузим модель
			$this->load->model('auth/dx_auth/users', 'users');
			// обновляем запись
			$this->users->set_user($user_id, $data);
		}
	}
	
	
    // Запись ошибок авторизации
	function _increase_login_attempt()
	{				
        if ($this->confi->DX_count_login_attempts AND ! $this->is_max_login_attempts_exceeded())
		{
			// модуль
			$this->load->model($this->module.'/dx_auth/login_attempts', 'login_attempts');		
			// обновляем на +1 количество попыток ошибочного входа
			$this->login_attempts->increase_attempt($this->input->ip_address());
		}
	}

    // Очистка ошибок авторизации с БД
	function _clear_login_attempts()
	{	 
        if ($this->confi->DX_count_login_attempts)
		{
			// грузим модель
			$this->load->model($this->module.'/dx_auth/login_attempts', 'login_attempts');		
			// очистка попыток входа с даннго IP
			$this->login_attempts->clear_attempts($this->input->ip_address());
		}
	}
		
	// Получить роль из БД по id, используемых в _set_session() функции
	// $parent_roles_id, $parent_roles_name выходят как массивы.
    
	function _get_role_data($role_id)
	{
		// грузим модель
		$this->load->model($this->module.'/dx_auth/roles', 'roles'); // роли
		$this->load->model($this->module.'/dx_auth/permissions', 'permissions'); // права
	
		// Очистка переменных
		$role_name = '';
        $role_title = '';
		$parent_roles_id = array();
		$parent_roles_name = array();
		$permission = array();
		$parent_permissions = array();
		
		/* Получаем role_name, parent_roles_id и parent_roles_name */
		
		// Получаем роль по запросу id
		$query = $this->roles->get_role_by_id($role_id);
		
		// Проверка на существование роли
		if ($query->num_rows() > 0)
		{
			// получаем строку
			$role = $query->row();		
	
			// получаем имя роли
			$role_name = $role->name;
            $role_title = $role->title;
			
			/* 
				Делаем рекурсию что-бы найти родителя роли
			*/
			
			// Check if role has parent id
			if ($role->parent_id > 0)
			{							
				// Add to result array
				$parent_roles_id[] = $role->parent_id;
				
				// Set variable used in looping
				$finished = FALSE;
				$parent_id = $role->parent_id;				

				// Get all parent id
				while ($finished == FALSE)
				{
					$i_query = $this->roles->get_role_by_id($parent_id);
					
					// If role exist
					if ($i_query->num_rows() > 0)
					{
						// Get row
						$i_role = $i_query->row();
						
						// Check if role doesn't have parent
						if ($i_role->parent_id == 0)
						{
							// Get latest parent name
							$parent_roles_name[] = $i_role->name;
							// Stop looping
							$finished = TRUE;
						}
						else
						{
							// Change parent id for next looping
							$parent_id = $i_role->parent_id;
							
							// Add to result array
							$parent_roles_id[] = $parent_id;
							$parent_roles_name[] = $i_role->name;
						}
					}
					else
					{	
						// Remove latest parent_roles_id since parent_id not found
						array_pop($parent_roles_id);
						// Stop looping
						$finished = TRUE;
					}
				}			
			}
		}
	    /* #Получаем role_name, parent_roles_id и parent_roles_name */
		
        
		/* Получение пользователя и права родителей */		
		// получаем право роли пользователя
		$permission = $this->permissions->get_permission_data($role_id);
		
		// получаем право ролителя 
		if ( ! empty($parent_roles_id))
		{
			$parent_permissions = $this->permissions->get_permissions_data($parent_roles_id);
		}		
		/* #Получение пользователя и права родителей */
		
		// Устанавливаем возвращаемое значение
		$data['role_name'] = $role_name;
        $data['role_title'] = $role_title;
		$data['parent_roles_id'] = $parent_roles_id;
		$data['parent_roles_name'] = $parent_roles_name;
		$data['permission'] = $permission;
		$data['parent_permissions'] = $parent_permissions;
		
		return $data;
	}


	/* Autologin related function */
    // создание автологина
	function _create_autologin($user_id)
	{
		$result = FALSE;
		
		// Если пользователь поставил запомнить меня
		$user = array(
			'key_id' => substr(md5(uniqid(rand().$this->input->cookie($this->config->item('sess_cookie_name')))), 0, 16),
			'user_id' => $user_id
		);
		
		// грузим модель
		$this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');

		// Prune ключей
		$this->user_autologin->prune_keys($user['user_id']);

		if ($this->user_autologin->store_key($user['key_id'], $user['user_id']))
		{
			// Устанавливаем куки для автологина
			$this->_auto_cookie($user);

			$result = TRUE;
		}

		return $result;
	}
    
    
    // Функция автологина
	function autologin()
	{
		$result = FALSE;
		
		if ($auto = $this->input->cookie('autologin') AND ! $this->session->userdata('DX_logged_in'))
		{
			// Получаем данные
			$auto = unserialize($auto);
			
			if (isset($auto['key_id']) AND $auto['key_id'] AND $auto['user_id'])
			{
				// грузим модель				
				$this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');

				// устанавливаем ключ
				$query = $this->user_autologin->get_key($auto['key_id'], $auto['user_id']);								

				if ($result = $query->row())
				{
					// User verified, log them in
					$this->_set_session($result);
					// Renew users cookie to prevent it from expiring
					$this->_auto_cookie($auto);
					
					// Set last ip and last login
					$this->_set_last_ip_and_last_login($auto['user_id']);
					
					$result = TRUE;
				}
			}
		}
		
		return $result;
	}


    // Удаление автологина
	function _delete_autologin()
	{
		if ($auto = $this->input->cookie('autologin'))
		{
			// Load Cookie Helper
			$this->load->helper('cookie');

			// грузим модель
			$this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');

			// Extract data
			$auto = unserialize($auto);			

			// Delete db entry
			$this->user_autologin->delete_key($auto['key_id'], $auto['user_id']);

			// Make cookie expired
			set_cookie($this->confi->DX_autologin_cookie_name, '', -1);
		}
	}

    function _delete_autologin_all($user){
        $this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');
    }
    
    // Установка сессии
	function _set_session($data)
	{
		$this->load->model($this->module.'/dx_auth/user_profile','main_model');
        // Get role data
		$role_data = $this->_get_role_data($data->role_id);
                 
        $profile = $this->main_model->get_data($data->id);
		// устанавливаем массив значений в сессию
		$user = array(						
			'DX_user_id'						=> $data->id, // идентификатор
			'DX_username'						=> $data->username, // Имя
            'DX_first_name'                     => $profile->name, // Фамилия
            'DX_last_name'                      => $profile->last_name, // Отчество
            'DX_photo'                          => $profile->avatar, // Ава
			'DX_role_id'						=> $data->role_id,	// идентификатор роли	
			'DX_role_name'					    => $role_data['role_name'], // имя роли
            'DX_role_title'					    => $role_data['role_title'], // имя роли
			'DX_parent_roles_id'		        => $role_data['parent_roles_id'],	// массив идентификаторов ролей родителей
			'DX_parent_roles_name'	            => $role_data['parent_roles_name'], // массив названий ролей родителей
			'DX_permission'					    => $role_data['permission'], // права
			'DX_parent_permissions'	            => $role_data['parent_permissions'], // права родителей			
			'DX_logged_in'					    => TRUE // авторизован
		);

		$this->session->set_userdata($user);
	}
    
    // Установка кук
	function _auto_cookie($data)
	{
		// Load Cookie Helper
		$this->load->helper('cookie');

		$cookie = array(
			'name' 		=> $this->confi->DX_autologin_cookie_name,
			'value'		=> serialize($data),
			'expire'	=> $this->confi->DX_autologin_cookie_life
		);

		set_cookie($cookie);
	}

	/* #Auto login related function */
	
    
	/* Helper function */
	
    // проверка прав доступа
	function check_uri_permissions($allow = TRUE)
	{
		// Проверяем прошла ли авторизация
		if ($this->is_logged_in())
		{
			// если пользователь не админ
			if ( ! $this->is_admin())
			{
				// получаем текущий урл
				$controller = '/'.$this->uri->rsegment(1).'/';                
				if ($this->uri->rsegment(2) != '')
				{
					$action = $controller.$this->uri->rsegment(2).'/';
				}
				else
				{
					$action = $controller.'index/';
				}
				
				// Get URI permissions from role and all parents
				// Note: URI permissions is saved in 'uri' key
				$roles_allowed_uris = $this->get_permissions_value('uri');
				
				// Variable to determine if URI found
				$have_access = ! $allow;
				// Loop each roles URI permissions
				foreach ($roles_allowed_uris as $allowed_uris)
				{										
					if ($allowed_uris != NULL)
					{
						// Check if user allowed to access URI
						if ($this->_array_in_array(array('/', $controller, $action), $allowed_uris))
						{
								$have_access = $allow;
								// Stop loop
								break;
						}
					}
				}
				
				// Trigger event
				$this->dx_auth_event->checked_uri_permissions($this->get_user_id(), $have_access);
				
				if ( ! $have_access)
				{
					// User didn't have previlege to access current URI, so we show user 403 forbidden access
					$this->deny_access();
				}
                
                //redirect();				
			}
            
            
            
		}
		else
		{			
            // User haven't logged in, so just redirect user to login page
			$this->session->set_userdata('url', $this->uri->uri_string());
            $this->deny_access('login');
		}
	}
    
	
	/*
		Get permission value from specified key.
		Call this function only when user is logged in already.
		$key is permission array key (Note: permissions is saved as array in table).
		If $check_parent is TRUE means if permission value not found in user role, it will try to get permission value from parent role.
		Returning value if permission found, otherwise returning NULL
	*/
	function get_permission_value($key, $check_parent = TRUE)
	{
		// Default return value
		$result = NULL;
	   
		// Get current user permission
		$permission = $this->session->userdata('DX_permission');
		
		// Check if key is in user permission array
		if (array_key_exists($key, $permission))
		{
			$result = $permission[$key];
		}
		// Key not found
		else
		{
			if ($check_parent)
			{
				// Get current user parent permissions
				$parent_permissions = $this->session->userdata('DX_parent_permissions');
				
				// Check parent permissions array				
				foreach ($parent_permissions as $permission)
				{
					if (array_key_exists($key, $permission))
					{
						$result = $permission[$key];
						break;
					}
				}
			}
		}
		
		// Trigger event
		$this->dx_auth_event->got_permission_value($this->get_user_id(), $key);
		return $result;
	}
	
	/*
		Get permissions value from specified key.
		Call this function only when user is logged in already.
		This will get user permission, and it's parents permissions.
				
		$array_key = 'default'. Array ordered using 0, 1, 2 as array key.
		$array_key = 'role_id'. Array ordered using role_id as array key.
		$array_key = 'role_name'. Array ordered using role_name as array key.
		
		Returning array of value if permission found, otherwise returning NULL.
	*/
	function get_permissions_value($key, $array_key = 'default')
	{
		$result = array();
		
		$role_id = $this->session->userdata('DX_role_id');
		$role_name = $this->session->userdata('DX_role_name');
		
		$parent_roles_id = $this->session->userdata('DX_parent_roles_id');
		$parent_roles_name = $this->session->userdata('DX_parent_roles_name');
		
		// Get current user permission
		$value = $this->get_permission_value($key, FALSE);
		
		if ($array_key == 'role_id')
		{
			$result[$role_id] = $value;
		}
		elseif ($array_key == 'role_name')
		{
			$result[$role_name] = $value;
		}
		else
		{
			array_push($result, $value);
		}
		
		// Get current user parent permissions
		$parent_permissions = $this->session->userdata('DX_parent_permissions');
		
		$i = 0;
		foreach ($parent_permissions as $permission)
		{
			if (array_key_exists($key, $permission))
			{
				$value = $permission[$key];
			}
			
			if ($array_key == 'role_id')
			{
				// It's safe to use $parents_roles_id[$i] because array order is same with permission array
				$result[$parent_roles_id[$i]] = $value;
			}
			elseif ($array_key == 'role_name')
			{
				// It's safe to use $parents_roles_name[$i] because array order is same with permission array
				$result[$parent_roles_name[$i]] = $value;
			}			
			else
			{
				array_push($result, $value);
			}
			
			$i++;
		}
		
		// Trigger event
		$this->dx_auth_event->got_permissions_value($this->get_user_id(), $key);
		
		return $result;
	}
    
    // проверка доступа когда пройдена авторизация
	function deny_access($uri = 'deny')
	{        
        $this->load->helper('url');
	
		if ($uri == 'login')
		{
			redirect('auth/login/', 'location');
		}
		else if ($uri == 'banned')
		{
			redirect('auth/banned/', 'location');
		}
		else
		{
			redirect('auth/deny/', 'location');			
		}
		exit;
	}
    
    

	// Получить id
	function get_user_id()
	{
		return $this->session->userdata('DX_user_id');
	}

	// получить имя
	function get_username()
	{	   
		return $this->session->userdata('DX_username');
	}
    
    // получить фамилию
	function get_firstname()
	{	   
		return $this->session->userdata('DX_first_name');
	}
    
    // получить отчество
	function get_lastname()
	{	   
		return $this->session->userdata('DX_last_name');
	}
	
	// получить id роли
	function get_role_id()
	{
		return $this->session->userdata('DX_role_id');
	}
	
	// получить имя роли
	function get_role_name()
	{
		return $this->session->userdata('DX_role_name');
	}
    
    // получить имя роли
	function get_role_title()
	{
		return $this->session->userdata('DX_role_title');
	}
	
	// проверить является ли пользователь админом
	function is_admin()
	{
		if($this->session->userdata('DX_role_name') == 'super' or $this->session->userdata('DX_role_name') == 'admin')
        {
            return true;
        }        
        else
            return false;
	}

    // проверить является ли пользователь админом
    function is_assignment()
    {
        if($this->session->userdata('DX_role_name') == 'assignment' or $this->session->userdata('DX_role_name') == 'super')
        {
            return true;
        }
        else
            return false;
    }
    
    // проверить является ли пользователь супер админом
	function is_super()
	{
		return strtolower($this->session->userdata('DX_role_name')) == 'super';
	}
	
	// Check if user has $roles privilege
	// If $use_role_name TRUE then $roles is name such as 'admin', 'editor', 'etc'
	// else $roles is role_id such as 0, 1, 2
	// If $check_parent is TRUE means if roles not found in user role, it will check if user role parent has that roles
	function is_role($roles = array(), $use_role_name = TRUE, $check_parent = TRUE)
	{
		// Default return value
		$result = FALSE;
	
		// Build checking array
		$check_array = array();
		
		if ($check_parent)
		{
			// Add parent roles into check array
			if ($use_role_name)
			{
				$check_array = $this->session->userdata('DX_parent_roles_name');
			}
			else
			{
				$check_array = $this->session->userdata('DX_parent_roles_id');
			}
		}
		
		// Add current role into check array
		if ($use_role_name)
		{
			array_push($check_array, $this->session->userdata('DX_role_name'));
		}
		else
		{
			array_push($check_array, $this->session->userdata('DX_role_id'));
		}
		
		// If $roles not array then we add it into an array
		if ( ! is_array($roles))
		{
			$roles = array($roles);
		}
		
		if ($use_role_name)
		{
			// Convert check array into lowercase since we want case insensitive checking
			for ($i = 0; $i < count($check_array); $i++)
			{
				$check_array[$i] = strtolower($check_array[$i]);
			}
		
			// Convert roles into lowercase since we want insensitive checking
			for ($i = 0; $i < count($roles); $i++)
			{
				$roles[$i] = strtolower($roles[$i]);
			}
		}
		
		// Check if roles exist in check_array
		if ($this->_array_in_array($roles, $check_array))
		{
			$result = TRUE;
		}
		
		return $result;
	}

	// Проверка на авторизацию
	function is_logged_in()
	{
		return $this->session->userdata('DX_logged_in');
	}

	// проверка на бан
	function is_banned()
	{
		return $this->_banned;
	}
	
	// сообщение почему забанен
	function get_ban_reason()
	{
		return $this->_ban_reason;
	}
	
	// Проверка на существования логина в базе
	function is_username_available($username)
	{		
        // грузим модель
		$this->load->model('auth/dx_auth/users', 'users');
		$this->load->model('auth/dx_auth/user_temp', 'user_temp');

		$users = $this->users->check_username($username);
		$temp = $this->user_temp->check_username($username);
		
		return $users->num_rows() + $temp->num_rows() == 0;
	}
	
	// Проверка на существование email в базе
	function is_email_available($email)
	{
		// грузим модель
		$this->load->model('auth/dx_auth/users', 'users');
		$this->load->model('auth/dx_auth/user_temp', 'user_temp');

		$users = $this->users->check_email($email);
		$temp = $this->user_temp->check_email($email);
		
		return $users->num_rows() + $temp->num_rows() == 0;
	}			
	
	// Проверка количества ошибок входа
	function is_max_login_attempts_exceeded()
	{
		$this->load->model('auth/dx_auth/login_attempts', 'login_attempts');
		
		return ($this->login_attempts->check_attempts($this->input->ip_address())->num_rows() >= 5);
	}
	
    // Возвращает сообщения об ошибки когда функции login(), forgot_password(), change_password(), cancel_account() возвращают FALSE.
	function get_auth_error()
	{
		return $this->_auth_error;
	}
	
	/* #Helper function */
	
    
	/* Main function */		
    // Авторизация
	function login($login, $password, $remember = FALSE)
	{
		// грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		$this->load->model($this->module.'/dx_auth/user_temp', 'user_temp');
		$this->load->model($this->module.'/dx_auth/login_attempts', 'login_attempts');
			
		// Default return value
		$result = FALSE;
				
		if ( ! empty($login) AND ! empty($password))
		{
			// Get which function to use based on config
			/*if ($this->confi->DX_login_using_username AND $this->confi->DX_login_using_email)
			{*/
				$get_user_function = 'get_login';
			/*}
			else if ($this->confi->DX_login_using_email)
			{
				$get_user_function = 'get_user_by_email';
			}			
			else
			{
				$get_user_function = 'get_user_by_username';
			}*/
		
			// Get user query
			if ($query = $this->users->$get_user_function($login) AND $query->num_rows() == 1)
			{
				// Get user record
				$row = $query->row();

				// Check if user is banned or not
				if ($row->banned > 0)
				{
					// Set user as banned
					$this->_banned = TRUE;					
					// Set ban reason
					$this->_ban_reason = $row->ban_reason;
				}
				// If it's not a banned user then try to login
				else
				{					
					$password = $this->_encode($password);
					$stored_hash = $row->password;

					// Is password matched with hash in database ?
					if (crypt($password, $stored_hash) === $stored_hash)
					{
						// Log in user 
						$this->_set_session($row); 												
						
						if ($row->newpass)
						{
							// Clear any Reset Passwords
							$this->users->clear_newpass($row->id); 
						}
						
						if ($remember)
						{
							// Create auto login if user want to be remembered
							$this->_create_autologin($row->id);
						}						
						
						// Set last ip and last login
						$this->_set_last_ip_and_last_login($row->id);
						// Clear login attempts
						$this->_clear_login_attempts();
						
						// Trigger event
						$this->dx_auth_event->user_logged_in($row->id);

						// Set return value
						$result = TRUE;
					}
					else						
					{
						// Increase login attempts
						$this->_increase_login_attempt();
						// Set error message
						$this->_auth_error = $this->lang->auth_login_incorrect_password;
					}
				}				
			}
			// Check if login is still not activated
			elseif ($query = $this->user_temp->$get_user_function($login) AND $query->num_rows() == 1)
			{
				// Set error message
				$this->_auth_error = $this->lang->auth_not_activated;
			}
			else
			{				
                // Increase login attempts
				$this->_increase_login_attempt();
				// Set error message
				$this->_auth_error = $this->lang->auth_login_username_not_exist;
			}
		}

		return $result;
	}

    // Выход
	function logout()
	{
		// Trigger event
		$this->dx_auth_event->user_logging_out($this->session->userdata('DX_user_id'));
	
		// Delete auto login
		if ($this->input->cookie($this->confi->DX_autologin_cookie_name)) {
			$this->_delete_autologin();
		}
		
		// Destroy session
		$this->session->sess_destroy();		
	}
    
    // Регистрация
	function register($username, $password, $email, $mas = NULL)
	{				        
        // грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		$this->load->model($this->module.'/dx_auth/user_temp', 'user_temp');
		
		// Возвращаемое значение по умолчанию
		$result = FALSE;

		// Создаем массив с данными для нового пользователя
		$new_user = array(			
			'username'				=> $username,			
			'password'				=> crypt($this->_encode($password)),
			'email'					=> $email,
			'last_ip'				=> $this->input->ip_address()
		);
               
		// Проверяем нужно ли отправлять письмо для активации пользователя
		if ($this->confi->DX_email_activation)
		{
			if($mas != NULL)
            {
                $new_user['profile_data'] = serialize($mas);
            }
            
            // добавляем в массив уникальный ключ для активации
			$new_user['activation_key'] = md5(rand().microtime());
			
			// Заносим пользовательские данные во временную таблицу.
			$insert = $this->user_temp->create_temp($new_user);
		}
		else // если активация отключена
		{				
			// Создаем пользователя
			$insert = $this->users->create_user($new_user);
			
            // Событие после создания юзера                        
			$this->dx_auth_event->user_activated($this->db->insert_id(), $mas);				
		}
		
		if ($insert)
		{
			// Берем пароль для отправки на email
			$new_user['password'] = $password;
			
			$result = $new_user;
			
			// Отправляем письмо по настроке конфигурации
		
			// если нужна активация
			if ($this->confi->DX_email_activation)
			{
				// берем email указанный в настроке
				$from = $this->confi->DX_webmaster_email;
                // вытаскиваем тему | активация
				$subject = sprintf($this->lang->auth_activate_subject, $this->confi->DX_website_name);

				// Ссылка для активации
				$new_user['activate_url'] = site_url($this->confi->DX_activate_uri."{$new_user['username']}/{$new_user['activation_key']}");
				
				// Событие отпрвки сообщения
				$this->dx_auth_event->sending_activation_email($new_user, $message);

				// Send email with activation link
				$this->_email($email, $from, $subject, $message);
			}
			else
			{
				// Check if need to email account details						
				if ($this->confi->DX_email_account_details) 
				{
					// Create email
					$from = $this->confi->DX_webmaster_email;
					$subject = sprintf($this->lang->auth_account_subject, $this->confi->DX_website_name); 
					
					// Trigger event and get email content
					$this->dx_auth_event->sending_account_email($new_user, $message);

					// Send email with account details
					$this->_email($email, $from, $subject, $message);														
				}
			}
		}
		
		return $result;
	}
    
    // Восстановление пароля
	function forgot_password($login)
	{
		// Default return value
		$result = FALSE;
	
		if ($login)
		{
			// Load Model
			$this->load->model($this->module.'/dx_auth/users', 'users');						

			// Get login and check if it's exist 
			if ($query = $this->users->get_login($login) AND $query->num_rows() == 1)
			{
				// Get User data
				$row = $query->row();
				
				// Check if there is already new password created but waiting to be activated for this login
				if ( ! $row->newpass_key)
				{
					// Appearantly there is no password created yet for this login, so we create new password
					$data['password'] = $this->_gen_pass();
					
					// Encode & Crypt password
					$encode = crypt($this->_encode($data['password'])); 

					// Create key
					$data['key'] = md5(rand().microtime());

					// Create new password (but it haven't activated yet)
					$this->users->newpass($row->id, $encode, $data['key']);

					// Create reset password link to be included in email
					$data['reset_password_uri'] = site_url($this->confi->DX_reset_password_uri."{$row->username}/{$data['key']}");
					
					// Create email
					$from = $this->confi->DX_webmaster_email;
					$subject = $this->lang->auth_forgot_password_subject; 
					
					// Trigger event and get email content
					$this->dx_auth_event->sending_forgot_password_email($data, $message);

					// Send instruction email
					$this->_email($row->email, $from, $subject, $message);
					
					$result = TRUE;
				}
				else
				{
					// There is already new password waiting to be activated
					$this->_auth_error = $this->lang->auth_request_sent;
				}
			}
			else
			{
				$this->_auth_error = $this->lang->auth_username_or_email_not_exist;
			}
		}
		
		return $result;
	}
    
    // Сброс пароля
	function reset_password($username, $key = '')
	{
		// грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		$this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');
		
		// Default return value
		$result = FALSE;
		
		// Default user_id set to none
		$user_id = 0;
		
		// Get user id
		if ($query = $this->users->get_user_by_username($username) AND $query->num_rows() == 1)
		{
			$user_id = $query->row()->id;
			
			// Try to activate new password
			if ( ! empty($username) AND ! empty($key) AND $this->users->activate_newpass($user_id, $key) AND $this->db->affected_rows() > 0 )
			{
				// Clear previously setup new password and keys
				$this->user_autologin->clear_keys($user_id);
				
				$result = TRUE;
			}
		}
		return $result;
	}
    
    // Активация
	function activate($username, $key = '')
	{		
		// грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		$this->load->model($this->module.'/dx_auth/user_temp', 'user_temp');
		
		// возвращаемое значение по умолчанию
		$result = FALSE;
				
		if ($this->confi->DX_email_activation)
		{
			// удаляем аккаунты в которых истек срок активации
			$this->user_temp->prune_temp();
		}

		// Активация пользователя
		if ($query = $this->user_temp->activate_user($username, $key) AND $query->num_rows() > 0)
		{
			// Получаем пользователя
			$row = $query->row_array();
            
            // вытаскиваем идентификатор записи
			$del = $row['id'];
            // вытаскиваем данные профиля
            $profile_data = unserialize($row['profile_data']);

			// Удаляем из массива значения
			unset($row['id']); 
			unset($row['activation_key']);
            unset($row['profile_data']);

			// Создание пользователя
			if ($this->users->create_user($row))
			{
				// Событие полсе создания пользователя
				$this->dx_auth_event->user_activated($this->db->insert_id(), $profile_data);	
				
				// Удаляем пользователя с временной таблицы
				$this->user_temp->delete_user($del);		
								
				$result = TRUE;
			}
		}

		return $result;
	}
    
    // Смена пароля
	function change_password($old_pass, $new_pass)
	{
		// грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		
		// значение по умолчанию
		$result = FAlSE;

		// Ищем пользователя в БД
		if ($query = $this->users->get_user_by_id($this->session->userdata('DX_user_id')) AND $query->num_rows() > 0)
		{
			// Вытаскиваем данные
			$row = $query->row();

			$pass = $this->_encode($old_pass);

			// Проверяем на корректность старый пароль
			if (crypt($pass, $row->password) === $row->password)
			{
				// Кодируем новый пароль
				$new_pass = crypt($this->_encode($new_pass));
				
				// Заменяем старый пароль на новый
				$this->users->change_password($this->session->userdata('DX_user_id'), $new_pass);
				
				// Выполняем триггер
				$this->dx_auth_event->user_changed_password($this->session->userdata('DX_user_id'), $new_pass);

                $this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');
                $this->user_autologin->clear_keys($this->session->userdata('DX_user_id'));

				$result = TRUE;
                $this->_delete_autologin();
			}
			else 
			{
				$this->_auth_error = $this->lang->auth_incorrect_old_password;
			}
		}
		
		return $result;
	}
	
    // Удаление аккаунта
	function cancel_account($password)
	{
		// грузим модель
		$this->load->model($this->module.'/dx_auth/users', 'users');
		
		// Default return value
		$result = FAlSE;
		
		// Search current logged in user in database
		if ($query = $this->users->get_user_by_id($this->session->userdata('DX_user_id')) AND $query->num_rows() > 0)
		{
			// Get current logged in user
			$row = $query->row();

			$pass = $this->_encode($password);

			// Check if password correct
			if (crypt($pass, $row->password) === $row->password)
			{
				// Trigger event
				$this->dx_auth_event->user_canceling_account($this->session->userdata('DX_user_id'));

				// Delete user
				$result = $this->users->delete_user($this->session->userdata('DX_user_id'));
				
				// Force logout
				$this->logout();
			}
			else
			{
				$this->_auth_error = $this->lang->auth_incorrect_password;
			}
		}
		
		return $result;
	}
	
	/* End of main function */
	
	/* Captcha related function */
    // Создание каптчи
	function captcha()
	{
		$this->load->plugin('auth/dx_captcha');
		
		$captcha_dir = trim('./asset/captcha/', './');

		$vals = array(
			'img_path'	 	=> './'.$captcha_dir.'/',
			'img_url'			=> BASEURL.'/'.$captcha_dir.'/',
			'font_path'	 	=> './asset/captcha/fonts',
			'font_size'  	=> 12,
			'img_width'	 	=> 115,
			'img_height' 	=> 32,
			'show_grid'	 	=> 0,
			'expiration' 	=> 180
		);
        
        // генерируем каптчу		
		$cap = create_captcha($vals);

		$store = array(
			'captcha_word' => $cap['word'],
			'captcha_time' => $cap['time']
		);

        
        $data_session = array(); // Создаем масив 
        $data_session['captcha_num'] = $cap['word']; // Заносим в масив сгенерированное значение        
        $this->session->set_userdata($data_session); // Записываем масив в сессию СІ
        
		// Plain, simple but effective
		$this->session->set_flashdata($store);

		// Set our captcha
		$this->_captcha_image = $cap['image'];
        
        $this->_captcha_images = $cap['images'];
	}
	
    // Получение каптчи
	function get_captcha_image()
	{
		return $this->_captcha_image;
	}
    
    function get_captcha_images()
	{
		return $this->_captcha_images;
	}
	
	// если истекло время жизни каптчи
	// Use this in callback function in your form validation
	function is_captcha_expired()
	{
		// Captcha Expired
		list($usec, $sec) = explode(" ", microtime());
		$now = ((float)$usec + (float)$sec);	
		
		// Check if captcha already expired
		return (($this->session->flashdata('captcha_time') + $this->confi->DX_captcha_expire) < $now);						
	}
	
	// Check is captcha match with code
	// Use this in callback function in your form validation
	function is_captcha_match($code)
	{
		if ($this->confi->DX_captcha_case_sensitive)
		{
			// Just check if code is the same value with flash data captcha_word which created in captcha() function		
			$result = ($code == $this->session->flashdata('captcha_word'));
		}
		else
		{
			$result = strtolower($code) == strtolower($this->session->flashdata('captcha_word'));
		}
		
		return $result;
	}		
	
	/* End of captcha related function */
	
	/* Recaptcha function */		
	/*	
	function get_recaptcha_reload_link($text = 'Get another CAPTCHA')
	{
		return '<a href="javascript:Recaptcha.reload()">'.$text.'</a>';
	}
		
	function get_recaptcha_switch_image_audio_link($switch_image_text = 'Get an image CAPTCHA', $switch_audio_text = 'Get an audio CAPTCHA')
	{
		return '<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">'.$switch_audio_text.'</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">'.$switch_image_text.'</a></div>';
	}
	
	function get_recaptcha_label($image_text = 'Enter the words above', $audio_text = 'Enter the numbers you hear')
	{
		return '<span class="recaptcha_only_if_image">'.$image_text.'</span>
			<span class="recaptcha_only_if_audio">'.$audio_text.'</span>';
	}
	
	// Get captcha image
	function get_recaptcha_image()
	{
		return '<div id="recaptcha_image"></div>';
	}
	
	// Get captcha input box 
	// IMPORTANT: You should at least use this function when showing captcha even for testing, otherwise reCAPTCHA image won't show up
	// because reCAPTCHA javascript will try to find input type with id="recaptcha_response_field" and name="recaptcha_response_field"
	function get_recaptcha_input()
	{
		return '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';
	}
	
	// Get recaptcha javascript and non javasript html
	// IMPORTANT: you should put call this function the last, after you are using some of get_recaptcha_xxx function above.
	function get_recaptcha_html()
	{
		// Load reCAPTCHA helper function
		$this->load->helper('recaptcha');
		
		// Add custom theme so we can get only image
		$options = "<script>
			var RecaptchaOptions = {
				 theme: 'custom',
				 custom_theme_widget: 'recaptcha_widget'
			};
			</script>";					
			
		// Get reCAPTCHA javascript and non javascript HTML
		$html = recaptcha_get_html($this->config->item('DX_recaptcha_public_key'));
		
		return $options.$html;
	}
	
	// Check if entered captcha code match with the image.
	// Use this in callback function in your form validation
	function is_recaptcha_match()
	{
		$this->load->helper('recaptcha');
		
		$resp = recaptcha_check_answer($this->config->item('DX_recaptcha_private_key'),
			$_SERVER["REMOTE_ADDR"],				
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]);
			
		return $resp->is_valid;
	}
	*/	
	/* End of Recaptcha function */
}

?>