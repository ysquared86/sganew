<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		if($this->logged_in()) { redirect(site_url()); }
		$data['title'] = 'BU Law SGA | Login';
		$data['heading'] = 'Login to the SGA Website';
		
		$this->load->view('header', $data);
		$this->load->view('login', $data);
		$this->load->view('footer_push', $data);
	}
	
	public function submit() {
		// TODO: Validate form
		$check = array(
			'username' => $this->input->post('username'),
			'password' => sha1($this->input->post('password'))
		);
		$this->db->from('users');
		$this->db->where($check);
		
		if($this->db->count_all_results() == 1) {
			$this->db->select('*');
			$this->db->from('users');
			$this->db->where($check);
			$user = $this->db->get()->result();
			$user = $user[0];
			// User checks out. Log them in.
			$this->session->set_userdata('user', $user);
			
			// update last login
			$this->db->where( array('id' => $user->id) );
			$this->db->update( 'users', array('lastlogin' => time()) );
			
			// back to where you were
			redirect($this->input->post('url'));
		} else {
			$this->session->set_userdata('after_login', $this->input->post('url'));
			redirect( 'login' );
		}
	}
	
	public function logout() {
		$this->session->unset_userdata('user');
		redirect( site_url() );
	}
	
	public function signup() {
		if($this->logged_in()) {
			redirect(site_url());
		}
		$this->load->model('Users');
		$this->load->library('form_validation');
		$this->load->helper('string');
		
		// Validate
		$this->form_validation->set_rules('firstname', 'First Name', 'required|max_length[100]');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required|max_length[100]');
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric|min_length[5]|max_length[30]|callback_check_username');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[100]|callback_check_passwords');
		$this->form_validation->set_rules('password1', 'Retyped Password', 'required');
		$this->form_validation->set_rules('email', 'E-mail Address', 'required|valid_email|callback_check_email|callback_check_bu');
		$this->form_validation->set_rules('email_pref', 'Preferred E-mail Address', 'valid_email');
		$this->form_validation->set_rules('phone', 'Phone Number', 'alpha_dash');
		$this->form_validation->set_rules('class', 'Class', 'required');
		
		$data['title'] = 'BU Law SGA | Sign Up for an Account';
		$data['heading'] = 'Sign up for an SGA Website login';
		$data['class_options'] = $this->Users->class_arr;
		
		$this->load->view('header', $data);
		
		if($this->form_validation->run() == false) {
			$this->load->view('signup', $data);
		} else {
			// insert user
			// TODO: Email
			$arr = array(
				'username' => $this->input->post('username'),
				'password' => sha1($this->input->post('password')),
				'firstname' => $this->input->post('firstname'),
				'lastname' => $this->input->post('lastname'),
				'email' => $this->input->post('email'),
				'email_pref' => $this->input->post('email_pref'),
				'phone' => $this->input->post('phone'),
				'class' => $this->input->post('class'),
				'pending' => 'Y',
				'token' => random_string('sha1', 20),
				'created' => time()
			);
			$this->db->insert('users', $arr);
			
			// load success
			$this->load->view('signup_success', $data);
		}
		$this->load->view('footer', $data);		
	}
		public function check_username($str) {
			// Check for repeat usernames
			$this->db->from('users');
			$this->db->where('username', $str);
			if( $this->db->count_all_results() <= 0 ) {
				return true;
			} else {
				$this->form_validation->set_message('check_username', 'The specified Username is already in use.');
				return false;
			}
		}
		public function check_passwords($str) {
			// Check for matching passwords
			if( $str == $this->input->post('password1') ) {
				return true;
			} else {
				$this->form_validation->set_message('check_passwords', 'The two Passwords do not match.');
				return false;
			}
		}
		public function check_email($str) {
			// Check for repeat email
			$this->db->from('users');
			$this->db->where('email', $str);
			if( $this->db->count_all_results() <= 0 ) {
				return true;
			} else {
				$this->form_validation->set_message('check_email', 'The specified e-mail is already in use on the SGA Website.');
				return false;
			}
		}
		
		public function check_bu($str) {
			// Make sure one of the e-mail
			$email1 = explode('@', $str);
			$email2 = explode('@', $this->input->post('email_pref'));
			if( ($email1[1] == 'bu.edu') || ($email2[1] == 'bu.edu') ) {
				return true;
			} else {
				$this->form_validation->set_message('check_bu', 'One of your e-mail addresses must be a BU e-mail.');
				return false;
			}
		}
		
		public function forget_pw( $action = null ) {
			$data['title'] = 'BU Law SGA | Reset Your Password';
			$data['heading'] = 'Reset Your Password';
			
			$this->load->view('header', $data);
			$this->load->view('forget_password', $data);
			$this->load->view('footer_push', $data);
		}
		
		public function forget_username() {
			$this->load->model('Users');
			$data['title'] = 'BU Law SGA | Retrieve Username';
			$data['heading'] = 'Retrieve Your Username';
			if($this->input->post()) {
				$username = $this->Users->get_username_by_email( $this->input->post('email') );
				if($username) {
					$data['string'] = 'Your username is: ' . $username;
				} else {
					$data['string'] = 'Your e-mail address is not on our database. Please ' . anchor('login/signup', 'sign up for an account') . '.';
				}
			} else {
				$data['string'] = '';
			}			
			$this->load->view('header', $data);
			$this->load->view('forget_username', $data);
			$this->load->view('footer_push', $data);
		}
}
?>