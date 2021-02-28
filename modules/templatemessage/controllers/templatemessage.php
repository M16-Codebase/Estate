<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
*
* Модуль pages
* Страницы
*
**/


class templatemessage extends MY_Controller {

    public $admin = array();       
    
	function __construct()
	{
		// конструктор
		parent::__construct();
        
        include(MDPATH.'templatemessage/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                                                           
	}


/** --------------------------------------------------------------------------------------------- **/
 
 
    /**
     * Вытаскиваем шаблон
    **/
    function templMessage($templateNumber)
    {
        $this->db->where('id', $templateNumber);
        $query = $this->db->get('templatemessage');
        return $query->row_object();
    }
    
    
/** --------------------------------------------------------------------------------------------- **/
 
 
    /**
     * Отправка сообщения
    **/
    function templSend($templateNumber, $arrayData)
    {
        $templ = $this->templMessage($templateNumber);
        $return = false;

        if(!empty($templ))
        {            
            
            if($templ->send == 0)
            {            
                $result1 = sendMessage($templ, $arrayData);
                $result2 = sendMessage($templ, $arrayData, false);
                
                if($result1 && $result2)
                {
                    $return = true;
                }                
            }
            elseif($templ->send == 1)
            {                
                $return = sendMessage($templ, $arrayData, false);
            }
            elseif($templ->send == 2)
            {
                $templ->text = $templ->textadmin;
                $return = sendMessage($templ, $arrayData);
            }
        }
        
        return $return;
    }    
    

/** --------------------------------------------------------------------------------------------- **/
    
    
	/**
	* Отправка заявки
	**/    
    function zayavkaSend()
    {
        $name = $this->input->post('name');
        $phone = $this->input->post('phone');
        $urlPage = $this->input->post('urlPage', true);
        $h1 = $this->input->post('h1', true);
                
        $arrayData = array(
            'name' => $name,
            'phone' => $phone,
            'url' => $urlPage,
            'h1' => $h1,
            'email' => $config['apex_email']
        );
        $ok = $this->templSend(1, $arrayData);

        echo json_encode(array(
            'ok' => $ok
        ));                 
    }    
 

/** --------------------------------------------------------------------------------------------- **/
    
    

    
    
/** --------------------------------------------------------------------------------------------- **/
    





}
/* End of file */