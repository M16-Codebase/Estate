<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class seo_meta_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'seometa/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getByLink($link){
        $this->db->select('*');
        $this->db->where('identity', $link);
        $query = $this->db->get($this->table);
        $result  = $query->row_array();
        return $result;
    }

}