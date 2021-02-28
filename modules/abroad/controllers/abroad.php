<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Модуль: Зарубежная недвижимость
 **/

class abroad extends MY_Controller {

    private $conf; // конфиг файл
    private $lang; // языковый файл    

    function __construct()
    {
        // конструктор
        parent::__construct();

        include(MDPATH.'abroad/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];

        // определяем, нужно ли использовать роутер
        if(!empty($moduleinfo['router']))
        { $this->link = $moduleinfo['router']; }
        else
        { $this->link = $this->module; }
    }

    /** Роутер модуля */
    function _remap($method, $argument)
    {
        show_404('404: Страница - '.$this->uri->uri_string().' не найдена');
        return;
        if($moduleinfo['status'] != 0) // проверка на доступность модуля 
        { return false; }
        else
        {
            if(uri(2) == 'map')
            {
                $this->showMap();
            }
            elseif(uri(2) == 'countFind')
            {
                $this->countFind();
            }
            else{
                if(isset($argument[0]))  $u = $argument[0]; else $u = 0; // сегмент ссылки
                if(method_exists($this,$method)) // если существует метод, то запускаем его
                {
                    if($method == 'index') { $this->index(); } else
                        if($method == 'limit') { $this->limit($u); } else
                            if($method == 'search') { $this->searchFunction($u); } else
                            { show_404('Метода не существует: '. $this->uri->uri_string()); }
                }
                else
                {
                    if($method == 'abroad_estate') {
                        $page = 0;
                        if (isset($argument[1])){
                            $ur = parse_url($argument[1]);
                            parse_str($ur['query'], $param);
                            if (isset($param['page']))
                                $page = $param['page'];
                        }
                        $this->estatePage($u, $page);
                    }
                    else if($method == 'abroad_country') {
                        $page = 0;
                        if (isset($argument[1])){
                            $ur = parse_url($argument[1]);
                            parse_str($ur['query'], $param);
                            if (isset($param['page']))
                                $page = $param['page'];
                        }
                        $this->countryPage($u, $page);
                    }
                    else if(is_numeric($method)) // если идет пагинация
                    { $this->index($method); }
                    else // иначе просмотр конкретной записи
                    { $this->view(); }
                }
            }
        }
    }

    /** Опции модуля */
    function setOptions()
    {
        // загрузка настроек
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);

        // загрузка языка              
        $foreach = $this->load->language($this->table.'_mod', '', true);
        foreach($foreach as $key=>$l) { $this->lang->$key = htmlspecialchars_decode($l['name']); }

        // выводить или не выводить хлебные крошки для модуля
        $this->noBreadcrumbs = $this->conf['breadcrumbs'];
    }

    /** Главная */
    function index($offset = 0)
    {
        $this->setOptions(); // заносим опции в переменные

        if (!$this->session->userdata('plitter_abroad')){
            $this->session->set_userdata('plitter_abroad', 'plit');
        }

        if (isset($_POST['chplit'])){
            $this->session->set_userdata('plitter_abroad', $_POST['chplit']);

        }

        if (isset($_POST['sortprice'])){
            $this->session->set_userdata('sort_price_abroad', $_POST['sortprice']);
        }

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAll();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAll();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }


        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $parentestate = $this->abroad_estate->getAll();
        foreach($parentestate as $p)
        {
            $parentestate_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_house/abroad_house_model','abroad_house');
        $parenthouse = $this->abroad_house->getAll();
        foreach($parenthouse as $p)
        {
            $parenthouse_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_resale/abroad_resale_model','abroad_resale');
        $parentresale = $this->abroad_resale->getAll();
        foreach($parentresale as $p)
        {
            $parentresale_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_rooms/abroad_rooms_model','abroad_rooms');
        $parentrooms = $this->abroad_rooms->getAll();
        foreach($parentrooms as $p)
        {
            $parentrooms_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_sdelka/abroad_sdelka_model','abroad_sdelka');
        $parentsdelka = $this->abroad_sdelka->getAll();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        $data['pcountry'] = $parentcountry_list;
        $data['pcity'] = $parentcity_list;
        $data['prooms'] = $parentrooms_list;
        $data['pestate'] = $parentestate_list;
        $data['pcurrency'] = $parentcurrency_list;
        $data['phouse'] = $parenthouse_list;
        $data['presale'] = $parentresale_list;
        $data['psdelka'] = $parentsdelka_list;

        $data['rows'] = ''; // переменная для хранения данных                
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $url = parse_url($_SERVER['REQUEST_URI']);
        parse_str($url['query'], $params);
        if(!empty($params)) { if(isset($params['plit'])) unset($params['plit']); $get = $url['query']; }

        if(!empty($get)){
            parse_str($get, $paag);
            //unset($paag['page']);
            $paag['page'] = 'all';
            $data['show_all'] = http_build_query($paag);
        }
        else {
            $data['show_all'] = 'page=all';
        }

        $data['params'] = $params;

        $dataRow = $this->$model->paginations($offset, $params); // вытаскиваем данные

        if($dataRow) // проверяем есть ли данные
        {
            // проверяем нужно ли использовать пагинацию 
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module,
                'uri_segment' => 2,
                'num_links' => 2
            );
            $data['pagination'] = $this->paginations($arrayPagination);
            // проходим цикл для формирования данных                                                                                                          
            foreach($dataRow as $key=>$value)
            {
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = $value['main_foto'];
                $data['rows']->$key->square_all = $value['square_all'];
                $data['rows']->$key->price = $value['price'] . $currencies[$value['currency_id']];
                $data['rows']->$key->country = $parentcountry_list[$value['country_id']];
                $data['rows']->$key->estate = $parentestate_list[$value['estate_type']];
                $data['rows']->$key->address = $parentcity_list[$value['city_id']].', '.$value['address'];
                $data['rows']->$key->sign = array('class'=>'abroad', 'name'=>'Зарубежная');
            }
        }

        $uri = 'abroad';
        $this->load->model('seometa/seo_meta_model','seo_meta_model');
        $seo = $this->seo_meta_model->getByLink($uri);
        if (!empty($seo)){
            $this->addVar('title', $seo['title']);
            $this->addVar('keywords', $seo['keywords']);
            $this->addVar('description', $seo['description']);
            $data['lang']->md_title = $seo['name'];
        }

        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
        { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
        { $this->breadcrumbs($this->lang->md_header); }

        if($this->input->is_ajax_request()){
            $this->load->view('themes/'.$this->defaultTheme.'/'.'abroad_part', $data);
        }
        else {
            $this->addVar('template', $this->render('abroad', $data)); // формируем шаблон
            $this->viewPage($this->data); // выводим весь вид
        }
    }

    /** Просмотр одной записи */
    function view()
    {
        $this->setOptions(); // заносим опции в переменные

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAll();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAll();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }


        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $parentestate = $this->abroad_estate->getAll();
        foreach($parentestate as $p)
        {
            $parentestate_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_house/abroad_house_model','abroad_house');
        $parenthouse = $this->abroad_house->getAll();
        foreach($parenthouse as $p)
        {
            $parenthouse_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_resale/abroad_resale_model','abroad_resale');
        $parentresale = $this->abroad_resale->getAll();
        foreach($parentresale as $p)
        {
            $parentresale_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_rooms/abroad_rooms_model','abroad_rooms');
        $parentrooms = $this->abroad_rooms->getAll();
        foreach($parentrooms as $p)
        {
            $parentrooms_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_sdelka/abroad_sdelka_model','abroad_sdelka');
        $parentsdelka = $this->abroad_sdelka->getAll();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;



        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные
        if(!empty($dataRow))
        {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид

            // Вы уже смотрели
            if ($this->session->userdata('abroad_views')){

                $sess = $this->session->userdata('abroad_views');
                if (!in_array($dataRow['id'], $sess)){
                    array_push($sess, $dataRow['id']);
                    $this->session->set_userdata('abroad_views', $sess);
                }

            }
            else {
                $this->session->set_userdata('abroad_views', array());
                $sess = array('0'=>$dataRow['id']);
                $this->session->set_userdata('abroad_views', $sess);
            }

            $sess = $this->session->userdata('abroad_views');

            $keyss = array_search($dataRow['id'],$sess);
            if($keyss!==false){
                unset($sess[$keyss]);
            }

            $yous = array();
            if (count($sess) > 0){
                $yousee = $this->$model->getAbroadAll($sess);
                foreach($yousee as $key=>$value)
                {
                    $yous[$key]['name'] = $value['name'];
                    $yous[$key]['link'] = BASEURL.'/'.$this->link.'/'.$value['link'];
                    $yous[$key]['foto'] = $value['main_foto'];
                    if ($value['price'] > 0)
                        $yous[$key]['price'] = '<span>'.$value['price'] . $currencies[$value['currency_id']].'</span>';
                    else
                        $yous[$key]['price'] = 'по запросу';
                    $yous[$key]['country'] = $parentcountry_list[$value['country_id']];
                    $yous[$key]['estate'] = $parentestate_list[$value['estate_type']];
                    $yous[$key]['address'] = $parentcity_list[$value['city_id']].', '.$value['address'];
                    $yous[$key]['sign'] = array('class'=>'abroad', 'name'=>'Зарубежная');
                }
            }
            $data['rows']->yousee = $yous;
            //-- Вы уже смотрели

            // Похожие объекты

            $likeas = array();
            if (!empty($dataRow['likeas'])){
                $temps = $this->$model->getAbroadAll(explode(',', $dataRow['likeas']));
                foreach($temps as $key=>$value)
                {
                    $likeas[$key]['name'] = $value['name'];
                    $likeas[$key]['link'] = BASEURL.'/'.$this->link.'/'.$value['link'];
                    $likeas[$key]['foto'] = $value['main_foto'];
                    if ($value['price'] > 0)
                        $likeas[$key]['price'] = '<span>'.$value['price'] . $currencies[$value['currency_id']].'</span>';
                    else
                        $likeas[$key]['price'] = 'по запросу';
                    $likeas[$key]['country'] = $parentcountry_list[$value['country_id']];
                    $likeas[$key]['estate'] = $parentestate_list[$value['estate_type']];
                    $likeas[$key]['address'] = $parentcity_list[$value['city_id']].', '.$value['address'];
                    $likeas[$key]['sign'] = array('class'=>'abroad', 'name'=>'Зарубежная');
                }

            }
            $data['rows']->likeas = $likeas;
            //-- Похожие объекты

            $map = explode('|', $dataRow['map']);

            $data['rows']->id = $dataRow['id'];
            $data['rows']->foto = unserialize($dataRow['foto']);
            $data['rows']->header = $dataRow['name'];
            $data['rows']->link = $dataRow['link'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->address = $dataRow['address'];
            $data['rows']->square_all = $dataRow['square_all'];
            $data['rows']->square_land = $dataRow['square_land'];
            $data['rows']->rooms = $parentrooms_list[$dataRow['rooms_id']];
            $data['rows']->estate = $parentestate_list[$dataRow['estate_type']];
            $data['rows']->sdelka = $parentsdelka_list[$dataRow['sdelka_type']];
            $data['rows']->country = $parentcountry_list[$dataRow['country_id']];
            $data['rows']->city = $parentcity_list[$dataRow['city_id']];
            $data['rows']->house_type = $parenthouse_list[$dataRow['house_type']];
            $data['rows']->resale = $parentresale_list[$dataRow['resale']];
            $data['rows']->map_lat = $map[0];
            $data['rows']->map_lng = $map[1];
            $data['rows']->location = $dataRow['location'];
            $data['rows']->infrastructure = $dataRow['infrastructure'];
            $data['rows']->vid = $dataRow['vid'];
            $data['rows']->planirovka = $dataRow['planirovka'];
            $data['rows']->year = $dataRow['year'];
            $data['rows']->floors = $dataRow['floors'];
            $data['rows']->presentation = $dataRow['presentation'];
            if ($dataRow['price'] > 0){
                $data['rows']->price = '<span><span>'.$dataRow['price'].' '.$currencies[$dataRow['currency_id']].'</span></span>';
                $data['rows']->price_text = ' стоимостью '.$dataRow['price'].' '.$currencies[$dataRow['currency_id']];
                $seo_price = $dataRow['price'].' '.$currencies[$dataRow['currency_id']];
            }
            else {
                $data['rows']->price = '<span>по запросу</span>';
                $data['rows']->price_text = '';
                $seo_price = 'по запросу';
            }

            $this->load->model('favorites/favorites_model','favorites_model');

            $favarray = array(
                'object_id' => $dataRow['id'],
                'category' => 'abroad',
                'name' => $dataRow['name'],
                'foto' => $dataRow['main_foto'],
                'sort' => $dataRow['sort'],
                'link' => $dataRow['link'],
                'price' => $data['rows']->price
            );
            $idfs = $this->favorites_model->addToFavorites($favarray);
            $this->load->helper('cookie');
            $favorites = $this->input->cookie('favorites', true);
            $favorites = explode('.',$favorites);
            if(in_array($idfs, $favorites)){
                $data['in_favorites'] = true;
            }

            $sessi = $this->session->userdata('seen');
            if (!in_array($idfs, $sessi)){
                array_push($sessi, $idfs);
                $this->session->set_userdata('seen', $sessi);
            }

            if ($this->session->userdata('seen')){

                $sessi = $this->session->userdata('seen');
                if (!in_array($idfs, $sessi)){
                    array_push($sessi, $idfs);
                    $this->session->set_userdata('seen', $sessi);
                }

            }
            else {
                $this->session->set_userdata('seen', array());
                $sessi = array('0'=>$idfs);
                $this->session->set_userdata('seen', $sessi);
            }

            $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
        }
        else
        { show_404('404: Страница - '.$this->uri->uri_string().' не найдена'); }

        // генерируем title, keywords, description
        if (!empty($dataRow['title'])){
            $this->addVar('title', $dataRow['title']);
        }
        else {

            $sn = $data['rows']->estate.' в стране '.$data['rows']->country.', городе '.$data['rows']->city.' на продажу, цена '.$seo_price.' | Компания "М16-Недвижимость"';
            $this->addVar('title', $sn);
        }
        if (!empty($dataRow['keywords'])){
            $this->addVar('keywords', $dataRow['keywords']);
        }
        else {
            $sn = mb_strtolower($data['rows']->estate.' '.$data['rows']->country.' '.$data['rows']->city.', '.$data['rows']->estate.' '.$data['rows']->city.' купить, '.$data['rows']->estate.' '.$data['rows']->city.' покупка, '.$data['rows']->estate.' '.$data['rows']->city.' продажа, '.$data['rows']->estate.' '.$data['rows']->city.' цена, '.$data['rows']->estate.' '.$data['rows']->city.' стоимость');
            $this->addVar('keywords', $sn);
        }
        if (!empty($dataRow['description'])){
            $this->addVar('description', $dataRow['description']);
        }
        else {
            $sn = $data['rows']->estate.' в стране '.$data['rows']->country.', городе '.$data['rows']->city.' на продажу, цена '.$seo_price.'. Описание объекта, фото и расположение.';
            $this->addVar('description', $sn);
        }

        $data['refer'] = BASEURL.$this->getReferer('abroad');

        // задаем крохи
        if(!empty($this->lang->md_breadcrumbs))
        { $brd = $this->lang->md_breadcrumbs; }
        else
        { $brd = $this->lang->md_header; }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('abroad_view', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    /**
     * Vuvod limitnogo k-va zapisey
     * @param $offset = 3 - k-vo zapisey
     * @param $template = true - vuvod html
     **/
    function limit($offset = 3, $template = true)
    {
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные           
        if($dataRow)
        {
            foreach($dataRow as $key=>$value) // проходим цикл для формирования данных
            {
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$value['link'];
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->text = $value['short_text'];
            }
        }

        // возвращаем вид
        if($template)
        { return $this->render('default', $data); }
        else
        { return $data['rows']; }
    }

    /** Пагинация на ajax */
    function ajaxPagination()
    {
        $offset = $this->input->post('page',true); // какую страницу грузить, номер страницы
        $uri2 = $this->input->post('uri2',true);
        $uri3 = $this->input->post('uri3',true);
        $uri4 = $this->input->post('uri4',true);
        $uri5 = $this->input->post('uri5',true);

        /** код для вытаскивания данных */

        // переменная шаблона
        $ok = '';

        // что-то делаем со всеми данными, которые в $_POST
        echo json_encode(array(
            'ok' => $ok
        ));
    }

    /** Отзыв */
    function ajaxComment()
    {
        $name = $this->input->post('name', true);
        $text = nl2br(htmlspecialchars($this->input->post('text', true)));
        $id = $this->input->post('id', true);
        $return = true; // выполнять ли запрос в базу
        $usedCaptcha = false; // использовать ли каптчу

        if($usedCaptcha)
        {
            $captcha = $this->input->post('captcha', true);
            if($captcha == $this->session->userdata('captcha_num')) // проверка каптчи
            { $return = true; }
            else
            { $return = false; $ok = 'errorCaptcha'; }
        }

        if($return)
        {
            $model = $this->table.'_model';
            $this->load->model($this->module.'/'.$model, $model);

            $array = array(
                'tovar_id' => $id,
                'name' => $name,
                'short_text' => $text,
                'date' => date('Y-m-d')
            );
            if($this->$model->module_add($array))
            { $ok = 'success'; }
            else
            { $ok = 'failure'; }
        }

        echo json_encode(array( 'ok' => $ok ));
    }

    /** Поиск */
    function searchFunction($search = '')
    {
        if(empty($search))
        {
            $search = $this->input->post('search_', true);
            $this->session->set_userdata('search_', $search);
            redirect('/'.$this->link.'/search/'.$search);
        }

        $search = $this->session->userdata('search_');

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->breadcrumbs('Поиск');

        /** данные */

        // переменная шаблона    
        $this->addVar('template', $this->render('default', $data));
        $this->viewPage($this->data);
    }

    function showMap()
    {

        $this->load->model('abroad/abroad_model','abroad_model');
        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAll();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }
        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAll();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }
        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }
        $params = null;
        if (!empty($_POST['param'])){
            $data = $_POST['param'];
            parse_str($data, $params);
        }
        $maps = $this->abroad_model->allMapAbroad($params);
        $dataMaps = array();
        foreach ($maps as $k => $v){
            $row['id'] = $v['id'];
            $row['name'] = $v['name'];
            $m = $v['map'];
            $m = explode("|", $m);
            $row['lat'] = $m[0];
            $row['lng'] = $m[1];
            $row['link'] = BASEURL.'/'.$this->link.'/'.$v['link'];
            $row['foto']  = image($v['main_foto'], 'small');
            if ($v['price'] > 0)
                $row['price'] = '<span>'.$v['price'] . $currencies[$v['currency_id']].'</span>';
            else
                $row['price'] = 'по запросу';
            $row['address'] = $parentcountry_list[$v['country_id']].', '.$parentcity_list[$v['city_id']].', '.$v['address'];
            $dataMaps[] = $row;
        }
        echo json_encode($dataMaps);
    }

    public function getFilterAbroad(){
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAllFastFilter();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAllFastFilter();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }


        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $parentestate = $this->abroad_estate->getAllFastFilter();
        foreach($parentestate as $p)
        {
            $parentestate_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_house/abroad_house_model','abroad_house');
        $parenthouse = $this->abroad_house->getAll();
        foreach($parenthouse as $p)
        {
            $parenthouse_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_resale/abroad_resale_model','abroad_resale');
        $parentresale = $this->abroad_resale->getAll();
        foreach($parentresale as $p)
        {
            $parentresale_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_rooms/abroad_rooms_model','abroad_rooms');
        $parentrooms = $this->abroad_rooms->getAllFastFilter();
        foreach($parentrooms as $p)
        {
            $parentrooms_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_sdelka/abroad_sdelka_model','abroad_sdelka');
        $parentsdelka = $this->abroad_sdelka->getAllFastFilter();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMax();
        $minMax = $this->getMinMaxValues($minMax);

        $minMaxS = $this->$model->getMinMaxS();
        $minMaxS = $this->getMinMaxValuesS($minMaxS);

        $minMaxSS = $this->$model->getMinMaxSS();
        $minMaxSS = $this->getMinMaxValuesSS($minMaxSS);

        $data = array();

        $data['filter']->country = $parentcountry_list;
        $data['filter']->city = $parentcity_list;
        $data['filter']->currency  = $parentcurrency_list;
        $data['filter']->estate = $parentestate_list;
        $data['filter']->house = $parenthouse_list;
        $data['filter']->resale = $parentresale_list;
        $data['filter']->sdelka = $parentsdelka_list;
        $data['filter']->rooms = $parentrooms_list;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;
        $data['filter']->minMaxSS = $minMaxSS;

        $this->load->view('themes/'.$this->defaultTheme.'/'.'abroad_filter', $data);

    }

    public function countFind(){
        $this->load->model('abroad/abroad_model','amodel');
        $filter = unserialize($_REQUEST['filter']);
        parse_str($_REQUEST['filter'], $filter);
        $count = $this->amodel->findCount($filter);
        echo $count;
        //echo $parent_room;
    }

    function number_format_drop_zero_decimals($n, $delimiter = ',')
    {
        $n = round($n, 1, PHP_ROUND_HALF_DOWN);
        $n = number_format($n, 1, $delimiter, '');
        $ex = explode(',', $n);
        if (count($ex) > 1 && (int)$ex[1] == 0)
            $n = (int)$n;
        return $n;
    }

    function getMinMaxValues($minmax){
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero_decimals($minmax['min']/1000, '.');
        $minmax['max'] = $this->number_format_drop_zero_decimals($minmax['max']/1000, '.');
        return $minmax;
    }

    function getMinMaxValuesS($minmax){
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero_decimals($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero_decimals($minmax['max'], '.');
        return $minmax;
    }

    function getMinMaxValuesSS($minmax){
        $minmax['min'] = $minmax['min'];
        $minmax['max'] = $minmax['max'];
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero_decimals($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero_decimals($minmax['max'], '.');
        return $minmax;
    }

    function countryPage($metro_link, $page = 0)
    {

        $category = $this->data['uri1'];
        $this->setOptions();


        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $metro = $this->abroad_country->getByLink($metro_link);
        if (empty($metro)){
            show_404('404: Страница - '.$this->uri->uri_string().' не найдена');
        }

        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAll();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAll();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }


        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $parentestate = $this->abroad_estate->getAll();
        foreach($parentestate as $p)
        {
            $parentestate_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_house/abroad_house_model','abroad_house');
        $parenthouse = $this->abroad_house->getAll();
        foreach($parenthouse as $p)
        {
            $parenthouse_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_resale/abroad_resale_model','abroad_resale');
        $parentresale = $this->abroad_resale->getAll();
        foreach($parentresale as $p)
        {
            $parentresale_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_rooms/abroad_rooms_model','abroad_rooms');
        $parentrooms = $this->abroad_rooms->getAll();
        foreach($parentrooms as $p)
        {
            $parentrooms_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_sdelka/abroad_sdelka_model','abroad_sdelka');
        $parentsdelka = $this->abroad_sdelka->getAll();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        switch ($category) {
            case 'abroad':
                $dataRow = $this->model->countryFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach($dataRows as $key=>$v)
                {
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$v['link'];
                    $data['rows']->$key->foto = $v['main_foto'];
                    $data['rows']->$key->square_all = $v['square_all'];
                    $data['rows']->$key->price = '<span>'.$v['price'] . $currencies[$v['currency_id']].'</span>';
                    $data['rows']->$key->rayon = $parentcountry_list[$v['country_id']];
                    $data['rows']->$key->srok = $parentestate_list[$v['estate_type']];
                    $data['rows']->$key->adress = $parentcity_list[$v['city_id']].', '.$v['address'];
                }
                $this->addVar('title', $metro['title']);
                $this->addVar('keywords', $metro['keywords']);
                $this->addVar('description', $metro['description']);
                $data['seotext'] = $metro['seotext'];
                $data['htag'] = $metro['htag'];
                $data['filterFunc'] = 'getFilterAbroad';
                $data['moduleName'] = 'abroad';
                $data['moduleView'] = 'abroad';
                break;
            default:
                show_404('404: Страница - '.$this->uri->uri_string().' не найдена');
        }
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category.'/'.$metro_link,
            'uri_segment' => 3,
            'num_links' => 2
        );
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);

        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон

        $this->viewPage($this->data); // выводим весь вид

    }

    function estatePage($metro_link, $page = 0)
    {

        $category = $this->data['uri1'];
        $this->setOptions();


        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, 'model');

        $data = array();
        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        //Определяем метро
        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $metro = $this->abroad_estate->getByLink($metro_link);
        if (empty($metro)){
            show_404('404: Страница - '.$this->uri->uri_string().' не найдена');
        }

        $this->load->model('abroad_country/abroad_country_model','abroad_country');
        $parentcountry = $this->abroad_country->getAll();
        foreach($parentcountry as $p)
        {
            $parentcountry_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_city/abroad_city_model','abroad_city');
        $parentcity = $this->abroad_city->getAll();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_currency/abroad_currency_model','abroad_currency');
        $parentcurrency = $this->abroad_currency->getAll();
        foreach($parentcurrency as $p)
        {
            $parentcurrency_list[$p['id']] = $p['name'];
            $currencies[$p['id']] = $p['sign'];
        }


        $this->load->model('abroad_estate/abroad_estate_model','abroad_estate');
        $parentestate = $this->abroad_estate->getAll();
        foreach($parentestate as $p)
        {
            $parentestate_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_house/abroad_house_model','abroad_house');
        $parenthouse = $this->abroad_house->getAll();
        foreach($parenthouse as $p)
        {
            $parenthouse_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_resale/abroad_resale_model','abroad_resale');
        $parentresale = $this->abroad_resale->getAll();
        foreach($parentresale as $p)
        {
            $parentresale_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_rooms/abroad_rooms_model','abroad_rooms');
        $parentrooms = $this->abroad_rooms->getAll();
        foreach($parentrooms as $p)
        {
            $parentrooms_list[$p['id']] = $p['name'];
        }

        $this->load->model('abroad_sdelka/abroad_sdelka_model','abroad_sdelka');
        $parentsdelka = $this->abroad_sdelka->getAll();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        switch ($category) {
            case 'abroad':
                $dataRow = $this->model->estateFilter($category, $metro['id'], $page); // вытаскиваем данные
                $dataRows = $dataRow['dataRows'];
                $countRows = $dataRow['countRows'];
                foreach($dataRows as $key=>$v)
                {
                    $data['rows']->$key->name = $v['name'];
                    $data['rows']->$key->link = BASEURL.'/'.$this->link.'/'.$v['link'];
                    $data['rows']->$key->foto = $v['main_foto'];
                    $data['rows']->$key->square_all = $v['square_all'];
                    $data['rows']->$key->price = '<span>'.$v['price'] . $currencies[$v['currency_id']].'</span>';
                    $data['rows']->$key->rayon = $parentcountry_list[$v['country_id']];
                    $data['rows']->$key->srok = $parentestate_list[$v['estate_type']];
                    $data['rows']->$key->adress = $parentcity_list[$v['city_id']].', '.$v['address'];
                }
                $this->addVar('title', $metro['title']);
                $this->addVar('keywords', $metro['keywords']);
                $this->addVar('description', $metro['description']);
                $data['seotext'] = $metro['seotext'];
                $data['htag'] = $metro['htag'];
                $data['filterFunc'] = 'getFilterAbroad';
                $data['moduleName'] = 'abroad';
                $data['moduleView'] = 'abroad';
                break;
            default:
                show_404('404: Страница - '.$this->uri->uri_string().' не найдена');
        }
        $data['conf'] = $this->conf;
        $data['conf']['Paging'] = 1;
        $data['conf']['perPaging'] = 16;
        $arrayPagination = array(
            'all_count' => $countRows,
            'conf' => $data['conf'],
            'uri' => $category.'/'.$metro_link,
            'uri_segment' => 3,
            'num_links' => 2
        );
        $data['page'] = $page;
        $data['category'] = $category;
        $data['linkto'] = $metro_link;

        $data['pagination'] = $this->my_pagination($arrayPagination);

        $this->addVar('template', $this->render('tmpl/seosearch', $data)); // формируем шаблон

        $this->viewPage($this->data); // выводим весь вид

    }

    private function getReferer($category){
        $ref = $_SERVER['HTTP_REFERER'];
        if (empty($ref))
            return '/'.$category;

        $parse = parse_url($ref);
        //echo $ref;
        if (!empty($parse['host']) && $parse['host'] == 'm16-estate.ru'){
            if (!empty($parse['path']) && str_replace('/', '',$parse['path']) == $category){
                if (!empty($parse['query']) || !empty($parse['fragment'])){
                    $ret = '/'.$category;
                    $ret .= isset($parse['query']) ? '?' . $parse['query'] : '';
                    $ret .= isset($parse['fragment']) ? '#' . $parse['fragment'] : '';
                    return $ret;
                }
            }
        }
        return '/'.$category;
    }


}
/* End of file */