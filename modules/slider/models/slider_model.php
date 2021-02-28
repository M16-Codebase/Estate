<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class slider_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'slider/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getSliders($id = NULL){
        if($id != NULL)
        {
            $this->db->where('category', $id);
            $this->db->where('banned', 0);
        }

        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}