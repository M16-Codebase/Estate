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
        
        include(MDPATH.'buildings/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                
            
        // Массив полей которые нужно проверять
        $this->ValidPolya = array(
            'name' => 'required'
        );
	}


/** --------------------------------------------------------------------------------------------- **/

    function razdel($id)
    {
        if(!empty($id))
        {
            $_SESSION['razdel_admin'] = $id;
        }
        else
        {
            $_SESSION['razdel_admin'] = 0;
        }

        redirect('/buildings/admin/index');
        /*
        'razdelu' => array(
                              '0' => 'Новостройки',
                              '1' => 'Городская',
                              '2' => 'Загородная',
                              '3' => 'Елитная',
                              '4' => 'Коммерческая',
                              '5' => 'Зарубежная',
                              '6' => 'Земельные участки'
                          )

        */
    }

	/**
	* Вывод списка данных
	**/
	function index($all = '')
	{
        if($_SESSION['razdel_admin'] == '')
        {
            $_SESSION['razdel_admin'] = 0;
        }

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
            'price' => array(
                'width'=>'',
                'class'=>'',
                'title' => ''
                ),
            'adress' => array(
                'width'=>'',
                'class'=>'',
                'title' => ''
                ),
            'razdelu' => array(
                'width'=>'', 
                'class'=>'hide-on-tablet',
                'title' => 'Разделы ( недвижимость )'
                ),
            'date_add' => array(
                'width'=>'',
                'class'=>'hide-on-tablet',
                'title' => 'Дата создания:'
            ),
            'date_edit' => array(
                'width'=>'',
                'class'=>'hide-on-tablet',
                'title' => 'Дата изменения:'
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
            $rzd = array(
                '0' => 'Новостройки',
                '1' => 'Городская',
                '2' => 'Загородная',
                '3' => 'Елитная',
                '4' => 'Коммерческая',
                '5' => 'Зарубежная',
                '6' => 'Земельные участки',
                '7' => 'Котеджные поселки',
                '8' => 'Переуступки',
                '9' => 'Эксклюзивная'
            );
            $listData['captionTable'] = $rzd[$_SESSION['razdel_admin']];
            
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

            $types = array();
            if (!empty($mas['typesdelka_id'])){
                $types = explode(",", $mas['typesdelka_id']);
            }

            $dop = array();
            if (!empty($mas['dop_metro'])){
                $dop = explode(",", $mas['dop_metro']);
            }
            if (!empty($mas['metro_id'])){
                $dop[] = $mas['metro_id'];
            }

            $dopr = array();
            if (!empty($mas['dop_rayon'])){

                $dopr = explode(",", $mas['dop_rayon']);

            }
            if (!empty($mas['rayon_id'])){
                $dopr[] = $mas['rayon_id'];
            }

            $bank = array();
            if (!empty($mas['banks'])){

                $bank = explode(",", $mas['banks']);

            }

            $mas['date_add'] = time();
            $mas['date_edit'] = time();
                                
            if(!$statusLink)
            {                                            
                if($this->$model->module_add($mas))
                {                    
                    $id = $this->db->insert_id();
                    $this->$model->typesdelka_add($id,$types);
                    $this->$model->dop_metro_add($id,$dop);
                    $this->$model->dop_rayon_add($id,$dopr);
                    $this->$model->banks_add($id,$bank);
                    $this->load->model('interest/admin_interest_model','mdInterest');
                    $arr = $this->$model->module_get($id);
                    $array = $this->$model->addToInterest($arr);
                    $this->mdInterest->addInterest($array, 'buildings');
                    $array = $this->$model->addToSpecials($arr);
                    $this->load->model('special/admin_special_model','mdSpecial');
                    $this->mdSpecial->addSpecials($array, 'buildings');

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
                    $this->$model->typesdelka_add($ids,$types);
                    $this->$model->dop_metro_add($ids,$dop);
                    $this->$model->dop_rayon_add($ids,$dopr);
                    $this->$model->banks_add($ids,$bank);
                    $this->load->model('interest/admin_interest_model','mdInterest');
                    $arr = $this->$model->module_get($ids);
                    $array = $this->$model->addToInterest($arr);
                    $this->mdInterest->addInterest($array, 'buildings');
                    $array = $this->$model->addToSpecials($arr);
                    $this->load->model('special/admin_special_model','mdSpecial');
                    $this->mdSpecial->addSpecials($array, 'buildings');
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
                        //redirect('/buildings/admin/index/edit/'.$ids);
                        $return = notification($this->alang->admin_76.' <em>"'.$newLink.'"</em> '.$this->alang->admin_77);
                    }
                    else
                    {
						//redirect('/buildings/admin/index/edit/'.$ids);
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
            $is_cottage = 0;
            $mas = NULL;
            if ($_SESSION['razdel_admin'] == 7){
                $mas['is_cottage'] = 1;
                $mas['razdelu'] = 2;
            }
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
                    'href'=>$statusPage,
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
        
            $currentLink = $this->$model->module_get($id); // вытаскиваем текущую запись

            $mas = $this->parseJson($this->input->post('dani')); // вытаскиваем данные из POST

            // Отправляем сообщение о сокрытии
            if ($mas['id'] > 0 AND $mas['banned'] == 1)
            {
                $text = array();
                $text[] = ($mas['title'])?$mas['title']:$mas['name'];
                $text[] = 'http://www.m16-estate.ru/'.$this->module.'/'.$currentLink['link'];
                $text[] = '------------------------------';
                remove_notify(implode('<br />', $text), 'Скрытие объекта');
            }

            unset($mas['id']); // удаляем ненужные данные
            
            $statusLink = false;
            // если не существует ссылка то спокойно редактируем запись   
            if(isset($mas['link']))
            {
                $statusLink = $this->$model->module_getLink($mas['link'], $id);
            }

            $types = array();
            if (!empty($mas['typesdelka_id'])){
                $types = explode(",", $mas['typesdelka_id']);
            }

            $dop = array();
            if (!empty($mas['dop_metro'])){

                $dop = explode(",", $mas['dop_metro']);

            }
            if (!empty($mas['metro_id'])){
                $dop[] = $mas['metro_id'];
            }
            $dopr = array();
            if (!empty($mas['dop_rayon'])){

                $dopr = explode(",", $mas['dop_rayon']);

            }
            if (!empty($mas['rayon_id'])){
                $dopr[] = $mas['rayon_id'];
            }
            $bank = array();
            if (!empty($mas['banks'])){

                $bank = explode(",", $mas['banks']);

            }

            unset($mas['foto[]']);
            unset($mas['foto_alt']);

            $mas['date_edit'] = time();

            if(!$statusLink)
            {
                // передаем данные в БД и ждем ответа true или false
                if($this->$model->module_edit($id,$mas)) // если ответ true
                {
                    $this->$model->typesdelka_add($id,$types);
                    $this->$model->dop_metro_add($id,$dop);
                    $this->$model->dop_rayon_add($id,$dopr);
                    $this->$model->banks_add($id,$bank);
                    $this->load->model('interest/admin_interest_model','mdInterest');
                    $arr = $this->$model->module_get($id);
                    $array = $this->$model->addToInterest($arr);
                    $this->mdInterest->addInterest($array, 'buildings');
                    $array = $this->$model->addToSpecials($arr);
                    $this->load->model('special/admin_special_model','mdSpecial');
                    $this->mdSpecial->addSpecials($array, 'buildings');
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
                $listData = $this->load->module('admin')->add_edit_data($this->table, $this->module, $mas, $this->ValidPolya, $this->paramFields($mas['id']), '');

                if(!empty($listData['table']['razdelu']['value']))
                    $listData['table']['razdelu']['value'] = explode(',', $listData['table']['razdelu']['value']);

                if(!empty($listData['table']['typesdelka_id']['value']))
                    $listData['table']['typesdelka_id']['value'] = explode(',', $listData['table']['typesdelka_id']['value']);
                if(!empty($listData['table']['dop_metro']['value']))
                    $listData['table']['dop_metro']['value'] = explode(',', $listData['table']['dop_metro']['value']);
                if(!empty($listData['table']['banks']['value']))
                    $listData['table']['banks']['value'] = explode(',', $listData['table']['banks']['value']);
                if(!empty($listData['table']['dop_rayon']['value']))
                    $listData['table']['dop_rayon']['value'] = explode(',', $listData['table']['dop_rayon']['value']);

                if(!empty($listData['table']['korpus_value']['value']))
                    $listData['table']['korpus_value']['value'] = date('d.m.Y', $listData['table']['korpus_value']['value']);

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
        if (!$this->input->is_ajax_request())
            die();

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
        
        $text = array();

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

                        $info = get_remove_text($chDel[0], 'buildings');

                        if($this->model->module_delete($chDel[0])) // выполняем удаление
                        {
                            $text[] = $info;

                            $this->load->model('interest/admin_interest_model','mdInterest');
                            $this->load->model('special/admin_special_model','mdSpecial');

                            $this->mdInterest->deleteInterest($chDel[0], 'buildings');
                            $this->mdSpecial->deleteSpecials($chDel[0], 'buildings');
                            
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

                $info = get_remove_text($checked, 'buildings');

                if($this->model->module_delete($checked)) // удаляем запись
                {
                    $text[] = $info;

                    $this->load->model('interest/admin_interest_model','mdInterest');
                    $this->load->model('special/admin_special_model','mdSpecial');

                    $this->mdInterest->deleteInterest($checked, 'buildings');
                    $this->mdSpecial->deleteSpecials($checked, 'buildings');

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
        
        // Отправляем сообщение
        if (count($text))
            remove_notify(implode("<br />", $text), 'Удаление объектов');

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
                elseif($d['name'] == 'foto_otdelka[]')
                {
                    $foto_otdelka[] = $d['value'];
                }
                elseif($d['name'] == 'foto_alt')
                {
                    $alt[] = $d['value'];
                }
                elseif($d['name'] == 'foto_otdelka_alt')
                {
                    $otdelka_alt[] = $d['value'];
                }
                elseif($d['name'] == 'recommend')
                {
                    $rec[] = $d['value'];
                }
                elseif($d['name'] == 'razdelu')
                {
                    $razdelu[] = $d['value'];
                }
                elseif($d['name'] == 'typesdelka_id')
                {
                    $sdelka[] = $d['value'];
                }
                elseif($d['name'] == 'dop_metro')
                {
                    $dopm[] = $d['value'];
                }
                elseif($d['name'] == 'banks')
                {
                    $bank[] = $d['value'];
                }
                elseif($d['name'] == 'likeas')
                {
                    $likeas[] = $d['value'];
                }
                elseif($d['name'] == 'dop_rayon')
                {
                    $dopr[] = $d['value'];
                }
                else
                {
                    $sds[$d['name']] = $d['value'];
                }                                    
            }

            $sds['korpus'] = $sds['button-radio'];
            if($sds['korpus'] != 3) { $sds['korpus_value'] = ''; }
            unset($sds['button-radio']);

            if(!empty($sds['korpus_value'])) { $sds['korpus_value'] = strtotime($sds['korpus_value']); }

            if(isset($sds['z_gas'])) { if($sds['z_gas'] == 0) $sds['z_gas'] = 1; }
            if(!isset($sds['z_gas'])) { $sds['z_gas'] = 0; }

            if(isset($sds['ipoteka'])) { if($sds['ipoteka'] == 0) $sds['ipoteka'] = 1; }
            if(!isset($sds['ipoteka'])) { $sds['ipoteka'] = 0; }

            if(isset($sds['rassrochka'])) { if($sds['rassrochka'] == 0) $sds['rassrochka'] = 1; }
            if(!isset($sds['rassrochka'])) { $sds['rassrochka'] = 0; }

            if(isset($sds['interest'])) { if($sds['interest'] == 0) $sds['interest'] = 1; }
            if(!isset($sds['interest'])) { $sds['interest'] = 0; }

			if(isset($sds['military'])) { if($sds['military'] == 0) $sds['military'] = 1; }
            if(!isset($sds['military'])) { $sds['military'] = 0; }

            if(isset($sds['commerce'])) { if($sds['commerce'] == 0) $sds['commerce'] = 1; }
            if(!isset($sds['commerce'])) { $sds['commerce'] = 0; }

            if(isset($sds['special'])) { if($sds['special'] == 0) $sds['special'] = 1; }
            if(!isset($sds['special'])) { $sds['special'] = 0; }

            if(isset($sds['vid'])) { if($sds['vid'] == 0) $sds['vid'] = 1; }
            if(!isset($sds['vid'])) { $sds['vid'] = 0; }

            if(isset($sds['club'])) { if($sds['club'] == 0) $sds['club'] = 1; }
            if(!isset($sds['club'])) { $sds['club'] = 0; }

            if(isset($sds['uc_gas'])) { if($sds['uc_gas'] == 0) $sds['uc_gas'] = 1; }
            if(!isset($sds['uc_gas'])) { $sds['uc_gas'] = 0; }

            if(isset($sds['z_electric'])) { if($sds['z_electric'] == 0) $sds['z_electric'] = 1; }
            if(!isset($sds['z_electric'])) { $sds['z_electric'] = 0; }

            if(isset($sds['z_water'])) { if($sds['z_water'] == 0) $sds['z_water'] = 1; }
            if(!isset($sds['z_water'])) { $sds['z_water'] = 0; }

            if(isset($sds['fz214'])) { if($sds['fz214'] == 0) $sds['fz214'] = 1; }
            if(!isset($sds['fz214'])) { $sds['fz214'] = 0; }

            if(isset($sds['parking'])) { if($sds['parking'] == 0) $sds['parking'] = 1; }
            if(!isset($sds['parking'])) { $sds['parking'] = 0; }

            if(isset($foto)) {
                $serailize = array(
                        'foto' => $foto, 
                        'alt' => $alt
                    ); 
                $sds['foto'] = serialize($serailize);
            }
            if(isset($foto_otdelka)) {
                $serailize = array(
                    'foto' => $foto_otdelka,
                    'alt' => $otdelka_alt
                );
                $sds['foto_otdelka'] = serialize($serailize);
            }
            
            if(isset($rec)) { $sds['recommend'] = serialize($rec); }

            if(isset($razdelu)) { $sds['razdelu'] = implode(',', $razdelu); }
            if(isset($sdelka)) { $sds['typesdelka_id'] = implode(',', $sdelka); }
            if(isset($dopm)) { $sds['dop_metro'] = implode(',', $dopm); } else {$sds['dop_metro'] = ''; }
            if(isset($dopr)) { $sds['dop_rayon'] = implode(',', $dopr); } else {$sds['dop_rayon'] = ''; }
            if(isset($bank)) { $sds['banks'] = implode(',', $bank); } else {$sds['banks'] = ''; }
            if(isset($likeas)) { $sds['likeas'] = implode(',', $likeas); } else {$sds['likeas'] = ''; }
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
    function paramFields($pid = null)
    {    
         
        // Вытаскиваем родителей
        /*$this->load->model('/admin__model','admin__model');
        $parent = $this->admin__model->parent_id();
        foreach($parent as $p)
        {
            $parent_id[$p['id']] = $p['name'];
        }*/

        $this->load->model('buildings/admin_buildings_model','buildings_model');
        $builds = $this->buildings_model->getLikes($pid);
        foreach($builds as $p)
        {
            $builds_likes[$p['id']] = $p['name'];
        }

        $this->load->model('banks/admin_banks_model','banks_model');
        $banks = $this->banks_model->parent_id();
        foreach($banks as $p)
        {
            $banks_list[$p['id']] = $p['name'];
        }

        $this->load->model('otdelka/admin_otdelka_model','admin_otdelka_model');
        $parent_otdelka = $this->admin_otdelka_model->parent_id();
        $parent_id_otdelka[''] = ' -- не выбрано -- ';
        foreach($parent_otdelka as $p)
        {
            $parent_id_otdelka[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model','admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        $parent_id_room[0] = ' -- не выбрано -- ';
        foreach($parent_room as $p)
        {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $this->load->model('metro/admin_metro_model','admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(NULL, 'name', 'ASC');
        $parent_idmetro['0'] = ' -- не выбрано -- ';
        foreach($parentmetro as $p)
        {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('builders/admin_builders_model','admin_builders_model');
        $parentbuilders = $this->admin_builders_model->parent_id();
        $parent_idbuilders['0'] = ' -- не выбрано -- ';
        foreach($parentbuilders as $p)
        {
            $parent_idbuilders[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model','admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(NULL, 'name', 'ASC');
        $parent_idrayon['0'] = ' -- не выбрано -- ';
        foreach($parentrayon as $p)
        {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model','admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach($parenttype as $p)
        {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('planirovka/admin_planirovka_model','admin_planirovka_model');
        $parentplanirovka = $this->admin_planirovka_model->parent_id();
        foreach($parentplanirovka as $p)
        {
            $parent_idplanirovka[$p['id']] = $p['name'];
        }

        $this->load->model('vhod/admin_vhod_model','admin_vhod_model');
        $parentvhod = $this->admin_vhod_model->parent_id();
        foreach($parentvhod as $p)
        {
            $parent_idvhod[$p['id']] = $p['name'];
        }

        $this->load->model('okna/admin_okna_model','admin_okna_model');
        $parentokna = $this->admin_okna_model->parent_id();
        foreach($parentokna as $p)
        {
            $parent_idokna[$p['id']] = $p['name'];
        }

        $this->load->model('typeprodazha/admin_typeprodazha_model','admin_typeprodazha_model');
        $parenttypeprodazha = $this->admin_typeprodazha_model->parent_id();
        foreach($parenttypeprodazha as $p)
        {
            $parent_idtypeprodazha[$p['id']] = $p['name'];
        }

        $this->load->model('typesdelka/admin_typesdelka_model','admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        $parent_idtypesdelka['0'] = '-- не выбрано --';
        foreach($parenttypesdelka as $p)
        {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }

        $this->load->model('infrostructura/admin_infrostructura_model','admin_infrostructura_model');
        $parentinfrostructura = $this->admin_infrostructura_model->parent_id();
        $parent_idinfrostructura['0'] = ' -- не выбрано -- ';
        foreach($parentinfrostructura as $p)
        {
            $parent_idinfrostructura[$p['id']] = $p['name'];
        }

        $this->load->model('vodoem/admin_vodoem_model','admin_vodoem_model');
        $parentvodoem = $this->admin_vodoem_model->parent_id();
        $parent_idvodoem['0'] = ' -- не выбрано -- ';
        foreach($parentvodoem as $p)
        {
            $parent_idvodoem[$p['id']] = $p['name'];
        }

        $this->load->model('ruchastok/admin_ruchastok_model','admin_ruchastok_model');
        $parentruchastok = $this->admin_ruchastok_model->parent_id();
        $parent_idruchastok['0'] = ' -- не выбрано -- ';
        foreach($parentruchastok as $p)
        {
            $parent_idruchastok[$p['id']] = $p['name'];
        }

        $this->load->model('matherial/admin_matherial_model','admin_matherial_model');
        $parentmatherial = $this->admin_matherial_model->parent_id();
        $parent_idmatherial['0'] = ' -- не выбрано -- ';
        foreach($parentmatherial as $p)
        {
            $parent_idmatherial[$p['id']] = $p['name'];
        }

        $this->load->model('vodoprovod/admin_vodoprovod_model','admin_vodoprovod_model');
        $parentvodoprovod = $this->admin_vodoprovod_model->parent_id();
        $parent_idvodoprovod['0'] = ' -- не выбрано -- ';
        foreach($parentvodoprovod as $p)
        {
            $parent_idvodoprovod[$p['id']] = $p['name'];
        }

        $this->load->model('buildings_class/admin_buildings_class_model','admin_buildings_class_model');
        $parentclass = $this->admin_buildings_class_model->parent_id();
        $parent_idclass['0'] = ' -- не выбрано -- ';
        foreach($parentclass as $p)
        {
            $parent_idclass[$p['id']] = $p['name'];
        }
        $this->load->model('residential_object/admin_residential_object_model','admin_residential_object_model');
        $parentresobject = $this->admin_residential_object_model->parent_id();
        $parent_idresobject['0'] = ' -- не выбрано -- ';
        foreach($parentresobject as $p)
        {
            $parent_idresobject[$p['id']] = $p['name'];
        }
        $this->load->model('elite_type/admin_elite_type_model','admin_elite_type_model');
        $parenteliteobject = $this->admin_elite_type_model->parent_id();
        $parent_ideliteobject['0'] = ' -- не выбрано -- ';
        foreach($parenteliteobject as $p)
        {
            $parent_ideliteobject[$p['id']] = $p['name'];
        }
        $this->load->model('commercial_bussines/admin_commercial_bussines_model','admin_commercial_bussines_model');
        $parentcommbuisness = $this->admin_commercial_bussines_model->parent_id();
        $parent_idcommbuisness['0'] = ' -- не выбрано -- ';
        foreach($parentcommbuisness as $p)
        {
            $parent_idcommbuisness[$p['id']] = $p['name'];
        }
        $this->load->model('commercial_forwhat/admin_commercial_forwhat_model','admin_commercial_forwhat_model');
        $parentcommercial_forwhat = $this->admin_commercial_forwhat_model->parent_id();
        $parent_idcommercial_forwhat['0'] = ' -- не выбрано -- ';
        foreach($parentcommercial_forwhat as $p)
        {
            $parent_idcommercial_forwhat[$p['id']] = $p['name'];
        }
        $this->load->model('commercial_type/admin_commercial_type_model','admin_commercial_type_model');
        $parentcommercial_type = $this->admin_commercial_type_model->parent_id();
        $parent_idcommercial_type['0'] = ' -- не выбрано -- ';
        foreach($parentcommercial_type as $p)
        {
            $parent_idcommercial_type[$p['id']] = $p['name'];
        }
        $this->load->model('commercial_vid/admin_commercial_vid_model','admin_commercial_vid_model');
        $parentcommercial_vid = $this->admin_commercial_vid_model->parent_id();
        $parent_idcommercial_vid['0'] = ' -- не выбрано -- ';
        foreach($parentcommercial_vid as $p)
        {
            $parent_idcommercial_vid[$p['id']] = $p['name'];
        }
        $this->load->model('commercial_pay/admin_commercial_pay_model','admin_commercial_pay_model');
        $parentcommercial_pay = $this->admin_commercial_pay_model->parent_id();
        $parent_idcommercial_pay['0'] = ' -- не выбрано -- ';
        foreach($parentcommercial_pay as $p)
        {
            $parent_idcommercial_pay[$p['id']] = $p['name'];
        }

        $this->load->model('cottage/admin_cottage_model','admin_cottage_model');
        $parentcottage = $this->admin_cottage_model->parent_id();
        $parent_idcottage['0'] = ' -- не выбрано -- ';
        foreach($parentcottage as $p)
        {
            $parent_idcottage[$p['name']] = $p['name'];
        }
        $is_cottage = 0;
        if ($_SESSION['razdel_admin'] == 7){
            $is_cottage = 1;
        }

        $this->load->model('consultants/admin_consultants_model','admin_consultants_model');
        $parent_consultants = $this->admin_consultants_model->parent_id();
        $parent_id_consultants[0] = ' -- не выбрано -- ';
        foreach($parent_consultants as $p)
        {
            $parent_id_consultants[$p['id']] = $p['name'];
        }

        // Массив параметров для полей
        return array(
                        'banned' => array($this->alang->admin_72,$this->alang->admin_73),
                        'interest' => array($this->alang->admin_72,$this->alang->admin_73),
                        'metro_id' => $parent_idmetro,
                        'dop_metro' => $parent_idmetro,
                        'rayon_id' => $parent_idrayon,
                        'dop_rayon' => $parent_idrayon,
                        'banks' => $banks_list,
                        'type_id' => $parent_idtype,
                        'cottage' => $parent_idcottage,
                        'builder_id' => $parent_idbuilders,
                        'razdelu' => array(
                                        '0' => 'Новостройки',
                                        '1' => 'Городская',
                                        '2' => 'Загородная',
                                        '3' => 'Элитная',
                                        '4' => 'Коммерческая',
                                        '5' => 'Зарубежная',
                                        '8' => 'Переуступки',
                                        '9' => 'Эксклюзивная',
                                    ),
                        'area' => array(
                            'Санкт-Петербург' => 'Санкт-Петербург',
                            'Ленинградская область' => 'Ленинградская область'
                        ),
                        'otd_metro' => array('пешком','транспортом'),
                        'typeprodazha_id' => $parent_idtypeprodazha,
                        'otdelka_id' => $parent_id_otdelka,
                        'planirovka_id' => $parent_idplanirovka,
                        'vhod_id' => $parent_idvhod,
                        'okna_id' => $parent_idokna,
                        'room_id' => $parent_id_room,
                        'elite_type' => $parent_ideliteobject,
                        'comm_type' => $parent_idcommercial_type,
                        'comm_vid' => $parent_idcommercial_vid,
                        'comm_bussines' => $parent_idcommbuisness,
                        'comm_forwhat' => $parent_idcommercial_forwhat,
                        'comm_pay' => $parent_idcommercial_pay,
                        'typesdelka_id' => $parent_idtypesdelka,
                        'infrostructura_id' => $parent_idinfrostructura,
                        'matherial_id' => $parent_idmatherial,
                        'razdel_uchastok_id' => $parent_idruchastok,
                        'res_type' => $parent_idresobject,
                        'vodoem_id' => $parent_idvodoem,
                        'class' => $parent_idclass,
                        'likeas' => $builds_likes,
                        'is_cottage' => $is_cottage,
                        'vodoprovod_id' => $parent_idvodoprovod,
                        'consultant_id' => $parent_id_consultants

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

    function updatePrices()
    {
        $model = $this->table.'_model';
        $this->load->model($this->module.'/admin_'.$model, 'admin_model');
        //$this->admin_model->fillRayon();
        $this->load->model($this->module.'/'.$model, $model);
		$this->load->model('interest/admin_interest_model','mdInterest');
		$this->load->model('special/admin_special_model','mdSpecial');
		
		$com = $this->$model->allBuildings(0);
        foreach($com as $c)
        {
            $prc = 0;
            $prc = $this->$model->complexMinMaxPrice($c['id']);
            if ($prc['min'] > 0 || $prc['max'] > 0){
                $prcfm = $this->$model->complexPriceForMeter($c['id']);
                $this->db->update($this->table, array('virtual_price' => $prc['min'], 'price' => $prc['min'], 'virtual_price_max' => $prc['max'], 'price_for_meter'=>$prcfm), array('id' => $c['id']));
				$this->mdInterest->synchronization($c['id'],'buildings',$prc['min']);
				$this->mdSpecial->synchronization($c['id'],'buildings',$prc['min']);
            }
        }

        $com = $this->$model->allBuildings(3);
        foreach($com as $c)
        {
            $prc = 0;
            $prc = $this->$model->complexMinMaxPrice($c['id']);
            if ($prc['min'] > 0 || $prc['max'] > 0){
                $prcfm = $this->$model->complexPriceForMeter($c['id']);
                $this->db->update($this->table, array('virtual_price' => $prc['min'], 'price' => $prc['min'], 'virtual_price_max' => $prc['max'], 'price_for_meter'=>$prcfm), array('id' => $c['id']));
				$this->mdInterest->synchronization($c['id'],'buildings',$prc['min']);
				$this->mdSpecial->synchronization($c['id'],'buildings',$prc['min']);
            }
        }

        /*$this->load->model('apartments/admin_apartments_model', 'apartments_model');
        $apart = $this->apartments_model->parent_id();
        foreach ($apart as $k=>$v){
            $this->db->update('apartments', array('price_for_meter' => (int)($v['price']/(doubleval($v['square_all'])))), array('id' => $v['id']));
        }*/

        $return = notification('Цены обновлены.');

        echo $return;

    }
    
}
/* End of file */