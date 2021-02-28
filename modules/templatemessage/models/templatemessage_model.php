<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class templatemessage_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'templatemessage/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        $this->sortMethod = 'sort';
        
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);        
	}


    /**
     * Вытаскиваем все данные по указанной опции пагинации
    **/
	function pagination($offset = 0)
    {                                
        $sort = 'asc';
        
        if(isset($this->conf['sortList']))
        {
            $sort = $this->conf['sortList'];
        }        
        
        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->order_by($this->sortMethod, $sort); // сортировка
        
        // проверяем нужно ли использовать пагинацию
        if($this->conf['Paging'])
        {                
            $per_paging = $this->conf['perPaging']; // количество записей на страницу
            
            if($offset == 0 or $offset == 1)
            {
                $offset = 0; 
            }
            else
            {
                $offset = ($offset-1) * $per_paging;
            }
                        
            $this->db->limit($per_paging, $offset);
        }
        
        $query = $this->db->get($this->table);        
        $result = $query->result_array();
        return $result;
                
        //return $this->lang_load($result, $this->table);
    }
    
    
    /**
     * Вытаскиваем одну определенную запись
    **/
	function getRow($link = NULL, $id = NULL)
    {
        // вытаскивать только видимые данные
        $this->db->where('banned', '0');
        
        if($link != NULL) 
        {  
            $this->db->where('link', $link); 
        } 
        elseif($id != NULL) 
        {  
            $this->db->where('id', $id); 
        }
        else 
        { 
            return false; 
        }
        
        $query = $this->db->get($this->table);        
        $result = $query->row_array();            
        return $result;        
        //return $this->lang_load($result, $this->table);
    }
    
    
    /**
     * Общее количество записей в таблице
    **/    
    function all_count($banned = false)
    {
        if(!$banned)
        {
            $this->db->where('banned','0');
        }
        $query = $this->db->get($this->table)->result_array();                
        return count($query);
    }


    /**
     * Вытаскиваем лимитное количество записей
    **/
	function limitRow($offset = 0)
    {                
        $this->db->where('banned', '0'); // вытаскивать только видимые данные
        $this->db->order_by($this->sortMethod, $this->conf['sortList']); // сортировка
        
        // проверяем на лимит
        if($offset != 0)
        {                                        
            $this->db->limit($offset);                
            $query = $this->db->get($this->table);        
            $result = $query->result_array();
            return $result;    
            //return $this->lang_load($result, $this->table);
        }
        else
        {
            return false;
        }
    } 


    /**
     * Если нужно выполнить отдельный какой-то скрипт
    **/ 
    public function all_data_where($table, $where, $id_sort = 'id' ,$sort = 'asc')
    {
        $this->db->order_by($id_sort, $sort);
        $this->db->where($where);
        $query = $this->db->get($table);               
        $result = $query->result_array();
        return $result;        
        //return $this->lang_load($result, $this->table);
    } 
    
    /**
     * Вытащить рандомно 1 строку
    **/       
    function randomRow($limit = 1)
    {
        $query = $this->db->query('SELECT * FROM ci_'.$this->table.' ORDER BY RAND() LIMIT '.$limit);
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
}