<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Otd extends MY_Controller {	

    function __construct()
    {
        // конструктор
        parent::__construct();                                                          
    }   

	function index()
    {
        $this->db->where('razdelu', 0);
        $query = $this->db->get('buildings');
        $r = $query->result_array();
        foreach ($r as $k => $v){
            if(!empty($v['metro_id'])){
                $mas = array(
                    'building_id' => $v['id'],
                    'metro_id' => $v['metro_id']
                );
                $this->db->insert('metro_buildings', $mas);
            }
        }
    }            

}