<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class pages_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'pages/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];  
        $this->conf = '';
	}     
    
    
    function searchs($terms, $table)
    {
        $sql = "SELECT name, link 
                    FROM  ci_$table                                      
                    WHERE  
					`banned` = '0' AND
                        `name` LIKE  '%$terms%' 
                    OR  `title` LIKE  '%$terms%'                              
                    OR  `text` LIKE  '%$terms%'    
                    COLLATE utf8_unicode_ci                           
                ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }   
}