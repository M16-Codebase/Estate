<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('memory_limit', '2048M');
class Parser extends MY_Admin {

    function __construct()
    {
        // конструктор
        parent::__construct();

        $this->typeDoma = '';
        $this->optMetro = '';
        $this->optPeriod = '';
        $this->builders = '';
        $this->subways = '';
        $this->regions = '';
        $this->roomTypes = '';
        $this->banks = '';
        $this->mortgage = '';
        $this->building = '';
        $this->blocks = '';
        $this->block_subways = '';
        $this->apartments = '';
        $this->metro_list = array();
        $this->rayon_list = array();
        $this->bank_list = array();
    }

    function nm()
    {
        $a = [10,9,21,22,23,24,25,17,18,20,19,15,14,13,12];

        sort($a);

        foreach ($a as $item) {
            $array[$item] = $item;
        }

        $min =  min($a);
        $max = max($a);

        $group = [];
        $group[$min] = $min;

        for($i = $min; $i<=$max; $i++) {
            if(isset($array[$i])) {
                if(isset($array[$i+1])) {
                    if($group[$i] != $min) {
                        $group[] = '-';
                    }
                } else {
                    $group[$i] = $i;
                }
            } else {
                if(isset($array[($i+1)])) {
                    $group[$i+1] = $i+1;
                }
            }
        }

        $end = '';
        $next = true;
        foreach($group as $k => $g) {
            if($g != '-') {
                $end .= $g;
                if($g != $max) {
                    if($group[$k+1] != '-') {
                        $end .= ',';
                    }
                }
                $next = true;
            } else {
                if($next) {
                    $end .= $g;

                }

                $next = false;
            }
        }

        echo '<style>html{overflow-y: scroll !important;}</style>';
        echo "<div class='with-padding'><pre class='prettyprint'>\n";
        print_r($group);
        echo '</pre></div>';
        echo $end;
    }

    function _del_ap()
    {
        $return = $this->db
            ->where('room_id', 30)
            ->get('apartments')
            ->result_array();

        foreach ($return as $item) {
            $this->db->limit(1);
            $this->db->delete('apartments', ['id' => $item['id']]);
        }

        $return = $this->db
            ->where('room_id', 30)
            ->get('apartments')
            ->result_array();
    }

    function index()
    {
        $this->data['data'] = $this->load->view('parser', $dani, true);
        $this->admin_display($this->data);
    }

    function getDecoration($decor){
        $decor = mb_strtolower($decor);
        $decs = array(
            'без отделки' => 8,
            'чистовая'  => 6,
            'подчистовая'   => 5,
            'с мебелью'   => 7
        );
        if (isset($decs[$decor]))
            return $decs[$decor];
        return 8;
    }

    function ajax_complex()
    {
        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);
        $files = $this->input->post('files');
        $xmlFile = $this->readDatabase($files);
		//print_r($xmlFile);
        foreach($xmlFile as $k=>$d)
        {
          if($d['tag'] == 'Building')
          {
            $this->typeDoma[$d['attributes']['blockid']] = $d['attributes']['buildingtype'];
            $this->optPeriod[$d['attributes']['blockid']] = $d['attributes']['endingperiod'];
            $this->building[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Builder')
          {
              $this->builders[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Subway')
          {
              $this->subways[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Region')
          {
              $this->regions[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'RoomType')
          {
              $this->roomTypes[$d['attributes']['id']] = $d['attributes']['name'];
          }
          elseif($d['tag'] == 'Bank')
          {
              $this->banks[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Mortgage')
          {
              $this->mortgage[$d['attributes']['blockid']][] = $d['attributes'];
          }
          elseif($d['tag'] == 'Apartment')
          {
              $this->apartments[$d['attributes']['blockid']][] = $d['attributes'];
              $roomers[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Block')
          {
              $this->blocks[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'BlockSubway')
          {
              $this->block_subways[$d['attributes']['blockid']][] = $d['attributes'];
          }
        }

        foreach ($this->subways as $k=>$v){
            $f = $this->setMetroList($v);
        }
        foreach ($this->regions as $k=>$v){
            $f = $this->setRayonList($v);
        }
        foreach ($this->builders as $k=>$v){
            $f = $this->setBuildersList($v);
        }
        foreach ($this->banks as $k=>$v){
            $f = $this->setBanksList($v);
        }
        $this->getRayons();
        $this->getMetro();
        $this->getBanks();

        $compl = array();

        $listComplex = '';

        $cRoom = count($roomers);

        $cComplex = count($this->blocks);


        foreach ($this->blocks as $k => $v){
            //$complex[$k] = $this->pComplexArray($v);
            $compl[] = $k;
            //print_r($this->pComplexArray($v));
            $listComplex .= '<label for="building-'.$k.'" id="build-'.$k.'"><input type="checkbox" class="checksb" checked="checked" id="building-'.$k.'" value="'.$k.'">'.$v['title'].' (квартир - '.count($this->apartments[$k]).')</label><br>';
        }
        $this->deleteComplex($compl);

        //$cComplex = count($complex);

        //$listComplex = '';
        //foreach($complex as $k=>$c)
        //{
        //    $listComplex .= 'Комплекс с ID  '.$k.' -- '.$c.'<br>';
        //}

        //$listComplex .= $this->deleteComplex($compl);
/*
        foreach($roomers as $kk => $vv)
        {
            $room[$kk] = $this->pRoomArray($vv);
        }

        $cRoom = count($room);

        //die();

       /* foreach($xmlFile as $k=>$d)
        {
          if($d['tag'] == 'Apartment')
          {
            $roomers[$d['attributes']['id']] = $d['attributes'];
            //$rID[] = $d['attributes']['id'];
          }
          elseif($d['tag'] == 'Block') // Обработка комплекса
          {
            $complex[$d['attributes']['id']] = $this->pComplexArray($d['attributes']);
            $compl[] = $d['attributes']['id'];
            //$this->deleteRoomZero($d['attributes']['id']);
          }
        }



        foreach($roomers as $kk => $vv)
        {
          $room[$kk] = $this->pRoomArray($vv);
        }

        $cRoom = count($room);
        $cComplex = count($complex);

        $listComplex = '';
        foreach($complex as $k=>$c)
        {
            $listComplex .= 'Комплекс с ID  '.$k.' -- '.$c.'<br>';
        }

        $listComplex .= $this->deleteComplex($compl);

        $listRoomAdd = 0;
        $listRoomUpdate = 0;
        $listRoomError = 0;
        $idError = array();
        $listRoomDelete = $this->clearRoom($room, $complex);
        foreach($room as $k=>$c)
        {
            if($c == 'insert') { $listRoomAdd++; }
            elseif ($c == 'update') { $listRoomUpdate++; }
            else { $listRoomError++; $idError[] = $k; }
        }

        $liRoom = " КВАРТИР:<br>
                    Добавлено: {$listRoomAdd}<br>
                    Отредактировано квартир: {$listRoomUpdate}<br>
                    Удалено квартир: {$listRoomDelete}<br>
                    Ошибок редакторирования/удаления: {$listRoomError}<br>
                    ID в которых возникли ошибки:".implode(' | ', $idError);
*/
        /*$listRoomAdd = 0;
        $listRoomUpdate = 0;
        $listRoomError = 0;
        $idError = array();
        $listRoomDelete = $this->clearRoom($room, $complex);
        foreach($room as $k=>$c)
        {
            if($c == 'insert') { $listRoomAdd++; }
            elseif ($c == 'update') { $listRoomUpdate++; }
            else { $listRoomError++; $idError[] = $k; }
        }

        $liRoom = " КВАРТИР:<br>
                    Добавлено: {$listRoomAdd}<br>
                    Отредактировано квартир: {$listRoomUpdate}<br>
                    Удалено квартир: {$listRoomDelete}<br>
                    Ошибок редакторирования/удаления: {$listRoomError}<br>
                    ID в которых возникли ошибки:".implode(' | ', $idError);
*/
        echo json_encode(array(
            'ok' => 'Найдено '.$cRoom.' квартир и '.$cComplex.' комплекс.',
            'complex' => $listComplex,
            //'list' => $liRoom
        ));
    }
	
	    function ajax_complex_cron()
    {
        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);
        $files = $this->input->post('files');
        $xmlFile = $this->readDatabase($files);
		//print_r($xmlFile);
        foreach($xmlFile as $k=>$d)
        {
          if($d['tag'] == 'Building')
          {
            $this->typeDoma[$d['attributes']['blockid']] = $d['attributes']['buildingtype'];
            $this->optPeriod[$d['attributes']['blockid']] = $d['attributes']['endingperiod'];
            $this->building[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Builder')
          {
              $this->builders[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Subway')
          {
              $this->subways[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Region')
          {
              $this->regions[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'RoomType')
          {
              $this->roomTypes[$d['attributes']['id']] = $d['attributes']['name'];
          }
          elseif($d['tag'] == 'Bank')
          {
              $this->banks[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Mortgage')
          {
              $this->mortgage[$d['attributes']['blockid']][] = $d['attributes'];
          }
          elseif($d['tag'] == 'Apartment')
          {
              $this->apartments[$d['attributes']['blockid']][] = $d['attributes'];
              $roomers[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'Block')
          {
              $this->blocks[$d['attributes']['id']] = $d['attributes'];
          }
          elseif($d['tag'] == 'BlockSubway')
          {
              $this->block_subways[$d['attributes']['blockid']][] = $d['attributes'];
          }
        }

        foreach ($this->subways as $k=>$v){
            $f = $this->setMetroList($v);
        }
        foreach ($this->regions as $k=>$v){
            $f = $this->setRayonList($v);
        }
        foreach ($this->builders as $k=>$v){
            $f = $this->setBuildersList($v);
        }
        foreach ($this->banks as $k=>$v){
            $f = $this->setBanksList($v);
        }
        $this->getRayons();
        $this->getMetro();
        $this->getBanks();

        $compl = array();

        $listComplex = '';

        $cRoom = count($roomers);

        $cComplex = count($this->blocks);


        foreach ($this->blocks as $k => $v){
            $compl[] = $k;
			$this->ajax_complex_save($files,$k);
        }
        $this->deleteComplex($compl);
		
		foreach ($this->blocks as $k => $v){
			$this->ajax_complex_save($files,$k);
        }

        echo json_encode(array(
            'ok' => 'Найдено '.$cRoom.' квартир и '.$cComplex.' комплекс.',
            'complex' => $listComplex,
            //'list' => $liRoom
        ));
    }

    function ajax_complex_save($files = null, $complex = null)
    {
        set_time_limit(0);
        ini_set('MAX_EXECUTION_TIME', -1);
		if(!$files)
        $files = $this->input->post('files');
		if(!$complex)
        $complex = $this->input->post('complex');
        $xmlFile = $this->readDatabase($files);

        foreach($xmlFile as $k=>$d)
        {
            if($d['tag'] == 'Building') // Тип дома
            {
                $this->typeDoma[$d['attributes']['blockid']] = $d['attributes']['buildingtype'];
                $this->optPeriod[$d['attributes']['blockid']] = $d['attributes']['endingperiod'];
                $this->building[$d['attributes']['id']] = $d['attributes'];
            }

            elseif($d['tag'] == 'Builder') // Метро
            {
                $this->builders[$d['attributes']['id']] = $d['attributes'];
            }
            elseif($d['tag'] == 'Subway') // Метро
            {
                $this->subways[$d['attributes']['id']] = $d['attributes'];
            }
            elseif($d['tag'] == 'Region') // Метро
            {
                $this->regions[$d['attributes']['id']] = $d['attributes'];
            }
            elseif($d['tag'] == 'RoomType') // Метро
            {
                $this->roomTypes[$d['attributes']['id']] = $d['attributes']['name'];
            }
            elseif($d['tag'] == 'Bank') // Метро
            {
                $this->banks[$d['attributes']['id']] = $d['attributes'];
            }
            elseif($d['tag'] == 'Mortgage') // Метро
            {
                $this->mortgage[$d['attributes']['blockid']] = $d['attributes'];
            }
            elseif($d['tag'] == 'Apartment') // Метро
            {
                if ($d['attributes']['blockid'] == $complex){
                    $this->apartments[] = $d['attributes'];
                    $app[] = $d['attributes']['id'];
                }
            }
            elseif($d['tag'] == 'Block') // Метро
            {
                $this->blocks[$d['attributes']['id']] = $d['attributes'];
                if ($d['attributes']['id'] == $complex)
                    $block = $d['attributes'];
            }
            elseif($d['tag'] == 'BlockSubway') // Метро
            {
                $this->block_subways[$d['attributes']['blockid']][] = $d['attributes'];
            }
        }

        foreach ($this->subways as $k=>$v){
            //    echo $this->setMetroList($v)."<br>";
            $f = $this->setMetroList($v);
        }
        foreach ($this->regions as $k=>$v){
            //    echo $this->setMetroList($v)."<br>";
            $f = $this->setRayonList($v);
        }
        foreach ($this->builders as $k=>$v){
            //    echo $this->setMetroList($v)."<br>";
            $f = $this->setBuildersList($v);
        }
        foreach ($this->banks as $k=>$v){
            //    echo $this->setMetroList($v)."<br>";
            $f = $this->setBanksList($v);
        }
        $this->getRayons();
        $this->getMetro();
        $this->getBanks();
        $res = $this->pComplexArray($block);
        $action = $res['action'];
        foreach ($this->apartments as $k => $v){
            $room[] = $this->pRoomArray($v);
        }
        $listRoomAdd = 0;
        $listRoomUpdate = 0;
        $listRoomError = 0;
        $idError = array();
        $delrooms = $this->deleteApartments($res['id'], $app);
        foreach($room as $k=>$c)
        {
            if($c == 'insert') { $listRoomAdd++; }
            elseif ($c == 'update') { $listRoomUpdate++; }
            else { $listRoomError++; $idError[] = $k; }
        }
        $this->updatePrice($res['id']);
        echo json_encode(array(
            'ok' => $action,
            'delrooms' => $delrooms,
            'insrooms' => $listRoomAdd,
            'updrooms' => $listRoomUpdate,
        ));
    }

    // чтение XML. Создаем массив
    function readDatabase($filename)
    {
        $data = implode("", file($filename));
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $values, $tags);
        xml_parser_free($parser);

        return $values;
    }

    function deleteComplex($compl){
        $this->db->where('id_complex >', 0);
        $query = $this->db->get('buildings');
        $que = $rows = $query->result_array();
        $str = '';

        $text = array();

        foreach ($que as $v){
            if (!in_array($v['id_complex'], $compl))
            {
                $text[] = $v['title'];
                $text[] = 'http://www.m16-estate.ru/buildings/'.$v['link'];
                $text[] = '------------------------------';

                $str .= 'Жилой комплекс "'.$v['title'].'" с ID '.$v['id'].' будет удален - ссылка <a href="http://m16-estate.ru/buildings/'.$v['link'].'">http://m16-estate.ru/buildings/'.$v['link'].'</a>'."<br>";
                $this->db->delete('buildings', array('banned' => 1), array('id'=>$v['id']));
            }
        }

        if (count($text))
            remove_notify(implode("<br />", $text), 'Удаление новостроек (парсер)');

        return $str;
    }

    function deleteApartments($complex,$compl){
        $this->db->where('id_room >', 0);
        $this->db->where('novostroy_id', $complex);
        $query = $this->db->get('apartments');
        $que = $rows = $query->result_array();
        $str = '';
        $count = 0;
        foreach ($que as $v){
            if (!in_array($v['id_room'], $compl)){
                $this->db->delete('apartments', array('id'=>$v['id']));
                $count++;
            }
        }
        return $count;
    }

    // Формируем массив с данными для комплекса
    function pComplexArray($data)
    {
       $id = $data['id'];

       $img = $data['avatar'];
       if(empty($img)) { $img = '/asset/images/logo-M16.png'; } else { $img = '/asset/uploads/crm/'.$img; }

       $serailize['foto'][0] = $img;
       $serailize['alt'][0] = '';

       $foto = serialize($serailize);
        $dop_metro = array();
        $banks = array();

        foreach ($this->block_subways[$id] as $k=>$value){
            if ($k == 0){
                $metro_id = $this->metro_list[$value['subwayid']];
                $otd = explode(' Минут ', $value['distance']);
                $otd_metr = (int)$otd;
                if (count($otd) > 1)
                    $otd_type = ($otd[1] == 'пешком') ? 0 : 1;
                else {
                    $otd_type = 0;
                }
            }
            else {
                $dop_metro[] = $this->metro_list[$value['subwayid']];
            }
        }

        foreach ($this->mortgage[$id] as $k=>$value){

                $banks[] = $this->bank_list[$value['bankid']];

        }

        $ipoteka = 0;

        if(count($banks)>0){
            $ipoteka = 1;
        }


       $mas = array(
            'id_complex' => $id, // ID по которому будем находить комплексы
            'metro_id' => $metro_id,
            'type_id' => $this->fType($this->typeDoma[$id]),
            'otd_metro_value'=>$otd_metr,
            'otd_metro'=>$otd_type,
            'dop_metro'=>implode(',', $dop_metro),
            'banks'=>implode(',', $banks),
            'banned' => '0',
            'ipoteka' => $ipoteka,
            'adress' => $data['address'], // адресс
            'name' => $data['title'], // название
            'title' => $data['title'], // title
            'text' => $data['note'], // описание
            'map' => $data['latitude'].' | '.$data['longitude'], // координаты
            'mainfoto' => $img, // главное изображение
            'link' => toTranslitUrl($data['title']).'_'.$data['id'], // ссылка
            'rayon_id' => $this->rayon_list[$data['region']],
            'foto' => $foto,
            'razdelu' => 0,
            'korpus' => 3,
            'korpus_value' => strtotime($this->optPeriod[$id])
       );

        $result= array();



       if($idl=$this->pIssetIDComplex($id)) // если запись есть уже в базе
       {
            $this->db->where('id_complex', $id);
            unset($mas['link']);
            unset($mas['foto']);
            unset($mas['razdelu']);
            unset($mas['title']);
            unset($mas['name']);
            unset($mas['text']);
            $mas['date_edit'] = time();
            if($this->db->update('buildings', $mas)) { $action = 'update'; } else { $action = 'update-error'; }
            $result['action'] = 'обновлен';
       }
       else // если записи нет в базе
       {
           $mas['date_add'] = time();
           $mas['date_edit'] = time();
            if($this->db->insert('buildings', $mas)) { $result['action'] = 'добавлен'; } else { $action = 'ошибка добавления'; }
            $idl = $this->db->insert_id();
       }
        $result['id'] = $idl;
        $this->dop_metro_add_parser($idl,$this->block_subways[$id]);
        $this->dop_rayon_add_parser($idl,array($data['region']));
        $this->mortgage_add_parser($idl,$this->mortgage[$id]);

       return $result;
    }

    public function dop_metro_add_parser($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete('ci_metro_buildings', $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'metro_id' => $this->metro_list[$v['subwayid']],
                    'distance' => $v['distance']
                );
                $this->db->insert('ci_metro_buildings', $mas);
            }
        }
    }

    public function mortgage_add_parser($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete('ci_banks_buildings', $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'bank_id' => $this->bank_list[$v['bankid']],
                );
                $this->db->insert('ci_banks_buildings', $mas);
            }
        }
    }

    public function updatePrice($id){
        $model = 'buildings_model';
        $this->load->model('buildings/'.$model, $model);
        $this->load->model('interest/admin_interest_model','mdInterest');
        $this->load->model('special/admin_special_model','mdSpecial');

        $prc = $this->$model->complexMinMaxPrice($id);
            if ($prc['min'] > 0 || $prc['max'] > 0){
                $prcfm = $this->$model->complexPriceForMeter($id);
                $this->db->update('ci_buildings', array('virtual_price' => $prc['min'], 'price' => $prc['min'], 'virtual_price_max' => $prc['max'], 'price_for_meter'=>$prcfm), array('id' => $id));
                $this->mdInterest->synchronization($id,'buildings',$prc['min']);
                $this->mdSpecial->synchronization($id,'buildings',$prc['min']);
            }

    }

    public function dop_rayon_add_parser($id, $mas){
        $where = array(
            'building_id' => $id
        );
        $this->db->delete('ci_rayon_buildings', $where);
        if (!empty($mas) && count($mas)>0){
            foreach ($mas as $v){
                $mas = array(
                    'building_id' => $id,
                    'rayon_id' => $this->rayon_list[$v],
                );
                $this->db->insert('ci_rayon_buildings', $mas);
            }
        }
    }

    // Формируем массив с данными для квартир
    function pRoomArray($data)
    {
       $id = $data['id'];

       $img = $data['flatplan'];

       if(empty($img)) { $img = '/asset/images/logo-M16.png'; } else { $img = '/asset/uploads/crm/'.$img; }

       $zapret = [30, 24];

       $mas = array(
            'banned' => '0',
            'id_room' => $id, // ID по которому будем находить квартиры
            'novostroy_id' => $this->complexID($data['blockid']), // ID комплекса
            'room_id' => $this->pRoom($data['rooms']), // К-во комнат
            'price' => $data['flatcostwithdiscounts'], // цена
            'price_for_meter' => (int)($data['flatcostwithdiscounts']/(doubleval($data['stotal']))),
            'floor' => $data['flatfloor'], // Этаж
            'square_all' => $data['stotal'], // Общая площадь
            'otdelka_id'   => $this->getDecoration($data['decoration']),
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

        /*if($Idd > 0) {
            if($this->pIssetRoom($mas)) {
                $Idd_true = false;
            }
        }*/

        if(in_array($data['rooms'], $zapret)) {
            $Idd_true = false;
        }

        if($Idd_true) {
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

    // Удаляем ненужные квартиры
    function clearRoom($arrayRoomId, $idComplex)
    {
        $del = 0;
        foreach($idComplex as $key=>$value)
        {
            $allRoomComplex = $this->allRoomComp($key);
            print_r($allRoomComplex); die();
            //$allRoomComplex = $data['id'];
            if(!empty($allRoomComplex))
            {
                foreach($arrayRoomId as $ki=>$vl)
                {
                    if(isset($allRoomComplex[$ki]))
                    {
                      unset($allRoomComplex[$ki]);
                    }
                }
            }

            if(!empty($allRoomComplex))
            {
              if(!empty($allRoomComplex))
              {
                foreach($allRoomComplex as $i)
                {
                      if($this->db->delete('apartments', array('id' => $i)))
                      {
                        $del++;
                      }
                }
              }
            }
        }

        return $del;
    }

    function allRoomComp($id)
    {
        $newID = $this->complexID($id);

        $this->db->where('novostroy_id', $newID);
        $this->db->where('id_room != ', '0');
        $query = $this->db->get('apartments');
        $rows = $query->result_array();

        $idArray = '';
        $rowsArray = '';
        if(!empty($rows))
        {
            foreach($rows as $r)
            {
                $rowsArray[$r['id_room']] = $r['id_room'];
                $idArray[$r['id_room']] = $r['id'];
            }
        }

        return $idArray;
    }

    // Узнаем тип дома
    function fType($id)
    {
        $value = trim($this->typeDoma[$id]);
        if($value == 'К/М') { $value = 'Кирпич-монолит'; }

        if(!empty($value))
        {
            $this->load->model('type/admin_type_model','model_type');
            $list_type = $this->model_type->parent_id();
            foreach($list_type as $p)
            {
                if($p['name'] == $value) { $type = $p['id']; }
            }

            if(empty($type)) // если нет совпадений по районам то добавляем его
            {
              $this->db->insert('ci_type', array('name' => $value));
              $type = $this->db->insert_id();
            }
        }
        else
        {
            $type = 6;
        }

        return $type;
    }

    function setMetroList($value)
    {
        $this->load->model('metro/admin_metro_model','model_metro');
        $list_metro = $this->model_metro->parent_id();
        foreach($list_metro as $p){
            if($p['abc_id'] == $value['id']) {
               $metro = $p['id'];
            }
        }
        if(empty($metro)) // если нет совпадений по районам то добавляем его
        {
                $this->db->insert('ci_metro', array('name' => $value['name'], 'abc_id' => $value['id']));
                $metro = $this->db->insert_id();
        }
        return $metro;
    }

    function setBanksList($value)
    {
        $query = $this->db->get('banks');
        $list_banks = $query->result_array();
        foreach($list_banks as $p){
            if($p['abc_id'] == $value['id']) {
                $bank = $p['id'];
            }
        }
        if(empty($bank)) // если нет совпадений по районам то добавляем его
        {
            $this->db->insert('ci_banks', array('name' => $value['name'], 'abc_id' => $value['id']));
            $bank = $this->db->insert_id();
        }
        return $bank;
    }

    function setRayonList($value)
    {
        $this->load->model('rayon/admin_rayon_model','model_rayon');
        $list_rayon = $this->model_rayon->parent_id();
        foreach($list_rayon as $p){
            if($p['abc_id'] == $value['id'] || $p['name'] == $value['name']) {
                $rayon = $p['id'];
            }
        }
        if(empty($rayon)) // если нет совпадений по районам то добавляем его
        {
            $this->db->insert('ci_rayon', array('name' => $value['name'], 'abc_id' => $value['id']));
            $rayon = $this->db->insert_id();
        }
        return $rayon;
    }

    function setBuildersList($value)
    {
        $this->load->model('builders/admin_builders_model','model_builders');
        $list_builders = $this->model_builders->parent_id();
        foreach($list_builders as $p){
            if($p['abc_id'] == $value['id']) {
                $builders = $p['id'];
            }
        }
        if(empty($builders)) // если нет совпадений по районам то добавляем его
        {
            $this->db->insert('ci_builders', array('name' => $value['name'], 'abc_id' => $value['id']));
            $builders = $this->db->insert_id();
        }
        return $builders;
    }

    // Узнаем метро
    function fMetro($id)
    {
        $value = trim($this->optMetro[$id]);

        if(!empty($value))
        {
          $this->load->model('metro/admin_metro_model','model_metro');
          $list_metro = $this->model_metro->parent_id();
          foreach($list_metro as $p)
          {
              if($p['name'] == $value) { $metro = $p['id']; }
          }

          if(empty($metro)) // если нет совпадений по районам то добавляем его
          {
            $this->db->insert('ci_metro', array('name' => $value));
            $metro = $this->db->insert_id();
          }
        }
        else
        {
          $metro = 51;
        }

        return $metro;
    }

     // Вытаскиваем районы
    function pRayon($region)
    {
        $region = trim($region);

        if(!empty($region))
        {
            $this->load->model('rayon/admin_rayon_model','model_rayon');
            $list_rayon = $this->model_rayon->parent_id();
            foreach($list_rayon as $p)
            {
                if($p['name'] == $region) { $rayon = $p['id']; }
            }

            if(empty($rayon)) // если нет совпадений по районам то добавляем его
            {
              $this->db->insert('ci_rayon', array('name' => $region));
              $rayon = $this->db->insert_id();
            }

            return $rayon;
        }
        else
        {
            return 26;
        }
    }

    function getRayons(){
        $this->load->model('rayon/admin_rayon_model','model_rayon');
        $list_rayon = $this->model_rayon->parent_id();
        foreach($list_rayon as $p)
        {
            if($p['abc_id'] > 0) { $this->rayon_list[$p['abc_id']] = $p['id']; }
        }
    }

    function getMetro(){
        $this->load->model('metro/admin_metro_model','model_metro');
        $list_metro = $this->model_metro->parent_id();
        foreach($list_metro as $p)
        {
            if($p['abc_id'] > 0) { $this->metro_list[$p['abc_id']] = $p['id']; }
        }
    }

    function getBanks(){
        $this->load->model('banks/admin_banks_model','model_banks');
        $list_metro = $this->model_banks->parent_id();
        foreach($list_metro as $p)
        {
            if($p['abc_id'] > 0) { $this->bank_list[$p['abc_id']] = $p['id']; }
        }
    }

    // Вытаскиваем комнаты
    function pRoom($room)
    {
        $room = trim($room);
        $room = trim(str_replace(' ккв', '', $room));
        $room = trim(str_replace('ккв', '', $room));
        $room = trim(str_replace('(Евро)', '', $room));

        if($room == 21) { $room = '4ккв (евро)'; }
        elseif($room == 22) { $room = '2ккв (евро)'; }
        elseif($room == 23) { $room = '3ккв (евро)'; }
        elseif($room == 25) { $room = 'К. пом'; }
        elseif($room == 30) { $room = 'Кладовка'; }
        elseif($room == 28) { $room = 'Таун Хаус'; }
        elseif($room == 29) { $room = 'Коттедж'; }

        if(!empty($room))
        {
            $this->load->model('room/admin_room_model','model_room');
            $list_room = $this->model_room->parent_id();
            foreach($list_room as $p)
            {
                if($p['name'] == $room) { $id = $p['id']; }
            }

            if(empty($id)) // если нет совпадений по районам то добавляем его
            {
                $this->db->insert('ci_room', array('name' => $room));
                $id = $this->db->insert_id();
            }

            return $id;
        }
        else
        {
            return 1;
        }
    }

    // Транслитерация рус => лат
    function translitLink($title)
    {
      $chars = array(
       "Є"=>"EH","І"=>"I","і"=>"i","№"=>"N","є"=>"eh",
       "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
       "Е"=>"E","Ё"=>"JO","Ж"=>"ZH",
       "З"=>"Z","И"=>"I","Й"=>"JJ","К"=>"K","Л"=>"L",
       "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
       "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"KH",
       "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
       "Ы"=>"Y","Ь"=>"","Э"=>"EH","Ю"=>"YU","Я"=>"YA",
       "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
       "е"=>"e","ё"=>"jo","ж"=>"zh",
       "з"=>"z","и"=>"i","й"=>"jj","к"=>"k","л"=>"l",
       "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
       "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"kh",
       "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
       "ы"=>"y","ь"=>"","э"=>"eh","ю"=>"yu","я"=>"ya",
       "—"=>"-","«"=>"","»"=>"","…"=>""," "=>"_","'"=>""
      );

    	if ($this->seems_utf8($title))
    		$title = urldecode($title);

    	$title = preg_replace('/\.+/','.',$title);
    	$r = strtr($title, $chars);
        return mb_strtolower($r);
    }

    // Проверяет строку на UTF-8 кодировку
    function seems_utf8($str) {
    	$length = strlen($str);
    	for ($i=0; $i < $length; $i++) {
    		$c = ord($str[$i]);
    		if ($c < 0x80) $n = 0; # 0bbbbbbb
    		elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
    		elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
    		elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
    		elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
    		elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
    		else return false; # Does not match any model
    		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
    			if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
    				return false;
    		}
    	}
    	return true;
    }

    // Проверяем есть ли ID уже в базе
    public function pIssetIDComplex($id)
    {
        $this->db->where('id_complex', $id);
        $query = $this->db->get('buildings');
        $que = $query->row_array();

        if(isset($que['id']))
            return $que['id'];
        else
            return false;
    }

    function complexID($id)
    {
        $this->db->where('id_complex', $id);
        $query = $this->db->get('buildings');
        $que = $query->row_array();

        return $que['id'];
    }

    // Проверяем есть ли ID уже в базе
    public function pIssetIDRoom($id)
    {
        $this->db->where('id_room', $id);
        $query = $this->db->get('apartments');
        $que = $query->row_array();

        if(isset($que['id']))
            return $que['id'];
        else
            return 0;
    }

    /*
    'banned' => '0',
    'id_room' => $id, // ID по которому будем находить квартиры
    'novostroy_id' => $this->complexID($data['blockid']), // ID комплекса
    'room_id' => $this->pRoom($data['rooms']), // К-во комнат
    'price' => $data['flatcostwithdiscounts'], // цена
    'price_for_meter' => (int)($data['flatcostwithdiscounts']/(doubleval($data['stotal']))),
    'floor' => $data['flatfloor'], // Этаж
    'square_all' => $data['stotal'], // Общая площадь
    'otdelka_id'   => $this->getDecoration($data['decoration']),
    'square_balcony' => $data['sbalcony'],
    'square_watercloset' => $data['swatercloset'],
    'square_corridor' => $data['scorridor'],
    'section' => $data['section'],
    'height' => $data['height'],
    'square_life' => $data['sroom'], // Жилая площадь
    'square_cook' => $data['skitchen'], // Площадь кухни
    'main_foto' => $img, // Изображение планировки
    */

    // Проверяем на однотипность квартиры (различие только по этажу
    public function pIssetRoom($mas)
    {
        $this->db->where('novostroy_id', $mas['novostroy_id']);
        $this->db->where('room_id', $mas['room_id']);
        $this->db->where('price', $mas['price']);
        $this->db->where('square_all', $mas['square_all']);
        $this->db->where('square_balcony', $mas['square_balcony']);
        $this->db->where('square_life', $mas['square_life']);
        $this->db->where('section', $mas['section']);


        $query = $this->db->get('apartments');
        $que = $query->row_array();

        if(isset($que['id']))
            return true;
        else
            return false;
    }

    function deleteRoomZero($id_complex)
    {
        $id = $this->complexID($id_complex);
        $this->db->where('id_room', 0);
        $this->db->where('category_id', $id);
        $this->db->delete('apartments');
    }

    function lambery(){
        die();
        $this->load->library('simple_html_dom');

        $data = file_get_html('http://lambery.ru/genplan');
        $links = array();
        foreach($data->find('.views-field-field-house-link a') as $tmp){
            $links[] = 'http://lambery.ru'.$tmp->href;
        }
        $data->clear();// подчищаем за собой
        unset($data);


        foreach ($links as $value){

            $html = file_get_html($value);

            $images = array();
            foreach($html->find('.gallery-slide a') as $tmp){
                $images[] = $tmp->href;
            }
            $text = '';
            foreach($html->find('.field-type-text-with-summary .field-item') as $txt){
                $text .= $txt->innertext;
            }
            $projectname = '';
            foreach($html->find('.field-name-field-projectname a') as $txt){
                $projectname .= $txt->innertext;
            }
            $name = '«'.$projectname.'», ';
            foreach($html->find('.left_menu .h1') as $txt){
                $name .= mb_strtolower($txt->innertext);
            }
            $square = '';
            foreach($html->find('.field-name-field-square .field-item') as $txt){
                $square .= $txt->innertext;
            }
            $square = explode(' ', $square);
            $square = $square[0];
            $area = '';
            foreach($html->find('.field-name-field-area .field-item') as $txt){
                $area .= $txt->innertext;
            }
            $area = explode(' ', $area);
            $area = $area[0];
            $area = str_replace(',', '.', $area);
            $area = floatval($area);

            if ($area > 0){
                $mas = array();
                $mas['name'] = $name;
                $mas['parser_link'] = $value;
                $mas['parent_id'] = 1014;
                $mas['land_square'] = $area;
                $mas['house_square'] = $square;
                $mas['text'] = $text;
                $foto = array();
                $alt = array();
                foreach ($images as $ki => $vi){
                    $n = md5(time().$vi);
                    if(file_put_contents('./asset/uploads/images/buildings/'.$n.'.jpg',file_get_contents($vi))){
                        $foto[] = '/asset/uploads/images/buildings/'.$n.'.jpg';
                        $alt[] = '';
                    }
                }
                if (count($foto) > 0){
                    $serailize['foto'] = $foto;
                    $serailize['alt'] = $alt;
                    $fotos = serialize($serailize);
                    $mainfoto = $foto[0];
                    $mas['main_foto'] = $mainfoto;
                    $mas['foto'] = $fotos;
                }
                if($this->db->insert('cottages', $mas)) {
                    $idl = $this->db->insert_id();
                    $link = toTranslitUrl($mas['name']);
                    $this->db->update('cottages',array('link'=>$link.'-'.$idl), array('id' => $idl));
                    echo $value.' - '.$area.'<br>';
                }
                else {
                    $action = 'ошибка добавления';
                }


            }



            $html->clear();// подчищаем за собой
            unset($html);

        }



    }

    function gorodu_net(){

        $this->load->library('simple_html_dom');
        $data = file_get_html('http://www.gorodu.net/settlements/ohtinskiy_park');
        $links = array();
        foreach($data->find('#pullheader .elem a') as $tmp){
            $links[] = 'http://www.gorodu.net'.$tmp->href;
            if ($tmp->innertext == 'План поселка'){
                $plan_link = 'http://www.gorodu.net'.$tmp->href;
            }
            if ($tmp->innertext == 'Участки'){
                $districts_link = 'http://www.gorodu.net'.$tmp->href;
            }
            if ($tmp->innertext == 'Готовые дома'){
                $houses_link = 'http://www.gorodu.net'.$tmp->href;
            }
            if ($tmp->innertext == 'Расположение'){
                $location_link = 'http://www.gorodu.net'.$tmp->href;
            }
            if ($tmp->innertext == 'Инфраструктура'){
                $infrastructure_link = 'http://www.gorodu.net'.$tmp->href;
            }
            if ($tmp->innertext == 'Фотогалерея'){
                $photo_link = 'http://www.gorodu.net'.$tmp->href;
            }
        }

        //echo $plan_link.'<br>';
        echo $districts_link.'<br>';
        //echo $houses_link.'<br>';
        //echo $location_link.'<br>';
        //echo $infrastructure_link.'<br>';
        //echo $photo_link.'<br>';
        if (!empty($infrastructure_link)){
            $data_il = file_get_html($infrastructure_link);
            foreach($data_il->find('.content .text') as $tmp){
                $tmp->find('h1')->outertext = '';
                $tmp->find('a')->outertext = '';
                foreach ($tmp->find('img') as $imv){
                    $imv->outertext = '<img width="'.$imv->width.'" height="'.$imv->height.'" align="left" src="http://www.gorodu.net'.$imv->src.'" />';
                }
                $infrastructure = $tmp->innertext;
            }

            $data_il->clear();// подчищаем за собой
            unset($data_il);
        }
        //echo $infrastructure;
        if (!empty($location_link)){
            $data_il = file_get_html($location_link);
            foreach($data_il->find('.content .text') as $tmp){
                foreach ($tmp->find('h1') as $ih){
                    $ih->outertext = '';
                }
                $tmp->find('a')->outertext = '';
                foreach ($tmp->find('img') as $imv){
                    $imv->outertext = '<img width="'.$imv->width.'" height="'.$imv->height.'" align="left" src="http://www.gorodu.net'.$imv->src.'" />';
                }
                $location = $tmp->innertext;
            }

            $data_il->clear();// подчищаем за собой
            unset($data_il);
        }
        //echo $location;
        $images = array();
        if (!empty($photo_link)){
            $data_il = file_get_html($photo_link);
            $tmp = $data_il->find('.gallery .two .one .image', 0);
            foreach ($tmp->find('a[class$=small]') as $av){
                $av->outertext = '';
            }
            $tmp = str_get_html($tmp->outertext);
            foreach ($tmp->find('a') as $av){
                if (!empty($av->href)){
                    $images[] = 'http://www.gorodu.net'.$av->href;
                }

            }
            $tmp->clear();// подчищаем за собой
            unset($tmp);
            $data_il->clear();// подчищаем за собой
            unset($data_il);
        }
        //print_r($images);
        /*if (!empty($districts_link)){
            $data_il = file_get_html($districts_link);
            foreach($data_il->find('#tables .text') as $tmp){
                foreach ($tmp->find('h1') as $ih){
                    $ih->outertext = '';
                }
                $tmp->find('a')->outertext = '';
                foreach ($tmp->find('img') as $imv){
                    $imv->outertext = '<img width="'.$imv->width.'" height="'.$imv->height.'" align="left" src="http://www.gorodu.net'.$imv->src.'" />';
                }
                $location = $tmp->innertext;
            }

            $data_il->clear();// подчищаем за собой
            unset($data_il);
        }*/
       /* $mas = array();
        $mas['name'] = 'Ольшаники';
        $mas['parser_link'] = 'http://www.gorodu.net/settlements/olshaniki';
        $mas['is_cottage'] = '1';
        $mas['rayon_id'] = '3';
        $mas['razdelu'] = '2';
        $mas['price'] = '0';
        $mas['adress'] = '';
        $mas['location'] = $location;
        $mas['infrastruct'] = $infrastructure;
        $mas['map'] = '60.393547|29.787425';
        $foto = array();
        $alt = array();
        foreach ($images as $ki => $vi){
            $n = md5(time().$vi);
            if(file_put_contents('./asset/uploads/images/buildings/'.$n.'.jpg',file_get_contents($vi))){
                $foto[] = '/asset/uploads/images/buildings/'.$n.'.jpg';
                $alt[] = '';
            }
        }
        if (count($foto) > 0){
            $serailize['foto'] = $foto;
            $serailize['alt'] = $alt;
            $fotos = serialize($serailize);
            $mainfoto = $foto[0];
            $mas['mainfoto'] = $mainfoto;
            $mas['foto'] = $fotos;
        }
        if($this->db->insert('buildings', $mas)) {
            $idl = $this->db->insert_id();
            $link = toTranslitUrl($mas['name']);
            $this->db->update('buildings',array('link'=>$link.'-'.$idl), array('id' => $idl));
            $this->db->insert('rayon_buildings', array('building_id'=>$idl, 'rayon_id'=>'3'));
        }*/
        if (!empty($districts_link)){
            $data_il = file_get_html($districts_link);
            $tmp = $data_il->find('.content .table-wrapper .grounds', 6);
            $tmp = str_get_html($tmp->outertext);
            //foreach($data_il->find('.content .table-wrapper .grounds', 0) as $tmp){
            foreach ($tmp->find('.pop_up') as $ihp){
                $ihp->outertext = '';
            }
            $tmp = str_get_html($tmp->outertext);

                foreach ($tmp->find('tbody tr') as $ih){
                    if ($ih->class != 'head'){
                        //echo $ih->outertext.'<br>';
                        $tmpl = str_get_html($ih->outertext);
                        $masf = array();
                        foreach ($tmpl->find('tr > td') as $k => $vm){
                            if ($k == 0){
                                $masf['identity'] = $vm->innertext;
                                $masf['name'] = 'Участок №'.$masf['identity'];
                            }
                            if ($k == 1)
                                $masf['land_square'] = $vm->innertext;
                            //if ($k == 2)
                            //    $masf['house_square'] = $vm->plaintext;
                            if ($k == 2)
                                $masf['price'] = $vm->innertext;
                                $masf['price'] = str_replace(' ', '', $masf['price']);
                            if ($k == 3)
                                $sta = trim($vm->innertext);
                                if ($sta == 'В продаже'){
                                    $masf['status'] = 1;
                                }
                                if ($sta == 'вторичная продажа'){
                                    $masf['status'] = 2;
                                }
                                //$masf['status'] = trim($vm->innertext);
                            if ($k == 4){
                                //$vv = $vm->find('a[class$=small]', 0);
                                //print_r($vv);
                                //$masf['note'] = 'http://www.gorodu.net'.$vv->href;
                                //$masf['note'] = $vm->innertext;
                                $masf['type'] = '2';
                                if ($vv = $vm->find('a', 0)){
                                    $hlink = 'http://www.gorodu.net'.$vv->href;
                                    $masf['is_house'] = 1;
                                    $data_hg = file_get_html($hlink);
                                    $hn = $data_hg->find('h1.house', 0);
                                    $masf['house_name'] = $hn->innertext;
                                    $hf = $data_hg->find('.photos img');
                                    $him = array();
                                    foreach ($hf as $khv=>$vhv){
                                        $src = $vhv->src;
                                        $src = 'http://www.gorodu.net'.str_replace('90x58','orig',$src);
                                        $him[] = $src;
                                    }
                                    $masf['img'] = $him;
                                    $masf['type'] = '1';
                                }
                            }
                        }

                        $masf['parent_id'] = '1027';
                        $foto = array();
                        $alt = array();
                        if (!empty($masf['img'])){
                            foreach ($masf['img'] as $kim=>$vim){
                                $n = md5(time().$vim);
                                if(file_put_contents('./asset/uploads/images/buildings/'.$n.'.jpg',file_get_contents($vim))){
                                    $foto[] = '/asset/uploads/images/buildings/'.$n.'.jpg';
                                    $alt[] = '';
                                }
                            }
                        }
                        if (count($foto) > 0){
                            $serailize['foto'] = $foto;
                            $serailize['alt'] = $alt;
                            $fotos = serialize($serailize);
                            $mainfoto = $foto[0];
                            $masf['main_foto'] = $mainfoto;
                            $masf['foto'] = $fotos;
                        }
                        unset($masf['img']);
                        if($this->db->insert('cottages', $masf)) {
                            $idl = $this->db->insert_id();
                            $link = toTranslitUrl($masf['name']);
                            $this->db->update('cottages',array('link'=>$link.'-'.$idl), array('id' => $idl));
                            //echo $value.' - '.$area.'<br>';
                        }
                        echo '<pre>';
                        print_r($masf);
                    }
                }
                //$tmp->find('a')->outertext = '';
                //foreach ($tmp->find('img') as $imv){
                //    $imv->outertext = '<img width="'.$imv->width.'" height="'.$imv->height.'" align="left" src="http://www.gorodu.net'.$imv->src.'" />';
                //}
                //$location = $tmp->innertext;
           // }

            $data_il->clear();// подчищаем за собой
            unset($data_il);
        }
        $data->clear();// подчищаем за собой
        unset($data);
    }

    function getYML(){
        $this->load->model('buildings/buildings_model','buildings_model');

        $buildings = $this->buildings_model->allBuildings(1,'*');
        $novostroyki = array();
        foreach ($buildings as $key => $value){
            $novostroyki[$value['id']] = $value;
        }
        $this->load->model('rayon/admin_rayon_model','admin_rayon_model');
        $rays = $this->admin_rayon_model->parent_id();
        $districts = array();
        foreach ($rays as $key => $value){
            $districts[$value['id']] = $value['name'];
        }
        $this->load->model('metro/admin_metro_model','admin_metro_model');
        $met = $this->admin_metro_model->parent_id();
        $metro = array();
        foreach ($met as $key => $value){
            $metro[$value['id']] = $value['name'];
        }
        $this->load->model('room/admin_room_model','admin_room_model');
        $rom = $this->admin_room_model->parent_id();
        $rooms = array();
        foreach ($rom as $key => $value){
            $rooms[$value['id']] = $value;
        }
        //print_r($novostroyki);
       /* $this->load->model('buildings/buildings_model','buildings_model');
        $buildings = $this->buildings_model->allBuildings();
        $novostroyki = array();
        foreach ($buildings as $key => $value){
            $novostroyki[$value['id']] = $value;
        }*/


        $xml = new XMLWriter();
        $xml->openURI('./asset/uploads/yandexfeed.xml');
        $xml->setIndent(true);
        $xml->startDocument('1.0','UTF-8');
        $xml->startElement("realty-feed");
        $xml->writeAttribute('xmlns', 'http://webmaster.yandex.ru/schemas/feed/realty/2010-06');
        $xml->writeElement("generation-date", date(DATE_ATOM, time()));

            foreach($novostroyki as $k=>$v){
                $foto = unserialize($v['foto']);
                $xml->startElement("offer");
                    $xml->writeAttribute('internal-id', 'r'.$k);
                    $xml->writeElement('type','продажа');
                    $xml->writeElement('property-type','жилая');
                    $xml->writeElement('category','квартира');
                    $xml->writeElement('url','http://m16-estate.ru/resale/'.$v['link']);
                    $xml->writeElement('creation-date', date(DATE_ATOM, $v['date_add']));
                    $xml->writeElement('last-update-date', date(DATE_ATOM, $v['date_edit']));
                    $xml->startElement("location");
                        $xml->writeElement('country','Россия');
                        if ($v['area']=='Санкт-Петербург'){
                            $xml->writeElement('locality-name','Санкт-Петербург');
                            $xml->writeElement('sub-locality-name', $districts[$v['rayon_id']]);
                        }
                        else {
                            $xml->writeElement('region','Ленинградская область');
                            $xml->writeElement('district', $districts[$v['rayon_id']]);
                            $xml->writeElement('locality-name',$v['locality_name']);
                        }
                        $xml->writeElement('address',$v['adress']);
                        if (!empty($v['metro_id'])){
                            $xml->startElement("metro");
                                $xml->writeElement('name',$metro[$v['metro_id']]);
                            $xml->endElement();
                        }
                        if (!empty($v['map'])){
                            $lats = explode('|', $v['map']);
                            $xml->writeElement('latitude',$lats[0]);
                            $xml->writeElement('longitude',$lats[1]);
                        }
                    $xml->endElement();
                    $xml->startElement("area");
                        $xml->writeElement('value',$v['square_all']);
                        $xml->writeElement('unit','кв.м');
                    $xml->endElement();
                    if (!empty($v['square_life'])){
                        $xml->startElement("living-space");
                            $xml->writeElement('value',$v['square_life']);
                            $xml->writeElement('unit','кв.м');
                        $xml->endElement();
                    }
                    if (!empty($v['room_id'])){
                        $room = $rooms[$v['room_id']];
                        if ($room['id'] == 1){
                            $xml->writeElement('rooms','1');
                            $xml->writeElement('rooms-type','Студия');
                        }
                        else {
                            $xml->writeElement('rooms',(int)$room['name']);
                        }
                    }
                    $xml->startElement("sales-agent");
                        $xml->writeElement('phone','8-800-550-55-16');
                        $xml->writeElement('category','agency');
                        $xml->writeElement('organization','«М16-Недвижимость»');
                        $xml->writeElement('url','http://m16-estate.ru');
                        $xml->writeElement('photo','http://m16-estate.ru/asset/assets/img/m16-logo.png');
                    $xml->endElement();
                    foreach ($foto['foto'] as $kf => $vf){
                        $xml->writeElement('image','http://m16-estate.ru'.$vf);
                    }
                    $xml->writeElement('description',strip_tags($v['text']));
                    $xml->startElement("price");
                        $xml->writeElement('value',$v['price']);
                        $xml->writeElement('currency','RUR');
                    $xml->endElement();
                $xml->endElement();

            }
        $xml->endElement();
        $xml->endDocument();

    }
}