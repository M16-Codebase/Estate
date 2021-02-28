<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 * Модуль Module
 * генератор модулей
 * 
**/
class Index extends MY_Admin {	

    function __construct()
    {
        // конструктор
        parent::__construct(); 
        
        header("Content-type: text/html; charset=utf-8");                                                
    } 
    

/** ---------------------------------------------------------------------------------------------------- **/             
   
     
/**
 *  Список модулей
**/
	function index()
	{			    
        $data['uri'] = $this->uri->uri_string();
        if($this->input->post('dani'))
        {            
            parse_str($this->input->post('dani',true),$dat);
        }
        else
        {                            
            $ignore = array(
                'admin', 'index.html', 'module', 'templateModule', 'favicon.ico'
            );
            
            $this->load->helper('directory'); 
            $map = directory_map(MDPATH,1);
            $mas='';  
            $yad = 1;
            $ak = 100;
            $nak = 250;
            $step = 0;



            foreach($map as $m)
            {
                if(!in_array($m,$ignore))
                {
                    $con = explode('_',$m);
                    include(MDPATH.$m.'/moduleinfo.php');                                           
                    
                    if($moduleinfo['status'] == 0) { $step = $nak;  $stat = 'Не активен'; $nak++;  }
                    elseif($moduleinfo['status'] == 1) { $step = $ak; $stat = 'Активен'; $ak++; }
                    elseif($moduleinfo['status'] == 2) { $step = $yad; $stat = 'Ядро'; $yad++; }
                                            
                    $mas[$step] = array(
                        'name' => $moduleinfo['name'],
                        'title' => $moduleinfo['title'],
                        'status' => $stat              
                    );            
                }
            }



            ksort($mas);
            $data['mas'] = $mas;
            $dat = $this->admin_view('admin/module',$data,TRUE);
        }

        if(!$this->input->is_ajax_request())
        {
            $this->admin_display($dat);
        }
        else
        {
            echo json_encode(array(
                'ok' => iconv('UTF-8', 'UTF-8//IGNORE', $dat)
            ));
        }
        
	}   
    

/** ---------------------------------------------------------------------------------------------------- **/ 
    

/**
 *  Сгенерировать новый модуль
**/
	function generate_modules()
	{                                    
        $data['url'] = BASEURL.'/'.urls($this->uri->uri_string()).'generate_modules';
        $data['form'] = '.serial_form';                 
        $data['valid'] = $this->admin_view('admin/template/validate',$data,true);
        
        if($this->input->post('dani'))
        {                                    
            $sended = parse_json($this->input->post('dani',true)); // прием данных                              
            $this->load->model('module/module_model','model'); // подгрузка модели
            $module_name = $sended['moduleName']; // имя модуля
            $mp = MDPATH.$module_name; // путь к модулю    
            
            if(!file_exists($mp)) // если есть такой путь
            {                
                mkdir($mp); // создание папки модуля                            
                $this->copy_files(MDPATH.'templateModule', MDPATH.$module_name, $sended); // копирование файлов шаблонного модуля с заменой                                                       
                $data = notification('Модуль '.$module_name.' успешно создан!'); 
            }    
            else
            {                    
                $data = notification('Модуль '.$module_name.' существует!','red');
            }   
          
            $dat = $this->admin_view('admin/template/message',$data,true);            
        }
        else // если не было отправлено запроса выводим форму генерирования модуля
        {                
            $dat = $this->admin_view('admin/generate',$data,TRUE);
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

    // функция копирования файлов (включая вложеные) из папки $source в $res 
    function copy_files($source, $res, $dtd){ 
        $hendle = opendir($source); // открываем директорию 
        while ($file = readdir($hendle)) { 
            if (($file!=".")&&($file!="..")) { 
                if (is_dir($source."/".$file) == true) { 
                    if(is_dir($res."/".$file)!=true) // существует ли папка 
                        mkdir($res."/".$file, 0777); // создаю папку 
                        $this->copy_files($source."/".$file, $res."/".$file, $dtd); 
                } 
                else{ 
                    if(!copy($source."/".$file, $res."/".$file)) {  
                        print ("при копировании файла $file произошла ошибка...<br>\n");  
                    }
                    else
                    {
                        
                        /** ----------------------------- **/
                            
                            // какой файл будем открывать
                            $files = $res."/".$file;
                            
                            // открываем файл
                            $fl = file_get_contents($files);     
                            
                            // перезаписываем переменные
                            $fl = str_replace('{moduleName}',$dtd['moduleName'],$fl);
                            $fl = str_replace('{moduleTable}',$dtd['moduleTables'],$fl);
                            $fl = str_replace('{moduleTitle}',$dtd['moduleTitles'],$fl);
                            $fl = str_replace('{moduleRouter}',$dtd['moduleRouter'],$fl);
                            $fl = str_replace('{moduleMenu}',$dtd['moduleMenu'],$fl);
                            
                            // сохраняем его
                            write_file($files,$fl);

                            if($file == 'templateModule.php')
                            {
                                // переименовуем
                                rename($files,$res."/".$dtd['moduleName'].'.php');
                            }
                            
                            if($file == 'templateModule_model.php')
                            {
                                // переименовуем
                                rename($files,$res."/".$dtd['moduleTables'].'_model.php');
                            }
                            
                            if($file == 'admin_templateModule_model.php')
                            {
                                // переименовуем
                                rename($files,$res."/admin_".$dtd['moduleTables'].'_model.php');
                            }
                            
                            if($file == 'templateModule_mod_lang.php')
                            {
                                // переименовуем
                                rename($files,$res."/".$dtd['moduleName'].'_mod_lang.php');
                            }
                                                        
                        /** ----------------------------- **/
                                                
                    }
                }  
            } // else $file == .. 
        } // end while 
        closedir($hendle);        
    }

/** ---------------------------------------------------------------------------------------------------- **/ 

    
/**
 *  Список языков | Редактирование языков     
 *  $lang - язык
 *  $mod - модуль
**/
	function list_language($lang=NULL,$mod=NULL)
	{		                
        if(!$this->input->is_ajax_request()) 
        { 
            die('Не верно передан запрос!');
        }
        
        if($lang != NULL) // проверяем передан ли язык
        {            
            if($this->input->post('dani')) // если была отправка формы
            {                
                if($mod != NULL) // если модуль присутствует в url
                {
                    $sended = parse_json($this->input->post('dani'));  // преобразование переменных с данными             
                                                            
                    $this->load->model('module/module_model','model');  // загружаем модель и переименовываем ее                  
                  
                    $i = 0;
                    foreach($sended as $it=>$vl) // преобразовываем данные в нужный нам массив
                    {                        
                        $key = str_replace('_title','',$it);
                        
                        if($key == 'md')
                        {
                            $key = 'md_title';
                        }
                        
                        if($key == 'apex')
                        {
                            $key = 'apex_title';
                        }
                        
                        $i++;
                        if($i == 1)
                        {
                            $value[$key]['name'] = $vl;
                        }
                        else
                        {
                            $value[$key]['title'] = $vl;
                            $i = 0;
                        }                                                
                    }            
                    
                    $write = $mod.'/language/'.$lang.'/'.$mod.'_mod_lang.php';
                    /*if($mod == 'auth')
                    {
                        $write = $mod.'/language/'.$lang.'/mod_lang.php';
                    }*/
                    
                    if($this->model->module_language($value,$write)) // передаем данные на обработку модели
                    {
                        // если все успешно обработано и сохранено
                        $data = notification($this->alang->admin_save);
                    }
                    else
                    {
                        // если возникли ошибки
                        $data = notification($this->alang->admin_save_error,'red');
                    }
                }
                else
                {
                    // если ошибка передачи модуля в url                    
                    $data = notification($this->alang->admin_save_error,'red');
                }
                
                $dat = $this->admin_view('admin/template/message',$data,true); // загрузка шаблона сообщения в переменную  
            }
            else // если отправки формы небыло и присутствует языковый файл в url
            {
                $params = $this->input->post('params'); // ловим переданные данные | передается только имя модуля
                   
                $data['url'] = BASEURL . '/'. urls($this->uri->uri_string()).$lang.'/'.$params['moduleName']; // формируем url для формы
                $data['form'] = '.language_form'; // клас формы
                $data['removeElem'] = '#timeoutId'; // идентификатор который нужно будет удалять после отправки формы                   
                $data['valid'] = $this->admin_view('admin/template/validate',$data,true); // подгрузка валидатора 
                
                $alt_path = MDPATH.$params['moduleName'].'/'; // задаем полный путь для модуля
                $data['lang'] = $this->load->language($params['moduleName'].'_mod',$lang,true,true,$alt_path); // подгружаем языковый файл                                         
                $dat = $this->admin_view('admin/language',$data,TRUE); // передаем все в шаблон и возвращаем данные в переменную
            }          
        }
        else // если язык не передан значит нужно вытянуть все языки модуля
        {                
            $params = $this->input->post('params'); // передаем название модуля через json
            
            $this->load->helper('directory'); // подгружаем хелпер для сканирования папок
            $map = directory_map(MDPATH.'/'.$params['moduleName'].'/language',1); // задаем папку для сканирования (без вложений)
            $mas=''; // создаем переменную (обнуляем ее)       
            $dat='<h4 class="with-padding margin-bottom message red">Записей не обнаружено <button class="button float-right compact icon-cross-round red-gradient glossy" onclick="$(this).parent().remove();"> Закрыть</button></h4>';
            
            if(!empty($map))
            {
                foreach($map as $m) // проходимся в цикле 
                {
                    if($m != 'index.html') // пропускаем если найден index.html
                    {                            
                        $mas[] = array( // записываем в массив названия папок
                            'name' => $m               
                        );            
                    }
                }       
                
                $data['moduleName'] = $params['moduleName'];
                $data['mas'] = $mas; // передаем данные для передачи в шаблон
                
                $co = count($mas);
                
                if($co > 1)
                {
                    $dat = $this->admin_view('admin/list_language',$data,TRUE); // загружаем шаблон с переменную
                }
                else
                {
                    $lang = 'russian';
                    $data['url'] = BASEURL . '/'. urls($this->uri->uri_string()).'list_language/'.$lang.'/'.$params['moduleName']; // формируем url для формы
                    $data['form'] = '.language_form'; // клас формы
                    $data['removeElem'] = '#timeoutId'; // идентификатор который нужно будет удалять после отправки формы                   
                    $data['valid'] = $this->admin_view('admin/template/validate',$data,true); // подгрузка валидатора 
                    
                    $alt_path = MDPATH.$params['moduleName'].'/'; // задаем полный путь для модуля
                    
                    if($params['moduleName'] == 'auth')
                    {
                        $data['lang'] = $this->load->language($params['moduleName'].'_mod',$lang,true,true,$alt_path); // подгружаем языковый файл
                    }
                    else
                    {
                        $data['lang'] = $this->load->language($params['moduleName'].'_mod',$lang,true,true,$alt_path); // подгружаем языковый файл                                         
                    }
                    
                    $dat = $this->admin_view('admin/language',$data,TRUE); // передаем все в шаблон и возвращаем данные в переменную
                }                                                                
            }            
        }

        // Возвращаем json
        echo json_encode(array(
            'ok' => $dat // передаем все обратно через json
        ));  
	}      


/** ---------------------------------------------------------------------------------------------------- **/ 
    
    
/**
 *  Удаление модуля
**/
	function drop_module()
	{		                                  
        if(!$this->input->is_ajax_request()) 
        { 
            die('Не верно передан запрос!');
        }
        
        $this->load->model('module/module_model','model');
        $params = $this->input->post('params',true);
        $module_name = $params['moduleName']; // название модуля        
        
        include(MDPATH.$module_name.'/moduleinfo.php');                
        
        if(!empty($module_name)) // если передан модуль
        {            
            if($this->model->drop_table($module_name)) // удаляем главную таблицу
            {
                $this->model->drop_tableInfo($module_name);
                                                
                $deldir = MDPATH.$module_name; // узказываем папку для удаления (тоесть модуль)           
                if(file_exists($deldir)) 
                {
                    drop_folders($deldir); // удаляем через ф-цию
                    if(!file_exists($deldir)) // проверяем или все удалено
                    {                        
                        if(isset($moduleinfo['router']))
                        {
                            $this->load->model('router/router_model');                
                            $this->router_model->module_deleteValue($module_name);
                            $this->router_model->write_router();
                        }
                        
                        // такой же проверкой делаем на БД                        
                        $data = notification('Модуль "'.$module_name.'" полностью удален!');
                    }
                    else
                    {
                        $data = notification('Ошибка! Не верно переданы параметры.','red');
                    }
                }
            }
            else
            {
                $deldir = MDPATH.$module_name; // узказываем папку для удаления (тоесть модуль)           
                if(file_exists($deldir)) 
                {
                    drop_folders($deldir); // удаляем через ф-цию
                    if(!file_exists($deldir)) // проверяем или все удалено
                    {
                        
                        if(isset($conf['module_router']))
                        {
                            $this->load->model('router/router_model');                
                            $this->router_model->module_deleteValue($module_name);
                            $this->router_model->write_router();
                        }
                
                        // такой же проверкой делаем на БД
                        $data = notification('Модуль "'.$module_name.'" полностью удален!'); 
                    }
                    else
                    {
                        $data = notification('Ошибка! Не верно переданы параметры.','red');
                    }
                }
            }   
        }
        else
        {
            $data = notification('Ошибка! Не верно переданы параметры.','red');
        }
                       
        $dat = $this->admin_view('admin/template/message',$data,true); // подгружаем сообщение

        // Возвращаем json
        echo json_encode(array(
            'ok' => $dat // передаем через json ответ
        ));  
	} 
    
    
/** ---------------------------------------------------------------------------------------------------- **/ 
    

           
}

/* End of file */

