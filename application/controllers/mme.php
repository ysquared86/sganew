<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mme extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Mme_issues');
		$this->load->model('Mme_issue');
	}

	public function index()
	{
		// mme
		$data['title'] = 'BU Law SGA | Monday Morning E-mail | This Week';
		$data['issue'] = $this->Mme_issues->get_most_recent();
		$data['heading'] = 'MME - This Week';
		
		$this->load->view('header', $data);
		$this->load->view('mme_single_issue', $data);
		$this->load->view('footer', $data);
	}
	
	public function view( $id )
	{
		// mme/view/id
		if(!isset($id)) { redirect('mme'); }
	
		$data['issue'] = $this->Mme_issues->get_issue_by_id( $id );
		$data['title'] = 'BU Law SGA | Monday Morning E-mail | Archives | ' . date('F d, Y', $data['issue']->firstmonday);
		$data['heading'] = 'MME - Archives - ' . date('F d, Y', $data['issue']->firstmonday);
		
		$this->load->view('header', $data);
		$this->load->view('mme_single_issue', $data);
	}
	
	public function archives()
	{
		// mme/archives
		$this->load->library('pagination');
	
		$config = array();
		$config['base_url'] = site_url() . 'mme/archives';
		$config['total_rows'] = $this->Mme_issues->issues_count();
		$config['per_page'] = 40;		
		$config["uri_segment"] = 3;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);

		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$data["issues"] = $this->Mme_issues->fetch_all_published_issues($config["per_page"], $page);
		
		$data['title'] = 'BU Law SGA | Monday Morning E-mail | Archives';
		$data['heading'] = 'MME - Archives';
		
		$this->load->view('header', $data);
		$this->load->view('mme_archives', $data);	
		$this->load->view('footer', $data);
	}
	
	public function submit()
	{	
		// mme/submit
		if(!$this->logged_in())
		{
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
		
		// Validation rules
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', 'Event Title', 'required|callback_check_login');
		$this->form_validation->set_rules('location', 'Location', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required|max_length[3000]');
		
		// blank rules for repopulation
		$this->form_validation->set_rules('starts_date', 'Starting date/time', 'callback_check_dates'); // see function for callback
		$this->form_validation->set_rules('starts_hour', '', ''); 
		$this->form_validation->set_rules('starts_minute', '', ''); 
		$this->form_validation->set_rules('starts_ampm', '', ''); 
		$this->form_validation->set_rules('ends_date', '', ''); 
		$this->form_validation->set_rules('ends_hour', '', ''); 
		$this->form_validation->set_rules('ends_minute', '', ''); 
		$this->form_validation->set_rules('ends_ampm', '', '');
		$this->form_validation->set_rules('link', '', '');
		$this->form_validation->set_rules('email', 'Contact E-mail', 'valid_email');
		
		// Page info
		$data['title'] = 'BU Law SGA | Monday Morning E-mail | Submit to MME';
		$data['heading'] = 'MME - Submit to MME';
		
		// Load views
		$this->load->view('header', $data);
		
		if($this->form_validation->run() == false) {
			// if invalid or no post, display form
			// Load user model for liaisons
			$this->load->model('Users');
			$data['orgs_list'] = $this->Users->get_my_orgs( $this->session->userdata('user')->id, true );
			if($this->is_sga()) { $data['orgs_list']['1'] = 'Student Government Association'; }
			$this->load->view('mme_submit', $data);
		} else {
			// if everything checks out
			$this->Mme_issues->insert_submission( $this->input->post() );
			$this->load->view('mme_submit_success', $data);
		}
		
		$this->load->view('footer', $data);
	}
	
	public function check_login() {
		if($this->logged_in()) {
			return true;
		} else {
			$this->form_validation->set_message('check_login', 'You must be logged in to submit to the MME.');
			return false;
		}
	}
	
	public function check_dates($str) {
		if($this->input->post('no_time') == 'Y') { return true; }
		
		/* function to check if the start time is earlier than the end time */
		$starts = strtotime( $str . ' ' . $this->input->post('starts_hour') . ':' . $this->input->post('starts_minute') . ' ' .$this->input->post('starts_ampm') );
		$ends = strtotime( $this->input->post('ends_date') . ' ' . $this->input->post('ends_hour') . ':' . $this->input->post('ends_minute') . ' ' .$this->input->post('ends_ampm') );
		
		if($starts > $ends)
		{
			$this->form_validation->set_message('check_dates', 'The Start Time must be earlier than the End Time.');
			return false;
		}
		elseif($starts < time())
		{	
			$this->form_validation->set_message('check_dates', 'The Start Time must be in the future.');
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function my_submissions( $submissions_id = null )
	{
		if($this->logged_in()) { $user_id = $this->session->userdata('user')->id; }
		else
		{
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
		
		if(!isset( $submissions_id ))
		{
			// mme/my_submissions/
			$data['title'] = 'BU Law SGA | Monday Morning E-mail | My MME Submissions';
			$data['heading'] = 'My MME Submissions';
			$data['submissions'] = $this->Mme_issues->fetch_submissions_by( $user_id );
		
			$this->load->view('header', $data);
			$this->load->view('my_submissions', $data);
		}
		
		else
		{
			// mme/my_submissions/id
			// Load user model for liaisons
			$this->load->model('Users');
			$data['orgs_list'] = $this->Users->get_my_orgs( $this->session->userdata('user')->id, true );	

			$data['submission'] = $this->Mme_issues->fetch_submission( $submissions_id );
			$data['title'] = 'BU Law SGA | Monday Morning E-mail | Edit a Submission';
			$data['heading'] = 'Edit a Submission';
			
			$this->load->view('header', $data);
			
			// Validation rules
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('title', 'Event Title', 'required|callback_check_login');
			$this->form_validation->set_rules('location', 'Location', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required|max_length[3000]');
			
			// blank rules for repopulation
			$this->form_validation->set_rules('starts_date', 'Starting date/time', 'callback_check_dates'); // see function for callback
			$this->form_validation->set_rules('starts_hour', '', ''); 
			$this->form_validation->set_rules('starts_minute', '', ''); 
			$this->form_validation->set_rules('starts_ampm', '', ''); 
			$this->form_validation->set_rules('ends_date', '', ''); 
			$this->form_validation->set_rules('ends_hour', '', ''); 
			$this->form_validation->set_rules('ends_minute', '', ''); 
			$this->form_validation->set_rules('ends_ampm', '', '');
			$this->form_validation->set_rules('link', '', '');
			$this->form_validation->set_rules('email', 'Contact E-mail', 'valid_email');
			
			if($this->form_validation->run() == true) {
				$this->Mme_issues->update_submission( $this->input->post() );
				$this->session->set_flashdata('flash', 'Your submission was successfully saved.');
			}			
			$this->load->view('edit_submission', $data);
		}
		
		$this->load->view('footer', $data);
	}
	
	public function delete_submission( $id )
	{
		$this->session->set_flashdata('flash', 'Submission deleted.');
		$this->Mme_issues->delete_submission( $id );
		redirect( 'mme/my_submissions' );
	}
}

?>