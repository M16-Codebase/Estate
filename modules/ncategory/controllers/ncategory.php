<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Районы
**/

class ncategory extends MY_Controller {
    
	private $conf; // конфиг файл
    private $lang; // языковый файл    
    
    function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'ncategory/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        
        // определяем, нужно ли использовать роутер
        if(!empty($moduleinfo['router']))
            { $this->link = $moduleinfo['router']; } 
        else
            { $this->link = $this->module; }                                                                                                                                                 
	}
    
    /** Роутер модуля */    
    function _remap($method, $argument)
    {            
        if($moduleinfo['status'] != 0) // проверка на доступность модуля 
            { return false; } 
        else 
        {        
            if(isset($argument[0]))  $u = $argument[0]; else $u = 0; // сегмент ссылки                         
            if(method_exists($this,$method)) // если существует метод, то запускаем его
            {
                if($method == 'index') { $this->index(); } else
                if($method == 'limit') { $this->limit($u); } else
                if($method == 'search') { $this->searchFunction($u); } else
                { show_404('Метода не существует: '. $this->uri->uri_string()); }            
            }
            else
            {
                if(is_numeric($method)) // если идет пагинация
                    { $this->index($method); }
                else // иначе просмотр конкретной записи
                    { $this->view(); }
            }
        }                                        
    }
    
    /** Опции модуля */
    function setOptions()
    {
        // загрузка настроек
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);

        // загрузка языка              
        $foreach = $this->load->language($this->table.'_mod', '', true);
        foreach($foreach as $key=>$l) { $this->lang->$key = htmlspecialchars_decode($l['name']); }  
                                 
        // выводить или не выводить хлебные крошки для модуля
        $this->noBreadcrumbs = $this->conf['breadcrumbs'];
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
                $data['rows']->$key->id = $value['id'];
                $data['rows']->$key->name = $value['name'];
                //$data['rows']->$key->foto = $value['mainfoto']; //thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->tooltip = $value['tootltip'];
            }                                                                                  
        }                        

        return $this->render('filters', $data); // формируем шаблон
	}

    /** Просмотр одной записи */
    function view()
    {
        $this->setOptions(); // заносим опции в переменные
        
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                                 	         
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;        
                               
        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные
        if(!empty($dataRow))
        {                       
            // $this->viewSession($dataRow['id']); // заносим в сессию ид
            
            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];
            
            // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
        }
        else
            { show_404('404: Страница - '.$this->uri->uri_string().' не найдена'); }
        
        // генерируем title, keywords, description
        $this->addVar('title', $dataRow['title']);
        $this->addVar('keywords', $dataRow['keywords']);
        $this->addVar('description', $dataRow['description']);
        
        // задаем крохи
        if(!empty($this->lang->md_breadcrumbs))
            { $brd = $this->lang->md_breadcrumbs; }
        else
            { $brd = $this->lang->md_header; }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);                
       
        $this->addVar('template', $this->render('default', $data)); // формируем шаблон        
        $this->viewPage($this->data); // выводим весь вид
    }

}
/* End of file */