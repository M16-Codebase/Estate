<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class consultants_model extends MY_Model
{
	// конструктор
	public function __construct()
	{
		parent::__construct();
        
        include(MDPATH.'consultants/moduleinfo.php');
        $this->module = $moduleinfo['name'];
        $this->table = $moduleinfo['table'];                      
	}

    public function getAllFilter($cat=0){
        $this->db->select('consultants.*');
        $this->db->where('consultants.banned', 0);
        $this->db->join('buildings', 'buildings.consultant_id = consultants.id', 'inner');
        $this->db->where('buildings.banned', 0);
        $this->db->like('buildings.razdelu', $cat);
        $this->db->distinct();
        $this->db->order_by('consultants.name', 'ASC');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

}