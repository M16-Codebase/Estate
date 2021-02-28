<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Admin {	
    
    private $arrayType;
    private $arrayOptions;
    
    function __construct()
    {
        // конструктор
        parent::__construct();         
        
        $this->arrayType = array(
            'INT'       => 'input',
            'TINYINT'   => 'input',
            'VARCHAR'   => 'input',
            'TEXT'      => 'textarea',
            'DATE'      => 'input_date'
        );  
        
        $this->arrayOptions = array(
            'class' => '', // класс
            'label_class' => '', // класс для Label
            'text_button' => '', // название кнопки
            'text_button_script' => '', // скрипт для кнопки
            'text_icon' => '', // иконка
            'select_chosen' => false, // используется ли для селекта плагин choosen
            'switch_text' => '', // текста через знак (|) для switch. Разелитель нужен для надписи кнопок ВКЛ|ОТКЛ.
            'editor_function' => '', // название функции настройки редактора
            'img_path' => '', // путь к папке изображений | файлов
            'width' => '' // ширина блока
        );                                                                                                       
    }


/** ---------------------------------------------------------------------------------------------------- **/ 

    
/**
 * Главная страница
**/     
	function index()
	{		                                                                                             
        $this->data['data'] = $this->admin_view('admin','',true);
        
        $this->admin_display($this->data);            
	} 
    
    
    function languages($lang)
    {
        
        if($lang == 'russian' or $lang == 'english')
        {
            $this->session->set_userdata('languages', $lang);
        }
        
        redirect('/admin');
    }


/** ---------------------------------------------------------------------------------------------------- **/ 

    
/**
 * Настройка системы (config модуля admin)
**/     
    function config()
    {        
        if(!$this->dx_auth->is_admin())
            die("<h2 class='thin with-padding blue boxed wrapped'>Ваши права на текущую страницу ограничены администрацией сайта.</h2>");
        
        $data = $this->validate('config');
        $data['confs'] = '';
        
        if($this->input->post('dani'))
        {            
            $sended = parse_json($this->input->post('dani'));
            $this->load->model('module/module_model','model');
            
            foreach($sended as $it=>$vl) // проходим в цикле вытаскивая в массив переменную настройки, значение, описание настройки
            {                                
                $value[] = array(
                    'name' => $it,
                    'value' => $vl
                );
                $in_array[] = $it;
            }            
            
            if(!in_array('apex_site', $in_array)) 
            { 
                $value[] = array(
                    'value' => '0',
                    'name' => 'apex_site'
                );
            }
            
            if($this->model->module_config($value,'admin/config/admin.php')) // сохраняем настройки в файл
            {
                $data = notification($this->alang->admin_save);                
            }
            else
            {                
                $data = notification($this->alang->admin_save_error,'red');
            }
            
            $dat = $this->admin_view('admin/template/message',$data,true);
        }
        else
        {                
            $params = $this->input->post('params',true);                        
            $data['confs'] = $this->load->config($params['moduleName'].'/admin',true); 
            
            $google = file_get_contents(APPPATH.'/views/template/google.php'); // гугл аналитик
            $yandex = file_get_contents(APPPATH.'/views/template/yandex.php'); // яндекс метрика
            $rambler = file_get_contents(APPPATH.'/views/template/rambler.php'); // рамблер топ 100
            
            // замена тегов для того что-бы скрипт не запускался при открытии файлов
            $par_google = str_replace('<script','<|scriptse',$google);
            $par_yandex = str_replace('<script','<|scriptse',$yandex);
            $par_rambler = str_replace('<script','<|scriptse',$rambler);
            
            $par_google = str_replace('</script>','<|scriptse>',$par_google);
            $par_yandex = str_replace('</script>','<|scriptse>',$par_yandex);
            $par_rambler = str_replace('</script>','<|scriptse>',$par_rambler);
            
            $data['google'] = $par_google;
            $data['yandex'] = $par_yandex;
            $data['rambler'] = $par_rambler;
                                        
            $dat = $this->admin_view('admin/config',$data,TRUE);
        }
        
        
        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($dat);
        }
        else
        {        
            echo json_encode(array(
                'ok' => $dat
            ));
        }
    } 
    

/** ---------------------------------------------------------------------------------------------------- **/ 
          
    
/**
 * Сохраняем скриптовый файл
**/     
    function save_file($files = NULL)
    {                
        if($files == NULL) 
        {
            $files = 'script';
        }        
        
        if($this->input->post('params'))
        {
            $params = $this->input->post('params');               
            
            $par = str_replace('<|скрипт','<script',$params['moduleName']);            
            $par = str_replace('<|скрипт>','script>',$par);
                                    
            if(write_file(APPPATH.'/views/template/'.$files.'.php',$par)) // сохраняем настройки в файл
            {
                $data = notification($this->alang->save);                
            }
            else
            {                
                $data = notification($this->alang->save_error,'red');
            }
            
            $dat = $this->admin_view('admin/template/message',$data,true);
                    
            
            // Возвращаем json
            echo json_encode(array(
                'ok' => $dat
            ));
        }
    }
    
    
    
/**
 * Сохраняем скриптовый файл
**/     
    function save_robots()
    {                
        if($this->input->post('params'))
        {
            $params = $this->input->post('params');               
                                 
            if(write_file($_SERVER["DOCUMENT_ROOT"].'/robots.txt',$params['moduleName'])) // сохраняем настройки в файл
            {
                $data = notification($this->alang->save);                
            }
            else
            {                
                $data = notification($this->alang->save_error,'red');
            }
            
            $dat = $this->admin_view('admin/template/message',$data,true);
                    
            
            // Возвращаем json
            echo json_encode(array(
                'ok' => $dat
            ));
        }
    }    

   
/** ---------------------------------------------------------------------------------------------------- **/    


/**
 * List the data
 * @param $nTable - table name 
 * @param $nModule - module name
 * @param $fields - array fields for created list 
 * @param $options - more options for fileds new type
 * @param $id_or_link - what key you need to pull out
 * @param $numberPage - number page for pull with table data - count rows on the page
 * @param $wordSearch - word search with table data
 * @param $optionsSearch - function search
**/ 
    function list_table($nTable, $nModule, $fields, $options = '', $id_or_link = 'link', $numberPage = 0, $wordSearch = NULL, $optionsSearch = 'search')
    {                                                   
        // Устанавливаем префикс таблицы
        $table_prefix = $this->db->dbprefix($nTable);                      
        $dat['table'] = '';        
        $dat['moduleName'] = $nModule;
        // подключаем модель
        $this->load->model('admin/module_model','model');
         
        // проверяем существует ли указанная таблица                        
        if($this->db->table_exists($table_prefix))
        {                                                                                                
            // вытаскиваем название модуля, модуль, перелинковку           
            include(MDPATH.$nModule.'/moduleinfo.php');                         
            
            // проверяем существует ли перенаправление для модуля
            $anchor = isset($moduleinfo['router']) ? $moduleinfo['router'] : '';            
            $anchor = !empty($anchor) ? $anchor : $moduleinfo['name'];
            
            // название модуля
            $dat['captionTable'] = anchor($anchor, $moduleinfo['title'], 'class="with-tooltip tooltip-right white icon-link" title="'.$this->alang->admin_13.'" target="_blank"');                        
            
            // Вытаскиваем информацию о полях для текушей таблицы
            $table_info = $this->model->all_data_where('table_info', array('table'=>$table_prefix), 'table');
            
            // Вытаскиваем все данные таблицы            
            if($wordSearch != NULL and $wordSearch != 'empty') // если есть данные для поиска | то есть передана строка поиска
            {                
                $table_data = $this->model->$optionsSearch($table_prefix, $wordSearch);                                 
            }
            else
            {            
                $numberPageExplode = explode('|',$numberPage);
                if(!isset($numberPageExplode[1])) { $numberPageExplode[1] = null; }

                $table_data_get = $this->model->pagination($table_prefix, $numberPageExplode[0], $numberPageExplode[1]);
                  
                $table_data = $table_data_get['rows']; // записи           
                $dat['count_pages'] = $table_data_get['pages']; // количество страниц
                $dat['count_rows'] = $table_data_get['count']; // количество записей все
                $dat['per_paging_rows'] = $table_data_get['paging_rows']; // количество записей на страницу
            }
            
            $lng = $this->session->userdata('languages'); // передаем язык в переменную            
            if($lng == 'english') { $prfx = '_en'; } else { $prfx = ''; }
                        
            if(isset($table_info[0]['table']))
            {                                                                
                $comment = unserialize($table_info[0]['comment'.$prfx]);  // берем названия полей которые будут вытаскиваться              
                $new_type = unserialize($table_info[0]['new_type']); // берем типы полей (если установлены новые типы)
                                                                
                foreach($fields as $pl=>$kk)
                {                    
                    $titles = '';                    
                    if(!empty($kk['title'])) // проверяем есть ли название поля
                    { 
                        $titles = $kk['title']; 
                    } 
                    else 
                    { 
                        $titles = $comment[$pl]; 
                    }                    
                    $dat['headingTable'][$titles] = $kk; // Заголовки таблицы
                    $typeRow[$pl] = isset($new_type[$pl]) ? $new_type[$pl] : ''; // проверяем на тип поля                                                                              
                }
                
                if(count($table_data) > 0) // если данные есть
                {
                    foreach($table_data as $dtKey=>$p) // проходим цикл 
                    {                    
                        $pLink = '';
                        
                        foreach($fields as  $pl=>$kk) // определяем только то что нам нужно
                        {                                                                                    
                            if($typeRow[$pl] == 'input_image') // если тип поля картинка
                            {                                
                                $dani = "<img src=\"{$p[$pl]}\" alt=\"\" height=\"30\" />";
                            }
                            elseif($typeRow[$pl] == 'input_enum') // если тип поля выпадающий список
                            {                                                                                                         
                                if($pl != 'banned')
                                {
                                    $dani = $options[$pl][$p[$pl]];
                                }
                            }
                            else // иначе
                            {
                                $dani = $p[$pl]; 
                            }                                                               
                            
                            // Для модуля авторизация
                            if($nModule == 'auth')
                            {
                                if($pl == 'id')
                                {
                                    $dani = $options[$pl][$p[$pl]];
                                }
                            } // #Для модуля авторизация                                                                                                                                         
                            
                            if($pl == 'sort') // если есть поле сортировка
                            {
                                $dani = "
                                    <input style=\"width: 100%;\" value=\"{$dani}\" data-action=\"sort\" data-id=\"{$p['id']}\" class=\"sorte\" />
                                    <span style='display: none;'>{$p[$pl]}</span>
                                ";
                            }
                            elseif($pl == 'title') // если есть поле seo Title 
                            {
                                $dani = "
                                    <input style=\"width: 100%;\" class=\"title\" data-action=\"title\" id=\"title_{$p['id']}\" data-id=\"{$p['id']}\" value=\"{$dani}\" />
                                    <span style='display: none;'>{$p[$pl]}</span>    
                                ";
                            }
                            elseif($pl == 'banned') // если есть поле видимости
                            {                     
                                if($nModule != 'cart')
                                {
                                    $ch = '';
                                    if($p[$pl] == 0)
                                    {
                                        $ch = 'checked';
                                    }
                                    
                                    $dani = "
                                        <input type=\"checkbox\" id=\"switch_{$p['id']}\" class=\"switch switches mini wide\" data-id=\"{$p['id']}\" value=\"\" {$ch}> 
                                        <span style='display: none;'>{$p[$pl]}</span>   
                                    ";
                                }
                                else
                                {
                                    $dani = $options[$pl][$p[$pl]];
                                }
                            }
                            elseif($pl == 'name') // если есть поле названия (заголовка строки таблицы)
                            {
                                $no_link = array(
                                    'menu',
                                    'widget',
                                    'admenu',
                                    'pages'
                                ); // не создавать ссылку для
                                
                                if(!isset($p['link'])) { $pLink = ''; } else { $pLink = $p['link']; }
                                
                                if(!in_array($nModule, $no_link)) // проверяем доступность таблицы
                                {
                                    if($pLink == 'main') { $pLink = ''; } // если ссылка main то страница будет главная
                                    if(!empty($nModule)) { $nModules = BASEURL.'/'.$nModule; } // задаем путь для перехода на сайт с админки на конкретный адресс
                                    
                                    $dani = "<a target='_blank' href='{$nModules}/admin/index/edit/{$p['id']}' class='with-tooltip' title='".$this->alang->admin_14."'>{$dani}</a>";
                                }
                            }
                            elseif($pl == 'date_add' || $pl == 'date_edit'){
                                $dani = date('d.m.Y H:i:s', $dani);
                            }
                                                                                 
                            $dat['table'][$p['id']][$pl] = $dani; // создаем массив с данными                                                                                                                
                        }
                                               
                        if($id_or_link == 'link') 
                        {                             
                            $dat['identifier'][$p['id']] = $pLink;
                        }
                        unset($table_data[$dtKey]);                        
                    }
                }                                
            }
        }
        
        return $dat;               
    }
    

/** ---------------------------------------------------------------------------------------------------- **/ 
    
    
    /**
     *  Вывод полей таблицы для редактирования или добавления данных в БД
     *  @param table - таблица
     *  @param mas - массив с данными
     *  @param valid - масив переменных которые нужно проверять
     *  @param $params - массив параметров
    **/
    function add_edit_data($table, $module, $mas = NULL, $valid = NULL, $params, $info)
    {
        $dat = '';                                                              
                   
        if($this->db->table_exists($table))
        {
            // Валидация           
            if(isset($mas))
                $dat = $this->validate2('','.serial_form'); 
            else
                $dat = $this->validate2('add','.serial_form');
                 
            $dat['uri'] = $this->uri->uri_string();
            $dat['uri_list'] = $module.'/admin/index';                 
            
            $tb = $table;
            
            // добавляем префикс для таблицы
            $table = $this->db->dbprefix($table);        
            
            // подключаем драйвер для работы с таблицей по вытаскиванию полей
            $this->load->dbforge();
            
            // массив объектов содержащих информацию о поле
            $ob_polya = $this->db->field_data($table);
            
            $this->load->model('admin/module_model','model');
            
            // Вытаскиваем информацию о полях для текушей таблицы
            $table_info = $this->model->module_TableInfo($table);
           
            $lng = $this->session->userdata('languages'); // передаем язык в переменную            
            if($lng == 'english') { $prfx = '_en'; } else { $prfx = ''; }
            
            if(isset($table_info[0]['table']))
            {                                
                // Формируем массивы
                $comment = unserialize($table_info[0]['comment'.$prfx]);                           
                $new_type = unserialize($table_info[0]['new_type']);
                $placeholder = unserialize($table_info[0]['placeholder']);
                $info = unserialize($table_info[0]['info']);
                $fieldType = unserialize($table_info[0]['fileld_type']);              
                              
                foreach($ob_polya as $p)
                {
                    if(isset($comment[$p->name]))
                    {                                              
                        $prms = isset($params[$p->name]) ? $params[$p->name] : ''; // параметры
                        $vls = isset($mas[$p->name]) ? $mas[$p->name] : $p->default; // значение по умолчанию
                        
                        $types = $new_type[$p->name]; // тип поля            
                        if(empty($types)) 
                            { $types = $this->arrayType[mb_strtoupper($p->type)]; }
                        
                        $option = $fieldType[$p->name]; // опции
                        if(empty($option)) 
                            { $option = $this->arrayOptions; }
                        
                        if($types == 'multi-select')
                            { $vls = unserialize($vls); }
                        
                        if($types == 'multi-image')
                            { $vls = unserialize($vls); $vls = array( 'foto' => $vls['foto'], 'alt' => $vls['alt'] ); }
                        
                        $dat['table'][$p->name] = array(
                            'name' => $p->name, // имя
                            'id' => $p->name, // идентификатор
                            'type' => $types, // тип поля
                            'label' => $comment[$p->name], // название поля для <label></label>
                            'value' => $vls, // значение по умолчанию
                            'params' => $prms, // параметры
                            'valid' => isset($valid[$p->name]) ? $valid[$p->name] : '', // нужна ли проверка поля | валидация
                            'info' => $info[$p->name], // информация о поле
                            'placeholder' => $placeholder[$p->name],
                            'options' => $option
                        );
                        
                    }  
                }        

                if(isset($mas))
                {
                    $dat['table']['id'] = array( 
                            'name' => 'id', // имя
                            'type' => 'hidden', // тип поля
                            'label' => '', // название поля для <label></label>
                            'value' => $mas['id'], // значение по умолчанию
                            'params' => '' // параметры
                        );
                        
                    $dat['tableId'] = $mas['id'];
                }                            
                                
                // вытаскиваем название модуля    
                include(MDPATH.$module.'/moduleinfo.php');                
                
                if(isset($mas))
                {
                    $dat['button'] =  $this->alang->admin_saved;
                    $dat['header'] =  $moduleinfo['title'].' - '.$this->alang->admin_editing;                    
                }
                else
                {
                    $dat['button'] =  $this->alang->admin_add;
                    $dat['header'] =  $moduleinfo['title'].' - '.$this->alang->admin_addition;
                }                                
            }
        }                   

        // возвращаем все данные в виде массива
        return $dat;                                 
    }


/** ---------------------------------------------------------------------------------------------------- **/ 

    /** Список полей таблицы */
    function list_fields($table)
    {        
        $explodeTable = explode('_',$table);      
        $tbls = $explodeTable[0];
        $tbs = $table;          
        
        include(MDPATH.$table.'/moduleinfo.php');
        $moduleInfoTable = $moduleinfo['table'];
        $moduleInfoTitle = $moduleinfo['title'];
        
        if($explodeTable[0] == 'auth')
        {
            if($explodeTable[1] == 'roles')
            {
                $moduleInfoTable = 'auth_roles';
                $moduleInfoTitle = 'Роли(Roles)';  
            }
            elseif($explodeTable[1] == 'user')
            {
                $moduleInfoTable = 'auth_user_profile';
                $moduleInfoTitle = 'Профиль(Profile)';  
            }
        }
        
        $tb = $moduleInfoTable;
        $table = $this->db->dbprefix($tb);        
        $this->load->model('admin/module_model','model');
        
        // начинаем процесс сохранения
        if($this->input->post('dani'))
        {                                         
            // ловим данные
            $sender = $this->input->post('dani');            
            
            foreach($sender as $key => $array)
            {
                // если данное поле не помечено для удаления, то формируем данные                
                if($array['del'] == 'false') 
                { 
                    $comment[$key] = $array['label'];
                    $comment_en[$key] = $array['label_en'];
                    $placeholder[$key] = $array['placeholder'];
                    $info[$key] = $array['info'];
                    $newType[$key] = $array['new_type'];
                    $fileldType[$key] = $array['options'];
                }                                
            }

            $mas['comment'] = serialize($comment);
            $mas['comment_en'] = serialize($comment_en);
            $mas['placeholder'] = serialize($placeholder);
            $mas['info'] = serialize($info);
            $mas['new_type'] = serialize($newType);
            $mas['fileld_type'] = serialize($fileldType);                                  
            
            // сохраняем все и выводим сообщение
            if($this->model->edit_tableInfo($table, $mas))
                { $mes = notification($this->alang->admin_action_completed); }
            else
                { $mes = notification($this->alang->admin_action_completed); }
                        
            $return = $this->admin_view('admin/template/message', $mes, true);           
        }
        else // вытаскиваем все для редактирования
        {
            // подключаем драйвер для работы с таблицей по вытаскиванию полей
            $this->load->dbforge();
            
            // массив объектов содержащих информацию о поле
            $ob_polya = $this->db->field_data($table);                        
                
            // Вытаскиваем информацию о полях для текушей таблицы
            $table_info = $this->model->all_data_where('table_info', array('table'=>$table), 'table');
            
            // Формируем массивы
            $comment = unserialize($table_info[0]['comment']);
            $comment_en = unserialize($table_info[0]['comment_en']);            
            $new_type = unserialize($table_info[0]['new_type']);
            $placeholder = unserialize($table_info[0]['placeholder']);
            $info = unserialize($table_info[0]['info']);
            $fieldType = unserialize($table_info[0]['fileld_type']);                                              
            
            
                                                                                                
            foreach($ob_polya as $p)
            {                                                        
                $option = $fieldType[$p->name]; // опции                                                
                if(empty($option)) 
                    { $option = $this->arrayOptions; }                                                
                                                
                $types = $new_type[$p->name]; // тип поля            
                if(empty($types)) 
                    { $types = $this->arrayType[mb_strtoupper($p->type)]; }
                
                $dat['dynamicSend'][$p->name] = array(
                    'label' => $comment[$p->name],
                    'label_en' => $comment_en[$p->name],
                    'placeholder' => $placeholder[$p->name],
                    'info' => $info[$p->name],
                    'type' => $p->type,
                    'new_type' => $types,
                    'del' => false,
                    'options' => $option                                                                              
                );                
            }
                               
            $dat['header'] =  $moduleInfoTitle;
            $dat['uri_list'] = $tbs;
                        
            $return = $this->admin_view('list_fields', $dat, true);
        }
        
        if(!$this->input->is_ajax_request()) 
            { $this->admin_display($return); } 
        else 
        {        
            echo json_encode(array(
                'ok' => $return
            ));
        }        
    }
        
}

/* End of file */

