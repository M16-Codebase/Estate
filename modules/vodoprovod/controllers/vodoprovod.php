<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Водоснабжение
**/

class vodoprovod extends MY_Controller {     
    
	private $conf; // конфиг файл
    private $lang; // языковый файл    
    
    function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'vodoprovod/moduleinfo.php');                
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
        $this->setOptions(); // заносим опции в переменные
        
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                                          	         
        $data['rows'] = ''; // переменная для хранения данных                
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;
                
        $dataRow = $this->$model->pagination($offset); // вытаскиваем данные                        
        if($dataRow) // проверяем есть ли данные
        {                                    
            // проверяем нужно ли использовать пагинацию 
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module,
                'uri_segment' => 2,
                'num_links' => 2                
            );
            $data['pagination'] = $this->paginations($arrayPagination);
            // проходим цикл для формирования данных                                                                                                          
            foreach($dataRow as $key=>$value) 
            {
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->text = $value['short_text'];
            }                                                                                  
        }                        
                             
        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);                         
        
        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
            { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
            { $this->breadcrumbs($this->lang->md_header); }                                    

        $this->addVar('template', $this->render('default', $data)); // формируем шаблон                                                
        $this->viewPage($this->data); // выводим весь вид                                             
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

	/**
	* Vuvod limitnogo k-va zapisey
    * @param $offset = 3 - k-vo zapisey
    * @param $template = true - vuvod html
	**/
	function limit($offset = 3, $template = true)
	{		                   
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);
                         	         
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;
                
        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные           
        if($dataRow)
        {                                                                                                          
            foreach($dataRow as $key=>$value) // проходим цикл для формирования данных
            {                
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->text = $value['short_text'];                
            }                                                                                  
        }                        

        // возвращаем вид
        if($template)
            { return $this->render('default', $data); }
        else
            { return $data['rows']; }
	}
        
    /** Пагинация на ajax */
    function ajaxPagination()
	{		           
        $offset = $this->input->post('page',true); // какую страницу грузить, номер страницы
        $uri2 = $this->input->post('uri2',true);
        $uri3 = $this->input->post('uri3',true);
        $uri4 = $this->input->post('uri4',true);
        $uri5 = $this->input->post('uri5',true);

        /** код для вытаскивания данных */                                       
                                     
        // переменная шаблона
        $ok = '';                
        
        // что-то делаем со всеми данными, которые в $_POST
        echo json_encode(array(
            'ok' => $ok
        ));                                            
	} 
     
    /** Отзыв */
    function ajaxComment()
    {        
        $name = $this->input->post('name', true);
        $text = nl2br(htmlspecialchars($this->input->post('text', true)));        
        $id = $this->input->post('id', true);        
        $return = true; // выполнять ли запрос в базу
        $usedCaptcha = false; // использовать ли каптчу
        
        if($usedCaptcha)
        {
            $captcha = $this->input->post('captcha', true);                
            if($captcha == $this->session->userdata('captcha_num')) // проверка каптчи
                { $return = true; }
            else
                { $return = false; $ok = 'errorCaptcha'; }
        }
                
        if($return)
        {
            $model = $this->table.'_model';
            $this->load->model($this->module.'/'.$model, $model);
            
            $array = array(
                'tovar_id' => $id,
                'name' => $name,
                'short_text' => $text,
                'date' => date('Y-m-d')
            );
            if($this->$model->module_add($array))
                { $ok = 'success'; }
            else
                { $ok = 'failure'; }
        }        
        
        echo json_encode(array( 'ok' => $ok ));
    }  
    
    /** Поиск */
    function searchFunction($search = '')
    {
        if(empty($search))
        {
            $search = $this->input->post('search_', true);
            $this->session->set_userdata('search_', $search);
            redirect('/'.$this->link.'/search/'.$search);
        }        
        
        $search = $this->session->userdata('search_');
            	           
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);        
        
        $this->breadcrumbs('Поиск');
                                            
        /** данные */                
        
        // переменная шаблона    
        $this->addVar('template', $this->render('default', $data));
        $this->viewPage($this->data);          
     }   
        
    
}
/* End of file */