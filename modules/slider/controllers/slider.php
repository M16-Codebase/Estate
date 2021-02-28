<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Слайдер
**/

class slider extends MY_Controller {     
    
	private $conf; // конфиг файл
    private $lang; // языковый файл
    
    function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'slider/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
	}

	/** Главная */
	function index($offset = 0)
    {
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                                          	         
        $data['rows'] = ''; // переменная для хранения данных
        $dataRow = $this->$model->pagination($offset); // вытаскиваем данные                        
        if($dataRow) // проверяем есть ли данные
        {
            // проходим цикл для формирования данных                                                                                                          
            foreach($dataRow as $key=>$value) 
            {
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = $value['likns'];
                $data['rows']->$key->foto = $value['mainfoto'];
            }                                                                                  
        }

        echo $this->render('tmpl/slider', $data); // формируем шаблон
	}
}
/* End of file */