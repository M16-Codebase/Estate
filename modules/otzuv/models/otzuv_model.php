<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class otzuv_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();

        include(MDPATH.'otzuv/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
	}

	public function getAll()
	{
		$this->db->where('banned','0');
		$query = $this->db->get($this->table);
        $result = $query->result_array();
		return $result;
	}

    function paginations($offset, $params = '', $per_paging = 16)
    {
        $this->db->where('banned','0');
		//echo $offset.'|';
        if(!empty($params))
        {
			//echo'params';
            $offset = 0;
            $this->functions($params);
        }
		//echo $offset;	
        // сортировка
        $this->db->order_by('date', 'DESC');
        $this->db->order_by('sort', 'ASC');
        // проверяем нужно ли использовать пагинацию
        if($offset < 999) {
             // количество записей на страницу
            if($offset == 0 or $offset == 1) {
                $offset = 0;
            } else {
                $offset = ($offset-1) * $per_paging;
            }
            $this->db->limit($per_paging, $offset);
        }
		
        $query = $this->db->get($this->table);
		$result = $query->result_array();
		//echo '<p>------------</p><pre>';
		//print_r($query);
		//echo '</pre><p>------------</p>';
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
        $like = '';



        if(!empty($params['search'])) {
            $seacrh = explode(' ', $params['search']);

            for ($i = 0; $i < count($seacrh); $i ++) {

                if ($i !== 0) {
                    $like .= ' OR ';
                }
                $like .= "name LIKE '%" . $seacrh[$i] . "%' OR text LIKE '%" . $seacrh[$i] . "%' OR manager_name LIKE '%" . $seacrh[$i] . "%' OR tags LIKE '%" . $seacrh[$i] . "%'";
            }
        }



        if(!empty($params['search'])) // район
        {
            $this->db->where($like);
        }

        if(!empty($params['tag'])) // район
        {
            $this->db->like("tags", urldecode($params['tag']));

        }

    }

    public function getLikes($id){
        $this->db->where('item_id', $id);
        $this->db->where('identity', 'reviews');
        $query = $this->db->get('likes');
        $c = $query->result_array();
        return count($c);
    }

    public function getMostComment(){
        $this->db->select('otzuv.*, COUNT(`ci_likes`.`id`) AS com');
        $this->db->where('otzuv.banned', '0');
        $this->db->join('likes', 'likes.item_id=otzuv.id ', 'left');
        $this->db->group_by('otzuv.id');
        $this->db->order_by('com', 'DESC');
        $this->db->order_by('otzuv.date', 'DESC');
        $this->db->limit(3, 0);
        $query = $this->db->get($this->table);
        $result = $query->result_array();
        return $result;
    }

    /**
     * Вытаскиваем одну определенную запись
    **/
	function getRowByKey($key = '') {
        $this->db->where('banned', '0');

        if(!empty($key)) {
            $this->db->where('otzuv_key', $key);
        } else {
            return false;
        }

        $query = $this->db->get($this->table);
        $result = $query->row_array();
        return $this->lang_load($result, $this->table);
    }


}
