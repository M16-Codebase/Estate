<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля
**/


class admin_buildings_model extends CI_Model
{

    public $typesdelka = 'sdelka_arenda';
    public $metro_buildings = 'metro_buildings';
    public $rayon_buildings = 'rayon_buildings';
    public $banks_buildings = 'banks_buildings';
    public $interests = 'interest';
    public $specials = 'special';
    public $military = 'military';
    public $commerce = 'commerce';
    public $favorites = 'favorites';
	// конструктор
	public function __construct()
	{
		parent::__construct();   
             
        include(MDPATH.'buildings/moduleinfo.php');                
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
            CREATE TABLE `ci_buildings` (
            	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            	`id_complex` INT(10) UNSIGNED NOT NULL DEFAULT '0',
                `metro_id` INT(10) NULL DEFAULT '0',
            	`rayon_id` INT(10) NULL DEFAULT '0',
                `adress` VARCHAR(255) NULL DEFAULT NULL,
                `ipoteka` INT(1) NULL DEFAULT '0',
                `name` VARCHAR(255) NOT NULL,
            	`link` VARCHAR(255) NOT NULL,
                `title` VARCHAR(255) NULL DEFAULT NULL,
            	`description` VARCHAR(255) NULL DEFAULT NULL,
            	`keywords` VARCHAR(255) NULL DEFAULT NULL,
                `sort` INT(5) NULL DEFAULT '100',
            	`banned` INT(1) NULL DEFAULT '0',
                `type_id` INT(10) NULL DEFAULT '0',
                `korpus` INT(1) NULL DEFAULT '0',
                `korpus_value` VARCHAR(50) NULL DEFAULT NULL,
                `rasstoyanie_metro` VARCHAR(50) NULL DEFAULT NULL,
                `map` VARCHAR(255) NULL DEFAULT NULL,
                `mainfoto` VARCHAR(255) NULL DEFAULT '/asset/img/logo-M16.png',
                `bigfoto` VARCHAR(255) NULL DEFAULT '/asset/img/logo-M16.png',
                `foto` TEXT NULL,
                `text` TEXT NULL,
            	PRIMARY KEY (`id`),
            	INDEX `link` (`link`),
            	INDEX `id_complex` (`id_complex`)
            )
            COLLATE='utf8_general_ci'
            ENGINE=InnoDB
            AUTO_INCREMENT=1;
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

    public function typesdelka_add($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete($this->typesdelka, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'typesdelka_id' => $v
                );
                $this->db->insert($this->typesdelka, $mas);
            }
        }
    }

    public function dop_metro_add($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete($this->metro_buildings, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'metro_id' => $v
                );
                $this->db->insert($this->metro_buildings, $mas);
            }
        }
    }



    public function dop_rayon_add($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete($this->rayon_buildings, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'rayon_id' => $v
                );
                $this->db->insert($this->rayon_buildings, $mas);
            }
        }
    }

    public function banks_add($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete($this->banks_buildings, $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'bank_id' => $v
                );
                $this->db->insert($this->banks_buildings, $mas);
            }
        }
    }
    
    
    /**
	* Удаление записей
	**/
	public function module_delete($id)
	{
		$where = array(
            'id' => $id
        );

        $where1 = array(
            'building_id' => $id
        );

        $where2 = array(
            'parent_id' => $id,
            'identity'  => 'buildings'
        );

        $where3 = array(
            'object_id' => $id
        );

        $this->db->delete($this->typesdelka, $where1);

        $this->db->delete($this->interests, $where2);
        $this->db->delete($this->specials, $where2);
        $this->db->delete($this->militarys, $where2);
        $this->db->delete($this->commerces, $where2);

        $this->db->delete($this->favorites, $where3);
        
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

    public function getCottages($id = NULL)
    {
        if($id != NULL)
        {
            $this->db->where('id', $id);
        }
        $this->db->where('is_cottage', '1');
        $this->db->like('razdelu', '2');
        $this->db->order_by('name', 'ASC');

        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function fillMetro(){
        $this->db->where('metro_id <>', 0);
        $query = $this->db->get($this->table);
        $arr = $query->result_array();
        foreach ($arr as $key => $value){
            if (!$this->isMetro($value['id'], $value['metro_id'])){
                $mas = array(
                    'building_id' => $value['id'],
                    'metro_id' => $value['metro_id']
                );
                $this->db->insert($this->metro_buildings, $mas);
            }
        }
    }

    public function fillRayon(){
        $this->db->where('metro_id <>', 0);
        $query = $this->db->get($this->table);
        $arr = $query->result_array();
        foreach ($arr as $key => $value){
            if (!$this->isRayon($value['id'], $value['rayon_id'])){
                $mas = array(
                    'building_id' => $value['id'],
                    'rayon_id' => $value['rayon_id']
                );
                $this->db->insert($this->rayon_buildings, $mas);
            }
        }
    }

    public function isRayon($id, $metro){
        $this->db->where('building_id', $id);
        $this->db->where('rayon_id', $metro);
        $query = $this->db->get($this->rayon_buildings);
        $que = $query->row_array();
        if(isset($que['building_id']))
        {
            return $que['building_id'];
        }
        else
            return 0;
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

    public function isMetro($id, $metro){
        $this->db->where('building_id', $id);
        $this->db->where('metro_id', $metro);
        $query = $this->db->get($this->metro_buildings);
        $que = $query->row_array();
        if(isset($que['building_id']))
        {
            return $que['building_id'];
        }
        else
            return 0;
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

        $this->load->model('typesdelka/admin_typesdelka_model','admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach($parenttypesdelka as $p)
        {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }

        if ((int)$array['razdelu'] == 0){
            $deadline = $this->dataKv($array['korpus_value']);
        }

        if ((int)$array['razdelu'] == 4){
            $this->load->model('buildings/buildings_model', 'bmodel');
            $sdd = $this->bmodel->getSdelka($array['id']);
            $sssd = array();
            if ($sdd && count($sdd)>0){

                foreach ($sdd as $vv){

                    $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                }
            }
            $deadline = (!$sdd || count($sdd)==0) ? 'не указано' : implode(" / ", $sssd);
        }

        $data = array(
            'parent_id'=>$array['id'],
            'special'=>$array['special'],
            'category'=>$array['razdelu'],
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['adress'],
            'price'=>$array['price'],
            'ipoteka'=>$array['ipoteka'],
            'price_arenda'=>$array['price_arenda'],
            'deadline'=>$array['korpus_value'],
            'metro'=>$array['metro_id'],
            'foto'=>$array['mainfoto'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => $deadline,
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }



    public function addToMilitary($array){
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

        $this->load->model('typesdelka/admin_typesdelka_model','admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach($parenttypesdelka as $p)
        {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }

        if ((int)$array['razdelu'] == 0){
            $deadline = $this->dataKv($array['korpus_value']);
        }

        if ((int)$array['razdelu'] == 4){
            $this->load->model('buildings/buildings_model', 'bmodel');
            $sdd = $this->bmodel->getSdelka($array['id']);
            $sssd = array();
            if ($sdd && count($sdd)>0){

                foreach ($sdd as $vv){

                    $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                }
            }
            $deadline = (!$sdd || count($sdd)==0) ? 'не указано' : implode(" / ", $sssd);
        }

        $data = array(
            'parent_id'=>$array['id'],
            'military'=>$array['military'],
            'category'=>$array['razdelu'],
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['adress'],
            'price'=>$array['price'],
            'ipoteka'=>$array['ipoteka'],
            'price_arenda'=>$array['price_arenda'],
            'deadline'=>$array['korpus_value'],
            'metro'=>$array['metro_id'],
            'foto'=>$array['mainfoto'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => $deadline,
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }



     public function addToCommerce($array){
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

        $this->load->model('typesdelka/admin_typesdelka_model','admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach($parenttypesdelka as $p)
        {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }

        if ((int)$array['razdelu'] == 0){
            $deadline = $this->dataKv($array['korpus_value']);
        }

        if ((int)$array['razdelu'] == 4){
            $this->load->model('buildings/buildings_model', 'bmodel');
            $sdd = $this->bmodel->getSdelka($array['id']);
            $sssd = array();
            if ($sdd && count($sdd)>0){

                foreach ($sdd as $vv){

                    $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                }
            }
            $deadline = (!$sdd || count($sdd)==0) ? 'не указано' : implode(" / ", $sssd);
        }

        $data = array(
            'parent_id'=>$array['id'],
            'commect'=>$array['commerce'],
            'category'=>$array['razdelu'],
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['adress'],
            'price'=>$array['price'],
            'ipoteka'=>$array['ipoteka'],
            'price_arenda'=>$array['price_arenda'],
            'deadline'=>$array['korpus_value'],
            'metro'=>$array['metro_id'],
            'foto'=>$array['mainfoto'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => $deadline,
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }



    public function addToInterest($array){
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

        $this->load->model('typesdelka/admin_typesdelka_model','admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach($parenttypesdelka as $p)
        {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }

        if ((int)$array['razdelu'] == 0){
            $deadline = $this->dataKv($array['korpus_value']);
        }

        if ((int)$array['razdelu'] == 4){
            $this->load->model('buildings/buildings_model', 'bmodel');
            $sdd = $this->bmodel->getSdelka($array['id']);
            $sssd = array();
            if ($sdd && count($sdd)>0){

                foreach ($sdd as $vv){

                    $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                }
            }
            $deadline = (!$sdd || count($sdd)==0) ? 'не указано' : implode(" / ", $sssd);
        }

        $data = array(
            'parent_id'=>$array['id'],
            'interest'=>$array['interest'],
            'category'=>$array['razdelu'],
            'name'=>$array['name'],
            'link'=>$array['link'],
            'address'=>$array['adress'],
            'price'=>$array['price'],
            'price_arenda'=>$array['price_arenda'],
            'deadline'=>$array['korpus_value'],
            'metro'=>$array['metro_id'],
            'foto'=>$array['mainfoto'],
            'ipoteka'=>$array['ipoteka'],
            'rayon'=>$array['rayon_id'],
            'line_top' => $parent_idrayon[$array['rayon_id']],
            'line_middle' => $deadline,
            'banned' => $array['banned'],
            'currency' => ''
        );
        return $data;
    }

    function dataKv($dt, $mini = true)
    {
        $newTime = strtotime(date('d.m.Y H:i:s'));
        if($dt < $newTime)
        {
            $return = 'Сдан';
        }
        else
        {
            $ex = explode('.', date('d.m.Y', $dt));

            if(    $ex[1] == '01') { $r = 'I'; }
            elseif($ex[1] == '02') { $r = 'I'; }
            elseif($ex[1] == '03') { $r = 'I'; }
            elseif($ex[1] == '04') { $r = 'II'; }
            elseif($ex[1] == '05') { $r = 'II'; }
            elseif($ex[1] == '06') { $r = 'II'; }
            elseif($ex[1] == '07') { $r = 'III'; }
            elseif($ex[1] == '08') { $r = 'III'; }
            elseif($ex[1] == '09') { $r = 'III'; }
            elseif($ex[1] == '10') { $r = 'IV'; }
            elseif($ex[1] == '11') { $r = 'IV'; }
            elseif($ex[1] == '12') { $r = 'IV'; }

            if($mini)
            { $return = $r.' кв. '.$ex[2]; }
            else
            { $return = $r.' квартал '.$ex[2].''; }
        }

        return $return;
    }
    
         
}