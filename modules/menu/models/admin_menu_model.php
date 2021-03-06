<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_menu_model extends CI_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();   
             
        include(MDPATH.'menu/moduleinfo.php');                
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
            'parent_id' => // краткое описание
                array(
                         'type' => 'INT',
                         'constraint' => "2",
                         'null' => true,
                         'default' => '0',
                         'comment' => 'Родитель',
                         'newType' => 'enum'
                     ),
            'name' => // заголовок
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'comment' => 'Название',
                         'newType' => ''
                     ),
            'link' => // ЧПУ
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'comment' => 'Ссылка',
                         'newType' => ''
                     ),
            'type_link' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Тип ссылки',
                         'newType' => ''
                     ),
            'attach' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Дополнительные аттрибуты',
                         'newType' => ''
                     ),
            'class' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Дополнительные классы',
                         'newType' => ''
                     ),
            'region' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '20',
                         'null' => true,
                         'comment' => 'Область вывода текущего пункта меню',
                         'newType' => 'enum'
                         
                     ),
            'mainfoto' => // главное фото 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'default' => '/uploads/_thumbs/images/no_image.jpg',
                         'comment' => 'Миниатюра',
                         'newType' => 'image'
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
        if(isset($this->fields['link']))
        {
            $this->dbforge->add_key('link'); // задаем дополнительный ключ если он существует
        }
                        
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
    
    
    
    function moduleLoad()
    {
        $this->db->where('in_menu >','0');
        $query = $this->db->get('module');                       
        $module = $query->result_array();        
        
        foreach($module as $mod)
        {
            if($mod['in_menu'] == 1)
            {
                $modu[$mod['link']] = $mod['title'];
            }
            elseif($mod['in_menu'] == 2)
            {
                $this->db->where('banned','0');
                $query = $this->db->get($mod['link']);                       
                $modul = $query->result_array();
                
                if(!empty($modul))
                {
                    foreach($modul as $md)
                    {
                        $modu[$md['link']] = $md['name'];
                        
                    }
                }
            }
            elseif($mod['in_menu'] == 3)
            {
                $modu[$mod['link']] = $mod['title'];
                
                $this->db->where('banned','0');
                $query = $this->db->get($mod['link']);                       
                $modul = $query->result_array();
                
                if(!empty($modul))
                {
                    foreach($modul as $md)
                    {
                        $modu[$mod['link'].'/'.$md['link']] = $md['name'];
                    }
                }
            }
        }
        
        return $modu;
    }
    
         
}