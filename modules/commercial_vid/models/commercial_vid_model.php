<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class commercial_vid_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'commercial_vid/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('sort', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFastFilter($cat=4){
        $this->db->select('commercial_vid.*');
        $this->db->where('commercial_vid.banned', 0);
        $this->db->join('buildings', 'commercial_vid.id = buildings.comm_vid', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('commercial_vid.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }
    
}