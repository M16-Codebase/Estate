<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class builders_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'builders/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFilter($cat=0){
        $this->db->select('builders.*');
        $this->db->where('builders.banned', 0);
        $this->db->join('buildings', 'buildings.builder_id = builders.id', 'inner');
        $this->db->where('buildings.banned', 0);
        $this->db->like('buildings.razdelu', $cat);
        $this->db->distinct();
        $this->db->order_by('builders.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}