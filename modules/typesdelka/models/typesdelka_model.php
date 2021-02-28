<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class typesdelka_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'typesdelka/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFastFilter($cat=4){
        $this->db->select('typesdelka.*');
        $this->db->where('typesdelka.banned', 0);
        $this->db->join('sdelka_arenda', 'sdelka_arenda.typesdelka_id = typesdelka.id', 'inner');
        $this->db->join('buildings', 'sdelka_arenda.building_id = buildings.id', 'inner');
        $this->db->like('buildings.razdelu', $cat);
        $this->db->where('buildings.banned', 0);
        $this->db->distinct();
        $this->db->order_by('typesdelka.name', 'ASC');
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