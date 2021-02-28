<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль pages | Админ
* Страницы
*
**/


class Index extends MY_Admin 
{
    private $needRoute = false; // нужно ли использовать роутер

	function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'auth/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        
        // Полезная информация для каждого поля
        $this->InfoPolya = array(
                                           
            ); 
            
        // Массив полей которые нужно проверять
        $this->ValidPolya = array(
            'username' => 'required', 
            'email' => 'required'
        );         
	}


/** --------------------------------------------------------------------------------------------- **/


	/**
	* Вывод списка данных
	**/
	function index()
	{                                                
        // Задаем поля которые нужно выводить
        $fields = array(                                
            'id' => array(
                'width'=>'30%', 
                'class'=>'', 
                'title'=>'ФИО'
                ),
            'username' => array(
                'width'=>'', 
                'class'=>'hide-on-mobile',
                'title' => ''
                ),
            'email' => array(
                'width'=>'', 
                'class'=>'hide-on-tablet',
                'title' => ''
                ),
            'role_id' => array(
                'width'=>'', 
                'class'=>'',
                'title' => ''
                ),
            'banned' => array(
                'width'=>'5%', 
                'class'=>'checkbox-cell',
                'title' => '<span class="icon-eye underline with-tooltip blue" title="'.$this->alang->admin_53.'"></span>'
                ),        
        );
            
        // опции полей
        $options = $this->paramFields();                                       
        $id_or_link = 'link';
                                
        if($this->input->post('pagination')) // когда отправялем через ajax запрос на следующую партию записей для пагинации
        {            
            $listData = $this->load->module('admin')->list_table(
                $this->table, 
                $this->module, 
                $fields, 
                $options, 
                $id_or_link, 
                $this->input->post('pagination',true)
            );
            $return = $this->admin_view('admin/template/list_table_pagination', $listData, true);
        }
        elseif($this->input->post('search')) // когда идет поиск
        {                        
            $listData = $this->load->module('admin')->list_table(
                $this->table, 
                $this->module, 
                $fields, 
                $options, 
                $id_or_link, 
                0,
                $this->input->post('search',true)
            );
            $return = $this->admin_view('admin/template/list_table_pagination', $listData, true);
        }
        else // если нечего не было отправлено, просто вытаскиваем данные
        {        
            $listData = $this->load->module('admin')->list_table(
                $this->table, 
                $this->module, 
                $fields, 
                $options, 
                $id_or_link                
            );
            
            // Валидация       
            $vls = $this->validate('index/deleteRow','.serial_form','.chekDel','table');                                                                                          
                                            
            $listData['uri'] = $this->uri->uri_string();
            
            $listData['moduleName'] = $this->module; // передаем модуль            
            $listData['moduleTable'] = $this->table; // передпем таблицу
            
            // какая-то полезная информация
            $listData['information'] = '';                        
            
            // объединение данных и валидации
            $listData = array_merge($listData, $vls);
            
            // для неопределенных модулей в которых нет ссылки перехода        
            $listData['captionTable'] = $this->settingModule('title');        
            
            $return = $this->admin_view('admin/list_table', $listData, true);
        }
                
        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($return);
        }
        else
        {
            /* Возвращаем json */
			echo json_encode(array(
				'ok' => $return
			));
        }
	}


/** --------------------------------------------------------------------------------------------- **/        

    
    /**
     *  Добавление записи
    **/
    function add()
    {        
        $model = $this->table.'_model';
        $statusPage = ''; // перенаправление страницы
        $statusPageLink = BASEURL.'/'.$this->module.'/admin/index/edit/';
        
        $this->load->model($this->module.'/admin_'.$model, $model);
        
        if($this->input->post('dani'))
        {
            $mas = $this->parseJson($this->input->post('dani'));                       
             
            $auth_users = array(
                    'username'      => $mas['username'],
                    'email'         => $mas['email'],
                    'password'      => crypt($this->dx_auth->encodepass($mas['password'])),
                    'role_id'       => $mas['role_id'],
                    'banned'        => $mas['banned'],
                    'ban_reason'    => $mas['ban_reason']
            );            
            unset($mas['username'], $mas['email'], $mas['password'], $mas['role_id'], $mas['banned'], $mas['ban_reason'], $mas['id']);
            
            $username_check = $this->_username_check($auth_users['username']);
            $email_check = $this->_email_check($auth_users['email']);
                            
            if($username_check AND $email_check)
            {                                            
                if($this->$model->module_add($auth_users))
                {                    
                    $id = $this->db->insert_id();
                    $statusPage = $statusPageLink.$id;
                    
                    $this->load->model('auth/dx_auth/user_profile','user_prof');
                    $this->user_prof->create_profile($id);
                    $this->user_prof->set_profile($id, $mas);
                    
                    if($this->needRoute)
                    {
                        $array = $this->routeArray($mas['link'], $mas['name']);                
                        $this->load->model('router/router_model','mdRoute');
                        $this->mdRoute->module_add($array);
                    }
                    $return = notification($this->alang->admin_save);                                        
                }
                else
                {
                    $return = notification($this->alang->admin_54,'red');
                }            
            }
            else
            {
                if(!$username_check)
                {
                    $return = notification($this->alang->admin_55,'red');
                }
                
                if(!$email_check)
                {
                    $return = notification($this->alang->admin_56,'red');
                }
                
                if(!$username_check AND !$email_check)
                {
                    $return = notification($this->alang->admin_57,'red');
                }
            }                        
        }       
        else
        {                        
            // Грузим модуль админа и вызываем нужную функцию для формирования вида
            $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, NULL, $this->ValidPolya, $this->paramFields(), $this->InfoPolya);                                                                                                                                                                                          
            $listUserProfile = $this->load->module('admin')->add_edit_data('auth_user_profile', $this->module, NULL, $this->ValidPolya,  $this->paramFields(), $this->InfoPolya);
            
            $listData['table'] = array_merge($listData['table'], $listUserProfile['table']);
                        
            // Передаем все данные и выводим шаблон в переменную для передачи данных
            $return = $this->admin_view('admin/edit_table', $listData, true);                        
        }
                
        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($return);
        }
        else
        {        
            echo json_encode(array(
                'ok' => $return,
                'statusPage' => $statusPage
            ));
        }
    }
    

/** --------------------------------------------------------------------------------------------- **/


    /**
     *  Редактирование
     *  @param id - идентификатор записи
    **/
    function edit($id = '')
    {        
        $statusPage = '';
        $statusPageLink = BASEURL.'/'.$this->module.'/admin/index';
        $model = $this->table.'_model';
        
        // загрузка модели
        $this->load->model($this->module.'/admin_'.$model, $model);
        
        if($this->input->post('dani')) // ловим данные
        {
            if(!$this->input->is_ajax_request()) // проверяем или была отправка ajax`ом
            {
                die();
            }



            $mas = $this->parseJson($this->input->post('dani')); // вытаскиваем данные из POST                                              
            
            if(isset($mas['change_password']))
            {                                        
                $mas['password'] = crypt($this->dx_auth->encodepass($mas['password']));
                $chp = true;
            }
                        
            $id = $mas['id']; // задаем идентификатор для редактирования записи            
            unset($mas['id']); // удаляем ненужные данные                        
            
            $currentLink = $this->$model->module_get($id); // вытаскиваем текущую запись
            
            $auth_users = array(
                    'username'      => $mas['username'],
                    'email'         => $mas['email'],
                    'password'      => $mas['password'],
                    'role_id'       => $mas['role_id'],
                    'banned'        => $mas['banned'],
                    'ban_reason'    => $mas['ban_reason']
            );            
            unset($mas['username'], $mas['email'], $mas['password'], $mas['role_id'], $mas['banned'], $mas['ban_reason'], $mas['change_password']);
            
            $username_check = $this->_username_check($auth_users['username'], $id);
            $email_check = $this->_email_check($auth_users['email'], $id);
                            
            if($username_check AND $email_check)
            {                                            
                // передаем данные в БД и ждем ответа true или false
                if($this->$model->module_edit($id, $auth_users)) // если ответ true
                {                    
                    $statusPage = $statusPageLink;
                    $return = notification($this->alang->admin_save);
                    
                    $this->load->model('auth/dx_auth/user_profile','user_prof');
                    $this->user_prof->set_profile($id, $mas);
                    if(isset($chp))
                    {
                        $this->load->model($this->module.'/dx_auth/user_autologin', 'user_autologin');
                        $this->user_autologin->clear_keys($id);
                    }
                    if($this->needRoute)
                    {
                        $array = $this->routeArray($mas['link'], $mas['name']);                
                        $this->load->model('router/router_model','mdRoute');
                        
                        if($this->mdRoute->module_getValueLink($currentLink['link']))
                        {
                            $this->mdRoute->module_editLink($currentLink['link'], $array);
                        }
                        else
                        {
                            $this->mdRoute->module_add($array);
                        }
                    }
                }
                else // иначе false
                {
                    $return = notification($this->alang->admin_save_error,'red');
                }                    
            }
            else // иначе выдаем сообщение о том что ссылка такая уже существует
            {                        
                if(!$username_check)
                {
                    $return = notification($this->alang->admin_55,'red');
                }
                
                if(!$email_check)
                {
                    $return = notification($this->alang->admin_56,'red');
                }
                
                if(!$username_check AND !$email_check)
                {
                    $return = notification($this->alang->admin_57,'red');
                }                       
            }
        }       
        elseif(!empty($id) and is_numeric($id)) // если идентификатор не пустой и он является цифрой
        {                        
            // вытаскиваем даннные по идентификатору
            $mas = $this->$model->module_get($id);
            
            if($mas != '') // проверяем если ли данные по такому идентификатору
            {
                // Грузим модуль админа и вызываем нужную функцию для формирования вида
                $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, $mas, $this->ValidPolya, $this->paramFields(), $this->InfoPolya);                                                                                                                                                                                                              
                
                $mas_profile = $this->$model->module_get_profile($id);                                             
                $listUserProfile = $this->load->module('admin')->add_edit_data('auth_user_profile', $this->module, $mas_profile, $this->ValidPolya,  $this->paramFields(), $this->InfoPolya);
                unset($listUserProfile['table']['id']);              
                
                $listData['table'] = array_merge($listData['table'], $listUserProfile['table']);
                
                
                // Передаем все данные и выводим шаблон в переменную для передачи данных
                $return = $this->admin_view('admin/edit_table',$listData,true);            
            }
            else // если данных нет то выдаем сообщение
            {
                $return = notification($this->alang->admin_58.' #' .$id. $this->alang->admin_59,'red');
            }
        }
        else // если id не является цифрой или пустой, выводим сообщение ошибки
        {
            $return = notification($this->alang->admin_60,'red');
        }
        
        // получаем данные        
        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($return);
        }
        else
        {        
            echo json_encode(array(
                'ok' => $return,
                'statusPage' => $statusPage
            ));
        }
    }
    
    
/** --------------------------------------------------------------------------------------------- **/


    /**
     *  Одинарное или множественное удаление
    **/
    function deleteRow()
    {                                
        if(!$this->input->is_ajax_request())
        {
            die();
        }        
        
        $checked = ''; // идентификаторы записей
        $ms = ''; // сообщение              
        $status = true; // статус действия после обработки
        $this->load->model('router/router_model','mdRouter'); // подгружаем модель роутера
        
        if($this->input->post('ckecked')) // если передается массив идентификаторов
        {
            $sended = $this->input->post('ckecked',true); // вытаскиваем данные в переменную            
            $action = $sended['action']; // вытаскиваем выбранное действие
            
            if(isset($sended['checked'])) // если существует переменная с массивом значений
            {
                $checked = $sended['checked']; // вытаскиваем данные в переменную
            }
        }
        elseif($this->input->post('deleteId')) // если передается идентификатор на удаление одной записи
        {
            $checked = $this->input->post('deleteId',true); // вытаскиваем данные в переменную           
            $cLink = $this->input->post('deleteLink',true); // вытаскиваем данные в переменную
        } 
                                        
        if(is_array($checked)) // если переменная с идентификатором является массивом
        {
            if($action == 1) // проверяем действие | 1 - удаление
            {                    
                if(count($checked) > 0)  // проверка на то или массив не пустой и имеет более 1-й записи
                {
                    $this->load->model($this->module.'/admin_'.$this->table.'_model','model'); // грузим модель                     
                    $ar = ''; // переменная в которую заносятся данные о том удалена запись или нет                   
                    
                    foreach($checked as $ch) // удаление проходим через цикл для отслеживания удаления
                    {                    
                        $chDel = explode('|',$ch);
                        
                        if($this->model->module_delete($chDel[0])) // выполняем удаление
                        {                                                                
                            
                            if(!empty($chDel[1]) and $this->needRoute)
                            {
                                $this->mdRouter->module_deleteLink($chDel[1]);
                            }
                            $ar[$chDel[0]] = 1; // удалено
                        }
                        else
                        {
                            $ar[$chDel[0]] = 0; // не удалено
                        }
                    }
                }
                
                if(in_array(0,$ar)) // проверяем имеются не удаленные записи в массиве
                {
                    foreach($ar as $k=>$d) // если такие имеются, то проходим по циклу и вытаскиваем их
                    {
                        if($d == 0)
                        {
                            $ms .= $this->alang->admin_58.' #'.$k. $this->alang->admin_61 .'<br />';                        
                        }
                    }
                    
                    $color = 'red';
                }
                else // если все записи успешно удалены
                {
                    $ms = $this->alang->admin_62;   
                    $color = 'green';             
                }
            }
            else // если действие не равно 1 (удаление)
            {                    
                $ms = $this->alang->admin_63;   
                $color = 'red';
                $status = false;
            }                                                    
        }
        else // если переменная с идентификатором не является массивом
        {
            if(!empty($checked) and is_numeric($checked)) // проврка пустоту и является ли цифрой переданный идентификатор
            {
                $this->load->model($this->module.'/admin_'.$this->table.'_model','model'); // грузим модель
            
                if($this->model->module_delete($checked)) // удаляем запись
                {
                    if(!empty($cLink) and $this->needRoute)
                    {
                        $this->mdRouter->module_deleteLink($cLink);
                    }
                    $ms = $this->alang->admin_64;   
                    $color = 'green';
                }
                else
                {
                    $ms = $this->alang->admin_65;   
                    $color = 'red';
                }                    
            }
            else // иначе ошибка
            {                    
                $ms = $this->alang->admin_66;   
                $color = 'red';
                $status = false;
            }                
        }                                            
        
        $data = notification($ms,$color); // вытаскиваем сообщение ответа                        
        
        if($this->needRoute)        
        {
            $this->mdRouter->write_router(); // записываем значения роутера из базы
        }
                        
        // Возвращаем json
        echo json_encode(array(
            'ok' => $data,
            'status' => $status
        ));
    }    
        


/** --------------------------------------------------------------------------------------------- **/

    
    /**
     *  Преобразуем json в нормальные переменные
    **/
    function parseJson($data)
    {
        if(is_array($data))
        {
            foreach($data as $d)
            {
                $sds[$d['name']] = $d['value'];                                                    
            }
        }
        else
        {
            parse_str($data,$sended);                        
                    
            foreach($sended as $key=>$sd)
            {
                $ks = str_replace(';','',$key);
                $sds[$ks] = $sd;
            }
        }
        return $sds;
    }
    
    
/** --------------------------------------------------------------------------------------------- **/

    
    /**
     *  Задаем параметры полей
    **/
    function paramFields()    
    {    
        $this->load->model($this->module.'/dx_auth/roles');
        $this->load->model($this->module.'/dx_auth/user_profile');
        $rol = $this->roles->get_all()->result_object();
        $prof = $this->user_profile->get_all()->result_object();
        
        $role = $this->session->userdata('DX_role_name');
        
        foreach($rol as $r)
        {
            if($role != 'super')
            {
                if($r->name != 'all' AND $r->name != 'super')
                {
                    $role_id[$r->id] = $r->title;
                }
            }
            else
            {
                $role_id[$r->id] = $r->title;
            }
        }
        
        foreach($prof as $r)
        {
            $profile[$r->user_id] = $r->name.' '.$r->last_name;
        }
        
        // Массив параметров для полей
        return array(
                        'banned' => array($this->alang->admin_67, $this->alang->admin_68),
                        'role_id' => $role_id,
                        'id' => $profile
                    ); 
    } 
    
    
/** --------------------------------------------------------------------------------------------- **/

    
    /**
     *  Роутер
    **/
    function routeArray($link, $name)    
    {        
      return  array(
                'key'=>$link,
                'value'=>$this->module,
                'desc'=>$name
            );   
    } 
    

/** ------------------------------------------------------------------------------------------- **/


    /**
     * Подгрузка настроек модуля
    **/        
    function settingModule($set)
    {
        include(MDPATH.$this->module.'/moduleinfo.php');   
        return $moduleinfo[$set];
    }
    
    
/** ------------------------------------------------------------------------------------------- **/


    /**
     * Управление настройками модуля
    **/        
    function config()
    {        
        $model = $this->table.'_model';                
        $this->load->model($this->module.'/admin_'.$model, $model);
        $data = $this->$model->configDataLoadBD();                
            
        if($this->input->post('dani'))
        {
            $mas = $this->parseJson($this->input->post('dani'));            
            
            foreach($mas as $item=>$value)    
            {
                $in_array[] = $item;
                $data['table'][$item]['value'] = $value;
            }                                                                  
            
            if(!in_array('DX_allow_registration', $in_array)) { $data['table']['DX_allow_registration']['value'] =  $mas['DX_allow_registration'] = '0'; }
            if(!in_array('DX_captcha_registration', $in_array)) { $data['table']['DX_captcha_registration']['value'] = $mas['DX_captcha_registration'] = '0'; }
            if(!in_array('DX_captcha_login', $in_array)) { $data['table']['DX_captcha_login']['value'] = $mas['DX_captcha_login'] = '0'; }
            if(!in_array('DX_email_activation', $in_array)) { $data['table']['DX_email_activation']['value'] = $mas['DX_email_activation'] = '0'; }
            if(!in_array('DX_email_account_details', $in_array)) { $data['table']['DX_email_account_details']['value'] = $mas['DX_email_account_details'] = '0'; }
            if(!in_array('DX_count_login_attempts', $in_array)) { $data['table']['DX_count_login_attempts']['value'] = $mas['DX_count_login_attempts'] = '0'; }
            
            if($this->$model->edit_tableInfo(array('configs' => serialize($data)))) // заносим настройки в базу
            {
                $return = configSaveToFile($this->module, $mas); // сохранение настроек в конфигурационный файл
            }
            else
            {
                $return = notification('Настройки не сохранены.', 'red');
            }
            
            echo json_encode(array(
                'ok' => $return
            ));
        }
        else
        {                    
            $data['moduleName'] = $this->module;        
            echo $this->admin_view('admin/config_table', $data);
        }
    }
    
    
    /**
     * Обновление настроек
    **/
    function updateConfig()
    {        
        $model = $this->table.'_model';                
        $this->load->model($this->module.'/admin_'.$model, $model);
        $data = $this->$model->configData();                            
        $dataBD = $this->$model->configDataLoadBD();
        
        if(!isset($dataBD['table'])) { $dataBD['table'] = array(); }
        
        if(count($dataBD['table']) < count($data['table']))
        {
            if(!empty($dataBD['table']))
            {
                foreach($dataBD['table'] as $item=>$value)
                {
                    unset($data['table'][$item]);
                }
            }
            
            $newConfig['table'] = array_merge($dataBD['table'], $data['table']);
        }
        else
        {
            if(!empty($dataBD['table']))
            {
                foreach($dataBD['table'] as $item=>$value)
                {
                    if(isset($data['table'][$item]))
                    {
                        $data['table'][$item]['value'] = $value['value'];
                    }
                }
            }
            
            $newConfig = $data;
        }                
              
        if($this->$model->edit_tableInfo(array('configs' => serialize($newConfig)))) // заносим настройки в базу
        {
            if(!empty($newConfig['table']))
            {
                foreach($newConfig['table'] as $item=>$value)
                {
                    $mas[$item] = $value['value'];
                }
            }
            else
            {
                $mas = '';
            }
            $return = configSaveToFile($this->module, $mas); // сохранение настроек в конфигурационный файл
        }
        else
        {
            $return = notification('Настройки не сохранены.', 'red');
        }
           
        echo $return;        
    }
    

/** ------------------------------------------------------------------------------------------- **/
    
    
    /**
     * Проверка на существование логина
    **/
    function _username_check($username, $id = NULL)
    {
        $this->load->model('auth/admin_auth_users_model', 'module_model');
        if(!$id)
        {
            $result1 = $this->module_model->all_data_where('auth_users',array('username'=>$username));
            $result2 = $this->module_model->all_data_where('auth_user_temp',array('username'=>$username));
        }
        else
        {
            $result1 = $this->module_model->all_data_where('auth_users',array('username'=>$username, 'id !='=>$id));
            $result2 = $this->module_model->all_data_where('auth_user_temp',array('username'=>$username));
        }
        
        $result = count($result1) + count($result2);
        if($result != 0) 
            return false;
        return true;
    }
    
    
    /**
     * Проверка на существование email
    **/
    function _email_check($email, $id = NULL)
    {
        $this->load->model('auth/admin_auth_users_model', 'module_model');
        if(!$id)
        {
            $result1 = $this->module_model->all_data_where('auth_users',array('email'=>$email));
            $result2 = $this->module_model->all_data_where('auth_user_temp',array('email'=>$email));
        }
        else
        {
            $result1 = $this->module_model->all_data_where('auth_users',array('email'=>$email, 'id !='=>$id));
            $result2 = $this->module_model->all_data_where('auth_user_temp',array('email'=>$email));
        }
        
        $result = count($result1) + count($result2);
        if($result != 0)
            return false;
        return true;
    }    
    
}
/* End of file */