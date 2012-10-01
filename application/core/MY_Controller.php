<?php
class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->session->set_userdata('nav_items', $this->nav_items());
		
		date_default_timezone_set('America/New_York');
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
			$this->db->from('users_orgs_roles uor');
			$this->db->join('roles r', 'uor.role_id = r.id');
			$this->db->where('uor.user_id', $this->session->userdata('user')->id);
			$this->db->where('r.role', 'SGA Liaison'); // SGA liaisons are given role ID of 8 in the database
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
		$obj = new stdClass();
		
		$obj->admin = false;
		$obj->sga_liaison = false;
		$obj->sga = false;
		$obj->logged_in = false;
		
		if($this->is_admin()) { $obj->admin = true; }
		if($this->is_sga_liaison()) { $obj->sga_liaison = true; }
		if($this->is_sga()) { $obj->sga = true; }
		if($this->logged_in()) { $obj->logged_in = true; }
		
		$this->load->model('Mme_issues');
		$obj->latest_mme_date = $this->Mme_issues->latest_mme_date();
		
		$obj->active_menu = $this->uri->segment(1);
		
		return $obj;
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
	
	public function users_usernames_array( $blank = true ) {
		// users for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('lastname');
		$users = $this->db->get('users')->result();
		foreach($users as $user) {
			$arr[$user->id] = $user->lastname . ', ' . $user->firstname . ' (' . $user->username . ')';
		}
		return $arr;
	}
	
	public function profs_array( $blank = true ) {
		// professors for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('lastname');
		$profs = $this->db->get('professors')->result();
		foreach($profs as $prof) {
			$arr[$prof->id] = $prof->lastname . ', ' . $prof->firstname;
		}
		return $arr;
	}
	
	public function courses_array( $blank = true ) {
		// courses for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('course_title');
		$courses = $this->db->get('courses')->result();
		foreach($courses as $course) {
			$arr[$course->id] = $course->course_title . ' - ' . $course->course_number;
		}
		return $arr;
	}
	
	public function years_array( $blank = true ) {
		// years for a dropdown
		$arr = array();
		if( $blank ) { $arr = array( '' => '----' ); }
		for($i = 2012; $i > 1999; $i--) {
			$arr[$i] = $i;
		}
		return $arr;
	}
	
	public function class_array() {
		return array(
			'2013' => '2013',
			'2014' => '2014',
			'2015' => '2015',
			'llm' => 'LLM',
			'faculty' => 'Faculty/Staff',
			'alumni' => 'Alumni'
		);
	}
	
	public function mkt_cats_array( $blank = true ) {
		// organizations for a dropdown
		if( $blank ) { $arr = array( '' => '-------------' ); }
		$this->db->order_by('id');
		$cats = $this->db->get('market_categories')->result();
		foreach($cats as $cat) {
			$arr[$cat->id] = $cat->category;
		}
		return $arr;
	}
	
	/* ACCESS CONTROL */
	public function check_registered_user() {
		if(!$this->logged_in()) {
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
	}
}
?>