<?php
class Organizations extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }

    public function orgs_count() {
        return $this->db->count_all('organizations');
    }

    public function fetch_orgs($limit = null, $start = null) {
		$this->db->select('*');
		$this->db->from('organizations');
		$this->db->where('status', 'active');
		$this->db->order_by('name');
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}
		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
   }
   
   public function get_org( $org_id ) {
		$result = $this->db->get_where('organizations', array('id' => $org_id), 1)->result();
		return $result[0];
   }
   
   public function update_org( $post )
   {
		$org_info = array(
			'description' => $post['description'],
			'website' => $post['website'],
			'email' => $post['email'],
			'phone' => $post['phone']
		);
		$this->db->update('organizations', $org_info, array('id' => $post['org_id']));
		
		// update officers
		for($i = 1; $i <= 15; $i++)
		{
			if(isset($post['role'.$i]))
			{
				$uor = array(
					'user_id' => $post['user'.$i],
					'organization_id' => $post['org_id'],
					'role_id' => $post['role'.$i]					
				);
				$this->db->from('users_orgs_roles');
				$this->db->where($uor);
				if($this->db->count_all_results() == 0)
				{
					$this->db->insert('users_orgs_roles', $uor);
				}
			}
		}
   }
   
   public function get_liaison_id( $org_id )
   {
		$this->db->select('uor.user_id');
		$this->db->from('users_orgs_roles uor');
		$this->db->join('roles r', 'uor.role_id = r.id', 'left');
		$this->db->where('uor.organization_id', $org_id);
		$this->db->where('r.role', 'SGA Liaison');
		$result = $this->db->get()->result();
		if(!empty($result)) { return $result[0]->user_id; }
		else { return ''; }
   }
   
   public function check_is_liaison_for( $org_id, $user_id )
   {
		$liaison_id = $this->get_liaison_id( $org_id );
		if(!($liaison_id == $user_id))
		{
			redirect( 'home/access_denied' );
		}
   }
   
   public function get_orgs_by_liaison( $liaison_id )
   {
		$this->db->select('o.*');
		$this->db->from('organizations o');
		$this->db->join('users_orgs_roles uor', 'o.id = uor.organization_id', 'left');
		$this->db->where('uor.user_id', $liaison_id);
		return $this->db->get()->result();
   }
   
   public function get_officers( $org_id )
   {
		$this->db->select('uor.*');
		$this->db->from('users_orgs_roles uor');
		$this->db->join('users u', 'uor.user_id = u.id', 'left');
		$this->db->join('roles r', 'uor.role_id = r.id', 'left');
		$this->db->where('uor.organization_id', $org_id);
		$this->db->where('r.role != ', 'SGA Liaison');
		$this->db->order_by('uor.role_id');
		return $this->db->get()->result();
   }
   
   public function get_officers_display( $org_id )
   {
		$this->db->select('u.firstname, u.lastname, u.email, r.role');
		$this->db->from('users_orgs_roles uor');
		$this->db->join('users u', 'uor.user_id = u.id', 'left');
		$this->db->join('roles r', 'uor.role_id = r.id', 'left');
		$this->db->where('uor.organization_id', $org_id);
		$this->db->where('r.role != ', 'SGA Liaison');
		$this->db->order_by('uor.role_id');
		return $this->db->get()->result();
   }
   
   
   public function add_roles( $user_id, $positions_arr )
   {
		$this->db->delete('users_orgs_roles', array('user_id' => $user_id));
		foreach( $positions_arr as $position ) {
			if($position[0] != '' && $position[1] != '') {
				$arr = array('user_id' => $user_id, 'organization_id' => $position[0], 'role_id' => $position[1]);
				$this->db->from('users_orgs_roles');
				$this->db->where( $arr );
				if($this->db->count_all_results() == 0) {
					$arr['created'] = time();
					$this->db->insert('users_orgs_roles', $arr);
				}
			}
		}
   }
   
   public function add_officer( $post )
   {
		if( $post['user_id'] != '' && $post['role_id'] != '' )
		{
			$uor = array(
				'user_id' => $post['user_id'],
				'organization_id' => $post['org_id'],
				'role_id' => $post['role_id']
			);
			$this->db->insert('users_orgs_roles', $uor);
		}
   }
   
   public function delete_officer( $uor_id )
   {
		$this->db->delete('users_orgs_roles', array('id' => $uor_id));
   }
}
?>