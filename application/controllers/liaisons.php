<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liaisons extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if(!$this->is_sga_liaison() || !$this->logged_in() ) { redirect( site_url() ); }
    }
    
    public function index() {
        $this->edit_orgs();
    }
    
    public function edit_orgs( $org_id = null )
	{
        $this->load->model('Organizations');
        if( !isset($org_id) )
        {
            // liaisons/edit_orgs/
            $data['my_orgs'] = $this->Organizations->get_orgs_by_liaison( $this->session->userdata('user')->id );
            $data['title'] = 'BU Law SGA | SGA Liaisons | Edit My Organizations';
            $data['heading'] = 'Edit My Organizations';
            $this->load->view('header', $data);
            $this->load->view('edit_my_orgs', $data);
        }
        else
        {
            // liaisons/edit_orgs/org_id
            $this->Organizations->check_is_liaison_for( $org_id, $this->session->userdata('user')->id );
            $data['org'] = $this->Organizations->get_org( $org_id );
            $data['officers'] = $this->Organizations->get_officers( $org_id );
			$data['roles'] = $this->roles_array();
			$data['users'] = $this->users_array();
			
			$data['title'] = 'BU Law SGA | SGA Liaisons | Edit Organization | ' . $data['org']->name;
            $data['heading'] = 'Edit - ' . $data['org']->name;
			
			$this->load->view('header', $data);
            $this->load->view('edit_my_orgs_org', $data);
        }
        $this->load->view('footer', $data);
    }
	
	public function edit_orgs_process()
	{
		$this->load->model('Organizations');
		$this->Organizations->update_org( $this->input->post() );
		$this->session->set_flashdata('flash', 'Organization info was successfully updated.');
		redirect('liaisons/edit_orgs/'.$this->input->post('org_id'));
	}
	
	public function delete_uor( $org_id, $uor_id )
	{
        $this->load->model('Organizations');
		$this->Organizations->delete_officer( $uor_id );
		redirect('liaisons/edit_orgs/'.$org_id);
	}
	
	public function add_officer( $org_id )
	{
		// liaisons/add_officer/org_id
		$this->load->model('Organizations');
		$org_name = $this->Organizations->get_org( $org_id )->name;
		$data['title'] = 'BU Law SGA | SGA Liaisons | Add Officer | ' . $org_name;
		$data['heading'] = 'Add Officer to ' . $org_name;
		$data['roles'] = $this->roles_array();
		$data['users'] = $this->users_usernames_array();
		$data['org_id'] = $org_id;
		
		$this->load->view('header', $data);
		$this->load->view('add_officer', $data);
        $this->load->view('footer', $data);
	}
	
	public function add_officer_process()
	{
		$this->load->model('Organizations');
		$this->Organizations->add_officer( $this->input->post() );
		redirect( 'liaisons/edit_orgs/'.$this->input->post('org_id') );
	}
	
	public function grants()
	{
		$data['title'] = 'BU Law SGA | SGA Liaisons | Grants - Under Construction';
		$data['heading'] = 'Grants - Under Construction';
		
		$this->load->view('header', $data);
		$this->load->view('under_construction', $data);
		$this->load->view('footer', $data);
	}
}

?>