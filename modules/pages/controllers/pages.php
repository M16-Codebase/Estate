<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *
 * Модуль pages
 * Страницы
 *
 **/

class pages extends MY_Controller {
    const AR_EL = '/asset/uploads/images/buildings';
    private $builders = array();
    private $buildings = array();
    private $buildingsUpdated = array();
    private $metro = array();
    private $rayon = array();
    private $type = array();
    private $otdelka = array();
    private $balcony = array();
    private $img = array(
        'building' => self::AR_EL,
        'apartment' => self::AR_EL,
        'plan' => self::AR_EL);
    private $imgBuildingPath = '';

    function __construct() {
        // конструктор
        parent::__construct();
        
        include (MDPATH . 'pages/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];

        // загрузка языка
        $foreach = $this->load->language($this->table . '_mod', '', true);
        foreach ($foreach as $key => $l) {
            $this->lang->$key = htmlspecialchars_decode($l['name']);
        }
    }

    /** Роутер модуля */
    function _remap($method, $argument) {
        
        switch ($this->data['uri1']) {
            case 'search':
                $this->search();
                break;

            case 'favorites':
                $this->favorites();
                break;

            case 'yandex_xml':
                $this->getYandexXml();
                break;

            case 'import_assignments':
                $this->import_assignments();
                break;

            default:
                $this->view();
                break;
        }
    }

    /** Вывод страницы */
    function view() {
        
        $link = $this->data['uri1']; // сегмент ссылки
		if(strtok($_SERVER['REQUEST_URI'],'?')=='/'){
			$link='newindex';
		}
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
		
        $data = array();
        $data['rows'] = (object)array(); // переменная для хранения данных
        $data['lang'] = $this->lang; // передаем язык в переменную

        if (empty($link)) {
			
            $returnLink = $this->$model->all_data_where('router', array('key' =>
                    'default_controller'));

            if (isset($returnLink[0])) {
                $link = array_pop(explode('/', $returnLink[0]['value']));
                $this->main = true;
            }
        }
        if($link=='main'){
            $link='newindex';
        }
        $dataRow = $this->$model->getRow($link);

        if ($dataRow) // заносим даннные в массив для вида
            {
				//echo 'sdasdsadasdsadas';
            $utpModule = $this->load->module('utp'); // проверка или модуль подгружен

            if (!empty($utpModule))
                $this->addVar('utp', $utpModule->utp('pages', $dataRow['id']));

            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->short_text = $dataRow['short_text'];
        } else {
			//echo 'sdasdsadasdsadas';
			//echo 'sdasdsadasdsadas';
            show_404('404: Страница - ' . $link . ' не найдена');
			return;
        }

        // генерируем title, keywords, description
        $this->addVar('title', $dataRow['title']);
        $this->addVar('keywords', $dataRow['keywords']);
        $this->addVar('description', $dataRow['description']);

        // задаем крохи
        $this->breadcrumbs($dataRow['name']);

        // переменная шаблона
        // если страница является главной
        if ($this->main) {
            
            $site_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
            $size = getimagesize($site_url . '/asset/assets/img/m16-logo.png');
            $w = $size[0];
            $h = $size[1];
    
            $og['title'] = $dataRow['title'];
            $og['description'] = $dataRow['description'];
            $og['image'] = '/asset/assets/img/m16-logo.png';
            $og['width'] = $w;
            $og['height'] = $h;
    
            $this->addVar('OG', $og);
            $this->data['template'] = $this->render('pages/main', $data);
            
        } elseif ($link == 'maint') {// если страница является главной
            $this->data['template'] = $this->render('pages/maint', $data);
        } else {
            $viewPage = 'view';

            if (file_exists(APPPATH . 'views/themes/' . $this->defaultTheme . '/pages/' . $link .
                '.php')) {
                $viewPage = $link;
            }
            $data['pageHeader'] = $dataRow['title'];
            $this->data['template'] = $this->render('pages/' . $viewPage, $data);
        }

        // выводим весь вид

        $this->viewPage($this->data);
    }

    // Поиск
    function search() {
        if ($this->input->post('search', true)) {
            redirect('/search/' . urlencode($this->input->post('search', true)));
        }

        $search = urldecode($this->data['uri2']); // сегмент ссылки
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang; // передаем язык в переменную
        $data['rows']->header = 'Поиск';

        $searchPages = $this->$model->searchs($search, 'pages');
        $searchUslugi = $this->$model->searchs($search, 'uslugi');

        $data['searching'] = '';

        if (!empty($searchPages)) {
            foreach ($searchPages as $s) {
                $link = $s['link'];

                if ($s['link'] == 'main') {
                    $link = '';
                }

                $data['searching'] .= "<li><a target=\"_balnk\" href=\"\\{$link}\">{$s['name']}</a></li>";
            }
        }

        if (!empty($searchUslugi)) {
            foreach ($searchUslugi as $s) {
                $data['searching'] .= "<li><a target=\"_balnk\" href=\"\\uslugi\\{$s['link']}\">{$s['name']}</a></li>";
            }
        }

        // генерируем title, keywords, description
        $this->addVar('title', 'Поиск');
        $this->addVar('keywords', '');
        $this->addVar('description', '');

        // задаем крохи
        $this->breadcrumbs($dataRow['name']);

        // переменная шаблона
        $this->data['template'] = $this->render('search', $data);

        // выводим весь вид
        $this->viewPage($this->data);
    }

    /*
    Move to assignment model
    */
    // Обновление переуступок
    function import_assignments() {

        // $q = $this->db->query('SELECT * FROM `elite_type` LIMIT 1');
        // pf($q->row_array());exit;
        // echo $this->db->query("ALTER TABLE `ci_buildings` ADD `id_import` INT NOT NULL DEFAULT '0' AFTER `parser_link`, ADD INDEX (`id_import`) ;"); exit;
        // echo $this->db->query("ALTER TABLE `ci_buildings` CHANGE `square_life` `square_life` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Площадь жилья';"); exit;

        $filename = 'new_slim.xml';
        // $filename = 'new.xml';

        $xmlArr = $this->getContentToXml($filename);

        if (!$xmlArr)
            die('Soryan, Errors');

        $data = $this->getPrepareArr($xmlArr);

        // $type = array();
        // pf($data); exit;

        foreach ($data as $item) {
            $this->getBuildingId($item);
            // $this->sizeForMYAss($item);
            // $type[$item['building-type']] = $item['building-type'];
            // pf($item);
        }

        // pf($type);

        /*
        if (count($xmlArr))
        {
        // Помечаем все объекты и квартиры как заблокированные
        $this->db->update('assignment_buildings', array('banned' => 1)); 
        $this->db->update('assignment_apartaments', array('banned' => 1)); 
        }
        */
    }

    /*
    function sizeForMYAss($item)
    {
    $arr = [
    'area'			=> $item['area'],
    'living-space'	=> $item['living-space'],
    'kitchen-space'	=> $item['kitchen-space'],
    'room-space'	=> $item['room-space'],
    ];

    pf($arr);

    echo '<hr />';
    }
    */

    // Формируем первичный XML массив
    function getContentToXml($filename) {
        if (file_exists($filename)) {
            $xmlStr = file_get_contents($filename);
            $xml = new SimpleXMLElement($xmlStr);
            return (count($xml)) ? simpleXMLToArray($xml) : false;
        }
    }

    // Формируем вторичный массив
    function getPrepareArr($xmlArr) {
        $data = array();

        foreach ($xmlArr as $keyN => $values) {
            if ($keyN == 'offer') {
                foreach ($values as $offer) {
                    $arr = array();

                    foreach ($offer as $key => $value) {
                        switch ($key) {
                            case 'creation-date':
                            case 'last-update-date':
                                $arr[$key] = strtotime($value);
                                break;

                            case 'location':
                                foreach ($value as $k => $v) {
                                    if ($k == 'metro') {
                                        $arr[$k][] = $v;
                                    } else {
                                        if (isset($value[$k]))
                                            $arr[$k] = $value[$k];
                                    }
                                }
                                break;

                            case 'image':
                                if (isset($value['type'])) {
                                    $arr['img'][$value['type']][] = $value['value'];
                                } else {
                                    foreach ($value as $img)
                                        $arr['img'][isset($img['type']) ? $img['type'] : 'other'][] = $img['value'];
                                }
                                break;

                            case 'area':
                            case 'living-space':
                            case 'kitchen-space':
                                $arr[$key] = (is_array($value) and isset($value['value'])) ? $value['value'] : $value;
                                break;

                            case 'room-space':
                                // echo '<hr />';
                                // pf($value);

                                foreach ($value as $k => $v) {
                                    if ($k === 'value') {
                                        $arr[$key][] = $v;
                                    } else
                                        if (is_array($v)) {
                                            if (isset($v['value']))
                                                $arr[$key][] = $v['value'];
                                        }
                                }

                                // pf($arr[$key]);

                                // echo (is_array($arr[$key]))?implode('+', $arr[$key]):$arr[$key];

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

                        if (isset($arr[$key]) and is_array($arr[$key]) and count($arr[$key]) == 0)
                            $arr[$key] = '';
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

    function getMetroList() {
        foreach ($this->db->get('metro')->result_array() as $row) {
            $this->metro[$row['name']] = $row['id'];
        }
    }

    function getRayonList() {
        foreach ($this->db->get('rayon')->result_array() as $row) {
            $this->rayon[$row['name']] = $row['id'];
        }
    }

    // Общая для простых справочников
    function getSpravId($table = '', $name = '', $append = true) {
        $name = trim($name);
        if (mb_strlen($name) == 0 or $table == '')
            return 0;

        switch ($table) {
            case 'type':
                $arr = array(
                    'Кирпично-монолитный' => 'Кирпич-монолит',
                    'Монолитно-панельный' => 'Монолит + панель',
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
                    'под чистовую' => 'подчистовая',
                    'под ключ' => 'с мебелью',
                    'чистовая' => 'чистовая',
                    'без отделки' => 'без отделки',
                    'полная' => 'с ремонтом',
                    );

                if (isset($arr[$name]))
                    $name = $arr[$name];

                break;
        }

        $query = $this->db->where('name', $name)->get($table);
        if ($query->num_rows()) {
            $row = $query->row_array();
            $id = $row['id'];
        } else {
            if ($append) {
                $arr = array(
                    'name' => $name,
                    'banned' => 0,
                    );

                $this->db->insert($table, $arr);

                $id = $this->db->insert_id();
            } else {
                return 0;
            }
        }

        return $id;
    }

    // Объект
    function getBuildingId($data) {
        $time = mktime(0, 0, 0, $data['ready-quarter'] * 3, 1, $data['built-year']);

        $this->getMetroList();
        $this->getRayonList();

        $buildingTable = 'buildings';

        $locality = '';

        if (isset($data['region']) and $data['region'] == 'Ленинградская область') {
            $area = $data['region'];
            $district = $data['district'];
            $locality = (isset($data['locality-name'])) ? $data['locality-name'] : '';
        } else
            if (isset($data['locality-name']) and $data['locality-name'] ==
                'Санкт-Петербург') {
                $area = $data['locality-name'];
                $district = $data['sub-locality-name'];
            }

        $rayonName = trim(str_replace(' район', '', $district));
        $rayonName = str_replace(' р-н', '', $rayonName);

        $rayonId = (isset($this->rayon[$rayonName])) ? $this->rayon[$rayonName] : 0;

        // Метро
        if (isset($data['metro'])) {
            foreach ($data['metro'] as $metro) {
                $pMetroId = 0;
                $sMetroId = array();

                $metro['name'] = str_replace(' проспект', ' пр.', $metro['name']);

                if (isset($this->metro[$metro['name']])) {
                    if ($pMetroId == 0) {
                        $pMetroId = $this->metro[$metro['name']];
                    } else {
                        $sMetroId[] = $this->metro[$metro['name']];
                    }
                }
            }
        }

        // Заливаем фото
        $photos = array('foto' => array());
        $imageMain = '';
        $imagePlan = '';

        $n = 0;

        foreach ($data['img']['main'] as $img) {
            if ($n++ == 0)
                $imageMain = $this->uploadPhoto($img, $this->img['building']);

            $photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
        }

        $n = 0;

        foreach ($data['img']['plan'] as $img) {
            if ($n++ == 0)
                $imagePlan = $this->uploadPhoto($img, $this->img['building']);

            $photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
        }

        foreach ($data['img']['other'] as $img) {
            $photos['foto'][] = $this->uploadPhoto($img, $this->img['building']);
        }

        $data['description'] = trim($data['description']);

        // Hardcode

        $bClass = 0;

        if ($data['is-elite'] == '+')
            $bClass = 4;

        $arr = array(
            'razdelu' => 8,
            'area' => $area,
            'locality_name' => $locality,
            'metro_id' => $pMetroId,
            'dop_metro' => (count($sMetroId)) ? implode(',', $sMetroId) : '',
            'dop_rayon' => '',
            'rayon_id' => $rayonId,
            'builder_id' => (is_array($data['builder'])) ? 0 : $this->getBuilderId($data['builder']),
            'adress' => $data['address'],
            'ipoteka' => 0,
            'name' => $data['building-name'],
            'alias_name' => '',
            'link' => toTranslitUrl('пререуступка квартиры ' . $data['internal-id']), //$data['category']
            'title' => $data['building-name'],
            'description' => '',
            'keywords' => '',
            'banned' => 0,
            'type_id' => $this->getTypeId($data['building-type']),
            'otdelka_id' => $this->getOtdelkaId($data['furnish']),
            'korpus' => $data['built-year'],
            'korpus_value' => mktime(12, 0, 0, $data['ready-quarter'] * 3, 1, $data['built-year']),
            'rasstoyanie_metro' => 0,
            'map' => (isset($data['lat'], $data['lng'])) ? $data['lat'] . ' | ' . $data['lng'] :
                '',
            'mainfoto' => ($imageMain != '') ? $imageMain : '/asset/img/logo-M16.png',
            'foto' => (count($photos['foto'])) ? serialize($photos) : '',
            'plan_photo' => $imagePlan,
            //'foto_otdelka'		=> '',
            'text' => $data['description'],
            'xml_text' => '',
            'bigfoto' => '/asset/img/logo-M16.png',
            'room_id' => $this->getRoomId(($data['studio'] == 1) ? 'Студия' : $data['rooms']),
            'fz214' => (isset($data['deal-status']) and $data['deal-status'] == '214 ФЗ') ?
                1 : 0,
            'class' => ($data['is-elite'] == '+') ? 4 : 0,
            // 'virtual_price'			=> 0,
            // 'virtual_price_max'		=> 0,
            // 'price_for_meter'		=> 0,
            // 'user_price_for_meter'	=> 0,
            // 'min_square'			=> 0,
            // 'max_square'			=> 0,
            // 'typeprodazha_id'		=> 0,	// ?????
            'typesdelka_id' => 3, // Только переуступка ибо такой раздел
            'floors' => (isset($data['floors-total'])) ? $data['floors-total'] : 0,
            'rassrochka' => 0,
            // 'matherial_id'		=> 0,
            'infrostructura_id' => 0,
            // 'res_type'			=> '',
            'date_add' => time(),
            'date_edit' => time(),
            'fz214' => 0,
            'parking' => ($data['parking'] == '+') ? 1 : 0,
            // 'class'				=> '',
            // 'cottage'			=> '',
            // 'presentation'		=> '',
            // 'is_cottage'			=> '',
            // 'plan_photo'			=> '',
            // 'plan'				=> '',
            'korpus' => $data['built-year'],
            'korpus_value' => mktime(0, 0, 0, $data['ready-quarter'] * 3, 1, $data['built-year']),
            'price' => (int)$data['price'],
            'price_for_meter' => ($data['price'] > 0 and $data['area'] > 0) ? round($data['price'] /
                $data['area']) : 0,
            'floor' => (int)$data['floor'],
            'square_all' => $data['area'],
            'square_life' => (is_array($data['room-space']) ? implode('+', $data['room-space']) :
                $data['room-space']), //$data['living-space'],
            'square_cook' => (isset($data['kitchen-space'])) ? $data['kitchen-space'] : '',
            'plan' => ($imagePlan != '') ? $imagePlan : '',
            'foto' => (count($photos)) ? serialize($photos) : '',
            'banned' => 0,
            'id_import' => (int)$data['internal-id'],
            );

        /*
        pf([
        'all'	=> $data['area'],
        'life'	=> $data['living-space'],
        'cook'	=> $data['kitchen-space'],
        'square_all'	=> $arr['square_all'],
        'square_life'	=> $arr['square_life'],
        'square_cook'	=> $arr['square_cook'],
        ]);
        */

        foreach ($arr as $key => $value) {
            if (is_array($arr[$key]) and count($arr[$key]) == 0)
                $arr[$key] = '';
        }

        // Ищем запись с таким internal_id
        $this->db->where(array('id_import' => $data['internal-id']));
        $query = $this->db->get('buildings');
        $row = $query->row_array();

        if ($query->num_rows() and $row['id'] > 0) {
            $buildingId = $row['id'];
            $this->db->where('id_import', $row['id_import']);
            $this->db->update('buildings', $arr);

            pf($buildingId . 'update');
        } else {
            $this->db->insert('buildings', $arr);

            if ($this->db->insert_id() > 0) {
                $buildingId = $this->db->insert_id();
                $this->buildingsUpdated[$buildingId] = $buildingId;
                pf($buildingId . 'add');
            }
        }

        // Обрабатываем районы
        if ($rayonId > 0) {
            $this->db->where(array('building_id' => $buildingId, 'rayon_id' => $rayonId));
            $query = $this->db->get('rayon_buildings');

            if ($query->num_rows() == 0)
                $this->db->insert('rayon_buildings', array('building_id' => $buildingId,
                        'rayon_id' => $rayonId));
        }

        // Обрабатываем метро
        $metro = array();
        if ($pMetroId)
            $metro[] = $pMetroId;
        if (count($sMetroId))
            $metro = array_merge($metro, $sMetroId);

        if (count($metro)) {
            foreach ($metro as $metroId) {
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
    function uploadPhoto($image, $dir = false) {
        if (!$dir)
            return '';

        $path = MDPATH . '..' . $dir;

        if (!is_dir($path))
            mkdir($path);

        $ch = curl_init($image);
        $fn = md5(time() . $image) . '.' . array_pop(explode('.', $image));
        $fp = fopen($path . '/' . $fn, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);

        // echo $dir.'/'.$fn;
        return $dir . '/' . $fn;
    }

    # Справочники

    // id застройщика
    function getBuilderId($name) {
        $name = trim($name);

        if (mb_strlen($name) == 0)
            return 0;

        if (isset($this->builders[$name])) {
            $id = $this->builders[$name];
        } else {
            $query = $this->db->like('name', $name)->get('builders');

            if ($query->num_rows()) {
                $row = $query->row_array();
                $id = $row['id'];
                $this->builders[$name] = $id;
            } else {
                $arr = array(
                    'name' => $name,
                    'abc_id' => 0,
                    'banned' => 0,
                    'sort' => 0,
                    'image' => '',
                    );

                $this->db->insert('builders', $arr);

                $id = $this->db->insert_id();
            }
        }

        return $id;
    }

    // Тип дома
    function getTypeId($name) {
        $name = trim($name);

        if (isset($this->type[$name])) {
            $id = $this->type[$name];
        } else {
            $id = $this->getSpravId('type', $name);
            $this->type[$name] = $id;
        }

        return $id;
    }

    // Тип дома
    function getRoomId($name) {
        $name = trim($name);

        if (isset($this->type[$name])) {
            $id = $this->type[$name];
        } else {
            $id = $this->getSpravId('room', $name, false);
            $this->type[$name] = $id;
        }

        return $id;
    }

    // Отделка
    function getOtdelkaId($name) {
        $name = trim($name);

        if (isset($this->otdelka[$name])) {
            $id = $this->otdelka[$name];
        } else {
            $id = $this->getSpravId('otdelka', $name);
            $this->otdelka[$name] = $id;
        }

        return $id;
    }
}
/* End of file */
