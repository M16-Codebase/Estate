<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Xml_load_yandex_model extends Xml_load_model {

    
    private $typesdelka;
    
    private $notUploaded = [];
    private $uploaded = 0;
    private $udated = 0;
    
    private $workedImg = 0;
    private $uploadImg = 0;
    
    const UPLOAD_URI = 'http://realtyposter.ru/data/export/yandex/5710c25e48fa58bd2c8b4567.xml';
    //const UPLOAD_URI = 'http://dev.m16-elite.ru/YandexXmlFeed.xml';
    //const UPLOAD_URI = 'http://dev.m16-elite.ru/m16.xml';

    public function __construct() {
        $this->load->model('arenda/admin_arenda_model', 'arenda');
        $this->load->model('buildings/admin_buildings_model', 'buildings');
        $this->load->model('rayon/rayon_model', 'districts');
        $this->load->model('room/admin_room_model', 'rooms');
        $this->load->model('type/admin_type_model', 'admin_type_model');
        $this->load->model('otdelka/admin_otdelka_model', 'admin_otdelka_model');
        $this->load->model('rayon/admin_rayon_model', 'admin_rayon_model');
        $this->load->model('metro/admin_metro_model', 'admin_metro_model');
        $this->load->model('consultants/admin_consultants_model',
            'admin_consultants_model');
        $this->load->model('room/admin_room_model', 'admin_room_model');

        $this->load->model('typesdelka/admin_typesdelka_model', 'admin_typesdelka_model');
        $this->typesdelka = $this->admin_typesdelka_model->parent_id();

        //dump($this->typesdelka);exit;

        $this->load->library('RusToEn', array(), 'translit');
        //dump($this->translit);
        $now = new DateTime();
        $this->timestamp = $now->getTimestamp();
    }


    private $counter;
    /**
     *
     */
    public function loadData() {
        
        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);
        
        //$this->conf = $this->config();
		$this->counter = 0;
		$start = microtime(true);
        $now = new DateTime();
        $t = $now->format('H:i:s');
        $message = "Xml load start : time - {$t}";
        //$this->sendReport($message);
        
        require_once 'LoadXMLParser.php';
		
        $reader = new LoadXMLParser;

        $reader->open(self::UPLOAD_URI);
        
        $reader->parse($this);
        $reader->close();
		
		$time = microtime(true) - $start;

        $message = "<b>Совершена выгрузка объектов на сайте m16-estate.ru</b>";
        $message .= "<br><br>Время отправки: " . date('H:i:s d.m.Y') . "";
		
		$message .= 'Скрипт выполнялся ' . $time . ' сек.<br />';
        $message .= 'Использовано памяти ' .  memSize(memory_get_usage()) . '<br />';
        $message .= 'Загружено новых объектов: ' . $this->uploaded . '<br />';
        $message .= 'Обновлено объектов: ' . $this->udated . '<br />';
        $message .= 'Ошибки при загрузке: ' . implode(', ', $this->notUploaded) . '<br />';
        $message .= 'Загружено новых изображений: ' . $this->uploadImg . '<br />';

        //$this->sendReport($message);
        
        /*
        dump($this->notUploaded);
        dump($this->udated);
        dump($this->uploaded);
        */

        //$this->log($this->errors);
        return true;

    }
    
    
    public function loadItem($objectId, $offer) {
        return;
        //global $h;
        
        $offer = $this->xml2array($offer);
        
        if (isset($offer['is-elite']) && $offer['is-elite'] == 1) {
            return false;
        }
        
        //if ($objectId==937502) {
         //   $h->debug($offer['is-elite']);
        //}

        // если новострой, то пропускаем
        $new_flat = 0;
        
        if (isset($offer['new-flat'])) {
            if ($offer['new-flat'] == 1) {
                return false;
            }
            $new_flat = $offer['new-flat'];
        }

        if (empty($offer['category'])) {
            return false;
        }
        
        
        /********************************/
        
        /*
        $x = 260;
        $y = $x+30;
        $this->counter ++;
        
        
        
        if (!($this->counter > $x && $this->counter <= $y)) {
            
            return;
        }
        */
        
        /*********************************/
        
        $object_isset = true;
        $is_arenda = false;
        
        
		
		// если адрес пуст то пропускаем
        if (empty($offer['location'])) {
            return false;
        }
        
        if ($offer['type'] == self::TYPE_RENT) {
            $is_arenda = true;
        }
        $module = 'buildings';
        if ($is_arenda) $module = 'arenda';
        
        $category = mb_strtolower($offer['category']);

        if ($category == self::CAT_FLAT) {
            $type = self::CAT_FLAT_TYPE;
        } elseif ($category == self::CAT_HOUSE) {
            $type = self::CAT_HOUSE_TYPE;
        } elseif ($category == self::CAT_COMMERCE) {
            $type = self::CAT_COMMERCE_TYPE;
        }
        
        if (is_null($type)) {
            return false;
        }
        
        $data = array();
        
        $object = $this->$module->getById($objectId);
        
        if (empty($object)) {
            $object_isset = false;
            $data = array(
                'id' => $objectId,
                'date_add' => $this->timestamp);

            $res = $this->$module->module_add($data, $this->$module->table);

            if (!$res) {
                return;
            }

            $object = $this->$module->getById($objectId);
        }

        $object = $object[0];
        
        if (!$is_arenda) {
            $data['razdelu'] = $type;
        }

        $vals = $this->convertXmlObjectToArray($offer);
        
        
        
        $data['date_edit'] = $this->timestamp;

        foreach ($vals as $key => $value) {

            if (array_key_exists($key, $object)) {

                if ($object[$key] != $value) {
                    $data[$key] = $value;
                }

            }

            if ($key == 'adress' && $type != 2) {
                if ($is_arenda) {
                    //для аренды
                    $data['address'] = $value;
                }
                $data['link'] = $this->translit->translit($value) . '_' . $objectId;
            }
            
            // для вторички
            if ($type == 2 && $key == 'locality_name') {
                $data['link'] = $this->translit->translit($value) . '_' . $objectId;
            }
            
            
            
            
            if ($key == 'consultant' && !$is_arenda) {
                $consultants = $this->admin_consultants_model->parent_id();
                
                $name_atts = explode(' ', $value);
                $maybe_cons = array();
                $cons = null;
                foreach ($consultants as $entity) {
                    $search_success = false;

                    if (strpos($entity['name'], $name_atts[0]) !== false || strpos($entity['name'],
                        $name_atts[1]) !== false || strpos($entity['name'], $name_atts[2]) !== false) {
                        $maybe_cons[] = $entity['id'];
                    }
                }
                
                if (!empty($maybe_cons)) {
                    $data['consultant_id'] = (int)$maybe_cons[0];
                }
            }

            if ($key == 'description') {
                $data['title'] = $value['title'];
                $data['description'] = trim($value['text']);
                $data['text'] = trim($value['text']);
            }
            
            if ($key == 'rooms') {
                if ($is_arenda) {
                    $data['rooms'] = (int)$value;
                } else {
                    $parent_room = $this->admin_room_model->parent_id();
                    foreach ($parent_room as $rooms_count) {
    
                        if ((int)$rooms_count['num_rooms'] == (int)$value) {
    
                            $data['room_id'] = $rooms_count['id'];
                        }
                    }
                }
                
            }

            if ($key == 'rooms_number') {
                if (!empty($value)) {
                    $rooms = $this->rooms->module_get($object['room_id']);
                        if (!empty($rooms)) {
                            if ($rooms['num_rooms'] != $value) {
                                $r_id = $this->rooms->getByRoomsNum($value);
                                if (!empty($r_id) && is_array($r_id)) {
                                    $data['room_id'] = $r_id['id'];
                                }
                            }
                        }
                }
            }

            // building_type
            // тут надо привести в точное соответствие значения полей
            // потому что если значение объекта не равно этому же значению
            // из файла, то по строкам очень неудобно искать
            // Кирпич-монолит и Кирпично-монолитный = разные строки
            // и заморачиваться на обработку не сильно хочется
            if ($key == 'building_type') {
                if (!empty($value)) {
                    $types = $this->admin_type_model->parent_id();
                    foreach ($types as $type) {
                        if (mb_strtolower($type['name']) == mb_strtolower($value)) {
                            $data['type_id'] = $type['id'];
                        }
                    }
                }
            }

            if ($key == 'otdelka') {
                if (!empty($value)) {

                    $parent_otdelka = $this->admin_otdelka_model->parent_id();
                    foreach ($parent_otdelka as $p) {
                        if (mb_strtolower($p['name']) == mb_strtolower($value)) {
                            $data['otdelka_id'] = $p['id'];
                        }
                    }
                }
            }

            //rayon
            if ($key == 'rayon') {
                if (!empty($value)) {

                    $parentrayon = $this->admin_rayon_model->parent_id(null, 'name', 'asc');
                    foreach ($parentrayon as $p) {
                        if (mb_strtolower($p['name']) == mb_strtolower($value)) {

                            $data['rayon_id'] = (int)$p['id'];
                        }
                    }
                }
            }

            //metro
            if ($key == 'metro') {
                if (!empty($value)) {

                    $metro = $this->admin_metro_model->parent_id(null, 'name', 'asc');

                    foreach ($metro as $p) {
                        
                        if (mb_strtolower($p['name']) == mb_strtolower($value)) {
                            
                            $data['metro_id'] = (int)$p['id'];
                        }
                    }
                }
            }

            //deal_type
            if ($key == 'deal_type') {
                foreach ($this->typesdelka as $tsd) {
                    if (strtolower($tsd['name']) == strtolower($value)) {
                        $data['typesdelka_id'] = (int)$tsd['id'];
                        break;
                    }
                }
            }

            if ($key == 'address_coords') {
                $data['map'] = $value;
            }
        }
        
        //exit;

        $photo = [];
        if (isset($offer['image'])) {
            $this->upload_images($offer['image'], $photo);
        }
        if ($is_arenda) {
            $data['main_foto'] = $photo['mainfoto'];
        } else {
            $data['mainfoto'] = $photo['mainfoto'];
        }
        
        
        unset($photo['mainfoto']);

        $alt = [];
        foreach ($photo as $ph) {
            if ($is_arenda) {
                $alt[] = $data['address'];
            } else {
                $alt[] = $data['adress'];
            }
        }

        $data['foto'] = serialize(array('foto' => $photo['foto'], 'alt' => $alt));
        
        $res = $this->$module->module_edit($objectId, $data);
        
        if (!$res) {
            $this->notUploaded[] = $objectId;
        } else {
            if ($object_isset) {
                $this->udated ++;
            } else {
                $this->uploaded ++;
            }
            
        }
        
        return true;
    }

    /**
     * @param array|string images
     */
    private function upload_images($images, &$photo) {

        if (empty($images)) {
            return false;
        }

        if (is_array($images)) {
            $count = 0;
            foreach ($images as $image) {
                $image = trim($image);
                $rup = $this->upload_image((string )$image, $photo, $count);
                
                $count++;
            }
        } elseif (is_string($images)) {
            $images = trim($images);
            $this->upload_image($images, $photo);
            $photo['mainfoto'] = $images;
        } else {
            return false;
        }
        return true;
    }

    /**
     * @param string image
     */
    private function upload_image($image, &$photo, $i = null) {

        if (empty($image)) {
            return false;
        }

        $url = trim($image);

        $tmp_path = FCPATH . 'asset/uploads/images/buildings/';
        $relative_path = '/asset/uploads/images/buildings/';
        $tmp_name = basename($url);

        $opts = array('path' => $tmp_path . $tmp_name, 'originalName' => $tmp_name);

        if (!file_exists($tmp_path)) {
            mkdir($tmp_path);
        }

        try {
            if (!file_exists($tmp_path . $tmp_name)) {
                $Headers = @get_headers($url);
                if (strpos($Headers[0], '200')) {
                    copy($url, $tmp_path . $tmp_name);
                    $this->uploadImg ++;
                }
            }

        }
        catch (\Exception $e) {
            return false;
        }

        if ($i == null || $i == 0) {
            $photo['mainfoto'] = $relative_path . $tmp_name;
        } else {
            $photo['foto'][] = $relative_path . $tmp_name;
        }

        return true;

    }

    private function getXml() {
        return simplexml_load_file(self::UPLOAD_URI);
    }

    protected function convertXmlObjectToArray($offer) {
//rooms_number
        return ['deal_type' => isset($offer['type']) ? $offer['type'] : null, 
        
        'price' =>
            isset($offer['price']['value']) ? $offer['price']['value'] : null,
            /*'description' => isset($offer['description']) ? $offer['description'] : null,*/
        'description' => array(
            'title' => isset($offer['description_title']) ? $offer['description_title'] : null,
            'text' => isset($offer['description']) ? $offer['description'] : null,
            ), 
            
        'images' => isset($offer['image']) ? $offer['image'] : null, 
        'square_all' =>
            isset($offer['area']['value']) ? $offer['area']['value'] : null,
        'square_uchastok' => isset($offer['lot-area']['value']) ? $offer['lot-area']['value'] : null,
            // пл участка
        'ceiling_height' => isset($offer['ceiling-height']) ? $offer['ceiling-height'] : null,
        'square_life' => isset($offer['living-space']['value']) ? $offer['living-space']['value'] : null,
        'square_cook' => isset($offer['kitchen-space']['value']) ? $offer['kitchen-space']['value'] : null,
        'rooms' => isset($offer['rooms']) ? intval($offer['rooms']) : null, 
        'floor' =>
            isset($offer['floor']) ? intval($offer['floor']) : null, 
        'floors' => isset($offer['floors-total']) ?
            intval($offer['floors-total']) : null, 
        'building_type' => isset($offer['building-type']) ?
            $offer['building-type'] : null, 
        'otdelka' => isset($offer['renovation']) ? $offer['renovation'] : null,
            'wc_number' => isset($offer['bathroom-unit']) ? $offer['bathroom-unit'] : null,
            'country' => isset($offer['location']['country']) ? $offer['location']['country'] : null,
        'area' => isset($offer['location']['region']) ? $offer['location']['region'] : (isset
            ($offer['location']['locality-name']) ? $offer['location']['locality-name'] : null),
            //'district' => isset($offer['location']['district']) ? $offer['location']['district'] : null,
        'locality_name' => isset($offer['location']['region']) ? $offer['location']['locality-name'] : (isset
            ($offer['location']['sub-locality-name']) ? $offer['location']['sub-locality-name'] : null),
        'rayon' => isset($offer['location']['sub-locality-name']) ? $offer['location']['sub-locality-name'] : null,
        'adress' => isset($offer['location']['address']) ? $offer['location']['address'] : null,
        'name' => isset($offer['location']['address']) ? $offer['location']['address'] : null,
            'link' => $this->translit->translit($offer['location']['address']),
            'center_distance' => isset($offer['location']['distance']) ? $offer['location']['distance'] : null,
            'metro' => isset($offer['location']['metro']['name']) ? $offer['location']['metro']['name'] : null,
            'metro_drive_time' => isset($offer['location']['metro']['time-on-transport']) ?
            $offer['location']['metro']['time-on-transport'] : null,
            //до метро на транспорте
            'metro_walk_time' => isset($offer['location']['metro']['time-on-foot']) ? $offer['location']['metro']['time-on-foot'] : null,
            //до метро на пешком
            'address_coords' => (isset($offer['location']['latitude']) && isset($offer['location']['longitude'])) ? ($offer['location']['latitude'] .
            '|' . $offer['location']['longitude']) : null, 'consultant' => isset($offer['sales-agent']['name']) ?
            $offer['sales-agent']['name'] : null, 'object_title' => isset($offer['building-name']) ?
            $offer['building-name'] : null,
            //'icon' => isset($offer['new-flat']) ? $offer['new-flat'] : null,
            ];
    }

    private function getBuildingsRazdelIdByKey($key) {
        $razds = array(
            'buildings' => 0, //новостройки
            'resale' => 1, //вторичка
            'assignment' => 8, //переуступки
            'residential' => 2, //загородная
            'elite' => 3, //элитная
            'exclusive' => 9, //эксклюзивная
            'commercial' => 4, //коммерческая
            'land' => 6,
            'world' => 5);
        return isset($razds[$key]) ? $razds[$key] : false;
    }

    public function syncData() {

        $districts = $this->admin_rayon_model->parent_id(null, 'name', 'asc');

        $uri = 'http://realtyposter.ru/data/export/yandex/54c63c9848fa589959570c63.xml';

        $xml = simplexml_load_file($uri);

        $sale = 'продажа';
        $rent = 'аренда';

        $resale_key = 1;
        $residential_key = 2;
        $commerce_key = 4;

        $commerce = 'коммерческая';
        $flat = 'квартира';
        $house = 'дом';

        $table = 'buildings';

        $db = $this->db;

        $fd = fopen("sync_result.html", 'w') or die("не удалось создать файл");

        $stl = '<style> 
            table td {
                padding: 10px;
                font-family: monospace;
                text-align: center;
            }
            </style>';
        $fh = "<!DOCTYPE html><html><head><title>Elite Sync Data</title><meta charset='utf-8'>$stl<head>" .
            PHP_EOL;
        $fh .= "<body><table>" . PHP_EOL;
        $fh .= "<tr><th>ID в яндексе</th>";
        $fh .= "<th>ID на сайте</th>";
        $fh .= "<th>Адрес</th>";
        $fh .= "<th>Тип</th>";
        $fh .= "<th>Сделка</th>";
        $fh .= "<th>Площадь</th>";
        $fh .= "<th>Цена</th>";
        $fh .= "<th>Этаж</th>" . PHP_EOL;
        fwrite($fd, $fh);

        foreach ($xml->offer as $offer) {

            $offer = $this->xml2array($offer);
            $internal_id = $offer['@attributes']["internal-id"];

            $type = mb_strtolower($offer['type']);
            $category = mb_strtolower($offer['category']);
            $price = intval($offer['price']['value']);
            $district = mb_strtolower($offer['location']['sub-locality-name']);
            $district_id = false;

            $key = false;

            foreach ($districts as $p) {
                if (mb_strtolower($p['name']) == $district) {
                    $district_id = $p['id'];
                }
            }
            if (empty($district_id))
                continue;
            $address = '-';
            $area = floatval($offer['area']['value']);

            if (isset($offer['location']['address'])) {
                $address = $offer['location']['address'];
            }

            if ($category == $commerce) {
                $key = $commerce_key;
                $key = $resale_key;

                $where = "`rayon_id` = $district_id AND (price = $price OR price_arenda = $price)";

                $query = $this->db->select('id, adress, razdelu')->where($where)->get($table);
                if (!$query) {
                    dump($this->db->last_query());
                }

                //if (!$query)  continue;
                $result = $query->result();

                if (count($result) == 0)
                    continue;

                $result = $result[0];

                if (is_null($result))
                    continue;
                $rzd = intval($result->razdelu);

                if ($rzd != 4) {
                    continue;
                }

                $id = $result->id;
                $addr = $result->adress;

                $line = "<tr><td>$internal_id</td>";
                $line .= "<td>$id</td>";
                $line .= "<td>$address | $addr</td>";
                $line .= "<td>$category</td>"; //$type
                $line .= "<td>$type</td>";
                $line .= "<td>$area</td>";
                $line .= "<td>$price</td>";
                $line .= "<td>-</td>" . PHP_EOL;

            } elseif ($category == $flat) {
                $key = $resale_key;

                $query = $this->db->select('id, adress, razdelu')->where(array(
                    'rayon_id' => $district_id,
                    'price' => $price,
                    'square_all' => $area))->get($table);

                $result = $query->result();
                $result = $result[0];

                $rzd = intval($result->razdelu);

                if ($rzd == 0 || $rzd == 4 || $rzd == 5 || $rzd == 6) {
                    continue;
                }

                $id = $result->id;
                $addr = $result->adress;

                $line = "<tr><td>$internal_id</td>";
                $line .= "<td>$id</td>";
                $line .= "<td>$address | $addr</td>";
                $line .= "<td>$category</td>"; //$type
                $line .= "<td>$type</td>";
                $line .= "<td>$area</td>";
                $line .= "<td>$price</td>";
                $line .= "<td>-</td>" . PHP_EOL;

            } elseif ($category == $house) {
                $key = $residential_key;
                $lot = $offer['lot-area']['value'];
                $where = "`rayon_id` = $district_id AND price = $price AND (square_all = $area OR square_uchastok = $lot)";

                $query = $this->db->select('id, adress, razdelu')->where($where)->get($table);

                $result = $query->result();
                $result = $result[0];

                $rzd = intval($result->razdelu);

                if ($rzd == 0 || $rzd == 4 || $rzd == 5 || $rzd == 6) {
                    continue;
                }

                $id = $result->id;
                $addr = $result->adress;

                $line = "<tr><td>$internal_id</td>";
                $line .= "<td>$id</td>";
                $line .= "<td>$address | $addr</td>";
                $line .= "<td>$category</td>"; //$type
                $line .= "<td>$type</td>";
                $line .= "<td>$area</td>";
                $line .= "<td>$price</td>";
                $line .= "<td>-</td>" . PHP_EOL;
            }
            fwrite($fd, $line);

        }
        $line = '</table></html>';
        fwrite($fd, $line);
        fclose($fd);

        include 'sync_result.html';
    }
    
    public function sendReport($message) {
        $this->load->library('phpmailer'); //Класс phpmailer
        $this->phpmailer->ClearAllRecipients();
        // Кодировка
        $this->phpmailer->CharSet = "utf-8";
        $this->phpmailer->SMTPDebug = 1;
        $this->phpmailer->CharSet = 'UTF-8';
        $this->phpmailer->IsSMTP();
        $this->phpmailer->Host = 'smtp.yandex.ru';
        $this->phpmailer->Port = 25;
        $this->phpmailer->SMTPSecure = 'tls';
        $this->phpmailer->SMTPAuth = true;
        $this->phpmailer->Username = 'm16.noreplay@yandex.ru';
        $this->phpmailer->Password = 'Vfkfattd016';
        
        $this->phpmailer->AddAddress('pahuss@mail.ru');
        $this->phpmailer->SetFrom('m16.noreplay@yandex.ru', 'M-16');
        
        $this->phpmailer->Subject = 'Выгрузка объектов на M16-ESTATE.RU | ' . $theme;
        
        $this->phpmailer->ContentType = 'text/html';
        
        $this->phpmailer->Body = $message;
        $this->phpmailer->MsgHTML = $message;
        return $this->phpmailer->send();
    }

}


function dump($var, $info = false) {
        $bt = debug_backtrace();

        echo '<br />';
        echo "========= file : {$bt[0]['file']}, line: {$bt[0]['line']} ==========";
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        if ($info) {
            foreach ($bt as $b) {
                echo '<small>file : ' .$b['file'].', line: '. $b['line'].'</small>';
                echo '<br />';
            }
        } else
            echo 'file : ' .$bt[1]['file'].', line: '. $bt[1]['line'];
        echo '<br />'; echo '==================='; echo '<br />';
    }
