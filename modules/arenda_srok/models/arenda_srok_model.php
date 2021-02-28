<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class arenda_srok_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'arenda_srok/moduleinfo.php');                
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
        $this->db->select('arenda_srok.*');
        $this->db->where('arenda_srok.banned', 0);
        $this->db->join('arenda', 'arenda_srok.id = arenda.srok_id', 'inner');
        $this->db->where('arenda.banned', 0);
        $this->db->distinct();
        $this->db->order_by('arenda_srok.name', 'ASC');
        $query = $this->db->get($this->table);
        $res = $query->result_array();
        return $res;
    }

}