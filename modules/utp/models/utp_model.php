<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class utp_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'utp/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                
        $this->conf = '';        
	}


    /**
     * Вытаскиваем все данные по указанной опции пагинации
    **/
	function pagination($link_id)
    {                                    
        $this->db->where('banned','0'); // вытаскивать только видимые данные
        //$this->db->order_by($this->sortMethod, $sort); // сортировка
        $this->db->like('link_id', $link_id);
        
        $query = $this->db->get($this->table);        
        $result = $query->result_array();
        return $result;        
        //return $this->lang_load($result, $this->table);
    }
 
}