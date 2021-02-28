<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Exceptions extends CI_Exceptions {
	public function __construct() 
	{
        parent::__construct();
       // $this->errorshow = new Error404();
    }

	function show_404($page = '', $log_error = TRUE)
	{
		if ($log_error)
		{			
            log_message('error', '404 Page Not Found --> '.$page);
		}

        $CI =& get_instance();
        $contr = new MY_Controller();
        $CI->load->model('pages/pages_model','pagesModel'); // подгрузка модели

        $pageText = 'Страница не найдена';
        
        $contr->headSeo($pageText); // генерируем title, keywords, description
        $contr->breadcrumbs($pageText); // задаем крохи
        $data['row'] = '';

        $contr->output->set_status_header('404');
        $contr->data['template'] = $contr->render('error_404', $data);
        $contr->addVar('title', '404 — Страница не найдена');
        $contr->viewPage($this->data); // выводим весь вид
	}         	
}