<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    /** таблица */  
    public $table = '';
    
    /** модуль */  
    public $module = '';

    /** конфиг */
    public $conf = '';     
    
    /** метод сортировки */
    public $sortMethod = 'sort';          
    
    /** параметр сортировки */
    public $sortAscDesc = 'asc';
    
    
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();

        $this->conf = $this->load->config($this->module.'/'.$this->module, true);
	}


    /**
     * Вытаскиваем все данные по указанной опции пагинации
     * @param $offset - к-во вытаскиваемых записей
    **/
	function pagination($offset = 0)
    {
        $sort = $this->sortAscDesc;
        
        if(isset($this->conf['sortList']))
        {
            $sort = $this->conf['sortList'];
        }        
        
        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->order_by($this->sortMethod, $sort); // сортировка

        if($this->table == 'news')
        {
            $this->db->order_by('date', 'desc');
        }

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

        if($this->table == 'news')
        {
            $this->db->limit(30, $offset);
        }
        
        $query = $this->db->get($this->table);        
        $result = $query->result_array();
        return $this->lang_load($result, $this->table);
    }
    
    /**
     * Вытаскиваем одну определенную запись
    **/
	function getRow($link = '', $id = '')
    {
        // вытаскивать только видимые данные
        $this->db->where('banned', '0');
        
        if(!empty($link)) 
        {  
            $this->db->where('link', $link); 
        } 
        elseif(!empty($id)) 
        {  
            $this->db->where('id', $id); 
        }
        else 
        { 
            return false; 
        }
        
        $query = $this->db->get($this->table);        
        $result = $query->row_array();            
        return $this->lang_load($result, $this->table);
    }
    
    /**
     * Общее количество записей в таблице
    **/    
    function all_count($banned = false, $where = '', $like = '')
    {
        if(!$banned)
        {
            $this->db->where('banned','0');
        }
        
        if(!empty($where))
        {
            if(is_array($where))
            {
                foreach($where as $k=>$v)
                {
                    $this->db->where($k, $v);
                }
            }
            else
            {
                $this->db->where($where);
            }
        }

        if(!empty($like))
        {
            $this->db->like($like);
        }
        
        $query = $this->db->get($this->table)->result_array();                
        return count($query);
    }
    
    /**
     * Вытаскиваем лимитное количество записей
    **/
	function limitRow($offset = 0, $where = '',$ncat='')
    {                
        $this->db->where('banned', '0'); // вытаскивать только видимые данные
        $this->db->order_by($this->sortMethod, $this->conf['sortList']); // сортировка
        
        if(!empty($where))
        {
            $this->db->where($where);
        }
        if(!empty($ncat))
        {
            $this->db->where('ncategory',$ncat);
        }

        if($this->table == 'news')
        {
            $this->db->order_by('id', 'desc');
        }

        // проверяем на лимит
        if($offset != 0)
        {                                        
            $this->db->limit($offset);                
            $query = $this->db->get($this->table);
            $result = $query->result_array();
            return $this->lang_load($result, $this->table);
        }
        else
        {
            return false;
        }
    }
    
    
    /**
     * Вытащить рандомно 1 строку
    **/       
    function randomRow($limit = 1)
    {
        $query = $this->db->query('SELECT * FROM ci_'.$this->table.' ORDER BY RAND() LIMIT '.$limit);
    }
    
    
    /**
     * Если нужно выполнить отдельный какой-то скрипт
    **/ 
    public function all_data_where($table, $where, $id_sort = 'id', $sort = 'asc')
    {
        $this->db->order_by($id_sort, $sort);
        $this->db->where($where);
        $query = $this->db->get($table);               
        $result = $query->result_array();
        return $this->lang_load($result, $table);
    }
    
    /**
     * Вытащить несколько значений через WHERE IN()
    **/
    function where_in($arrayId, $table = '', $select = '')
    {
        if(!empty($select))
        {
            $this->db->select($select);
        }
        
        $this->db->order_by($this->sortMethod, $this->sortAscDesc);                
        $this->db->where('banned','0');        
        $this->db->where_in('id', $arrayId);
        
        if(empty($table))
        {
            $table = $this->table;
        }
        
        $query = $this->db->get($table);        
        $result = $query->result_array();
        return $this->lang_load($result, $table);
    }
    
    /**
     * Следующая запись
    **/
    function rowNextPrev($id, $next = true, $where = '', $table = '')
    {
        $this->db->order_by('id','asc');                
        $this->db->where('banned','0');
        
        if(!empty($where))
        {
            $this->db->where($where);
        }
        
        if($next)
        {
            $this->db->where('id >', $id);
        }     
        else
        {
           $this->db->where('id <', $id); 
        }  
         
        $this->db->limit(1);
        
        if(empty($table))
        {
            $table = $this->table;
        }
        
        $query = $this->db->get($table);        
        $result = $query->row_array();
        return $this->lang_load($result, $table);
    }
    
    /**
	* Редактирование записи
	**/
	public function module_edit($id, $array, $table = '')
	{        
        $where = array(
            'id' => $id
        );
        
        if(empty($table))
        {
            $table = $this->table;
        }
        
        return $this->db->update($table, $array, $where);
	}
    
    /**
	* Добавление записи
	**/
	public function module_add($array, $table = '')
	{                                  
        if(empty($table))
        {
            $table = $this->table;
        }
        
        return $this->db->insert($table, $array);
	}
    
    /**
     * Массив результата - $result, вывод данных под язык (мультиязычность)
     * Таблица - $table
    **/
    function lang_load($result, $table, $array = true)
    {        
        return $result;
        /*$langPrefix = config_item('language_prefix'); // префикс языка        
        $langPrefix = $langPrefix == 'ru' ? '' : '_'.$langPrefix; // передаем префикс                                             
        
        $data = array();        
        if(count($result) > 0)
        {            
            $tb = $this->db->query('SHOW FULL COLUMNS FROM ci_'.$table);
            
            if(!empty($result['id']))
            {
                /////////////////////////////////////////////////////////
                $res = $tb->result_object();                                           
                                        
                foreach($res as $t)
                {                
                    $fields = $t->Field;                
                    
                    $fields_replace = str_replace($langPrefix, '', $fields);
                    $tbl[$fields_replace] = $fields;                                                
                }                          
                                                   
                foreach($tbl as $item=>$tb)
                {
                    $data[$item] = $result[$tb];
                }                                                   
                /////////////////////////////////////////////////////////
            }
            else
            {            
                ////////////////////////////////////////////////////////
                $res = $tb->result_object();
                                        
                foreach($res as $t)
                {                
                    $fields = $t->Field;                
                    
                    $fields_replace = str_replace($langPrefix, '', $fields);
                    $tbl[$fields_replace] = $fields;                                                
                }
                
                if($array)
                {
                    foreach($result as $k=>$r)
                    {
                        foreach($tbl as $item=>$tb)
                        {
                            $data[$k][$item] = $r[$tb];
                        }
                    }
                }
                else
                {                      
                    foreach($result as $k=>$r)
                    {
                        foreach($tbl as $item=>$tb)
                        {
                            $data[$k]->$item = $r[$tb];
                        }
                    }            
                }
                //////////////////////////////////////////////////////
            }
        }   
        
        return $data;*/
    }
}
// END Model Class

/* End of file Model.php */
/* Location: ./system/core/Model.php */