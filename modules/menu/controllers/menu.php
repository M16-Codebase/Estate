<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль menu
* Меню сайта
*
**/


class menu extends MY_Controller {

    public $admin = array();       
    
	function __construct()
	{
		// конструктор
		parent::__construct();
		header('Content-type: text/html; charset=utf-8');                                           
	}
    


/** --------------------------------------------------------------------------------------------- **/
    
    
	/**
	* Формируем меню
	**/
	function index()
	{		           
        // подгрузка                               
        $this->load->model('menu/menu_model','menuModel'); // модели
        
        // массив регионов
        $region = array(
            'header','center','footer'
        );
        
        $url = uri(1);
        
         
        
        // вытаскиваем данные
        $data_paging = $this->menuModel->pagination();
        
        // переменная для хранения данных
        $data = array();
        $menu = array();                   
        
        $this->load->helper('menu/menu');
        
        //$data_paging = array_reverse($data_paging);
        
        $langPrefix = config_item('language_prefix'); // префикс языка
        $langPrefix = $langPrefix == 'ru' ? '' : '/'.$langPrefix; // передаем префикс
        
        // проверяем есть ли данные
        if($data_paging)
        {                                                                                                                           
            /*// Страницы
            $this->load->model('pages/admin_pages_model');
            $parent_pages = $this->admin_pages_model->parent_id();   
            $parent_pages_id = array();
            foreach($parent_pages as $pg)
            {
                $parent_pages_id[$pg['id']] = $pg['link'] == 'main' ? BASEURL.'/' : $pg['link'];
            }
                        
            // Модули
            $mdl = $this->menuModel->all_data_where('module',array());    
            $parent_module_id = array();       
            if(count($mdl) > 0)
            {
                foreach($mdl as $pg)
                {
                    if(!empty($pg['router']))
                    {
                      $pp = BASEURL.$langPrefix.'/'.$pg['router'];  
                    }
                    else
                    {
                      $pp = BASEURL.$langPrefix.'/'.$pg['link'];  
                    }
                    $parent_module_id['link_page']['params'][$pg['id']] = $pp;
                }            
            }  */               
            
            // проходим цикл для формирования данных
            foreach($data_paging as $key=>$t)
            {
                if(in_array($t['region'], $region))
                {
                    if($t['type_link'] == 'link_page')
                    {
                        if($t['link'] == 'main') { $t['link'] = ''; }
                        $links = '/'.$t['link'];
                    }
                    elseif($t['type_link'] == 'link_site')
                    {
                        $links = $t['link'];
                        //if($links == '/') { $links = ''; }
                    }
                    else
                    {
                        $links = $t['link'];
                    }
                    
                    $mas = array(
                            $links,
                            $t['name'],
                            $t['id'],
                            $t['parent_id'],
                            $t['attach'],
                            $t['class']
                        );                                        
                    
                   if($mas[3] != 0)
                   {
                       $data['parent'][$t['region']][] = array(
                            'name' => $mas[1],
                            'link'  => $mas[0],
                            'id' => $mas[2],
                            'parent' => $mas[3],
                            'attach' => $mas[4],
                            'class' => $mas[5],
                            'type_link' => $t['type_link']
                       );
                       
                       $data['inArray'][$mas[3]] = $mas[3];
                   }    
                   else
                   {
                        $data['noparent'][$t['region']][] = array(
                            'name' => $mas[1],
                            'link'  => $mas[0],
                            'id' => $mas[2],
                            'parent' => $mas[3],
                            'attach' => $mas[4],
                            'class' => $mas[5],
                            'type_link' => $t['type_link']

                        );                                               
                   }
                   
                   $pr[$t['region']][] = array(
                            'name' => $mas[1],
                            'link'  => $mas[0],
                            'id' => $mas[2],
                            'parent_id' => $mas[3],
                            'attach' => $mas[4],
                            'class' => $mas[5],
                            'type_link' => $t['type_link']
                        );                                                        
                }            
            }                                                                                                          

            
            foreach($region as $r)
            {
                if(isset($pr[$r]))
                {
                    $menu[$r] = li_tree_menu(create_children_menu($pr[$r]));
                }
            }
        
        }                                                                                                         
                                                       
        return $menu;            
	}
    
}
/* End of file */