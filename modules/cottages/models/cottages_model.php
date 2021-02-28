<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class cottages_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'cottages/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getCottagesByParent($id = NULL){
        if ($id){
            $this->db->where('parent_id', $id);
        }
        $this->db->where('banned', '0');
        $this->db->order_by('ABS(identity)');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
}