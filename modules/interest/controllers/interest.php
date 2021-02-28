<?php

//if (!defined('BASEPATH'))
    //exit('No direct script access allowed');

/**
 * Модуль: Интересные предложения
 **/

class interest extends MY_Controller {

    private $conf; // конфиг файл
    private $lang; // языковый файл

    function __construct() {
        // конструктор
        parent::__construct();

        include (MDPATH . 'interest/moduleinfo.php');
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
        if ($moduleinfo['status'] != 0) // проверка на доступность модуля
            {
            return false;
        } else {
            if (isset($argument[0]))
                $u = $argument[0];
            else
                $u = 0; // сегмент ссылки
            if (method_exists($this, $method)) // если существует метод, то запускаем его
                {
                if ($method == 'index') {
                    $this->index();
                } else
                    if ($method == 'limit') {
                        $this->limit($u);
                    } else
                        if ($method == 'search') {
                            $this->searchFunction($u);
                        } else {
                            show_404('Метода не существует: ' . $this->uri->uri_string());
                        }
            } else {
                if (is_numeric($method)) // если идет пагинация
                    {
                    $this->index($method);
                } else // иначе просмотр конкретной записи
                {
                    $this->view();
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
    function index($offset = 0) {
        $this->setOptions(); // заносим опции в переменные

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

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);


        $data['data'] = array();

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->pagination($offset); // вытаскиваем данные
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
            // проходим цикл для формирования данных
            foreach ($dataRow as $key => $v) {
                if($v['link']=='123'){
                    continue;
                }
                $row = array();
                $data['rows']->$key = (object)array();
                $data['rows']->$key->metro = $parent_idmetro[$v['metro']];
                $data['rows']->$key->rayon = $parent_idrayon[$v['rayon']] . ' район';
                $data['rows']->$key->name = $v['name'];
                //if ($v['category'] == 0){
                //    $data['rows']->$key->deadline = $this->dataKv($v['deadline']);
                //}
                $data['rows']->$key->deadline = $v['line_middle'];
                $data['rows']->$key->adress = $v['address'];
                $price = '';
                if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб / <span>' . $this->
                        number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                        '</span> тыс руб';
                } elseif ($v['price_arenda'] > 0) {
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                        1000), 1) . '</span> тыс руб';
                } elseif (!empty($v['price']) && $v['price'] > 0) {
                    if ($v['category'] == 0) {
                        $price = 'от ';
                    }
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб';
                } else {
                    $price = 'по запросу';
                }
                $data['rows']->$key->price = $price;
                $data['rows']->$key->link = $this->getLink($v['category'], $v['link']);
                $data['rows']->$key->foto = $v['foto'];
                $data['rows']->$key->price_arenda = $v['price_arenda'];
                $data['rows']->$key->sign = $this->getCategory($v['category']);
                $data['rows']->$key->mortgage = $v['ipoteka'];

            }
        }

        // генерируем title, keywords, description
        $this->addVar('title', $this->lang->md_title);
        $data['pageHeader'] = $this->lang->md_title;
        $this->addVar('keywords', $this->lang->md_keywords);
        $this->addVar('description', $this->lang->md_description);

        // задаем хлебную кроху
        if (!empty($this->lang->md_breadcrumbs)) {
            $this->breadcrumbs($this->lang->md_breadcrumbs);
        } else {
            $this->breadcrumbs($this->lang->md_header);
        }

        $this->addVar('template', $this->render('interest', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    /** Просмотр одной записи */
    function view() {
        $this->setOptions(); // заносим опции в переменные

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->getRow($this->data['uri2']); // вытаскиваем данные
        if (!empty($dataRow)) {
            // $this->viewSession($dataRow['id']); // заносим в сессию ид

            $data['rows']->header = $dataRow['name'];
            $data['rows']->text = $dataRow['text'];

            // $this->$model->module_edit($dataRow['id'], array('views' => ($dataRow['views'] + 1)));
        } else {
            show_404('404: Страница - ' . $this->uri->uri_string() . ' не найдена');
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

        $this->addVar('template', $this->render('default', $data)); // формируем шаблон
        $this->viewPage($this->data); // выводим весь вид
    }

    /**
     * Vuvod limitnogo k-va zapisey
     * @param $offset = 3 - k-vo zapisey
     * @param $template = true - vuvod html
     **/
    function limit($offset = 3, $template = true) {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $data['rows'] = ''; // переменная для хранения данных
        $data['lang'] = $this->lang;
        $data['conf'] = $this->conf;

        $dataRow = $this->$model->limitRow($offset); // вытаскиваем данные
        if ($dataRow) {
            foreach ($dataRow as $key => $value) // проходим цикл для формирования данных
                {
                $data['rows']->$key->name = $value['name'];
                $data['rows']->$key->link = BASEURL . '/' . $this->link . '/' . $value['link'];
                $data['rows']->$key->foto = thumbImage($value['mainfoto'], $this->module);
                $data['rows']->$key->text = $value['short_text'];
            }
        }

        // возвращаем вид
        if ($template) {
            return $this->render('default', $data);
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

    function getRandom() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $count = $this->$model->getInterestCount();

        $diff = $count / 9;
        $rnd = floor($diff);

        $random = rand(0, $rnd);
        if ($random == 0) {
            return 0;
        }
        if ($random < $rnd) {
            return $random * 9 - 1;
        } else {
            return $count - 10;
        }
        return 0;
    }

    function showHide() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $dataRow = $this->$model->getInterestHide();

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['name'] = $v['name'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                    1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                    1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                if ((int)$v['category'] == 0) {
                    $price = 'от ';
                }
                if ((int)$v['category'] == 5)
                    $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                else
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб';
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/tmpl/' . 'interest', $data);
    }

    function showMain() {

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

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $off = $this->getRandom();

        $dataRow = $this->$model->getInterests($off);

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['metro'] = $parent_idmetro[$v['metro']];
            $row['rayon'] = $parent_idrayon[$v['rayon']] . ' район';
            $row['name'] = $v['name'];
            //if ($v['category'] == 0){
            //    $row['deadline'] = $this->dataKv($v['deadline']);
            //}
            $row['deadline'] = $v['line_middle'];
            $row['address'] = $v['address'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                    1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                    1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                switch ($v['currency']) {
                    case '€':
                    case '$':
                        $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                        break;

                    default:
                        if ($v['category'] == 0) {
                            $price = 'от ';
                        }
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                            1000000), 1) . '</span> млн руб';
                }
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $row['price_arenda'] = $v['price_arenda'];
            $row['sign'] = $this->getCategory($v['category']);
            $row['mortgage'] = $v['ipoteka'];
            $row['id'] = $v['id'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'main_interest', $data);

    }
    function showNewMain() {
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

        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);

        $off = $this->getRandom();

        $dataRow = $this->$model->getInterests($off);

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['metro'] = $parent_idmetro[$v['metro']];
            $row['rayon'] = $parent_idrayon[$v['rayon']] . ' район';
            $row['name'] = $v['name'];
            //if ($v['category'] == 0){
            //    $row['deadline'] = $this->dataKv($v['deadline']);
            //}
            $row['deadline'] = $v['line_middle'];
            $row['address'] = $v['address'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                        1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                switch ($v['currency']) {
                    case '€':
                    case '$':
                        $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                        break;

                    default:
                        if ($v['category'] == 0) {
                            $price = 'от ';
                        }
                        $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                                1000000), 1) . '</span> млн руб';
                }
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $row['price_arenda'] = $v['price_arenda'];
            $row['sign'] = $this->getCategory($v['category']);
            $row['mortgage'] = $v['ipoteka'];
            $row['id'] = $v['id'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/' . 'newmain_interest', $data);

    }

    function showObject($id = false) {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $dataRow = $this->$model->getInterestHide($id);

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['name'] = $v['name'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                    1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                    1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                if ((int)$v['category'] == 0) {
                    $price = 'от ';
                }
                if ((int)$v['category'] == 5)
                    $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                else
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб';
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/tmpl/' . 'specials_object',
            $data);
    }
	
	
	 function showObjectPg($id = false) {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $dataRow = $this->$model->getInterestHidePg($id);

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['name'] = $v['name'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                    1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                    1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                if ((int)$v['category'] == 0) {
                    $price = 'от ';
                }
                if ((int)$v['category'] == 5)
                    $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                else
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб';
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/tmpl/' . 'specials_object',
            $data);
    }

    function showObjectBottom() {
        $model = $this->table . '_model';
        $this->load->model($this->module . '/' . $model, $model);
        $dataRow = $this->$model->getInterestHide();

        $data['data'] = array();

        foreach ($dataRow as $k => $v) {
            if($v['link']=='123'){
                continue;
            }
            $row = array();
            $row['name'] = $v['name'];
            $price = '';
            if ($v['price'] > 0 && $v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                    1000000), 1) . '</span> млн руб / <span>' . $this->
                    number_format_drop_zero_decimals((($v['price_arenda']) / 1000), 1) .
                    '</span> тыс руб';
            } elseif ($v['price_arenda'] > 0) {
                $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price_arenda']) /
                    1000), 1) . '</span> тыс руб';
            } elseif (!empty($v['price']) && $v['price'] > 0) {
                if ((int)$v['category'] == 0) {
                    $price = 'от ';
                }
                if ((int)$v['category'] == 5)
                    $price .= '<span>' . $v['price'] . $v['currency'] . '</span>';
                else
                    $price .= '<span>' . $this->number_format_drop_zero_decimals((($v['price']) /
                        1000000), 1) . '</span> млн руб';
            } else {
                $price = 'по запросу';
            }
            $row['price'] = $price;
            $row['link'] = $this->getLink($v['category'], $v['link']);
            $row['foto'] = $v['foto'];
            $data['data'][] = $row;
        }

        $this->load->view('themes/' . $this->defaultTheme . '/tmpl/' .
            'specials_object_bottom', $data);
    }

    function getLink($category, $link) {
        $cats = array(
            '0' => 'buildings',
            '1' => 'resale',
            '2' => 'residential',
            '3' => 'elite',
            '4' => 'commercial',
            '5' => 'abroad',
            '6' => 'land',
            '8' => 'assignment'
            );
        $category = (int)$category;
        $link = BASEURL . '/' . $cats[$category] . '/' . $link;
        return $link;
    }

    function getCategory($category) {
        $cats = array(
            '0' => array('class' => 'buildings', 'name' => 'Новостройки'),
            '1' => array('class' => 'resale', 'name' => 'Вторичная'),
            '2' => array('class' => 'residential', 'name' => 'Загородная'),
            '3' => array('class' => 'elite', 'name' => 'Элитная'),
            '4' => array('class' => 'commercial', 'name' => 'Коммерческая'),
            '5' => array('class' => 'abroad', 'name' => 'Зарубежная'),
            '6' => array('class' => 'land', 'name' => 'Земельные массивы'),
            '8' => array('class' => 'assignment', 'name' => 'Переуступки'),
            );
        $category = (int)$category;
        $link = $cats[$category];
        return $link;
    }

    function number_format_drop_zero_decimals($n, $n_decimals) {
        //$n = number_format($n, $n_decimals, ',', ' ');
        $n = round($n, 1, PHP_ROUND_HALF_DOWN);
        $n = number_format($n, 1, ',', ' ');
        $ex = explode(',', $n);

        if (count($ex) > 1 && (int)$ex[1] == 0)
            $n = (int)$n;
        //return (($n == round($n, $n_decimals)) ? number_format($n, 0, ',', ' ') : number_format($n, $n_decimals, ',', ' '));
        return $n;
    }

    function dataKv($dt, $mini = true) {
        $newTime = strtotime(date('d.m.Y H:i:s'));
        if ($dt < $newTime) {
            $return = 'Сдан';
        } else {
            $ex = explode('.', date('d.m.Y', $dt));

            if ($ex[1] == '01') {
                $r = 'I';
            } elseif ($ex[1] == '02') {
                $r = 'I';
            } elseif ($ex[1] == '03') {
                $r = 'I';
            } elseif ($ex[1] == '04') {
                $r = 'II';
            } elseif ($ex[1] == '05') {
                $r = 'II';
            } elseif ($ex[1] == '06') {
                $r = 'II';
            } elseif ($ex[1] == '07') {
                $r = 'III';
            } elseif ($ex[1] == '08') {
                $r = 'III';
            } elseif ($ex[1] == '09') {
                $r = 'III';
            } elseif ($ex[1] == '10') {
                $r = 'IV';
            } elseif ($ex[1] == '11') {
                $r = 'IV';
            } elseif ($ex[1] == '12') {
                $r = 'IV';
            }

            if ($mini) {
                $return = $r . ' кв. ' . $ex[2];
            } else {
                $return = $r . ' квартал ' . $ex[2] . '';
            }
        }

        return $return;
    }

}
/* End of file */
