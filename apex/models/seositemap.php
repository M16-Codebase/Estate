<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
* генератор карты сайта
*
* На основе данных о страницах сайта и объектах, генерирует xml карту сайта
* @author Pavel G <pahuss@mail.ru>
* @version 1.0
* @package apex/models
*/


/**
* SeoSiteMap extends MY_Model
* @package apex/models
*/
class SeoSiteMap extends MY_Model {

    /**
     * сегменты сайта
     * */
    private $segments;

    /**
     * виды страниц
     * */
    private $pageKinds;

    /**
     * Url сайта
     * */
    private $url;

    /**
     * ссылка на файл карты сайта
     * */
    private $file;

    /**
     * количество записанных урлов в карту
     * */
    private $written_urls;

    /**
     * храни массив с правилами файла robots.txt
     * */
     private $robots_rules;

    /**
     * имя файла карты сайта
     * */
    //const FILE_NAME = 'current_sitemap';
    const FILE_NAME = 'sitemap';

    /**
     * системный путь до каталога с сайтом
     * */
    const SITE_ROOT_PATH = FCPATH;

    const USER_AGENT_DIRECTIVE = 'User-agent:';
    const ALLOW_DIRECTIVE = 'Allow:';
    const DISALLOW_DIRECTIVE = 'Disallow';

    const PAGES = 'pages';
    const ARTICLES = 'articles';
    const OBJECTS = 'objects';

    /**
     * урлов в файле не более
     * */
    const URLS_PER_FILE = 5000;


    const NEWS_TABLE = 'ci_news';


    public function __construct() {
        parent::__construct();



        $this->load->helper('url');
        $this->loadRobotsTxt();

        $this->load->model('news/admin_news_model', 'news');
        $this->load->model('buildings/buildings_model', 'buildings');
        $this->load->model('otzuv/otzuv_model', 'otzuv');

        $this->pageKinds = ['pages', 'segments'];
        $this->url = base_url();

    

        $this->segments = [
            'pages' => [
                'root',
                'excursion',
                'kontakty',
                'o-kompanii',
                'catalog',
                'interest',
                'sell-appart',
                'regionalnym-klientam',
                'military',
                'partners',
                'calculator',
            ],

            'arcticles' => [
                'news',
                'otzuv',
            ],
            'objects' => [
                'buildings',
                'resale',
                'residential',
                'exclusive',
                'commercial',
                'assignment',
                'arenda',
            ]
        ];
    }


    public function generate() {

        // создать или открыть файл карты, записать в него шапку
        $this->createSiteMapFile();

        // создать карту для страниц
        $this->createPagesSitemapUrls();

        // создать карту для статей
        $this->createArticlesSitemapUrls();

        // создать карту для объектов
        $this->createObjectsSitemapUrls();

        $this->createArendaSitemapUrls();

        $this->createCustomSitemapsUrls();

        $this->closeMapFile();

        exit();

    }


    private function createCustomSitemapsUrls() {
        $this->addUrlToSiteMapFile( 'commercial/arenda' );
        $this->addUrlToSiteMapFile( 'commercial/prodazha' );
        $this->addUrlToSiteMapFile( 'gifts' );
        $this->addUrlToSiteMapFile( 'privacy_policy' );
        $this->addUrlToSiteMapFile( 'special' );
        $this->addUrlToSiteMapFile( 'residential/dom' );
        $this->addUrlToSiteMapFile( 'residential/zemelnyj-uchastok' );
        $this->addUrlToSiteMapFile( 'residential/kottedzh' );
        $this->addUrlToSiteMapFile( 'residential/taunhaus' );

        ///privacy_policy<
    }



    private function createArendaSitemapUrls() {

        $rent = 'arenda';

        $this->addUrlToSiteMapFile( $rent );

        //commercial/arenda
        $res = $this->db->query("SELECT `link` FROM `ci_arenda` WHERE `banned` = 0  AND `link` IS NOT NULL AND `link` <> ''");
        $objs = $res->result_array();

        foreach ($objs as $obj) {

            if (!$this->validate($obj['link'])) {
                continue;
            }
            $this->addUrlToSiteMapFile( $rent . '/' . $obj['link'] );
        }

    }


    private function createObjectsSitemapUrls() {
        global $h;
        $uriArray = array(
                0 => 'buildings',
                1 => 'resale',
                8 => 'assignment',
                2 => 'residential',
                3 => 'elite',
                9 => 'exclusive',
                4 => 'commercial',
                6 => 'land',
                );

        foreach ($uriArray as $key=>$uri) {
            if ($key === 3) continue;
            $this->addUrlToSiteMapFile( $uri );
        }

        $res = $this->db->query("SELECT `id`, `link`, `razdelu`, `metro_id`, `rayon_id`  FROM `ci_buildings`
            WHERE `banned` = 0  AND `razdelu` IS NOT NULL AND `razdelu` <> '' AND `razdelu` <> 3 AND `razdelu` <> 110");
        $objs = $res->result_array();

        $rayon_res = $this->db->query("SELECT `id`, `link` FROM `ci_rayon` WHERE 1 ");
        $rayon = $rayon_res->result_array();

        $metro_res = $this->db->query("SELECT `id`, `link` FROM `ci_metro` WHERE 1 ");
        $metro = $metro_res->result_array();

        // надо сделать так чтобы был массив метро_ид = метро_название
        // для рацоной тоже самое

        $merto_vals = [];
        foreach ($metro as $item) {
            $merto_vals[(int)$item['id']] = $item['link'];
        }

        $rayon_vals = [];
        foreach ($rayon as $item) {
            $rayon_vals[(int)$item['id']] = $item['link'];
        }
        $metro_r = [];
        $rayon_r = [];

        foreach ($objs as $obj) {

            if ($obj['razdelu'] == 0 || $obj['razdelu'] == 1 || $obj['razdelu'] == 8) {
                if (!empty($obj['metro_id'])) {
                    $metro_r[(int)$obj['razdelu']][] = (int)$obj['metro_id'];
                }
                //if ($obj['razdelu'] == 0) {

                    //$h->debug($merto_vals[$obj['metro_id']]);
                //}
            }

            if (!empty($obj['rayon_id'])) {
                $rayon_r[(int)$obj['razdelu']][] = (int)$obj['rayon_id'];
            }

            if (!$this->validate($obj['link'])) {
                continue;
            }
            $this->addUrlToSiteMapFile( $uriArray[(int)$obj['razdelu']] . '/' . $obj['link'] );
        }



        foreach ($metro_r as $razdel=>$ids) {
            $links = array();
            $razdel_link = $uriArray[$razdel];
            foreach ($ids as $id) {

                if (in_array($razdel, array(0,1,8))) {
                    $b = $this->buildings->metroFilter($razdel_link, $id);
                    if ($b['countRows']) {
                        $links[] = $razdel_link . '/' . $merto_vals[$id];
                    }
                }

                if (!empty($merto_vals[$id])) {
                    $links[] = $razdel_link . '/' . $merto_vals[$id];
                }
            }
            $links = array_unique($links);
            foreach ($links as $link) {
                $this->addUrlToSiteMapFile($link);
            }
        }

        foreach ($rayon_r as $razdel=>$ids) {
            $links = array();
            $razdel_link = $uriArray[$razdel];
            foreach ($ids as $id) {
                if (!empty($rayon_vals[$id])) {
                    $links[] = $razdel_link . '/' . $rayon_vals[$id];
                }
            }
            $links = array_unique($links);
            foreach ($links as $link) {
                $this->addUrlToSiteMapFile($link);
            }
        }
        //dump($rayon_vals);
        //dump($rayon_r);
    }


    private function createSiteMapFile(){
        $this->written_urls = 0;
        //echo self::SITE_ROOT_PATH . self::FILE_NAME . '.xml';
        //exit;
        $this->file = fopen(self::SITE_ROOT_PATH . self::FILE_NAME . '.xml', 'w');
        fputs($this->file, '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL);
        fputs($this->file, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"'
            . ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"'
            . ' xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'
            . PHP_EOL);
    }

    private function closeMapFile() {
        fputs($this->file, '</urlset>' . PHP_EOL);
        fclose($this->file);
    }

    private function createPagesSitemapUrls() {
        foreach ($this->segments[self::PAGES] as $page) {
            if ($page == 'root') $page = '';
            $this->addUrlToSiteMapFile($page);
        }
    }


    private function createArticlesSitemapUrls() {
        global $h;
        $this->addUrlToSiteMapFile('news');
        $this->addUrlToSiteMapFile('news/cat/novosti');
        $this->addUrlToSiteMapFile('news/cat/stati');
        $where = array(
            'exact_values' => array('banned' => 0)
        );

        $news = $this->news->getNews('link', $where);

        foreach ($news as $link) {
            //dump($link['link']);

            if (!$this->validate($link['link'])) {
                continue;
            }
            $this->addUrlToSiteMapFile('news/' . $link['link'] );
        }

        $this->addUrlToSiteMapFile('otzuv');
        $otzuvAll = $this->otzuv->getAll();

        foreach ($otzuvAll as $link) {
            //dump($link['link']);

            if (!$this->validate($link['otzuv_key'])) {
                continue;
            }
            $this->addUrlToSiteMapFile('otzuv/' . $link['otzuv_key'] );
        }
    }


    /**
     * Запись урла в sitemap
     * Метод публичный для возможности добавления урлов из хелперов
     * @param string $loc - урл страницы
     * @param string $lastmod дата обновления страницы
     * @param string $priority
     * @param bool $check_is_loaded нужно ли проверять, попадание урла в кастомный список (FALSE только для кастомного списка) @TODO нужно ли?
     * @return bool FALSE если урл запрещен в robots.txt или дополнительными правилами от сеошников
     */
    public function addUrlToSiteMapFile($loc){
        // Проверяем, разрешен ли урл к индексации в роботс
        if (!$allowed = $this->isUrlAllowed($loc)){
            return FALSE;
        }

        if (empty($this->file)){
            $this->createSiteMapFile();
        }

        fputs($this->file, '<url>');
        fputs($this->file, '<loc>' . $this->url . $loc . '</loc>');
        fputs($this->file, '</url>' . PHP_EOL);
        $this->written_urls ++;
        if ($this->written_urls >= self::URLS_PER_FILE){
            $this->saveSiteMapFile();
        }
        return TRUE;
    }

    /**
     * Дополнительно проверяем урл
     * не пропускаем урлы с нижним слешем впереди
     * */
    public function validate($loc) {
        if (strpos($loc, '_') === 0 || empty($loc)) {
            return false;
        }
        return true;
    }


    /**
     * Парсит robots.txt, правила из роботса записываются в $this->robots_rules в формате
     * array(
     *    array(
     *       'allow' => bool,
     *       'regex' => bool,
     *       'url' => string
     *    ),
     *    ...
     * )
     */
    public function loadRobotsTxt(){
        $this->robots_rules = array();
        $robots_file = fopen(self::SITE_ROOT_PATH . '/robots.txt', 'r');
        if ($robots_file !== FALSE){
            $current_user_agent = NULL;
            $rules_array = array();

            while($line = fgets($robots_file)) {
                if (mb_substr($line, 0, mb_strlen(self::USER_AGENT_DIRECTIVE)) == self::USER_AGENT_DIRECTIVE) {
                    $user_agent = trim(mb_substr($line, mb_strlen(self::USER_AGENT_DIRECTIVE)));
                    $current_user_agent = $user_agent;
                } elseif (empty($line)) {
                    $current_user_agent = NULL;
                } elseif (!empty($current_user_agent))
                {
                    $rules_array[$current_user_agent][] = $line;
                }
            }
            fclose($robots_file);

            usort($rules_array, function($a, $b){
                $a = explode(':', $a);
                $b = explode(':', $b);
                $a = isset($a[1]) ? $a[1] : '';
                $b = isset($b[1]) ? $b[1] : '';
                return strlen($a) > strlen($b) ? '+1' : '-1';
            });
            foreach($rules_array as $line){

                if ( is_array($line) ) {
                    foreach ($line as $l) {
                        $l = trim($l);
                        $allow = (mb_strpos($l, self::ALLOW_DIRECTIVE) === 0);
                        $url = $this->prepareRuleUrl(trim(mb_substr($l, ($allow ? mb_strlen(self::ALLOW_DIRECTIVE) : mb_strlen(self::DISALLOW_DIRECTIVE)) + 1)), $is_regex);
                        if (empty($url)) continue;
                        $this->robots_rules[] = array(
                            'allow' => $allow,
                            'regex' => $is_regex,
                            'url' => $url
                        );
                    }
                } else {
                    $line = trim($line);
                    $allow = (mb_strpos($line, self::ALLOW_DIRECTIVE) === 0);
                    $url = $this->prepareRuleUrl(trim(mb_substr($line, ($allow ? mb_strlen(self::ALLOW_DIRECTIVE) : mb_strlen(self::DISALLOW_DIRECTIVE)) + 1)), $is_regex);
                    if (empty($url)) continue;
                    $this->robots_rules[] = array(
                        'allow' => $allow,
                        'regex' => $is_regex,
                        'url' => $url
                    );
                }
            }
        }
    }

    /**
     * Проверяем урл, при необходимости преобразуем его в регулярку
     * @param string $url исходный урл
     * @param bool $is_regex
     * @return string преобразованный урл
     */
    private function prepareRuleUrl($url, &$is_regex){
        $is_regex = FALSE;
        $strict = mb_substr($url, -1) == '$';
        if ($strict) {
            $url = mb_substr($url, 0, -1);
        }
        if (mb_strpos($url, '*') !== FALSE || $strict){
            $is_regex = true;
            $url = '~^' . implode('.+', array_map('preg_quote', explode('*', $url))) . ($strict ? '$' : '') . '~';
        }
        return $url;
    }


    /**
     * Проверяем, разрешен ли url для размещения в sitemap.xml
     * @param string $url
     * @return bool
     */
    private function isUrlAllowed($url){
        // проверяем по правилам robots.txt
        $allow = $this->checkUrlViaRulesGroup($url, $this->robots_rules);
        return $allow;
    }


    /**
     * Проверяем, позволяют ли правила передавать url в sitemap
     * @param string $url - проверяемый url
     * @param array $rules - набор правил в формате
     *          array(
     *             array(
     *                'allow' => bool,
     *                'regex' => bool,
     *                'url' => string
     *             ),
     *             ...
     *          )
     * @return bool
     */
    private function checkUrlViaRulesGroup($url, $rules){
        $allow = true;
        foreach($rules as $rule){
            $match = $rule['regex'] ? preg_match($rule['url'], $url) : (mb_substr($url, 0, mb_strlen($rule['url'])) == $rule['url']);
            if ($match){
                $allow = $rule['allow'];
            }
        }

        return $allow;
    }

}
