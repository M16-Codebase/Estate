<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class elite_type_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'elite_type/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFastFilter($cat=3){
        $this->db->select('elite_type.*');
        $this->db->where('elite_type.banned', 0);
        $this->db->join('buildings', 'elite_type.id = buildings.elite_type', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('elite_type.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

    public function getByLink($link){
        $this->db->select('*');
        $this->db->where('banned', 0);
        $this->db->where('link', $link);
        $query = $this->db->get($this->table);
        $result  = $query->row_array();
        return $result;
    }
    
}