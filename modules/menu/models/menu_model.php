<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class menu_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'menu/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        $this->sortMethod = 'sort';       
	}


    /**
     * Вытаскиваем все данные по указанной опции пагинации
    **/
	function pagination($offset = 0)
    {                                
        $this->db->where('banned', '0');
        $this->db->order_by($this->sortMethod, 'asc');
        
        $query = $this->db->get($this->table);        
        $result = $query->result_array();
                
        return $this->lang_load($result, $this->table);
    }
    
    /**
     * Если нужно выполнить отдельный какой-то скрипт
    **/ 
    public function all_data_where($table, $where, $id_sort = 'id' ,$sort = 'asc')
    {
        $this->db->order_by($id_sort, $sort);
        $this->db->where($where);
        $query = $this->db->get($table);       
        
        return $query->result_array();
    }         

}