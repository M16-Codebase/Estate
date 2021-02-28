<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class abroad_country_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'abroad_country/moduleinfo.php');                
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
        $this->db->select('abroad_country.*');
        $this->db->where('abroad_country.banned', 0);
        $this->db->join('abroad', 'abroad_country.id = abroad.country_id', 'inner');
        $this->db->where('abroad.banned', 0);
        $this->db->distinct();
        $this->db->order_by('abroad_country.name', 'ASC');
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