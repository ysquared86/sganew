<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resources extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		$this->calendar();
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
	
	public function marketplace( $action = 'all' ) {
		if(!$this->logged_in())
		{
			$this->session->set_userdata('after_login', current_url());
			redirect('home/access_denied');
		}
		$this->load->model('Marketplace');
		$this->load->library('pagination');
		$config = array();
		$config['total_rows'] = $this->db->count_all('market');
		$config['per_page'] = 40;		
        $config["uri_segment"] = 3;
		$config["num_links"] = round($config["total_rows"] / $config["per_page"]);
		
		$data['categories'] = $this->mkt_cats_array();
		
		switch($action)
		{
			case 'all':
				// resources/marketplace/all(/page)
				if( $this->session->userdata('search_post') )
				{
					redirect('resources/marketplace/search');
				}
				
				$data['title'] = 'BU Law SGA | Resources | Marketplace';
				$data['heading'] = 'Marketplace';
				$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
				$config['base_url'] = site_url() . 'resources/marketplace/all';
				
				$this->pagination->initialize($config);
				$data['listings'] = $this->Marketplace->fetch_all_listings($config['per_page'], $page);
				$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
				$this->load->view('header', $data);
				$this->load->view('marketplace_all', $data);
			break;
				
			case 'view':
				// resources/marketplace/view/id
				if( $this->uri->segment(4) ) { $id = $this->uri->segment(4); }
				else { redirect('resources/marketplace'); }
				
				$data['admin_links'] = $this->Marketplace->check_is_listing_author( $this->session->userdata('user')->id, $id );
				
				$data['listing'] = $this->Marketplace->get_listing( $id );
				$data['attachments'] = $this->Marketplace->get_attachments( $id );
				$data['title'] = 'BU Law SGA | Resources | Marketplace | View Listing';
				$data['heading'] = 'Marketplace - View Listing';
				$this->load->view('header', $data);
				$this->load->view('marketplace_view', $data);
			break;
				
			case 'post':
				// resources/marketplace/post
				$data['title'] = 'BU Law SGA | Resources | Marketplace | Post Listing';
				$data['heading'] = 'Marketplace - Post Listing';
				$data['conditions_array'] = array(
					'Excellent' => 'Excellent',
					'Good' => 'Good',
					'Fair' => 'Fair',
					'Poor' => 'Poor'
				);
				$this->load->library('form_validation');
				$this->form_validation->set_rules('mkt_cat_id', 'Category', 'required');
				$this->form_validation->set_rules('title', 'Title', 'required');
				$this->form_validation->set_rules('description', 'Description', '');
				$this->form_validation->set_rules('condition', 'Condition', 'required');
				$this->form_validation->set_rules('price', 'Price', 'required');
				$this->form_validation->set_rules('file', 'File', '');
				$this->form_validation->set_rules('author', 'Author', '');
				$this->form_validation->set_rules('year', 'Year', '');
				$this->form_validation->set_rules('edition', 'Edition', '');
				$this->form_validation->set_rules('isbn', 'ISBN', '');
				
				if($this->form_validation->run() == false) 
				{
					$data['user_id'] = $this->session->userdata('user')->id;
					$this->load->view('header', $data);
					$this->load->view('marketplace_post', $data);
				} 
				else 
				{					
					// do the insert
					$inserted_id = $this->Marketplace->insert_listing( $this->input->post() );
					
					// insert uploads
					if($_FILES['file']['name'] != '')
					{
						$this->load->library('upload');
						// get extension
						$ext_arr = pathinfo($_FILES['file']['name']);
						$extension = $ext_arr['extension'];
						// upload
						$filename = $this->session->userdata('user')->id. '_' . time() . '.' . $extension;
						$config['upload_path'] = './uploads/marketplace/';
						$config['file_name'] = $filename;
						$config['allowed_types'] = 'gif|png|jpg|GIF|PNG|JPG';
						$this->upload->initialize($config);
						if ( !$this->upload->do_upload('file') )
						{
							echo $this->upload->display_errors();
							die;
						}
						else
						{
							$this->Marketplace->insert_upload( $inserted_id, $filename );
						}
					}
					
					$this->session->set_flashdata('flash', 'Your listing was successfully uploaded.');
					redirect('resources/marketplace/view/'.$inserted_id);
				}
			break;
			
			case 'my_listings':
				// resources/marketplace/my_listings
				$data['title'] = 'BU Law SGA | Resources | Marketplace | My Listings';
				$data['heading'] = 'Marketplace - My Listings';
				$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
				$config['base_url'] = site_url() . 'resources/marketplace/my_listings';
				
				$this->pagination->initialize($config);
				$data['listings'] = $this->Marketplace->fetch_my_listings($this->session->userdata('user')->id, $config['per_page'], $page);
				$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
				$this->load->view('header', $data);
				$this->load->view('marketplace_my_listings', $data);
			break;
				
			case 'edit':
				// resources/marketplace/edit/id
				if( $this->uri->segment(4) ) { $id = $this->uri->segment(4); }
				else { redirect('resources/marketplace'); }
				
				if( !$this->Marketplace->check_is_listing_author( $this->session->userdata('user')->id, $id ) )
				{
					redirect('resources/marketplace');
				}
				
				$data['title'] = 'BU Law SGA | Resources | Marketplace | Edit Listing';
				$data['heading'] = 'Marketplace - Edit Listing';
				$data['conditions_array'] = array(
					'Excellent' => 'Excellent',
					'Good' => 'Good',
					'Fair' => 'Fair',
					'Poor' => 'Poor'
				);
				$this->load->library('form_validation');
				$this->form_validation->set_rules('mkt_cat_id', 'Category', 'required');
				$this->form_validation->set_rules('title', 'Title', 'required');
				$this->form_validation->set_rules('description', 'Description', '');
				$this->form_validation->set_rules('condition', 'Condition', 'required');
				$this->form_validation->set_rules('price', 'Price', 'required');
				$this->form_validation->set_rules('file', 'File', '');
				$this->form_validation->set_rules('author', 'Author', '');
				$this->form_validation->set_rules('year', 'Year', '');
				$this->form_validation->set_rules('edition', 'Edition', '');
				$this->form_validation->set_rules('isbn', 'ISBN', '');
				
				if($this->form_validation->run() == false) 
				{
					$data['user_id'] = $this->session->userdata('user')->id;
					$data['listing'] = $this->Marketplace->get_listing( $id );
					$data['attachments'] = $this->Marketplace->get_attachments( $id );
					$this->load->view('header', $data);
					$this->load->view('marketplace_edit', $data);
				} 
				else 
				{
					$this->Marketplace->update_listing( $this->input->post() );
					
					// insert uploads
					if($_FILES['file']['name'] != '')
					{
						$this->load->library('upload');
						// get extension
						$ext_arr = pathinfo($_FILES['file']['name']);
						$extension = $ext_arr['extension'];
						// upload
						$filename = $this->session->userdata('user')->id. '_' . time() . '.' . $extension;
						$config['upload_path'] = './uploads/marketplace/';
						$config['file_name'] = $filename;
						$config['allowed_types'] = 'gif|png|jpg|GIF|PNG|JPG';
						$this->upload->initialize($config);
						if ( !$this->upload->do_upload('file') )
						{
							echo $this->upload->display_errors();
							die;
						}
						else
						{
							$this->Marketplace->insert_upload( $this->input->post('id'), $filename );
						}
					}
					
					$this->session->set_flashdata('flash', 'Your listing has been successfully updated.');
					redirect('resources/marketplace/view/'.$id);
				}
			break;
				
			case 'delete':
				// resources/marketplace/delete/id
				if( $this->uri->segment(4) ) { $id = $this->uri->segment(4); }
				if( $this->Marketplace->check_is_listing_author( $this->session->userdata('user')->id, $id ) )
				{
					$this->Marketplace->delete_listing( $id );
					$this->session->set_flashdata('flash', 'Your listing was successfullly deleted.');
				}
				redirect('resources/marketplace');
			break;
			
			case 'search':
				// resources/marketplace/search
				if( !$this->input->post() && !($this->session->userdata('search_post')) )
				{
					// no post, no saved session - redirect to all
					redirect('resources/marketplace');
				}
				elseif( $this->input->post() )
				{
					$this->session->set_userdata('search_post', $this->input->post());
				}
				
				$data['title'] = 'BU Law SGA | Resources | Marketplace | Search Results';
				$data['heading'] = 'Marketplace - Search Results';
				$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
				$config['base_url'] = site_url() . 'resources/marketplace/search';
				
				$this->pagination->initialize($config);
				$data['listings'] = $this->Marketplace->search_listings($this->session->userdata('search_post'), $config['per_page'], $page);
				$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
				$this->load->view('header', $data);
				if( $this->input->post('cat_id') == 1 )
				{
					// if searching for books, load the books view
					$this->load->view('marketplace_all_books', $data);
				}
				else
				{
					// if not load the default view
					$this->load->view('marketplace_all', $data);
				}
			break;
			
			case 'clear':
				// resources/marketplace/clear (clear search fields)
				$this->session->unset_userdata('search_post');
				redirect('resources/marketplace');
			break;
		}
		$this->load->view('footer', $data);
	}
	
	public function view_org( $org_id ) {
		// resources/view_org
		$this->load->model('Organizations');
		if(!isset($org_id)) { redirect('resources/student_orgs'); }
		
		$data['org'] = $this->Organizations->get_org( $org_id );
		$data['officers'] = $this->Organizations->get_officers_display( $org_id );
		$data['title'] = 'BU Law SGA | Resources | Student Organizations | ' . $data['org']->name;
		$data['heading'] = $data['org']->name;
		
		$this->load->view('header', $data);
		$this->load->view('view_org', $data);
		$this->load->view('footer', $data);
	}
	
	public function outlines( $action = 'all' ) {
		$data['years'] = $this->years_array();
		
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
			// resources/outlines/all
			$this->session->unset_userdata('search_post');
			$data["results"] = $this->Outlines->fetch_outlines($config["per_page"], $page);
			$this->pagination->initialize($config);
			$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';
			
			$data['title'] = 'BU Law SGA | Resources | Outline Database';
			$data['heading'] = 'Outline Database';
			
			$this->load->view('header', $data);
			$this->load->view('outlines', $data);
			$this->load->view('footer', $data);
		}
		
		elseif($action == 'search')
		{
			// resources/outlines/search
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
			$data["page_links"] = '<div class="page-links">'.$this->pagination->create_links().'</div>';

			$data['title'] = 'BU Law SGA | Resources | Outline Database | Search Results';
			$data['heading'] = 'Outline Database - Search Results';

			$this->load->view('header', $data);
			$this->load->view('outlines', $data);
			$this->load->view('footer', $data);
		} 
		
		elseif($action == 'upload')
		{	
			// resources/outlines/upload
			$data['title'] = 'BU Law SGA | Resources | Upload Outlines';
			$data['heading'] = 'Upload Outlines';
			$data['courses'] = $this->courses_array( false );
			$data['profs'] = $this->profs_array( false );
			
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
			$data['ratings'] = $this->Professor_ratings->get_all_ratings( $this->profs_array( false) );
			$this->load->view('header', $data);
			$this->load->view('prof_ratings', $data);
		}
		
		elseif( $action == 'view' )
		{
			$data['ratings'] = $this->Professor_ratings->get_ratings_for( $prof_id );
			$data['comments'] = $this->Professor_ratings->get_comments_for( $prof_id );
			$data['courses'] = $this->courses_array();
			$data['years'] = $this->years_array();
			
			$data['title'] = 'BU Law SGA | Resources | Professor Ratings';
			$data['heading'] = 'Professor Ratings - ' . $data['ratings']->firstname . ' ' . $data['ratings']->lastname;
			$this->load->view('header', $data);
			$this->load->view('prof_ratings_view', $data);
		}
		
		elseif( $action == 'add' )
		{
			if(!$this->input->post()) { redirect('resources/prof_ratings'); }
			else {
				if(	$this->Professor_ratings->check_rating_exists( $this->session->userdata('user')->id, $this->input->post() ) ) {
					$data['title'] = 'BU Law SGA | Resources | Professor Ratings';
					$data['heading'] = 'Repeat Ratings';
					$data['prof_id'] = $this->input->post('professor_id');
					$this->load->view('header', $data);
					$this->load->view('prof_ratings_failure', $data);
				}
				else {
					// we're all good for insert
					$this->Professor_ratings->insert_rating( $this->session->userdata('user')->id, $this->input->post() );
					
					$data['title'] = 'BU Law SGA | Resources | Professor Ratings | Success';
					$data['heading'] = 'Rating Submitted';
					$data['prof_id'] = $this->input->post('professor_id');
					$this->load->view('header', $data);
					$this->load->view('prof_ratings_success', $data);
				}
			}
		}
		
		$this->load->view('footer', $data);
	}
}

?>