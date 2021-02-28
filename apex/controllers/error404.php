<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Error404 extends MY_Controller {	

    function __construct()
    {
        // конструктор
        parent::__construct();                                                          
    }   

	function index()
    {    
        header("HTTP/1.0 404 Not Found");
        $this->output->set_status_header('404');
        $this->load->model('pages/pages_model','pagesModel'); // подгрузка модели                   
        
        $pageText = 'Страница не найдена';                                                                                   
        $this->headSeo($pageText); // генерируем title, keywords, description                     
        $this->breadcrumbs($pageText); // задаем крохи   
        
        $data['row'] = '';
        /*$this->load->model('blog/blog_model');     
        $conf = $this->load->config('blog/blog', true);   
        $data['row'] = $this->blog_model->limitRow($conf['newBlog'], 'id'); */

        $this->data['title'] = '404 — Страница не найдена';       
        $this->data['template'] = $this->render('error_404', $data);        
        $this->viewPage($this->data); // выводим весь вид
    }
    
    
    

}