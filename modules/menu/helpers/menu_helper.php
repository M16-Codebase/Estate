<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// создание бесконечного списка по данным из БД
// Результат: многомерный массив с данными
    function create_children_menu($res)
    {   
        $levels = array();
        $tree = array();
        $cur = array();        
        
        if(count($res) > 0)
        {
            foreach($res as $rows)
            {           
                $cur = &$levels[$rows['id']];
                $cur['parent_id'] = $rows['parent_id'];
                $cur['name'] = $rows['name'];
                $cur['link'] = $rows['link'];
                $cur['attach'] = $rows['attach'];
                $cur['class'] = $rows['class'];
                $cur['type'] = $rows['type_link'];

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
    function li_tree_menu($array)
    {

        $dt = '';
        $langPrefix = '';
        /*$langPrefix = config_item('language_prefix');
        if($array['type'] != 'link_http')
        {
            $langPrefix = $langPrefix == 'ru' ? '/' : '/'.$langPrefix.'/'; // определяем что главный язык русский
        }
        else
        {
            $langPrefix = '';
        }*/
        
        $ci = &get_instance();
        $ur1 = $ci->uri->uri_string();
        if($ur1 == '') { $ur1 = '11111111111111111'; }
        if(count($array) > 0)
        {                             
            foreach($array as $item=>$value)
            {   
                $value_class = '';
                if($value['link'] == '/'.$ur1)
                {
                    $value_class = 'active ';
                    $linked = "<a href=\"{$value['link']}\" {$value['attach']} class=\"\">{$value['name']}</a>";
                }
                else
                {
                    $linked = "<a href=\"{$value['link']}\" {$value['attach']} class=\"\">{$value['name']}</a>";
                }
                
                if(!isset($value['children']))
                {
                    $dt .= "<li class='{$value_class}{$value['class']}'><span class=\"border-top\"></span>$linked<span class=\"border-bottom\"></span></li>\n";
                }
                else
                {
                    $dt .= "<li class='{$value_class}{$value['class']} sf-with-ul'>$linked \n";                    
                    $dt .= "<ul>\n";
                }
                
                if(isset($value['children']))
                {                             
                    $dt .= li_tree_menu($value['children']);
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