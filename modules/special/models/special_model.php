<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class special_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'special/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getSpecials($off = 0){
    $this->db->where('banned','0');
    $this->db->limit(8, $off);
    $this->db->order_by('sort','ASC');
    $query = $this->db->get($this->table);
    return $query->result_array();
    }

    public function getNewSpecials($off = 0){
        $this->db->where('banned','0');
        $this->db->limit(4, 0);
        $this->db->like('buildings.razdelu', '0');
        $this->db->order_by('date_add desc');
        $query = $this->db->get('buildings');
        echo '<div id="sasai" style="display: none;">'.$this->db->last_query().'</div>';
        return $query->result_array();
    }

    public function getSpecialsCount(){
        $this->db->select('id');
        $this->db->where('banned','0');
        return $this->db->count_all_results($this->table);
    }
	
	public function getSpecialsHide($id = false){
	//$id = false;
	if ( $id ) {
		$this->db->select('id,price,price_arenda,map,rayon_id,dop_rayon,name');
		$this->db->where('id',$id);
		$q = $this->db->get('ci_buildings');
		$q = $q->result_array();
	}

        $this->db->select('*');
        $this->db->where('banned','0');
	$p = 0.20;
	if ( $id and $q ) {
        	if ( $q[0]['price'] > 0 ) {
        	        $price = $q[0]['price'];
        	        $this->db->where('price >', $price*(1-$p));
        	        $this->db->where('price <', $price*(1+$p));
        	} elseif ( $q[0]['price_arenda'] > 0 ) {
        	        $price = $q[0]['price_arenda']*48;
        	        $this->db->where('price >', $price*(1-$p));
        		$this->db->where('price <', $price*(1+$p));
        	}
	}
		$this->db->where('name !=',$q[0]['name']);
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
	
	

    
	

}
