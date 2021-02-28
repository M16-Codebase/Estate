<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модуль: Квартиры
**/

class apartments extends MY_Controller {

	private $conf; // конфиг файл
    private $lang; // языковый файл

    function __construct()
	{
		// конструктор
		parent::__construct();

        include(MDPATH.'apartments/moduleinfo.php');
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

            if(isset($argument[0]))  $u = $argument[0]; else $u = 0; // сегмент ссылки
			//echo $method;
            if(method_exists($this,$method)) // если существует метод, то запускаем его
            {

                //if($method == 'index') { $this->index(); } else
                if($method == 'limit') { $this->limit($u); } else
                if($method == 'view') { $this->view($argument); } else
                if($method == 'search') { $this->searchFunction($u); } else
                { show_404('Метода не существует: '. $this->uri->uri_string()); }
            }
            else
            {
                $this->view();
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


        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->pagination($offset); // вытаскиваем данные
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
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->text = $value['short_text'];


            }
        }

        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);

        // задаем хлебную кроху
        if(!empty($this->lang->md_breadcrumbs))
            { $this->breadcrumbs($this->lang->md_breadcrumbs); }
        else
            { $this->breadcrumbs($this->lang->md_header); }

        $this->addVar('template', $this->render('default', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
	}

    /** Просмотр одной записи */
    function view($argument = null)
    {
        $this->setOptions(); // заносим опции в переменные


        $model = $this->table.'_model';
        $this->load->model($this->module.'/'.$model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $this->load->model('metro/admin_metro_model','admin_metro_model');
        $parentmetro = $this->admin_metro_model->parent_id(null, 'name', 'asc');
        foreach($parentmetro as $p)
        {
            $parent_idmetro[$p['id']] = $p['name'];
        }

        $this->load->model('rayon/admin_rayon_model','admin_rayon_model');
        $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
        foreach($parentrayon as $p)
        {
            $parent_idrayon[$p['id']] = $p['name'];
        }

        $this->load->model('type/admin_type_model','admin_type_model');
        $parenttype = $this->admin_type_model->parent_id();
        foreach($parenttype as $p)
        {
            $parent_idtype[$p['id']] = $p['name'];
        }

        $this->load->model('otdelka/admin_otdelka_model','admin_otdelka_model');
        $parent_otdelka = $this->admin_otdelka_model->parent_id();
        foreach($parent_otdelka as $p)
        {
            $parent_id_otdelka[$p['id']] = $p['name'];
        }

        $this->load->model('room/admin_room_model','admin_room_model');
        $parent_room = $this->admin_room_model->parent_id();
        foreach($parent_room as $p)
        {
            $parent_id_room[$p['id']] = $p['name'];
        }

        if (count($argument)==3){
            $id_room = $argument[2];
        }
        else {
            $id_room = $this->data['uri2'];
        }

        /*$apps = $this->$model->getAll();
        foreach ($apps as $k=>$v){
            $room = $parent_id_room[$v['room_id']];
            if ($room == 'Студия')
                $rs = "Квартира студия";
            elseif ($room == 'К. пом' || $room == 'Коммерческое пом.')
                $rs = "Коммерческое помещение";
            else {
                $rs = (int)$room." комн. квартира";
            }
            if (in_array($v['room_id'], array(16, 17, 18))){
                $rs .= " (евро)";
            }
            $link = toTranslitUrl($rs).'-'.$v['id'];
            $where = array('id'=>$v['id']);
            $mas = array('link'=>$link);
            $this->db->update($this->table, $mas, $where);

        }*/


        $dataRow = $this->$model->getRow(null, $id_room); // вытаскиваем данные
        $this->load->model('buildings/buildings_model', 'buildings_model');
        $buildingRow = $this->buildings_model->getRow(null, $dataRow['novostroy_id']);

        if(!empty($dataRow))
        {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид
			
			$this->addVar('prcint',$this->number_format_drop_zero_decimals($dataRow['price']/1000000, 1));
			
            $this->load->model('builders/admin_builders_model','admin_builders_model');
            $builders = $this->admin_builders_model->module_get($buildingRow['builder_id']);
            $data['rows']->builder = $builders;
            $zastr = (!empty($builders)) ? $builders['name'] : '';

            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->building = 'ЖК «'.$buildingRow['name'].'»';
            $data['rows']->building_link = BASEURL.'/buildings/'.$buildingRow['link'];
            $room = $parent_id_room[$dataRow['room_id']];
            if ($room == 'Студия'){
                $data['rows']->room = "Квартира студия";
                $seo_name = "Квартира студия";
            }
            elseif ($room == 'К. пом' || $room == 'Коммерческое пом.'){
                $data['rows']->room = "Коммерческое помещение";
                $seo_name = "Коммерческое помещение";
            }
            else {
                $data['rows']->room = (int)$room." комн. квартира";
                $seo_name = (int)$room."-комнатная квартира";
            }
            if (in_array($dataRow['room_id'], array(16, 17, 18))){
                $data['rows']->room .= " (евро)";
                $seo_name .= " (евро)";
            }
            if($dataRow['price'] > 0){
				$data['rows']->price_int = $this->number_format_drop_zero_decimals($dataRow['price']/1000000, 1);
                $data['rows']->price = '<span><span>'.$this->number_format_drop_zero_decimals($dataRow['price']/1000000, 1).'</span> млн. руб</span>';
                $data['rows']->price_text = ' стоимостью '.$this->number_format_drop_zero_decimals($dataRow['price']/1000000, 1).' млн. руб';
                $priceseo = $this->number_format_drop_zero_decimals($dataRow['price']/1000000, 1).' млн. руб';
            }
            else {
                $data['rows']->price = '<span>по запросу</span>';
                $data['rows']->price_text = '';
                $priceseo = 'по запросу';
            }
            $data['rows']->foto = $dataRow['main_foto'];
            $data['rows']->rayon = $parent_idrayon[$buildingRow['rayon_id']];
            $data['rows']->metro = $parent_idmetro[$buildingRow['metro_id']];
            $data['rows']->rooms = $room;
            $data['rows']->type = $parent_idtype[$buildingRow['type_id']];
            $data['rows']->otdelka = $parent_id_otdelka[$dataRow['otdelka_id']];
            $data['rows']->floor = $dataRow['floor'];
            $data['rows']->square_all = $dataRow['square_all'];
            $data['rows']->square_life = $dataRow['square_life'];
            $data['rows']->square_cook = $dataRow['square_cook'];
            $data['rows']->presentation = $dataRow['presentation'];
            $data['rows']->address = $buildingRow['adress'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->youtube = getYoutubeVideoID($dataRow['video_code'], $dataRow['video_desc']);

            $data['rows']->img_alt = "Фото и планировки {$seo_name} {$dataRow['square_all']} кв м. | М16 - актуальный фотокаталог недвижимости, продажа квартир в Санкт-Петербурге.";
            // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));

            $this->addVar('noIndex', true);
        }
        else
            { show_404('404: Страница - '.$this->uri->uri_string().' не найдена'); return; }

        $sess = $this->session->userdata('buildings_views');

        $yous = array();
        if (count($sess) > 0){
            $yousee = $this->buildings_model->getBuildingsAll($sess);
            foreach($yousee as $key=>$value)
            {


                if($value['korpus'] == '2') { $kr = 'Собственность'; }
                elseif($value['korpus'] == '3') { $kr = $this->dataKv($value['korpus_value']); }

                $key = $value['link'];

                $yous[$key]['name'] = $value['name'];
                $yous[$key]['link'] = BASEURL.'/buildings/'.$value['link'];
                $yous[$key]['foto'] = $value['mainfoto'];
                $yous[$key]['adress'] = $value['adress'];
                $yous[$key]['metro'] = $parent_idmetro[$value['metro_id']];
                $yous[$key]['rayon'] = $parent_idrayon[$value['rayon_id']].' район';
                $yous[$key]['price'] = $this->number_format_drop_zero_decimals(($this->buildings_model->complexMinPrice($value['id'])/1000000), 1);
                $yous[$key]['srok'] = $kr;
            }
        }
        $data['rows']->yousee = $yous;
        $smetro = mb_strtolower($data['rows']->metro);
        $srayon = mb_strtolower($data['rows']->rayon);
        // генерируем title, keywords, description
        if (!empty($dataRow['title'])){
            $this->addVar('title', $dataRow['title']);
        }
        else {
            $this->addVar('title', $seo_name.' в ЖК '.$buildingRow['name'].' у метро '.$smetro.', '.$srayon.' район – цена '.$priceseo.' | Компания "М16-Недвижимость"');
        }
        if (!empty($dataRow['keywords'])){
            $this->addVar('keywords', $dataRow['keywords']);
        }
        else {
            $sn = mb_strtolower($seo_name.' '.$buildingRow['name'].', '.$seo_name.' '.$buildingRow['name'].' '.$zastr.', '.$seo_name.' '.$buildingRow['name'].' купить, '.$seo_name.' '.$buildingRow['name'].' цена, '.$seo_name.' '.$buildingRow['name'].' продажа, '.$seo_name.' '.$buildingRow['name'].' стоимость');
            $this->addVar('keywords', $sn);
        }
        if (!empty($dataRow['description'])){
            $this->addVar('description', $dataRow['description']);
        }
        else {
            $this->addVar('description', $seo_name.' в жилом комплексе '.$buildingRow['name'].' у метро '.$smetro.', '.$srayon.' район от застройщика '.$zastr.'. Цена '.$priceseo.'. '.$seo_name.' – описание, цены, фото, расположение.');
        }


        // задаем крохи
        if(!empty($this->lang->md_breadcrumbs))
            { $brd = $this->lang->md_breadcrumbs; }
        else
            { $brd = $this->lang->md_header; }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('apartment', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
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

    function number_format_drop_zero_decimals($n, $n_decimals)
    {
        //$n = number_format($n, $n_decimals, ',', ' ');
        $n = round($n, 1, PHP_ROUND_HALF_DOWN);
        $n = number_format($n, 1, ',', ' ');
        $ex = explode(',', $n);

        if (count($ex) > 1 && (int)$ex[1] == 0)
            $n = (int)$n;
        //return (($n == round($n, $n_decimals)) ? number_format($n, 0, ',', ' ') : number_format($n, $n_decimals, ',', ' '));
        return $n;
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


}
/* End of file */
