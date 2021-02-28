<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Модель модуля | pages | пользовательская часть
**/


class interest_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'interest/moduleinfo.php');                
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getInterests($off = 0){
        $this->db->where('banned','0');
        $this->db->limit(9, $off);
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);

        //echo '<!--';print_r($this->db);echo '-->';
        return $query->result_array();
    }

    public function getInterestCount(){
        $this->db->select('id');
        $this->db->where('banned','0');
        return $this->db->count_all_results($this->table);
    }

    public function getInterestHide($id = false){
	//$id = false;
	if ( $id ) {
		$this->db->select('id,price,price_arenda,map,rayon_id,dop_rayon');
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
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }
	
	public function getInterestHidePg($id = false){
        if ( $id ) {
            $this->db->select('id,name,price,rayon_id,dop_rayon,metro_id');
            $this->db->where('id',$id);
            $q = $this->db->get('ci_buildings');
            $q = $q->result_array();
        }
		
        $this->db->select('*');
        $this->db->where('banned','0');
		$price = $q[0]['price'];
		
		if($price<3000000){
			$this->db->where('price <', 3000000);
		}elseif($price<5000000){
			$this->db->where('price <', 5000000);
			$this->db->where('price >', 3000000);
		}elseif($price<15000000){
			$this->db->where('price <', 15000000);
			$this->db->where('price >', 5000000);
		}else{
			$this->db->where('price >', 15000000);
		}
		
		$this->db->where('metro',$q[0]['metro_id']);
		$this->db->where('name !=',$q[0]['name']);
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
		
		if(count($query->result_array())>1){
			return $query->result_array();
		}else{
		$this->db->select('*');
        $this->db->where('banned','0');
		$price = $q[0]['price'];
		
		if($price<3000000){
			$this->db->where('price <', 3000000);
		}elseif($price<5000000){
			$this->db->where('price <', 5000000);
			$this->db->where('price >', 3000000);
		}elseif($price<15000000){
			$this->db->where('price <', 15000000);
			$this->db->where('price >', 5000000);
		}else{
			$this->db->where('price >', 15000000);
		}
		
		$this->db->where('name !=',$q[0]['name']);
        $this->db->order_by('sort','ASC');
        $query = $this->db->get($this->table);
		return $query->result_array();
		}
        
    }


}
