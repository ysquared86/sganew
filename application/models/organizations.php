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
   
   public function get_liaison_id( $org_id, $liaison_role = 8 ) {
		$result = $this->db->get_where('users_orgs_roles', array('organization_id' => $org_id, 'role_id' => $liaison_role), 1)->result();
		if(!empty($result)) { return $result[0]->user_id; }
		else { return ''; }
   }
   
   public function add_roles( $user_id, $positions_arr ) {
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
}
?>