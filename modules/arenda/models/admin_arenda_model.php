<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_arenda_model extends CI_Model
{

    public $metro_arenda = 'metro_arenda';
    public $rayon_arenda = 'rayon_arenda';

	// конструктор
	public function __construct()
	{
		parent::__construct();   
             
        include(MDPATH.'arenda/moduleinfo.php');                
        $this->module  = $moduleinfo['name'];
        $this->table   = $moduleinfo['table'];
        $this->title   = $moduleinfo['title'];
        $this->router  = $moduleinfo['router'];
        $this->in_menu = $moduleinfo['in_menu'];
	}
    
    
    /**
    * Конфигурационные данные
    **/
    function configData()
    {
        $data['table'] = '';
        
        $data['table']['sortList'] = array(
            'name' => 'sortList',
            'id' => 'sortList',
            'type' => 'input_enum',
            'label' => 'Способ сортировки',
            'value' => 'asc',            
            'params' => array('asc'=>'от А до Я', 'desc' => 'от Я до А'),
            'valid' => '',
            'info' => 'Каким способом будут сортироваться данные для вывода',
            'placeholder' => '',
            'options' => array(
                            'class' => 'multiple white-gradient check-list', // класс
                            'label_class' => '', // класс для Label
                            'text_button' => '', // название кнопки
                            'text_button_script' => '', // скрипт для кнопки
                            'text_icon' => '', // иконка
                            'select_chosen' => false, // используется ли для селекта плагин choosen
                            'switch_text' => '', // текста через знак (|) для switch. Разелитель нужен для надписи кнопок ВКЛ|ОТКЛ.
                            'editor_function' => '', // название функции настройки редактора
                            'img_path' => '', // путь к папке изображений | файлов
                            'width' => '' // ширина блока
                        )               
        );
        
        $data['table']['Paging'] = array(
            'name' => 'Paging',
            'id' => 'Paging',
            'type' => 'input_switch',
            'label' => 'Включение пагинации',
            'value' => '1',            
            'info' => 'Для включения пагинации(нумерации) страниц нужно поставить переключатель в положение ВКЛ',
            'placeholder' => '',
            'options' => array(
                            'class' => '', // класс
                            'label_class' => '', // класс для Label
                            'text_button' => '', // название кнопки
                            'text_button_script' => '', // скрипт для кнопки
                            'text_icon' => '', // иконка
                            'select_chosen' => false, // используется ли для селекта плагин choosen
                            'switch_text' => '', // текста через знак (|) для switch. Разелитель нужен для надписи кнопок ВКЛ|ОТКЛ.
                            'editor_function' => '', // название функции настройки редактора
                            'img_path' => '', // путь к папке изображений | файлов
                            'width' => '' // ширина блока
                        )
        );
        
        $data['table']['perPaging'] = array(
            'name' => 'perPaging',
            'id' => 'perPaging',
            'type' => 'input',
            'label' => 'К-во записей',
            'value' => '5',
            'params' => '',
            'info' => 'Количество отображаемых записей на странице',
            'placeholder' => ''
        );
        
        $data['table']['nextPaging'] = array(
            'name' => 'nextPaging',
            'id' => 'nextPaging',
            'type' => 'input',
            'label' => 'Надпись "следующая запись"',
            'value' => '>',
            'params' => '',
            'info' => 'Отображенеи надписи "следующая запись"',
            'placeholder' => ''
        );
        
        $data['table']['prevPaging'] = array(
            'name' => 'prevPaging',
            'id' => 'prevPaging',
            'type' => 'input',
            'label' => 'Надпись "предыдущая запись"',
            'value' => '<',
            'params' => '',
            'info' => 'Отображенеи надписи "предыдущая запись"',
            'placeholder' => ''
        );
                
        $data['table']['sortList'] = array(
            'name' => 'sortList',
            'id' => 'sortList',
            'type' => 'input_enum',
            'label' => 'Способ сортировки',
            'value' => 'asc',
            'params' => array('asc'=>'от А до Я', 'desc' => 'от Я до А'),
            'info' => 'Каким способом будут сортироваться данные для вывода',
            'placeholder' => '',
            'options' => array(
                            'class' => 'multiple white-gradient check-list', // класс
                            'label_class' => '', // класс для Label
                            'text_button' => '', // название кнопки
                            'text_button_script' => '', // скрипт для кнопки
                            'text_icon' => '', // иконка
                            'select_chosen' => false, // используется ли для селекта плагин choosen
                            'switch_text' => '', // текста через знак (|) для switch. Разелитель нужен для надписи кнопок ВКЛ|ОТКЛ.
                            'editor_function' => '', // название функции настройки редактора
                            'img_path' => '', // путь к папке изображений | файлов
                            'width' => '' // ширина блока
                        )
        );
        
        $data['table']['breadcrumbs'] = array(
            'name' => 'breadcrumbs',
            'id' => 'breadcrumbs',
            'type' => 'input_enum',
            'label' => 'Статус хлебной крохи',
            'value' => '0',
            'params' => array('0'=>'Выводить', '1' => 'Не выводить'),
            'info' => 'Выводить хлебные крохи на странице или нет',
            'placeholder' => ''
            ,
            'options' => array(
                            'class' => 'multiple white-gradient check-list', // класс
                            'label_class' => '', // класс для Label
                            'text_button' => '', // название кнопки
                            'text_button_script' => '', // скрипт для кнопки
                            'text_icon' => '', // иконка
                            'select_chosen' => false, // используется ли для селекта плагин choosen
                            'switch_text' => '', // текста через знак (|) для switch. Разелитель нужен для надписи кнопок ВКЛ|ОТКЛ.
                            'editor_function' => '', // название функции настройки редактора
                            'img_path' => '', // путь к папке изображений | файлов
                            'width' => '' // ширина блока
                        )
        );
                       
        return $data;
    }
    
    
    /**
     * Вытаскиваем настрйки из БД
    **/
    function configDataLoadBD()
    {
        $this->db->select('configs');
        $this->db->where('table', 'ci_'.$this->table);
        $query = $this->db->get('table_info');
        $data = $query->row_array();
        
        return unserialize($data['configs']);
    }    
    
	public function getById($id = null) {
        if (!isset($id)) return false;
        
        $this->db->where('id', (int)$id);

        $query = $this->db->get($this->table);
        return $query->result_array();
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
        $set = array (
			'link' => $this->module,
			'title' => $this->title,
            'router' => $this->router,
            'in_menu' => $this->in_menu
		);

		return $this->db->insert('module', $set);
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
        if($this->db->table_exists($this->table)) // проверяем есть ли такая таблица в БД
        {
            return $this->table_info(); // пытаемся занести информацию о таблице            
        }
        else // если таблицы не существует
        {            
            $sql = "
                CREATE TABLE `testt` (
                	`Column 1` INT(10) NULL AUTO_INCREMENT COMMENT '1111',
                	`Column 2` VARCHAR(50) NULL COMMENT '22222',
                	`Column 3` TEXT NULL COMMENT '33333',
                	PRIMARY KEY (`Column 1`)
                )
                COMMENT='комментарий'
                COLLATE='utf8_general_ci'
                ENGINE=InnoDB;        
            ";                
        
            if($this->db->simple_query($sql)) // создаем таблицу | выполенние скрипта
                { return $this->table_info(); } // заносим инфу о таблице
            else
                { return false; }
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
            // Создаем переменную со всеми данными
            $info = array(
                'table'     => $this->db->dbprefix($this->table), // имя таблицы
                'comment'   => '', // сериализуем подписи
                'comment_en' => '', // сериализуем подписи (english)
                'new_type'  => '', // сериализуем новые типы данных
                'configs'  => serialize($this->configData()) // сериализуем конфигурационные настроки модуля
            );
                 
            // заносим значения в таблицу и возвращаем true или false       
            return $this->db->insert('table_info', $info);                   
       }
       else
        { return true; }
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

    public function getLikes($id = NULL)
    {
        if($id != NULL)
        {
            $this->db->where('id !=', $id);
        }

        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function dop_metro_add($id, $mas){
        $where = array(
            'arenda_id' => $id
        );
        $this->db->delete($this->metro_arenda, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'arenda_id' => $id,
                    'metro_id' => $v
                );
                $this->db->insert($this->metro_arenda, $mas);
            }
        }
    }



    public function dop_rayon_add($id, $mas){
        $where = array(
            'arenda_id' => $id
        );
        $this->db->delete($this->rayon_arenda, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'arenda_id' => $id,
                    'rayon_id' => $v
                );
                $this->db->insert($this->rayon_arenda, $mas);
            }
        }
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

    public function addToSpecials($array){
        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $this->load->model('rayon/admin_rayon_model','admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id();
        foreach($parentrayon as $p)
        {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('metro/admin_metro_model','admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id();
        foreach($parentmetro as $p)
        {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $data = array(
            'parent_id'=>$array['id'],
            'special'=>$array['special'],
            'category'=>8,
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['address'],
            'price'=>0,
            'price_arenda'=>$array['price'],
            'deadline'=>0,
            'metro'=>$array['metro_id'],
            'foto'=>$array['main_foto'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => '',
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }

    public function addToInterest($array){
        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $this->load->model('rayon/admin_rayon_model','admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id();
        foreach($parentrayon as $p)
        {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('metro/admin_metro_model','admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id();
        foreach($parentmetro as $p)
        {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $data = array(
            'parent_id'=>$array['id'],
            'interest'=>$array['interest'],
            'category'=>8,
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['address'],
            'price'=>0,
            'price_arenda'=>$array['price'],
            'deadline'=>0,
            'metro'=>$array['metro_id'],
            'foto'=>$array['main_foto'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => '',
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }
    
         
}