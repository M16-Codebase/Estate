<?php
class User_Profile extends CI_Model 
{
	function User_Profile()
	{
		parent::__construct();
		
		$this->_table = 'auth_user_profile';
	}
	
	function get_all()
	{
		$this->db->order_by('id', 'asc');
		return $this->db->get($this->_table);
	}
    
	function create_profile($user_id)
	{
		$this->db->set('user_id', $user_id);
		return $this->db->insert($this->_table);
	}

	function get_profile_field($user_id, $fields)
	{
		$this->db->select($fields);
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}

	function get_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->get($this->_table);
	}

	function set_profile($user_id, $data)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->update($this->_table, $data);
	}

	function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		return $this->db->delete($this->_table);
	}
    
    public function get_data($id)
    {
        $this->db->where('user_id',$id);
        $query = $this->db->get($this->_table);               
        return $query->row_object();                
    }
}

?>