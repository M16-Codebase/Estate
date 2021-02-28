<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class yandex_xml_gen extends MY_Model
{
	public $file;
	public $domain;

	public $config = array(
		'new'		=> array('prefix' => 'new_'),
		'commercial'=> array('prefix' => 'commercial_'),
		'resale'	=> array('prefix' => 'resale_'),
		'zagorod'	=> array('prefix' => 'zagorod_'),
		'elite'		=> array('prefix' => 'elite_'),
	);

	// конструктор
	public function __construct()
	{
		parent::__construct();
	}

	public function getFormatDate($date)
	{
		return date('Y-m-d', $date).'T'.date('H:i:s', $date).'+04:00';
	}

	public function getDescription($arr)
	{
		return strip_tags(str_replace('&nbsp;', ' ', trim(($arr['xml_text'] != '')?$arr['xml_text']:$arr['text'])));
	}

	// Список новостроек
	public function getNewList()
	{
		$query = $this->db->query('SELECT * FROM `ci_buildings` WHERE `razdelu` LIKE "%0%" AND `id_yandex` > 0 AND banned = 0');
		
		return ($query->num_rows() > 0)?$query->result_array():array();
	}

	// Список коммерческой недвижимости
	public function getCommercialList()
	{
		$this->db->select('
			buildings.*,
			buildings.name				as building_name,
			commercial_bussines.name	as comm_bussines_name,
			commercial_forwhat.name		as comm_forwhat_name,
			commercial_pay.name			as comm_pay_name,
			commercial_type.name		as comm_type_name,
			commercial_vid.name			as comm_vid_name
		');
		$this->db->where('buildings.banned','0');
		$this->db->like('buildings.razdelu', '4');
		$this->db->join('commercial_bussines',	'commercial_bussines.id	= buildings.comm_bussines',	'left');
		$this->db->join('commercial_forwhat',	'commercial_forwhat.id	= buildings.comm_type',		'left');
		$this->db->join('commercial_pay',		'commercial_pay.id		= buildings.comm_vid',		'left');
		$this->db->join('commercial_type',		'commercial_type.id		= buildings.comm_forwhat',	'left');
		$this->db->join('commercial_vid',		'commercial_vid.id		= buildings.comm_pay',		'left');
		$query = $this->db->get('buildings');

		// $query = $this->db->query('SELECT * FROM `ci_buildings` WHERE `razdelu` LIKE "%4%" AND `banned` = 0');

		return ($query->num_rows() > 0)?$query->result_array():array();
	}

	// Список коммерческой недвижимости
	public function getZagorodList()
	{
		$this->db->select('
			buildings.*,
			buildings.name	as building_name,
			matherial.name	as matherial_name
		');

		$this->db->where('buildings.banned','0');
		$this->db->like('buildings.razdelu', '2');
		$this->db->join('matherial', 'matherial.id = buildings.matherial_id', 'left');
		$query = $this->db->get('buildings');

		return ($query->num_rows() > 0)?$query->result_array():array();
	}

	// Список новостроек
	public function getResaleList()
	{
		$this->db->select('
			buildings.*
		');

		$this->db->where('buildings.banned','0');
		$this->db->like('buildings.razdelu', '1');
		$query = $this->db->get('buildings');

		return ($query->num_rows() > 0)?$query->result_array():array();
	}

	// Список новостроек
	public function getEliteList()
	{
		$this->db->select('
			buildings.*
		');

		$this->db->where('buildings.banned','0');
		$this->db->like('buildings.razdelu', '3');
		$query = $this->db->get('buildings');

		return ($query->num_rows() > 0)?$query->result_array():array();
	}

	// Шапка
	public function appendHead()
	{
		if (!isset($this->file) OR $this->file == '')
			die('File not found');

		$head = array();
		$head[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$head[] = '<realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">';
		$head[] = "\t".'<generation-date>'.date('Y-m-d', time()).'T'.date('H:i:s', time()).'+04:00</generation-date>';
		
		// Переписываем файл с шапкой
		$handle = fopen($this->file, 'w');
		fwrite($handle, implode("\r\n", $head));
		fclose($handle);
	}

	// Значение
	public function getAreaTag($name, $value)
	{
		return "\t\t<{$name}>\r\n\t\t\t<value>{$value}</value>\r\n\t\t\t<unit>кв.м</unit>\r\n\t\t</{$name}>";
	}

	// Значение
	public function getLotAreaTag($name, $value)
	{
		return "\t\t<{$name}>\r\n\t\t\t<value>{$value}</value>\r\n\t\t\t<unit>сот</unit>\r\n\t\t</{$name}>";
	}

	// Футер
	public function appendFooter()
	{
		$handle = fopen($this->file, 'a');
		fwrite($handle, "\r\n".'</realty-feed>');
		fclose($handle);
	}

	// Отдаем Файл
	public function download()
	{
		if (file_exists($this->file))
		{
			$this->load->helper('download');
			$data = file_get_contents($this->file);
			force_download('yandex.xml', $data);

			return true;
		}
	}

	/*
	public function getAll(){
		$this->db->where('banned', 0);
		$this->db->order_by('sort', 'ASC');
		$query = $this->db->get($this->table);
		return $query->result_array();
	}

	public function getAllFastFilter($cat=4){
		$this->db->select('commercial_vid.*');
		$this->db->where('commercial_vid.banned', 0);
		$this->db->join('buildings', 'commercial_vid.id = buildings.comm_vid', 'inner');
		$this->db->like('buildings.razdelu', $cat);
		$this->db->where('buildings.banned', 0);
		$this->db->distinct();
		$this->db->order_by('commercial_vid.name', 'ASC');
		$query = $this->db->get($this->table);
		$res = $query->result_array();
		return $res;
	}
	*/
}