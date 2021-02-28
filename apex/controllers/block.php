<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Block extends MY_Controller {	

    function __construct()
    {
        // конструктор
        parent::__construct();                                                          
    }   

	function index()
    {                                                    
        $conf = config_item('apex_site_name');                              
        $data['rows']->header = $conf;
        $data['rows']->text = htmlspecialchars_decode(config_item('apex_site_text'));             
                                       
        // генерируем title, keywords, description                            
        $this->headSeo($conf,'','');        
        // задаем крохи        
        $this->breadcrumbs($conf);                   
        $this->data['template'] = $this->render(array('block'),$data);

        // выводим весь вид
        $this->viewPage($this->data); 
    }            

}