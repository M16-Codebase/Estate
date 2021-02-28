<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль pages | Админ
* Страницы
*
**/


class roles extends MY_Admin 
{
    private $needRoute = false; // нужно ли использовать роутер

	function __construct()
	{
		// конструктор
		parent::__construct();
                       
        $this->module = 'auth';
        $this->table = 'auth_roles';
        
        // Полезная информация для каждого поля
        $this->InfoPolya = array(
            'name' => $this->alang->admin_69,
            'title' => $this->alang->admin_70                                   
        ); 
            
        // Массив полей которые нужно проверять
        $this->ValidPolya = array(
            'name' => 'required',
            'title' => 'required'
        );  
                
        // Вытаскиваем модули для корректировки на них прав
        $this->load->model($this->module.'/admin_auth_roles_model','model_modules');                
        
        $lng = $this->session->userdata('languages'); // передаем язык в переменную            
        if($lng == 'english') { $prfx = '_en'; } else { $prfx = ''; }
        
        if($lng == 'english')
        {
            $md['auth'] = 'Members';
            $md['pages'] = 'Pages';
            $md['menu'] = 'Navigation';
            $md['shortcode'] = 'Shortcode';
        }
        else
        {  
            $md['auth'] = 'Пользователи';
            $md['pages'] = 'Страницы';
            $md['menu'] = 'Навигация';
            $md['shortcode'] = 'Шорткод';
        }
        
        
        $mdl = $this->model_modules->all_data_where('module',array());
        foreach($mdl as $m)
        {
            $md[$m['link']] = $m['title'.$prfx];
        }
                
        $this->table_permisions = $md;       
	}


/** --------------------------------------------------------------------------------------------- **/


	/**
	* Вывод списка данных
	**/
	function index()
	{                                                
        // Задаем поля которые нужно выводить
        $fields = array(                                            
            'title' => array(
                'width'=>'', 
                'class'=>'',
                'title' => ''
                )        
        );
            
        // опции полей
        $options = $this->paramFields();                                       
        $id_or_link = 'id';
                                
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
            
            $return = $this->admin_view('admin/list_table_roles', $listData, true);
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
        $statusPageLink = BASEURL.'/'.$this->module.'/admin/roles/edit/';
        
        $this->load->model($this->module.'/admin_'.$model, $model);
        
        if($this->input->post('dani'))
        {
            $mas = $this->parseJson($this->input->post('dani'));                       
            
            // Вытаскиваем права доступа
            foreach($mas as $k=>$v)
            {
                $ex = explode('-',$k);
                if($ex[0] == 'table')
                {                    
                    $prm[$ex[1]] = array();                    
                    foreach($mas as $key=>$item)
                    {
                         $ex_p = explode('-',$key);
                         if($ex_p[1] == $ex[1])
                         {
                            if($ex_p[0] != 'table')
                            {
                                $prm[$ex[1]][$ex_p[0]] = $item;
                            } 
                         }
                    }
                }
            }
            $prm['admin']['edit'] = 1;
            
            $newMas['name'] = $mas['name'];
            $newMas['title'] = $mas['title'];
            $newMas['permissions'] = serialize($prm);
            if(isset($mas['id'])) {$newMas['id'] = $mas['id'];}            
            
            $mas = array();
            $mas = $newMas;
            
            $rolename_check = $this->_rolename_check($mas['name']);
                            
            if($rolename_check)
            {                                            
                if($this->$model->module_add($mas))
                {                    
                    $id = $this->db->insert_id();
                    $statusPage = $statusPageLink.$id;
                    $return = notification($this->alang->admin_save);                                        
                }
                else
                {
                    $return = notification($this->alang->admin_54,'red');
                }            
            }
            else
            {
                $return = notification($this->alang->admin_71,'red');
            }                        
        }       
        else
        {                        
            // Грузим модуль админа и вызываем нужную функцию для формирования вида
            $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, NULL, $this->ValidPolya, $this->paramFields(), $this->InfoPolya);                                                                                                                                                                                                   
            
            // Модули для которых применяются права
            $listData['table_permisions'] = $this->table_permisions;
            
            // Передаем все данные и выводим шаблон в переменную для передачи данных
            $return = $this->admin_view('admin/edit_table_roles', $listData, true);                        
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
        $statusPageLink = BASEURL.'/'.$this->module.'/admin/roles/index';
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
            
            // Вытаскиваем права доступа
            foreach($mas as $k=>$v)
            {
                $ex = explode('-',$k);
                if($ex[0] == 'table')
                {                    
                    $prm[$ex[1]] = array();                    
                    foreach($mas as $key=>$item)
                    {
                         $ex_p = explode('-',$key);
                         if($ex_p[1] == $ex[1])
                         {
                            if($ex_p[0] != 'table')
                            {
                                $prm[$ex[1]][$ex_p[0]] = $item;
                            } 
                         }
                    }
                }
            }
            $prm['admin']['edit'] = 1;
            
            $newMas['name'] = $mas['name'];
            $newMas['title'] = $mas['title'];
            $newMas['permissions'] = serialize($prm);
            if(isset($mas['id'])) {$newMas['id'] = $mas['id'];}            
            
            $mas = array();
            $mas = $newMas;
            
            $id = $mas['id']; // задаем идентификатор для редактирования записи            
            unset($mas['id']); // удаляем ненужные данные                        

            $rolename_check = $this->_rolename_check($mas['name'], $id);
                            
            if($rolename_check)
            {                                            
                // передаем данные в БД и ждем ответа true или false
                if($this->$model->module_edit($id, $mas)) // если ответ true
                {                    
                    $statusPage = $statusPageLink;
                    $return = notification($this->alang->admin_save);
                }
                else // иначе false
                {
                    $return = notification($this->alang->admin_save_error,'red');
                }                    
            }
            else // иначе выдаем сообщение о том что ссылка такая уже существует
            {                        
                $return = notification($this->alang->admin_71,'red');                                      
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
                
                // Права доступа для конкретной роли 
                $listData['prm'] = unserialize($mas['permissions']);
                
                // Модули для которых применяются права
                $listData['table_permisions'] = $this->table_permisions;
                
                // Передаем все данные и выводим шаблон в переменную для передачи данных
                $return = $this->admin_view('admin/edit_table_roles', $listData, true);            
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
            die('Не верно передан запрос!');
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
                            $ms .= $this->alang->admin_58.' #'.$k.$this->alang->admin_61.'<br />';                        
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
            //$this->mdRouter->write_router(); // записываем значения роутера из базы
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
        // Массив параметров для полей
        return array(
                        
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
                $data[$item]['value'] = $value;
            }        
            
            if(!in_array('DX_allow_registration', $in_array)) { $data['DX_allow_registration']['value'] =  $mas['DX_allow_registration'] = '0'; }
            if(!in_array('DX_captcha_registration', $in_array)) { $data['DX_captcha_registration']['value'] = $mas['DX_captcha_registration'] = '0'; }
            if(!in_array('DX_captcha_login', $in_array)) { $data['DX_captcha_login']['value'] = $mas['DX_captcha_login'] = '0'; }
            if(!in_array('DX_email_activation', $in_array)) { $data['DX_email_activation']['value'] = $mas['DX_email_activation'] = '0'; }
            if(!in_array('DX_email_account_details', $in_array)) { $data['DX_email_account_details']['value'] = $mas['DX_email_account_details'] = '0'; }
            if(!in_array('DX_count_login_attempts', $in_array)) { $data['DX_count_login_attempts']['value'] = $mas['DX_count_login_attempts'] = '0'; }                                                             
            
            if($this->$model->edit_tableInfo(array('configs' => serialize($data)))) // заносим настройки в базу
            {
                $return = configSaveToFile($this->module, $mas); // сохранение настроек в конфигурационный файл
            }
            else
            {
                $return = notification($this->alang->admin_save_error, 'red');
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
        
        if(count($dataBD) < count($data))
        {
            foreach($dataBD as $item=>$value)
            {
                unset($data[$item]);
            }
            
            $newConfig = array_merge($dataBD, $data);
        }
        else
        {
            foreach($dataBD as $item=>$value)
            {
                if(isset($data[$item]))
                {
                    $data[$item]['value'] = $value['value'];
                }
            }
            
            $newConfig = $data;
        }                
                
        if($this->$model->edit_tableInfo(array('configs' => serialize($newConfig)))) // заносим настройки в базу
        {
            foreach($newConfig as $item=>$value)
            {
                $mas[$item] = $value['value'];
            }
            $return = configSaveToFile($this->module, $mas); // сохранение настроек в конфигурационный файл
        }
        else
        {
            $return = notification($this->alang->admin_save_error, 'red');
        }
           
        echo $return;        
    }
    

/** ------------------------------------------------------------------------------------------- **/
    
    
    /**
     * Проверка на существование логина
    **/
    function _rolename_check($username, $id = NULL)
    {
        $this->load->model('auth/admin_auth_users_model', 'module_model');
        if(!$id)
        {
            $result1 = $this->module_model->all_data_where('auth_roles',array('name'=>$username));
        }
        else
        {
            $result1 = $this->module_model->all_data_where('auth_roles',array('name'=>$username, 'id !='=>$id));
        }
        
        $result = count($result1);
        if($result != 0) 
            return false;
        return true;
    }
   
    
}
/* End of file */