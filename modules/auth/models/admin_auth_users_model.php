<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_auth_users_model extends CI_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();   
             
        include(MDPATH.'auth/moduleinfo.php');                
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
            'title' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Title',
                         'newType' => ''
                     ),
            'description' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Описание',
                         'newType' => 'description'
                         
                     ),
            'keywords' => 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'comment' => 'Ключевые слова',
                         'newType' => ''
                     ),
            'mainfoto' => // главное фото 
                array(
                         'type' => 'VARCHAR',
                         'constraint' => '255',
                         'null' => true,
                         'default' => '/uploads/_thumbs/images/no_image.jpg',
                         'comment' => 'Фото',
                         'newType' => 'image'
                     ),
            'foto' =>  // дополнительные фоты
                array(
                         'type' => 'TEXT',
                         'null' => true,
                         'comment' => 'Дополнительные фото',
                         'newType' => 'multi-image'
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
                     ),
            'short_text' => // краткое описание
                array(
                         'type' => 'TEXT',
                         'null' => true,
                         'comment' => 'Краткое описание',
                         'newType' => ''
                     ),
            'text' =>  // полное описание
                array(
                         'type' => 'TEXT',
                         'null' => true,
                         'comment' => 'Полное описание',
                         'newType' => ''
                     ),
            'date' => // дата
                array(
                         'type' => 'DATE',
                         'null' => true,
                         'comment' => 'Дата',
                         'newType' => 'date'
                     )
        );
	}
    
    
    /**
    * Конфигурационные данные
    **/
    function configData()
    {
        $data['table']['table'] = '';
        
        $data['table']['DX_webmaster_email'] = array(
            'name' => 'DX_webmaster_email',
            'id' => 'DX_webmaster_email',
            'type' => 'input',
            'label' => 'Email адресс',
            'value' => 'admin@admin.com',
            'params' => '',
            'info' => 'Email адресс администратора сайта',
            'placeholder' => ''
        );
        
        $data['table']['DX_website_name'] = array(
            'name' => 'DX_website_name',
            'id' => 'DX_website_name',
            'type' => 'input',
            'label' => 'Название сайта',
            'value' => 'CMS Apex',
            'params' => '',
            'info' => '',
            'placeholder' => ''
        );
        
        $data['table']['DX_allow_registration'] = array(
            'name' => 'DX_allow_registration',
            'id' => 'DX_allow_registration',
            'label' => 'Регистрация на сайте',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Включить возможность регистрации на сайте',
            'placeholder' => ''
        );
        
        $data['table']['DX_captcha_registration'] = array(
            'name' => 'DX_captcha_registration',
            'id' => 'DX_captcha_registration',
            'label' => 'Каптча регистрации',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Включить проверку на робота при регистрации (включение CAPTCHA)',
            'placeholder' => ''
        );
        
        $data['table']['DX_captcha_login'] = array(
            'name' => 'DX_captcha_login',
            'id' => 'DX_captcha_login',
            'label' => 'Каптча авторизации',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Включить проверку на робота при авторизации (включение CAPTCHA)',
            'placeholder' => ''
        );
        
        $data['table']['DX_email_activation'] = array(
            'name' => 'DX_email_activation',
            'id' => 'DX_email_activation',
            'label' => 'Активация учетной записи',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Включить активацию учётной записи через e-mail',
            'placeholder' => ''
        );
        
        $data['table']['DX_email_account_details'] = array(
            'name' => 'DX_email_account_details',
            'id' => 'DX_email_account_details',
            'label' => 'Информация о пользователе',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Выслать ли пользователю информацию о его учётной записи после регистрации',
            'placeholder' => ''
        );
        
        $data['table']['DX_count_login_attempts'] = array(
            'name' => 'DX_count_login_attempts',
            'id' => 'DX_count_login_attempts',
            'label' => 'Количество неудавшихся попыток входа',
            'value' => '0',
            'type' => 'input_switch',
            'params' =>  '',
            'info' => 'Включить ли подсчет количества неудавшихся попыток ввода пользователем пароля',
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
        $mas['created'] = date('Y-m-d H:i:s', time());
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
    public function module_get_profile($id)
    {
        $this->db->where('user_id', $id);
        $query = $this->db->get('auth_user_profile');               
        
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