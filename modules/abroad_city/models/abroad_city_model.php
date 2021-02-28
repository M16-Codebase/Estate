<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class abroad_city_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'abroad_city/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFastFilter(){
        $this->db->select('abroad_city.*');
        $this->db->where('abroad_city.banned', 0);
        $this->db->join('abroad', 'abroad_city.id = abroad.city_id', 'inner');
        $this->db->where('abroad.banned', 0);
        $this->db->distinct();
        $this->db->order_by('abroad_city.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

}