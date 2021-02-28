<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class military_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'military/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = 'buildings';
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);
	}

    function all_count($banned = false, $where = '', $like = '')
    {
        $get_active_tab = $this->session->userdata('military_active_tab');

        $this->db->where('buildings.banned','0'); // вытаскивать только видимые данные
        $this->db->where('buildings.military','1'); // вытаскивать только видимые данные
        $this->db->where('buildings.military_banned','0'); // вытаскивать только видимые данные

        // Новострой или вторичка
        if($get_active_tab == 'buildings') {
            $this->db->where('buildings.razdelu', 0);
        } else {
            $this->db->not_like('buildings.razdelu', '0');
        }

        $query = $this->db->get($this->table)->result_array();
        return count($query);
    }

    /**
     * Вытаскиваем все данные по указанной опции пагинации
     * @param $offset - к-во вытаскиваемых записей
    **/
	function pagination($offset = 0, $uri, $params)
    {
        $get_active_tab = $this->session->userdata('military_active_tab');

        if (!empty($params)) {
            switch ($get_active_tab) {
                case 'buildings':
                    $uu = 0;
                    break;

                case 'resale':
                    $uu = 1;
                    break;
            }

            $this->functions($params, $uu);

            // Банк
            if (!empty($params['bank']))
                $this->db->where("banks IN (".$params['bank'].")");
        }

        $sort = $this->sortAscDesc;

        if(isset($this->conf['sortList']))
        {
            //$sort = $this->conf['sortList'];
        }

        $this->db->where('buildings.banned','0'); // вытаскивать только видимые данные
        $this->db->where('buildings.military','1'); // вытаскивать только видимые данные
        $this->db->where('buildings.military_banned','0'); // вытаскивать только видимые данные

        // Новострой или вторичка
        if($get_active_tab == 'buildings') {
            $this->db->where('buildings.razdelu', 0);
        } else {
            $this->db->not_like('buildings.razdelu', '0');
        }

        $this->db->order_by('buildings.military_sort', 'desc'); // сортировка
        $this->db->order_by('buildings.price', 'asc'); // сортировка

        // проверяем нужно ли использовать пагинацию
        if($this->conf['Paging'])
        {
            $per_paging = $this->conf['perPaging']; // количество записей на страницу

            if ($offset !== '999') {
                if($offset == 0 or $offset == 1)
                {
                    $offset = 0;
                }
                else
                {
                    $offset = ($offset-1) * $per_paging;
                }

                $this->db->limit($per_paging, $offset);
            }
        }

        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $this->lang_load($result, $this->table);
    }

    public function getInterests($off = 0){
        $this->db->where('banned','0');
        $this->db->limit(9, $off);
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    public function getInterestCount(){
        $this->db->select('id');
        $this->db->where('banned','0');
        return $this->db->count_all_results($this->table);
    }

    public function getInterestHide(){
        $this->db->select('*');
        $this->db->where('banned','0');
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function getSrok($param)
    {
        $srok['from'] = strtotime('01.01'.'.'.$param);
        $srok['to'] = strtotime('31.12'.'.'.$param);
        return $srok;
    }

    // Новостройки
    function getMinMaxBuildings()
    {
        $this->db->select('MIN(price) as min, MAX(price) as max');

        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->where('military','1'); // вытаскивать только видимые данные
        $this->db->where('military_banned','0'); // вытаскивать только видимые данные

        $get_active_tab = $this->session->userdata('military_active_tab');
        if($get_active_tab == 'buildings') {
            $this->db->where('razdelu', 0);
        } else {
            $this->db->not_like('razdelu', '0');
        }

        $query	= $this->db->get($this->table);
        $result	= $query->result_array();

        return $result[0];
    }

    function getMinMaxBuildingsSquare()
    {
        $this->db->select('MIN(min_square) as min, MAX(max_square) as max');

        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->where('military','1'); // вытаскивать только видимые данные
        $this->db->where('military_banned','0'); // вытаскивать только видимые данные

        $get_active_tab = $this->session->userdata('military_active_tab');
        if($get_active_tab == 'buildings') {
            $this->db->where('razdelu', 0);
        } else {
            $this->db->not_like('razdelu', '0');
        }

        $query	= $this->db->get($this->table);
        $result	= $query->result_array();

        return $result[0];
    }

    function functions($params, $uri)
    {
        switch ($uri)
        {
            // Новостройки
            case 0:
                $this->db->select('buildings.id, buildings.name, buildings.adress, buildings.user_price_for_meter, buildings.razdelu, buildings.id_complex, buildings.map, buildings.metro_id, buildings.rayon_id, buildings.link, buildings.korpus_value, buildings.korpus, buildings.mainfoto, buildings.virtual_price, buildings.ipoteka, buildings.fz214, buildings.parking, buildings.rassrochka, buildings.otdelka_id');

                // Таблица с аппартаментами
                if (!empty($params['room']) || !empty($params['price_from']) || !empty($params['price_to']) || !empty($params['square_from']) || !empty($params['square_to']))
                    $this->db->join('apartments', 'buildings.id = apartments.novostroy_id', 'inner');

                // Метро
                if (!empty($params['metro']) && $params['metro'] > 0 && empty($params['rayon']))
                {
                    $this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
                    $this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
                }

                // Район
                if (!empty($params['rayon']) && $params['rayon'] > 0 && empty($params['metro']))
                {
                    $this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
                    $this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
                }

                // Метро и район
                if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0)
                {
                    $this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'left');
                    $this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'left');
                    $this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
                }

                // Срок
                if (!empty($params['srok']))
                {
                    $where = array();
                    $srok = explode(',', $params['srok']);

                    foreach ($srok as $v)
                    {
                        if ($v == 1)
                        {
                            $where[] = "(`ci_buildings`.`korpus_value` <=".time().")";
                        }
                        else {
                            $tt = $this->getSrok($v);
                            $where[] = "(`ci_buildings`.`korpus_value` >=". $tt['from']." AND `ci_buildings`.`korpus_value` <=". $tt['to'].")";
                        }
                    }

                    $this->db->where("(".implode(' OR ', $where).")");
                }

                // Адрес
                if (!empty($params['adress']))
                    $this->db->like('adress', $params['adress']);

                // Кол-во комнат
                if (!empty($params['room']))
                    $this->db->where_in("apartments.room_id", explode(',', $params['room']));

                // Ипотека
                if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
                    $this->db->where('ipoteka', $params['ipoteka']);

                // С отделкой
                if (!empty($params['otdelka']) && $params['otdelka'] == 1)
                    $this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

                // Рассрочка
                if (!empty($params['rassrochka']))
                    $this->db->where('rassrochka', $params['rassrochka']);

                // ФЗ-214
                if (!empty($params['fz214']))
                    $this->db->where('fz214', $params['fz214']);

                // Парковка
                if (!empty($params['parking']))
                    $this->db->where('parking', $params['parking']);

                // Название
                if (!empty($params['name']))
                    $this->db->where("(`ci_buildings`.`name` LIKE '%".$params['name']."%' OR `ci_buildings`.`alias_name` LIKE '%".$params['name']."%')");

                // Тип дома
                if (!empty($params['type']))
                    $this->db->where("type_id IN (".$params['type'].")");

                // Класс
                if (!empty($params['class']))
                    $this->db->where("class IN (".$params['class'].")");

                // Застройщик
                if (!empty($params['builder']))
                    $this->db->where("builder_id IN (".$params['builder'].")");

                // Цена от
                if (!empty($params['price_from']))
                    $this->db->where('apartments.price >=', intval($params['price_from']*1000000));

                // Цена до
                if (!empty($params['price_to']))
                    $this->db->where('apartments.price <=', intval($params['price_to']*1000000));

                // Площадь от
                if (!empty($params['square_from']))
                    $this->db->where('apartments.square_all >=', floatval($params['square_from']));

                // Площадь до
                if (!empty($params['square_to']))
                    $this->db->where('apartments.square_all <=', intval($params['square_to']));

                break;

            // Вторичка
            case 1:
                $this->db->select('*');

                // Метро
                if (!empty($params['metro']) && $params['metro'] > 0 && $params['rayon'] == 0) // метро
                {
                    $this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
                    $this->db->where_in('metro_buildings.metro_id', explode(',', $params['metro']));
                }

                // Район
                if (!empty($params['rayon']) && $params['rayon'] > 0 && $params['metro'] == 0) // метро
                {
                    $this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
                    $this->db->where_in('rayon_buildings.rayon_id', explode(',', $params['rayon']));
                }

                // Метро и район
                if (!empty($params['rayon']) && $params['rayon'] > 0 && !empty($params['metro']) && $params['metro'] > 0) // метро
                {
                    $this->db->join('rayon_buildings', 'rayon_buildings.building_id = buildings.id', 'inner');
                    $this->db->join('metro_buildings', 'metro_buildings.building_id = buildings.id', 'inner');
                    $this->db->where("(`ci_rayon_buildings`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_buildings`.`metro_id` IN(".$params['metro']."))");
                }

                // Ипотека
                if (!empty($params['ipoteka']) && $params['ipoteka'] < 3)
                    $this->db->where('ipoteka', $params['ipoteka']);

                // С отделкой
                if (!empty($params['otdelka']) && $params['otdelka'] == 1)
                    $this->db->where_in('buildings.otdelka_id', array(1,3,4,5,6,7));

                // ФЗ-214
                if (!empty($params['fz214']))
                    $this->db->where('fz214', $params['fz214']);

                // Кол-во комнат
                if (!empty($params['room']))
                    $this->db->where_in("room_id", explode(',', $params['room']));

                // Цена от
                if (!empty($params['price_from']))
                    $this->db->where('price >=', intval($params['price_from']*1000000));

                // Цена до
                if(!empty($params['price_to']))
                    $this->db->where('price <=', intval($params['price_to']*1000000));

                // Площадь от
                if (!empty($params['square_from']))
                    $this->db->where('square_all >=', floatval($params['square_from']));

                // Площадь до
                if (!empty($params['square_to']))
                    $this->db->where('square_all <=', intval($params['square_to']));

                // Адрес
                if (!empty($params['adress']))
                    $this->db->where("(`adress` LIKE '%".$params['adress']."%' OR `name` LIKE '%".$params['adress']."%')");

                break;
        }

        $this->db->group_by('buildings.id');
    }

}