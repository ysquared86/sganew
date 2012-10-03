<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller {
	public function __construct() {
		parent::__construct();
		if( !($this->is_sga() || $this->is_admin()) ) {
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
	}
	
	public function index() {
		$this->manage_users();
	}
	
/* USERS
=========================================================================================*/
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
		$data["pending"] = $this->Users->fetch_pending_users();
        $data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
		
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
		$data['class_arr'] = $this->class_array();
		
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
	
	public function approve_user( $user_id ) {
		$this->load->model('Users');		
		if(!isset($user_id)) { redirect('admin/manage_users'); }
		
		$this->Users->approve_user( $user_id );
		$this->session->set_flashdata('flash', 'User was approved.');
		redirect('admin/manage_users');
	}
	
/* ORGANIZATIONS
=========================================================================================*/
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
        $data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
		
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
		$data['liaison_id'] = $this->Organizations->get_liaison_id($org_id);
		$data['users'] = $this->users_array();
		
		$this->load->view('header', $data);
		$this->load->view('edit_org', $data);
		$this->load->view('footer', $data);
	}
	
/* OUTLINES
=========================================================================================*/
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
		$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
		
		$data['title'] = 'BU Law SGA | Admin | Manage Outlines';
		$data['heading'] = 'Manage Outlines';
		
		$this->load->view('header', $data);
		$this->load->view('manage_outlines', $data);
		$this->load->view('footer', $data);
	}
	
	public function delete_outline( $outline_id ) {
		$this->load->model('Outlines');
		$this->Outlines->delete_outline( $outline_id );
		redirect('admin/manage_outlines');
	}
	
	public function approve_outline( $outline_id ) {
		$this->load->model('Outlines');
		$this->Outlines->approve_outline( $outline_id );
		redirect('admin/manage_outlines');
	}
	
/* LIAISONS
=========================================================================================*/
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
	
/* MME
=========================================================================================*/
	public function manage_mmes( $action = 'view' )
	{
		$this->load->model('Mme_issues');
		if( $action == 'view' )
		{
			// admin/manage_mmes/view
			$data['title'] = 'BU Law SGA | Admin | MME Issues';
			$data['heading'] = 'Manage MME Issues';
			
			$this->load->library('pagination');
		
			$config = array();
			$config['base_url'] = site_url() . 'admin/manage_mmes/view/';
			$config['total_rows'] = $this->Mme_issues->issues_count();
			$config['per_page'] = 40;		
			$config["uri_segment"] = 4;
			$config["num_links"] = round($config["total_rows"] / $config["per_page"]);

			$this->pagination->initialize($config);
			
			$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
			$data["issues"] = $this->Mme_issues->fetch_all_issues($config["per_page"], $page);
			$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
			
			$this->load->view('header', $data);
			$this->load->view('mme_archives_admin', $data);
		}
		elseif( $action == 'create' )
		{
			// admin/manage_mmes/create
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('firstmonday', 'First Monday', 'required|callback_check_mme_range');
			$this->form_validation->set_rules('secondmonday', 'Second Monday', 'required');
			$this->form_validation->set_rules('lastday', 'Last Day', 'required');
			
			if($this->form_validation->run() == false)
			{
				$data['title'] = 'BU Law SGA | Admin | Create MME Issue';
				$data['heading'] = 'Create a new issue of the MME';
			
				$this->load->view('header', $data);
				$this->load->view('create_mme_issue', $data);
			}
			else
			{
				// Validated. Success!
				$new_issue_id = $this->Mme_issues->insert_issue( $this->input->post() );
				redirect('admin/single_issue/'.$new_issue_id);
			}
		}
		elseif( $action == 'overrides' )
		{
			// admin/manage_mmes/overrides
		}
		
		$this->load->view('footer', $data);
	}
	
	public function single_issue( $id )
	{
		// admin/single_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');
		
		$data['issue'] = $this->Mme_issues->get_issue_by_id( $id, true );
		$data['title'] = 'BU Law SGA | Admin | View Single MME Issue';
		$data['heading'] = 'Manage Single MME Issue - Week of ' . date('F d, Y', $data['issue']->firstmonday);
		
		$this->session->set_userdata('redirect_url', current_url());
		
		$this->load->view('header', $data);
		$this->load->view('mme_single_issue_admin', $data);
		$this->load->view('footer', $data);
	}
	
	public function delete_issue( $id )
	{
		// admin/delete_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');
		
		$this->Mme_issues->delete_issue( $id );
		$this->session->set_flashdata('flash', 'Issue '.$id.' was successfully deleted.');
		redirect('admin/manage_mmes/view');
	}
	
	public function publish_issue( $id )
	{
		// admin/publish_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');		
		$this->Mme_issues->publish_issue( $id );
		$this->session->set_flashdata('flash', 'Issue '.$id.' was successfully published.');
		
		$nexturl = ($this->session->userdata('redirect_url')) ? $this->session->userdata('redirect_url') : 'admin/manage_mmes';
		$this->session->unset_userdata('redirect_url');
		redirect( $nexturl );
	}
	
	public function unpublish_issue( $id )
	{
		// admin/publish_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');		
		$this->Mme_issues->unpublish_issue( $id );
		$this->session->set_flashdata('flash', 'Issue '.$id.' has been pulled from publication.');
		
		$nexturl = ($this->session->userdata('redirect_url')) ? $this->session->userdata('redirect_url') : 'admin/manage_mmes';
		$this->session->unset_userdata('redirect_url');
		redirect( $nexturl );
	}
	
	public function delete_submission( $id )
	{
		// admin/delete_submission/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');
		
		$this->Mme_issues->delete_entry( $id );
		$this->session->set_flashdata('flash', 'Entry '.$id.' was successfully deleted.');
		
		$nexturl = ($this->session->userdata('redirect_url')) ? $this->session->userdata('redirect_url') : 'admin/manage_mmes';
		$this->session->unset_userdata('redirect_url');
		redirect( $nexturl );
	}
	
	public function approve_submission( $id )
	{
		// admin/delete_submission/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		$this->load->model('Mme_issues');		
		$this->Mme_issues->approve_entry( $id );
		$this->session->set_flashdata('flash', 'Entry '.$id.' was approved.');
		
		$nexturl = ($this->session->userdata('redirect_url')) ? $this->session->userdata('redirect_url') : 'admin/manage_mmes';
		$this->session->unset_userdata('redirect_url');
		redirect( $nexturl );
	}
	
	public function mme_emailpreview( $id )
	{
		// admin/single_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		
		$this->load->model('Mme_issues');		
		$data['issue'] = $this->Mme_issues->get_issue_by_id( $id );
		$data['title'] = 'BU Law SGA | Admin | MME E-mail Version | Issue '.$id;
		
		$this->load->view('mme_emailpreview', $data);
	}
	
	public function mme_emailsend( $id )
	{
		// admin/single_issue/id
		if( !isset($id) ) { redirect('admin/manage_mmes'); }
		
		$this->load->model('Mme_issues');		
		$data['issue'] = $this->Mme_issues->get_issue_by_id( $id );
		$data['title'] = 'BU Law SGA | Admin | MME E-mail Version | Issue '.$id;
		
		$this->load->library('email', $this->email_config);
		$this->email->set_newline("\r\n");
		
		$this->email->from('ysquared86@gmail.com', 'SGA Law Webmaster');
		$this->email->to('ysquared86@gmail.com');

		$this->email->subject( 'Monday Morning E-mail - Week of ' . date('F d, Y', $data['issue']->firstmonday) );
		$this->email->message( $this->load->view('mme_emailview', $data, true) );

		if (!$this->email->send())
		{
			echo $this->email->print_debugger();
		}
		else
		{
			$this->session->set_flashdata('flash', 'This MME was successfully sent.<br />DO NOT CLICK SEND AGAIN.');
			redirect('admin/mme_emailpreview/'.$id);
		}
	}
	
	public function overrides( $issue_id )
	{
		$this->load->model('Mme_issues');
		$data['issue'] = $this->Mme_issues->get_issue_by_id( $issue_id );
		$data['entries_wo_time'] = $this->Mme_issues->fetch_submissions_wo_time();
		$data['in_this_issue'] = $this->Mme_issues->fetch_overrides( $issue_id );
		$data['title'] = 'BU Law SGA | Admin | Edit Overridden Entries';
		$data['heading'] = 'Edit Overridden Entries: Week of ' . date('F d, Y', $data['issue']->firstmonday);
		
		$this->load->view('header', $data);
		$this->load->view('mme_overrides', $data);
		$this->load->view('footer', $data);
	}
	
	public function overrides_process( $issue_id )
	{
		$this->load->model('Mme_issues');
		$this->Mme_issues->add_overrides( $this->input->post(), $issue_id );
		$this->session->set_flashdata('flash', 'Overrides were saved.');
		redirect('admin/single_issue/'.$issue_id);
	}
}
?>