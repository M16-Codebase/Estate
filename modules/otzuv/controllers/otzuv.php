<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Модуль: Отзывы
 **/

class otzuv extends MY_Controller {

    private $conf; // конфиг файл
    private $lang; // языковый файл

    private $met;

    function __construct() {
        // конструктор
        parent::__construct();

        include (MDPATH . 'otzuv/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];

        // определяем, нужно ли использовать роутер
        if (!empty($moduleinfo['router'])) {
            $this->link = $moduleinfo['router'];
        } else {
            $this->link = $this->module;
        }
    }

    /** Роутер модуля */
    function _remap($method, $argument) {
        $this->met = $method;
        if ($moduleinfo['status'] != 0) // проверка на доступность модуля
            {
            return false;
        } else {
			$str = explode(' ',substr($_SERVER['REQUEST_URI'],7,1));
			$str2 = explode(' ', '1 2 3 4 5 6 7 8 9');
			$res = array_intersect($str, $str2);
			//print_r($str);
			//print_r($str2);
			if($res){
				//echo'ind';
				$this->index(false,$method);
				return;
			}
            if (isset($argument[0]))
                $u = $argument[0];
            else
                $u = 0; // сегмент ссылки
            // если существует метод, то запускаем его
            if (method_exists($this, $method) || $method == 'tag') {

                if ($method == 'index') {
                    $this->index();
                } elseif ($method == 'tag') {
                    $this->index(uri(3));
                } elseif ($method == 'limit') {
                    $this->limit($u);
                } elseif ($method == 'search﻿') {
                    $this->searchFunction($u);
                } elseif ($method == 'view') {
                    $this->view();
                } else {
                    show_404('Метода не существует: ' . $this->uri->uri_string());
                }
            } else {

                // если идет пагинация
                if (is_numeric($method)) {
                    $this->index($method);
                } else {
                    // иначе просмотр конкретной записи
                    $this->singleReview($method);
                }
            }
        }
    }

    /** Опции модуля */
    function setOptions() {
        // загрузка настроек
        $this->conf = $this->load->config($this->module . '/' . $this->module, true);

        // загрузка языка
        $foreach = $this->load->language($this->table . '_mod', '', true);
        foreach ($foreach as $key => $l) {
            $this->lang->$key = htmlspecialchars_decode($l['name']);
        }

        // выводить или не выводить хлебные крошки для модуля
        $this->noBreadcrumbs = $this->conf['breadcrumbs'];
    }

    /** Главная */
    function index($tag = false,$method=0) {
		if(strpos($_SERVER['REQUEST_URI'],'tag') or strpos($_SERVER['REQUEST_URI'],'?')){
			$offset = 0;
			$perpaging=500;
		}else{
        $offset = $method;
		$perpaging=16;
		}
        $this->setOptions(); // заносим опции в переменные

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $this->load->model('votes/votes_model', 'votes');
        $ip = get_ip();
        $pre = $this->votes->getAll($ip);
        $data['votes'] = $pre;

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;
        $params = array();
        $search = '';
        $tagsl = '';
        if (!empty($_GET['search'])) {
            $params['search'] = $_GET['search'];
            $search = $_GET['search'];
        }

        if (!empty($tag)) {
			//echo'par';
            $params['tag'] = $tag;
            $tagsl = urldecode($tag);
        }
		//echo $offset;
        $dataRow = $this->$model->paginations($offset, $params, $perpaging); // вытаскиваем данные
        //dump($dataRow);
        if ($dataRow) // проверяем есть ли данные
            {
            // проверяем нужно ли использовать пагинацию
            $arrayPagination = array(
                'all_count' => $this->$model->all_count(),
                'conf' => $data['conf'],
                'noAjax' => true,
                'uri' => $this->module,
                'uri_segment' => 2,
                'num_links' => 2);
            $data['pagination'] = $this->paginations($arrayPagination);
            $data['search'] = $search;
            $data['tag'] = $tagsl;
            // проходим цикл для формирования данных
            foreach ($dataRow as $key => $value) {
                $tags = explode(',', $value['tags']);
                $tag = array();
                if (count($tags) > 0) {
                    foreach ($tags as $tg) {
                        $g = trim($tg);
                        if (!empty($g))
                            $tag[] = '<a href="/otzuv/tag/' . $g . '">' . trim($g) . '</a>';
                    }
                }
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = '/otzuv/' . $value['otzuv_key'];
                $data['rows']->$key->id = $value['id'];
                $data['rows']->$key->tags = $tag;
                if (mb_strlen(strip_tags($value['text'])) > 240) {
                    $data['rows']->$key->text = mb_substr(strip_tags($value['text']), 0, 240) .
                        '...' . '&nbsp;' . '<a target="_blank" href="' . $data['rows']->$key->link .
                        '" class="more-link">Далее</a>';
                } else {
                    $data['rows']->$key->text = strip_tags($value['text']);
                }
                $data['rows']->$key->date = date('d.m.Y', $value['date']);
                $data['rows']->$key->photo_count = $value['photo_count'];
                $data['rows']->$key->audio_count = $value['audio_count'];
                $data['rows']->$key->foto = unserialize($value['foto']);
                //$data['rows']->$key->foto = $value['foto'];
                $data['rows']->$key->video_count = $value['video_count'];
                $data['rows']->$key->comment_count = $value['comment_count'];
                $data['rows']->$key->likes = $this->$model->getLikes($value['id']);
                if ($value['audio_count'] > 0 && !empty($value['audio'])) {
                    $this->load->model('files_table/files_table_model', 'files_table_model');
                    $fs = explode(',', $value['audio']);
                    $qs = $this->files_table_model->getFiles($fs);
                    $data['rows']->$key->audio = $qs;
                }
            }

            /* Stas empty page fix */
        } elseif (
            empty($dataRow)
            && (
                is_numeric ($this->met)
            )
        ) {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
            return;
        }

        $most = $this->$model->getMostComment();
        $mostcomment = array();
        foreach ($most as $key => $value) {
            $onec = array();
            $tags = explode(',', $value['tags']);
            $tag = array();
            if (count($tags) > 0) {
                foreach ($tags as $tg) {
                    $g = trim($tg);
                    if (!empty($g))
                        $tag[] = '<a href="/otzuv/tag/' . $g . '">' . trim($g) . '</a>';
                }
            }
            $onec['name'] = $value['name'] . ':';
            //$onec['link'] = '/otzuv/view/' . $value['id'];
            $onec['link'] = '/otzuv/' . $value['otzuv_key'];
            $onec['id'] = $value['id'];
            $onec['tags'] = $tag;
            if (mb_strlen(strip_tags($value['text'])) > 70) {
                $onec['text'] = mb_substr(strip_tags($value['text']), 0, 70) . '...';
            } else {
                $onec['text'] = strip_tags($value['text']);
            }
            /*$data['rows']->$key->date = date('d.m.Y', $value['date']);
            $data['rows']->$key->photo_count = $value['photo_count'];
            $data['rows']->$key->audio_count = $value['audio_count'];
            $data['rows']->$key->video_count = $value['video_count'];
            $data['rows']->$key->comment_count = $value['comment_count'];
            $data['rows']->$key->likes = $this->$model->getLikes($value['id']);*/
            $mostcomment[] = $onec;
        }

        $data['mostcomment'] = $mostcomment;

        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);

        // задаем хлебную кроху
        if (!empty($this->lang->md_breadcrumbs)) {
            $this->breadcrumbs($this->lang->md_breadcrumbs);
        } else {
            $this->breadcrumbs($this->lang->md_header);
        }

        //dump($data);
        $this->addVar('template', $this->render('tmpl/otzuv', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    /**
     * Просмотр записи (новый метод взамен view() )
     * Для отзывов сделано ЧПУ поэтому
     * тот не подходит
     * Вытаскивает запись по ключу
     *
     */
    function singleReview($key) {

        $this->setOptions(); // заносим опции в переменные

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataid = 0;
        $u = uri(3);
        if (!empty($u)) {
            $dataid = (int)$u;
        }

        $dataRow = $this->$model->getRowByKey($key); // вытаскиваем данные
        if (empty($dataRow)) {
            $pos = strripos($key, '-');
            $key = substr($key, 0, $pos) . '-20' . substr($key, $pos+1, 2);
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: http://m16-estate.ru/otzuv/{$key}");
            exit();
            //dump("m16-estate.ru/otzuv/{$key}");

        }
        if (!empty($dataRow)) {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид
            $data['rows']->id = $dataRow['id'];
            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->foto = unserialize($dataRow['foto']);
            $data['rows']->video = unserialize($dataRow['video']);
            $data['rows']->date = date('d.m.Y', $dataRow['date']);
            $data['rows']->photo_count = $dataRow['photo_count'];
            $data['rows']->audio_count = $dataRow['audio_count'];
            $data['rows']->video_count = $dataRow['video_count'];
            $data['rows']->comment_count = $dataRow['comment_count'];
            $data['rows']->likes = $this->$model->getLikes($dataRow['id']);
            if ($dataRow['audio_count'] > 0 && !empty($dataRow['audio'])) {
                $this->load->model('files_table/files_table_model', 'files_table_model');
                $fs = explode(',', $dataRow['audio']);
                $qs = $this->files_table_model->getFiles($fs);
                $data['rows']->audio = $qs;
            }

            // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
        } else {

            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
            return;
        }

        // генерируем title, keywords, description
        $h1 = 'Отзыв клиента ' . $dataRow['name'];
        $title = $h1 . ' | М16 Недвижимость';

        $descr = 'Агентство недвижимости Вячеслава Малафеева «М16» в СПб. Честный отзыв клиента ' .
            $dataRow['name'];
        if (!empty($dataRow['tags'])) {
            $descr .= ' о работе менеджера \'M16\' ' . $dataRow['tags'];
        }
        $data['header1'] = $h1;
        $this->addVar('title', $title);
        $this->addVar('description', $descr);

        $site_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
        $size = getimagesize($site_url . $data['rows']->foto['foto'][0]);
        $w = $size[0];
        $h = $size[1];

        $og['title'] = $title;
        $og['description'] = $descr;
        $og['image'] = $data['rows']->foto['foto'][0];
        $og['width'] = $w;
        $og['height'] = $h;

        $this->addVar('OG', $og);

        $dt = new DateTime();

        $dt = $dt->setTimestamp($dataRow['date']);
        $date = $dt->format('Y-m-d');
        $time = $dt->format("h:i:s");
        $article_published = $date.'T'.$time.'+00:00';

        $this->addVar('article_published', $article_published);

        // задаем крохи
        if (!empty($this->lang->md_breadcrumbs)) {
            $brd = $this->lang->md_breadcrumbs;
        } else {
            $brd = $this->lang->md_header;
        }
        //dump($dataRow);
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('tmpl/otzuv_view', $data)); // формируем шаблон

        $this->viewPage($this->data); // выводим весь вид
    }

    /** Просмотр одной записи */
    function view() {
        /*редиректим*/
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $dataid = 0;
        $u = uri(3);
        if (!empty($u)) {
            $dataid = (int)$u;
        }

        if (isset($this->uri->segments[4])) {
            $this->_view404();
        }

        $dataRow = $this->$model->getRow(null, $dataid); // вытаскиваем данные

        if (empty($dataRow)) {
            $this->_view404();
        }

        $r_scheme = $_SERVER['REQUEST_SCHEME'];
        $host = $_SERVER['HTTP_HOST'];

        header("HTTP/1.1 301 Moved Permanently");
        header("Location: {$r_scheme}://{$host}/otzuv/{$dataRow['otzuv_key']}");
        exit();

        /**ВСЕ КОНЕЦ!!!*/

        $this->setOptions(); // заносим опции в переменные

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        if (!empty($dataRow)) {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид
            $data['rows']->id = $dataRow['id'];
            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];
            $data['rows']->foto = unserialize($dataRow['foto']);
            $data['rows']->video = unserialize($dataRow['video']);
            $data['rows']->date = date('d.m.Y', $dataRow['date']);
            $data['rows']->photo_count = $dataRow['photo_count'];
            $data['rows']->audio_count = $dataRow['audio_count'];
            $data['rows']->video_count = $dataRow['video_count'];
            $data['rows']->comment_count = $dataRow['comment_count'];
            $data['rows']->likes = $this->$model->getLikes($dataRow['id']);
            if ($dataRow['audio_count'] > 0 && !empty($dataRow['audio'])) {
                $this->load->model('files_table/files_table_model', 'files_table_model');
                $fs = explode(',', $dataRow['audio']);
                $qs = $this->files_table_model->getFiles($fs);
                $data['rows']->audio = $qs;
            }

            // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
        } else {
            $this->_view404();
        }

        // генерируем title, keywords, description
        $this->addVar('title', $dataRow['title']);
        $this->addVar('keywords', $dataRow['keywords']);
        $this->addVar('description', $dataRow['description']);

        // задаем крохи
        if (!empty($this->lang->md_breadcrumbs)) {
            $brd = $this->lang->md_breadcrumbs;
        } else {
            $brd = $this->lang->md_header;
        }
        $this->breadcrumbs($brd, $this->data['uri1']);
        $this->breadcrumbs($dataRow['name']);

        $this->addVar('template', $this->render('tmpl/otzuv_view', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    /**
     * Vuvod limitnogo k-va zapisey
     * @param $offset = 3 - k-vo zapisey
     * @param $template = true - vuvod html
     **/
    function limit($offset = 1, $template = true) {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = (object)array(); // переменная для хранения данных
        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные
        if ($dataRow) {
            //dump($dataRow);
            foreach ($dataRow as $key => $value) // проходим цикл для формирования данных
                {
                $data['rows']->$key = (object)array();
                $data['rows']->$key->name = $value['name'];
                if (mb_strlen($value['text']) > 300) {
                    $data['rows']->$key->link = $value['otzuv_key'];
                    $data['rows']->$key->text = mb_substr($value['text'], 0, 300) . "...";
                } else {
                    $data['rows']->$key->text = $value['text'];
                }

            }
        }
        //dump($data);
        // возвращаем вид
        if ($template) {
            echo $this->render('tmpl/otzuv_limit', $data);
        } else {
            return $data['rows'];
        }
    }
	function limits($offset = 1, $template = true) {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = (object)array(); // переменная для хранения данных
        $dataRo = $this->$model->getRowByKey('otzyv-klienta-maksim-i-svetlana-04-05-2015'); // вытаскиваем данные
		//print_r($dataRow);
		//exit;
		$dataRow[]=$dataRo;
        if ($dataRow) {
            //dump($dataRow);
            foreach ($dataRow as $key => $value) // проходим цикл для формирования данных
                {
                $data['rows']->$key = (object)array();
                $data['rows']->$key->name = $value['name'];
                if (mb_strlen($value['text']) > 300) {
                    $data['rows']->$key->link = $value['otzuv_key'];
                    $data['rows']->$key->text = mb_substr($value['text'], 0, 300) . "...";
                } else {
                    $data['rows']->$key->text = $value['text'];
                }

            }
        }
        //dump($data);
        // возвращаем вид
        if ($template) {
            echo $this->render('tmpl/otzuv_limit', $data);
        } else {
            return $data['rows'];
        }
    }

    /** Пагинация на ajax */
    function ajaxPagination() {
        $offset = $this->input->post('page', true); // какую страницу грузить, номер страницы
        $uri2 = $this->input->post('uri2', true);
        $uri3 = $this->input->post('uri3', true);
        $uri4 = $this->input->post('uri4', true);
        $uri5 = $this->input->post('uri5', true);

        /** код для вытаскивания данных */

        // переменная шаблона
        $ok = '';

        // что-то делаем со всеми данными, которые в $_POST
        echo json_encode(array('ok' => $ok));
    }

    /** Отзыв */
    function ajaxComment() {
        $name = $this->input->post('name', true);
        $text = nl2br(htmlspecialchars($this->input->post('text', true)));
        $id = $this->input->post('id', true);
        $return = true; // выполнять ли запрос в базу
        $usedCaptcha = false; // использовать ли каптчу

        if ($usedCaptcha) {
            $captcha = $this->input->post('captcha', true);
            if ($captcha == $this->session->userdata('captcha_num')) // проверка каптчи
                {
                $return = true;
            } else {
                $return = false;
                $ok = 'errorCaptcha';
            }
        }

        if ($return) {
            $model = $this->table . '_model';
            $this->load->model($this->module . '/' . $model, $model);

            $array = array(
                'tovar_id' => $id,
                'name' => $name,
                'short_text' => $text,
                'date' => date('Y-m-d'));
            if ($this->$model->module_add($array)) {
                $ok = 'success';
            } else {
                $ok = 'failure';
            }
        }

        echo json_encode(array('ok' => $ok));
    }

    /** Поиск */
    function searchFunction($search = '') {

        if (empty($search)) {
            $search = $this->input->post('search_', true);
            $this->session->set_userdata('search_', $search);
            redirect('/' . $this->link . '/search/' . $search);
        }

        $search = $this->session->userdata('search_');

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $this->breadcrumbs('Поиск');

        /** данные */

        // переменная шаблона
        $this->addVar('template', $this->render('default', $data));
        $this->viewPage($this->data);
    }
    /*
    public function headSeo() {
    return array(
    'title' => 'kjfhkjhskjzhkchjx'
    );
    }*/

}
/* End of file */
