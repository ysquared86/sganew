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
	
	public function my_account() {
		$this->load->model('Users');
		
		$data['title'] = 'BU Law SGA | My Account';
		$data['heading'] = 'My Account';
		$data['edit_user'] = $this->session->userdata('user');
		$data['class_arr'] = $this->class_array();
		
		$this->load->view('header', $data);
		
		if($this->input->post())
		{
			$this->Users->update_my_account( $this->input->post() );
			$this->session->set_flashdata('flash', 'Your account was successfully updated.');
		}
		$this->load->view('edit_my_account', $data);		
		$this->load->view('footer', $data);
	}
}

?>