<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class type_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'type/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFilter($cat=0){
        $this->db->select('type.*');
        $this->db->where('type.banned', 0);
        $this->db->join('buildings', 'buildings.type_id = type.id', 'inner');
        $this->db->where('buildings.banned', 0);
        $this->db->like('buildings.razdelu', $cat);
        $this->db->distinct();
        $this->db->order_by('type.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

}