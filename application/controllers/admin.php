<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		if( !($this->is_sga() || $this->is_admin()) ) {
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
	}
	
	public function manage_users() {
		$this->load->model('Users');		
		$this->load->library('pagination');
		$config = array();
		$config['base_url'] = site_url() . 'admin/manage_users';
		$config['total_rows'] = $this->db->count_all('users');
		$config['per_page'] = 40;		
        $config["uri_segment"] = 3;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);

		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->Users->fetch_users($config["per_page"], $page);
        $data["page_links"] = $this->pagination->create_links();
		
		$data['title'] = 'BU Law SGA | Admin | Manage Users';
		$data['heading'] = 'Manage Users';
		
		$this->load->view('header', $data);
		$this->load->view('manage_users', $data);
		$this->load->view('footer', $data);
	}
	
	public function edit_user( $user_id ) {	
		$this->load->model('Users');
		$this->load->model('Organizations');
		if($this->input->post())
		{
			// if post, then update
			$positions = $this->Users->update_user( $this->input->post() ); // function updates the user info then returns an array of positions
			$this->Organizations->add_roles( $user_id, $positions );
			$this->session->set_flashdata( 'flash', 'User info was successfully updated.' );
		} 
		elseif(!isset($user_id))
		{ 
			redirect('admin/manage_users');
		}
		$data['title'] = 'BU Law SGA | Admin | Edit User';
		$data['heading'] = 'Edit User';
		$data['edit_user'] = $this->Users->get_user($user_id, true);
		
		// these are defined in the my_controller
		$data['orgs'] = $this->orgs_array(); 
		$data['roles'] = $this->roles_array();
		
		$this->load->view('header', $data);
		$this->load->view('edit_user', $data);
		$this->load->view('footer', $data);
	}
	
	public function delete_user( $user_id ) {
		$this->load->model('Users');
		if(!isset($user_id)) { redirect('admin/manage_users'); }
		
		$data['title'] = 'BU Law SGA | Admin | Delete User Confirm';
		$data['heading'] = 'Confirm User Deletion';
		$data['delete_user'] = $this->Users->get_user($user_id);
		
		$this->load->view('header', $data);
		$this->load->view('delete_user', $data);
		$this->load->view('footer', $data);
	}
		public function delete_user_confirm( $user_id ) {
			$this->load->model('Users');
			if(!isset($user_id)) { redirect('admin/manage_users'); }
			if($this->Users->delete_user($user_id)) {
				$this->session->set_flashdata('flash', 'User successfully deleted.');
			} else {
				$this->session->set_flashdata('flash', 'Encountered a problem deleting the user.');
			}
			redirect('admin/manage_users');
		}
	
	public function manage_orgs() {	
		$this->load->model('Organizations');		
		$this->load->library('pagination');
		
		$config = array();
		$config['base_url'] = site_url() . 'admin/manage_orgs';
		$config['total_rows'] = $this->Organizations->orgs_count();
		$config['per_page'] = 40;		
        $config["uri_segment"] = 3;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);

		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["results"] = $this->Organizations->fetch_orgs($config["per_page"], $page);
        $data["page_links"] = $this->pagination->create_links();
		
		$data['title'] = 'BU Law SGA | Admin | Manage Organizations';
		$data['heading'] = 'Manage Organizations';
		
		$this->load->view('header', $data);
		$this->load->view('manage_orgs', $data);
		$this->load->view('footer', $data);
	}
	
	public function edit_org( $org_id ) {
		if(!isset($org_id)) { redirect('admin/manage_orgs'); }
		$this->load->model('Organizations');
		$this->load->model('Liaisons');
		$data['title'] = 'BU Law SGA | Admin | Edit Organization';
		$data['heading'] = 'Edit Organization';
		$data['org'] = $this->Organizations->get_org($org_id);
		
		$liaison_role = $this->Liaisons->role_id();
		$data['liaison_id'] = $this->Organizations->get_liaison_id($org_id, $liaison_role);
		$data['users'] = $this->users_array();
		
		$this->load->view('header', $data);
		$this->load->view('edit_org', $data);
		$this->load->view('footer', $data);
	}
	
	public function manage_outlines() {
		$this->load->model('Outlines');		
		$this->load->library('pagination');
		
		$config = array();
		$config['base_url'] = site_url() . 'admin/manage_outlines';
		$config['total_rows'] = $this->Outlines->outlines_count();
		$config['per_page'] = 40;		
		$config["uri_segment"] = 3;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);

		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["results"] = $this->Outlines->fetch_outlines($config["per_page"], $page);
		$data["pending"] = $this->Outlines->fetch_pending_outlines();
		$data["page_links"] = $this->pagination->create_links();
		
		$data['title'] = 'BU Law SGA | Admin | Manage Outlines';
		$data['heading'] = 'Manage Outlines';
		
		$this->load->view('header', $data);
		$this->load->view('manage_outlines', $data);
		$this->load->view('footer', $data);
	}
	
	public function liaisons() {
		$this->load->model('Liaisons');
		
		$data['title'] = 'BU Law SGA | Admin | Liaisons';
		$data['heading'] = 'SGA Liaisons';
		
		$liaison_role = $this->Liaisons->role_id();
		$data['orgs_liaisons'] = $this->Liaisons->orgs_liaisons();
		
		// these are defined in the my_controller
		$data['orgs'] = $this->orgs_array( false ); 
		$data['users'] = $this->users_array();
		
		$this->load->view('header', $data);
		$this->load->view('liaisons', $data);
		$this->load->view('footer', $data);
	}
		public function liaisons_process() {
			$this->load->model('Liaisons');
			$liaison_role = $this->Liaisons->role_id();
			
			foreach( $this->input->post() as $id => $liaison_id ) {
				// take off liaison_
				$id_arr = explode('_', $id);
				$id = $id_arr[1];
				
				if( $liaison_id != '' && isset($id) ) {
					// only insert if liaison is set
					$this->Liaisons->update_org_liaison( $id, $liaison_id );
				} elseif( $liaison_id == '' ) {
					// if liaison has been made blank, delete
					$arr = array(
						'organization_id' => $id,
						'role_id' => $liaison_role
					);
					$this->db->delete('users_orgs_roles', $arr);
				}
			} //endforeach
			redirect('admin/liaisons');
		} //end function
	
}
?>