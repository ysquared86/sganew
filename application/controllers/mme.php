<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mme extends MY_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Mme_issue');
	}

	public function index()
	{
		$data['title'] = 'BU Law SGA | Monday Morning E-mail | This Week';
		$data['issue'] = $this->Mme_issue->most_recent();
		$data['heading'] = 'MME - This Week';
		
		$this->load->view('header', $data);
		$this->load->view('mme_issue', $data);
		$this->load->view('footer', $data);
	}
	
	public function archives($id = null, $function = null)
	{
		if($id) {
			// if archives/id/, show single page
			$data['issue'] = $this->Mme_issue->get_by_id($id);
			$data['title'] = 'BU Law SGA | Monday Morning E-mail | Archives | ' . date('F d, Y', $data['issue']->firstmonday);
			$data['heading'] = 'MME - Archives - ' . date('F d, Y', $data['issue']->firstmonday);
			
			$this->load->view('header', $data);
			$this->load->view('mme_issue', $data);
		} else {
			// if archives/, show list
			$this->db->select('*');
			$this->db->from('mme_issues');
			$this->db->where('published', 'Y');
			$this->db->order_by('firstmonday', 'desc');
			$data['archive_array'] = $this->db->get()->result();
			$data['title'] = 'BU Law SGA | Monday Morning E-mail | Archives';
			$data['heading'] = 'MME - Archives';
			
			$this->load->view('header', $data);
			$this->load->view('mme_archives', $data);
		}			
		$this->load->view('footer', $data);
	}
	
	public function submit($function = 'new')
	{	
		// Validation rules
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('title', 'Event Title', 'required|callback_check_login');
		$this->form_validation->set_rules('location', 'Location', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required|max_length[3000]');
		
		// blank rules for repopulation
		$this->form_validation->set_rules('starts_date', 'Starts', 'callback_check_dates'); // see function for callback
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
		
		// Load organization list
		$data['orgs_list'] = array( 'NULL' => '-------------------' );
		
		if($this->form_validation->run() == false) {
			// if invalid or no post, display form
			// build organization list
			if($this->logged_in()) {	
				// everyone on the SGA board can submit on behalf of SGA
				$this->db->from('users_orgs_roles');
				$this->db->where( array('user_id' => $this->session->userdata('user')->id, 'organization_id' => 1) );
				if($this->db->count_all_results() > 0) { $data['orgs_list']['1'] = 'Student Government Association'; }
				
				// a user can submit on behalf of an organization if he is the liaison
				$this->db->select('organizations.*');
				$this->db->from('users_orgs_roles');
				$this->db->join('organizations', 'users_orgs_roles.organization_id = organizations.id');
				$this->db->where('users_orgs_roles.role_id', 8);
				$this->db->where('users_orgs_roles.user_id', $this->session->userdata('user')->id);
				$orgs = $this->db->get();
				foreach($orgs->result() as $org) {
					$data['orgs_list'][$org->id] = $org->name;
				}
			}
			
			$this->load->view('mme_submit', $data);
		} else {
			// if everything checks out
			if( $this->input->post('no_time') == 'Y' ) {
				$starts = 'NULL';
				$ends = 'NULL';
			} else {
				$starts = strtotime( $this->input->post('starts_date') . ' ' . $this->input->post('starts_hour') . ':' . $this->input->post('starts_minute') . ' ' .$this->input->post('starts_ampm') );
				$ends = strtotime( $this->input->post('ends_date') . ' ' . $this->input->post('ends_hour') . ':' . $this->input->post('ends_minute') . ' ' .$this->input->post('ends_ampm') );
			}
			$mme_submission = array(
				'user_id' => $this->session->userdata('user')->id,
				'organization_id' => $this->input->post('organization_id'),
				'title' => $this->input->post('title'),
				'starts' => $starts,
				'ends' => $ends,
				'location' => $this->input->post('location'),
				'description' => $this->input->post('description'),
				'link' => $this->input->post('link'),
				'email' => $this->input->post('email'),
				'status' => 'P',
				'created' => time()
			);
			
			$this->db->insert('mme_submissions', $mme_submission);
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
			/* function to check if the start time is earlier than the end time */
			// Convert times to string
			$starts = strtotime( $str . ' ' . $this->input->post('starts_hour') . ':' . $this->input->post('starts_minute') . ' ' .$this->input->post('starts_ampm') );
			$ends = strtotime( $this->input->post('ends_date') . ' ' . $this->input->post('ends_hour') . ':' . $this->input->post('ends_minute') . ' ' .$this->input->post('ends_ampm') );
			
			if($starts > $ends) {
				$this->form_validation->set_message('check_dates', 'The Start Time must be earlier than the End Time.');
				return false;
			} else {
				return true;
			}
		}
	
	public function edit($id = null)
	{
		
	}
}

?>