<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Модуль: Земельные участки
 **/

class land extends MY_Controller {

    private $conf; // конфиг файл
    private $lang; // языковый файл    

    function __construct()
    {
        // конструктор
        parent::__construct();

        include(MDPATH.'land/moduleinfo.php');
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
            else {
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
                    if(is_numeric($method)) // если идет пагинация
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

        if (!$this->session->userdata('plitter_land')){
            $this->session->set_userdata('plitter_land', 'plit');
        }

        if (isset($_POST['chplit'])){
            $this->session->set_userdata('plitter_land', $_POST['chplit']);

        }

        if (isset($_POST['sortprice'])){
            $this->session->set_userdata('sort_price_land', $_POST['sortprice']);
        }

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->load->model('land_rayon/admin_land_rayon_model','admin_land_rayon_model');
        $parentrayon = $this->admin_land_rayon_model->parent_id(null, 'name', 'asc');
        foreach($parentrayon as $p)
        {
            $parent_idrayon[$p['id']] = $p['name'];
        }



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

        $s = array(
            '$this->module' => $this->module,
            '$model'        => $model,
        );

        $data['params'] = $params;

        $dataRow = $this->$model->paginations($offset, $params); // вытаскиваем данные

        $data['empty'] = false;
        if(empty($dataRow)){
            $dataRow = $this->$model->paginations($offset, null, null);
            $params = null;
            $data['empty'] = true;
        }

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
                $data['rows']->$key->address = $value['address'];
                $data['rows']->$key->rayon = $parent_idrayon[$value['rayon_id']];
                $data['rows']->$key->sign = array('class'=>'land', 'name'=>'Земельные массивы');
                if($value['price'] > 0) {
                    $pri = '<span>'.$this->number_format_drop_zero_decimals((($value['price'])/1000000)).'</span> млн руб';
                } else {
                    $pri = 'по запросу';
                }
                $data['rows']->$key->price = $pri;
                if($value['tokad'] > 0) {
                    $tokad = $value['tokad'].' км до КАД';
                } else {
                    $tokad = '';
                }
                $data['rows']->$key->tokad = $tokad;
            }
        }

        $uri = 'land';
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
            $this->load->view('themes/'.$this->defaultTheme.'/'.'land_part', $data);
        }
        else {
            $this->addVar('template', $this->render('land', $data)); // формируем шаблон
            $this->viewPage($this->data); // выводим весь вид
        }

    }

    /** Просмотр одной записи */
    function view()
    {
        $this->setOptions(); // заносим опции в переменные

        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);


        $this->load->model('land_forwhat/admin_land_forwhat_model','admin_land_forwhat_model');
        $parentforwhat = $this->admin_land_forwhat_model->parent_id();
        foreach($parentforwhat as $p)
        {
            $parentforwhat_list[$p['id']] = $p['name'];
        }

        $this->load->model('land_city/admin_land_city_model','admin_land_city_model');
        $parentcity = $this->admin_land_city_model->parent_id();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные
        if(!empty($dataRow))
        {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид

            // Вы уже смотрели
            /*if ($this->session->userdata('land_views')){

                $sess = $this->session->userdata('land_views');
                if (!in_array($dataRow['id'], $sess)){
                    array_push($sess, $dataRow['id']);
                    $this->session->set_userdata('land_views', $sess);
                }

            }
            else {
                $this->session->set_userdata('land_views', array());
                $sess = array('0'=>$dataRow['id']);
                $this->session->set_userdata('land_views', $sess);
            }

            $sess = $this->session->userdata('land_views');

            $keyss = array_search($dataRow['id'],$sess);
            if($keyss!==false){
                unset($sess[$keyss]);
            }

            $yous = array();
            if (count($sess) > 0){
                $yousee = $this->$model->getLandAll($sess);
                foreach($yousee as $key=>$value)
                {
                    $yous[$key]['name'] = $value['name'];
                    $yous[$key]['link'] = BASEURL.'/'.$this->link.'/'.$value['link'];
                    $yous[$key]['foto'] = $value['main_foto'];
                    $yous[$key]['address'] = $value['address'];
                    $yous[$key]['sign'] = array('class'=>'land', 'name'=>'Земельные массивы');
                    if($value['price'] > 0) {
                        $pri = '<span>'.$this->number_format_drop_zero_decimals((($value['price'])/1000000)).'</span> млн руб';
                    } else {
                        $pri = 'по запросу';
                    }
                    $yous[$key]['price'] = $pri;
                    if($value['tokad'] > 0) {
                        $tokad = $value['tokad'].' км до КАД';
                    } else {
                        $tokad = '';
                    }
                    $yous[$key]['tokad'] = $tokad;
                }
            }
            $data['rows']->yousee = $yous;*/
            //-- Вы уже смотрели

            // Похожие объекты

            $likeas = array();
            if (!empty($dataRow['likeas'])){
                $temps = $this->$model->getLandAll(explode(',', $dataRow['likeas']));
                foreach($temps as $key=>$value)
                {
                    $likeas[$key]['name'] = $value['name'];
                    $likeas[$key]['link'] = BASEURL.'/'.$this->link.'/'.$value['link'];
                    $likeas[$key]['foto'] = $value['main_foto'];
                    $likeas[$key]['address'] = $value['address'];
                    $likeas[$key]['sign'] = array('class'=>'land', 'name'=>'Земельные массивы');
                    if($value['price'] > 0) {
                        $pri = '<span>'.$this->number_format_drop_zero_decimals((($value['price'])/1000000)).'</span> млн руб';
                    } else {
                        $pri = 'по запросу';
                    }
                    $likeas[$key]['price'] = $pri;
                    if($value['tokad'] > 0) {
                        $tokad = $value['tokad'].' км до КАД';
                    } else {
                        $tokad = '';
                    }
                    $likeas[$key]['tokad'] = $tokad;
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
            $data['rows']->square = $dataRow['square'];
            $data['rows']->tokad = $dataRow['tokad'];
            $data['rows']->city = $parentcity_list[$dataRow['city_id']];
            $data['rows']->forwhat = $parentforwhat_list[$dataRow['forwhat']];
            $data['rows']->presentation = $dataRow['presentation'];
            $data['rows']->map_lat = $map[0];
            $data['rows']->map_lng = $map[1];

            if ($dataRow['price'] > 0)
                $data['rows']->price = '<span><span>'.$this->number_format_drop_zero_decimals((($dataRow['price'])/1000000)).'</span> млн руб</span>';
            else
                $data['rows']->price = '<span>по запросу</span>';

            $this->load->model('favorites/favorites_model','favorites_model');

            $favarray = array(
                'object_id' => $dataRow['id'],
                'category' => 'land',
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

        $data['refer'] = BASEURL.$this->getReferer('land');

        // генерируем title, keywords, description
        $this->addVar('title', $dataRow['title']);
        $this->addVar('keywords', $dataRow['keywords']);
        $this->addVar('description', $dataRow['description']);

        // задаем крохи
        if(!empty($this->lang->md_breadcrumbs))
        { $brd = $this->lang->md_breadcrumbs; }
        else
        { $brd = $this->lang->md_header; }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('land_view', $data)); // формируем шаблон
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
        $this->load->model('land/land_model','land_model');
        $params = null;
        if (!empty($_POST['param'])){
            $data = $_POST['param'];
            parse_str($data, $params);
        }
        $maps = $this->land_model->allMapLand($params);
        $dataMaps = array();
        foreach ($maps as $k => $v){
            $row['id'] = $v['id'];
            $row['name'] = $v['name'];
            $m = $v['map'];
            $m = explode("|", $m);
            $row['lat'] = $m[0];
            $row['lng'] = $m[1];
            $row['link'] = BASEURL.'/'.$this->link.'/'.$v['link'];
            if (!empty($v['tokad']))
                $row['srok'] = $v['tokad'].' км до КАД';
            else
                $row['srok'] = '';
            $row['foto']  = image($v['main_foto'], 'small');
            if($v['price'] > 0) {
                $row['price'] = '<span>'.$this->number_format_drop_zero_decimals((($v['price'])/1000000)).'</span> млн руб';
            } else {
                $row['price'] = 'по запросу';
            }
            $row['address'] = $v['address'];
            $dataMaps[] = $row;
        }
        echo json_encode($dataMaps);
    }

    public function getFilterLand(){
        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $this->load->model('land_rayon/land_rayon_model','land_rayon_model');
        $parentrayon = $this->land_rayon_model->getAllFilter();
        foreach($parentrayon as $p)
        {
            $parentrayon_list[$p['id']] = $p['name'];
        }

        $this->load->model('land_city/land_city_model','land_city_model');
        $parentcity = $this->land_city_model->getAllFilter();
        foreach($parentcity as $p)
        {
            $parentcity_list[$p['id']] = $p['name'];
        }

        $this->load->model('land_forwhat/admin_land_forwhat_model','admin_land_forwhat_model');
        $parentforwhat = $this->admin_land_forwhat_model->parent_id();
        foreach($parentforwhat as $p)
        {
            $parentforwhat_list[$p['id']] = $p['name'];
        }

        $this->load->model('land_sdelka/admin_land_sdelka_model','admin_land_sdelka_model');
        $parentsdelka = $this->admin_land_sdelka_model->parent_id();
        foreach($parentsdelka as $p)
        {
            $parentsdelka_list[$p['id']] = $p['name'];
        }

        $this->load->model('land_type/land_type_model','admin_land_type_model');
        $parenttype = $this->admin_land_type_model->getAll();
        foreach($parenttype as $p)
        {
            $parenttype_list[$p['id']] = $p['name'];
        }

        $minMax = $this->$model->getMinMax();
        $minMax = $this->getMinMaxValues($minMax);

        $minMaxS = $this->$model->getMinMaxSquare();
        $minMaxS = $this->getMinMaxValuesSquare($minMaxS);


        $data = array();

        $data['filter']->rayon = $parentrayon_list;
        $data['filter']->sdelka = $parentsdelka_list;
        $data['filter']->type = $parenttype_list;
        $data['filter']->forwhat = $parentforwhat_list;
        $data['filter']->city = $parentcity_list;
        $data['filter']->minMax = $minMax;
        $data['filter']->minMaxS = $minMaxS;
        $this->load->view('themes/'.$this->defaultTheme.'/'.'land_filter', $data);

    }

    public function countFind(){
        $this->load->model('land/land_model','lmodel');
        $filter = unserialize($_REQUEST['filter']);
        parse_str($_REQUEST['filter'], $filter);
        $count = $this->lmodel->findCount($filter);
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
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero_decimals($minmax['min']/1000000, '.');
        $minmax['max'] = $this->number_format_drop_zero_decimals($minmax['max']/1000000, '.');
        return $minmax;
    }

    function getMinMaxValuesSquare($minmax){
        if ($minmax['min'] > 0)
            $minmax['min'] = $this->number_format_drop_zero_decimals($minmax['min'], '.');
        $minmax['max'] = $this->number_format_drop_zero_decimals($minmax['max'], '.');
        return $minmax;
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