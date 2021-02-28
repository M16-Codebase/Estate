<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Партнеры
**/

class parthner extends MY_Controller {     
    
	private $conf; // конфиг файл
    private $lang; // языковый файл    
    
    function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'parthner/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
	}

	function index($offset = 0)
	{
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                                          	         
       $data['rows'] = (object)array(); // переменная для хранения данных                

        $dataRow = $this->$model->getMainPartners(); // вытаскиваем данные
        if ($dataRow) // проверяем есть ли данные
        {
            // проходим цикл для формирования данных                                                                                                          
            foreach($dataRow as $key=>$value) 
            {
                $data['rows']->$key = (object)array();
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->foto = $value['mainfoto'];
            }
        }                        

        echo $this->render('tmpl/parthner', $data); // формируем шаблон
	}

    function list_partners($offset = 0)
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
                if ($value['banned'] == 0){
                    $data['rows']->$key->name = $value['name'];
                    $data['rows']->$key->foto = $value['mainfoto'];
                    $data['rows']->$key->site = $value['site'];
                    $data['rows']->$key->text = $value['text'];
                }
            }
        }
        $this->addVar('title', "Наши партнеры"); // формируем шаблон
        $data['pageHeader'] = 'Наши партнеры';
        $this->addVar('template', $this->render('partners', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид

    }

}
/* End of file */