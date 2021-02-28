<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends CI_Model
{
    // конструктор
    public function __construct()
    {
        parent::__construct();
    }    
    

    /**
     * Выполнение запроса
    **/
    public function sql($sql)
    {
        return $this->db->query($sql);
    }  
    

    /**
     * Если нужно сделать каокй-то ActiveRecords запрос
    **/
    public function all_data_where($table, $where, $id_sort='id' ,$sort = 'asc')
    {        
        $this->db->order_by($id_sort, $sort);
        $this->db->where($where);
        $query = $this->db->get($table);       
        
        return $query->result_array();
    }  
    
    
    /**
     * Добавление данных в подставленную таблицу
    **/
    public function add_us($table,$mas)
    {               
        $query = $this->db->insert($table, $mas);       
        
        return $query;
    }     
    
    
    /**
     * Редактирование инфтормационной таблицы
    **/
    public function edit_tableInfo($table,$mas)
    {               
        $this->db->where('table',$table);
        $query = $this->db->update('table_info', $mas);       
        
        return $query;
    }  
    
    /**
     * Вытаскиваем данные с таблицы table_info
    **/
    public function module_TableInfo($table)
    {        
        $where = array(
            'table'=>$table
        );
        $this->db->where($where);
        $query = $this->db->get('table_info');       
        
        return $query->result_array();
    }   
    
    
    /**
	* Редактирование записи в БД
	**/
	public function module_edit($id, $mas, $table)
	{
        $where = array(
            'id' => $id
        );
        $this->db->where($where);    
        return $this->db->update($table,$mas,$where);
	}  
    
    
    
    /**
     * Вытаскиваем все данные по указанной опции пагинации
    **/
	function pagination($table, $offset = 0, $per_paging = null, $search = null)
    {     
        if($per_paging == null) { $per_paging = 50; }

        $id_sort = 'name';
        $id_order = 'asc';

        if($table == 'ci_shortcode')
        {
            $id_sort = 'sort';
            $id_order = 'asc';
        }

        if($table == 'ci_auth_users' || $table == 'ci_router')
        {
            $id_sort = 'id';
            $id_order = 'asc';
        }

        if($table == 'ci_news')
        {
            $id_sort = 'date';
            $id_order = 'desc';
        }

        if(uri(1) == 'buildings')
        {
            $id_sort = 'name';
            $id_order = 'asc';
            if ($_SESSION['razdel_admin'] != 7){
                if ($_SESSION['razdel_admin'] == 2)
                    $this->db->where('is_cottage', '0');
                $this->db->like('razdelu', $_SESSION['razdel_admin']);
            }
            else {
                $this->db->like('razdelu', '2');
                $this->db->where('is_cottage', '1');
            }
            //$this->db->like('razdelu', $_SESSION['razdel_admin']);
        }

        if($table == 'ci_apartments' && !empty($search))
        {

            $this->db->like('id', $search);

        }

        // порядок вывода | последний вверху
        $this->db->order_by($id_sort, $id_order);
        // определение с которой записи будем выводить               
        if($offset == 0 or $offset == 1)
        {
            $offset = 0; 
        }
        else
        {
            $offset = ($offset-1) * $per_paging;
        }        
        // определяем лимит            
        $this->db->limit($per_paging, $offset);

        // даем запрос
        $query = $this->db->get($table);        
        // возвращаем результат
        $rows = $query->result_array();
        $count = $this->all_count($table);


        
        return array(
            'rows' => $rows, // строки
            'count' => $count, // к-во строк в таблице
            'pages' => ceil($count / $per_paging), // к-во страниц
            'paging_rows' => $per_paging // к-во записей на страницу
        );
    }
    
    /**
     * Общее количество записей в таблице
    **/    
    function all_count($table)
    {
        if(uri(1) == 'buildings')
        {
            if ($_SESSION['razdel_admin'] != 7){
                if ($_SESSION['razdel_admin'] == 2)
                    $this->db->where('is_cottage', '0');
                $this->db->like('razdelu', $_SESSION['razdel_admin']);
            }
            else {
                $this->db->like('razdelu', '2');
                $this->db->where('is_cottage', '1');
            }

        }
        $query = $this->db->get($table);                
        return $query->num_rows();
    }
    
    
    /**
     * Поиск
    **/
    function search($table, $terms)
    {
         if($table == 'ci_pages')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  name LIKE  '%$terms%'
                    OR  title LIKE  '%$terms%'
                    OR  text LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_menu')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  name LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_auth_users')
         {
            $sql = "SELECT ci_auth_user_profile.name, ci_auth_user_profile.last_name, ci_auth_users.id, ci_auth_users.role_id, ci_auth_users.username, ci_auth_users.email
                    FROM  $table RIGHT
                    JOIN ci_auth_user_profile
                    ON ci_auth_users.id = ci_auth_user_profile.user_id
                    WHERE  ci_auth_users.username LIKE  '%$terms%'
                    OR  ci_auth_users.email LIKE  '%$terms%'
                    OR  ci_auth_user_profile.name LIKE  '%$terms%'
                    OR  ci_auth_user_profile.last_name LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_blog')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  name LIKE  '%$terms%'
                    OR  title LIKE  '%$terms%'
                    OR  text LIKE  '%$terms%'
                    OR  date LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_comment')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  name LIKE  '%$terms%'
                    OR  short_text LIKE  '%$terms%'
                    OR  date LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_symptom')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  nm LIKE  '%$terms%'
                    OR  nm_ru LIKE  '%$terms%'
                    OR  txt LIKE  '%$terms%'
                    OR  txt_ru LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_sanswer')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  vl LIKE  '%$terms%'
                    OR  vl_ru LIKE  '%$terms%'
                    OR  txt LIKE  '%$terms%'
                    OR  txt_ru LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_squestion')
         {
            $sql = "SELECT *
                    FROM  $table
                    WHERE  q LIKE  '%$terms%'
                    OR  q_ru LIKE  '%$terms%'
                ";
         }
         elseif($table == 'ci_buildings')
         {
            /*if(uri(1) == 'buildings')
            { */

                    $sql = "SELECT *
                        FROM  $table
                        WHERE  name LIKE  '%$terms%'
                        AND  razdelu LIKE  '%{$_SESSION['razdel_admin']}%'
                    ";

            //}
         }
         elseif($table == 'ci_apartments')
         {

             $sql = "SELECT *
                        FROM  $table
                        WHERE  id LIKE  '%$terms%'
                        LIMIT 0,400
                        
                    ";

             
         }
         else
         {
            $this->load->dbforge();
            $ob_polya = $this->db->field_data($table);
            
            $sql = "SELECT *
                    FROM  $table
                    WHERE  id LIKE '%$terms%'";
             
            foreach($ob_polya as $p)
            {
                $sql .= " OR {$p->name} LIKE '%$terms%' COLLATE utf8_unicode_ci ";
            }
         }

        $query = $this->db->query($sql);
        return $query->result_array();
    }     
    
    
    function chart()
    {
        $this->db->where('date', date('Y-m-d'));
        $query = $this->db->get('chart');               
        $qq = $query->row_array();
        if(empty($qq))
        {
            $this->db->insert('chart', array('date' => date('Y-m-d')));
        }
        
        $this->db->order_by('id','desc');            
        $this->db->limit(20);
                
        $query = $this->db->get('chart');
        $data = $query->result_array();
        
        $array = '';
        
        foreach($data as $d)
        {
            $array[$d['date']] = array($d['rastanovka'], $d['trening'], $d['besp']);
        }
                
        return $array;    
    }                                                    
}
?>