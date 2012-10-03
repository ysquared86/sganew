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
		$this->load->model('Users');
		
		if( $this->Users->valid_login( $this->input->post() ) ) {
			// User checks out. Log them in.
			$user = $this->Users->log_in_user( $this->input->post('username') );
			$this->session->set_userdata('user', $user);
			
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
			$this->load->view('footer', $data);		
		} else {
			// insert user
			$token = $this->Users->insert_user( $this->input->post() );
			
			// EMAIL
			$this->load->library('email', $this->email_config);
			$this->email->set_newline("\r\n");
			
			$this->email->from('ysquared86@gmail.com', 'SGA Law Webmaster');
			$this->email->to( $this->input->post('email_pref') );
			
			$message = 'Welcome to the BU Law SGA Website!';
			$message .= "<br /><br />";
			$message .= 'You have registered to use ' . anchor('http://sgalaw.bu.edu', 'sgalaw.bu.edu') . '. Your account will be in pending status until you finalize your sign-up by going to the confirmation page and entering the following code:';
			$message .= "<br /><br />";
			$message .= 'Confirmation Code: <strong>'.$token.'</strong><br />';
			$message .= anchor('login/confirm/' . $this->input->post('username'), 'Click here to finalize your registration.');
			$message .= "<br /><br />";
			$message .= 'Thank you for your registration.';
			$message .= 'Please do not reply to this message.';
			$message .= "<br /><br />";
			$message .= '- Your SGA';
			
			$this->email->subject('Finalize your registration for BU Law SGA Website');
			$this->email->message( $message );
			$this->email->send();
			
			// load success
			$data['username'] = $this->input->post('username');
			$this->load->view('signup_success', $data);
			$this->load->view('footer_push', $data);		
		}		
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
		
	public function confirm( $username ) {
		$data['title'] = 'BU Law SGA | Finalize Your Registration';
		$data['heading'] = 'Finalize Your Registration';
		
		$this->load->model('Users');
		$this->load->library('form_validation');
		
		$data['user'] = $this->Users->get_user_by_username( $username );
		
		// Validate
		$this->form_validation->set_rules('token', 'Confirmation Code', 'required|callback_check_token');
		
		$this->load->view('header', $data);
		
		if($this->form_validation->run() == false) {
			$this->load->view('signup_confirm', $data);
		} else {
			$this->Users->confirm( $this->input->post() );
			$this->session->set_flashdata('flash', 'You have successfully created an account!');
			redirect('login');
		}
		
		$this->load->view('footer_push', $data);
	}
	
	public function forget_pw( $action = null ) {
		$data['title'] = 'BU Law SGA | Request Password Reset';
		$data['heading'] = 'Request Password Reset';
		
		$this->load->model('Users');
		$this->load->library('form_validation');
		
		// Validate
		$this->form_validation->set_rules('username', 'Username', 'required|callback_check_un_email_combo');
		$this->form_validation->set_rules('email', 'E-mail Address', 'required');
		
		$this->load->view('header', $data);
		
		if($this->form_validation->run() == false) {
			$this->load->view('forget_password', $data);
		} else {
			$this->load->helper('string');
			$this->load->library('email', $this->email_config);
			$this->email->set_newline("\r\n");
			
			$this->email->from('ysquared86@gmail.com', 'SGA Law Webmaster');
			$this->email->to( $this->input->post('email') );
			
			$user_id = $this->Users->get_user_by_username( $this->input->post('username') )->id;
			$token = random_string('sha1', 20);
			$this->Users->set_token( $user_id, $token );
			
			$message = 'Dear SGA user,';
			$message .= "<br /><br />";
			$message .= 'You have requested to reset your password. Please use the following:';
			$message .= "<br /><br />";
			$message .= 'Security Code: <strong>'.$token.'</strong><br />';
			$message .= anchor('login/reset_pw/' . $this->input->post('username'), 'Click here to reset your password.');
			$message .= "<br /><br />";
			$message .= 'Please do not reply to this message.';
			$message .= "<br /><br />";
			$message .= '- Your SGA';
			
			$this->email->subject('Reset your SGA website password');
			$this->email->message( $message );
			$this->email->send();
			
			$this->load->view('forget_password_sent', $data);
		}
		$this->load->view('footer_push', $data);
	}
		public function check_un_email_combo($str) {
			$this->load->model('Users');
			
			if( $this->Users->check_un_email_combo($str, $this->input->post('email')) ) {
				return true;
			} else {
				$this->form_validation->set_message('check_un_email_combo', 'We could not find an account under this username and e-mail combination.');
				return false;
			}			
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
	
	public function reset_pw( $username = null )
	{
		$data['title'] = 'BU Law SGA | Reset Your Password';
		$data['heading'] = 'Reset Your Password';
		
		$this->load->model('Users');
		$this->load->library('form_validation');
		
		$data['user'] = $this->Users->get_user_by_username( $username );
		
		// Validate
		$this->form_validation->set_rules('password', 'Password', 'required|callback_check_passwords');
		$this->form_validation->set_rules('password1', 'Re-typed Password', 'required');
		$this->form_validation->set_rules('token', 'Secret Code', 'required|callback_check_token');
		
		$this->load->view('header', $data);
		
		if($this->form_validation->run() == false) {
			$this->load->view('reset_pw', $data);
		} else {
			$this->Users->update_password( $this->input->post() );
			$this->session->set_flashdata('flash', 'You have successfully reset your password.');
			redirect('login');
		}
		
		$this->load->view('footer_push', $data);
	}
		public function check_token($str)
		{
			$this->load->model('Users');
			if( $this->Users->check_token($this->input->post('id'), $str) ) {
				return true;
			} else {
				$this->form_validation->set_message('check_token', 'Incorrect security code.');
				return false;
			}
		}
}
?>
