<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_auth_roles_model extends CI_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();   
                            
        $this->module = 'auth';
        $this->table  = 'auth_roles';
        $this->title  = $moduleinfo['title'];                
    }    
    
    
/** ************************************************************************************************************************************ **/
    
    /**
	* Добавление записи
	**/
	public function module_add($mas)
	{                                  
        return $this->db->insert($this->table, $mas);
	}
    
    
    /**
	* Вытаскиваем записи
	**/
    public function module_get($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get($this->table);               
        
        return $query->row_array();                        
    }
    
    
    /**
	* Редактирование записи
	**/
	public function module_edit($id, $mas)
	{        
        $where = array(
            'id' => $id
        );
   
        return $this->db->update($this->table, $mas, $where);
	}
    
    
    /**
	* Удаление записей
	**/
	public function module_delete($id)
	{
		$where = array(
            'id' => $id
        );
        
        if($this->db->delete($this->table, $where))
        {
            $this->db->delete('auth_user_profile', array('user_id' => $id));
            return true;
        }
        else
        {
            return false;
        }
	}   
    
    
/** ********************************************************************************************************************************** **/    
    
    
    /**
	* Вытаскиваем родителя или все поля
	**/
    public function parent_id($id = NULL)
    {
        if($id != NULL)
        {
            $this->db->where('id', $id);
        }
        
        $query = $this->db->get($this->table);               
        return $query->result_array();
     }

     
    /**
     * Если нужно выполнить отдельный какой-то скрипт
    **/ 
    public function all_data_where($table, $where, $id_sort='id' ,$sort = 'asc')
    {
        $this->db->order_by($id_sort, $sort);
        $this->db->where($where);
        $query = $this->db->get($table);       
        
        return $query->result_array();
    }
    
         
}