<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class votes_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'votes/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAll($ip=false){
        $this->db->select('*');
        $this->db->where('banned', '0');
        $query = $this->db->get($this->table);
        $vo = $query->result_array();
        $votes = array();
        foreach ($vo as $k=>$value){
            $this->db->select('*');
            $this->db->where('banned', '0');
            $this->db->where('vote_id', $value['id']);
            $quer = $this->db->get('votes_answers');
            $votes[$k] = $value;
            $ans = $quer->result_array();
            $votes[$k]['answers'] = $ans;
            $votes[$k]['count'] = 0;
            $votes[$k]['abs'] = 0;
            if ($ip){
                $this->db->where('vote_id', $value['id']);
                $this->db->where('ip', $ip);
                $que = $this->db->get('votes_count');
                $c = $que->result_array();
                if (count($c) > 0){
                    $votes[$k]['abs'] = 1;
                    $cnt = 0;
                    foreach ($ans as $ka => $va){
                        $cnt += $va['count'];
                    }
                    $votes[$k]['count'] = $cnt;
                }
            }
        }
        return $votes;
    }

}