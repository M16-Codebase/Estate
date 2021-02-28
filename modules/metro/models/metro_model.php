<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class metro_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'metro/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get($this->table);
        
        return $query->result_array();
    }

    public function getAllFilter($cat = 0)
    {
        $this->db->select('metro.id');
        $this->db->where('metro.banned', 0);
        $this->db->join('metro_buildings', 'metro_buildings.metro_id = metro.id', 'inner');
        $this->db->join('buildings', 'metro_buildings.building_id = buildings.id', 'inner');
        $this->db->where('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('metro.name', 'ASC');
        $query  = $this->db->get($this->table);
        $res    = $query->result_array();
        $result = array();
        
        foreach ($res as $k=>$v)
            $result[] = $v['id'];
        
        return $result;
    }

    public function getAllFastFilter($cat=0){
        $this->db->select('metro.*');
        $this->db->where('metro.banned', 0);
        $this->db->join('metro_buildings', 'metro_buildings.metro_id = metro.id', 'inner');
        $this->db->join('buildings', 'metro_buildings.building_id = buildings.id', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('metro.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

    public function getAllFilterArenda(){
        $this->db->select('metro.id');
        $this->db->where('metro.banned', 0);
        $this->db->join('metro_arenda', 'metro_arenda.metro_id = metro.id', 'inner');
        $this->db->join('arenda', 'metro_arenda.arenda_id = arenda.id', 'inner');
        $this->db->where('arenda.banned', 0);
        $this->db->distinct();
        $this->db->order_by('metro.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        $result = array();
        foreach ($res as $k=>$v){
            $result[] = $v['id'];
        }
        return $result;
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