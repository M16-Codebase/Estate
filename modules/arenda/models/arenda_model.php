<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class arenda_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'arenda/moduleinfo.php');                
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
        if ($this->session->userdata('sort_price_arenda')){
            $this->db->order_by('price', $this->session->userdata('sort_price_arenda'));
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

        if(!empty($params['metro']) && empty($params['rayon'])) // метро
        {
            $this->db->join('metro_arenda', 'metro_arenda.arenda_id = arenda.id', 'left');
            $this->db->where_in('metro_arenda.metro_id', explode(",", $params['metro']));
        }
        if(!empty($params['rayon']) && empty($params['metro'])) // метро
        {
            $this->db->join('rayon_arenda', 'rayon_arenda.arenda_id = arenda.id', 'left');
            $this->db->where_in('rayon_arenda.rayon_id', explode(",", $params['rayon']));
        }
        if(!empty($params['rayon']) && !empty($params['metro'])) // метро
        {
            $this->db->join('rayon_arenda', 'rayon_arenda.arenda_id = arenda.id', 'left');
            $this->db->join('metro_arenda', 'metro_arenda.arenda_id = arenda.id', 'left');
            $this->db->where("(`ci_rayon_arenda`.`rayon_id` IN (".$params['rayon'].") OR `ci_metro_arenda`.`metro_id` IN(".$params['metro']."))");
        }


        if(!empty($params['price_from'])) //
        {
            $this->db->where('price >=', floatval($params['price_from'])*1000);
        }
        if(!empty($params['price_to']))
        {
            $this->db->where('price <=', floatval($params['price_to'])*1000);
        }

        if(!empty($params['square_from'])) // площадь участка
        {
            $this->db->where('square >= ', $params['square_from']);
        }
        if(!empty($params['square_to']))
        {
            $this->db->where('square <= ', $params['square_to']);
        }

        if(!empty($params['srok']))
        {
            $this->db->where_in('srok_id', explode(",", $params['srok']));

        }

        if(!empty($params['csrok']))
        {
            $this->db->where_in('count_srok', explode(",", $params['csrok']));

        }

        if(!empty($params['rooms']))
        {
            $this->db->where_in('rooms', explode(",", $params['rooms']));

        }
        if(!empty($params['room']))
        {
            $this->db->where_in('object', explode(",", $params['room']));

        }

        $this->db->distinct();

    }

    public function findCount($params){
        $this->db->where('banned','0');
        $this->functions($params, 'id');
        return $this->db->count_all_results($this->table);
    }

    public function allMapArenda($params=null){
        $this->db->where('banned','0');
        $this->functions($params, '*');
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result;
    }

    function getArendaAll($ids){
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