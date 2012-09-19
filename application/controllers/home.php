<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		$data['title'] = 'BU Law Student Government Association Website';
		$data['heading'] = 'Welcome to BU Law SGA!';
		$this->load->view('header', $data);
		$this->load->view('home', $data);
		$this->load->view('footer', $data);
	}
	
	public function access_denied()
	{
		$data['title'] = 'BU Law SGA | Access Denied';
		$data['heading'] = 'Access Denied';
		$this->load->view('header', $data);
		$this->load->view('access_denied', $data);
		$this->load->view('footer', $data);
	}
}

?>