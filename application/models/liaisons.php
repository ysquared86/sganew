<?php
class Liaisons extends CI_Model {
	public function __construct() {
        parent::__construct();
    }
	
	public function role_id() {
		$result = $this->db->get_where('roles', array('role' => 'SGA Liaison'))->result();
		return $result[0]->id;
	}
	
	public function orgs_liaisons() {
		$arr = array();
		$liaison_role = $this->role_id();
		$this->db->select('organizations.id, users_orgs_roles.user_id');
		$this->db->from('organizations');
		$this->db->join('users_orgs_roles', 'organizations.id = users_orgs_roles.organization_id', 'left');
		$this->db->where('users_orgs_roles.role_id', $liaison_role);
		$this->db->order_by('organizations.id');
		$result = $this->db->get()->result();
		
		foreach($result as $row) {
			$arr[$row->id] = $row->user_id;
		}
		return $arr;
	}
	
	public function update_org_liaison( $org_id, $liaison_id ) {
		$liaison_role = $this->role_id();
		$this->db->from('users_orgs_roles');
		$this->db->where('organization_id', $org_id);
		$this->db->where('role_id', $liaison_role);

		if($this->db->count_all_results() == 0) {
			// row doesn't exist yet - insert
			$arr = array(
				'user_id' => $liaison_id,
				'organization_id' => $org_id,
				'role_id' => $liaison_role
			);
			$this->db->insert('users_orgs_roles', $arr);
		} else {
			$arr = array(
				'user_id' => $liaison_id
			);
			$this->db->where('organization_id', $org_id);
			$this->db->where('role_id', $liaison_role);
			$this->db->update('users_orgs_roles', $arr);
		}
	}
}
?>