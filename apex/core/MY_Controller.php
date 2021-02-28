<?php

(defined('BASEPATH')) or exit('No direct script access allowed');
ini_set('display_errors', 1);
/** MY_Controller **/
class MY_Controller extends MX_Controller {
    public $data; // массив данных
    public $crumbs = ''; // массив хлебных крох
    public $main = false; // определяем главную страницу
    public $langPrefix; // префикс языка
    public $loadPlugin; // подгрузка плагинов
    protected $use_cache = false;
    public $noBreadcrumbs = false; // не выводить крохи
    private $microtime = 0; // время загрузки страницы
    public $defaultTheme = 'default'; // название темы по умолчанию
    public $is_pagination = false;
    public $url_pagination = '';

    public function __construct() {
        parent::__construct();

        if ($this->use_cache) {
            $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
            if (!empty($_SERVER['REQUEST_URI'])) {
                $idq = md5($_SERVER['REQUEST_URI']);
                if ($rend = $this->cache->get($idq)) {
                    echo $rend;
                    die();
                }
            }
        }

        if ($this->microtime == 0) {
            $this->microtime = microtime();
        }

        $this->defaultTheme = config_item('default_theme');
        $this->data['themes'] = 'themes/' . $this->defaultTheme . '/';

        $this->data['uri1'] = uri(1);
        $this->data['uri2'] = uri(2);

        // все глобальные языковые переменные
        $this->data['langLine'] = langLine();

        $this->langPrefix = config_item('language_prefix'); // префикс языка
        $this->data['langPrefix'] = $this->langPrefix == 'ru' ? '' : '/' . $this->
            langPrefix; // передаем префикс в шаблон

        if (config_item('apex_site')) {
            $uris = $this->data['uri1'];
            if ($uris != 'auth' and $uris != 'admin' and $uris != 'block' and $this->data['uri2'] !=
                'admin') {
                if (!$this->session->userdata('DX_logged_in')) {
                    redirect(BASEURL . '/block', 'refresh');
                }
            }
        } else // первая кроха
        {
            $this->breadcrumbs($this->data['langLine']->md_breadcrumbs, $this->data['langPrefix'] .
                '/');
        }
        $this->load->library('minify');

        $this->data['load_css'] = array(); // загрузка css
        $this->data['load_js'] = array(); // загрузка js
    }

    /** Загрузка видов в главный индексный шаблон */
    function viewPage($data = '', $templates = 'template') {
        global $h,$global_context;
        if(strpos($_SERVER['REQUEST_URI'],'url_collect')){
            print_r($data);
        }
        if (empty($data)) {
            $data = $this->data;
        }
		$data['site_url'] = substr(site_url(), 0, -1);
        if (isset($data['OG'])) {
            $this->createOGData($data['OG']);
        }
        $data['ver'] = '17.12.25';
        $data['headSeo'] = $this->headSeo(); // генерируем title|description|keywords
        // $data['breadcrumbs'] = $this->buildBreadcrumbs(); // создаем хлебные крошки
        $data['menu'] = $this->load->module('menu')->index(); // формируем меню
        $data['loadPlugin'] = $this->loadPlugin; // загрузка плагинов
$data['no_index'] = true;
        //var_dump($data);

        // проверка на необходимость создания крох
        if (!$this->noBreadcrumbs) {
            $data['breadcrumbs'] = !$this->main ? $this->buildBreadcrumbs() : '';
        }

        // Вывод сообщения модуля авторизация
        if (isset($data['auth_message'])) {
            $data['template'] = $data['auth_message'];
        }

        $data['templates'] = $templates;

        $return = $this->load->view('themes/index', $data, true); // вывод данных в шаблон
        $shortcode = $this->load->config('admin/shortcode', true); // вывод шортов в переменную

        // замена шортов на значение
        foreach ($shortcode as $short => $code) {
            if (!empty($short)) {
                $shortReplace = htmlspecialchars_decode($code);
                $shortTag = '[' . $short . ']';
                $return = str_replace($shortTag, $shortReplace, $return);
            }
        }

        $sizeDo = strlen($return); // длина в байтах ДО сжатия
        //$return = str_replace(array("\n","\t",'  ','{{IMAGE}}'), array('','','',URLIMAGE), $return); // замена define на путь к изображению
        $sizeAfter = strlen($return); // длина в байтах ПОСЛЕ сжатия

        // измеряем время
        if ($this->microtime != 0) {
            $microtime = mb_strcut(microtime() - $this->microtime, 0, 5);
        }

        if ($this->use_cache) {
            $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
            if (!empty($_SERVER['REQUEST_URI'])) {
                $idq = md5($_SERVER['REQUEST_URI']);
                $this->cache->save($idq, $return, 300);
            }
        }

        $this->includeSectorScript();
		$return .= "\n" . '<!-- ' . "\n\t" . 'Размер страницы в компактном режиме: ' .
            formatSize($sizeAfter) . "\n\t" . 'Размер страницы уменьшился на: ' . formatSize($sizeDo -
            $sizeAfter) . "\n" . "\tВремя загрузки страницы: {$microtime} сек\n" . $this->
            load->view('template/admin');


        $ci = &get_instance();
        $ci->output->set_output($return);
    }

    /**
    * Полдключаем файл скрипта с названием модуля.
    * Файла может и не быть, в консоли будет ошибка, но
    * ее нужно игнорировать
    * либо проверять тут наличие
    * этого файла перед подключением
    */
    function includeSectorScript()
    {
        $fileName = mb_strtolower(get_called_class()) . '.js';
        $path = $this->config->config['sectors_scripts_path'];
        if (null !== $path = $this->config->config['sectors_scripts_path']) {
            js($path . $fileName);
        }
    }

    /** Загрузка вида (файлов шаблона) */
    function render($templateView, $masData = array(), $bool = true) {
        $return = ''; // очистка переменной в которой будут храниться виды
        $masData['langPrefix'] = $this->data['langPrefix'];
        $masData['data'] = $this->data;

        $masData = $this->_seometa($masData);

        if (is_array($templateView)) {
            foreach ($templateView as $tm) {
                $return .= $this->load->view($this->data['themes'] . $tm, $masData, $bool);
            }
        } else {
            $return .= $this->load->view($this->data['themes'] . $templateView, $masData, $bool);
        }
        return $return;
    }

    // Генерируем новые seo данные
    function _seometa($data) {
        // Берем текущую ссылку
        $url = site_url($this->uri->uri_string());

        $sg = $this->uri->segment_array();

        $offset = array_pop($sg);
        if (!empty($offset)) {
            if (ctype_digit($offset)) {
                $url = site_url(implode('/', $sg));
                $this->is_pagination = true;
                $this->url_pagination = $url;
            }
        }

        if (!empty($_GET['page'])) {
            if ($_GET['page'] == 'all') {
                $this->is_pagination = true;
                $this->url_pagination = $url;
            }
        }

        // Проверяем на наличии записи в SEO разделе

        $get_row = $this->db->where('url', $url)->get('seo_meta');
        if ($get_row) {
            $get_db = $get_row->row();
        }
        // Меняем данные если запись нашлась
        if (!empty($get_db->url)) {
            if ($get_db->url == $url) {
                $data['title'] = $this->data['title'] = !empty($get_db->title) ? $get_db->title :
                    $this->data['title'];
                $data['keywords'] = $this->data['keywords'] = !empty($get_db->keywords) ? $get_db->
                    keywords : $this->data['keywords'];
                $data['description'] = $this->data['description'] = !empty($get_db->description) ?
                    $get_db->description : $this->data['description'];
                //$data['rows']->header = !empty($get_db->name) ? $get_db->name : $data['rows']->header;
            }
        }

        return $data;
    }

    /** Генератор title. description. keywords */
    function headSeo() {
        $title = '';
        $keywords = '';
        $description = '';
        $main = $this->main;
        //dump($this->data);
        if (isset($this->data['title'])) {
            $title = $this->data['title'];
        }
        if (isset($this->data['description'])) {
            $description = $this->data['description'];
        }
        if (isset($this->data['keywords'])) {
            $keywords = $this->data['keywords'];
        }

        // берем из конфига
        $c_title = $this->data['langLine']->apex_title;
        $c_description = $this->data['langLine']->apex_description;
        $c_keywords = $this->data['langLine']->apex_keywords;
        $delimetr = !empty($title) ? $this->data['langLine']->apex_delimiter : '';

        // проверяем какой тайтл грузить
        if (!empty($title)) {
            if (!$main) {
                $t = $title . $delimetr . $c_title;
            } else {
                $t = (!empty($title) ? $title : $c_title);
            }
        } else {
            $t = $c_title;
        }

        // описание
        if (!empty($description)) {
            if (!$main) { //$d = $description.','.$c_description;
                $d = $description;
            } else {
                $d = (!empty($description) ? $description : $c_description);
            }
        } else {
            $d = $c_description;
        }

        // ключевые слова
        if (!empty($keywords)) {
            if (!$main) {
                $k = $keywords . ',' . $c_keywords;
            } else {
                $k = (!empty($keywords) ? $keywords : $c_keywords);
            }
        } else {
            $k = $c_keywords;
        }

        // выводим в переменные
        $set[] = '<title>' . $t . '</title>';
        $set[] .= "\t" . '<meta name="description" content="' . $d . '"/>';
        $set[] .= "\t" . '<meta name="keywords" content="' . $k . '"/>' . "\n";

        $cl = '';
        if (isset($this->data['canonical_link'])) {
            $cl = $this->data['canonical_link'];
        } else {
            if ($this->is_pagination && !empty($this->url_pagination)) {
                $cl = $this->url_pagination;
            }
        }

        if (!empty($cl)) {
            $set[] .= "\t" . '<link rel="canonical" href="' . $cl . '" />' .
                    "\n";
        }

        return implode("\n", $set);
    }

    /** Задаем значения хлебных крошек */
    function breadcrumbs($name = '', $link = null) {
        if (is_array($name)) {
            foreach ($name as $n) {
                if (!empty($n['link'])) {
                    $this->crumbs[] = anchor($this->data['langPrefix'] . '/' . $n['link'], $n['name']);
                } else {
                    $this->crumbs[] = $n['name'];
                }
            }
        } else {
            if ($link != null) {
                $this->crumbs[] = anchor($this->data['langPrefix'] . '/' . $link, $name);
            } else {
                $this->crumbs[] = $name;
            }
        }
    }

    /** Создаем хлебные крошки для вывода в шаблоне */
    function buildBreadcrumbs() {
        $block = htmlspecialchars_decode(config_item('bread_block')); // блок крох
        $href = htmlspecialchars_decode(config_item('bread_href')); // кроха ссылкой
        $no_href = htmlspecialchars_decode(config_item('bread_no_href')); // кроха без ссылки
        $delimetr = config_item('bread_delimiter'); // разделитель
        $count = count($this->crumbs) - 1; // к-во крох
        $arrayCrumb = $this->crumbs; // массив крох
        $crumb = ''; // возвращаем html крох

        foreach ($arrayCrumb as $key => $cr) {
            if ($count != $key) {
                $crumb .= sprintf($href, $cr . $delimetr);
            } else {
                $crumb .= sprintf($no_href, $cr);
            }
        }

        return sprintf($block, $crumb);
    }

    /** Создание переменной */
    function addVar($name, $value) {
        $this->data[$name] = $value;
    }

    /**
     * Заносим данные для просмотренных товаров в сессию CI
     * @param $id - идентификатор просмотра
     * @param $nameSession - имя сессии
     **/
    function viewSession($id, $nameSession) {
        if ($this->session->userdata($nameSession)) {
            $view = $this->session->userdata($nameSession);
            $view[$id] = $id;
            $this->session->set_userdata($nameSession, $view);
        } else {
            $view[$id] = $id;
            $this->session->set_userdata($nameSession, $view);
        }
    }

    /**
     * Формируем обычную пагинацию. 1.2.3.4...
     * @param $all_count - количество записей
     * @param $conf - конфиги
     * @param $noAjax - без ajax подгрузки
     * @param $uri - ссылка $paging['base_url'] = '/'.$uri
     * @param $num_links - количество линков возле главной страницы (слева и справа)
     * @param $uri_segment - какой по счету сегмент ссылки будет номером страници
     **/
    function paginations($array) {
        $all_count = $array['all_count'];
        //echo $all_count;
        $conf = $array['conf'];
        $noAjax = !empty($array['noAjax']) ? true : false;
        $uri = empty($array['uri']) ? '' : $array['uri'];
        $uri_segment = empty($array['uri_segment']) ? 2 : $array['uri_segment'];
        $num_links = empty($array['num_links']) ? 2 : $array['num_links'];
        $get = '';

        if (!empty($array['get'])) {
            $paging['page_query_string'] = true;
            parse_str($array['get'], $outputarr);
            unset($outputarr['page']);
            $get = '?' . http_build_query($outputarr);
        }

        if ($noAjax) {
            $return = '';
            if ($conf['Paging']) {

                $this->load->library('pagination');
                // настройка пагинации
                $paging['base_url'] = '/' . $uri . $get; // ссылка
                $paging['total_rows'] = $all_count; // количество записей
                $paging['per_page'] = $conf['perPaging']; // сколько записей выводить
                $paging['num_links'] = $num_links; // количество линков возле главной страницы (слева и справа)
                $paging['uri_segment'] = $uri_segment; // какой по счету сегмент ссылки будет номером
                $paging['next_link'] = $conf['nextPaging'] != false ? $conf['nextPaging'] : false; // следующая запись
                $paging['prev_link'] = $conf['prevPaging'] != false ? $conf['prevPaging'] : false; // предыдущая запись
                $paging['display_pages'] = true; // выводить ли цифры страниц

                $this->pagination->initialize($paging);
                $return = $this->pagination->create_links();
            }
        } else {

            $return = ceil($all_count / $conf['perPaging']);
        }

        return $return;
    }

    function my_pagination($array) {
        $all_count = $array['all_count'];
        $conf = $array['conf'];
        $noAjax = !empty($array['noAjax']) ? true : false;
        $uri = empty($array['uri']) ? '' : $array['uri'];
        $uri_segment = empty($array['uri_segment']) ? 3 : $array['uri_segment'];
        $num_links = empty($array['num_links']) ? 2 : $array['num_links'];

        $return = '';

        $this->load->library('pagination');
        // настройка пагинации
        $paging['base_url'] = '/' . $uri; // ссылка
        $paging['total_rows'] = $all_count; // количество записей
        $paging['per_page'] = $conf['perPaging']; // сколько записей выводить
        $paging['num_links'] = $num_links; // количество линков возле главной страницы (слева и справа)
        $paging['uri_segment'] = $uri_segment; // какой по счету сегмент ссылки будет номером
        $paging['next_link'] = $conf['nextPaging'] != false ? $conf['nextPaging'] : false; // следующая запись
        $paging['prev_link'] = $conf['prevPaging'] != false ? $conf['prevPaging'] : false; // предыдущая запись
        $paging['display_pages'] = true; // выводить ли цифры страниц
        $this->pagination->initialize($paging);
        $return = $this->pagination->create_links();

        return $return;
    }

    private function createOGData(& $OG) {
        $OG['type'] = 'website';
        $OG['locale'] = 'ru_RU';
        $OG['site_name'] = 'М16-Недвижимость';
        $OG['url'] = current_url() . '/';
    }

	/**
     *
     * generate page 404
     * */
    protected function _view404() {
        show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
        exit();
    }

}
/** # MY_Controller **/

/**  MY_Admin **/
class MY_Admin extends MX_Controller {
    public $data;
    public $alang;

    public function __construct() {
        parent::__construct();

        $this->session->set_userdata(array('adminPage' => true));
        $this->dx_auth->check_uri_permissions();
        $this->load->helper(array(
            'admin/disp',
            'admin/adm_func',
            'form',
            'file'));

        // Елементы по типу формы
        $this->load->library('admin/Element', '', 'elem');
        $this->data['data'] = ''; // главная переменная
        $_SESSION['CKFinder_UserRole'] = ses_data('DX_role_name');

        if (!$this->session->userdata('languages')) {
            $this->session->set_userdata('languages', 'russian');
        }
        $lng = $this->session->userdata('languages');

        // Загрузка языка
        $foreachs = $this->load->language('admin/apex', $lng, true);
        foreach ($foreachs as $key => $l) {
            $apexLang = htmlspecialchars_decode($l['name']);
            $this->alang->$key = $apexLang; // создаем переменные
        }
    }

    /** Выводим шаблон валидации с указанными данными */
    public function validate($url, $form = '.serial_form', $removeElem =
        '#timeoutId', $type = '') {
        $slesh = '';
        if (BASEURL != '') {
            $slesh = BASEURL . '/';
        }

        $data['url'] = $slesh . urls($this->uri->uri_string()) . $url;
        $data['form'] = $form;
        $data['removeElem'] = $removeElem;
        if ($type == 'array') {
            $data['func'] = 'send_onValidComplete_my';
        }
        if ($type == 'modal') {
            $data['func'] = 'send_onValidComplete_modal';
        }
        if ($type == 'table') {
            $data['func'] = 'send_onValidComplete_table';
        }
        $data['valid'] = $this->load->view('admin/template/validate', $data, true);

        return $data;
    }
    public function validate2($url, $form = '.serial_form', $removeElem =
        '#timeoutId', $type = '') {
        $slesh = '';
        if (BASEURL != '') {
            $slesh = BASEURL . '/';
        }

        $data['url'] = $slesh . $url;
        $data['form'] = $form;
        $data['removeElem'] = $removeElem;
        if ($type == 'array') {
            $data['func'] = 'send_onValidComplete_my';
        }
        if ($type == 'modal') {
            $data['func'] = 'send_onValidComplete_modal';
        }
        if ($type == 'table') {
            $data['func'] = 'send_onValidComplete_table';
        }
        $data['valid'] = $this->load->view('admin/template/validate', $data, true);

        return $data;
    }

    function admin_display($data = null) {
        if (!is_array($data)) {
            $dat['data'] = $data;
        } else {
            $dat = $data;
        }

        $dat['alang'] = $this->alang;
        $this->load->model('admin/module_model', 'model');

        if (is_super()) {
            $pr = $this->model->all_data_where('admin_menu', array('banned' => '0'), 'sort');
        } else {
            $pr = $this->model->all_data_where('admin_menu', array('banned' => '0',
                    'is_super' => '0'), 'sort');

            $role_id = ses_data('DX_role_id');
            $this->db->where('id', $role_id);
            $role_data = $this->db->get('auth_roles');
            $role_data = $role_data->row_array();
            $perm = unserialize($role_data['permissions']);
            $dsp = array();

            if (!empty($perm)) {
                foreach ($perm as $p => $b) {
                    if (count($b) > 0) {
                        if ($p != 'admin') {
                            $dsp[] = $p;
                        }
                    }
                }
            }

            foreach ($pr as $k => $a) {
                if (!empty($a['link'])) {
                    $ex = explode('/', $a['link']);
                    if (!in_array($ex[0], $dsp)) {
                        unset($pr[$k]);
                    }
                }
            }

            foreach ($pr as $k => $a) {
                $prId[] = $a['parent_id'];
            }

            foreach ($pr as $k => $a) {
                if (empty($a['link'])) {
                    if (!in_array($a['id'], $prId)) {
                        unset($pr[$k]);
                    }
                }
            }
        }

        $dat['menu'] = li_tree(create_children($pr), '');
        $this->load->view('admin/template/index', $dat);
    }

    function admin_view($template, $data = array(), $save = false) {
        if (!$this->input->is_ajax_request()) {
            $data['alang'] = $this->alang;
        }

        if ($save) {
            return $this->load->view($template, $data, $save);
        } else {
            echo $this->load->view($template, $data, $save);
        }
    }


}

/** # MY_Admin **/

/**  MY_Assignments **/
class MY_Assignments extends MX_Controller {
    public $data;
    public $alang;

    public function __construct() {
        parent::__construct();

        $this->session->set_userdata(array('assignmentPage' => true));
        $this->dx_auth->check_uri_permissions();
        $this->load->helper(array(
            'admin/disp',
            'admin/adm_func',
            'form',
            'file'));

        // Елементы по типу формы
        $this->load->library('admin/Element', '', 'elem');
        $this->data['data'] = ''; // главная переменная
        $_SESSION['CKFinder_UserRole'] = ses_data('DX_role_name');

        if (!$this->session->userdata('languages')) {
            $this->session->set_userdata('languages', 'russian');
        }
        $lng = $this->session->userdata('languages');

        // Загрузка языка
        $foreachs = $this->load->language('admin/apex', $lng, true);
        foreach ($foreachs as $key => $l) {
            $apexLang = htmlspecialchars_decode($l['name']);
            $this->alang->$key = $apexLang; // создаем переменные
        }
    }

    /** Выводим шаблон валидации с указанными данными */
    public function validate($url, $form = '.serial_form', $removeElem =
        '#timeoutId', $type = '') {
        $slesh = '';
        if (BASEURL != '') {
            $slesh = BASEURL . '/';
        }

        $data['url'] = $slesh . urls($this->uri->uri_string()) . $url;
        $data['form'] = $form;
        $data['removeElem'] = $removeElem;
        if ($type == 'array') {
            $data['func'] = 'send_onValidComplete_my';
        }
        if ($type == 'modal') {
            $data['func'] = 'send_onValidComplete_modal';
        }
        if ($type == 'table') {
            $data['func'] = 'send_onValidComplete_table';
        }
        $data['valid'] = $this->load->view('admin/template/validate', $data, true);

        return $data;
    }
    public function validate2($url, $form = '.serial_form', $removeElem =
        '#timeoutId', $type = '') {
        $slesh = '';
        if (BASEURL != '') {
            $slesh = BASEURL . '/';
        }

        $data['url'] = $slesh . $url;
        $data['form'] = $form;
        $data['removeElem'] = $removeElem;
        if ($type == 'array') {
            $data['func'] = 'send_onValidComplete_my';
        }
        if ($type == 'modal') {
            $data['func'] = 'send_onValidComplete_modal';
        }
        if ($type == 'table') {
            $data['func'] = 'send_onValidComplete_table';
        }
        $data['valid'] = $this->load->view('admin/template/validate', $data, true);

        return $data;
    }

    function admin_display($data = null) {
        if (!is_array($data)) {
            $dat['data'] = $data;
        } else {
            $dat = $data;
        }

        $dat['alang'] = $this->alang;
        $this->load->model('admin/module_model', 'model');

        if (is_super()) {
            $pr = $this->model->all_data_where('admin_menu', array('banned' => '0'), 'sort');
        } else {
            $pr = $this->model->all_data_where('admin_menu', array('banned' => '0',
                    'is_super' => '0'), 'sort');

            $role_id = ses_data('DX_role_id');
            $this->db->where('id', $role_id);
            $role_data = $this->db->get('auth_roles');
            $role_data = $role_data->row_array();
            $perm = unserialize($role_data['permissions']);
            $dsp = array();

            if (!empty($perm)) {
                foreach ($perm as $p => $b) {
                    if (count($b) > 0) {
                        if ($p != 'admin') {
                            $dsp[] = $p;
                        }
                    }
                }
            }

            foreach ($pr as $k => $a) {
                if (!empty($a['link'])) {
                    $ex = explode('/', $a['link']);
                    if (!in_array($ex[0], $dsp)) {
                        unset($pr[$k]);
                    }
                }
            }

            foreach ($pr as $k => $a) {
                $prId[] = $a['parent_id'];
            }

            foreach ($pr as $k => $a) {
                if (empty($a['link'])) {
                    if (!in_array($a['id'], $prId)) {
                        unset($pr[$k]);
                    }
                }
            }
        }

        $dat['menu'] = li_tree(create_children($pr), '');
        $this->load->view('admin/template/index', $dat);
    }

    function admin_view($template, $data = array(), $save = false) {
        if (!$this->input->is_ajax_request()) {
            $data['alang'] = $this->alang;
        }

        if ($save) {
            return $this->load->view($template, $data, $save);
        } else {
            echo $this->load->view($template, $data, $save);
        }
    }

}

/** # MY_Admin **/
