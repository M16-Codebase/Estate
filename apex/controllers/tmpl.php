<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tmpl extends MY_Controller {	

    function __construct()
    {
        // конструктор
        parent::__construct();                                                          
    }   

	/**
    * Вывод шаблонов верстки
    */
    function tmp()
    {
        $data = array();           
        $link = $this->data['uri2']; // сегмент ссылки        
        if(empty($link)) { $link = 'link'; }                    
        $this->addVar('template', $this->load->view('tmpl/'.$link, $data, true));        
        $this->viewPage();
    }            

}