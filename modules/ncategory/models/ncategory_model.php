<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class ncategory_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'ncategory/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll(){
        $this->db->where('banned', 0);
        $this->db->order_by('is_city', 'DESC');
        $this->db->order_by('name', 'ASC');

        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFastFilter($cat=0){
        $this->db->select('rayon.*');
        //$this->db->where('rayon.banned', 0);
        $this->db->join('rayon_buildings', 'rayon_buildings.rayon_id = rayon.id', 'inner');
        $this->db->join('buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('rayon.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

    public function getAllFilter($cat=0){
        $this->db->select('rayon.*');
        $this->db->where('rayon.banned', 0);
        $this->db->where('rayon.poly <>', '');
        $this->db->join('rayon_buildings', 'rayon_buildings.rayon_id = rayon.id', 'inner');
        $this->db->join('buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('rayon.name', 'ASC');
        $query = $this->db->get($this->table);
        $result = $query->result_array();

        return $result;
    }

    public function getAllFilterResidential(){
        $this->db->select('rayon.*');
        $this->db->join('buildings', 'buildings.rayon_id = rayon.id', 'inner');
        $this->db->like('buildings.razdelu', 2);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('rayon.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getAllFilterArenda(){
        $this->db->select('rayon.*');
        $this->db->where('rayon.banned', 0);
        $this->db->where('rayon.poly <>', '');
        $this->db->join('rayon_arenda', 'rayon_arenda.rayon_id = rayon.id', 'inner');
        $this->db->join('arenda', 'rayon_arenda.arenda_id = arenda.id', 'inner');
        $this->db->where('arenda.banned', 0);
        $this->db->distinct();
        $this->db->order_by('rayon.is_city', 'DESC');
        $this->db->order_by('rayon.name', 'ASC');
        $query = $this->db->get($this->table);
        $result = $query->result_array();

        return $result;
    }

    public function getByLink($link){
        $this->db->select('*');
        //$this->db->where('banned', 0);
        $this->db->where('link', $link);
        $query = $this->db->get($this->table);
        $result  = $query->row_array();
        return $result;
    }

}