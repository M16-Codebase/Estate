<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class land_rayon_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'land_rayon/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFilter(){
        $this->db->select('land_rayon.*');
        $this->db->where('land_rayon.banned', 0);
        $this->db->join('land', 'land.rayon_id = land_rayon.id', 'inner');
        $this->db->distinct();
        $this->db->order_by('land_rayon.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
    
}