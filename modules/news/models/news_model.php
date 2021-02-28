<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class news_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'news/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];
        $this->sortMethod = 'date';
        $this->conf = $this->load->config($this->module.'/'.$this->module, true);
	}


    function pagination_tag($offset = 0, $tag = '')
    {
        if($tag != '')
        {
            $this->db->like('tag', $tag);
        }
        $sort = $this->sortAscDesc;

        if(isset($this->conf['sortList']))
        {
            $sort = $this->conf['sortList'];
        }

        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->order_by("`date` DESC, `id` DESC"); // сортировка
		//$this->db->order_by('date', 'desc'); // сортировка
        //$this->db->order_by('id', 'desc'); // сортировка

        // проверяем нужно ли использовать пагинацию
        if($this->conf['Paging'])
        {
            $per_paging = $this->conf['perPaging']; // количество записей на страницу

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

	function pagination_cat($offset = 0, $tag = '')
    {
        if($tag != '')
        {
            $this->db->like('ncategory', $tag);
        }
        $sort = $this->sortAscDesc;

        if(isset($this->conf['sortList']))
        {
            $sort = $this->conf['sortList'];
        }

        $this->db->where('banned','0'); // вытаскивать только видимые данные
        $this->db->order_by("`date` DESC, `id` DESC"); // сортировка
		//$this->db->order_by('date', 'desc'); // сортировка
        //$this->db->order_by('id', 'desc'); // сортировка

        // проверяем нужно ли использовать пагинацию
        if($this->conf['Paging'])
        {
            $per_paging = $this->conf['perPaging']; // количество записей на страницу

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
}