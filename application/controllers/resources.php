<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function calendar() {
		$data['title'] = 'BU Law SGA | Resources | Events Calendar';
		$data['heading'] = 'Events Calendar';
		
		$this->load->view('header', $data);
		$this->load->view('calendar', $data);
		$this->load->view('footer', $data);
	}
	
	public function student_orgs() {
		$this->load->model('Organizations');
		$data['title'] = 'BU Law SGA | Resources | Student Organizations';
		$data['heading'] = 'Student Organizations';
		$data['orgs'] = $this->Organizations->fetch_orgs();
		
		$this->load->view('header', $data);
		$this->load->view('student_orgs', $data);
		$this->load->view('footer', $data);
	}
	
	public function view_org( $org_id ) {
		$this->load->model('Organizations');
		if(!isset($org_id)) { redirect('resources/student_orgs'); }
		
		$data['org'] = $this->Organizations->get_org( $org_id );
		$data['title'] = 'BU Law SGA | Resources | Student Organizations | ' . $data['org']->name;
		$data['heading'] = $data['org']->name;
		
		$this->load->view('header', $data);
		$this->load->view('view_org', $data);
		$this->load->view('footer', $data);
	}
	
	public function outlines( $action = 'all' ) {
		if(!$this->logged_in()) {
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
		$this->load->model('Outlines');
		$this->load->library('pagination');
		
		$config = array();
		$config['base_url'] = site_url() . 'resources/outlines/' . $action;
		$config['total_rows'] = $this->db->count_all('outlines');
		$config['per_page'] = 40;		
		$config["uri_segment"] = 4;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		
		if($action == 'all')
		{
			$this->session->unset_userdata('search_post');
			$data["results"] = $this->Outlines->fetch_outlines($config["per_page"], $page);
			$this->pagination->initialize($config);
			$data["page_links"] = $this->pagination->create_links();
			
			$data['title'] = 'BU Law SGA | Resources | Outline Database';
			$data['heading'] = 'Outline Database';
			
			$this->load->view('header', $data);
			$this->load->view('outlines', $data);
			$this->load->view('footer', $data);
		}
		
		elseif($action == 'search')
		{
			if($this->input->post()) {
				$this->session->set_userdata('search_post', $this->input->post());
			} elseif(!($this->session->userdata('search_post'))) {
				// if no post and no session
				redirect('resources/outlines/all');
			}
			$data["results"] = $this->Outlines->search_outlines($this->session->userdata('search_post'), $config["per_page"], $page);
			$config['total_rows'] = count($data["results"]);
			$config["num_links"] = round($config["total_rows"] / $config["per_page"]);
			
			$this->pagination->initialize($config);
			$data["page_links"] = $this->pagination->create_links();

			$data['title'] = 'BU Law SGA | Resources | Outline Database | Search Results';
			$data['heading'] = 'Outline Database - Search Results';

			$this->load->view('header', $data);
			$this->load->view('outlines', $data);
			$this->load->view('footer', $data);
		} 
		
		elseif($action == 'upload')
		{	
			$data['title'] = 'BU Law SGA | Resources | Upload Outlines';
			$data['heading'] = 'Upload Outlines';
			$data['courses'] = $this->courses_array( false );
			$data['profs'] = $this->profs_array( false );
			$data['years'] = $this->years_array();
			
			$this->load->view('header', $data);
		
			$this->load->library('form_validation');
			$this->form_validation->set_rules('course_id', 'Course', 'required');
			$this->form_validation->set_rules('professor_id', 'Instructor', '');
			$this->form_validation->set_rules('semester', 'Semester', 'required');
			$this->form_validation->set_rules('year', 'Year', 'required');
			
			if($this->form_validation->run() == false) 
			{				
				$this->load->view('outlines_upload', $data);
			} 
			else 
			{
				// do the insert
				$insert_result = $this->Outlines->insert_outline( $this->input->post() );
				// then redirect
				if(is_array($insert_result))
				{
					$this->session->set_flashdata('flash', 'Your outline was successfully uploaded. The administrator will review and approve your outline.');
					redirect('resources/outlines');
				}
				else
				{
					var_dump($insert_result);
				}
			}
			
			$this->load->view('footer', $data);
		}		
	} // end function
	
	public function prof_ratings( $action = 'all', $prof_id = null ) {
		$this->check_registered_user();
		
		$this->load->model('Professor_ratings');
		if( $action == 'all' )
		{
			$data['title'] = 'BU Law SGA | Resources | Professor Ratings';
			$data['heading'] = 'Professor Ratings';
			$this->load->view('header', $data);
			$this->load->view('prof_ratings', $data);
		}
		
		elseif( $action == 'view' )
		{
			$data['ratings'] = $this->Professor_ratings->get_ratings_for( $prof_id );
			$data['courses'] = $this->courses_array();
			$data['years'] = $this->years_array();
			
			$data['title'] = 'BU Law SGA | Resources | Professor Ratings';
			$data['heading'] = 'Professor Ratings - ' . $data['ratings']->firstname . ' ' . $data['ratings']->lastname;
			$this->load->view('header', $data);
			$this->load->view('prof_ratings_view', $data);
		}
		
		elseif( $action == 'add' )
		{
			$this->Professor_ratings->check_rating_exists( $this->session->userdata('user')->id ); // WRITE THIS FUNCTION... needs course/professor id
		}
		
		$this->load->view('footer', $data);
	}
}

?>