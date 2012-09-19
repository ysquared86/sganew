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
		
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		if($action == 'all') {
			$data["results"] = $this->Outlines->fetch_outlines($config["per_page"], $page);
		} elseif($action == 'search') {
			if($this->input->post()) {
				$this->session->set_userdata('search_post', $this->input->post());
			} elseif(!($this->session->userdata('search_post'))) {
				// if no post and no session
				redirect('resources/outlines/all');
			}
			$data["results"] = $this->Outlines->search_outlines($this->session->userdata('search_post'), $config["per_page"], $page);
		}
		$data["page_links"] = $this->pagination->create_links();
		
		$data['title'] = 'BU Law SGA | Resources | Outline Database';
		$data['heading'] = 'Outline Database';
		
		$this->load->view('header', $data);
		$this->load->view('outlines', $data);
		$this->load->view('footer', $data);
	}
}

?>