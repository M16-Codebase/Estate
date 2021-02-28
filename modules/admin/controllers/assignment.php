<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Assignment extends MY_Admin {

	function __construct()
	{
		// конструктор
		parent::__construct();
	}

	var $add = 0;
	var $update = 0;

	function index()
	{
		$this->data['data'] = $this->load->view('assignment', $dani, true);
		$this->admin_display($this->data);
	}

	function ajax_process()
	{
		$file = $this->input->post('files');

		$xmlArr = $this->getContentToXml($file);

		if (!$xmlArr)
		{
			echo json_encode(array('type' => 'error', 'msg' => 'Неверный формат файла'));
			exit;
		}

		$data = $this->getPrepareArr($xmlArr);

		foreach ($data as $item)
		{
			$this->getBuildingId($item);
		}

		echo json_encode(array('type' => 'success', 'add' => $this->add, 'update' => $this->update));
	}

	// Формируем первичный XML массив
	function getContentToXml($filename)
	{
		if (file_exists($filename))
		{
			$xmlStr = file_get_contents($filename);
			$xml = new SimpleXMLElement($xmlStr);
			return (count($xml))?simpleXMLToArray($xml):false;
		}
		else
		{
			echo json_encode(array('type' => 'error', 'msg' => 'Файл XML не найден'));
			exit;
		}
	}

	// Формируем вторичный массив
	function getPrepareArr($xmlArr)
	{
		$data = array();

		foreach ($xmlArr as $keyN => $values)
		{
			if ($keyN == 'offer')
			{
				foreach ($values as $offer)
				{
					$arr = array();

					foreach ($offer as $key => $value)
					{
						switch ($key)
						{
							case 'creation-date':
							case 'last-update-date':
								$arr[$key] = strtotime($value);
								break;
							
							case 'location':
								foreach ($value as $k => $v)
								{
									if ($k == 'metro')
									{
										$arr[$k][] = $v;
									}
									else
									{
										if (isset($value[$k]))
											$arr[$k] = $value[$k];
									}
								}
								break;

							case 'image':
								if (isset($value['type']))
								{
									$arr['img'][$value['type']][] = $value['value'];
								}
								else
								{
									foreach ($value as $img)
										$arr['img'][isset($img['type'])?$img['type']:'other'][] = $img['value'];
								}
								break;

							case 'area':
							case 'living-space':
							case 'kitchen-space':
								$arr[$key] = (is_array($value) AND isset($value['value']))?$value['value']:$value;
								break;

							case 'room-space':
								foreach ($value as $k => $v)
								{
									if ($k === 'value')
									{
										$arr[$key][] = $v;
									}
									else if (is_array($v))
									{
										if (isset($v['value']))
											$arr[$key][] = $v['value'];
									}
								}
								break;

							case 'price':
								if (isset($value['value']))
									$arr[$key] = (int)$value['value'];
								break;

							case 'map':
								if (isset($value['lat']))
									$arr['lat'] = $value['lat'];

								if (isset($value['lng']))
									$arr['lng'] = $value['lng'];
								break;

							default:
								$arr[$key] = $value;
						}

						// $typess[$arr['building-type']] = $arr['building-type'];
						// $typess[$value['building-type']] = $value['building-type'];

						if (isset($arr[$key]) AND is_array($arr[$key]) AND count($arr[$key]) == 0) $arr[$key] = '';
					}

					if (count($arr))
						$data[] = $arr;

					/*
					pf($offer['building-name']);

					$name = $offer['building-name'];

					$query = $this->db->where(array('name' => $name, 'razdelu' => 8))->get('buildings');

					pf($this->db->last_query());
					*/
				}
			}
		}

		return $data;
	}


	function getMetroList()
	{
		foreach ($this->db->get('metro')->result_array() as $row)
		{
			$this->metro[$row['name']] = $row['id'];
		}
	}

	function getRayonList()
	{
		foreach ($this->db->get('rayon')->result_array() as $row)
		{
			$this->rayon[$row['name']] = $row['id'];
		}
	}

	// Общая для простых справочников
	function getSpravId($table = '', $name = '', $append = true)
	{
		$name = trim($name);
		if (mb_strlen($name) == 0 OR $table == '')
			return 0;

		switch ($table)
		{
			case 'type':
				$arr = array(
					'Кирпично-монолитный'   => 'Кирпич-монолит',
					'Монолитно-панельный'   => 'Монолит + панель',
					// 'Монолит' => 'Монолит',
					// '' => 'Панельный',
					// '' => 'Кирпичный',
					// '' => 'Кирпич-монолит',
					// '' => 'Деревянный',
					// '' => 'Инд. панель',
				);

				if (isset($arr[$name]))
					$name = $arr[$name];

				break;
			
			case 'otdelka':
				$arr = array(
					'под чистовую'  => 'подчистовая',
					'под ключ'      => 'с мебелью',
					'чистовая'      => 'чистовая',
					'без отделки'   => 'без отделки',
					'полная'        => 'с ремонтом',
				);

				if (isset($arr[$name]))
					$name = $arr[$name];

				break;
		}

		$query = $this->db->where('name', $name)->get($table);
		if ($query->num_rows())
		{
			$row= $query->row_array();
			$id = $row['id'];
		}
		else
		{
			if ($append)
			{
				$arr = array(
					'name'  => $name,
					'banned'=> 0,
				);

				$this->db->insert($table, $arr);

				$id = $this->db->insert_id();
			}
			else
			{
				return 0;
			}
		}

		return $id;
	}

	// Объект
	function getBuildingId($data)
	{
		$time = mktime(0, 0, 0, $data['ready-quarter']*3, 1, $data['built-year']);

		$this->getMetroList();
		$this->getRayonList();

		$buildingTable = 'buildings';
		
		$locality = '';

		if (isset($data['region']) AND $data['region'] == 'Ленинградская область')
		{
			$area       = $data['region'];
			$district   = $data['district'];
			$locality   = (isset($data['locality-name']))?$data['locality-name']:'';
		}
		else if (isset($data['locality-name']) AND $data['locality-name'] == 'Санкт-Петербург')
		{
			$area       = $data['locality-name'];
			$district   = $data['sub-locality-name'];
		}
		
		$rayonName = trim(str_replace(' район', '', $district));
		$rayonName = str_replace(' р-н', '', $rayonName);

		$rayonId = (isset($this->rayon[$rayonName]))?$this->rayon[$rayonName]:0;

		// Метро
		if (isset($data['metro']))
		{
			foreach ($data['metro'] as $metro)
			{
				$pMetroId = 0;
				$sMetroId = array();

				$metro['name'] = str_replace(' проспект', ' пр.', $metro['name']);

				if (isset($this->metro[$metro['name']]))
				{
					if ($pMetroId == 0)
					{
						$pMetroId = $this->metro[$metro['name']];
					}
					else
					{
						$sMetroId[] = $this->metro[$metro['name']];
					}
				}
			}
		}

		// Заливаем фото
		$photos     = array('foto' => array());
		$imageMain  = '';
		$imagePlan  = '';
		
		$n = 0;

		foreach ($data['img']['main'] as $img)
		{
			if ($n++ == 0)
				$imageMain = $this->uploadPhoto($img, $this->img['building']);

			$photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
		}

		$n = 0;

		foreach ($data['img']['plan'] as $img)
		{
			if ($n++ == 0)
				$imagePlan = $this->uploadPhoto($img, $this->img['building']);

			$photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
		}

		foreach ($data['img']['other'] as $img)
		{
			$photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
		}

		$data['description'] = trim($data['description']);

		// Hardcode
		$bClass = 0;
		if ($data['is-elite'] == '+') $bClass = 4;

		/*
		$arr = array(
			'razdelu'				=> 8,
			'area'					=> $area,
			'locality_name'			=> $locality,
			'metro_id'				=> $pMetroId,
			'dop_metro'				=> (count($sMetroId))?implode(',', $sMetroId):'',
			'dop_rayon'				=> '',
			'rayon_id'				=> $rayonId,
			'builder_id'			=> (is_array($data['builder']))?0:$this->getBuilderId($data['builder']),
			'adress'				=> $data['address'],
			'ipoteka'				=> 0,
			'name'					=> $data['building-name'],
			'alias_name'			=> '',
			'link'					=> toTranslitUrl('пререуступка квартиры '.$data['internal-id']),//$data['category']
			'title'					=> $data['building-name'],
			'description'			=> '',
			'keywords'				=> '',
			'banned'				=> 0,
			'type_id'				=> $this->getTypeId($data['building-type']),
			'otdelka_id'			=> $this->getOtdelkaId($data['furnish']),
			'korpus'				=> $data['built-year'],
			'korpus_value'			=> mktime(12, 0, 0, $data['ready-quarter']*3, 1, $data['built-year']),
			'rasstoyanie_metro'		=> 0,
			'map'					=> (isset($data['lat'], $data['lng']))?$data['lat'].' | '.$data['lng']:'',
			'mainfoto'				=> ($imageMain != '')?$imageMain:'/asset/img/logo-M16.png',
			'foto'					=> (count($photos['foto']))?serialize($photos):'',
			'plan_photo'			=> $imagePlan,
			'text'					=> $data['description'],
			'xml_text'				=> '',
			'bigfoto'				=> '/asset/img/logo-M16.png',
			'room_id'				=> $this->getRoomId(($data['studio'] == 1)?'Студия':$data['rooms']),
			'fz214'					=> (isset($data['deal-status']) AND $data['deal-status'] == '214 ФЗ')?1:0,
			'class'					=> ($data['is-elite'] == '+')?4:0,
			'typesdelka_id'			=> 3,   // Только переуступка ибо такой раздел
			'floors'				=> (isset($data['floors-total']))?$data['floors-total']:0,
			'rassrochka'			=> 0,
			'infrostructura_id'		=> 0,
			'date_add'				=> time(),
			'date_edit'				=> time(),
			'fz214'					=> 0,
			'parking'				=> ($data['parking'] == '+')?1:0,
			'korpus'				=> $data['built-year'],
			'korpus_value'			=> mktime(0, 0, 0, $data['ready-quarter']*3, 1, $data['built-year']),
			'price'					=> (int)$data['price'],
			'price_for_meter'		=> ($data['price'] > 0 AND $data['area'] > 0)?round($data['price'] / $data['area']):0,
			'floor'					=> (int)$data['floor'],
			'square_all'			=> $data['area'],
			'square_life'			=> (is_array($data['room-space'])?implode('+',$data['room-space']):$data['room-space']),//$data['living-space'],
			'square_cook'			=> (isset($data['kitchen-space']))?$data['kitchen-space']:'',
			'plan'					=> ($imagePlan != '')?$imagePlan:'',
			'foto'					=> (count($photos))?serialize($photos):'',
			'banned'				=> 0,
			'id_import'				=> (int)$data['internal-id'],
		);
		*/

		$arr = array();

		$arr['date_edit']			= time();
		$arr['price']				= (int)$data['price'];
		$arr['price_for_meter']		= ($data['price'] > 0 AND $data['area'] > 0)?round($data['price'] / $data['area']):0;
		$arr['banned']				= 0;





			//'foto_otdelka'        => '',
			// 'virtual_price'          => 0,
			// 'virtual_price_max'      => 0,
			// 'price_for_meter'        => 0,
			// 'user_price_for_meter'   => 0,
			// 'min_square'         => 0,
			// 'max_square'         => 0,
			// 'typeprodazha_id'        => 0,   // ?????
			// 'matherial_id'       => 0,
			// 'res_type'           => '',
			// 'class'              => '',
			// 'cottage'            => '',
			// 'presentation'       => '',
			// 'is_cottage'         => '',
			// 'plan_photo'         => '',
			// 'plan'               => '',

		/*
		pf([
			'all'   => $data['area'],
			'life'  => $data['living-space'],
			'cook'  => $data['kitchen-space'],
			'square_all'    => $arr['square_all'],
			'square_life'   => $arr['square_life'],
			'square_cook'   => $arr['square_cook'],
		]);
		*/

		foreach ($arr as $key => $value)
		{
			if (is_array($arr[$key]) AND count($arr[$key]) == 0)
				$arr[$key] = '';
		}

		// Ищем запись с таким internal_id
		$this->db->where(array('id_import' => $data['internal-id']));
		$query  = $this->db->get('buildings');
		$row    = $query->row_array();


		$add = 0;
		$update = 0;

		if ($query->num_rows() AND $row['id'] > 0)
		{
			$buildingId = $row['id'];
			$this->db->where('id_import', $row['id_import']);
			
			if ($this->db->update('buildings', $arr))
				$this->update++;
		}
		else
		{
			$arr['razdelu']				= 8;
			$arr['area']				= $area;
			$arr['locality_name']		= $locality;
			$arr['metro_id']			= $pMetroId;
			$arr['dop_metro']			= (count($sMetroId))?implode(',', $sMetroId):'';
			$arr['dop_rayon']			= '';
			$arr['rayon_id']			= $rayonId;
			$arr['builder_id']			= (is_array($data['builder']))?0:$this->getBuilderId($data['builder']);
			$arr['adress']				= $data['address'];
			$arr['ipoteka']				= 0;
			$arr['name']				= $data['building-name'];
			$arr['alias_name']			= '';
			$arr['link']				= toTranslitUrl('пререуступка квартиры '.$data['internal-id']);
			$arr['title']				= $data['building-name'];
			$arr['description']			= '';
			$arr['keywords']			= '';
			$arr['banned']				= 0;
			$arr['type_id']				= $this->getTypeId($data['building-type']);
			$arr['otdelka_id']			= $this->getOtdelkaId($data['furnish']);
			$arr['korpus']				= $data['built-year'];
			$arr['korpus_value']		= mktime(12, 0, 0, $data['ready-quarter']*3, 1, $data['built-year']);
			$arr['rasstoyanie_metro']	= 0;
			$arr['map']					= (isset($data['lat'], $data['lng']))?$data['lat'].' | '.$data['lng']:'';
			$arr['mainfoto']			= ($imageMain != '')?$imageMain:'/asset/img/logo-M16.png';
			$arr['foto']				= (count($photos['foto']))?serialize($photos):'';
			$arr['plan_photo']			= $imagePlan;
			$arr['text']				= $data['description'];
			$arr['xml_text']			= '';
			$arr['bigfoto']				= '/asset/img/logo-M16.png';
			$arr['room_id']				= $this->getRoomId(($data['studio'] == 1)?'Студия':$data['rooms']);
			$arr['fz214']				= (isset($data['deal-status']) AND $data['deal-status'] == '214 ФЗ')?1:0;
			$arr['class']				= ($data['is-elite'] == '+')?4:0;
			$arr['typesdelka_id']		= 3;   // Только переуступка ибо такой разде;
			$arr['floors']				= (isset($data['floors-total']))?$data['floors-total']:0;
			$arr['rassrochka']			= 0;
			$arr['infrostructura_id']	= 0;
			$arr['date_add']			= time();
			
			$arr['fz214']				= 0;
			$arr['parking']				= ($data['parking'] == '+')?1:0;
			$arr['korpus']				= $data['built-year'];
			$arr['korpus_value']		= mktime(0, 0, 0, $data['ready-quarter']*3, 1, $data['built-year']);
			$arr['floor']				= (int)$data['floor'];
			$arr['square_all']			= $data['area'];
			$arr['square_life']			= (is_array($data['room-space'])?implode('+',$data['room-space']):$data['room-space']);//$data['living-space'];
			$arr['square_cook']			= (isset($data['kitchen-space']))?$data['kitchen-space']:'';
			$arr['plan']				= ($imagePlan != '')?$imagePlan:'';
			$arr['foto']				= (count($photos))?serialize($photos):'';
			$arr['id_import']			= (int)$data['internal-id'];

			$this->db->insert('buildings', $arr);

			if ($this->db->insert_id() > 0)
			{
				$this->add++;
				$buildingId = $this->db->insert_id();
			}
		}

		// Обрабатываем районы
		if ($rayonId > 0)
		{
			$this->db->where(array('building_id' => $buildingId, 'rayon_id' => $rayonId));
			$query = $this->db->get('rayon_buildings');

			if ($query->num_rows() == 0)
				$this->db->insert('rayon_buildings', array('building_id' => $buildingId, 'rayon_id' => $rayonId)); 
		}

		// Обрабатываем метро
		$metro = array();
		if ($pMetroId) $metro[] = $pMetroId;
		if (count($sMetroId)) $metro = array_merge($metro, $sMetroId);

		if (count($metro))
		{
			foreach ($metro as $metroId)
			{
				$param = array('building_id' => $buildingId, 'metro_id' => $metroId);
				$this->db->where($param);
				$query = $this->db->get('metro_buildings');

				if ($query->num_rows() == 0)
					$this->db->insert('metro_buildings', $param + array('distance' => '')); 
			}
		}



		return $buildingId;

		/*
		$buildingId = $row['id'];

		if (!isset($this->buildingsUpdated[$buildingId]))
		{
			// Обновляем?
			pf($buildingId.' update');
			$this->buildingsUpdated[$buildingId] = $buildingId;
		}

		foreach ($arr as $key => $value)
		{
			if (is_array($arr[$key]) AND count($arr[$key]) == 0)
				$arr[$key] = '';
		}

		$this->db->insert('buildings', $arr);
		if ($this->db->insert_id() > 0)
		{
			$buildingId = $this->db->insert_id();
			$this->buildingsUpdated[$buildingId] = $buildingId;
			pf($buildingId. 'add');
		}
		*/
	}


	// Заливаем главное фото
	function uploadPhoto($image, $dir = false)
	{
		if (!$dir) return '';

		$path = MDPATH.'..'.$dir;

		if (!is_dir($path))
			mkdir($path);

		$ch = curl_init($image);
		$fn = md5(time().$image).'.'.array_pop(explode('.', $image));
		$fp = fopen($path.'/'.$fn, 'wb');
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);

		// echo $dir.'/'.$fn;
		return $dir.'/'.$fn;
	}

	# Справочники

	// id застройщика
	function getBuilderId($name)
	{
		$name = trim($name);

		if (mb_strlen($name) == 0) return 0;

		if (isset($this->builders[$name]))
		{
			$id = $this->builders[$name];
		}
		else
		{
			$query = $this->db->like('name', $name)->get('builders');

			if ($query->num_rows())
			{
				$row= $query->row_array();
				$id = $row['id'];
				$this->builders[$name] = $id;
			}
			else
			{
				$arr = array(
					'name'  => $name,
					'abc_id'=> 0,
					'banned'=> 0,
					'sort'  => 0,
					'image' => '',
				);

				$this->db->insert('builders', $arr);

				$id = $this->db->insert_id();
			}
		}

		return $id;
	}


	// Тип дома
	function getTypeId($name)
	{
		$name = trim($name);

		if (isset($this->type[$name]))
		{
			$id = $this->type[$name];
		}
		else
		{
			$id = $this->getSpravId('type', $name);
			$this->type[$name] = $id;
		}

		return $id;
	}

	// Тип дома
	function getRoomId($name)
	{
		$name = trim($name);

		if (isset($this->type[$name]))
		{
			$id = $this->type[$name];
		}
		else
		{
			$id = $this->getSpravId('room', $name, false);
			$this->type[$name] = $id;
		}

		return $id;
	}

	// Отделка
	function getOtdelkaId($name)
	{
		$name = trim($name);

		if (isset($this->otdelka[$name]))
		{
			$id = $this->otdelka[$name];
		}
		else
		{
			$id = $this->getSpravId('otdelka', $name);
			$this->otdelka[$name] = $id;
		}

		return $id;
	}


}