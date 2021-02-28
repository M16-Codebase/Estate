<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** ********************************************************* ADMINKA ******************************************************** **/

/**
|==========================================================
| Upload image
|==========================================================
*/
function upload_image($img_name, $img_path, $max_size='6500', $max_width='5120', $max_height='5120', $is_error = '', $is_resize = '', $is_resize_auto = '', $img_width = '', $img_height = '')
{
	$CI = &get_instance();
        
    $CI->load->library('image_lib');
    $config['upload_path'] = './'.$img_path;
	$config['allowed_types'] = 'gif|jpg|png|jpeg|bmp|JPG|gz|gzip';
	$config['max_size']	= $max_size;
	$config['max_width']  = $max_width;
	$config['max_height']  = $max_height;
    $config['overwrite'] = TRUE;                          		
	$CI->load->library('upload', $config);
    
    
    if ( ! $CI->upload->do_upload($img_name)) //foto1
		{
			$data['error'] = $CI->upload->display_errors();                                          
            $data['success'] = $_POST; 
            $CI->session->set_userdata(array('error'=>$data['error'],'success'=>$data['success']));    
            redirect(site_url().$is_error);                  			
		}	
		else
		{                                		
            $img = $CI->upload->data();
            $foto = $img_path.'/'.$img['file_name'];                                        
            
            if($is_resize != '')
            {
                $config['image_library'] = 'gd2'; // выбираем библиотеку
                $config['source_image']	= './'.$img_path.'/'.$img['file_name'];
                $config['create_thumb'] = FALSE; // ставим флаг создания эскиза
                $config['maintain_ratio'] = TRUE; // сохранять пропорции
                $config['width']	= $img_width; // задаем размеры
                $config['height']	= $img_height;
                $config['quality'] = 100; // качество
                                
                if($is_resize_auto != '')
                {
                    if($img['image_width'] > $img['image_height'])
                    {
                        $config['master_dim'] = 'height'; //Устанавливает размеры для создаваемого эскиза
                    }
                    else
                    {
                        $config['master_dim'] = 'width'; //Устанавливает размеры для создаваемого эскиза
                    }
                }
                else
                {
                    $config['master_dim'] = 'width';
                }
                $config['new_image'] = './'.$img_path.'/'; // путь к миниатюре                                    
                                                        
                $CI->image_lib->initialize($config); // загружаем библиотеку 
                
                if ( ! $CI->image_lib->resize())
                {
                $data['error'] = $CI->image_lib->display_errors();   
                $data['success'] = $_POST;                                     
                $CI->session->set_userdata(array('error'=>$data['error'],'success'=>$data['success']));                                                                                    
                redirect(site_url().$is_error);
                }
           }                                                                                 
		}
        
        //watermark($img_path.'/'.$img['file_name']);                
        
    return true;
}


/**
* Наложение водяного знака в виде изображения
* @param $oldimage_name - исходное изображение
* @param $new_image_name - выходное изображение
* @return Boolean
*/
function watermarks($oldimage_name){
	// Загрузка штампа и фото, для которого применяется водяной знак (называется штамп или печать)
    $stamp = imagecreatefrompng(site_url().'uploads/watermark/logo.png');
    $im = imagecreatefromjpeg($oldimage_name);
     
    // Установка полей для штампа и получение высоты/ширины штампа
    $marge_right = 10;
    $marge_bottom = 10;
    $sx = imagesx($stamp);
    $sy = imagesy($stamp);
    // Копирование изображения штампа на фотографию с помощью смещения края
    // и ширины фотографии для расчета позиционирования штампа.
    imagecopy($im, $stamp, imagesx($im) - $sx - $marge_right,
    imagesy($im) - $sy - $marge_bottom, 0, 0, imagesx($stamp),
    imagesy($stamp));
     
    // Вывод и освобождение памяти
    //header('Content-type: image/jpg');
    imagepng($im);
    imagedestroy($im);
}

/**
|==========================================================
| вытаскиваем укороченную на 1 шаг адресную строку
|==========================================================
*/
function urls($data_uri)
{
    $k = explode('/',$data_uri); 
    $co = count($k)-1;
    $new_uri = '';
    for($i=0; $i<$co; $i++)
    {
        $new_uri .= $k[$i].'/';
    }
    
    return $new_uri;
}


/**
|==========================================================
| Преобразуем json в нормальные переменные
|==========================================================
*/
function parse_json($data)
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
            elseif($d['name'] == 'recommend[]')
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
                    'foto' => serialize($foto), 
                    'alt' => serialize($alt) 
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




/**
|==========================================================
| Удаление папки
|==========================================================
*/
function drop_folders($delfile) //обзываем функцию. ниже- порядок ее действий
{
  if (file_exists($delfile)) //если файл/каталог создан - продолжай
  {
    chmod($delfile,0777); //выстави атрибут на файл/каталог 777,т.е. полный доступ
    if (is_dir($delfile)) //если это каталог - продолжай,если файл - выполни действия после "else"
      {
        $handle = opendir($delfile); //открой каталог
        while($filename = readdir($handle)) //цикл чтения и запись всего в переменную

          if ($filename != "." && $filename != "..") //в явном виде отменяем запись в переменную текущий и родительсткий каталоги, чтобы не потерять нужные данные
          {
            drop_folders($delfile."/".$filename); //удаляем файлы в каталоге          
          }

        closedir($handle); //закрываем каталог                  
        rmdir($delfile);  //удаляем уже пустой каталог
      }
    else                   //если это все таки это не каталог
      {
       unlink($delfile);    //удали файл
      }      
  }
}




/**
|==============================================================
| Изминение какой-то одной настройки + сохранение в тот же файл
|==============================================================
*/
function change_configs($path,$key,$value)
{
    $mdp = explode('/',$path);
    $mo = str_replace('.php','',$mdp[count($mdp)-1]);

    $ci =& get_instance();
    $ci->load->helper('file');    
    $pt = explode('/',$path);
    $co = count($pt);
    $pt = array_slice($pt,$co-3,$co);
    $write = $pt[0].'/config/'.$mo.'.php';    
    $string = $ci->load->config($pt[0].'/'.$mo,TRUE);        

    foreach($string as $it=>$vl) // проходим в цикле вытаскивая в массив переменную настройки, значение, описание настройки
    {
        if($it != $key) { $v = $vl['name']; } else { $v = $value; }
        $values[] = array(
            'name' => $it,
            'value' => $v,
            'title' => $vl['title'] 
        );
    }
    
    $set[] = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";            
    $set[] = "/**";
    $set[] = "* Конфигурационный файл модуля | {$pt[0]}";
    $set[] = "**/\n\n"; 
    
    foreach($values as $t) 
    {            
        $set[] = "\$config['{$t['name']}'] = array(";
        $set[] = "\t\t'name' => '{$t['value']}',";
        $set[] = "\t\t'title' => '{$t['title']}'";
        $set[] = "\t);";
    }                
    
    $output = implode("\n", $set);

    if(write_file(MDPATH . $write, $output))
        return true;
    return false;    
} 


function change_moduleinfo($path,$key,$value)
{
    $ci =& get_instance();
    $ci->load->helper('file');    
    $write = $path;    
    include($path);        

    foreach($moduleinfo as $it=>$vl) // проходим в цикле вытаскивая в массив переменную настройки, значение, описание настройки
    {
        if($it != $key) { $v = $vl; } else { $v = $value; }
        $values[$it] = $v;
    }
    
    $set[] = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";            
    $set[] = "// --------------------";
    $set[] = "// Информация о модуле";
    $set[] = "// --------------------\n\n"; 
    
    foreach($values as $t=>$vs) 
    {            
        $set[] = "\$moduleinfo['{$t}'] = '{$vs}';";
    }                
    
    $output = implode("\n", $set);

    if(write_file($write, $output))
        return true;
    return false;    
} 


function configSaveToFile($module, $array)
{
    $ci =& get_instance();
    $set[] = "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n";            
    
    if(!empty($array))
    {       
        foreach($array as $item=>$value) 
        {            
            $set[] = "\$config['{$item}'] = '{$value}';";
        }
    }
    else
    {
        $set[] = "\$config[''] = '';";
    }                
    
    $output = implode("\n", $set);
    
    $ci->load->helper('file');
    if(write_file(MDPATH.$module.'/config/'.$module.'.php', $output))
    {
        return notification('Настройки сохранены.');
    }
    else
    {
        return notification('Настройки не сохранены в конфигурационный файл.', 'red');
    }
}


/**
|==============================================================
| Подключение для формы валидатор
|==============================================================
*/
function validates($uri,$form='.serial_form')
{
    $ci = &get_instance();
    echo $ci->load->view('admin/template/validate',array('form'=>$form,'url'=>$uri),TRUE);
}


/**
|==============================================================
| Проверка на супер админа
|==============================================================
*/
function is_super()
{
    $ci = &get_instance();
    $ci->load->library('auth/DX_Auth');
    if($ci->session->userdata('DX_role_name') == 'super')
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
} 



/**
|==============================================================
| Возврат сообщения
| $mes - текст сообщения
| $color - цвет фона сообщения
|==============================================================
*/
function notification($mes,$color='green')
{    
    $data['mes'] = array(
        'text' => $mes,
        'color' => $color
    );
    
    $ci = &get_instance();    
    return $ci->load->view('admin/template/message',$data,true);   
}



// создание бесконечного списка по данным из БД
// Результат: многомерный массив с данными
    function create_children($res)
    {   
        $levels = array();
        $tree = array();
        $cur = array();        
        
        $lng = ses_data('languages'); // передаем язык в переменную            
        if($lng == 'english') { $prfx = '_en'; } else { $prfx = ''; }
        
        if(count($res) > 0)
        {
            foreach($res as $rows)
            {           
                $cur = &$levels[$rows['id']];
                $cur['parent_id'] = $rows['parent_id'];
                $cur['title'] = $rows['name'.$prfx];
                $cur['link'] = $rows['link'];
                $cur['icon'] = $rows['icon'];
                if(isset($rows['ajax'])) {$cur['ajax'] = $rows['ajax'];}
               
                if($rows['parent_id'] == 0){
                    $tree[$rows['id']] = &$cur;
                }
                else{
                    $levels[$rows['parent_id']]['children'][$rows['id']] = &$cur;
                }
               
            }
        }
        return $tree;       
    }
    
// Создание бесконечного списка по массиву        
    function option_tree($array,$arg='',$select='')
    {
        $dt = '';                                
        foreach($array as $item=>$value)
        {            
            $selec = '';            
            if($select == $item) { $selec = 'selected="selected"'; }
            
            if($arg == '')
            {
                $dt .= '<option '.$selec.' value="'.$item.'">'.$arg.$value['title'].'</option>';            
            }
            else
            {
                $dt .= '<option '.$selec.' value="'.$item.'">'.$arg.' '.$value['title'].'</option>';
            }
            
            if(isset($value['children']))
            {
                $argm = $arg.'&mdash;';                
                $dt .= option_tree($value['children'],$argm,$select);
            }            
        }
        
        return $dt;
    }    
    
// Создание бесконечного списка по массиву
    function li_tree($array)
    {                
        $dt = '';   
        if(count($array) > 0)
        {                             
            foreach($array as $item=>$value)
            {                        
                if(!isset($value['children']))
                {
                    $dt .= "<li><a href=\"/{$value['link']}\" class=\"menuClick {$value['icon']}\"> {$value['title']}</a></li>\n"; 
                }
                else
                {
                    $dt .= "<li class=\"with-right-arrow grey-arrow\">\n";
                    $dt .= "<span><span class=\"list-count menuClick\">".count($value['children'])."</span>{$value['title']}</span>\n";
                    $dt .= "<ul class=\"big-menu blue white-bg dark-text-bevel\">\n";
                }
                
                if(isset($value['children']))
                {                             
                    $dt .= li_tree($value['children']);
                }
                
                if(isset($value['children']))
                {
                    $dt .= "</ul>\n";
                    $dt .= "</li>\n";
                }            
            }
        }
        
        return $dt;
    }