<?php
class Users extends CI_Model
{
	public $class_arr = array(
		'2013' => '2013',
		'2014' => '2014',
		'2015' => '2015',
		'llm' => 'LLM',
		'faculty' => 'Faculty/Staff',
		'alumni' => 'Alumni'
	);
	
    public function __construct() {
        parent::__construct();
    }

    public function users_count() {
		$this->db->from('users');
		$this->db->where('pending', 'N');
        return $this->db->count_all_results();
    }

    public function fetch_users($limit = null, $start = null) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('pending', 'N');
		$this->db->order_by('lastname');
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}
		
        return $this->db->get()->result();
   }
   
    public function fetch_pending_users() {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('pending', 'Y');
		$this->db->order_by('lastname');
		
        return $this->db->get()->result();
   }
   
   public function get_user($user_id, $positions = false) {
		$result = $this->db->get_where('users', array('id' => $user_id))->result();
		$user = $result[0];
		
		if($positions) {
			// store positions in with the user
			$user->positions = $this->db->get_where('users_orgs_roles', array('user_id' => $user_id))->result();
		}
		return $user;
   }
   
   public function get_username_by_email( $email ) {
		$result = $this->db->get_where('users', array('email' => $email), 1)->result();
		if(!empty($result)) {
			return $result[0]->username;
		}
		return false;
   }
   
   public function update_user($post, $extras = null) {
		$arr = array(
			'firstname' => $post['firstname'],
			'lastname' => $post['lastname'],
			'email_pref' => $post['email_pref'],
			'phone' => $post['phone'],
			'class' => $post['class']
		);
		$this->db->where( 'id', $post['id'] );
		$this->db->update( 'users', $arr );
		
		// update liaison info
		$liaison_arr = array();
		for($i = 1; $i <= 5; $i++) {
			$org_property = 'position'.$i;
			$role_property = 'role'.$i;
			$liaison_arr[] = array( $post[$org_property], $post[$role_property] );
		}
		return $liaison_arr;
   }
   
   public function update_my_account( $post ) {
		$arr = array(
			'firstname' => $post['firstname'],
			'lastname' => $post['lastname'],
			'email_pref' => $post['email_pref'],
			'phone' => $post['phone'],
			'class' => $post['class']
		);
		$this->db->where( 'id', $post['id'] );
		$this->db->update( 'users', $arr );
   }
   
   public function delete_user($user_id) {
		$this->db->delete('users', array('id' => $user_id));
		$this->db->delete('users_orgs_roles', array('user_id' => $user_id));
		return true;
   }
   
   public function get_class($fromdb) {
		return $this->class_arr[$fromdb];
   }
   
   public function get_my_orgs( $user_id, $blank = true ) {
		$arr = array();
		if($blank) { $arr['NULL'] = '--------------'; }
		
		// a user can submit on behalf of an organization if he is the liaison
		$this->db->select('o.*');
		$this->db->from('users_orgs_roles uor');
		$this->db->join('organizations o', 'uor.organization_id = o.id', 'left');
		$this->db->join('roles r', 'uor.role_id = r.id', 'left');
		$this->db->where('r.role', 'SGA Liaison');
		$this->db->where('uor.user_id', $user_id);
		$orgs = $this->db->get()->result();
		
		foreach($orgs as $org) {
			$arr[$org->id] = $org->name;
		}
		return $arr;
   }
   
	public function approve_user($user_id) {
		$arr = array(
			'pending' => 'N'
		);
		$this->db->where( 'id', $user_id );
		$this->db->update( 'users', $arr );
	}
}
?>