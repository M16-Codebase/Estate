<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_utp_model extends CI_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();   
             
        include(MDPATH.'utp/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table  = $moduleinfo['table'];
        $this->title  = $moduleinfo['title'];
        $this->router = $moduleinfo['router'];
        
        $this->fields = array( // создаем стандартный массив полей
            'id' => // идентификатор
                array(
                         'type' => 'INT',
                         'constraint' => 10, 
                         'unsigned' => TRUE,
                         'auto_increment' => TRUE,
                         'comment' => '',
                         'newType' => ''
                      ),
            'name' => // заголовок
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'comment' => 'Название (русский)',
                         'newType' => ''
                     ),
            'name_en' => // заголовок
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'comment' => 'Название (english)',
                         'newType' => ''
                     ),
            'link' => // ЧПУ
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'comment' => 'Ссылка',
                         'newType' => ''
                     ),
            'sort' => // сортировка
                array(
                         'type' => 'INT',
                         'constraint' => '3',
                         'null' => true,
                         'default' => '100',
                         'comment' => 'Сортировка',
                         'newType' => ''
                     ),
            'banned' => // видимость | 0 - показывать, 1 - скрывать
                array(
                         'type' => 'INT',
                         'constraint' => "1",
                         'null' => true,
                         'default' => '0',
                         'comment' => 'Видимость',
                         'newType' => 'enum'
                     )
        );
	}
    
    
    /**
    * Конфигурационные данные
    **/
    function configData()
    {
        $data = array();
     
        $data['sortList'] = array(
            'name' => 'sortList',
            'id' => 'sortList',
            'label' => 'Способ сортировки',
            'value' => 'asc',
            'params' => array('asc'=>'от А до Я', 'desc' => 'от Я до А'),
            'info' => 'Каким способом будут сортироваться данные для вывода',
            'placeholder' => ''
        );
        
        return $data;
    }
    
    
    /**
     * 
    **/
    function configDataLoadBD()
    {
        $this->db->select('configs');
        $this->db->where('table', 'ci_'.$this->table);
        $query = $this->db->get('table_info');
        $data = $query->row_array();
        
        return unserialize($data['configs']);
    }    
    
    
    /**
     * Редактирование инфтормационной таблицы
    **/
    public function edit_tableInfo($array)
    {               
        $this->db->where('table', 'ci_'.$this->table);
        $query = $this->db->update('table_info', $array);       
        
        return $query;
    }
    
    
    /**
	* Проверка или активирован модуль
	**/
	public function check_install()
	{
		$where = array (
			'link' => $this->module
		);

		$this->db->where($where);
		$query = $this->db->get('module');

		if($query->num_rows() > 0)
			return true;
		return false;
	}
    
    
    /**
	* Активация модуля
	**/
	public function module_install()
	{
        $where = array (
			'link' => $this->module,
			'title' => $this->title,
            'router' => $this->router
		);

		return $this->db->insert('module', $where);
	}
    
    
    /**
	* Деактивация модуля
	**/
	public function module_uninstall()
	{
		$where = array (
			'link' => $this->module,
		);

		return $this->db->delete('module', $where);
	}    
    
    
    /**
     * Создание таблицы в БД
    **/
    public function create_tables()
    {
        $this->load->dbforge(); // Подгружаем драйвер

        $this->dbforge->add_field($this->fields); // задаем поля в драйвер
        $this->dbforge->add_key('id', TRUE); // задаем первичный ключ
                        
        if($this->db->table_exists($this->table)) // проверяем есть ли такая таблица в БД
        {
            if($this->table_info()) // пытаемся занести информацию о таблице
            {
                return true;
            }
        }
        else // если таблицы не существует
        {
            if($this->dbforge->create_table($this->table,TRUE)) // создаем таблицу
            {
                
                if($this->table_info()) // заносим инфу о таблице
                {
                    return true;
                }  
                else
                {
                    return false;
                }                  
            }
            else
            {
                return false;
            }
        }        
    }
    
    
    /**
     * Добавляем данные о таблице в Информационную таблицу
    **/
    function table_info()
    {        
       $in = $this->exist_tableInfo(); 
       
       if(empty($in))
       {                 
            $fields = $this->fields;
            foreach($fields as $k=>$f)
            {
                if(!empty($f['comment']))
                {
                    $comment[$k] = $f['comment']; // Подписи полей
                }
                
                if(!empty($f['newType']))
                {
                    $new_type[$k] = $f['newType']; // Задаем если нужно новые типы
                }
            }                
            
            // Создаем переменную со всеми данными
            $info = array(
                'table'     => $this->db->dbprefix($this->table), // имя таблицы
                'comment'   => serialize($comment), // сериализуем подписи
                'new_type'  => serialize($new_type), // сериализуем новые типы данных
                'configs'  => serialize($this->configData()) // сериализуем конфигурационные настроки модуля
            );
                 
            // заносим значения в таблицу и возвращаем true или false       
            return $this->db->insert('table_info', $info);       
       }
       else
       {
        return true;
       }
    }
    
    
    /**
     * Проверяем, есть ли такая уже запись в информационной таблице
    **/
    function exist_tableInfo()
    {
        $this->db->where(array('table'=>'ci_'.$this->table));
        $query = $this->db->get('table_info');
        return count($query->result_array());        
    }
    
    
/** ************************************************************************************************************************************ **/
    
    
    /**
	* Вытаскиваем запись по ссылке | Определяем существует ли такая запись
	**/
    public function module_getLink($link, $id=NULL)
    {
        if($id)
        {
            $this->db->where('id !=', $id);
        }
        $this->db->where('link', $link);
        $query = $this->db->get($this->table);               
        $que = $query->row_array();                
                        
        if(isset($que['id']))
        {                    
            return true;
        }
        else
            return false;
    }
    
    
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
        
        return $this->db->delete($this->table, $where);
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