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
        return $this->db->count_all('users');
    }

    public function fetch_users($limit, $start) {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->order_by('lastname');
        $this->db->limit($limit, $start);
		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
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
   
   public function delete_user($user_id) {
		$this->db->delete('users', array('id' => $user_id));
		$this->db->delete('users_orgs_roles', array('user_id' => $user_id));
		return true;
   }
   
   public function get_class($fromdb) {
		return $this->class_arr[$fromdb];
   }
}
?>