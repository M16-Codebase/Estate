<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



/**
 * Модуль: Каталог недвижимости
 **/

 /**
  *
  * Изменил порядок работы
  * Чтобы не было 404 ошибок
  * Например, если приходит урл /exclusive/centralnyj/
  * то сначала remap вызывает метод View потому что он думает, что это одиночная запись.
  * В этом методе вытаскиваются данные из БД объектов по ключу "centralnyj", которая может не найтись
  * Если записи нет, то вызывается метод rayonPage, в котором происходит попытка
  * вытащить запись с помощью ключа, и если она есть, то выводятся объекты района
  * если записи нет, то вызывается метод metroPage, где происходит тоже самое, только примениельно к метро
  * Если записи нет, то оттуда вызыввптся ошибка 404
  *
  *
  * */

class buildings extends MY_Controller {
    private $conf; // конфиг файл
    private $lang; // языковый файл

    private $sectors = array(
        'buildings' => 0,
        'resale' => 1,
        'assignment' => 8,
        'residential' => 2,
        'elite' => 3,
        'exclusive' => 9,
        'commercial' => 4,
        'land' => 6,
        'world' => 5);


    /**
     * в методе view присваивается массив типа id => name
     * переменная нужна для формирования микроразметки
     * @var array
     */
    private $districtsnameArray;

    function __construct() {
        // конструктор
        parent::__construct();
        $this->load->library('session');
        include (MDPATH . 'buildings/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];

        // определяем, нужно ли использовать роутер
        if (!empty($moduleinfo['router'])) {
            $this->link = $moduleinfo['router'];
        } else {
            $this->link = $this->module;
        }

        $this->link = $this->data['uri1'];
    }

    /** Роутер модуля */
    function _remap($method, $argument) {

        // проверка на доступность модуля
        if (isset($moduleinfo['status']) != 0)
            return false;

        $u = (isset($argument[0])) ? $argument[0] : 0; // сегмент ссылки
		
        $maybe_actions = array(
            'metro',
            'rayon',
            'residential_object',
            'elite_type',
            'typesdelka'
        );
		if (strpos($_SERVER['REQUEST_URI'], '/0')) {
			show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
			return;
		}
        //echo 'dsdsada';
        switch (uri(2)) {
			//echo 'dsdsada';
            case 'search':
                $this->searchFunction($u);
                break;

            case 'map':
                $this->showMap();
                break;

            case 'countFind':
                $this->countFind();
                break;

            case 'sort':
                $this->sortings();
                break;

            case 'filt':
                $this->filtering();
                break;

            default:
                // если существует метод, то запускаем его
                if (method_exists($this, $method)) {
                    switch ($method) {
                        case 'index':
						    //echo $method;
                            $this->index();
                            break;

                        case 'limit':
                            $this->limit($u);
                            break;

                        case 'search':
                            $this->searchFunction($u);
                            break;

                        default:
                            show_404('Метода не существует: ' . $this->uri->uri_string());
                    }
                } else {
                    if (in_array($method, $maybe_actions)) {
                        $page = 0;

                        if (isset($argument[1])) {
                            $ur = parse_url($argument[1]);
                            parse_str($ur['query'], $param);

                            if (isset($param['page']))
                                $page = $param['page'];
                        }
                        
                        switch ($method) {
                            case 'metro':
                                $this->metroPage($u, $page);
                                break;

                            case 'rayon':
                                $this->rayonPage($u, $page);
                                break;

                            case 'residential_object':
                                $this->resobjectPage($u, $page);
                                break;

                            case 'elite_type':
                                $this->elitetypePage($u, $page);
                                break;

                            case 'typesdelka':
                                $this->typesdelkaPage($u, $page);
                                break;
                        }
                    } else {
                        if ( 
                            !is_bool(strpos($_SERVER['REQUEST_URI'], '/buildings/'))
                            && strpos($_SERVER['REQUEST_URI'], '/amp')
                            && array_count_values(explode('/', $_SERVER['REQUEST_URI']))['amp'] < 2
                            //&& ($_SERVER['REMOTE_ADDR'] == '46.47.225.222'
                            //&& $_SERVER['REMOTE_ADDR'] == '78.37.56.52')
                        ) {
							$this->view(true);
						} else {
							if (!empty($argument)) {
								show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
								return;
							}
							if (is_numeric($method)) {
								// если идет пагинация
								$this->index($method);
							} else {
                                // иначе просмотр конкретной записи
								// последний шанс: делим на _ и проверяем есть ли слово район или метро
								// относимся строго: если
								//$pts = explode('_', $method);
								$this->view();
							}
						}
                    }
                }
        }
    }

	function AmpPage($data){
		//echo'<pre>';print_r($data);echo'</pre>';
		$page=array();
		$page['canonical']='https://m16-estate.ru/buildings/'.$data['link'];
		$page['mainlink']=$data['link'];
		$page['favicon']='/favicon.ico';
		$page['title']=$data['title'];
		$page['name']=$data['name'];
		$page['description']=$data['description'];
		$price = $this->prices($data['id']);
		$page['lprice']=$price['min'];
		$page['hprice']=$price['max'];
		$page['link']=$page['canonical'];
		$page['ratingg']=$data['rating'];
		$page['rating']=(int)($data['rating']/$data['raters']);
		$page['raters']=$data['raters'];
		$page['h1']='Жилой комплекс «'.$data['name'].'»';
		$page['fprice']=round($page['lprice'], 1);
		$page['carousel']=unserialize($data['foto'])['foto'];
		$page['srok']=$this->dataKv($data['korpus_value']);
		$page['adress']=$data['adress'];
		
		$this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $idm[$p['id']] = $p['name'];
			
		$page['metro']=$idm[$data['metro_id']];
		
		$this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $idr[$p['id']] = $p['name'];

		$page['rayon']=$idr[$data['rayon_id']];
		
		$this->load->model('builders/admin_builders_model', 'admin_builders_model');
        $builders = $this->admin_builders_model->module_get($data['builder_id']);
				
		$page['builder']=$builders['name'];
		
		$page['opisanie']=$data['text'];
		$page['otdelka']=$data['otdelka'];
		$page['infra']=$data['infrastruct'];
		$page['transport']=$data['transport'];
		$page['ipoteka']=$data['ipoteka_text'];
		
		$same=$this->prepSame($data['id'],$price['min'])[0];
		$page['near']=array();
		//echo'<pre>';print_r($same);echo'</pre>';
		//exit;
		foreach($same as $key=>$value){
			if($key>10){
				break;
			}
			if($value['link']==$data['link']){
			    continue;
            }
			$page['near'][$key]['img']=$value['mainfoto'];
            $page['near'][$key]['name']=$value['name'];
            $page['near'][$key]['link']='https://m16-estate.ru/buildings/'.$value['link'].'/amp';
		}
		$aps = $this->room_complex($data['id']);
		foreach($aps as $key=>$value){
			$apa[$value['room_id']][$key]['square']=$value['square_all'];
			$apa[$value['room_id']][$key]['price']=$value['price'];
			$apa[$value['room_id']][$key]['img']=$value['main_foto'];
		}
		
		$this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $pri[$p['id']] = $p['name'];
		foreach(array_keys($apa) as $key=>$val){
			$page['plan'][$key]['title']=$pri[$val];
			$kiy=0;
			foreach($apa[$val] as $val){
				if($kiy>3){
					break;
				}
				$page['plan'][$key]['content'][]=$val;
				$kiy++;
			}
		}
		error_reporting( E_ALL );
		//echo'<pre>';print_r($page);echo'</pre>';
		include('/var/www/estate/data/www/m16-estate.ru/releases/20151208131140/modules/buildings/views/amp-buildings/amp.php');
		
	}
	
	function room_complex($id)
	{
		$this->db->where('novostroy_id', $id);
		$this->db->where('banned','0');
		$this->db->order_by('price', 'ASC');
		$query = $this->db->get('apartments');

		return $query->result_array();
	}
	
	function prepSame($label,$prc){
		$price=$prc;
		if($price<3000000){
			$dataRows[]=$this->sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`<3000000 AND `link`!='$label'");
		}elseif($price<5000000){
			$dataRows[]=$this->sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>3000000 AND `price`<5000000 AND `link`!='$label'");
		}elseif($price<15000000){
			$dataRows[]=$this->sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>5000000 AND `price`<15000000 AND `link`!='$label'");
		}else{
			$dataRows[]=$this->sqlGet('ci_buildings','`name`,`link`,`price`,`mainfoto`',"`price`>15000000 AND `link`!='$label'");
		}
		
		return $dataRows;
	}
	function sqlGet($table,$subject,$where){
		$mysqli = new mysqli('localhost', 'ned', '5M2i1R0q', 'ned');
		if ($mysqli->connect_error) {
			die('Ошибка подключения (' . $mysqli->connect_errno . ') '
					. $mysqli->connect_error);
		}
		
		$mysqli->query("SET NAMES 'utf8mb4'");
		$result=array();
		//echo("SELECT ".$subject." FROM ".$table." WHERE ".$where);
		//echo'<br>';
		if ($data = $mysqli->query("SELECT ".$subject." FROM ".$table." WHERE ".$where." LIMIT 70")) {
			if (($data->num_rows)==1){
				$row = $data->fetch_array(MYSQLI_ASSOC);
				$result[]=$row;
				return $result;
			}elseif(($data->num_rows)==0){
				return null;
			}else{
				while ($row = $data->fetch_assoc()){
					array_push($result,$row);
				}
				return $result;
			}
		}else{
			$result[]=$mysqli->error;
			return $result;
		}
		$data->close();
		$mysqli->close();
	}
	function prices($id){
		$this->db->select('MIN(price) as min, MAX(price) as max');
		$this->db->where('banned','0');
		$this->db->where('price > 500000');
		$this->db->where('novostroy_id', $id);
		$query = $this->db->get('apartments');
		$result = $query->result_array();
		return $result[0];
	}

    // Сортировка
    function sortings() {
        $_SESSION[$this->data['uri1']] = uri(3);
        redirect($this->data['uri1']);
    }

    // Выбор категории земельного участка
    function filtering() {
        $_SESSION[$this->data['uri1']] = uri(3);
        redirect($this->data['uri1']);
    }

    /** Опции модуля */
    function setOptions() {
        // загрузка настроек
        $this->conf = $this->load->config($this->module . '/' . $this->module, true);

        // загрузка языка
        $foreach = $this->load->language($this->table . '_mod', '', true);
        foreach ($foreach as $key => $l) {
            $this->lang = (object)array();
            $this->lang->$key = htmlspecialchars_decode($l['name']);
        }

        // выводить или не выводить хлебные крошки для модуля
        $this->noBreadcrumbs = $this->conf['breadcrumbs'];
        /*
        $sessionArray = array(
        'buildings' => 'price',
        'resale' => 'price',
        'residential' => 'sort',
        'elite' => 'price',
        'commercial' => 'name',
        'world' => 'sort'
        );

        if($_SESSION['buildings'] == 'date') { unset($_SESSION['buildings']); }

        if(empty($_SESSION[$this->data['uri1']]) and $this->data['uri1'] != 'land')
        {
        $_SESSION[$this->data['uri1']] = $sessionArray[$this->data['uri1']];

        }*/
    }

    /** Главная */
    function index($offset = 0) {

        $this->setOptions(); // заносим опции в переменные
        $uri = $this->data['uri1'];
		//echo $uri;
        $shown = array(
            'plitter_buildings' => 'plit',
            'plitter_resale' => 'plit',
            'plitter_assignment' => 'plit',
            'plitter_elite' => 'plit',
            'plitter_exclusive' => 'plit',
            'plitter_commercial' => 'plit',
            'plitter_residential' => 'plit',
            'plitter_land' => 'plit',
            );

        if (isset($_POST['chplit']) and in_array($uri, array(
            'buildings',
            'resale',
            'assignment',
            'elite',
            'exclusive',
            'commercial',
            'residential',
            'land')))
            $this->session->set_userdata('plitter_' . $uri, $_POST['chplit']);

        if ($this->session->userdata('plitter_buildings'))
            $shown['plitter_buildings'] = $this->session->userdata('plitter_buildings');

        if ($this->session->userdata('plitter_resale'))
            $shown['plitter_resale'] = $this->session->userdata('plitter_resale');

        if ($this->session->userdata('plitter_assignment'))
            $shown['plitter_assignment'] = $this->session->userdata('plitter_assignment');

        if ($this->session->userdata('plitter_elite'))
            $shown['plitter_elite'] = $this->session->userdata('plitter_elite');

        if ($this->session->userdata('plitter_exclusive'))
            $shown['plitter_exclusive'] = $this->session->userdata('plitter_exclusive');

        if ($this->session->userdata('plitter_commercial'))
            $shown['plitter_commercial'] = $this->session->userdata('plitter_commercial');

        if ($this->session->userdata('plitter_residential'))
            $shown['plitter_residential'] = $this->session->userdata('plitter_residential');

        if ($this->session->userdata('plitter_land'))
            $shown['plitter_land'] = $this->session->userdata('plitter_land');

        // Сортировка по названию
        if (isset($_POST['sortname']))
            $this->session->set_userdata('sort_name_buildings', $_POST['sortname']);

        // Сортировка по цене
        if (isset($_POST['sortprice']) and in_array($uri, array(
            'buildings',
            'resale',
            'assignment',
            'elite',
            'exclusive',
            'commercial',
            'residential')))
            $this->session->set_userdata('sort_price_' . $uri, $_POST['sortprice']);

        // Метро
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        // Район
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        // Тип
        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        // Комнатность
        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $parent_id_room[$p['id']] = $p['name'];

        $data['d_room'] = $parent_id_room;
        $data['d_type'] = $parent_idtype;
        $data['d_rayon'] = $parent_idrayon;
        $data['d_metro'] = $parent_idmetro;

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;
        $data['shown'] = $shown;

        $url = parse_url($_SERVER['REQUEST_URI']);
		//echo '<pre>';print_r($url);echo '</pre>';
        parse_str(isset($url['query']), $params);
        if (!empty($params)) {
            if (isset($params['plit']))
                unset($params['plit']);
            $get = $url['query'];
        }
		

        $data['params'] = $params;
        if (!empty($get)) {
            parse_str($get, $paag);
            //unset($paag['page']);
            //$paag['page'] = 'all';
            $data['show_all'] = http_build_query($paag);
        } else {
            $data['show_all'] = 'page=all';
        }

        $params = $paag;

        if ($uri == 'buildings') {
            $minMax = $this->$model->getMinMaxBuildings();
            $minMax = $this->getMinMaxValuesBuildings($minMax);
            $minMaxS = $this->$model->getMinMaxBuildingsSquare();
            $minMaxS = $this->getMinMaxValuesBuildingsS($minMaxS);

            if (!empty($params['price_from']) && $minMax['min'] == $params['price_from'])
                $params['price_from'] = 0;

            if (!empty($params['price_to']) && $minMax['max'] == $params['price_to'])
                $params['price_to'] = 0;

            if (!empty($params['square_from']) && $minMaxS['min'] == $params['square_from'])
                $params['square_from'] = 0;

            if (!empty($params['square_to']) && $minMaxS['max'] == $params['square_to'])
                $params['square_to'] = 0;
        }

        if (isset($params['page'])) {
            $offset = $params['page'];
        }

        if (empty($offset)) {
            $offset = 0;
        }

        // $this->benchmark->mark('code_start');
		//echo'<pre>';print_r($params);echo'</pre>';
        $dataRow = $this->$model->paginations($offset, $uri, $params); // вытаскиваем данные

        $this->load->model('seometa/seo_meta_model', 'seo_meta_model');
        $seo = $this->seo_meta_model->getByLink($uri);
        if (!empty($seo)) {
            $this->addVar('title', $seo['title']);
            $this->addVar('keywords', $seo['keywords']);
            $this->addVar('description', $seo['description']);
            $data['lang']->md_title = $seo['name'];
        }
//        dump('ldfgkldfglkdfglj');
        if ($uri == 'land') {
            $this->load->model('ruchastok/admin_ruchastok_model', 'admin_ruchastok_model');
            $parentruchastok = $this->admin_ruchastok_model->parent_id();
            $parent_idruchastok['0'] = ' -- не выбрано -- ';

            foreach ($parentruchastok as $p) {
                $parent_idruchastok[$p['id']] = array('name' => $p['name'], 'mainfoto' => $p['mainfoto']);
            }

            $data['parent_idruchastok'] = $parent_idruchastok;
        }

        $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach ($parenttypesdelka as $p)
            $parent_idtypesdelka[$p['id']] = $p['name'];

        $data['sdelka'] = $parent_idtypesdelka;

        // Инфроструктура
        $this->load->model('infrostructura/admin_infrostructura_model',
            'admin_infrostructura_model');
        $parentinfrostructura = $this->admin_infrostructura_model->parent_id();
        $parent_idinfrostructura['0'] = ' -- не выбрано -- ';

        foreach ($parentinfrostructura as $p)
            $parent_idinfrostructura[$p['id']] = $p['name'];

        // Материал
        $this->load->model('matherial/admin_matherial_model', 'admin_matherial_model');
        $parentmatherial = $this->admin_matherial_model->parent_id();
        $parent_idmatherial['0'] = ' -- не выбрано -- ';
        foreach ($parentmatherial as $p)
            $parent_idmatherial[$p['id']] = $p['name'];

        // Водоем
        $this->load->model('vodoem/admin_vodoem_model', 'admin_vodoem_model');
        $parentvodoem = $this->admin_vodoem_model->parent_id();
        $parent_idvodoem['0'] = ' -- не выбрано -- ';
        foreach ($parentvodoem as $p)
            $parent_idvodoem[$p['id']] = $p['name'];

        $data['vodoem'] = $parent_idvodoem;
        $data['infrostructura'] = $parent_idinfrostructura;
        $data['matherial'] = $parent_idmatherial;
        $data['empty'] = false;

		//echo $uri;
        if (empty($dataRow)) {
			//echo $uri;
            $dataRow = $this->$model->paginations($offset, $uri, null);
            $params = null;
            $data['empty'] = true;
        }
        // проверяем есть ли данные
        if ($dataRow) {
			//echo $method;
            $uriArray = array(
                'buildings' => 0,
                'resale' => 1,
                'assignment' => 8,
                'residential' => 2,
                'elite' => 3,
                'exclusive' => 9,
                'commercial' => 4,
                'land' => 6,
                'world' => 5);

            $data['conf']['Paging'] = 1;

            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_counts(false, '', array('razdelu' => $uriArray[$uri]),
                    $params, ''),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module,
                'uri_segment' => 2,
                'num_links' => 2,
                'get' => isset($get) ? $get : '',
                'uri' => $uri,
                );

            $data['pagination'] = $this->paginations($arrayPagination);

            if (uri(2) == 999)
                $data['pagination'] = '';

            $data['rows'] = new stdClass;
            $mr = new StdClass;
			
			//НАЧАЛО НЕЙМ
            switch ($uri) {
                case 'buildings':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Новостройки';
                    $data['rows'] = (object)array();

                    foreach ($dataRow as $key => $value) {
                        $kr = ($value['korpus'] == '2') ? 'Собственность' : $this->dataKv($value['korpus_value']);

                        $key = $value['link'];

                        $data['rows']->$key = (object)array();
                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
						$truUrl=substr($value['mainfoto'],1);
						//ini_set('error_reporting', E_ALL);
						//ini_set('display_errors', 1);
						//ini_set('display_startup_errors', 1);
						if(fopen($truUrl, "r")){
							//echo 'found';
							$data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
						}else{
							//echo $value['link'];
							$trueUrl=str_replace('crm/','crm/small/',$value['mainfoto']);
							$data['rows']->$key->foto = thumbImage($trueUrl, $this->module);
						}
						//echo '<pre style="display:none">';
						//echo $truUrl;
						//print_r($urlHeaders);
						//echo '</pre>';
                        //$data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']] . ' район';
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->mortgage = $value['ipoteka'];
                        $data['rows']->$key->fz214 = $value['fz214'];
                        $data['rows']->$key->parking = $value['parking'];
                        $data['rows']->$key->rassrochka = $value['rassrochka'];
                        $data['rows']->$key->otdelka = (in_array($value['otdelka_id'], array(
                            1,
                            3,
                            4,
                            5,
                            6,
                            7))) ? 1 : 0;

                        $price = '';

                        if (!empty($value['virtual_price']) && $value['virtual_price'] != 0) {
                            $price .= 'от <span>' . $this->number_format_drop_zero_decimals((($value['virtual_price']) /
                                1000000), 1) . '</span> млн руб';
                        } else {
                            $price = 'по запросу';
                        }

                        $data['rows']->$key->price = $price;
                        $data['rows']->$key->srok = $kr;
                        $data['rows']->$key->sign = array('class' => 'buildings', 'name' =>
                                'Новостройки');
                    }
                    break;

                case 'commercial':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Коммерческая недвижимость';
                    foreach ($dataRow as $key => $value) {
                        $sdd = $this->$model->getSdelka($value['id']);
                        $sssd = array();

                        if ($sdd && count($sdd) > 0) {
                            foreach ($sdd as $vv)
                                $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                        }

                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->price = $this->number_format_drop_zero_decimals($value['price'] /
                            1000000, 1);
                        $data['rows']->$key->price_arenda = $this->number_format_drop_zero_decimals($value['price_arenda'] /
                            1000, 1);

                        $price = '';

                        if ($data['rows']->$key->price > 0 && $data['rows']->$key->price_arenda > 0) {
                            $price = '<span>' . $data['rows']->$key->price . '</span> млн руб / <span>' . $data['rows']->
                                $key->price_arenda . '</span> тыс руб';
                        } elseif ($data['rows']->$key->price > 0) {
                            $price = '<span>' . $data['rows']->$key->price . '</span> млн руб';
                        } elseif ($data['rows']->$key->price_arenda > 0) {
                            $price = '<span>' . $data['rows']->$key->price_arenda . '</span> тыс руб';
                        } else {
                            $price = 'по запросу';
                        }

                        $data['rows']->$key->price = $price;
                        $data['rows']->$key->mortgage = $value['ipoteka'];
                        $data['rows']->$key->sdelka = (!$sdd || count($sdd) == 0) ? 'не указано' :
                            implode(" / ", $sssd);
                        $data['rows']->$key->sign = array('class' => 'commercial', 'name' =>
                                'Коммерческая');
                    }
                    break;

                case 'elite':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Элитная недвижимость';
                    foreach ($dataRow as $key => $value) {
                        if ($value['korpus'] == '2') {
                            $kr = 'Собственность';
                        } elseif ($value['korpus'] == '3') {
                            $kr = $this->dataKv($value['korpus_value']);
                        }

                        $key = $value['link'];

                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->price = (!empty($value['user_price_for_meter'])) ? $value['user_price_for_meter'] :
                            $value['price_for_meter'];
                        $data['rows']->$key->prices = $value['price'];
                        $data['rows']->$key->srok = $kr;
                        $data['rows']->$key->sign = array('class' => 'elite', 'name' => 'Элитная');
                        $data['rows']->$key->mortgage = $value['ipoteka'];

                        /*if($data['rows']->$key->price > 0)
                        { */
                        if ($data['rows']->$key->price > 0 or $data['rows']->$key->prices > 0) {
                            if ($data['rows']->$key->price > 0) {
                                $pri = 'от <span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                    price) / 1000), 1) . '</span> тыс руб/м<sup>2</sup>';
                            } else {
                                $pri = '<span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                    prices) / 1000000), 1) . '</span> млн руб';
                            }
                        } else {
                            $pri = 'по запросу';
                        }

                        $data['rows']->$key->price = $pri;
                        /*}
                        else
                        {
                        $data['rows']->$key->price = number_format($value['price'], 0, ' ', ' ').' руб.';
                        }	*/
                    }
                    break;

                case 'exclusive':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Эксклюзивные объекты';
                    foreach ($dataRow as $key => $value) {
                        if ($value['korpus'] == '2') {
                            $kr = 'Собственность';
                        } elseif ($value['korpus'] == '3') {
                            $kr = $this->dataKv($value['korpus_value']);
                        }

                        $key = $value['link'];

                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->price = (!empty($value['user_price_for_meter'])) ? $value['user_price_for_meter'] :
                            $value['price_for_meter'];
                        $data['rows']->$key->prices = $value['price'];
                        $data['rows']->$key->srok = $kr;
                        $data['rows']->$key->sign = array('class' => 'exclusive', 'name' =>
                                'Эксклюзивная');
                        $data['rows']->$key->mortgage = $value['ipoteka'];

                        /*if($data['rows']->$key->price > 0)
                        { */
                        if ($data['rows']->$key->price > 0 or $data['rows']->$key->prices > 0) {
                            if ($data['rows']->$key->price > 0) {
                                $pri = 'от <span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                    price) / 1000), 1) . '</span> тыс руб/м<sup>2</sup>';
                            } else {
                                $pri = '<span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                    prices) / 1000000), 1) . '</span> млн руб';
                            }
                        } else {
                            $pri = 'по запросу';
                        }

                        $data['rows']->$key->price = $pri;
                        /*}
                        else
                        {
                        $data['rows']->$key->price = number_format($value['price'], 0, ' ', ' ').' руб.';
                        }	*/
                    }
                    break;

                case 'resale':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Вторичная недвижимость';
                    foreach ($dataRow as $key => $value) {
                        $data['rows']->$key = new stdClass;
                        $data['rows']->$key->name = empty($value['name']) ? 'Название вторички' : $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->otd_metro = $value['otd_metro'] == 0 ? 'пешком' :
                            'транспортом';
                        $data['rows']->$key->otd_metro_value = empty($value['otd_metro_value']) ? '10' :
                            $value['otd_metro_value'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->rayon = !empty($data['d_rayon'][$value['rayon_id']]) ? $data['d_rayon'][$value['rayon_id']] :
                            '';
                        $data['rows']->$key->sign = array('class' => 'resale', 'name' => 'Вторичная');
                        $data['rows']->$key->mortgage = $value['ipoteka'];
                        $price = '';

                        $data['rows']->$key->square_all = $value['square_all'];
                        $data['rows']->$key->room = $parent_id_room[$value['room_id']];

                        if (!empty($value['price']) && $value['price'] != 0) {
                            $price .= '<span>' . number_format($this->number_format_drop_zero_decimals((($value['price'] /
                                1000)), 1), 0, ' ', ' ') . '</span> тыс руб';
                        } else {
                            $price = 'по запросу';
                        }

                        $data['rows']->$key->price = $price;
                        $data['rows']->$key->type = empty($data['d_type'][$value['type_id']]) ?
                            'Видовая квартира класса Luxe' : $data['d_type'][$value['type_id']];
                    }
                    break;

                case 'assignment':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Переуступки';
                    foreach ($dataRow as $key => $value) {
                        $data['rows']->$key->name = empty($value['name']) ? 'Название переуступки' : $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->otd_metro = $value['otd_metro'] == 0 ? 'пешком' :
                            'транспортом';
                        $data['rows']->$key->otd_metro_value = empty($value['otd_metro_value']) ? '10' :
                            $value['otd_metro_value'];
                        $data['rows']->$key->metro = $data['d_metro'][$value['metro_id']];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->sign = array('class' => 'resale', 'name' => 'Переуступки');
                        $data['rows']->$key->mortgage = $value['ipoteka'];
                        $price = '';

                        if (!empty($value['price']) && $value['price'] != 0) {
                            $price .= '<span>' . $this->number_format_drop_zero_decimals((($value['price']) /
                                1000000), 1) . '</span> млн руб';
                        } else {
                            $price = 'по запросу';
                        }

                        $data['rows']->$key->price = $price;
                        $data['rows']->$key->type = empty($data['d_type'][$value['type_id']]) ?
                            'Видовая квартира класса Luxe' : $data['d_type'][$value['type_id']];
                    }
                    break;

                case 'residential':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Загородная недвижимость';
                    foreach ($dataRow as $key => $value) {
                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->sign = array('class' => 'residential', 'name' =>
                                'Загородная');
                        $data['rows']->$key->mortgage = $value['ipoteka'];
                        $price = '';

                        if (!empty($value['price']) && $value['price'] != 0) {
                            $price .= '<span>' . $this->number_format_drop_zero_decimals((($value['price']) /
                                1000000), 1) . '</span> млн руб';
                        } else {
                            $price = 'по запросу';
                        }

                        $data['rows']->$key->price = $price;
                        $data['rows']->$key->desc = $value['z_desc'];
                    }
                    break;

                case 'world':

                    break;

                case 'land':
                $mr->{'name'} = $seo['title'];
                    $pageTitle = 'Участки';
                    $this->load->model('vodoprovod/admin_vodoprovod_model', 'admin_vodoprovod_model');
                    $parentvodoprovod = $this->admin_vodoprovod_model->parent_id();
                    $parent_idvodoprovod['0'] = ' -- не выбрано -- ';
                    foreach ($parentvodoprovod as $p)
                        $parent_idvodoprovod[$p['id']] = $p['name'];

                    $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                    $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                    $parent_idtypesdelka['0'] = '-- не выбрано --';
                    foreach ($parenttypesdelka as $p)
                        $parent_idtypesdelka[$p['id']] = $p['name'];

                    foreach ($dataRow as $key => $value) {
                        $map = explode('|', $value['map']);

                        $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $data['rows']->$key->name = $value['name'];
                        $data['rows']->$key->price = $this->number_format_drop_zero_decimals($value['price'] /
                            1000000, 1);
                        $data['rows']->$key->adress = $value['adress'];
                        $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                        $data['rows']->$key->lat = $map[0];
                        $data['rows']->$key->lng = $map[1];
                        $data['rows']->$key->rayon = $data['d_rayon'][$value['rayon_id']];
                        $data['rows']->$key->square_zemlya = $value['square_zemlya'];
                        $data['rows']->$key->ot_kad = $value['ot_kad'];
                        $data['rows']->$key->gas = $value['uc_gas'] == 1 ? 'есть' : 'нет';
                        $data['rows']->$key->uc_electrika = $value['uc_electrika'];
                        $data['rows']->$key->vodoprovod = $parent_idvodoprovod[$value['vodoprovod_id']];
                        $data['rows']->$key->typesdelka_id = $parent_idtypesdelka[$value['typesdelka_id']];
                        $data['rows']->$key->category = $parent_idruchastok[$value['razdel_uchastok_id']]['name'];
                        $data['rows']->$key->plashka = $parent_idruchastok[$value['razdel_uchastok_id']]['mainfoto'];
                    }
                    break;
            }

            $data['pageHeader'] = $pageTitle;
        }else{
			//echo'false';
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
            return;
        }

        // задаем хлебную кроху
        if (!empty($this->lang->md_breadcrumbs)) {
            $this->breadcrumbs($this->lang->md_breadcrumbs);
        } else {
            if (!isset($this->lang->md_header)) {
                $this->lang->md_header = '';
            }
            $this->breadcrumbs($this->lang->md_header);
        }


        $this->data['mr_tmpl'] = 'root';
            $offers = new StdClass;
            $offers_ = [];
            $minPrice = 9999999999;
            $maxPrice = 0;

            foreach ($dataRow as $offer) {
                $off = new StdClass;
                $off->{'@type'} = "Offer";
                $off->{'price'} = $offer['price'];
                $off->{'url'} = '/' . $this->link . '/' . $offer['link'];
                $off->{'priceCurrency'} = "RUB";
                $offers_[] = $off;

                if (!isset($minPrice)) {
                    $minPrice = (int)$offer['price'];
                }
                if (!isset($maxPrice)) {
                    $maxPrice = (int)$offer['price'];
                }

                if ($minPrice > (int)$offer['price']) {
                    $minPrice = (int)$offer['price'];
                }

                if ($maxPrice < (int)$offer['price']) {
                    $maxPrice = (int)$offer['price'];
                }
            }

            $offers->{'@type'} = "AggregateOffer";
            $offers->{'highPrice'} = $maxPrice;
            $offers->{'lowPrice'} = $minPrice;
            $offers->{'priceCurrency'} = "RUB";
            $offers->{'offerCount'} = count($dataRow);
            $offers->{'offers'} = $offers_;


            $mr->{'@context'} = "http://schema.org";
            $mr->{'@type'} = 'Product';
            $mr->{'offers'} = $offers;

            //dump(json_encode($mr));
            $this->data['MR'] = json_encode($mr);

        // формируем шаблон

        $this->addVar('template', $this->render($uri, $data));


        if ($this->input->is_ajax_request() and in_array($uri, array(
            'buildings',
            'resale',
            'assignment',
            'elite',
            'exclusive',
            'commercial',
            'residential',
            'land'))) {
            $this->load->view('themes/' . $this->defaultTheme . '/' . $uri . '_part', $data);
        } else {
            $this->viewPage($this->data); // выводим весь вид
        }
    }

    function number_format_drop_zero_decimals($n, $n_decimals) {
        //$n = number_format($n, $n_decimals, ',', ' ');
        $n = round($n, 1, PHP_ROUND_HALF_DOWN);
        $n = number_format($n, 1, ',', '');
        $ex = explode(',', $n);

        if (count($ex) > 1 && (int)$ex[1] == 0)
            $n = (int)$n;
        //return (($n == round($n, $n_decimals)) ? number_format($n, 0, ',', ' ') : number_format($n, $n_decimals, ',', ' '));
        return $n;
    }

    function showMap() {
        $uri = $this->data['uri1'];
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        $params = null;
        if (!empty($_POST['param'])) {
            $data = $_POST['param'];
            parse_str($data, $params);
        }

        $this->load->model('buildings/buildings_model', 'buildings_model');

        switch ($uri) {
            case 'resale':
                $razdel = 1;
                break;

            case 'assignment':
                $razdel = 8;
                break;

            case 'residential':
                $razdel = 2;
                break;

            case 'elite':
                $razdel = 3;
                break;

            case 'exclusive':
                $razdel = 9;
                break;

            case 'commercial':
                $razdel = 4;
                break;

            case 'land':
                $razdel = 6;

            default:
                $razdel = 0;
                $minMax = $this->buildings_model->getMinMaxBuildings();
                $minMax = $this->getMinMaxValuesBuildings($minMax);
                $minMaxS = $this->buildings_model->getMinMaxBuildingsSquare();
                $minMaxS = $this->getMinMaxValuesBuildingsS($minMaxS);

                if (!empty($params['price_from']) && $minMax['min'] == $params['price_from'])
                    $params['price_from'] = 0;

                if (!empty($params['price_to']) && $minMax['max'] == $params['price_to'])
                    $params['price_to'] = 0;

                if (!empty($params['square_from']) && $minMaxS['min'] == $params['square_from'])
                    $params['square_from'] = 0;

                if (!empty($params['square_to']) && $minMaxS['max'] == $params['square_to'])
                    $params['square_to'] = 0;
        }

        $maps = $this->buildings_model->allMapBuildings($razdel, $params);
        $dataMaps = array();
        foreach ($maps as $k => $v) {
            $row['id'] = $v['id'];
            $row['name'] = $v['name'];
            $m = $v['map'];
            $m = explode("|", $m);
            $row['lat'] = isset($m[0]) ? $m[0] : '';
            $row['lng'] = isset($m[1]) ? $m[1] : '';
            $row['link'] = BASEURL . '/' . $this->link . '/' . $v['link'];
            $row['foto'] = image($v['mainfoto'], 'small');
            $row['rayon'] = (isset($parent_idrayon[$v['rayon_id']])) ? $parent_idrayon[$v['rayon_id']] .
                ' район' : '';
            $row['address'] = $v['adress'];
            $row['metro'] = isset($parent_idmetro[$v['metro_id']]) ? $parent_idmetro[$v['metro_id']] :
                '';

            switch ($uri) {
                case 'buildings':
                    if ($v['korpus'] == '2') {
                        $kr = 'Собственность';
                    } elseif ($v['korpus'] == '3') {
                        $kr = $this->dataKv($v['korpus_value']);
                    }

                    $row['srok'] = $kr;
                    $row['price'] = ($v['virtual_price'] > 0) ? 'от <span>' . $this->
                        number_format_drop_zero_decimals($v['virtual_price'] / 1000000, 1) .
                        '</span> млн. руб.' : 'по запросу';
                    break;

                case 'resale':
                    $row['srok'] = null;
                    $row['price'] = ($v['price'] > 0) ? '<span>' . $this->
                        number_format_drop_zero_decimals($v['price'] / 1000000, 1) . '</span> млн. руб.' :
                        'по запросу';
                    break;

                case 'assignment':
                    $row['srok'] = null;
                    $row['price'] = ($v['price'] > 0) ? '<span>' . $this->
                        number_format_drop_zero_decimals($v['price'] / 1000000, 1) . '</span> млн. руб.' :
                        'по запросу';
                    break;

                case 'commercial':
                    $row['srok'] = null;
                    $row['price'] = ($v['price'] > 0) ? '<span>' . $this->
                        number_format_drop_zero_decimals($v['price'] / 1000000, 1) . '</span> млн. руб.' :
                        'по запросу';
                    break;

                case 'residential':
                    $row['srok'] = null;
                    $row['price'] = ($v['price'] > 0) ? '<span>' . $this->
                        number_format_drop_zero_decimals($v['price'] / 1000000, 1) . '</span> млн. руб.' :
                        'по запросу';
                    break;

                case 'land':
                    $row['srok'] = null;
                    $row['price'] = ($v['price'] > 0) ? '<span>' . $this->
                        number_format_drop_zero_decimals($v['price'] / 1000000, 1) . '</span> млн. руб.' :
                        'по запросу';
                    break;

                case 'elite':
                    $row['srok'] = null;
                    if ($v['price'] > 0 or $v['virtual_price']) {
                        $pri = ($v['virtual_price']) ? 'от <span>' . $this->
                            number_format_drop_zero_decimals($v['virtual_price'] / 1000000, 1) .
                            '</span> млн. руб.' : '<span>' . $this->number_format_drop_zero_decimals($v['price'] /
                            1000000, 1) . '</span> млн. руб.';
                    } else {
                        $pri = 'по запросу';
                    }

                    $row['price'] = $pri;
                    break;

                case 'exclusive':
                    $row['srok'] = null;
                    if ($v['price'] > 0 or $v['virtual_price']) {
                        $pri = ($v['virtual_price']) ? 'от <span>' . $this->
                            number_format_drop_zero_decimals($v['virtual_price'] / 1000000, 1) .
                            '</span> млн. руб.' : '<span>' . $this->number_format_drop_zero_decimals($v['price'] /
                            1000000, 1) . '</span> млн. руб.';
                    } else {
                        $pri = 'по запросу';
                    }

                    $row['price'] = $pri;
                    break;
            }

            $dataMaps[] = $row;
        }

        echo json_encode($dataMaps);
    }

    function showMapResale() {
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        $this->load->model('buildings/buildings_model', 'buildings_model');
        $maps = $this->buildings_model->allMapBuildings(1);
        $dataMaps = array();

        foreach ($maps as $k => $v) {
            $row['id'] = $v['id'];
            $row['name'] = $v['name'];
            $m = $v['map'];
            $m = explode("|", $m);
            $row['lat'] = $m[0];
            $row['lng'] = $m[1];
            $row['link'] = BASEURL . '/' . $this->link . '/' . $v['link'];
            $row['foto'] = image($v['mainfoto'], 'small');
            $row['rayon'] = $parent_idrayon[$v['rayon_id']] . ' район';
            $row['address'] = $v['adress'];
            $row['metro'] = $parent_idmetro[$v['metro_id']];

            if ($v['korpus'] == '2') {
                $kr = 'Собственность';
            } elseif ($v['korpus'] == '3') {
                $kr = $this->dataKv($v['korpus_value']);
            }

            $row['price'] = $this->number_format_drop_zero_decimals((($v['virtual_price']) /
                1000000), 1);
            $dataMaps[] = $row;
        }

        echo json_encode($dataMaps);
    }

    function showMapAssignment() {
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        $this->districtsnameArray = $parent_idrayon;

        $this->load->model('buildings/buildings_model', 'buildings_model');
        $maps = $this->buildings_model->allMapBuildings(1);
        $dataMaps = array();

        foreach ($maps as $k => $v) {
            $row['id'] = $v['id'];
            $row['name'] = $v['name'];
            $m = $v['map'];
            $m = explode("|", $m);
            $row['lat'] = $m[0];
            $row['lng'] = $m[1];
            $row['link'] = BASEURL . '/' . $this->link . '/' . $v['link'];
            $row['foto'] = image($v['mainfoto'], 'small');
            $row['rayon'] = $parent_idrayon[$v['rayon_id']] . ' район';
            $row['address'] = $v['adress'];
            $row['metro'] = $parent_idmetro[$v['metro_id']];

            if ($v['korpus'] == '2') {
                $kr = 'Собственность';
            } elseif ($v['korpus'] == '3') {
                $kr = $this->dataKv($v['korpus_value']);
            }

            $row['price'] = $this->number_format_drop_zero_decimals((($v['virtual_price']) /
                1000000), 1);
            $dataMaps[] = $row;
        }

        echo json_encode($dataMaps);
    }

    /** Просмотр одной записи */
    function view($amp=false) {

        $this->setOptions(); // заносим опции в переменные
        $uri = $this->data['uri1'];

        $eliteORresale = 'elite_view';

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id();
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id();
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $parent_id_room[$p['id']] = $p['name'];

        $this->load->model('otdelka/admin_otdelka_model', 'admin_otdelka_model');
        $parent_otdelka = $this->admin_otdelka_model->parent_id();
        foreach ($parent_otdelka as $p)
            $parent_id_otdelka[$p['id']] = $p['name'];

        $data['room'] = $parent_id_room;
        $data['otdelka'] = $parent_id_otdelka;

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные

        if (empty($dataRow)) {
            // Если в url есть amp не показывать страницу
            if (strpos($_SERVER['REQUEST_URI'], '/amp')) {
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
                return;
            }
            // если записине нашлось, то пробуем найти по районам
            // и вывести район
            $this->rayonPage($this->data['uri2']);
            return;
        } else {
            $data['id'] = $dataRow['id'];

            $this->$model->updateViews($dataRow['id']);

            $data['rows']->presentation = $dataRow['presentation'];

            if ($uri == 'buildings') {
				if($amp){
					$this->AmpPage($dataRow);
					exit;
				}
            	
					// новогодний попап
                $fileName =  'ny_popup.js';
                if (null !== $path = $this->config->config['sectors_scripts_path']) {
                    js($path . $fileName);
                }            	
            	
                //$this->addVar('no_index', true);

                if ($dataRow['korpus'] == '2') {
                    $kr = 'Собственность';
                } else {
                    $kr = $this->dataKv($dataRow['korpus_value']);
                }

                $map = explode('|', $dataRow['map']);

                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if (($bigFoto == '/asset/uploads/_thumbs/images/no_image.jpg' || $bigFoto ==
                    '/asset/img/logo-M16.png') && $dataRow['mainfoto'] != '/asset/img/logo-M16.png') {
                    $bigFoto = $dataRow['mainfoto'];
                } elseif ($dataRow['mainfoto'] == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                $price = $this->$model->complexMinPrice($dataRow['id']);
                $price_buildings = $this->number_format_drop_zero_decimals((($price) / 1000000),
                    1) . ' млн. руб.';
                if (!empty($price) && $price != 0) {
                    $dig_price=$price;
                    $price = '<span> от <span>' . $this->number_format_drop_zero_decimals((($price) /
                        1000000), 1) . '</span> млн руб</span>';
                } else {
                    $price = '<span>по запросу</span>';
                }

                $this->load->model('builders/admin_builders_model', 'admin_builders_model');
                $builders = $this->admin_builders_model->module_get($dataRow['builder_id']);



                $data['rows']->id = $dataRow['id'];
                if(floor($dataRow['rating']/$dataRow['raters'])>5){
                    $data['rows']->ratval = floor($dataRow['rating']/$dataRow['raters']);
                }else {
                    $data['rows']->ratval = 5;
                }
                $data['rows']->raterval = $dataRow['raters'];
                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->foto_otdelka = unserialize($dataRow['foto_otdelka']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->link = $dataRow['link'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->text = $dataRow['text'];
                $data['rows']->adress = $dataRow['adress'];
                $data['rows']->srok = $kr;
                $data['rows']->price = $price;
                $data['rows']->dig_price = $dig_price;
                $data['rows']->type = $parent_idtype[$dataRow['type_id']];
                $data['rows']->rayon = $parent_idrayon[$dataRow['rayon_id']];
                $data['rows']->metro = $parent_idmetro[$dataRow['metro_id']];
                $data['rows']->mainfoto = $dataRow['mainfoto'];
                $data['rows']->infrastruct = $dataRow['infrastruct'];
				//echo '<pre>'; echo $dataRow['video_desc']; echo '</pre>';
                $data['rows']->youtube = getYoutubeVideoID($dataRow['video_code'], $dataRow['video_desc']);
                $data['rows']->video_code = $dataRow['video_code'];
                $data['rows']->video_desc = $dataRow['video_desc'];
                $data['rows']->otdelka = $dataRow['otdelka'];
                $data['rows']->ipoteka_text = $dataRow['ipoteka_text'];
                $data['rows']->banks_list = $this->$model->getBanksBuilding($dataRow['banks']);
                $data['rows']->banks_programms = $this->$model->getBanksProgrammsBuilding($dataRow['banks']);
                $data['rows']->transport = $dataRow['transport'];
                $data['rows']->builder = $builders;
                $data['rows']->tour = $dataRow['tour'];

                $zastr = (!empty($builders)) ? $builders['name'] : '';
                $data['rows']->img_alt = "Фото новостройки Жилой комплекс «{$data['rows']->header}» рядом с метро {$data['rows']->metro} | М16 - актуальный фотокаталог недвижимости, продажа квартир в СПб.";
                $likeas = array();

                if (!empty($dataRow['likeas'])) {
                    $temps = $this->$model->getBuildingsAll(explode(',', $dataRow['likeas']));
                    foreach ($temps as $key => $value) {
                        $likeas[$key]['name'] = $value['name'];
                        $likeas[$key]['link'] = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $likeas[$key]['foto'] = $value['main_foto'];
                        $likeas[$key]['price'] = ($value['virtual_price'] > 0) ? 'от <span>' . $this->
                            number_format_drop_zero_decimals(($value['virtual_price'] / 1000000), 1) .
                            '</span> млн руб' : 'по запросу';
                        $likeas[$key]['adress'] = $value['adress'];
                        $likeas[$key]['metro'] = $parent_idmetro[$value['metro_id']];
                        $likeas[$key]['rayon'] = $parent_idrayon[$value['rayon_id']] . ' район';

                        $kr = ($value['korpus'] == '2') ? 'Собственность' : $this->dataKv($value['korpus_value']);

                        $likeas[$key]['srok'] = $kr;
                        $likeas[$key]['sign'] = array('class' => 'buildings', 'name' => 'Новостройки');
                    }

                }
                $data['rows']->likeas = $likeas;

                $nArray = '';
                $apartments = $this->$model->room_complex($dataRow['id']); // квартиры комплекса
                if (!empty($apartments)) {
                    foreach ($apartments as $k => $ap) {
                        $nArray[(int)$ap['room_id']][$k] = $ap;
                        $nArray[(int)$ap['room_id']][$k]['room_id'] = $parent_id_room[$ap['room_id']];
                        $room = $parent_id_room[$ap['room_id']];

                        if ($room == 'Студия') {
                            $nArray[$ap['room_id']][$k]['room'] = "Квартира студия";
                        } elseif ($room == 'К. пом.' || $room == 'Коммерческое пом.' || $room == 'К. пом') {
                            $nArray[$ap['room_id']][$k]['room'] = "Коммерческое помещение";
                        } else {
                            $nArray[$ap['room_id']][$k]['room'] = (int)$room . " комн. квартира";
                        }

                        if (in_array($dataRow['room_id'], array(
                            16,
                            17,
                            18)))
                            $nArray[$ap['room_id']][$k]['room'] .= " (евро)";

                        $nArray[$ap['room_id']][$k]['price'] = number_format($ap['price'], 0, ' ', ' ');
                        $otd = $parent_id_otdelka[$ap['otdelka_id']];
                        if (empty($otd)) {
                            $otd = 'без отделки';
                        }
                        $nArray[$ap['room_id']][$k]['otdelka_id'] = $otd;
                        $nArray[$ap['room_id']][$k]['floor'] = $ap['floor'];
                    }

                    $newArray = '';
                    $floor_array = '';

                    if (!empty($nArray)) {
                        foreach ($nArray as $ley => $p) {
                            if (!empty($p)) {
                                foreach ($p as $k => $v) {

                                    $ms = $v['room_id'] . '|' . $v['price'] . '|' . $v['square_all'] . '|' . $v['square_life'] .
                                        '|' . $v['square_cook'] . '|' . $v['square_balcony'] . '|' . $v['section'] . '|' .
                                        $v['otdelka_id'] . '|' . $v['room'];

                                    if (!isset($newArray[$ley][$ms])) {
                                        $newArray[$ley][$ms] = $v;
                                        $floor_array[$ms][$v['floor']] = $v['floor'];
                                    } else {
                                        $floor_array[$ms][$v['floor']] = $v['floor'];
                                    }

                                }
                            }
                        }

                        $nArray = '';
                        if (!empty($newArray)) {
                            foreach ($newArray as $k => $v) {
                                foreach ($v as $kk => $item) {
                                    if (isset($floor_array[$kk])) {
                                        $item['floor'] = floor_ap($floor_array[$kk]);
                                    }
                                    $nArray[$k][] = $item;
                                }
                            }
                        }
                    }

                }

                $data['rows']->appartments = $nArray;

                $this->load->model('favorites/favorites_model', 'favorites_model');

                $favarray = array(
                    'object_id' => $dataRow['id'],
                    'category' => 'buildings',
                    'name' => $dataRow['name'],
                    'foto' => $dataRow['mainfoto'],
                    'sort' => $dataRow['sort'],
                    'link' => $dataRow['link'],
                    'price' => "sss");

                $idfs = $this->favorites_model->addToFavorites($favarray);
                $this->load->helper('cookie');
                $favorites = $this->input->cookie('favorites', true);
                $favorites = explode('.', $favorites);

                if (in_array($idfs, $favorites)) {
                    $data['in_favorites'] = true;
                }

                $sessi = $this->session->userdata('seen');

                if (!in_array($idfs, $sessi)) {
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($this->session->userdata('seen')) {

                    $sessi = $this->session->userdata('seen');
                    if (!in_array($idfs, $sessi)) {
                        array_push($sessi, $idfs);
                        $this->session->set_userdata('seen', $sessi);
                    }

                } else {
                    $this->session->set_userdata('seen', array());
                    $sessi = array('0' => $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }
                $data['refer'] = BASEURL . $this->getReferer('buildings');
            } elseif ($uri == 'commercial') {
                $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                $parent_idtypesdelka['0'] = '-- не выбрано --';
                foreach ($parenttypesdelka as $p) {
                    $parent_idtypesdelka[$p['id']] = $p['name'];
                }

                $map = explode('|', $dataRow['map']);
                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if ($bigFoto == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                $sdd = $this->$model->getSdelka($dataRow['id']);
                $sssd = array();
                if ($sdd && count($sdd) > 0) {

                    foreach ($sdd as $vv) {

                        $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                    }
                }

                $this->load->model('commercial_type/admin_commercial_type_model',
                    'commercial_type_model');
                $commtype = $this->commercial_type_model->parent_id();
                foreach ($commtype as $p) {
                    $parent_idcommtype[$p['id']] = $p['name'];
                }

                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->link = $dataRow['link'];
                $data['rows']->text = $dataRow['text'];
                $data['rows']->adress = $dataRow['adress'];
                $data['rows']->rayon = $parent_idrayon[$dataRow['rayon_id']];
                $data['rows']->comm_type = $parent_idcommtype[$dataRow['comm_type']];
                $commtype = $parent_idcommtype[$dataRow['comm_type']];

                $data['rows']->metro = $parent_idmetro[$dataRow['metro_id']];
                //$data['rows']->price = number_format($dataRow['price'], 0, ' ', ' ');
                //$data['rows']->price_arenda = number_format($dataRow['price_arenda'], 0, ' ', ' ');

                // alt = pageTitle, присваивается ниже там где формируется title
                //$data['rows']->img_alt

                if (!empty($dataRow['price']) && !empty($dataRow['price_arenda'])) {
                    $price = '<span><span>' . $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . '</span> млн. руб / <span>' . $this->
                        number_format_drop_zero_decimals((($dataRow['price_arenda']) / 1000), 1) .
                        '</span>тыс. руб</span>';
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $this->
                        number_format_drop_zero_decimals((($dataRow['price']) / 1000000), 1) .
                        ' млн руб / ' . $this->number_format_drop_zero_decimals((($dataRow['price_arenda']) /
                        1000), 1) . 'тыс. руб';
                    $seo_price = $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . ' млн. руб / ' . $this->number_format_drop_zero_decimals((($dataRow['price_arenda']) /
                        1000), 1) . ' тыс. руб';
                } elseif (!empty($dataRow['price'])) {
                    $price = '<span><span>' . $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . '</span> млн. руб</span>';
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $this->
                        number_format_drop_zero_decimals((($dataRow['price']) / 1000000), 1) .
                        ' млн руб';
                    $seo_price = $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . ' млн. руб';
                } elseif (!empty($dataRow['price_arenda'])) {

                    $price = '<span><span>' . $this->number_format_drop_zero_decimals((($dataRow['price_arenda']) /
                        1000), 1) . '</span> тыс. руб</span>';
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $this->
                        number_format_drop_zero_decimals((($dataRow['price_arenda']) / 1000), 1) .
                        ' тыс. руб';
                    $seo_price = $this->number_format_drop_zero_decimals((($dataRow['price_arenda']) /
                        1000), 1) . ' тыс. руб';
                } else {
                    $price = '<span>по запросу</span>';
                    $data['rows']->price_text = '';
                    $seo_price = 'по запросу';
                }
                $data['rows']->price = $price;
                // $data['rows']->$key->sdelka = (!$sdd || count($sdd)==0) ? 'не указано' : implode(" / ", $sssd);
                $smetro = $parent_idmetro[$dataRow['metro_id']];
                $srayon = $parent_idrayon[$dataRow['rayon_id']];
                $data['rows']->options = array(
                    'Район' => $parent_idrayon[$dataRow['rayon_id']],
                    'Метро' => $parent_idmetro[$dataRow['metro_id']],
                    'Тип сделки' => (!$sdd || count($sdd) == 0) ? 'не указано' : implode(" / ", $sssd),
                    'Адрес' => $dataRow['adress']);
                $data['rows']->mainfoto = $dataRow['mainfoto'];

                $this->load->model('favorites/favorites_model', 'favorites_model');

                $favarray = array(
                    'object_id' => $dataRow['id'],
                    'category' => 'commercial',
                    'name' => $dataRow['name'],
                    'foto' => $dataRow['mainfoto'],
                    'sort' => $dataRow['sort'],
                    'link' => $dataRow['link'],
                    'price' => $data['rows']->price);
                $idfs = $this->favorites_model->addToFavorites($favarray);
                $this->load->helper('cookie');
                $favorites = $this->input->cookie('favorites', true);
                $favorites = explode('.', $favorites);
                if (in_array($idfs, $favorites)) {
                    $data['in_favorites'] = true;
                }
                $sessi = $this->session->userdata('seen');
                if (!in_array($idfs, $sessi)) {
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($this->session->userdata('seen')) {

                    $sessi = $this->session->userdata('seen');
                    if (!in_array($idfs, $sessi)) {
                        array_push($sessi, $idfs);
                        $this->session->set_userdata('seen', $sessi);
                    }

                } else {
                    $this->session->set_userdata('seen', array());
                    $sessi = array('0' => $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }
                $data['refer'] = BASEURL . $this->getReferer('commercial');
            } elseif ($uri == 'elite' or $uri == 'exclusive') {


                if ($dataRow['korpus'] == '2') {
                    $kr = 'Собственность';
                } elseif ($dataRow['korpus'] == '3') {
                    $kr = $this->dataKv($dataRow['korpus_value'], false);
                }

                $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                $parent_idtypesdelka['0'] = '-- не выбрано --';
                foreach ($parenttypesdelka as $p) {
                    $parent_idtypesdelka[$p['id']] = $p['name'];
                }

                $this->load->model('okna/admin_okna_model', 'admin_okna_model');
                $parentokna = $this->admin_okna_model->parent_id();
                foreach ($parentokna as $p) {
                    $parent_idokna[$p['id']] = $p['name'];
                }

                $this->load->model('planirovka/admin_planirovka_model', 'admin_planirovka_model');
                $parentplanirovka = $this->admin_planirovka_model->parent_id();
                foreach ($parentplanirovka as $p) {
                    $parent_idplanirovka[$p['id']] = $p['name'];
                }

                $this->load->model('otdelka/admin_otdelka_model', 'admin_otdelka_model');
                $parent_otdelka = $this->admin_otdelka_model->parent_id();
                $parent_id_otdelka[''] = ' -- не выбрано -- ';
                foreach ($parent_otdelka as $p) {
                    $parent_id_otdelka[$p['id']] = $p['name'];
                }

                $this->load->model('vhod/admin_vhod_model', 'admin_vhod_model');
                $parentvhod = $this->admin_vhod_model->parent_id();
                foreach ($parentvhod as $p) {
                    $parent_idvhod[$p['id']] = $p['name'];
                }

                $this->load->model('typeprodazha/admin_typeprodazha_model',
                    'admin_typeprodazha_model');
                $parenttypeprodazha = $this->admin_typeprodazha_model->parent_id();
                foreach ($parenttypeprodazha as $p) {
                    $parent_idtypeprodazha[$p['id']] = $p['name'];
                }

                $map = explode('|', $dataRow['map']);
                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if ($bigFoto == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                if (empty($map[0])) {
                    $map[0] = 59.9349682020846;
                    $map[1] = 30.3330386634766;
                }

                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->text = $dataRow['text'];
                $data['rows']->adress = $dataRow['adress'];
                $data['rows']->link = $dataRow['link'];
                $data['rows']->srok = $kr;
                $data['rows']->price = (!empty($dataRow['user_price_for_meter'])) ? $dataRow['user_price_for_meter'] :
                    $dataRow['price_for_meter'];
                ;
                $data['rows']->type = $parent_idtype[$dataRow['type_id']];
                $data['rows']->rayon = $parent_idrayon[$dataRow['rayon_id']];
                $srayon = $parent_idrayon[$dataRow['rayon_id']];
                $data['rows']->mainfoto = $dataRow['mainfoto'];
                $data['rows']->tour = $dataRow['tour'];
                $data['rows']->prices = '';

                if (!empty($data['rows']->price) && $data['rows']->price > 0) {
                    $data['rows']->prices = '<span>от <span>' . $this->
                        number_format_drop_zero_decimals((($data['rows']->price) / 1000), 1) .
                        '</span> тыс руб/м<sup>2</sup></span>';
                    $data['rows']->price = '<span>от <span>' . $this->
                        number_format_drop_zero_decimals((($data['rows']->price) / 1000), 1) .
                        '</span> тыс руб/м<sup>2</sup></span>';
                    $data['rows']->price_text = '';
                    $seo_price = 'от ' . $this->number_format_drop_zero_decimals((($data['rows']->
                        price) / 1000), 1) . '</span> тыс. руб/кв. м.';
                    $stype = 'Недвижимость в ЖК ' . $dataRow['name'];
                } elseif ($dataRow['price'] > 0) {
                    $data['rows']->price = '<span><span>' . $this->number_format_drop_zero_decimals($dataRow['price'] /
                        1000000, 1, ',', ' ') . '</span> млн руб</span>';
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $this->
                        number_format_drop_zero_decimals((($dataRow['price']) / 1000000), 1) .
                        ' млн руб';
                    $seo_price = $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . ' млн руб';
                    $stype = 'Квартира';
                } else {
                    $data['rows']->price = '<span>по запросу</span>';
                    $data['rows']->price_text = '';
                    $seo_price = 'по запросу';
                    $stype = 'Квартира';
                }

                $data['rows']->type_id = $parent_idtype[$dataRow['type_id']];

                $data['rows']->metro = $parent_idmetro[$dataRow['metro_id']];
                $smetro = $parent_idmetro[$dataRow['metro_id']];
                $data['rows']->room = $parent_id_room[$dataRow['room_id']];
                $sroom = (!empty($dataRow['room_id'])) ? 'количество комнат ' . $parent_id_room[$dataRow['room_id']] .
                    ' шт.' : '';
                $data['rows']->typesdelka_id = $parent_idtypesdelka[$dataRow['typesdelka_id']];

                $data['rows']->okna = $parent_idokna[$dataRow['okna_id']];
                $data['rows']->planirovka = $parent_idplanirovka[$dataRow['planirovka_id']];
                $data['rows']->otdelka = $dataRow['otdelka'];
                $data['rows']->vhod = $parent_idvhod[$dataRow['vhod_id']];
                $data['rows']->prodazha = $parent_idtypeprodazha[$dataRow['typeprodazha_id']];
                $data['rows']->otdelkaid = $parent_id_otdelka[$dataRow['otdelka_id']];

                $data['rows']->floor = $dataRow['floor'];
                $data['rows']->square_all = $dataRow['square_all'];
                $ssquare = (!empty($dataRow['square_all'])) ? 'общей площадью ' . $dataRow['square_all'] .
                    ' кв. м.' : '';
                $data['rows']->square_life = $dataRow['square_life'];
                $data['rows']->square_cook = $dataRow['square_cook'];
                $data['rows']->mainfoto = $dataRow['mainfoto'];
                $data['rows']->otdelka = $dataRow['otdelka'];
                $data['rows']->foto_otdelka = unserialize($dataRow['foto_otdelka']);

                $nArray = '';
                $apartments = $this->$model->room_complex($dataRow['id']); // квартиры комплекса
                if (!empty($apartments)) {
                    foreach ($apartments as $k => $ap) {
                        $nArray[$ap['room_id']][$k] = $ap;
                        $nArray[$ap['room_id']][$k]['room_id'] = $parent_id_room[$ap['room_id']];
                        $nArray[$ap['room_id']][$k]['price'] = number_format($ap['price'], 0, ' ', ' ');
                        $room = $parent_id_room[$ap['room_id']];
                        if ($room == 'Студия')
                            $nArray[$ap['room_id']][$k]['room'] = "Квартира студия";
                        elseif ($room == 'К. пом.' || $room == 'Коммерческое пом.')
                            $nArray[$ap['room_id']][$k]['room'] = "Коммерческое помещение";
                        else {
                            $nArray[$ap['room_id']][$k]['room'] = (int)$room . " комн. квартира";
                        }
                        if (in_array($dataRow['room_id'], array(
                            16,
                            17,
                            18))) {
                            $nArray[$ap['room_id']][$k]['room'] .= " (евро)";
                        }
                        $otd = $parent_id_otdelka[$ap['otdelka_id']];
                        if (empty($otd)) {
                            $otd = 'без отделки';
                        }
                        $nArray[$ap['room_id']][$k]['otdelka_id'] = $otd;
                    }
                    $eliteORresale = 'apartments';
                }
                $data['rows']->appartments = $nArray;
                $this->load->model('favorites/favorites_model', 'favorites_model');

                $favarray = array(
                    'object_id' => $dataRow['id'],
                    'category' => 'elite',
                    'name' => $dataRow['name'],
                    'foto' => $dataRow['mainfoto'],
                    'sort' => $dataRow['sort'],
                    'link' => $dataRow['link'],
                    'price' => $data['rows']->price);

                if ($uri == 'exclusive')
                    $favarray['category'] = 'exclusive';

                $idfs = $this->favorites_model->addToFavorites($favarray);
                $this->load->helper('cookie');
                $favorites = $this->input->cookie('favorites', true);
                $favorites = explode('.', $favorites);
                if (in_array($idfs, $favorites)) {
                    $data['in_favorites'] = true;
                }
                $sessi = $this->session->userdata('seen');
                if (!in_array($idfs, $sessi)) {
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($this->session->userdata('seen')) {

                    $sessi = $this->session->userdata('seen');
                    if (!in_array($idfs, $sessi)) {
                        array_push($sessi, $idfs);
                        $this->session->set_userdata('seen', $sessi);
                    }

                } else {
                    $this->session->set_userdata('seen', array());
                    $sessi = array('0' => $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($uri == 'exclusive') {
                    $data['refer'] = BASEURL . $this->getReferer('exclusive');
                } else {
                    $data['refer'] = BASEURL . $this->getReferer('elite');
                }
            } elseif ($uri == 'resale' or $uri == 'assignment') {

                $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                $parent_idtypesdelka['0'] = '-- не выбрано --';
                foreach ($parenttypesdelka as $p) {
                    $parent_idtypesdelka[$p['id']] = $p['name'];
                }

                $this->load->model('okna/admin_okna_model', 'admin_okna_model');
                $parentokna = $this->admin_okna_model->parent_id();
                foreach ($parentokna as $p) {
                    $parent_idokna[$p['id']] = $p['name'];
                }

                $this->load->model('planirovka/admin_planirovka_model', 'admin_planirovka_model');
                $parentplanirovka = $this->admin_planirovka_model->parent_id();
                foreach ($parentplanirovka as $p) {
                    $parent_idplanirovka[$p['id']] = $p['name'];
                }

                $this->load->model('otdelka/admin_otdelka_model', 'admin_otdelka_model');
                $parent_otdelka = $this->admin_otdelka_model->parent_id();
                $parent_id_otdelka[''] = ' -- не выбрано -- ';
                foreach ($parent_otdelka as $p) {
                    $parent_id_otdelka[$p['id']] = $p['name'];
                }

                $this->load->model('vhod/admin_vhod_model', 'admin_vhod_model');
                $parentvhod = $this->admin_vhod_model->parent_id();
                foreach ($parentvhod as $p) {
                    $parent_idvhod[$p['id']] = $p['name'];
                }

                $this->load->model('typeprodazha/admin_typeprodazha_model',
                    'admin_typeprodazha_model');
                $parenttypeprodazha = $this->admin_typeprodazha_model->parent_id();
                foreach ($parenttypeprodazha as $p) {
                    $parent_idtypeprodazha[$p['id']] = $p['name'];
                }

                $map = explode('|', $dataRow['map']);
                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if ($bigFoto == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                if (empty($map[0])) {
                    $map[0] = 59.9349682020846;
                    $map[1] = 30.3330386634766;
                }

                if (!empty($dataRow['price']) && $dataRow['price'] != 0) {
                    $prc = '<span>' . number_format($this->number_format_drop_zero_decimals((($dataRow['price'] /
                        1000)), 1), 0, ' ', ' ') . '</span> тыс руб';
                    $prc2 = number_format($this->number_format_drop_zero_decimals((($dataRow['price'] /
                        1000)), 1), 0, ' ', ' ') . ' тыс руб';

                    $price = $prc;
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $prc2;
                    $price_buildings = $prc2;
                } else {
                    $price = '<span>по запросу</span>';
                    $data['rows']->price_text = '';
                    $price_buildings = 'по запросу';
                }
                //dump($dataRow['mainfoto']);
                //dump(unserialize($dataRow['foto']));
                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->text = $dataRow['text'];
                $data['rows']->link = $dataRow['link'];
                $data['rows']->adress = $dataRow['adress'];
                $data['rows']->price = $price;
                $data['rows']->type_id = $parent_idtype[$dataRow['type_id']];
                $data['rows']->rayon = $parent_idrayon[$dataRow['rayon_id']];
                $data['rows']->metro = $parent_idmetro[$dataRow['metro_id']];
                $data['rows']->room = $parent_id_room[$dataRow['room_id']];
                $roomsc = $parent_id_room[$dataRow['room_id']];
                $data['rows']->typesdelka_id = $parent_idtypesdelka[$dataRow['typesdelka_id']];

                $data['rows']->okna = $parent_idokna[$dataRow['okna_id']];
                $data['rows']->planirovka = $parent_idplanirovka[$dataRow['planirovka_id']];
                $data['rows']->otdelkaid = $parent_id_otdelka[$dataRow['otdelka_id']];

                $data['rows']->otdelka = $dataRow['otdelka'];
                $data['rows']->foto_otdelka = unserialize($dataRow['foto_otdelka']);
                $data['rows']->vhod = $parent_idvhod[$dataRow['vhod_id']];
                $data['rows']->prodazha = $parent_idtypeprodazha[$dataRow['typeprodazha_id']];

                $data['rows']->floor = $dataRow['floor'];
                $data['rows']->tour = $dataRow['tour'];
                $data['rows']->square_all = $dataRow['square_all'];
                $data['rows']->square_life = $dataRow['square_life'];
                $data['rows']->square_cook = $dataRow['square_cook'];
                $data['rows']->mainfoto = $dataRow['mainfoto'];
                $data['rows']->img_alt = "фото квартиры {$dataRow['adress']}, {$dataRow['square_all']} кв м. | М16 - продажа квартир на вторичном рынке Санкт-Петербурга.";



                $likeas = array();
                if (!empty($dataRow['likeas'])) {
                    $temps = $this->$model->getBuildingsAll(explode(',', $dataRow['likeas']));
                    foreach ($temps as $key => $value) {
                        $likeas[$key]['name'] = $value['name'];
                        $likeas[$key]['link'] = BASEURL . '/' . $this->link . '/' . $value['link'];
                        $likeas[$key]['foto'] = $value['main_foto'];
                        if ($value['price'] > 0)
                            $likeas[$key]['price'] = '<span>' . $this->number_format_drop_zero_decimals(($value['price'] /
                                1000000), 1) . '</span> млн руб';
                        else
                            $likeas[$key]['price'] = 'по запросу';
                        $likeas[$key]['adress'] = $value['adress'];
                        $likeas[$key]['metro'] = $parent_idmetro[$value['metro_id']];
                        $likeas[$key]['rayon'] = $parent_idrayon[$value['rayon_id']] . ' район';
                        /*if($value['korpus'] == '2') { $kr = 'Собственность'; }
                        else { $kr = $this->dataKv($value['korpus_value']); }
                        $likeas[$key]['srok'] = $kr;*/
                        $likeas[$key]['sign'] = array('class' => 'buildings', 'name' => 'Новостройки');
                    }

                }
                $data['rows']->likeas = $likeas;
                $this->load->model('favorites/favorites_model', 'favorites_model');

                $favarray = array(
                    'object_id' => $dataRow['id'],
                    'category' => 'resale',
                    'name' => $dataRow['name'],
                    'foto' => $dataRow['mainfoto'],
                    'sort' => $dataRow['sort'],
                    'link' => $dataRow['link'],
                    'price' => $data['rows']->price);

                if ($uri == 'assignment') {
                    // Ссылка на новостройку
                    $data['rows']->newObjectLink = $this->$model->getNewBuildingLinkByName($data['rows']->
                        header);
                    $favarray['category'] = 'assignment';
                }

                $idfs = $this->favorites_model->addToFavorites($favarray);
                $this->load->helper('cookie');
                $favorites = $this->input->cookie('favorites', true);
                $favorites = explode('.', $favorites);
                if (in_array($idfs, $favorites)) {
                    $data['in_favorites'] = true;
                }
                $sessi = $this->session->userdata('seen');
                if (!in_array($idfs, $sessi)) {
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($this->session->userdata('seen')) {

                    $sessi = $this->session->userdata('seen');
                    if (!in_array($idfs, $sessi)) {
                        array_push($sessi, $idfs);
                        $this->session->set_userdata('seen', $sessi);
                    }

                } else {
                    $this->session->set_userdata('seen', array());
                    $sessi = array('0' => $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($uri == 'assignment') {
                    $data['refer'] = BASEURL . $this->getReferer('assignment');
                } else {
                    $data['refer'] = BASEURL . $this->getReferer('resale');
                }
            } elseif ($uri == 'residential') {

                $this->load->model('infrostructura/admin_infrostructura_model',
                    'admin_infrostructura_model');
                $parentinfrostructura = $this->admin_infrostructura_model->parent_id();
                $parent_idinfrostructura['0'] = ' -- не выбрано -- ';
                foreach ($parentinfrostructura as $p) {
                    $parent_idinfrostructura[$p['id']] = $p['name'];
                }

                $this->load->model('matherial/admin_matherial_model', 'admin_matherial_model');
                $parentmatherial = $this->admin_matherial_model->parent_id();
                $parent_idmatherial['0'] = ' -- не выбрано -- ';
                foreach ($parentmatherial as $p) {
                    $parent_idmatherial[$p['id']] = $p['name'];
                }

                $this->load->model('vodoem/admin_vodoem_model', 'admin_vodoem_model');
                $parentvodoem = $this->admin_vodoem_model->parent_id();
                $parent_idvodoem['0'] = ' -- не выбрано -- ';
                foreach ($parentvodoem as $p) {
                    $parent_idvodoem[$p['id']] = $p['name'];
                }

                $data['vodoem'] = $parent_idvodoem;
                $data['infrostructura'] = $parent_idinfrostructura;
                $data['matherial'] = $parent_idmatherial;

                $map = explode('|', $dataRow['map']);
                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if ($bigFoto == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->link = $dataRow['link'];
                $data['rows']->text = $dataRow['text'];
                $data['rows']->adress = $dataRow['adress'];
                $data['rows']->tour = $dataRow['tour'];

                $data['rows']->otdelka = $dataRow['otdelka'];
                $data['rows']->infrastruct = $dataRow['infrastruct'];

                if (!empty($dataRow['price']) && $dataRow['price'] != 0) {
                    $price = '<span><span>' . $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . '</span> млн руб</span>';
                    $data['rows']->price_text = ' Стоимость квартиры квартирыю ' . $this->
                        number_format_drop_zero_decimals((($dataRow['price']) / 1000000), 1) .
                        ' млн руб';
                    $price_buildings = $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . ' млн. руб';
                    $seo_price = $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . ' млн. руб';
                } else {
                    $price = '<span>по запросу</span>';
                    $data['rows']->price_text = '';
                    $price_buildings = '<span>по запросу</span>';
                    $seo_price = 'по запросу';
                }

                $this->load->model('residential_object/admin_residential_object_model',
                    'admin_residential_object');
                $parentresobj = $this->admin_residential_object->parent_id();
                foreach ($parentresobj as $p) {
                    $parent_idresobj[$p['id']] = $p['name'];
                }
                $ssquare = (!empty($dataRow['square_dom'])) ? $dataRow['square_dom'] . ' кв. м.' :
                    '';
                $sland = (!empty($dataRow['square_uchastok'])) ? $dataRow['square_uchastok'] .
                    ' сот.' : '';

                $resobj = $parent_idresobj[$dataRow['res_type']];

                $lot = !empty($dataRow['square_uchastok']) ? 'Площадь участка ' . $dataRow['square_uchastok'] . ' соток' : '';
                $sq =  !empty($dataRow['square_dom']) ? ' Площадь дома ' . $dataRow['square_dom'] . '  кв м' : '';

                if (!empty($dataRow['otdalennost']))
                    $dataRow['otdalennost'] .= ' км';
                if (!empty($dataRow['square_dom']))
                    $dataRow['square_dom'] .= ' м<sup>2</sup>';
                if (!empty($dataRow['square_uchastok']))
                    $dataRow['square_uchastok'] .= ' сот';


                $sq = implode(', ', array($lot, $sq));


                $data['rows']->img_alt = "Фото дома Парголово по адресу {$dataRow['adress']}. {$sq} | М16 - продажа домов, коттеджей, участков недорого в СПб.";
                //Фото дома Парголово по адресу {$dataRow['adress']}. {$sq} | М16 - продажа домов, коттеджей, участков недорого в СПб.


                $data['rows']->options = array(
                    'Район' => $parent_idrayon[$dataRow['rayon_id']],
                    'Удален. от города' => $dataRow['otdalennost'],
                    'Наличие водоема' => $parent_idvodoem[$dataRow['vodoem_id']],
                    'Инфраструктруа' => $parent_idinfrostructura[$dataRow['infrostructura_id']],
                    'Площадь дома' => $dataRow['square_dom'],
                    'Площадь участка' => $dataRow['square_uchastok'],
                    'Материал дома' => $parent_idmatherial[$dataRow['matherial_id']],
                    'Рассрочка' => $dataRow['rassrochka'] ? 'Есть' : '',
                    'Газ' => $dataRow['z_gas'] ? 'Есть' : '',
                    'Водоснабжение' => $dataRow['z_water'] ? 'Есть' : '',
                    'Электричество' => $dataRow['z_electric'] ? 'Есть' : '',
                    'Адрес' => $dataRow['adress']);

                $srayon = $parent_idrayon[$dataRow['rayon_id']];

                //$yous[$key]['foto'] = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->mainfoto = thumbImage($bigFoto, $this->module);
                if ($dataRow['is_cottage'] == 1) {
                    $this->load->model('cottages/cottages_model', 'cottages_model');
                    $cott = $this->cottages_model->getCottagesByParent($dataRow['id']);
                    $cottages = array();
                    foreach ($cott as $kc => $vc) {
                        $cotarr = array();
                        $cotarr['name'] = $vc['name'];
                        $cotarr['foto'] = $vc['main_foto'];
                        if (!empty($vc['price']) && $vc['price'] != 0) {
                            $price = '<span>' . $this->number_format_drop_zero_decimals((($vc['price']) /
                                1000000), 1) . '</span> млн руб';
                        } else {
                            $price = 'по запросу';
                        }
                        $cotarr['land_square'] = $vc['land_square'];
                        $cotarr['house_square'] = $vc['house_square'];
                        $cotarr['price'] = $price;
                        $cotarr['is_house'] = $vc['is_house'];
                        if ($vc['is_house'] == 1) {
                            $data['rows']->houses = 1;
                        }
                        $cotarr['identity'] = $vc['identity'];
                        if ($vc['main_foto'] !== '/asset/img/logo-M16.png')
                            $cotarr['foto'] = $vc['main_foto'];
                        else {
                            $cotarr['foto'] = '';
                        }
                        $cotarr['link'] = BASEURL . '/' . $this->link . '/' . $dataRow['link'] . '/' . $vc['link'];
                        $cotarr['status'] = $vc['status'];
                        $cottages[] = $cotarr;
                    }
                    $data['rows']->cottages = $cottages;
                    $data['rows']->location = $dataRow['location'];
                    $data['rows']->cottages_square_land = $dataRow['cottages_square_land'];
                    $data['rows']->cottages_vodoem = $dataRow['cottages_vodoem'];
                    $data['rows']->cottages_count = $dataRow['cottages_count'];
                    $data['rows']->cottages_square = $dataRow['cottages_square'];
                    $data['rows']->rayon = $parent_idrayon[$dataRow['rayon_id']];
                    $data['rows']->plan = $dataRow['plan'];

                    $data['rows']->plan_photo = $dataRow['plan_photo'];

                    $minprcottages = $this->$model->cottageMinPrice($dataRow['id']);

                    if (!empty($minprcottages) && $minprcottages != 0) {
                        $price = '<span>от <span>' . $this->number_format_drop_zero_decimals((($minprcottages) /
                            1000000), 1) . '</span> млн руб</span>';
                        $data['rows']->price_text = ' Стоимость квартиры квартирыю от ' . $this->
                            number_format_drop_zero_decimals((($minprcottages) / 1000000), 1) . 'млн руб';
                        $price_buildings = $this->number_format_drop_zero_decimals((($minprcottages) /
                            1000000), 1) . ' млн. руб';
                        $seo_price = $this->number_format_drop_zero_decimals((($minprcottages) / 1000000),
                            1) . ' млн. руб';
                    } else {
                        $price = '<span>по запросу</span>';
                        $data['rows']->price_text = '';
                        $price_buildings = '<span>по запросу</span>';
                        $seo_price = 'по запросу';
                    }
                }
                $data['rows']->price = $price;

                $this->load->model('favorites/favorites_model', 'favorites_model');

                $favarray = array(
                    'object_id' => $dataRow['id'],
                    'category' => 'residential',
                    'name' => $dataRow['name'],
                    'foto' => $dataRow['mainfoto'],
                    'sort' => $dataRow['sort'],
                    'link' => $dataRow['link'],
                    'price' => $data['rows']->price);
                $idfs = $this->favorites_model->addToFavorites($favarray);
                $this->load->helper('cookie');
                $favorites = $this->input->cookie('favorites', true);
                $favorites = explode('.', $favorites);
                if (in_array($idfs, $favorites)) {
                    $data['in_favorites'] = true;
                }

                $sessi = $this->session->userdata('seen');
                if (!in_array($idfs, $sessi)) {
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                if ($this->session->userdata('seen')) {

                    $sessi = $this->session->userdata('seen');
                    if (!in_array($idfs, $sessi)) {
                        array_push($sessi, $idfs);
                        $this->session->set_userdata('seen', $sessi);
                    }

                } else {
                    $this->session->set_userdata('seen', array());
                    $sessi = array('0' => $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

                $data['refer'] = BASEURL . $this->getReferer('residential');

            } elseif ($uri == 'world') {

            } elseif ($uri == 'land') {
                $this->load->model('vodoprovod/admin_vodoprovod_model', 'admin_vodoprovod_model');
                $parentvodoprovod = $this->admin_vodoprovod_model->parent_id();
                $parent_idvodoprovod['0'] = ' -- не выбрано -- ';
                foreach ($parentvodoprovod as $p) {
                    $parent_idvodoprovod[$p['id']] = $p['name'];
                }

                $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                $parent_idtypesdelka['0'] = '-- не выбрано --';
                foreach ($parenttypesdelka as $p) {
                    $parent_idtypesdelka[$p['id']] = $p['name'];
                }

                $this->load->model('ruchastok/admin_ruchastok_model', 'admin_ruchastok_model');
                $parentruchastok = $this->admin_ruchastok_model->parent_id();
                $parent_idruchastok['0'] = ' -- не выбрано -- ';
                foreach ($parentruchastok as $p) {
                    $parent_idruchastok[$p['id']] = array('name' => $p['name'], 'mainfoto' => $p['mainfoto']);
                }

                $map = explode('|', $dataRow['map']);
                $this->loadPlugin[] = 'fancybox';

                $bigFoto = $dataRow['bigfoto'];
                if ($bigFoto == '/asset/img/logo-M16.png') {
                    $bigFoto = '/asset/assets/img/complex-bg.jpg';
                }

                $data['rows']->foto = unserialize($dataRow['foto']);
                $data['rows']->header = $dataRow['name'];
                $data['rows']->bigfoto = $bigFoto;
                $data['rows']->map_lat = str_replace(" ", "", $map[0]);
                $data['rows']->map_lng = str_replace(" ", "", $map[1]);
                $data['rows']->text = $dataRow['text'];
                $data['rows']->adress = $dataRow['adress'];
                if (!empty($dataRow['price']) && $dataRow['price'] != 0) {
                    $price = '<span><span>' . $this->number_format_drop_zero_decimals((($dataRow['price']) /
                        1000000), 1) . '</span> млн руб</span>';
                } else {
                    $price = '<span>по запросу</span>';
                }
                $data['rows']->price = $price;
                $data['rows']->options = array(
                    'Площадь' => $dataRow['square_zemlya'] . ' Га',
                    'Расстояние от КАД' => $dataRow['ot_kad'],
                    'Собственность' => $parent_idtypesdelka[$dataRow['typesdelka_id']],
                    'Элекстричество' => $dataRow['uc_electrika'] . 'кВт',
                    'Водоснабжение' => $parent_idvodoprovod[$dataRow['vodoprovod_id']],
                    'Газ' => $dataRow['uc_gas'] == 1 ? 'есть' : 'нет',
                    'Адрес' => $dataRow['adress']);
                $data['rows']->mainfoto = $dataRow['mainfoto'];
            }

            $data['rows']->ipoteka = $dataRow['ipoteka'] == 0 ? 'Нет' : 'Есть';
            $data['rows']->vid = $dataRow['vid'] == 0 ? 'Нет' : 'Есть';
            $data['rows']->club = $dataRow['club'] == 0 ? 'Нет' : 'Есть';
        }
        global $h;
        //$h->debug($dataRow['h1']);
        //переопределяем заголовок страницы
        if (isset($dataRow['h1']) && !empty($dataRow['h1'])) {
            $data['rows']->h1 = $dataRow['h1'];
        }

        // генерируем title, keywords, description
        $page_title = '';
        $page_description = '';
        $page_keywords = '';
        if ($uri == 'buildings') {
            if (!empty($dataRow['title'])) {
                $page_title = $dataRow['title'];
            } else {
                $page_title = 'Недвижимость в ЖК ' . $dataRow['name'] . ' у метро ' . $parent_idmetro[$dataRow['metro_id']] .
                    ', ' . $parent_idrayon[$dataRow['rayon_id']] . ' район - квартиры от ' . $price_buildings .
                    ' | Компания "М16-Недвижимость"';
            }

            if (!empty($dataRow['keywords'])) {
                $page_keywords = $dataRow['keywords'];
            } else {
                $dataRow['keywords'] = 'недвижимость ' . $dataRow['name'] . ', квартиры ' . $dataRow['name'] .
                    ', квартира ' . $dataRow['name'] . ' ' . $zastr . ', купить квартиру ' . $dataRow['name'];
            }

            if (!empty($dataRow['description'])) {
                $page_description = $dataRow['description'];
            } else {
                $page_description = 'Недвижимость в жилом комплексе ' . $dataRow['name'] .
                    ' у метро ' . $parent_idmetro[$dataRow['metro_id']] . ', ' . $parent_idrayon[$dataRow['rayon_id']] .
                    ' от застройщика ' . $zastr . '. Цены на квартиры от ' . $price_buildings .
                    ' Описание объекта, цены, фото, расположение.';
            }
			
        } elseif ($uri == 'resale') {

            if (!empty($dataRow['title'])) {
                $page_title = $dataRow['title'];
            } else {
                $page_title = 'Квартира на ' . $dataRow['adress'] . ' у метро ' . $parent_idmetro[$dataRow['metro_id']] .
                    ', ' . $parent_idrayon[$dataRow['rayon_id']] . ' район - цена ' . $price_buildings .
                    ' | Компания "М16-Недвижимость"';
            }
            if (!empty($dataRow['keywords'])) {
                $page_keywords = $dataRow['keywords'];
            } else {
                $page_keywords = 'квартира ' . $parent_idrayon[$dataRow['rayon_id']] .
                    ' район купить, квартира метро ' . $parent_idmetro[$dataRow['metro_id']] .
                    ' купить, квартира ' . str_replace(',', ' ', $dataRow['adress']) . ', квартира ' .
                    str_replace(',', ' ', $dataRow['adress']) . ' купить, квартира ' . str_replace(',',
                    ' ', $dataRow['adress']) . ' покупка, квартира ' . str_replace(',', ' ', $dataRow['adress']) .
                    ' продажа';
            }
            if (!empty($dataRow['description'])) {
                $page_description = $dataRow['description'];
            } else {
                $page_description = 'Квартира на ' . $dataRow['adress'] . ' у метро ' .
                    $parent_idmetro[$dataRow['metro_id']] . ', ' . $parent_idrayon[$dataRow['rayon_id']] .
                    ' район, количество комнат ' . $roomsc . ' шт., общей площадью ' . $dataRow['square_all'] .
                    ' кв. м. - цена ' . $price_buildings . '. Описание объекта, фото, расположение.';
            }
			
			//$data['rows']->h1 = 'Квартира на ' . $dataRow['adress'] . ' у метро ' . $parent_idmetro[$dataRow['metro_id']];
			
        } elseif ($uri == 'commercial') {

            if (!empty($dataRow['title'])) {
                $page_title = $dataRow['title'];
            } else {
                if ($commtype == 'Офис')
                    $commtyp = 'офиса';
                if ($commtype == 'Склад')
                    $commtyp = 'склада';
                $page_title = (!$sdd || count($sdd) == 0) ? '' : implode(" / ", $sssd) . ' ' . $commtyp .
                    ' на ' . $dataRow['adress'] . ' у метро ' . $smetro . ', ' . $srayon .
                    ' район - цена ' . $seo_price . ' | Компания "М16-Недвижимость"';
            }
            $data['rows']->img_alt = $page_title;
            if (!empty($dataRow['keywords'])) {
                $page_keywords = $dataRow['keywords'];
            } else {
                $sct = (!$sdd || count($sdd) == 0) ? '' : implode(" / ", $sssd);
                $page_keywords = mb_strtolower($commtype . ' ' . $srayon . ' район, ' . $sct . ' метро ' .
                    $smetro . ', ' . $commtype . ' ' . str_ireplace(',', ' ', $dataRow['adress']) .
                    ', ' . $commtype . ' ' . $srayon . ' район ' . $sct . ', ' . $commtype .
                    ' метро ' . $smetro . ' ' . $sct . ', ' . $commtype . ' ' . str_ireplace(',',
                    ' ', $dataRow['adress']) . ' ' . $sct);
            }
            if (!empty($dataRow['description'])) {
                $page_description = $dataRow['description'];
            } else {
                if ($commtype == 'Офис')
                    $commtyp = 'офиса';
                if ($commtype == 'Склад')
                    $commtyp = 'склада';
                $sct = (!$sdd || count($sdd) == 0) ? '' : implode(" / ", $sssd);
                $page_description = $sct . ' ' . $commtyp . ' на ' . $dataRow['adress'] . ' у метро ' . $smetro .
                    ', ' . $srayon . ' район - цена ' . $seo_price .
                    '. Описание объекта, фото и его месторасположение.';
            }
			
			//$data['rows']->h1 = (!$sdd || count($sdd) == 0) ? '' : implode(" / ", $sssd) . ' ' . $commtyp .
                    //' на ' . $dataRow['adress'] . ' у метро ' . $smetro;

        } elseif ($uri == 'residential') {

            if (!empty($dataRow['title'])) {
                $page_title = $dataRow['title'];
            } else {
                $page_title = $resobj . ' по адресу: ' . $dataRow['adress'] . ', ' . $srayon .
                    ' район - цена ' . $seo_price . ' | Компания "М16-Недвижимость"';
            }
            if (!empty($dataRow['keywords'])) {
                $page_keywords = $dataRow['keywords'];
            } else {
                $page_keywords = mb_strtolower($resobj . ' ' . $ssquare . ' ' . $srayon . ' район, ' . $resobj .
                    ' ' . $sland . ' ' . $srayon . ' район, ' . $resobj . ' ' . $srayon . ' район, ' .
                    $resobj . ' ' . $ssquare . ' ' . $srayon . ' район купить, ' . $resobj . ' ' . $ssquare .
                    ' ' . $srayon . ' район цены, ' . $resobj . ' ' . $sland . ' ' . $srayon .
                    ' район купить, ' . $resobj . ' ' . $sland . ' ' . $srayon . ' район продажа');
            }
            if (!empty($dataRow['description'])) {
                $page_description = $dataRow['description'];
            } else {
                $page_description = $resobj . ' по адресу: ' . $dataRow['adress'] . ', ' . $srayon .
                    ', с площадью дома ' . $ssquare . ' - цена ' . $seo_price .
                    '. Описание объекта, фото, расположение.';
            }
			
			//$data['rows']->h1 = $resobj . ' по адресу: ' . $dataRow['adress'] . ', ' . $srayon .
                    //' район';
			
        } elseif ($uri == 'elite' or $uri == 'exclusive') {
            if (!empty($dataRow['title'])) {
                $page_title = $dataRow['title'];
            } else {
                $page_title = $stype . ' на ' . $dataRow['adress'] . ' у метро ' . $smetro . ', ' . $srayon .
                    ' район - цена ' . $seo_price . ' | Компания "М16-Недвижимость"';
            }
            if (!empty($dataRow['keywords'])) {
                $page_keywords = $dataRow['keywords'];
            } else {
                $page_keywords = mb_strtolower($stype . ' ' . $srayon . ' район купить, ' . $stype .
                    ' метро ' . $smetro . ' купить, ' . $stype . ' ' . str_ireplace(',', ' ', $dataRow['adress']) .
                    ', ' . $stype . ' ' . str_ireplace(',', ' ', $dataRow['adress']) . ' купить, ' .
                    $stype . ' ' . str_ireplace(',', ' ', $dataRow['adress']) . ' покупка, ' . $stype .
                    ' ' . str_ireplace(',', ' ', $dataRow['adress']) . ' продажа');
            }
            if (!empty($dataRow['description'])) {
                $page_description = $dataRow['description'];
            } else {

                $page_description = $stype . ' на ' . $dataRow['adress'] . ' у метро ' . $smetro . ', ' . $srayon .
                    ' район, ' . $sroom . ', ' . $ssquare . ' - цена ' . $seo_price .
                    '. Описание объекта, фото, расположение.';
            }
        } else {
            $page_title = $dataRow['title'];
            $page_keywords = $dataRow['keywords'];
            $page_description = $dataRow['description'];
        }

        $this->addVar('title', $page_title);
        $this->addVar('keywords', $page_keywords);
        $this->addVar('description', $page_description);

        $site_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $size = getimagesize($site_url . $data['rows']->foto['foto'][0]);
        $w = $size[0];
        $h = $size[1];

        $og['title'] = $page_title;
        $og['description'] = $page_description;
        $og['image'] = $data['rows']->foto['foto'][0];
        $og['width'] = $w;
        $og['height'] = $h;


		$dt = new DateTime();
		$dt = $dt->setTimestamp($dataRow['date_add']);
        $date = $dt->format('Y-m-d');
        $time = $dt->format("h:i:s");
        $article_published = $date.'T'.$time.'+00:00';

        $this->addVar('article_published', $article_published);
		$this->addVar('OG', $og);

        // задаем крохи
        if (!empty($this->lang->md_breadcrumbs)) {
            $brd = $this->lang->md_breadcrumbs;
        } else {
            $brd = $this->lang->md_header;
        }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $residential_view = 'residential_view';
        if ($uri == 'residential' && $dataRow['is_cottage'] == '1')
            $residential_view = 'cottages_view';

        $this->data['MR'] = $this->createObjectMikroMarks($uri, $dataRow);

        $uriTemplate = array(
            'buildings' => 'apartments',
            'resale' => 'resale_view',
            'assignment' => 'assignment_view',
            'residential' => $residential_view,
            'elite' => $eliteORresale,
            'exclusive' => 'exclusive_view',
            'commercial' => 'commercial_view',
            'land' => 'land_view',
            'world' => 'apartments');


        $this->addVar('template', $this->render($uriTemplate[$uri], $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }
	


    /**
     * генерация микроразметки
     * @param string $uri текущий раздел
     * @param null $dataRow строка объекта из БД
     * @return null|string
     */
    private function createObjectMikroMarks($uri = '', $dataRow = null)
    {
        //exit($uri);
        if (empty($uri) || empty($dataRow)) {
            return null;
        }
        $mr = new StdClass;
        $mr->{'@context'} = "http://schema.org";

        $add = new StdClass;
        $add->{'@type'} = "PostalAddress";
        $add->{'streetAddress'} = $dataRow['adress'];
        if (isset($this->districtsnameArray['rayon_id'])) {
            $add->{'addressLocality'} = $this->districtsnameArray['rayon_id'];
        }

        if ($uri == 'buildings') {

            $mr->{'@type'} = "Place";
            $mr->{'name'} = "Жилой комплекс".$dataRow['name'];

            if (!empty($add)) {
                $mr->{'address'} = $add;
            }
            $ph = new StdClass;

            $ph->{'@type'} = "ImageObject";

            $ph_ = unserialize($dataRow['foto']);

            if (isset($ph_['foto'][0])) {
                $ph->{"url"} = $ph_['foto'][0];
            } else {
                $ph->{"url"} = $dataRow['bigfoto'];
            }
            $mr->{'photo'} = $ph;
            return json_encode($mr);
        } else {

            $place = new StdClass;
            $offer = new StdClass;

            $place->{'@type'} = "Place";
            $place->{'name'} = $dataRow['name'];
            if (!empty($add)) {
                $place->{'address'} = $add;
            }

            $offer->{"@type"} = "Offer";
            $offer->{"price"} = (int)$dataRow['price'];
            $offer->{"priceCurrency"} = "RUB";
            $offer->{"url"} = "/" . $uri . "/" . $dataRow['link'];

            $mr->{"@graph"} = [$place, $offer];
            //dump($mr);
            return json_encode($mr);
        }
         return null;
    }

    /** Поиск */
    function searchFunction($searchss = '') {
        $startTime = microtime();
        $search = urldecode(uri(3));

        if ($this->input->post('search', true)) {
            $search = $this->input->post('search', true);
            //$this->session->set_userdata('search_', $search);
            if (!empty($search)) {
                redirect('/' . $this->link . '/search/' . $search);
            }
        }

        $this->setOptions(); // заносим опции в переменные

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        // генерируем title, keywords, description
        $this->addVar('title', 'Поиск недвижимости');
        $data['lang']->md_title = 'Поиск недвижимости';

        //if(empty($search)) {
        //		$search = $this->session->userdata('search_');
        //}

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        if (!empty($search)) {
            $dataRow = $this->$model->paginations(uri(4), '', '', $search); // вытаскиваем данные
        }

        if ($dataRow) // проверяем есть ли данные
            {
            $uriArray = array(
                'buildings' => 0,
                'resale' => 1,
                'residential' => 2,
                'elite' => 3,
                'exclusive' => 9,
                'commercial' => 4,
                'land' => 6,
                'world' => 5);

            $uriArrayReverse = array(
                'buildings',
                'resale',
                'residential',
                'elite',
                'exclusive',
                'commercial',
                'world',
                'land');

            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_counts(false, '', '', $params, $search),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => 'buildings/search/' . $search,
                'uri_segment' => 4,
                'num_links' => 2,
                'get' => '');

            $data['pagination'] = $this->paginations($arrayPagination);

            foreach ($dataRow as $key => $value) {
                $expa = explode(',', $value['razdelu']);
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL . '/' . $uriArrayReverse[$expa[0]] . '/' . $value['link'];
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
            }
        }

        $this->addVar('template', $this->render('search', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
        $endTime = microtime();
        echo $endTime - $startTime;
    }

    function dataKv($dt, $mini = true) {
        $newTime = strtotime(date('d.m.Y H:i:s'));
        if ($dt < $newTime) {
            $return = 'Сдан';
        } else {
            $ex = explode('.', date('d.m.Y', $dt));

            switch ($ex[1]) {
                case '01':
                case '02':
                case '03':
                    $r = 'I';
                    break;
                case '04':
                case '05':
                case '06':
                    $r = 'II';
                    break;
                case '07':
                case '08':
                case '09':
                    $r = 'III';
                    break;
                case '10':
                case '11':
                case '12':
                    $r = 'IV';
                    break;
            }

            $return = $r . (($mini) ? ' кв. ' : ' квартал ') . $ex[2];
        }

        return $return;
    }

    public function getFilterBuildings() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        // Метро
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        // Район
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        // Застройщик
        $this->load->model('builders/builders_model', 'builders_model');
        $parentbuilders = $this->builders_model->getAllFilter();
        foreach ($parentbuilders as $p)
            $parent_idbuilders[$p['id']] = $p['name'];

        // Тип
        $this->load->model('type/type_model', 'type_model');
        $parenttype = $this->type_model->getAllFilter();
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        // Тип комнат
        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $parent_id_room[$p['id']] = $p['name'];

        // Класс
        $this->load->model('buildings_class/buildings_class_model',
            'buildings_class_model');
        $parent_class = $this->buildings_class_model->getAllFilter();
        foreach ($parent_class as $p) {
            $parent_id_class[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxBuildings();
        $minMax = $this->getMinMaxValuesBuildings($minMax);

        $minMaxS = $this->$model->getMinMaxBuildingsSquare();
        $minMaxS = $this->getMinMaxValuesBuildingsS($minMaxS);

        $data = array();
        $data['filter'] = (object)array();
        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->builder = $parent_idbuilders;
        $data['filter']->class = $parent_id_class;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'building_filter', $data);

    }

    public function getNewFilterBuildings() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        // Метро
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        // Район
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        // Застройщик
        $this->load->model('builders/builders_model', 'builders_model');
        $parentbuilders = $this->builders_model->getAllFilter();
        foreach ($parentbuilders as $p)
            $parent_idbuilders[$p['id']] = $p['name'];

        // Тип
        $this->load->model('type/type_model', 'type_model');
        $parenttype = $this->type_model->getAllFilter();
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        // Тип комнат
        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $parent_id_room[$p['id']] = $p['name'];

        // Класс
        $this->load->model('buildings_class/buildings_class_model',
            'buildings_class_model');
        $parent_class = $this->buildings_class_model->getAllFilter();
        foreach ($parent_class as $p) {
            $parent_id_class[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxBuildings();
        $minMax = $this->getMinMaxValuesBuildings($minMax);

        $minMaxS = $this->$model->getMinMaxBuildingsSquare();
        $minMaxS = $this->getMinMaxValuesBuildingsS($minMaxS);

        $data = array();
        $data['filter'] = (object)array();
        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->builder = $parent_idbuilders;
        $data['filter']->class = $parent_id_class;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'newbuilding_filter', $data);

    }
    public function getNewFilterBuildings1() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        // Метро
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p)
            $parent_idmetro[$p['id']] = $p['name'];

        // Район
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p)
            $parent_idrayon[$p['id']] = $p['name'];

        // Застройщик
        $this->load->model('builders/builders_model', 'builders_model');
        $parentbuilders = $this->builders_model->getAllFilter();
        foreach ($parentbuilders as $p)
            $parent_idbuilders[$p['id']] = $p['name'];

        // Тип
        $this->load->model('type/type_model', 'type_model');
        $parenttype = $this->type_model->getAllFilter();
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        // Тип комнат
        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p)
            $parent_id_room[$p['id']] = $p['name'];

        // Класс
        $this->load->model('buildings_class/buildings_class_model',
            'buildings_class_model');
        $parent_class = $this->buildings_class_model->getAllFilter();
        foreach ($parent_class as $p) {
            $parent_id_class[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxBuildings();
        $minMax = $this->getMinMaxValuesBuildings($minMax);

        $minMaxS = $this->$model->getMinMaxBuildingsSquare();
        $minMaxS = $this->getMinMaxValuesBuildingsS($minMaxS);

        $data = array();
        $data['filter'] = (object)array();
        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->builder = $parent_idbuilders;
        $data['filter']->class = $parent_id_class;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'newbuildingg_filter', $data);

    }

    public function getFilterBuildingsTest() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxBuildings();
        $minMax = $this->getMinMaxValuesBuildings($minMax);

        $data = array();

        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->minMax = $minMax;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'building_filter_test',
            $data);

    }

    public function getFilterResale() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxResale();
        $minMax = $this->getMinMaxValuesResale($minMax);

        $minMaxS = $this->$model->getMinMaxResaleSquare();
        $minMaxS = $this->getMinMaxValuesResaleS($minMaxS);

        $data = array();

        $data['filter'] = new stdClass;
        $data['filter']->metro = isset($parent_idmetro) ? $parent_idmetro : '';
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'resale_filter', $data);

    }

    public function getFilterAssignment() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        // Метро
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        // Район
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        // Застройщик
        $this->load->model('builders/builders_model', 'builders_model');
        $parentbuilders = $this->builders_model->getAllFilter(8);
        foreach ($parentbuilders as $p)
            $parent_idbuilders[$p['id']] = $p['name'];

        // Тип
        $this->load->model('type/type_model', 'type_model');
        $parenttype = $this->type_model->getAllFilter(8);
        foreach ($parenttype as $p)
            $parent_idtype[$p['id']] = $p['name'];

        // Комнатность
        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        // Класс
        $this->load->model('buildings_class/buildings_class_model',
            'buildings_class_model');
        $parent_class = $this->buildings_class_model->getAllFilter(8);
        foreach ($parent_class as $p) {
            $parent_id_class[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxPrice(8);
        $minMax = $this->getMinMaxValues($minMax);

        $minMaxS = $this->$model->getMinMaxSquare(8);
        $minMaxS = $this->getMinMaxValuesS($minMaxS);

        $data = array();
        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->class = $parent_id_class;
        $data['filter']->builder = $parent_idbuilders;
        $data['filter']->room = $parent_id_room;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'assignment_filter', $data);
    }

    public function getFilterResidential() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/rayon_model', 'rayon_model');
        $parentrayon = $this->rayon_model->getAllFilterResidential();
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }
        $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach ($parenttypesdelka as $p) {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }
        $data['sdelka'] = $parent_idtypesdelka;

        $this->load->model('infrostructura/admin_infrostructura_model',
            'admin_infrostructura_model');
        $parentinfrostructura = $this->admin_infrostructura_model->parent_id();
        $parent_idinfrostructura['0'] = ' -- не выбрано -- ';
        foreach ($parentinfrostructura as $p) {
            $parent_idinfrostructura[$p['id']] = $p['name'];
        }

        $this->load->model('matherial/matherial_model', 'matherial_model');
        $parentmatherial = $this->matherial_model->getAllFastFilter();
        foreach ($parentmatherial as $p) {
            $parent_idmatherial[$p['id']] = $p['name'];
        }

        $this->load->model('vodoem/admin_vodoem_model', 'admin_vodoem_model');
        $parentvodoem = $this->admin_vodoem_model->parent_id();
        $parent_idvodoem['0'] = ' -- не выбрано -- ';
        foreach ($parentvodoem as $p) {
            $parent_idvodoem[$p['id']] = $p['name'];
        }

        $this->load->model('residential_object/residential_object_model',
            'residential_object_model');
        $parentobj = $this->residential_object_model->getAllFastFilter();
        foreach ($parentobj as $p) {
            $parent_idobj[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxResidential();
        $minMax = $this->getMinMaxValuesResidential($minMax);

        $minMaxS = $this->$model->getMinMaxResidentialSquare();
        $minMaxS = $this->getMinMaxValuesResidentialS($minMaxS);

        $minMaxSS = $this->$model->getMinMaxResidentialSquareUch();
        $minMaxSS = $this->getMinMaxValuesResidentialSS($minMaxSS);

        $data = array();

        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idobj;
        $data['filter']->room = $parent_id_room;
        $data['filter']->vodoem = $parent_idvodoem;
        $data['filter']->matherial = $parent_idmatherial;
        $data['filter']->infrostructura = $parent_idinfrostructura;
        $data['filter']->typesdelka = $parent_idtypesdelka;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;
        $data['filter']->minMaxSS = $minMaxSS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'residential_filter',
            $data);
    }

    public function countFind() {
        $this->load->model('buildings/buildings_model', 'bmodel');
        $filter = unserialize($_REQUEST['filter']);
        parse_str($_REQUEST['filter'], $filter);
        $uri = $this->data['uri1'];

        $find = str_replace('http://m16-estate.ru/', '', $_SERVER['HTTP_REFERER']);

        if ($uri == 'buildings') {
            $minmax = $this->bmodel->getMinMaxBuildings();
            $minmax = $this->getMinMaxValuesBuildings($minmax);
            $minmaxs = $this->bmodel->getMinMaxBuildingsSquare();
            $minmaxs = $this->getMinMaxValuesBuildingsS($minmaxs);
            $m = array(
                'min' => $minmax['min'],
                'max' => $minmax['max'],
                'mins' => $minmaxs['min'],
                'maxs' => $minmaxs['max']);

            $count = $this->bmodel->findCount(0, $filter, $m, $find);
        }

        if ($uri == 'resale')
            $count = $this->bmodel->findCount(1, $filter, null, $find);

        if ($uri == 'assignment')
            $count = $this->bmodel->findCount(8, $filter);

        if ($uri == 'residential')
            $count = $this->bmodel->findCount(2, $filter);

        if ($uri == 'elite')
            $count = $this->bmodel->findCount(3, $filter);

        if ($uri == 'exclusive')
            $count = $this->bmodel->findCount(9, $filter);

        if ($uri == 'commercial')
            $count = $this->bmodel->findCount(4, $filter, null, $find);

        echo $count;
    }
    public function isBuilding($name){
        $this->load->model('buildings/buildings_model', 'bmodel');
        $count = $this->bmodel->isBuld($name);
        return $count;
    }
    public function getFilterElite() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('elite_type/elite_type_model', 'elite_type_model');
        $parentelitetype = $this->elite_type_model->getAllFastFilter();
        foreach ($parentelitetype as $p) {
            $parent_idelitetype[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $data = array();

        $minMax = $this->$model->getMinMaxElite();
        $minMax = $this->getMinMaxValuesElite($minMax);

        $minMaxS = $this->$model->getMinMaxEliteSquare();
        $minMaxS = $this->getMinMaxValuesEliteS($minMaxS);

        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->elite_type = $parent_idelitetype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'elite_filter', $data);

    }

    // Эксклюзивная -> фильтры
    public function getFilterExclusive() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model', 'admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $query = $this->db->query("
			SELECT DISTINCT `ci_elite_type`.*
			FROM (`ci_elite_type`)
			INNER JOIN `ci_buildings` ON `ci_elite_type`.`id` = `ci_buildings`.`elite_type`
			WHERE `ci_elite_type`.`banned` =  0
			AND `ci_buildings`.`banned` =  0
			AND  `ci_buildings`.`razdelu`  LIKE '%9%'
			ORDER BY `ci_elite_type`.`name` ASC
		");
        foreach ($query->result_array() as $p) {
            $parent_idelitetype[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $data = array();

        $minMax = $this->$model->getMinMaxExclusive();
        $minMax = $this->getMinMaxValuesExclusive($minMax);

        $minMaxS = $this->$model->getMinMaxExclusiveSquare();
        $minMaxS = $this->getMinMaxValuesExclusiveS($minMaxS);

        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->elite_type = $parent_idelitetype;
        $data['filter']->room = $parent_id_room;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'exclusive_filter', $data);

    }

    public function getFilterCommercial() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('commercial_type/commercial_type_model',
            'commercial_type_model');
        $parenttype = $this->commercial_type_model->getAllFastFilter();
        foreach ($parenttype as $p) {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('typesdelka/typesdelka_model', 'typesdelka_model');
        $parenttypesdelka = $this->typesdelka_model->getAllFastFilter();
        foreach ($parenttypesdelka as $p) {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }
        $data['sdelka'] = $parent_idtypesdelka;

        $this->load->model('room/admin_room_model', 'admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach ($parent_room as $p) {
            $parent_id_room[$p['id']] = $p['name'];
        }

        $this->load->model('commercial_vid/commercial_vid_model', 'commercial_vid');
        $parent_vid = $this->commercial_vid->getAllFastFilter();
        foreach ($parent_vid as $p) {
            $parent_id_vid[$p['id']] = $p['name'];
        }

        $this->load->model('commercial_pay/commercial_pay_model', 'commercial_pay');
        $parent_pay = $this->commercial_pay->getAllFastFilter();
        foreach ($parent_pay as $p) {
            $parent_id_pay[$p['id']] = $p['name'];
        }

        $this->load->model('commercial_bussines/commercial_bussines_model',
            'commercial_bussines');
        $parent_bussines = $this->commercial_bussines->getAllFastFilter();
        foreach ($parent_bussines as $p) {
            $parent_id_bussines[$p['id']] = $p['name'];
        }

        $this->load->model('commercial_forwhat/commercial_forwhat_model',
            'commercial_forwhat');
        $parent_forwhat = $this->commercial_forwhat->getAllFastFilter();
        foreach ($parent_forwhat as $p) {
            $parent_id_forwhat[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMaxCommercial();
        $minMax = $this->getMinMaxValuesCommercial($minMax);

        $minMaxS = $this->$model->getMinMaxCommercialSquare();
        $minMaxS = $this->getMinMaxValuesCommercialS($minMaxS);

        $data = array();

        $data['filter']->metro = $parent_idmetro;
        $data['filter']->rayon = $parent_idrayon;
        $data['filter']->type = $parent_idtype;
        $data['filter']->sdelka = $parent_idtypesdelka;
        $data['filter']->room = $parent_id_room;
        $data['filter']->vid = $parent_id_vid;
        $data['filter']->pay = $parent_id_pay;
        $data['filter']->forwhat = $parent_id_forwhat;
        $data['filter']->bussines = $parent_id_bussines;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'commercial_filter', $data);

    }

    function number_format_drop_zero($n, $delimiter = ',') {
        $n = round($n, 1, PHP_ROUND_HALF_DOWN);
        $n = number_format($n, 1, $delimiter, '');
        $ex = explode(',', $n);
        if (count($ex) > 1 && (int)$ex[1] == 0)
            $n = (int)$n;
        return $n;
    }

    function getMinMaxValues($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];

        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');

        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');

        return $minmax;
    }

    function getMinMaxValuesS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];

        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');

        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');

        return $minmax;
    }

    function getMinMaxValuesResale($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesResaleS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesBuildings($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesBuildingsS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesResidential($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesResidentialS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesResidentialSS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesElite($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesEliteS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesExclusive($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesExclusiveS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesCommercial($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'] / 1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'] / 1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesCommercialS($minmax) {
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero($minmax['max'], '.');
        return $minmax;
    }

    function metroPage($metro_link, $page = 0) {
        //global $h;
        $category = $this->data['uri1'];

        $this->setOptions();

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('metro/metro_model', 'metro_model');
        $metro = $this->metro_model->getByLink($metro_link);

        if (empty($metro)) {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
			return;
        }

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }
        $htag = '';
        switch ($category) {
            case 'buildings':
                $dataRow = $this->model->metroFilter($category, $metro['id'], $page); // вытаскиваем данные
                //$h->debug($dataRow);
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['virtual_price']) && $v['virtual_price'] != 0) {
                        $price .= 'от <span>' . $this->number_format_drop_zero_decimals((($v['virtual_price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = ($v['korpus'] == '2') ? 'Собственность' : $this->
                        dataKv($v['korpus_value']);
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $metro['buildings_title']);
                $this->addVar('keywords', $metro['buildings_keywords']);
                $this->addVar('description', $metro['buildings_description']);
                $data['seotext'] = $metro['buildings_seotext'];
                $htag = $metro['buildings_htag'];

                $data['filterFunc'] = 'getFilterBuildings';
                $data['moduleName'] = 'buildings';
                break;
            case 'resale':
                $dataRow = $this->model->metroFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                //dump($metro['resale_title']);
                $data['filterFunc'] = 'getFilterResale';
                $data['moduleName'] = 'buildings';
                $this->addVar('title', $metro['resale_title']);
                $this->addVar('keywords', $metro['resale_keywords']);
                $this->addVar('description', $metro['resale_description']);
                $data['seotext'] = $metro['resale_seotext'];
                $htag = $metro['resale_htag'];

                break;

            case 'assignment':
                $dataRow = $this->model->metroFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $data['filterFunc'] = 'getFilterAssignment';
                $data['moduleName'] = 'buildings';
                $this->addVar('title', $metro['assignment_title']);
                $this->addVar('keywords', $metro['assignment_keywords']);
                $this->addVar('description', $metro['assignment_description']);
                $data['seotext'] = $metro['assignment_seotext'];
                $htag = $metro['assignment_htag'];
                break;
            default:
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
				return;
        }
        $data['htag'] = $htag;
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category . '/' . $metro_link,
            'uri_segment' => 3,
            'num_links' => 2);
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);
        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    private function getPageTitleByDeltiminer($title) {
        $title = explode('|', $title);
        return trim($title[0]);
    }

    function rayonPage($metro_link, $page = 0) {

        $category = $this->data['uri1'];

        $this->setOptions();

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('rayon/rayon_model', 'rayon_model');
        $rayon = $this->rayon_model->getByLink($metro_link);

        if (empty($rayon)) {
            // если не нашлось записи, пробуем найти метро
            $this->metroPage($metro_link);
			return;
            //show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');

        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }
        $htag = '';
        switch ($category) {
            case 'buildings':
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['virtual_price']) && $v['virtual_price'] != 0) {
                        $price .= 'от <span>' . $this->number_format_drop_zero_decimals((($v['virtual_price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = ($v['korpus'] == '2') ? 'Собственность' : $this->
                        dataKv($v['korpus_value']);
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['buildings_title']);
                $this->addVar('keywords', $rayon['buildings_keywords']);
                $this->addVar('description', $rayon['buildings_description']);
                $data['seotext'] = $rayon['buildings_seotext'];
                $htag = $rayon['buildings_htag'];
                $data['filterFunc'] = 'getFilterBuildings';
                $data['moduleName'] = 'buildings';
                break;
            case 'resale':

                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                //var_dump($metro['resale_title']);
                $this->addVar('title', $rayon['resale_title']);
                $this->addVar('keywords', $rayon['resale_keywords']);
                $this->addVar('description', $rayon['resale_description']);
                $data['seotext'] = $rayon['resale_seotext'];
                $htag = $rayon['resale_htag'];
                $data['filterFunc'] = 'getFilterResale';
                $data['moduleName'] = 'buildings';
                break;

            case 'assignment':
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['assignment_title']);
                $this->addVar('keywords', $rayon['assignment_keywords']);
                $this->addVar('description', $rayon['assignment_description']);
                $data['seotext'] = $rayon['assignment_seotext'];
                $htag = $rayon['assignment_htag'];
                $data['filterFunc'] = 'getFilterAssignment';
                $data['moduleName'] = 'buildings';
                break;

            case 'residential':
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['residential_title']);
                $this->addVar('keywords', $rayon['residential_keywords']);
                $this->addVar('description', $rayon['residential_description']);
                $data['seotext'] = $rayon['residential_seotext'];

                $htag = $rayon['residential_htag'];
                $data['filterFunc'] = 'getFilterResidential';
                $data['moduleName'] = 'buildings';
                break;
            case 'elite':
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $data['rows']->$key->price = $v['price_for_meter'];
                    $data['rows']->$key->prices = $v['price'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                    if ($data['rows']->$key->price > 0 or $data['rows']->$key->prices > 0) {
                        if ($data['rows']->$key->price > 0) {
                            $pri = 'от <span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                price) / 1000), 1) . '</span> тыс руб/м<sup>2</sup>';
                        } else {
                            $pri = '<span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                prices) / 1000000), 1) . '</span> млн руб';
                        }
                    } else {
                        $pri = 'по запросу';
                    }
                    $data['rows']->$key->price = $pri;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['elite_title']);
                $this->addVar('keywords', $rayon['elite_keywords']);
                $this->addVar('description', $rayon['elite_description']);
                $data['seotext'] = $rayon['elite_seotext'];
                $htag = $rayon['elite_htag'];
                $data['filterFunc'] = 'getFilterElite';
                $data['moduleName'] = 'buildings';
                break;

            case 'exclusive':
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                //dump($dataRows);
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $data['rows']->$key->price = $v['price_for_meter'];
                    $data['rows']->$key->prices = $v['price'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                    if ($data['rows']->$key->price > 0 or $data['rows']->$key->prices > 0) {
                        if ($data['rows']->$key->price > 0) {
                            $pri = 'от <span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                price) / 1000), 1) . '</span> тыс руб/м<sup>2</sup>';
                        } else {
                            $pri = '<span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                prices) / 1000000), 1) . '</span> млн руб';
                        }
                    } else {
                        $pri = 'по запросу';
                    }
                    $data['rows']->$key->price = $pri;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['elite_title']);

                $this->addVar('keywords', $rayon['elite_keywords']);
                $this->addVar('description', $rayon['elite_description']);
                $data['seotext'] = $rayon['elite_seotext'];
                $htag = $rayon['elite_htag'];
                $data['filterFunc'] = 'getFilterExclusive';
                $data['moduleName'] = 'buildings';
                break;

            case 'commercial':
                $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
                $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
                foreach ($parenttypesdelka as $p) {
                    $parent_idtypesdelka[$p['id']] = $p['name'];
                }
                $dataRow = $this->model->rayonFilter($category, $rayon['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $sdd = $this->model->getSdelka($v['id']);
                    $sssd = array();
                    if ($sdd && count($sdd) > 0) {
                        foreach ($sdd as $vv) {
                            $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                        }
                    }
                    $data['rows']->$key->price = $this->number_format_drop_zero_decimals($v['price'] /
                        1000000, 1);
                    $data['rows']->$key->price_arenda = $this->number_format_drop_zero_decimals($v['price_arenda'] /
                        1000, 1);
                    if ($data['rows']->$key->price > 0 && $data['rows']->$key->price_arenda > 0) {
                        $price = '<span>' . $data['rows']->$key->price . '</span> млн руб / <span>' . $data['rows']->
                            $key->price_arenda . '</span> тыс руб';
                    } elseif ($data['rows']->$key->price > 0) {
                        $price = '<span>' . $data['rows']->$key->price . '</span> млн руб';
                    } elseif ($data['rows']->$key->price_arenda > 0) {
                        $price = '<span>' . $data['rows']->$key->price_arenda . '</span> тыс руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = (!$sdd || count($sdd) == 0) ? 'не указано' : implode(" / ",
                        $sssd);
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $this->addVar('title', $rayon['commercial_title']);
                $this->addVar('keywords', $rayon['commercial_keywords']);
                $this->addVar('description', $rayon['commercial_description']);
                $data['seotext'] = $rayon['commercial_seotext'];
                $htag = $rayon['commercial_htag'];
                $data['filterFunc'] = 'getFilterCommercial';
                $data['moduleName'] = 'buildings';
                break;
            default:
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }
        $data['htag'] = $htag;
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category . '/' . $metro_link,
            'uri_segment' => 3,
            'num_links' => 2);

        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);
        // $this->output->cache(10);
        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    function resobjectPage($metro_link, $page = 0) {
        $category = $this->data['uri1'];
        $this->setOptions();

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('residential_object/residential_object_model',
            'residential_object');
        $metro = $this->residential_object->getByLink($metro_link);
        if (empty($metro)) {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }
        switch ($category) {
            case 'residential':
                $dataRow = $this->model->resobjectFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $price = '';
                    if (!empty($v['price']) && $v['price'] != 0) {
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
				if((!isset($metro['title']) or !isset($metro['description']) 
					or !isset($metro['keywords']) or !isset($metro['seotext'])) 
						&&( strpos($_SERVER['REQUEST_URI'],'dom') or strpos($_SERVER['REQUEST_URI'],'zemelnyj-uchastok')
							or strpos($_SERVER['REQUEST_URI'],'kottedzh') or strpos($_SERVER['REQUEST_URI'],'taunhaus'))){
							$seoblock=$this->model->seoblock(substr($_SERVER['REQUEST_URI'],13,strlen($_SERVER['REQUEST_URI'])-13));
							//echo'<pre>';print_r($seoblock);echo'</pre>';
							$this->addVar('title', $seoblock[0]['title']);
							$this->addVar('description', $seoblock[0]['description']);
							$this->addVar('keywords', $seoblock[0]['keywords']);
							$data['htag'] = $seoblock[0]['htag'];
							$data['seotext'] = $seoblock[0]['seo_text'];
					
				}else{
					$this->addVar('title', $metro['title']);
					$this->addVar('description', $metro['description']);
					$this->addVar('keywords', $metro['keywords']);
					$data['htag'] = $metro['title'];
					$data['seotext'] = $metro['seotext'];
				}
                $data['filterFunc'] = 'getFilterResidential';
                $data['moduleName'] = 'buildings';
                break;
            default:
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category . '/' . $metro_link,
            'uri_segment' => 3,
            'num_links' => 2);
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);
        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }
	

    function elitetypePage($metro_link, $page = 0) {
        $category = $this->data['uri1'];
        $this->setOptions();

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('elite_type/elite_type_model', 'elite_type');
        $metro = $this->elite_type->getByLink($metro_link);

        if (empty($metro)) {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }
        switch ($category) {
            case 'elite':
            case 'exclusive':
                $dataRow = $this->model->elitetypeFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];

                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $data['rows']->$key->price = $v['price_for_meter'];
                    $data['rows']->$key->prices = $v['price'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                    if ($data['rows']->$key->price > 0 or $data['rows']->$key->prices > 0) {
                        if ($data['rows']->$key->price > 0) {
                            $pri = 'от <span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                price) / 1000), 1) . '</span> тыс руб/м<sup>2</sup>';
                        } else {
                            $pri = '<span>' . $this->number_format_drop_zero_decimals((($data['rows']->$key->
                                prices) / 1000000), 1) . '</span> млн руб';
                        }
                    } else {
                        $pri = 'по запросу';
                    }
                    $data['rows']->$key->price = $pri;
                    $data['rows']->$key->srok = '';
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                    $data['filterFunc'] = 'getFilterElite';
                    $data['moduleName'] = 'buildings';
                }

                if ($category == 'exclusive') {
                    $data['filterFunc'] = 'getFilterElite';
                    $data['moduleName'] = 'buildings';
                }

                $this->addVar('title', $metro['title']);
                $this->addVar('keywords', $metro['keywords']);
                $this->addVar('description', $metro['description']);
                $data['seotext'] = $metro['seotext'];
                $data['htag'] = $metro['htag'];
                break;
            default:
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category . '/' . $metro_link,
            'uri_segment' => 3,
            'num_links' => 2);
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);
        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    function typesdelkaPage($metro_link, $page = 0) {

        $category = $this->data['uri1'];
        $this->setOptions();

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('typesdelka/typesdelka_model', 'typesdelka');
        $metro = $this->typesdelka->getByLink($metro_link);
        if (empty($metro)) {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }

        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach ($parentmetro as $p) {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach ($parentrayon as $p) {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
        $parenttypesdelka = $this->admin_typesdelka_model->parent_id();
        foreach ($parenttypesdelka as $p) {
            $parent_idtypesdelka[$p['id']] = $p['name'];
        }
        switch ($category) {
            case 'commercial':
                $dataRow = $this->model->typesdelkaFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach ($dataRows as $key => $v) {
                    $data['rows']->$key->metro = $parent_idmetro[$v['metro_id']];
                    $data['rows']->$key->rayon = $parent_idrayon[$v['rayon_id']] . ' район';
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->adress = $v['adress'];
                    $sdd = $this->model->getSdelka($v['id']);
                    $sssd = array();
                    if ($sdd && count($sdd) > 0) {
                        foreach ($sdd as $vv) {
                            $sssd[] = $parent_idtypesdelka[$vv['typesdelka_id']];
                        }
                    }
                    $data['rows']->$key->price = $this->number_format_drop_zero_decimals($v['price'] /
                        1000000, 1);
                    $data['rows']->$key->price_arenda = $this->number_format_drop_zero_decimals($v['price_arenda'] /
                        1000, 1);
                    if ($data['rows']->$key->price > 0 && $data['rows']->$key->price_arenda > 0) {
                        $price = '<span>' . $data['rows']->$key->price . '</span> млн руб / <span>' . $data['rows']->
                            $key->price_arenda . '</span> тыс руб';
                    } elseif ($data['rows']->$key->price > 0) {
                        $price = '<span>' . $data['rows']->$key->price . '</span> млн руб';
                    } elseif ($data['rows']->$key->price_arenda > 0) {
                        $price = '<span>' . $data['rows']->$key->price_arenda . '</span> тыс руб';
                    } else {
                        $price = 'по запросу';
                    }
                    $data['rows']->$key->price = $price;
                    $data['rows']->$key->srok = (!$sdd || count($sdd) == 0) ? 'не указано' : implode(" / ",
                        $sssd);
                    $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $v['link'];
                    ;
                    $data['rows']->$key->foto = $v['mainfoto'];
                    $data['rows']->$key->mortgage = $v['ipoteka'];
                }
                $data['pageHeader'] = $metro['title'];
                $this->addVar('title', $metro['title']);
                $this->addVar('keywords', $metro['keywords']);
                $this->addVar('description', $metro['description']);
                $data['seotext'] = $metro['seotext'];
                $data['htag'] = $metro['htag'];
                $data['filterFunc'] = 'getFilterCommercial';
                $data['moduleName'] = 'buildings';
                break;
            default:
                show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        }
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category . '/' . $metro_link,
            'uri_segment' => 3,
            'num_links' => 2);
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);

        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон

        $this->viewPage($this->data); // выводим весь вид

    }

    private function getReferer($category) {
        $ref = $_SERVER['HTTP_REFERER'];
        if (empty($ref))
            return '/' . $category;

        $parse = parse_url($ref);
        //echo $ref;
        if (!empty($parse['host']) && $parse['host'] == 'm16-estate.ru') {
            if (!empty($parse['path']) && str_replace('/', '', $parse['path']) == $category) {
                if (!empty($parse['query']) || !empty($parse['fragment'])) {
                    $ret = '/' . $category;
                    $ret .= isset($parse['query']) ? '?' . $parse['query'] : '';
                    $ret .= isset($parse['fragment']) ? '#' . $parse['fragment'] : '';
                    return $ret;
                }
            }
        }
        return '/' . $category;
    }
	

}
/* End of file */
