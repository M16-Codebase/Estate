<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class favorites_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'favorites/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getFavorites($ids){
        $this->db->where_in('id', $ids);
        $this->db->where('banned', '0');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function addToFavorites($mas){
        $this->db->select('*');
        $this->db->where('object_id', $mas['object_id']);
        $this->db->where('category', $mas['category']);
        $query = $this->db->get($this->table);
        $c = $query->result_array();
        if (count($c) == 0){
            $this->db->insert($this->table, $mas);
            return $this->db->insert_id();
        }
        else {
            return $c[0]['id'];
        }
    }

    public function delFromFavorites($id,$category){
        $this->db->where('object_id', $id);
        $this->db->where('category', $category);
        $this->db->delete($this->table);
    }
}