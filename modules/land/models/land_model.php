<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Модель модуля | pages | пользовательская часть
 **/


class land_model extends MY_Model
{

    public $typesdelka = 'land_sdelkaland';
    // конструктор
    public function __construct()
    {
        parent::__construct();

        include(MDPATH.'land/moduleinfo.php');
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
        if ($this->session->userdata('sort_price_land')){
            $this->db->order_by('price', $this->session->userdata('sort_price_land'));
        }
        else{
            $this->db->order_by('sort', 'ASC');
            $this->db->order_by('price', "ASC");
            //$this->db->order_by('views', 'DESC'); // сортировка
        }
        // проверяем нужно ли использовать пагинацию
        if($offset < 999)
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

        if($params['rayon'] > 0) // район
        {
            $this->db->where_in('rayon_id', explode(",", $params['rayon']));
        }
        if($params['city'] > 0) // район
        {
            $this->db->where_in('city_id', explode(",", $params['city']));
        }
        if($params['forwhat'] > 0) // район
        {
            $this->db->where_in('forwhat', explode(",", $params['forwhat']));
        }
        if($params['type'] > 0) // район
        {
            $this->db->where_in('type_id', explode(",", $params['type']));
        }
        if(!empty($params['address'])) // адрес
        {
            $this->db->like('address', $params['address']);
        }
        if($params['square_from'] > 0 and ($params['square_to'] > 0)) // площадь участка
        {
            $this->db->where('square >= ', $params['square_from']);
            $this->db->where('square <= ', $params['square_to']);
        }
        elseif($params['square_from'] > 0)
        {
            $this->db->where('square >= ', $params['square_from']);
        }
        elseif($params['square_to'] > 0)
        {
            $this->db->where('square <= ', $params['square_to']);
        }
        if($params['distance_from'] > 0 and ($params['distance_to'] > 0)) // площадь участка
        {
            $this->db->where('tokad >= ', $params['distance_from']);
            $this->db->where('tokad <= ', $params['distance_to']);
        }
        elseif($params['distance_from'] > 0)
        {
            $this->db->where('tokad >= ', $params['distance_from']);
        }
        elseif($params['distance_to'] > 0)
        {
            $this->db->where('tokad <= ', $params['distance_to']);
        }

        if($params['price_from'] > 0 and $params['price_to'] > 0) //
        {
            $this->db->where('price >=', intval($params['price_from']*1000000));
            $this->db->where('price <=', intval($params['price_to']*1000000));
        }
        elseif($params['price_to'] > 0)
        {
            $this->db->where('price <=', intval($params['price_to']*1000000));
        }

    }

    public function getSdelka($id = NULL)
    {
        if ($id == NULL)
            return false;

        $this->db->where('land_id', $id);


        $query = $this->db->get($this->typesdelka);
        return $query->result_array();
    }

    public function findCount($params){
        $this->db->where('banned','0');
        $this->functions($params, 'id');
        return $this->db->count_all_results($this->table);
    }

    public function allMapLand($params=null){
        $this->db->where('banned','0');
        $this->functions($params, '*');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result;
    }

    function getLandAll($ids){
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

    function getMinMaxSquare(){
        $this->db->select('MIN(square) as min, MAX(square) as max');
        $this->db->where('banned','0');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result[0];
    }

}