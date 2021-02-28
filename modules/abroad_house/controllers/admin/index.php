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
        
        include(MDPATH.'abroad_house/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                
            
        // Массив полей которые нужно проверять
        $this->ValidPolya = array(
            'name' => 'required'
        );         
	}


/** --------------------------------------------------------------------------------------------- **/


	/**
	* Вывод списка данных
	**/
	function index($all= '')
	{                                
        // Задаем поля которые нужно выводить
        $fields = array(                                
            'sort' => array(
                'width'=>'5%', 
                'class'=>'hide-on-mobile', 
                'title'=>'<span class="underline with-tooltip blue" title="Порядок вывода">№</span>'
                ),
            'name' => array(
                'width'=>'', 
                'class'=>'',
                'title' => ''
                ),
            'banned' => array(
                'width'=>'5%', 
                'class'=>'checkbox-cell',
                'title' => '<span class="icon-eye underline with-tooltip blue" title="'.$this->alang->admin_visibility.'"></span>'
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
            $pgs = 0;
            if($all == 'all') { $pgs = '0|500'; }
            $listData = $this->load->module('admin')->list_table(
                $this->table,
                $this->module,
                $fields,
                $options,
                $id_or_link,
                $pgs
            );
            
            // Валидация       
            $vls = $this->validate('index/deleteRow','.serial_form','.chekDel','table');                                                                                          
                                            
            $listData['uri'] = $this->uri->uri_string();
            
            $listData['moduleName'] = $this->module; // передаем модуль            
            $listData['moduleTable'] = $this->table; // передаем таблицу
            
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
			echo json_encode(array(
				'ok' => $return
			));
        }
	}


/** --------------------------------------------------------------------------------------------- **/        

    
    /**
     *  Добавление записи
     *  @param returnArray - возвращать или нет массив с данными
    **/
    function add($returnArray = false)
    {        
        $model = $this->table.'_model';
        $statusPage = ''; // перенаправление страницы
        $statusPageLink = BASEURL.'/'.$this->module.'/admin/index/edit/';
        
        $this->load->model($this->module.'/admin_'.$model, $model);
        
        if($this->input->post('dani'))
        {
            $mas = $this->parseJson($this->input->post('dani'));                       
           
            $statusLink = false;
            if(isset($mas['link']))
            {
                $statusLink = $this->$model->module_getLink($mas['link']);
            } 
                                
            if(!$statusLink)
            {                                            
                if($this->$model->module_add($mas))
                {                    
                    $id = $this->db->insert_id();
                    $statusPage = $statusPageLink.$id;
                    
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
                    $return = notification($this->alang->admin_save_error,'red');
                }            
            }
            else
            {
                $link = $mas['link'];
                $mas['link'] = '123';

                if($this->$model->module_add($mas))
                {
                    $ids = $this->db->insert_id();                    
                    $statusPage = $statusPageLink.$ids;                    
                    $newLink = $link.'_'.$ids;
                    
                    if($this->$model->module_edit($ids,array('link'=>$newLink)))
                    {
                        if($this->needRoute)
                        {
                            $array = $this->routeArray($newLink, $mas['name']);                
                            $this->load->model('router/router_model','mdRoute');
                            $this->mdRoute->module_add($array);
                        }
                        
                        $return = notification($this->alang->admin_76.' <em>"'.$newLink.'"</em> '.$this->alang->admin_77);
                    }
                    else
                    {
                        $return = notification($this->alang->admin_78);
                    }
                }
                else
                {
                    $return = notification($this->alang->admin_save_error,'red');
                }
            }                        
        }       
        else
        {                        
            // Грузим модуль админа и вызываем нужную функцию для формирования вида
            $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, NULL, $this->ValidPolya, $this->paramFields(), '');                                                                                                                                                                              

            if($returnArray)
            {
                $return = $listData;
            }   
            else
            { 
                // Передаем все данные и выводим шаблон в переменную для передачи данных
                $return = $this->admin_view('admin/edit_table',$listData,true);
            }                        
        }
                
        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($return);
        }
        else
        {        
            if($returnArray)
            {
                return $return;
            }
            else
            {
                echo json_encode(array(
                    'ok' => $return,
                    'statusPage' => BASEURL.'/'.$this->module.'/admin/index/'
                ));
            }
        }
    }
    

/** --------------------------------------------------------------------------------------------- **/


    /**
     *  Редактирование
     *  @param id - идентификатор записи
     *  @param returnArray - возвращать или нет массив с данными 
    **/
    function edit($id = '', $returnArray = false)
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
           
            unset($mas['id']); // удаляем ненужные данные                        
            
            $currentLink = $this->$model->module_get($id); // вытаскиваем текущую запись
            
            $statusLink = false;
            // если не существует ссылка то спокойно редактируем запись   
            if(isset($mas['link']))
            {
                $statusLink = $this->$model->module_getLink($mas['link'], $id);
            }
                      
            if(!$statusLink)
            {                                            
                // передаем данные в БД и ждем ответа true или false
                if($this->$model->module_edit($id,$mas)) // если ответ true
                {                    
                    $statusPage = $statusPageLink;
                    $return = notification($this->alang->admin_save);
                    
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
                $return = notification($this->alang->admin_link_error,'red');                        
            }
        }       
        elseif(!empty($id) and is_numeric($id)) // если идентификатор не пустой и он является цифрой
        {                        
            // вытаскиваем даннные по идентификатору
            $mas = $this->$model->module_get($id);
            
            if($mas != '') // проверяем если ли данные по такому идентификатору
            {
                // Грузим модуль админа и вызываем нужную функцию для формирования вида
                $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, $mas, $this->ValidPolya, $this->paramFields(), '');                                                                                                                                                                              

                if($returnArray)
                {
                    $return = $listData;
                }   
                else
                { 
                    // Передаем все данные и выводим шаблон в переменную для передачи данных
                    $return = $this->admin_view('admin/edit_table',$listData,true);
                }            
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
            if($returnArray)
            {
                return $return;
            }
            else
            {
                echo json_encode(array(
                    'ok' => $return,
                    'statusPage' => BASEURL.'/'.$this->module.'/admin/index/'
                ));
            }
        }
    }
    
    
/** --------------------------------------------------------------------------------------------- **/
    

	/**
	* Установка модуля | Запись данных в базу
	**/
	function install()
	{
		if(!$this->input->is_ajax_request())
        {
            die('Не верно передан запрос!');
        }
        
        $status = '';
		if($this->input->post('params'))
		{
			$params = $this->input->post('params',true); // ловим переданные данные | передается только имя модуля
			
            $model = $this->table.'_model';
            $this->load->model($this->module.'/admin_'.$model, $model);

			if(!$this->$model->check_install())
			{                
                if($this->$model->create_tables())
                {                                        
                    if($this->$model->module_install())
    				{    					
                        $rout = $this->settingModule('router'); // роут модуля                                                
                        if(!empty($rout))
                        {
                            $this->load->model('router/router_model');
                            $ksp = $this->router_model->module_getValue($this->module);                            
                            if(empty($ksp))
                            {                            
                                $title = $this->settingModule('title');
                                
                                $array = array(
                                    'key' => $rout,
                                    'value' => $this->module,
                                    'desc' => $title
                                );                                
                                $this->router_model->module_add($this->routeArray());
                                
                                $array1 = array(
                                    'key' => $rout.'/(:any)',
                                    'value' => $this->module,
                                    'desc' => $title
                                );                                
                                $this->router_model->module_add($array1);
                                
                                $this->router_model->write_router(); // записать роутер
                            }
                        }
                        
                        change_moduleinfo(MDPATH.$this->module.'/moduleinfo.php','status','1');    
    					$status = '<span class="tag with-small-padding green-bg">Активен</span>';
    
    					$return = notification('Модуль успешно активирован!');
    				}
    				else
    				{
    					$return = notification('Ошибка! Модуль не активирован!','red');
    				}                
                }
                else
                {
                    $return = notification('Ошибка! Не удалось создать таблицу в БД!','red');
                }                
			}
			else
			{
				$return = notification('Ошибка! Модуль уже активирован!','red');
			}
		}
		else
		{
			$return = notification('Ошибка! Не переданы параметры!','red');
		}

		echo json_encode(array(
			'ok' => $return, // сообщение
			'mod' => $this->module, // модуль
			'td' => $status // статус
		));
	}


/** --------------------------------------------------------------------------------------------- **/


	/**
	* Деинстал модуля | Очистка модуля с БД модули
	**/
	function uninstall()
	{
		
        if(!$this->input->is_ajax_request())
        {
            die('Не верно передан запрос!');
        }
        
        $status = '';
		if($this->input->post('params'))
		{
			$params = $this->input->post('params',true); // ловим переданные данные | передается только имя модуля
			
            $model = $this->table.'_model';
            $this->load->model($this->module.'/admin_'.$model, $model);;

			if($this->$model->check_install())
			{
				if($this->$model->module_uninstall())
				{
					change_moduleinfo(MDPATH.$this->module.'/moduleinfo.php','status','0');
					$status = '<span class="tag with-small-padding blue-bg">Не активен</span>';

					$return = notification('Модуль успешно деактивирован!');
				}
				else
				{
					$return = notification('Ошибка! Модуль не деактивирован!','red');
				}
			}
			else
			{
				$return = notification('Ошибка! Модуль уже деактивирован!','red');
			}
		}
		else
		{
			$return = notification('Ошибка! Не переданы параметры!','red');
		}

		echo json_encode(array(
			'ok' => $return,
			'mod' => $this->module,
			'td' => $status
		));
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
                if($d['name'] == 'foto[]')
                {
                    $foto[] = $d['value'];
                }
                elseif($d['name'] == 'foto_alt[]')
                {
                    $alt[] = $d['value'];
                }
                elseif($d['name'] == 'recommend')
                {
                    $rec[] = $d['value'];
                }
                else
                {
                    $sds[$d['name']] = $d['value'];
                }                                    
            }
            
            if(isset($foto)) { 
                $serailize = array(
                        'foto' => $foto, 
                        'alt' => $alt 
                    ); 
                $sds['foto'] = serialize($serailize);
            }
            
            if(isset($rec))
            {
                $sds['recommend'] = serialize($rec); 
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
         
        // Вытаскиваем родителей
        /*$this->load->model('/admin__model','admin__model');                        
        $parent = $this->admin__model->parent_id();                
        foreach($parent as $p)
        {
            $parent_id[$p['id']] = $p['name'];
        }*/
        
        
        // Массив параметров для полей
        return array(
                        'banned' => array($this->alang->admin_72,$this->alang->admin_73)
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
            
            if(!in_array('Paging', $in_array)) { $mas['Paging'] = $data['table']['Paging']['value'] = '0';}                                           
            
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
        
        if(count($dataBD['table']) < count($data['table']))
        {
            foreach($dataBD['table'] as $item=>$value)
            {
                unset($data['table'][$item]);
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
    
    /**
     * Подгружаемая строка к таблице
    **/
    function dropRow()
    {                       
        $id = str_replace('dlt', '', $this->input->post('id')); // идентификатор строки

        $mas = $this->edit($id, true);
        
        $mas['tables'] = $this->table;
        $mas['modules'] = $this->module;
        $mas['id'] = $id;

        $return = $this->load->view($this->module.'/admin/tr_table', $mas, true);            

        echo json_encode(array(
            'ok' => $template
        ));                            
        
    }
    
}
/* End of file */