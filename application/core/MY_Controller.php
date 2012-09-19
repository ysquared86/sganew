<?php
class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->db->select('firstmonday');
		$this->db->from('mme_issues');
		$this->db->where('published', 'Y');
		$this->db->order_by('firstmonday', 'desc');
		$this->db->limit(1);
		$last_mme = $this->db->get()->result();
		
		$this->session->set_userdata('latest_mme_date', date('Y/m/d', $last_mme[0]->firstmonday));
		$this->session->set_userdata('nav_items', $this->nav_items());
	}
	
	public function logged_in() {
		return $this->session->userdata('user');
	}
	
	public function is_sga() {
		if($this->logged_in()) {
			$this->db->from('users_orgs_roles');
			$this->db->where('user_id', $this->session->userdata('user')->id);
			$this->db->where('organization_id', 1);
			return ($this->db->count_all_results() > 0);
		} else {
			return false;
		}
	}
	
	public function is_sga_liaison() {
		if($this->logged_in()) {
			$this->db->from('users_orgs_roles');
			$this->db->where('user_id', $this->session->userdata('user')->id);
			$this->db->where('role_id', 8); // SGA liaisons are given role ID of 8 in the database
			return ($this->db->count_all_results() > 0);
		} else {
			return false;
		}
	}
	
	public function is_admin() {
		if($this->logged_in()) {
			return ($this->session->userdata('user')->id == 1);
		} else {
			return false;
		}
	}
	
	public function nav_items() {
		// function to hide certain nav items based on user roles
		$arr = array();
		if($this->is_admin()) { array_push($arr, 'admin'); }
		if($this->is_sga_liaison()) { array_push($arr, 'sga_liaison'); }
		if($this->is_sga()) { array_push($arr, 'sga'); }
		return $arr;
	}
	
	public function orgs_array( $blank = true ) {
		// organizations for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('name');
		$orgs = $this->db->get('organizations')->result();
		foreach($orgs as $org) {
			$arr[$org->id] = $org->name;
		}
		return $arr;
	}
	
	public function roles_array( $blank = true )  {
		// roles for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$roles = $this->db->get('roles')->result();
		foreach($roles as $role) {
			$arr[$role->id] = $role->role;
		}
		return $arr;
	}
	
	public function users_array( $blank = true ) {
		// users for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('lastname');
		$users = $this->db->get('users')->result();
		foreach($users as $user) {
			$arr[$user->id] = $user->lastname . ', ' . $user->firstname;
		}
		return $arr;
	}
}
?>