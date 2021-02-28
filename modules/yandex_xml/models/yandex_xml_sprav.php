<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class yandex_xml_sprav extends MY_Model
{
	// Конструктор
	public function __construct()
	{
		parent::__construct();
	}

	// Справочник метро
	public function getSubwayList()
	{
		$this->db->select('id, name');
		$this->db->where('banned', 0);
		$this->db->order_by('name', 'ASC');

		$subway = array();
		foreach ($this->db->get('metro')->result_array() as $row)
			$subway[$row['id']] = $row['name'];

		return $subway;
	}

	// Справочник районов
	public function getRayonList()
	{
		$this->db->select('id, name');
		$this->db->where('banned', 0);
		$this->db->order_by('name', 'ASC');

		$rayon = array();
		foreach ($this->db->get('rayon')->result_array() as $row)
			$rayon[$row['id']] = $row['name'];

		return $rayon;
	}

	// Справочник кол-ва комнат
	public function getRoomList()
	{
		$this->db->select('id, num_rooms');
		$this->db->where('banned', 0);
		$this->db->where('num_rooms >', 0);
		$this->db->order_by('name', 'ASC');

		$room = array();
		foreach ($this->db->get('room')->result_array() as $row)
			$room[$row['id']] = $row['num_rooms'];

		return $room;
	}

	// Тип домов
	public function getTypeList()
	{
		$this->db->select('id, name');
		$this->db->where('banned', 0);
		$this->db->order_by('name', 'ASC');	
		$type = array();
		foreach ($this->db->get('type')->result_array() as $row)
			$type[$row['id']] = $row['name'];

		return $type;
	}

	// Тип сделки
	public function getTypesdelkaList()
	{
		$this->db->select('id, name');
		$this->db->where('banned', 0);
		$this->db->order_by('name', 'ASC');	
		$type = array();
		foreach ($this->db->get('typesdelka')->result_array() as $row)
			$type[$row['id']] = $row['name'];

		return $type;
	}

	// Тип сделки
	public function getTypeprodazhaList()
	{
		$this->db->select('id, name');
		$this->db->where('banned', 0);
		$this->db->order_by('name', 'ASC');	
		$type = array();
		foreach ($this->db->get('typeprodazha')->result_array() as $row)
			$type[$row['id']] = $row['name'];

		return $type;
	}

	// Отделка
	public function getOtdelkaName($otdelkaId)
	{
		switch ($otdelkaId)
		{
			case 3:
				$name = 'евро';
				break;
			
			case 5:
			case 8:
				$name = 'черновая отделка';
				break;

			case 2:
				$name = 'требует ремонта';
				break;

			case 7:
				$name = 'хороший';
				break;

			case 1:
			case 6:
				$name = 'с отделкой';
				break;

			default:
				$name = '';
				break;
		}

		return $name;
	}

	public function getWcName($arr)
	{
		$name = '';

		if (count($arr) > 0)
		{
			$tmp = array();

			foreach ($arr as $row)
			{
				if (mb_strpos($row, 'в'))
				{
					$tmp['split']['b'] = $row;
				}
				else if (mb_strpos($row, 'т'))
				{
					$tmp['split']['t'] = $row;
				}
				else if (mb_strpos($row, 'с'))
				{
					$tmp['merge'] = $row;	
				}
			}

			if (isset($tmp['split']) AND ((isset($tmp['split']['b']) AND count($tmp['split']['b'])) || (isset($tmp['split']['t']) AND count($tmp['split']['t']))))
			{
				$name = 'раздельный';
			}
			else
			{
				if (isset($tmp['merge']))
					$name = (count($tmp['merge']) == 1)?'совмещенный':count($tmp['merge']);
			}
		}

		return $name;
	}


	// Тип загородной недвижимости
	public function getZagorodType($data)
	{
		$type = false;

		if ($data['is_cottage'])
		{
			$type = 'коттедж';
		}
		else
		{
			if ($data['square_dom'] > 0 AND $data['square_uchastok'] > 0)
			{
				$type = 'дом с участком';
			}
			else if ($data['square_dom'] > 0)
			{
				$type = 'дом';
			}
			else if ($data['square_uchastok'] > 0)
			{
				$type = 'участок';
			}
		}

		return $type;
	}
}