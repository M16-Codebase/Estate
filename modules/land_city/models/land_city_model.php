<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class land_city_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'land_city/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFilter(){
        $this->db->select('land_city.*');
        $this->db->where('land_city.banned', 0);
        $this->db->join('land', 'land.city_id = land_city.id', 'inner');
        $this->db->distinct();
        $this->db->order_by('land_city.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

}