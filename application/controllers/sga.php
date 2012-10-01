<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sga extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->about();
	}
	
	public function about() {
		$this->load->model('Organizations');
		$data['title'] = 'BU Law SGA | Student Government | About Us';
		$data['heading'] = 'About Us';
		$data['officers'] = $this->Organizations->get_officers_display(1);
		
		$this->load->view('header', $data);
		$this->load->view('about', $data);
		$this->load->view('footer', $data);
	}
	
	public function office_hours() {
		$data['title'] = 'BU Law SGA | Student Government | Office Hours';
		$data['heading'] = 'Office Hours';
		
		$this->load->view('header', $data);
		$this->load->view('office_hours', $data);
		$this->load->view('footer', $data);
	}
	
	public function documents() {
		$data['title'] = 'BU Law SGA | Student Government | Documents';
		$data['heading'] = 'SGA Documents';
		
		$this->load->view('header', $data);
		$this->load->view('documents', $data);
		$this->load->view('footer', $data);
	}
	
	public function merch() {
		$data['title'] = 'BU Law SGA | Student Government | BU Law Merchandise';
		$data['heading'] = 'BU Law Merchandise';
		
		$this->load->view('header', $data);
		$this->load->view('merch', $data);
		$this->load->view('footer', $data);
	}
}
?>