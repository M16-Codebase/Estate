<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class buildings_model extends MY_Model
{
	public $typesdelka = 'sdelka_arenda';

	// конструктор
	public function __construct()
	{
		parent::__construct();
		
		include(MDPATH.'buildings/moduleinfo.php');
		$this->module = $moduleinfo['name'];
		$this->table  = $moduleinfo['table'];
		$this->conf   = $this->load->config($this->module.'/'.$this->module, true);

		$this->uriArray = array(
			'buildings'		=> 0,
			'resale'		=> 1,
			'assignment'	=> 8,
			'residential'	=> 2,
			'elite'			=> 3,
			'exclusive'		=> 9,
			'commercial'	=> 4,
			'land'			=> 6,
			'world'			=> 5
		);
	}

	// Для переуступок возвращаем ссылку на новостройку по названию переуступки
	function getNewBuildingLinkByName($name = '')
	{
		if ($name == '')
			return false;

		$this->db->select('link');
		$this->db->like('name', $name);
		$this->db->like('razdelu','0');
		$this->db->where('banned','0');
		$query = $this->db->get('buildings');

		if ($query->num_rows())
		{
			$row = $query->row_array();
			return $row['link'];
		}
	}

	function isBuld($name){
        if ($name == '')
            return 0;

        $this->db->select('name');
        $this->db->like('link', $name);
        $this->db->where('banned','0');
        $query = $this->db->get('buildings');

        if ($query->num_rows())
        {
            return count($query->num_rows());
        }
        else
        {
            return 0;
        }
    }

	/**
	 * Вытаскиваем минимальную цену
	**/
	function complexMinPrice($complexId)
	{
		$this->db->select_min('price');
		$this->db->where('banned','0');
		//$this->db->where('banned','0');
		$this->db->where('price > 500000');
		$this->db->where('novostroy_id', $complexId);
		$query = $this->db->get('apartments');
		$price = $query->row_array();

		return $price['price'];
	}

	function cottageMinPrice($complexId)
	{
		$this->db->select_min('price');
		$this->db->where('banned','0');
		$this->db->where('parent_id', $complexId);
		$query = $this->db->get('cottages');
		$price = $query->row_array();

		return $price['price'];
	}

	function complexPriceForMeter($complexId)
	{
		$this->db->select_min('price_for_meter');
		$this->db->where('banned','0');
		$this->db->where('novostroy_id', $complexId);
		$query = $this->db->get('apartments');
		$price = $query->row_array();

		return (int)$price['price_for_meter'];
	}

	function complexMinMaxPrice($complexId)
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price > 500000');
		$this->db->where('novostroy_id', $complexId);
		$query = $this->db->get('apartments');
		$result = $query->result_array();
		return $result[0];
	}

	function complexMinSquare($complexId)
	{
		$this->db->select_min('square_all');
		$this->db->where('banned','0');
		$this->db->where('novostroy_id', $complexId);
		$query = $this->db->get('apartments');
		$price = $query->row_array();

		return $price['square_all'];
	}

	function complexMaxSquare($complexId)
	{
		$this->db->select_max('square_all');
		$this->db->where('banned','0');
		$this->db->where('novostroy_id', $complexId);
		$query = $this->db->get('apartments');
		$price = $query->row_array();

		return $price['square_all'];
	}

	/**
	 * Вытаскиваем квартиры которые относятся к данному комплексу
	**/
	function room_complex($id)
	{
		$this->db->where('novostroy_id', $id);
		$this->db->where('banned','0');
		$this->db->order_by('price', 'ASC');
		$query = $this->db->get('apartments');

		return $query->result_array();
	}
	function seoblock($cat){
		$this->db->where('link', $cat);
		$query = $this->db->get('residential_object');
		return $query->result_array();
	}

	function paginations($offset, $uri, $params = '', $search = '')
	{
		//echo $search;
		if (!empty($params))
		{
			$offset = $params['page'];

			switch ($uri)
			{
				case 'buildings':
					$uu = 0;
					break;

				case 'resale':
					$uu = 1;
					break;

				case 'assignment':
					$uu = 8;
					break;

				case 'residential':
					$uu = 2;
					break;

				case 'elite':
					$uu = 3;
					break;

				case 'exclusive':
					$uu = 9;
					break;

				case 'commercial':
					$uu = 4;
					break;
			}

			$this->functions($params, $uu);
		}
		else
		{
			$this->db->where('buildings.banned', 0);
		}

		// Поиск по названию, адресу или тексту
		if (!empty($search))
		{
			$this->db->like('name', $search);
			$this->db->or_like('adress', $search);
			$this->db->or_like('text', $search);
		}
		else
		{
			if(!empty($uri) and $uri != 'land')
			{
				$this->sortMethod = isset($_SESSION[$uri]) ? $_SESSION[$uri] : '';
			}
			elseif($uri == 'land')
			{
				if (!empty($_SESSION[$uri]))
				{
					if (is_numeric($_SESSION[$uri]))
					{
						$this->db->where('razdel_uchastok_id', $_SESSION[$uri]);
					}
					else
					{
						unset($_SESSION[$uri]);
					}
				}
			}
		}

		/*
		if($uri == 'buildings') {
			if($this->sortMethod == 'price') {
				$this->db->select('*');
				$this->db->select('buildings.id,buildings.price as bprice,apartments.price');
				$this->db->select_min('apartments.price');
				$this->db->join('apartments', 'apartments.novostroy_id = buildings.id', 'left');
			}
		}
		*/

		$sort = $this->sortAscDesc;

		if (isset($this->conf['sortList']))
			$sort = $this->conf['sortList'];

		if (!empty($uri))
			//echo($this->uriArray[$uri]);
			$this->db->like('buildings.razdelu', $this->uriArray[$uri]);

		if (!empty($uri))
		{
			switch ($uri)
			{
				case 'buildings':
					if ($this->session->userdata('sort_price_buildings'))
					{
						$this->db->order_by('buildings.virtual_price', $this->session->userdata('sort_price_buildings'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.virtual_price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'resale':
					if ($this->session->userdata('sort_price_resale'))
					{
						$this->db->order_by('buildings.price', $this->session->userdata('sort_price_resale'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'assignment':
					if ($this->session->userdata('sort_price_assignment'))
					{
						$this->db->order_by('buildings.price', $this->session->userdata('sort_price_assignment'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'elite':
					if ($this->session->userdata('sort_price_elite')){
						$this->db->order_by('buildings.price', $this->session->userdata('sort_price_elite'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'excluive':
					if ($this->session->userdata('sort_price_exclusive')){
						$this->db->order_by('buildings.price', $this->session->userdata('sort_price_exclusive'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'residential':
					if ($this->session->userdata('sort_price_residential')){
						$this->db->order_by('buildings.price', $this->session->userdata('sort_price_residential'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;

				case 'commercial':
					if ($this->session->userdata('sort_price_commercial'))
					{
						$this->db->order_by('buildings.price,buildings.price_arenda', $this->session->userdata('sort_price_commercial'));
					}
					else
					{
						$this->db->order_by('buildings.sort', 'ASC');
						$this->db->order_by('buildings.price,buildings.price_arenda', 'ASC');
						//$this->db->order_by('buildings.views', 'DESC');
					}
					break;
			}
		}

		//if ($this->session->userdata('sort_name_buildings')){
		//    $this->db->order_by('buildings.name', $this->session->userdata('sort_name_buildings'));
		//}

		// проверяем нужно ли использовать пагинацию
		if ($offset !== 'all')
		{
			$per_paging = 16; // количество записей на страницу

			if(empty($offset)) {
				$offset = 0;
			}

			if ($offset == 0 or $offset == 1)
			{
				$offset = 0;
			}
			else
			{
				$offset = ($offset-1) * $per_paging;
			}

			$this->db->limit($per_paging, $offset);

		}
		$this->db->distinct();
		$query = $this->db->get($this->table);
		echo '<div id="sasai" style="display: none;">'.$this->db->last_query().'</div>';
		$result = $query->result_array();

		return $this->lang_load($result, $this->table);
	}

	function all_counts($banned = false, $where = '', $like = '', $params = '', $search)
	{
		if (!empty($params))
			$this->functions($params, $like['razdelu']);

		if (!empty($search))
		{
			$this->db->like('name', $search);
			$this->db->or_like('adress', $search);
		}

		if(!$banned)
			$this->db->where('buildings.banned','0');

		if(!empty($where))
		{
			if(is_array($where))
			{
				foreach($where as $k=>$v)
					$this->db->where($k, $v);
			}
			else
			{
				$this->db->where($where);
			}
		}

		if (!empty($like))
		{
			$this->db->like($like);
		}

		$query = $this->db->get($this->table)->result_array();

		$querye = '';
		if (!empty($query))
		{
			foreach($query as $k)
				$querye[$k['link']] = '';
		}

		return count($querye);
	}

	function getSrok($param)
	{
		/*$fr = array('1'=>'01.01', '2'=>'01.04', '3'=>'01.07', '4'=>'01.10');
		$to = array('1'=>'31.03', '2'=>'30.06', '3'=>'30.09', '4'=>'31.12');
		$pa = substr($param, 0, 1);
		$y = substr($param, 1, 4);*/
		$srok['from'] = strtotime('01.01'.'.'.$param);
		$srok['to'] = strtotime('31.12'.'.'.$param);
		return $srok;
	}

	function functions($params, $uri)
	{
		$this->db->where('buildings.banned',0); // вытаскивать только видимые данные

		switch ($uri)
		{
			// Новостройки
			case 0:
				$this->db->select('buildings.id, buildings.name, buildings.adress, buildings.user_price_for_meter, buildings.razdelu, buildings.id_complex, buildings.map, buildings.metro_id, buildings.rayon_id, buildings.link, buildings.korpus_value, buildings.korpus, buildings.mainfoto, buildings.virtual_price, buildings.ipoteka, buildings.fz214, buildings.parking, buildings.rassrochka, buildings.otdelka_id');
				
				// Таблица с аппартаментами
				if (!empty($params['room']) || !empty($params['price_from']) || !empty($params['price_to']) || !empty($params['square_from']) || !empty($params['square_to']))
					$this->db->join('apartments', 'buildings.id = apartments.novostroy_id', 'inner');

				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && empty($params['rayon']))
				{

					

					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && empty($params['metro']))
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Срок
				if (!empty($params['srok']))
				{
					$where = array();
					$srok = explode(',', $params['srok']);
					
					foreach ($srok as $v)
					{
						if ($v == 1)
						{
							$where[] = "(`ci_buildings`.`korpus_value` <=".time().")";
						}
						else {
							$tt = $this->getSrok($v);
							$where[] = "(`ci_buildings`.`korpus_value` >=". $tt['from']." AND `ci_buildings`.`korpus_value` <=". $tt['to'].")";
						}
					}

					$this->db->where("(".implode(' OR ', $where).")");
				}

				// Адрес
				if (!empty($params['adress']))
					$this->db->like('adress', $params['adress']);

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("apartments.room_id", explode(',', $params['room']));

				// Ипотека
				if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
					$this->db->where('ipoteka', $params['ipoteka']);

				// С отделкой
				if (!empty($params['otdelka']) && $params['otdelka'] == 1)
					$this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

				// Рассрочка
				if (!empty($params['rassrochka']))
					$this->db->where('rassrochka', $params['rassrochka']);

				// ФЗ-214
				if (!empty($params['fz214']))
					$this->db->where('fz214', $params['fz214']);

				// Парковка
				if (!empty($params['parking']))
					$this->db->where('parking', $params['parking']);

				// Название
				if (!empty($params['name']))
					$this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");

				// Тип дома
				if (!empty($params['type']))
					$this->db->where("type_id IN (".$params['type'].")");

				// Класс
				if (!empty($params['class']))
					$this->db->where("class IN (".$params['class'].")");

				// Застройщик
				if (!empty($params['builder']))
					$this->db->where("builder_id IN (".$params['builder'].")");

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('buildings.virtual_price >=', intval($params['price_from']*1000000));

				// Цена до
				if (!empty($params['price_to']))
					$this->db->where('apartments.price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('apartments.square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('apartments.square_all <=', intval($params['square_to']));
				break;

			// Городская
			case 1:
				$this->db->select('*');
				
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0) // метро
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}
				
				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0) // метро
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}
				
				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0) // метро
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}
				
				// Ипотека
				if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
					$this->db->where('ipoteka', $params['ipoteka']);

				// С отделкой
				if (!empty($params['otdelka']) && $params['otdelka'] == 1)
					$this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

				// ФЗ-214
				if (!empty($params['fz214']))
					$this->db->where('fz214', $params['fz214']);

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("room_id", explode(',', $params['room']));

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('price >=', intval($params['price_from']*1000000));

				// Цена до
				if(!empty($params['price_to']))
					$this->db->where('price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('square_all <=', intval($params['square_to']));

				// Адрес
				if (!empty($params['adress']))
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");
				break;
			


			// Городская
			case 8:
				$this->db->select('*');
				
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0) // метро
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}
				
				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0) // метро
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}
				
				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0) // метро
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}
				
				// Тип дома
				if (!empty($params['type']))
					$this->db->where("type_id IN (".$params['type'].")");

				// Класс
				if (!empty($params['class']))
					$this->db->where("class IN (".$params['class'].")");

				// Застройщик
				if (!empty($params['builder']))
					$this->db->where("builder_id IN (".$params['builder'].")");

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("room_id", explode(',', $params['room']));

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('price >=', intval($params['price_from']*1000000));

				// Цена до
				if(!empty($params['price_to']))
					$this->db->where('price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('square_all <=', intval($params['square_to']));

				// Адрес
				if (!empty($params['adress']))
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");
				break;

			// Загородная
			case 2:
				$this->db->select('buildings.*');
				//$this->db->join('cottages', 'buildings.id = cottages.parent_id');
				$ressql = array();
				$cotsql = array();

				$iscot = true;

				//$this->db->like('buildings.razdelu', '2');

				// Район
				if (!empty($params['rayon']))
				{
					//$this->db->where("rayon_id IN (".$params['rayon'].")");
					$rayon		= " AND ci_buildings.rayon_id IN (".$params['rayon'].")";
					$ressql[]	= "`ci_buildings`.rayon_id IN (".$params['rayon'].")";
				}

				// Материал
				if (!empty($params['matherial']))
				{
					//$this->db->where("matherial_id IN (".$params['matherial'].")");
					$ressql[] = "`ci_buildings`.matherial_id IN (".$params['matherial'].")";
				}

				// Тип
				if (!empty($params['type']))
				{
					//$this->db->where("res_type IN (".$params['type'].")");
					$ressql[] = "`ci_buildings`.res_type IN (".$params['type'].")";
					$cc = explode(',',$params['type']);
					if (!in_array(2,$cc))
						$iscot = false;
				}

				$price = '';
				
				// Цена от
				if (!empty($params['price_from']))
				{
					//$this->db->where('buildings.price >=', intval($params['price_from']*1000000));
					$ressql[] = "`ci_buildings`.price >= ".intval($params['price_from']*1000000);
					$cotsql[] = "`ci_cottages`.price >= ".intval($params['price_from']*1000000);
				}
				
				// Цена до
				if (!empty($params['price_to']))
				{
					//$this->db->where('buildings.price <=', intval($params['price_to']*1000000));
					$ressql[] = "`ci_buildings`.price <= ".intval($params['price_to']*1000000);
					$cotsql[] = "`ci_cottages`.price <= ".intval($params['price_to']*1000000);
				}

				// Площадь участка от
				if (!empty($params['sq_uch_from'])) // площадь участка
				{
					//$this->db->where('square_uchastok >= ', $params['sq_uch_from']);
					$ressql[] = "`ci_buildings`.square_uchastok >= ".$params['sq_uch_from'];
					$cotsql[] = "`ci_cottages`.land_square >= ".$params['sq_uch_from'];
				}

				// Площадь участка до
				if (!empty($params['sq_uch_to']))
				{
					//$this->db->where('square_uchastok <= ', $params['sq_uch_to']);
					$ressql[] = "`ci_buildings`.square_uchastok <= ".$params['sq_uch_to'];
					$cotsql[] = "`ci_cottages`.land_square <= ".$params['sq_uch_to'];
				}

				// Площадь дома от
				if (!empty($params['sq_dom_from']))
				{
					//$this->db->where('square_dom >= ', $params['sq_dom_from']);
					$ressql[] = "`ci_buildings`.square_dom >= ".$params['sq_dom_from'];
					$cotsql[] = "`ci_cottages`.house_square >= ".$params['sq_dom_from'];
				}

				// Площадь дома до
				if (!empty($params['sq_dom_to']))
				{
					//$this->db->where('square_dom <= ', $params['sq_dom_to']);
					$ressql[] = "`ci_buildings`.square_dom <= ".$params['sq_dom_to'];
					$cotsql[] = "`ci_cottages`.house_square <= ".$params['sq_dom_to'];
				}

				/* if(!empty($params['vodoem'])) // водоем
				{
					$this->db->like('vodoem_id', $params['vodoem']);
					$ressql[] = "`ci_buildings`.vodoem_id LIKE '%".$params['vodoem']."%'";
				}*/

				// Инфраструктура
				if (!empty($params['infrostr'])) // инфроструктрура
				{
					//$this->db->where('infrostructura_id >', 0);
					$ressql[] = "`ci_buildings`.infrostructura_id > 0";
				}

				// Газ
				if (!empty($params['gas']))
				{
					//$this->db->where('z_gas >', 0);
					$ressql[] = "`ci_buildings`.z_gas > 0";
				}

				// Вода
				if (!empty($params['water']))
				{
					$this->db->where('z_water > ', 0);
					$ressql[] = "`ci_buildings`.z_water > 0";
				}
				
				// Электричество
				if (!empty($params['electric']))
				{
					//$this->db->where('z_electric > ', 0);
					$ressql[] = "`ci_buildings`.z_electric > 0";
				}
				
				// Водоем
				if (!empty($params['vodoem']))
				{
					//$this->db->where('vodoem_id > ', 0);
					$ressql[] = "`ci_buildings`.vodoem_id > 0";
				}
				
				// Рассрочка
				if (!empty($params['rassrochka']))
				{
					//$this->db->where('rassrochka > ', 0);
					$ressql[] = "`ci_buildings`.rassrochka > 0";
				}
				
				if (!empty($params['name'])) // адрес
				{
					//$this->db->where("(`name` LIKE '%".$params['name']."%' || `cottage` LIKE '%".$params['name']."%')");
					$ressql[] = "(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`cottage` LIKE '%".$params['name']."%')";
				}

				if (count($ressql) > 0)
				{
					$sss = "(
								".implode(' AND ', $ressql)."
							)";

					if ($iscot){
						$sss .= "
								OR EXISTS (
									SELECT ci_cottages.*
									FROM `ci_cottages`
									WHERE
									ci_buildings.id = ci_cottages.parent_id AND
									".implode(' AND ', $cotsql)."
									AND ci_cottages.banned = '0'
								)
						";
					}
					$this->db->where("(".$sss.")");
				}
				break;

			// Элитная
			case 3:
			case 9:
				// Метро
				if (!empty($params['metro']) && empty($params['rayon']))
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}
				
				// Район
				if (!empty($params['rayon']) && empty($params['metro']))
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}
				
				// Метро и район
				if (!empty($params['rayon']) && !empty($params['metro']))
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("room_id", explode(',', $params['room']));

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('price >=', intval($params['price_from']*1000000));

				// Цена до
				if (!empty($params['price_to']))
					$this->db->where('price <=', intval($params['price_to']*1000000));
				
				// Тип
				if (!empty($params['type']))
					$this->db->where("elite_type IN (".$params['type'].")");
				
				// Площадь от
				if (!empty($params['square_from'])) //
					$this->db->where('square_all >=', floatval($params['square_from']));

				// Площадь до			
				if (!empty($params['square_to']))
					$this->db->where('square_all <=', intval($params['square_to']));
				
				// Адрес
				if (!empty($params['adress']))
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");
				
				// Название
				if (!empty($params['name']))
					$this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");
				break;

			// Коммерческая
			case 4:
				$this->db->select('*');

				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Тип сделки
				if ($params['sdelka'] > 0)
				{
					$this->db->join('sdelka_arenda', 'buildings.id = sdelka_arenda.building_id', 'left');
					$this->db->where("sdelka_arenda.typesdelka_id", $params['sdelka']);
				}

				// Цена
				if ($params['price_from'] > 0 && $params['price_to'] > 0)
				{
					$this->db->where('price >=', intval($params['price_from']*1000000));
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}
				else
				{
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}

				// Площадь
				if ($params['square_to'] > 0 && $params['square_from'] > 0)
				{
					$this->db->where('comm_square <=', floatval($params['square_to']));
					$this->db->where('comm_square >=', floatval($params['square_from']));
				}
				elseif($params['square_to'] > 0)
				{
					$this->db->where('comm_square <=', intval($params['square_to']));
				}

				// Адрес
				if (!empty($params['adress']))
				{
					$this->db->like('adress', $params['adress']);
				}

				// Название
				if (!empty($params['name']))
				{
					$this->db->like('name', $params['name']);
				}

				// Тип
				if (!empty($params['type']))
				{
					$this->db->where_in('comm_type', explode(',', $params['type']));
				}

				// Вид
				if (!empty($params['vid']))
				{
					$this->db->where_in('comm_vid', explode(',', $params['vid']));
				}

				// Бизнес
				if (!empty($params['bussines']))
				{
					$this->db->where_in('comm_bussines', explode(',', $params['bussines']));
				}

				// Оплата
				if (!empty($params['pay']))
				{
					$this->db->where_in("comm_pay", explode(',', $params['pay']));
				}

				// ???
				if (!empty($params['forwhat']))
				{
					$this->db->where_in("comm_forwhat", explode(',', $params['forwhat']));
				}
				break;

			case 6:

				break;
		}

		$this->db->group_by('buildings.id');
		// $this->db->distinct('buildings.id');
	}

	function allBuildings($cat=0, $select='id')
	{
		$this->db->select($select);
		$this->db->like('razdelu', $cat);
		$this->db->where('banned','0');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result;
	}

	function allBuildingsFor()
	{
		$this->db->select('id');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result;
	}

	function getBuildingsAll($ids){
		$this->db->select('*');
		$this->db->where_in('id', $ids);
		$this->db->where('banned','0');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result;
	}

	function allMapBuildings($id = 0, $params=null){
		$this->db->like('buildings.razdelu', $id);
		$this->functions($params, $id);
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result;
	}

	public function getSdelka($id = NULL)
	{
		if ($id == NULL)
			return false;

		$this->db->where('building_id', $id);

		$query = $this->db->get($this->typesdelka);
		return $query->result_array();
	}

	public function updateViews($id = NULL)
	{
		if ($id == NULL)
			return false;

		$this->db->where('id', $id);
		$this->db->set('views', 'views+1', FALSE);
		$this->db->update($this->table);

		return true;
	}

	public function findCount($building, $params, $minmax = null, $military = '')
	{
		$this->db->select('buildings.id');
		$this->db->where('buildings.banned', 0);

        if($military == 'military') {

            $this->db->where('military','1'); // вытаскивать только видимые данные
            $this->db->where('military_banned','0'); // вытаскивать только видимые данные

            $get_active_tab = $this->session->userdata('military_active_tab');
            if($get_active_tab == 'buildings') {
                $this->db->where('razdelu', 0);
            } else {
                $this->db->not_like('razdelu', '0');
            }

            // Банк
            if (!empty($params['bank']))
                $this->db->where("banks IN (".$params['bank'].")");

        } elseif($military == 'commerce') {

            $this->db->where('commerce','1'); // вытаскивать только видимые данные
            $this->db->where('commerce_banned','0'); // вытаскивать только видимые данные

            $get_active_tab = $this->session->userdata('commerce_active_tab');
            if($get_active_tab == 'buildings') {
                $this->db->where('razdelu', 4);
                $building = 4;
            } else {
                $this->db->not_like('razdelu', '0');
            }

            // Банк
            if (!empty($params['bank']))
                $this->db->where("banks IN (".$params['bank'].")");

        } else {
            $this->db->like('razdelu', $building);
        }

		switch ($building)
		{
			case 0:
				// Цена от
				if (!empty($params['price_from']) && $minmax['min'] == $params['price_from'])
					$params['price_from'] = 0;

				// Цена от
				if (!empty($params['price_to']) && $minmax['max'] == $params['price_to'])
					$params['price_to'] = 0;

				// Площадь от
				if (!empty($params['square_from']) && $minmax['mins'] == $params['square_from'])
					$params['square_from'] = 0;

				// Площадь до
				if (!empty($params['square_to']) && $minmax['maxs'] == $params['square_to'])
					$params['square_to'] = 0;

				// 
				if (!empty($params['room']) || !empty($params['price_from']) || !empty($params['price_to']) || !empty($params['square_from']) || !empty($params['square_to']))
					$this->db->join('apartments', 'buildings.id = apartments.novostroy_id', 'right');

				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && empty($params['rayon']))
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}
				
				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && empty($params['metro']))
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}
				
				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Срок
				if (!empty($params['srok']))
				{
					$where = array();
					$srok = explode(',', $params['srok']);
					foreach ($srok as $v)
					{
						if ($v == 1)
						{
							$where[] = "(`ci_buildings`.`korpus_value` <=".time().")";
						}
						else
						{
							$tt = $this->getSrok($v);
							$where[] = "(`ci_buildings`.`korpus_value` >=". $tt['from']." AND `ci_buildings`.`korpus_value` <=". $tt['to'].")";
						}
					}

					$this->db->where("(".implode(' OR ', $where).")");
				}

				// Адрес
				if (!empty($params['adress']))
					$this->db->like('adress', $params['adress']);

				 // Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("apartments.room_id", explode(',', $params['room']));

				// Ипотека
				if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
					$this->db->where('ipoteka', $params['ipoteka']);

				// Рассрочка
				if (!empty($params['rassrochka']))
					$this->db->where('rassrochka', $params['rassrochka']);

				// ФЗ-214
				if (!empty($params['fz214']))
					$this->db->where('fz214', $params['fz214']);

				// С отделкой
				if (!empty($params['otdelka']) && $params['otdelka'] == 1)
					$this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

				// Парковка
				if (!empty($params['parking']))
					$this->db->where('parking', $params['parking']);

				// Название
				if (!empty($params['name']))
					$this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");

				// Тип дома
				if (!empty($params['type']))
					$this->db->where("type_id IN (".$params['type'].")");

				// Класс
				if (!empty($params['class']))
					$this->db->where("class IN (".$params['class'].")");

				// Застройщик
				if (!empty($params['builder']))
					$this->db->where("builder_id IN (".$params['builder'].")");

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('apartments.price >=', intval($params['price_from']*1000000));

				// Цена до
				if (!empty($params['price_to']))
					$this->db->where('apartments.price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('apartments.square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('apartments.square_all <=', intval($params['square_to']));
				
				break;
			
			case 1:
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Тип дома
				if (!empty($params['type']))
					$this->db->where("type_id IN (".$params['type'].")");

				// Класс
				if (!empty($params['class']))
					$this->db->where("class IN (".$params['class'].")");

				// Застройщик
				if (!empty($params['builder']))
					$this->db->where("builder_id IN (".$params['builder'].")");

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("room_id", explode(',', $params['room']));

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('price >=', intval($params['price_from']*1000000));

				// Цена до
				if (!empty($params['price_to']))
					$this->db->where('price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('square_all <=', intval($params['square_to']));

				// Адрес
				if (!empty($params['adress']))
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");

				// Ипотека
				if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
					$this->db->where('ipoteka', $params['ipoteka']);

				// ФЗ-214
				if (!empty($params['fz214']))
					$this->db->where('fz214', $params['fz214']);

				// С отделкой
				if (!empty($params['otdelka']) && $params['otdelka'] == 1)
					$this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

				break;

			case 8:
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Тип дома
				if (!empty($params['type']))
					$this->db->where("type_id IN (".$params['type'].")");

				// Класс
				if (!empty($params['class']))
					$this->db->where("class IN (".$params['class'].")");

				// Застройщик
				if (!empty($params['builder']))
					$this->db->where("builder_id IN (".$params['builder'].")");

				// Ипотека
				if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
					$this->db->where('ipoteka', $params['ipoteka']);

				// ФЗ-214
				if (!empty($params['fz214']))
					$this->db->where('fz214', $params['fz214']);

				// С отделкой
				if (!empty($params['otdelka']) && $params['otdelka'] == 1)
					$this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

				// Кол-во комнат
				if (!empty($params['room']))
					$this->db->where_in("room_id", explode(',', $params['room']));

				// Цена от
				if (!empty($params['price_from']))
					$this->db->where('price >=', intval($params['price_from']*1000000));

				// Цена до
				if (!empty($params['price_to']))
					$this->db->where('price <=', intval($params['price_to']*1000000));

				// Площадь от
				if (!empty($params['square_from']))
					$this->db->where('square_all >=', floatval($params['square_from']));

				// Площадь до
				if (!empty($params['square_to']))
					$this->db->where('square_all <=', intval($params['square_to']));

				// Адрес
				if (!empty($params['adress']))
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");

				break;

			case 2:
				$ressql = array();
				$cotsql = array();

				$iscot = true;

				//$this->db->like('buildings.razdelu', '2');

				// Район
				if (!empty($params['rayon']))
				{
					//$this->db->where("rayon_id IN (".$params['rayon'].")");
					$rayon = " AND ci_buildings.rayon_id IN (".$params['rayon'].")";
					$ressql[] = "`ci_buildings`.rayon_id IN (".$params['rayon'].")";
				}

				// Материал
				if (!empty($params['matherial']))
				{
					//$this->db->where("matherial_id IN (".$params['matherial'].")");
					$ressql[] = "`ci_buildings`.matherial_id IN (".$params['matherial'].")";
				}

				// Тип
				if (!empty($params['type']))
				{
					//$this->db->where("res_type IN (".$params['type'].")");
					$ressql[] = "`ci_buildings`.res_type IN (".$params['type'].")";
					$cc = explode(',',$params['type']);
					if (!in_array(2,$cc))
						$iscot = false;
				}

				$price = '';

				// Цена от
				if (!empty($params['price_from']))
				{
					//$this->db->where('buildings.price >=', intval($params['price_from']*1000000));
					$ressql[] = "`ci_buildings`.price >= ".intval($params['price_from']*1000000);
					$cotsql[] = "`ci_cottages`.price >= ".intval($params['price_from']*1000000);
				}

				// Цена до
				if (!empty($params['price_to']))
				{
					//$this->db->where('buildings.price <=', intval($params['price_to']*1000000));
					$ressql[] = "`ci_buildings`.price <= ".intval($params['price_to']*1000000);
					$cotsql[] = "`ci_cottages`.price <= ".intval($params['price_to']*1000000);
				}

				// Площадь участка от
				if (!empty($params['sq_uch_from'])) // площадь участка
				{
					//$this->db->where('square_uchastok >= ', $params['sq_uch_from']);
					$ressql[] = "`ci_buildings`.square_uchastok >= ".$params['sq_uch_from'];
					$cotsql[] = "`ci_cottages`.land_square >= ".$params['sq_uch_from'];
				}

				// Площадь участка до
				if (!empty($params['sq_uch_to']))
				{
					//$this->db->where('square_uchastok <= ', $params['sq_uch_to']);
					$ressql[] = "`ci_buildings`.square_uchastok <= ".$params['sq_uch_to'];
					$cotsql[] = "`ci_cottages`.land_square <= ".$params['sq_uch_to'];
				}

				// Площадь дома от
				if (!empty($params['sq_dom_from']))
				{
					//$this->db->where('square_dom >= ', $params['sq_dom_from']);
					$ressql[] = "`ci_buildings`.square_dom >= ".$params['sq_dom_from'];
					$cotsql[] = "`ci_cottages`.house_square >= ".$params['sq_dom_from'];
				}

				// Площадь дома до
				if (!empty($params['sq_dom_to']))
				{
					//$this->db->where('square_dom <= ', $params['sq_dom_to']);
					$ressql[] = "`ci_buildings`.square_dom <= ".$params['sq_dom_to'];
					$cotsql[] = "`ci_cottages`.house_square <= ".$params['sq_dom_to'];
				}

				/*
				if (!empty($params['vodoem'])) // водоем
				{
					$this->db->like('vodoem_id', $params['vodoem']);
					$ressql[] = "`ci_buildings`.vodoem_id LIKE '%".$params['vodoem']."%'";
				}
				*/

				// Инфраструктура
				if (!empty($params['infrostr']))
				{
					//$this->db->where('infrostructura_id >', 0);
					$ressql[] = "`ci_buildings`.infrostructura_id > 0";
				}

				// Газ
				if (!empty($params['gas']))
				{
					//$this->db->where('z_gas >', 0);
					$ressql[] = "`ci_buildings`.z_gas > 0";
				}

				// Вода
				if (!empty($params['water']))
				{
					$this->db->where('z_water > ', 0);
					$ressql[] = "`ci_buildings`.z_water > 0";
				}

				// Электричество
				if (!empty($params['electric']))
				{
					//$this->db->where('z_electric > ', 0);
					$ressql[] = "`ci_buildings`.z_electric > 0";
				}

				// Водоем
				if (!empty($params['vodoem']))
				{
					//$this->db->where('vodoem_id > ', 0);
					$ressql[] = "`ci_buildings`.vodoem_id > 0";
				}

				// Рассрочка
				if (!empty($params['rassrochka']))
				{
					//$this->db->where('rassrochka > ', 0);
					$ressql[] = "`ci_buildings`.rassrochka > 0";
				}

				// Название
				if (!empty($params['name']))
				{
					//$this->db->where("(`name` LIKE '%".$params['name']."%' || `cottage` LIKE '%".$params['name']."%')");
					$ressql[] = "(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`cottage` LIKE '%".$params['name']."%')";
				}

				// 
				if (count($ressql) > 0)
				{
					$sss = "(
									".implode(' AND ', $ressql)."
								)";

					if ($iscot)
					{
						$sss .= "
									OR EXISTS (
										SELECT ci_cottages.*
										FROM `ci_cottages`
										WHERE
										ci_buildings.id = ci_cottages.parent_id AND
										".implode(' AND ', $cotsql)."
										AND ci_cottages.banned = '0'
									)
							";
					}
					$this->db->where("(".$sss.")");
				}

				break;

			case 3:
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Кол-во комнат
				if ($params['room'] > 0)
				{
					$this->db->where_in("room_id", explode(',', $params['room']));
				}

				// Цена от
				if ($params['price_from'] > 0 and $params['price_to'] > 0)
				{
					$this->db->where('price >=', intval($params['price_from']*1000000));
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}
				else
				{
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}

				// Тип
				if (!empty($params['type']))
				{
					$this->db->where("elite_type IN (".$params['type'].")");
				}

				// Площадь
				if ($params['square_from'] > 0 and $params['square_to'] > 0) //
				{
					$this->db->where('square_all <=', floatval($params['square_to']));
					$this->db->where('square_all >=', floatval($params['square_from']));
				}
				elseif($params['square_to'] > 0)
				{
					$this->db->where('square_all <=', intval($params['square_to']));
				}

				// Адрес
				if (!empty($params['adress'])) // адрес
				{
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");
				}

				// Название
				if (!empty($params['name']))
				{
					$this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");
				}

				break;

			case 9:
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Кол-во комнат
				if ($params['room'] > 0)
				{
					$this->db->where_in("room_id", explode(',', $params['room']));
				}

				// Цена от
				if ($params['price_from'] > 0 and $params['price_to'] > 0)
				{
					$this->db->where('price >=', intval($params['price_from']*1000000));
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}
				else
				{
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}

				// Тип
				if (!empty($params['type']))
				{
					$this->db->where("elite_type IN (".$params['type'].")");
				}

				// Площадь
				if ($params['square_from'] > 0 and $params['square_to'] > 0) //
				{
					$this->db->where('square_all <=', floatval($params['square_to']));
					$this->db->where('square_all >=', floatval($params['square_from']));
				}
				elseif($params['square_to'] > 0)
				{
					$this->db->where('square_all <=', intval($params['square_to']));
				}

				// Адрес
				if (!empty($params['adress'])) // адрес
				{
					$this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");
				}

				// Название
				if (!empty($params['name']))
				{
					$this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");
				}

				break;

			case 4:
				// Метро
				if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0)
				{
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
				}

				// Район 
				if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
				}

				// Метро и район
				if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
				{
					$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
					$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
					$this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
				}

				// Тип сделки
				if($params['sdelka'] > 0)
				{
					$this->db->join('sdelka_arenda', 'buildings.id = sdelka_arenda.building_id', 'left');
					$this->db->where("sdelka_arenda.typesdelka_id", $params['sdelka']);
				}

				if ($params['price_from'] > 0 and $params['price_to'] > 0)
				{
					$this->db->where('price >=', intval($params['price_from']*1000000));
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}
				else
				{
					$this->db->where('price <=', intval($params['price_to']*1000000));
				}

				if ($params['square_to'] > 0 and $params['square_from'] > 0)
				{
					$this->db->where('comm_square <=', floatval($params['square_to']));
					$this->db->where('comm_square >=', floatval($params['square_from']));
				}
				elseif($params['square_to'] > 0)
				{
					$this->db->where('comm_square <=', intval($params['square_to']));
				}

				// Адрес
				if (!empty($params['adress']))
					$this->db->like('adress', $params['adress']);

				// Название
				if (!empty($params['name']))
					$this->db->like('name', $params['name']);
				
				// Тип
				if (!empty($params['type']))
					$this->db->where_in("comm_type", explode(',', $params['type']));

				// Вид
				if (!empty($params['vid']))
					$this->db->where_in("comm_vid", explode(',', $params['vid']));

				// Бизнес
				if (!empty($params['bussines']))
					$this->db->where_in("comm_bussines", explode(',', $params['bussines']));

				// Оплата
				if (!empty($params['pay']))
					$this->db->where_in("comm_pay", explode(',', $params['pay']));

				// ???
				if (!empty($params['forwhat']))
					$this->db->where_in("comm_forwhat", explode(',', $params['forwhat']));
				break;
		}

		$this->db->distinct();
		$query = $this->db->get($this->table)->result_array();

		return count($query);
		// return $this->db->count_all_results('buildings');
	}


	// Новостройки
	function getMinMaxBuildings()
	{
		$this->db->select('MIN(virtual_price) as min, MAX(virtual_price) as max');
		$this->db->where('banned','0');
		$this->db->where('virtual_price >','0');
		$this->db->where('razdelu','0');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		//print_r($result[0]);
		return $result[0];
	}

	function getMinMaxBuildingsSquare()
	{
		$this->db->select('MIN(min_square) as min, MAX(max_square) as max');
		$this->db->where('banned','0');
		$this->db->where('min_square >','0');
		$this->db->where('razdelu','0');
		//echo($this->table);
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	// Общее
	function getMinMaxPrice($razdel)
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu',$razdel);
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxSquare($razdel)
	{
		$this->db->select('MIN(square_all) as min, MAX(square_all) as max');
		$this->db->where('banned','0');
		$this->db->where('square_all >','0');
		$this->db->where('razdelu',$razdel);
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	// Вторичка
	function getMinMaxResale()
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu','1');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxResaleSquare()
	{
		$this->db->select('MIN(square_all) as min, MAX(square_all) as max');
		$this->db->where('banned','0');
		$this->db->where('square_all >','0');
		$this->db->where('razdelu','1');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	// Загородная
	function getMinMaxResidential()
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu','2');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxResidentialSquare()
	{
		$this->db->select('MIN(square_dom) as min, MAX(square_dom) as max');
		$this->db->where('banned','0');
		$this->db->where('square_dom >','0');
		$this->db->where('razdelu','2');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxResidentialSquareUch()
	{
		$this->db->select('MIN(square_uchastok) as min, MAX(square_uchastok) as max');
		$this->db->where('banned','0');
		$this->db->where('square_uchastok >','0');
		$this->db->where('razdelu','2');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}


	// Элитная
	function getMinMaxElite()
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu','3');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxEliteSquare()
	{
		$this->db->select('MIN(square_all) as min, MAX(square_all) as max');
		$this->db->where('banned','0');
		$this->db->where('square_all >','0');
		$this->db->where('razdelu','3');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	// Эксклюзивная
	function getMinMaxExclusive()
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu','9');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxExclusiveSquare()
	{
		$this->db->select('MIN(square_all) as min, MAX(square_all) as max');
		$this->db->where('banned','0');
		$this->db->where('square_all >','0');
		$this->db->where('razdelu','9');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	// Коммерческая
	function getMinMaxCommercial()
	{
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price >','0');
		$this->db->where('razdelu','4');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getMinMaxCommercialSquare()
	{
		$this->db->select('MIN(comm_square) as min, MAX(comm_square) as max');
		$this->db->where('banned','0');
		$this->db->where('comm_square >','0');
		$this->db->where('razdelu','4');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}

	function getEliteNov()
	{
		$this->db->select('id,name');
		$this->db->where('banned','0');
		$this->db->where('razdelu','0');
		$this->db->where('razdelu','3');
		$query	= $this->db->get($this->table);
		$result	= $query->result_array();
		
		return $result[0];
	}
	
	function getYoutubeVideoID($video_code){
	 
		
		
		if(preg_match("/youtu.be\/[a-z1-9.-_]+/", $video_code)) {
			preg_match("/youtu.be\/([a-z1-9.-_]+)/", $video_code, $matches);
			if(isset($matches[1])) {
				$url = 'http://www.youtube.com/embed/'.$matches[1];
			}
		}
		else if(preg_match("/youtube.com(.+)v=([^&]+)/", $video_code)) {
			preg_match("/v=([^&]+)/", $video_code, $matches);
			if(isset($matches[1])) {
				$url = 'http://www.youtube.com/embed/'.$matches[1];
			}
		}
		
		return $video_code;
	}

	function getBanksBuilding($banks)
	{
		$this->db->select('*');
		$this->db->where('banned','0');
		$this->db->where_in('id', explode(',', $banks));
		$query	= $this->db->get('banks');
		$result	= $query->result_array();
		
		return $result;
	}

	function getBanksProgrammsBuilding($banks)
	{
		$this->db->select('*');
		$this->db->where('banned','0');
		$this->db->where_in('bank_id', explode(',', $banks));
		$query		= $this->db->get('banks_programms');
		$result		= $query->result_array();
		$programms	= array();
		
		foreach ($result as $k=>$v)
			$programms[$v['bank_id']][] = $v;

		return $programms;
	}

	function metroFilter($category, $metro, $page = 0)
	{

	
		$count = 0;

		
		switch ($category)
		{
			case 'buildings':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '0');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '0');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				break;

			case 'resale':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '1');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.price,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '1');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				break;

			case 'assignment':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '8');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.price,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '8');
				$this->db->where('buildings.banned', 0);
				$this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
				$this->db->where('metro_buildings.metro_id', $metro);
				break;
		}

		$this->db->distinct('buildings.id');
		$offset = $page;
		
		if ($offset !== 'all')
		{
			$per_paging	= 16; // количество записей на страницу
			$offset		= ($offset < 2) ? 0 : ($offset-1) * $per_paging;
			$this->db->limit($per_paging, $offset);
		}
		
		$query				= $this->db->get($this->table);
		$result['dataRows']	= $query->result_array();
		$result['countRows']= $count;

		return $result;
	}

	function rayonFilter($category, $metro, $page = 0)
	{
		$count = 0;
		
		switch ($category)
		{
			case 'buildings':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '0');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '0');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'resale':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '1');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.price,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '1');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'assignment':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '8');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.price,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '8');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'elite':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '3');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.*');
				$this->db->like('buildings.razdelu', '3');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'exclusive':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '9');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.*');
				$this->db->like('buildings.razdelu', '9');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'residential':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '2');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.*');
				$this->db->like('buildings.razdelu', '2');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;

			case 'commercial':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '4');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.*');
				$this->db->like('buildings.razdelu', '4');
				$this->db->where('buildings.banned', 0);
				$this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
				$this->db->where('rayon_buildings.rayon_id', $metro);
				break;
		}

		$this->db->distinct('buildings.id');
		$offset = $page;

		if ($offset !== 'all')
		{
			$per_paging = 16; // количество записей на страницу
			$offset = ($offset < 2) ? 0 : ($offset-1) * $per_paging;

			$this->db->limit($per_paging, $offset);
		}
		$query = $this->db->get($this->table);
		$result['dataRows'] = $query->result_array();
		$result['countRows'] = $count;

		return $result;
	}

	function resobjectFilter($category, $metro, $page = 0)
	{
		$count = 0;
		
		switch ($category)
		{
			case 'residential':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '2');
				$this->db->where('buildings.banned', 0);
				$this->db->join('residential_object', 'residential_object.id = buildings.res_type', 'left');
				$this->db->where('residential_object.id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.id,buildings.name,buildings.adress,buildings.razdelu,buildings.id_complex,buildings.map,buildings.metro_id,buildings.rayon_id,buildings.link,buildings.korpus_value,buildings.korpus,buildings.mainfoto,buildings.price,buildings.virtual_price,buildings.ipoteka');
				$this->db->like('buildings.razdelu', '2');
				$this->db->where('buildings.banned', 0);
				$this->db->join('residential_object', 'residential_object.id = buildings.res_type', 'left');
				$this->db->where('residential_object.id', $metro);
				break;
		}
		$this->db->distinct('buildings.id');
		$offset = $page;
		
		if ($offset !== 'all')
		{
			$per_paging = 16; // количество записей на страницу
			$offset = ($offset < 2) ? 0 : ($offset-1) * $per_paging;
			$this->db->limit($per_paging, $offset);
		}

		$query				= $this->db->get($this->table);
		$result['dataRows']	= $query->result_array();
		$result['countRows']= $count;

		return $result;
	}

	function elitetypeFilter($category, $metro, $page = 0)
	{
		$count = 0;

		if (isset($this->uriArray[$category]))
		{
			$uu = $this->uriArray[$category];
			$this->db->select('buildings.id');
			$this->db->like('buildings.razdelu', $uu);
			$this->db->where('buildings.banned', 0);
			$this->db->join('elite_type', 'elite_type.id = buildings.elite_type', 'left');
			$this->db->where('elite_type.id', $metro);
			$qu		= $this->db->get($this->table);
			$cnt	= $qu->result_array();
			$count	= count($cnt);
			$this->db->select('buildings.*');
			$this->db->like('buildings.razdelu', $uu);
			$this->db->where('buildings.banned', 0);
			$this->db->join('elite_type', 'elite_type.id = buildings.elite_type', 'left');
			$this->db->where('elite_type.id', $metro);
		}
		
		$this->db->distinct('buildings.id');
		$offset = $page;
		
		if ($offset !== 'all')
		{
			$per_paging = 16; // количество записей на страницу
			$offset = ($offset < 2) ? 0 : ($offset-1) * $per_paging;
			$this->db->limit($per_paging, $offset);
		}

		$query				= $this->db->get($this->table);
		$result['dataRows']	= $query->result_array();
		$result['countRows']= $count;

		return $result;
	}

	function typesdelkaFilter($category, $metro, $page = 0)
	{
		$count = 0;
		
		switch ($category)
		{
			case 'commercial':
				$this->db->select('buildings.id');
				$this->db->like('buildings.razdelu', '4');
				$this->db->where('buildings.banned', 0);
				$this->db->join('sdelka_arenda', 'sdelka_arenda.building_id = buildings.id', 'left');
				$this->db->where('sdelka_arenda.typesdelka_id', $metro);
				$qu		= $this->db->get($this->table);
				$cnt	= $qu->result_array();
				$count	= count($cnt);
				$this->db->select('buildings.*');
				$this->db->like('buildings.razdelu', '4');
				$this->db->where('buildings.banned', 0);
				$this->db->join('sdelka_arenda', 'sdelka_arenda.building_id = buildings.id', 'left');
				$this->db->where('sdelka_arenda.typesdelka_id', $metro);
				break;
		}

		$this->db->distinct('buildings.id');
		$offset = $page;
		
		if ($offset !== 'all')
		{
			$per_paging	= 16; // количество записей на страницу
			$offset		= ($offset < 2) ? 0 : ($offset-1) * $per_paging;
			$this->db->limit($per_paging, $offset);
		}
		
		$query = $this->db->get($this->table);
		$result['dataRows']	= $query->result_array();
		$result['countRows']= $count;

		return $result;
	}
}