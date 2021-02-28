<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cron extends MY_Controller {

    private $buildingsAll = array();
    private $apartmentsAll = array();
    private $apartmentsVNK = array();
    private $listRooms;
    private $typeDoma;
    private $optPeriod;
    private $building;
    private $optMetro;
    private $builders;
    private $subways;
    private $regions;
    private $roomTypes;
    private $banks;
    private $mortgage;
    private $blocks;
    private $block_subways;
    private $apartments;
    private $metro_list = array();
    private $rayon_list = array();
    private $bank_list = array();

    private $_typeList = array();

    function __construct() {
        parent::__construct();

        $model = 'buildings_model';
        $this->load->model('buildings/' . $model, $model);
        $this->load->model('interest/admin_interest_model', 'mdInterest');
        $this->load->model('special/admin_special_model', 'mdSpecial');

        $this->load->model('room/admin_room_model', 'model_room');
        $this->listRooms = $this->model_room->parent_id();

        $query = $this->db->query("SELECT id, id_complex FROM `ci_buildings` WHERE id_complex<>0");
        $buildings_ = $query->result_array();

        $buildings = array();
        foreach ($buildings_ as $building) {
            $id = $building['id'];
            $cid = $building['id_complex'];
            if (!array_key_exists($cid, $this->buildingsAll)) {
                $this->buildingsAll[$cid] = $id;
            }
        }


        $query = $this->db->query("SELECT id, id_room, novostroy_id FROM `ci_apartments` WHERE novostroy_id IS NOT NULL");
        $apartments = $query->result_array();

        foreach ($apartments as $apartment) {
            $id = $apartment['id'];
            $rid = $apartment['id_room'];
            $nid = $apartment['novostroy_id'];
            if (!array_key_exists($rid, $this->apartmentsAll)) {
                $this->apartmentsAll[$rid] = $id;
            }
            $this->apartmentsVNK[$nid][] = $apartment;
        }

        $this->load->model('type/admin_type_model', 'model_type');
        $list_type = $this->model_type->parent_id();
        foreach ($list_type as $p) {
            $this->_typeList[$p['name']] = $p['id'];
        }
    }

    function getRayons() {
        $this->load->model('rayon/admin_rayon_model', 'model_rayon');
        $list_rayon = $this->model_rayon->parent_id();
        foreach ($list_rayon as $p) {
            if ($p['abc_id'] > 0) {
                $this->rayon_list[$p['abc_id']] = $p['id'];
            }
        }
    }

    function getMetro() {
        $this->load->model('metro/admin_metro_model', 'model_metro');
        $list_metro = $this->model_metro->parent_id();
        foreach ($list_metro as $p) {
            if ($p['abc_id'] > 0) {
                $this->metro_list[$p['abc_id']] = $p['id'];
            }
        }
    }

    function getBanks() {
        $this->load->model('banks/admin_banks_model', 'model_banks');
        $list_metro = $this->model_banks->parent_id();
        foreach ($list_metro as $p) {
            if ($p['abc_id'] > 0) {
                $this->bank_list[$p['abc_id']] = $p['id'];
            }
        }
    }

    function index()
    {
        $this->sendMessage('ryryryrtyrt');
        echo 1;
    }

    public function biuldings_update() {

        $this->sendMessage('start');

        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);

        $start = microtime(true);

        $link_xml = '/var/www/estate/data/www/m16-estate.ru/shared/asset/uploads/crm/SiteDataEstate.xml';


        $xml = simplexml_load_file($link_xml);

        $blocks = $_blocks = array();
        $apartments = array();
        $app = array();
        $delrooms = $listRoomAdd = $listRoomUpdate = 0;
        $blocks_reg = array();

        $counter = 0;

        foreach ($xml->Buildings->Building as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'blockid') {
                    $blId = (int)$a_val;
                }
                if ($a_key == 'buildingtype') {
                    $blTp = (string )$a_val;
                }
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                if ($a_key == 'endingperiod') {
                    $ep = (string )$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->typeDoma[$blId] = $blTp;
            $this->optPeriod[$blId] = $ep;
            $this->building[$id] = $atts;
        }

        foreach ($xml->Builders->Builder as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->builders[$id] = $atts;
        }

        foreach ($xml->Subways->Subway as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->subways[$id] = $atts;
        }

        foreach ($xml->Regions->Region as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->regions[$id] = $atts;
        }

        foreach ($xml->RoomTypes->RoomType as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                if ($a_key == 'name') {
                    $name = (string )$a_val;
                }
            }
            $this->roomTypes[$id] = $name;
        }

        foreach ($xml->Banks->Bank as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->banks[$id] = $atts;
        }

        foreach ($xml->Mortgages->Mortgage as $block) {
            $atts = array();
            foreach ($block->attributes() as $a_key => $a_val) {
                if ($a_key == 'id') {
                    $id = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }
            $this->mortgage[$id] = $atts;
        }

        foreach ($xml->Blocks->Block as $block) {

            //$blocks[] = $block['id'][0];
            $atts = array();
            $key = null;
            //dump($bl['address']);
            foreach ($block->attributes() as $a_key => $a_val) {

                if ($a_key == 'id') {
                    $key = (int)$a_val;
                }
                $atts[$a_key] = (string )$a_val;
            }

            $blocks[$key] = $atts;

        }

        $this->getRayons();
        $this->getMetro();
        $this->getBanks();

        foreach ($xml->Apartments->Apartment as $apart) {

            $apart_tmp = array();
            $atts = array();
            $key = null;
            //dump($bl['address']);
            foreach ($apart->attributes() as $a_key => $a_val) {

                if ($a_key == 'id') {
                    $key = (int)$a_val;
                }
                if ($a_key == 'blockid') {
                    $blockid = (int)$a_val;
                }

                $atts[$a_key] = (string )$a_val;
            }
            $app[] = $key;
            $room[] = $this->pRoomArray($atts);
            $apartments[$blockid][] = $atts;
            //array_push($apartments[$blockid], $atts);
        }

        unset($xml);

        //dump($apartments);

        //$complex = 2;
        $counter = 0;
        foreach ($blocks as $key => $complex) {
            if ($counter > 10) {
                continue;
            }

            $block = $complex;

            $res = $this->pComplexArray($block);
            $delrooms_ = $this->deleteApartments($res['id'], $app);
            $delrooms += $delrooms_;
            foreach ($room as $k => $c) {
                if ($c == 'insert') {
                    $listRoomAdd++;
                } elseif ($c == 'update') {
                    $listRoomUpdate++;
                } else {
                    $listRoomError++;
                    $idError[] = $k;
                }
            }
            $this->updatePrice($res['id']);
            $counter++;
        }
        //echo 123;

        $time = microtime(true) - $start;
        //printf('Скрипт выполнялся %.10F сек.', $time);
        //echo memory_get_usage() . "\n"; // 36744
        //exit;

        $message = '';
        $message .= 'Запуск парсера.';
        $message .= 'Удалено объектов: ' . $delrooms . '<br />';
        $message .= 'Залито в БД: ' . $listRoomAdd . '<br />';
        $message .= 'Обновлено: ' . $listRoomUpdate . '<br />';
        $message .= 'Скрипт выполнялся ' . $time . ' сек.<br />';
        $message .= 'Использовано памяти ' .  $this->memSize(memory_get_usage()) . '<br />';

        $this->sendMessage($message);

        $this->db->query("INSERT INTO `ci_cron`(`name`, `data`) VALUES ('buildings_update', '{$res}')");

        //$time = microtime(true) - $start;
        //printf('Скрипт выполнялся %.10F сек.', $time);
        //echo memory_get_usage() . "\n"; // 36744
        exit;
    }


    function memSize($size)
    {
        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .$filesizename[$i] : '0 Bytes';
    }

    // Проверяем есть ли ID уже в базе
    public function pIssetIDRoom($id) {
        /*$this->db->where('id_room', $id);
        $query = $this->db->get('apartments');
        $que = $query->row_array();

        if (isset($que['id']))
        return $que['id'];
        else
        return 0;*/
        /*--------------*/
        if (isset($this->apartmentsAll[$id])) {
            return $this->apartmentsAll[$id];
        } else {
            return 0;
        }
    }

    function getDecoration($decor) {
        $decor = mb_strtolower($decor);
        $decs = array(
            'без отделки' => 8,
            'чистовая' => 6,
            'подчистовая' => 5,
            'с мебелью' => 7);
        if (isset($decs[$decor]))
            return $decs[$decor];
        return 8;
    }
    // Вытаскиваем комнаты
    function pRoom($room) {
        $room = trim($room);
        $room = trim(str_replace(' ккв', '', $room));
        $room = trim(str_replace('ккв', '', $room));
        $room = trim(str_replace('(Евро)', '', $room));

        if ($room == 21) {
            $room = '4ккв (евро)';
        } elseif ($room == 22) {
            $room = '2ккв (евро)';
        } elseif ($room == 23) {
            $room = '3ккв (евро)';
        } elseif ($room == 25) {
            $room = 'К. пом';
        } elseif ($room == 30) {
            $room = 'Кладовка';
        } elseif ($room == 28) {
            $room = 'Таун Хаус';
        } elseif ($room == 29) {
            $room = 'Коттедж';
        }

        if (!empty($room)) {

            foreach ($this->listRooms as $p) {
                if ($p['name'] == $room) {
                    $id = $p['id'];
                }
            }

            if (empty($id)) // если нет совпадений по районам то добавляем его
            {
                dump($room);
            $this->db->insert('ci_room', array('name' => $room));
            $id = $this->db->insert_id();
            }

            return $id;
        } else {
            return 1;
        }
    }

    function pRoomArray($data) {

        $id = $data['id'];

        $img = $data['flatplan'];

        if (empty($img)) {
            $img = '/asset/images/logo-M16.png';
        } else {
            $img = '/asset/uploads/crm/' . $img;
        }

        $zapret = [30, 24];

        $mas = array(
            'banned' => '0',
            'id_room' => $id, // ID по которому будем находить квартиры
            'novostroy_id' => $this->complexID($data['blockid']), // ID комплекса
            'room_id' => $this->pRoom($data['rooms']), // К-во комнат
            'price' => $data['flatcostwithdiscounts'], // цена
            'price_for_meter' => (int)($data['flatcostwithdiscounts'] / (doubleval($data['stotal']))),
            'floor' => $data['flatfloor'], // Этаж
            'square_all' => $data['stotal'], // Общая площадь
            'otdelka_id' => $this->getDecoration($data['decoration']),
            'square_balcony' => $data['sbalcony'],
            'square_watercloset' => $data['swatercloset'],
            'square_corridor' => $data['scorridor'],
            'section' => $data['section'],
            'height' => $data['height'],
            'square_life' => $data['sroom'], // Жилая площадь
            'square_cook' => $data['skitchen'], // Площадь кухни
            'main_foto' => $img, // Изображение планировки
            'date_add' => strtotime($data['dateadded']), // Жилая площадь
            'date_edit' => strtotime($data['datemodified']), // Площадь кухни
            //'link' => toTranslitUrl($this->complexID($data['blockid'])).'-'.$this->pIssetIDRoom($data['id']) // ссылка
            );

        $action = "есть";

        $Idd_true = true;
        $Idd = $this->pIssetIDRoom($id);

        if (in_array($data['rooms'], $zapret)) {
            $Idd_true = false;
        }

        if ($Idd_true) {
            if ($Idd > 0) // если запись есть уже в базе
                {
                $this->db->where('id', $Idd);
                if ($this->db->update('apartments', $mas)) {
                    $action = 'update';
                } else {
                    $action = 'update-error';
                }
            } else // если записи нет в базе
            {

                if ($this->db->insert('apartments', $mas)) {
                    $action = 'insert';
                } else {
                    $action = 'insert-error';
                }
            }
        }
        return $action;
    }

    private function updatePrice($id) {
        $model = 'buildings_model';
        $prc = $this->buildings_model->complexMinMaxPrice($id);
        if ($prc['min'] > 0 || $prc['max'] > 0) {
            $prcfm = $this->$model->complexPriceForMeter($id);
            $this->db->update('ci_buildings', array(
                'virtual_price' => $prc['min'],
                'price' => $prc['min'],
                'virtual_price_max' => $prc['max'],
                'price_for_meter' => $prcfm), array('id' => $id));
            $this->mdInterest->synchronization($id, 'buildings', $prc['min']);
            $this->mdSpecial->synchronization($id, 'buildings', $prc['min']);
        }

    }

    // Формируем массив с данными для комплекса
    function pComplexArray($data) {
        $id = $data['id'];

        $img = $data['avatar'];
        if (empty($img)) {
            $img = '/asset/images/logo-M16.png';
        } else {
            $img = '/asset/uploads/crm/' . $img;
        }

        $serailize['foto'][0] = $img;
        $serailize['alt'][0] = '';

        $foto = serialize($serailize);
        $dop_metro = array();
        $banks = array();

        foreach ($this->block_subways[$id] as $k => $value) {
            if ($k == 0) {
                $metro_id = $this->metro_list[$value['subwayid']];
                $otd = explode(' Минут ', $value['distance']);
                $otd_metr = (int)$otd;
                if (count($otd) > 1)
                    $otd_type = ($otd[1] == 'пешком') ? 0 : 1;
                else {
                    $otd_type = 0;
                }
            } else {
                $dop_metro[] = $this->metro_list[$value['subwayid']];
            }
        }

        foreach ($this->mortgage[$id] as $k => $value) {

            $banks[] = $this->bank_list[$value['bankid']];

        }

        $ipoteka = 0;

        if (count($banks) > 0) {
            $ipoteka = 1;
        }

        $mas = array(
            'id_complex' => $id, // ID по которому будем находить комплексы
            'metro_id' => $metro_id,
            'type_id' => $this->fType($this->typeDoma[$id]),
            'otd_metro_value' => $otd_metr,
            'otd_metro' => $otd_type,
            'dop_metro' => implode(',', $dop_metro),
            'banks' => implode(',', $banks),
            'banned' => '0',
            'ipoteka' => $ipoteka,
            'adress' => $data['address'], // адресс
            'name' => $data['title'], // название
            'title' => $data['title'], // title
            'text' => $data['note'], // описание
            'map' => $data['latitude'] . ' | ' . $data['longitude'], // координаты
            'mainfoto' => $img, // главное изображение
            'link' => toTranslitUrl($data['title']) . '_' . $data['id'], // ссылка
            'rayon_id' => $this->rayon_list[$data['region']],
            'foto' => $foto,
            'razdelu' => 0,
            'korpus' => 3,
            'korpus_value' => strtotime($this->optPeriod[$id]));

        $result = array();

        if ($idl = $this->pIssetIDComplex($id)) // если запись есть уже в базе
            {
            $this->db->where('id_complex', $id);
            unset($mas['link']);
            unset($mas['foto']);
            unset($mas['razdelu']);
            unset($mas['title']);
            unset($mas['name']);
            unset($mas['text']);
            $mas['date_edit'] = time();
            if ($this->db->update('buildings', $mas)) {
                $action = 'update';
            } else {
                $action = 'update-error';
            }
            $result['action'] = 'обновлен';
        } else // если записи нет в базе
        {
            $mas['date_add'] = time();
            $mas['date_edit'] = time();
            if ($this->db->insert('buildings', $mas)) {
                $result['action'] = 'добавлен';
            } else {
                $action = 'ошибка добавления';
            }
            $idl = $this->db->insert_id();
        }
        $result['id'] = $idl;
        $this->dop_metro_add_parser($idl, $this->block_subways[$id]);
        $this->dop_rayon_add_parser($idl, array($data['region']));
        $this->mortgage_add_parser($idl, $this->mortgage[$id]);

        return $result;
    }

    // Узнаем тип дома
    function fType($id) {
        $value = trim($this->typeDoma[$id]);
        if ($value == 'К/М') {
            $value = 'Кирпич-монолит';
        }

        if (!empty($value)) {
            if (isset($this->_typeList[$value])) {
                $type = $this->_typeList[$value];
            }

            if (empty($type)) // если нет совпадений по районам то добавляем его
                {
                $this->db->insert('ci_type', array('name' => $value));
                $type = $this->db->insert_id();
            }
        } else {
            $type = 6;
        }

        return $type;
    }

    // Проверяем есть ли ID уже в базе
    public function pIssetIDComplex($id) {
        if (isset($this->buildingsAll[$id])) {
            return $this->buildingsAll[$id];
        }
        return false;
    }

    function complexID($id) {
        return $this->buildingsAll[$id];
    }

    public function dop_metro_add_parser($id, $mas) {
        $where = array('building_id' => $id);
        $this->db->delete('ci_metro_buildings', $where);
        if (!empty($mas) && count($mas) > 0) {
            foreach ($mas as $v) {
                $mas = array(
                    'building_id' => $id,
                    'metro_id' => $this->metro_list[$v['subwayid']],
                    'distance' => $v['distance']);
                $this->db->insert('ci_metro_buildings', $mas);
            }
        }
    }

    public function dop_rayon_add_parser($id, $mas) {
        $where = array('building_id' => $id);
        $this->db->delete('ci_rayon_buildings', $where);
        if (!empty($mas) && count($mas) > 0) {
            foreach ($mas as $v) {
                $mas = array(
                    'building_id' => $id,
                    'rayon_id' => $this->rayon_list[$v],
                    );
                $this->db->insert('ci_rayon_buildings', $mas);
            }
        }
    }

    public function mortgage_add_parser($id, $mas) {
        $where = array('building_id' => $id);
        $this->db->delete('ci_banks_buildings', $where);
        if (!empty($mas) && count($mas) > 0) {
            foreach ($mas as $v) {
                $mas = array(
                    'building_id' => $id,
                    'bank_id' => $this->bank_list[$v['bankid']],
                    );
                $this->db->insert('ci_banks_buildings', $mas);
            }
        }
    }

    function deleteApartments($complex, $compl) {
        $count = 0;
        if (isset($this->apartmentsVNK[$complex])) {
            foreach ($this->apartmentsVNK[$complex] as $v) {
                if (!in_array($v['id_room'], $compl)) {
                    $this->db->delete('apartments', array('id' => $v['id']));
                    $count++;
                }
            }
        }
        return $count;
        /*
        $this->db->where('id_room >', 0);
        $this->db->where('novostroy_id', $complex);
        $query = $this->db->get('apartments');
        $que = $rows = $query->result_array();
        $str = '';
        $count = 0;
        foreach ($que as $v) {
            if (!in_array($v['id_room'], $compl)) {
                $this->db->delete('apartments', array('id' => $v['id']));
                $count++;
            }
        }
        return $count;*/
    }

    function sendMessage ($message) {

        $message .= "<br><br>Время отправки: " . date('H:i:s d.m.Y') . "";

        try {
            $this->load->library('phpmailer'); //Класс phpmailer
            $this->phpmailer->ClearAllRecipients();
            // Кодировка
            $this->phpmailer->SMTPDebug = 0;
            $this->phpmailer->CharSet = "utf-8";
            //$this->phpmailer->SMTPDebug = 1;
            $this->phpmailer->CharSet = 'UTF-8';
            $this->phpmailer->IsSMTP();
            $this->phpmailer->Host = 'smtp.yandex.ru';
            $this->phpmailer->Port = 25;
            $this->phpmailer->SMTPSecure = 'tls';
            $this->phpmailer->SMTPAuth = true;
            $this->phpmailer->isHtml(true);
            $this->phpmailer->Username = 'm16.noreplay@yandex.ru';
            $this->phpmailer->Password = 'Vfkfattd016';
            //Емайл получателя

            $this->phpmailer->AddAddress('pahuss@mail.ru' );

            $this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');

            $this->phpmailer->Subject = 'CRON M16-ESTATE.RU | PARSER';

            $this->phpmailer->ContentType = 'text/html';

            $this->phpmailer->Body = $message;
            $this->phpmailer->MsgHTML = $message;

            $r = $this->phpmailer->send();

        } catch (phpmailerException $e) {

          echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {

          echo $e->getMessage(); //Boring error messages from anything else!
        }
        //$this->phpmailer->ErrorInfo;
        return $r;
    }

}
