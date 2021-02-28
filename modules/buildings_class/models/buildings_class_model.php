<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class buildings_class_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'buildings_class/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFilter($cat=0){
        $this->db->select('buildings_class.*');
        $this->db->where('buildings_class.banned', 0);
        $this->db->join('buildings', 'buildings.class = buildings_class.id', 'inner');
        $this->db->where('buildings.banned', 0);
        $this->db->like('buildings.razdelu', $cat);
        $this->db->distinct();
        $this->db->order_by('buildings_class.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

}