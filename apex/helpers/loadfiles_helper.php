<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Хелперы подключения файлов
**/



/**
 ***********************************
 * Подключаем JS
 * $data - массив js файлов
 ***********************************  
**/ 
function js_files($array = NULL, $admin = false)
{
    $ci = &get_instance();
    
    if($admin){ $obertka = '<script src="'.BASEURL.'/asset/admin/js/%s.js"></script>'; }
    else { $obertka = '<script src="'.BASEURL.'/asset/js/%s.js?v1"></script>'; }
    
    $files='';

    if(!empty($array))
    {
        if(is_array($array))
        {
            if(!empty($array[0]))
            {
                foreach($array as $dat)
                {
                    if(is_array($dat))
                    {
                        foreach($dat as $d)
                        {
                            $files .= sprintf($obertka,$d)."\n\t";
                        }
                    }
                    else
                    {
                        $files .= sprintf($obertka,$dat)."\n\t";
                    }  
                }
            }   
        }
        else
        {
            $files .= sprintf($obertka,$array);
        }
    }
        
    return $files;
}


/**
 ***********************************
 * Подключаем CSS
 * $data - массив css файлов
 ***********************************  
**/ 
function css_files($array = NULL, $admin = false)
{
    $ci = &get_instance();

    if($admin){ $obertka = '<link href="'.BASEURL.'/asset/admin/css/%s.css" rel="stylesheet" type="text/css" />'; }
    else { $obertka = '<link href="'.BASEURL.'/asset/css/%s.css?v1" rel="stylesheet" type="text/css" />'; }

    $files='';
    
    if(!empty($array))
    {
        if(is_array($array))
        {
            if(!empty($array[0]))
            {
                foreach($array as $dat)
                {
                    if(is_array($dat))
                    {
                        foreach($dat as $d)
                        {
                            $files .= sprintf($obertka,$d)."\n\t";
                        }
                    }
                    else
                    {
                        $files .= sprintf($obertka,$dat)."\n\t";
                    }  
                }
            }   
        }
        else
        {
            $files .= sprintf($obertka,$array);
        }
    }
        
    return $files;
}	 


/**
 ***********************************
 * Подключаем Плагины
 * $plugin - массив плагинов
 ***********************************  
**/ 
function loadPlugin($plugin)
{               

    
    $ci = &get_instance();
    $pluginCount = count($plugin);    
    $return = '';
    
    if($pluginCount > 0)
    {
        foreach($plugin as $pl)
        {
            if(is_array($pl))
            {
                foreach($pl as $p)
                {
                    $return .= $ci->load->view('/plugin/'.$p,'',true);
                }
            }
            else
            {
                $return .= $ci->load->view('/plugin/'.$pl,'',true);
            }
        }
    }    
    
    return $return;
}
