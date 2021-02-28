<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Модель модуля | pages | пользовательская часть
 **/


class abroad_model extends MY_Model
{
    // конструктор
    public function __construct()
    {
        parent::__construct();

        include(MDPATH.'abroad/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
    }

    function paginations($offset, $params = '')
    {
        if(!empty($params))
        {
            $offset = $params['page'];
            $this->functions($params);
        }
        $this->db->where('banned','0');
        // сортировка
        if ($this->session->userdata('sort_price_abroad')){
            $this->db->order_by('price', $this->session->userdata('sort_price_abroad'));
        }
        else{
            $this->db->order_by('sort', 'ASC');
            $this->db->order_by('price', "ASC");
            //$this->db->order_by('views', 'DESC'); // сортировка
        }
        // проверяем нужно ли использовать пагинацию
        if($offset < 999 AND $offset != 'all')
        {
            $per_paging = 16; // количество записей на страницу
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
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $this->lang_load($result, $this->table);
    }

    function all_counts($banned = false, $where = '', $like = '', $params = '')
    {
        if(!empty($params))
        {
            $this->functions($params);
        }
        if(!empty($search))
        {
            $this->db->like('name', $search);
            $this->db->or_like('adress', $search);
        }
        if(!$banned)
        {
            $this->db->where('banned','0');
        }

        if(!empty($where))
        {
            if(is_array($where))
            {
                foreach($where as $k=>$v)
                {
                    $this->db->where($k, $v);
                }
            }
            else
            {
                $this->db->where($where);
            }
        }
        if(!empty($like))
        {
            $this->db->like($like);
        }
        $query = $this->db->get($this->table)->result_array();

        $querye = '';
        if(!empty($query))
        {
            foreach($query as $k)
            {
                $querye[$k['link']] = '';
            }
        }
        return count($querye);
    }

    function functions($params, $select = '*')
    {
        $this->db->select($select);

        if(!empty($params['country'])) // район
        {
            if (!empty($params['city'])){
                $this->db->where("(country_id IN (".$params['country'].") OR city_id IN (".$params['city']."))");
            }
            else {
                $this->db->where_in('country_id', explode(",", $params['country']));
            }
        }

        if(!empty($params['city']) && empty($params['country']))
        {
            $this->db->where_in('city_id', explode(",", $params['city']));

        }
        if(!empty($params['rooms']))
        {
            $this->db->where_in('rooms_id', explode(",", $params['rooms']));

        }
        if(!empty($params['address'])) // адрес
        {
            $this->db->like('address', $params['address']);
        }
        if(!empty($params['estate']))
        {
            $this->db->where_in('estate_type', explode(",", $params['estate']));

        }
        if(!empty($params['sdelka']))
        {
            $this->db->where_in('sdelka_type', explode(",", $params['sdelka']));

        }
        if(!empty($params['square_from'])) // площадь участка
        {
            $this->db->where('square_all >= ', $params['square_from']);
        }
        if(!empty($params['square_to']))
        {
            $this->db->where('square_all <= ', $params['square_to']);
        }
        if(!empty($params['land_from'])) // площадь участка
        {
            $this->db->where('square_land >= ', $params['land_from']);
        }
        if(!empty($params['land_to']))
        {
            $this->db->where('square_land <= ', $params['land_to']);
        }
        if(!empty($params['price_from'])) //
        {
            $this->db->where('price >=', intval($params['price_from']*1000));
        }
        if(!empty($params['price_to']))
        {
            $this->db->where('price <=', intval($params['price_to']*1000));
        }

    }

    public function findCount($params){
        $this->db->where('banned','0');
        $this->functions($params, 'id');
        return $this->db->count_all_results($this->table);
    }

    public function allMapAbroad($params=null){
        $this->db->where('banned','0');
        $this->functions($params, '*');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result;
    }

    function getAbroadAll($ids){
        $this->db->select('*');
        $this->db->where_in('id', $ids);
        $this->db->where('banned','0');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result;
    }

    function getMinMax(){
        $this->db->select('MIN(price) as min, MAX(price) as max');
        $this->db->where('banned','0');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result[0];
    }

    function getMinMaxS(){
        $this->db->select('MIN(square_all) as min, MAX(square_all) as max');
        $this->db->where('banned','0');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result[0];
    }

    function getMinMaxSS(){
        $this->db->select('MIN(square_land) as min, MAX(square_land) as max');
        $this->db->where('banned','0');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result[0];
    }

    function countryFilter($category, $metro, $page = 0){
        $count = 0;
        switch ($category){
            case 'abroad':

                $this->db->select('abroad.id');
                $this->db->where('abroad.banned', 0);
                $this->db->join('abroad_country', 'abroad_country.id = abroad.country_id', 'left');
                $this->db->where('abroad_country.id', $metro);
                $qu = $this->db->get($this->table);
                $cnt = $qu->result_array();
                $count = count($cnt);
                $this->db->select('abroad.*');
                $this->db->where('abroad.banned', 0);
                $this->db->join('abroad_country', 'abroad_country.id = abroad.country_id', 'left');
                $this->db->where('abroad_country.id', $metro);
                break;
        }
        $this->db->distinct('abroad.id');
        $offset = $page;
        if($offset !== 'all')
        {
            $per_paging = 16; // количество записей на страницу
            $offset = ($offset < 2) ? 0 : ($offset-1) * $per_paging;
            $this->db->limit($per_paging, $offset);
        }
        $query = $this->db->get($this->table);
        $result['dataRows'] = $query->result_array();
        $result['countRows'] = $count;

        return $result;
    }

    function estateFilter($category, $metro, $page = 0){
        $count = 0;
        switch ($category){
            case 'abroad':

                $this->db->select('abroad.id');
                $this->db->where('abroad.banned', 0);
                $this->db->join('abroad_estate', 'abroad_estate.id = abroad.estate_type', 'left');
                $this->db->where('abroad_estate.id', $metro);
                $qu = $this->db->get($this->table);
                $cnt = $qu->result_array();
                $count = count($cnt);
                $this->db->select('abroad.*');
                $this->db->where('abroad.banned', 0);
                $this->db->join('abroad_estate', 'abroad_estate.id = abroad.estate_type', 'left');
                $this->db->where('abroad_estate.id', $metro);
                break;
        }
        $this->db->distinct('abroad.id');
        $offset = $page;
        if($offset !== 'all')
        {
            $per_paging = 16; // количество записей на страницу
            $offset = ($offset < 2) ? 0 : ($offset-1) * $per_paging;
            $this->db->limit($per_paging, $offset);
        }
        $query = $this->db->get($this->table);
        $result['dataRows'] = $query->result_array();
        $result['countRows'] = $count;

        return $result;
    }

}