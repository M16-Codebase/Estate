<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль pages
* Страницы
*
**/


class utp extends MY_Controller {

    public $admin = array();       
    
	function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'utp/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                                                                                  
	}
    

/** --------------------------------------------------------------------------------------------- **/
    
    
	/**
	* Выбираем из базы УТП под текущий модуль / страницу
	**/
	function utp($module, $link_id)
	{		           
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                                	         
        $data['rows'] = ''; // переменная для хранения данных

        // вытаскиваем данные
        $dataRow = $this->$model->pagination($module.'-'.$link_id);                
        if(!empty($dataRow))
        {                                                                                                         
            // проходим цикл для формирования данных
            foreach($dataRow as $keys=>$item)
            {
                $name = explode('|', $item['name']);
                $link = explode('|', $item['link']);
                foreach($name as $k=>$t)
                {
                    $key = 'key_'.$k; // переназначаем для возможности использовать прямым вызовом
                    $data['rows'][$keys]->$key->name = $t;
                    $data['rows'][$keys]->$key->link = $link[$k];   
                }                         
            }                                                                                  
        }                                                  
        
        return $this->render('utp', $data);                                                                          
	}
}
/* End of file */