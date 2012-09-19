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
}

?>