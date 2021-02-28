<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class matherial_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'matherial/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFastFilter($cat=2){
        $this->db->select('matherial.*');
        $this->db->where('matherial.banned', 0);
        $this->db->join('buildings', 'matherial.id = buildings.matherial_id', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('matherial.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

}