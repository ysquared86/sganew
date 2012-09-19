<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends CI_Controller {
	public $db1, $db2;
	
	public function __construct() {
		parent::__construct();
		$this->load->helper('html');
		
		$config1 = array(
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'sga_dbase',
			'dbdriver' => 'mysql'
		);
		$config2 = array(
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '',
			'database' => 'sga_new',
			'dbdriver' => 'mysql'
		);
		$this->db1 = $this->load->database($config1, TRUE); //old DB
		$this->db2 = $this->load->database($config2, TRUE); //new DB
	}

	public function index()
	{
		$data['title'] = 'Migrating Data';
		$this->load->view('header', $data);
		$this->load->view('migration', $data);
		$this->load->view('footer', $data);
	}
	
	public function users()
	{
		/* DONE!
		$data['title'] = 'Migrating Users';
		$query = $this->db1->get('user_info');
		$class = '';
		foreach ($query->result() as $row)
		{
			switch($row->user_Affiliation) {
				case 'Class of 2012':
					$class = 'alumni';
					break;
				case 'Class of 2013':
					$class = '2013';
					break;
				case 'Class of 2014':
					$class = '2014';
					break;
				case 'Class of 2015':
					$class = '2015';
					break;
				case 'LLM':
					$class = 'alumni';
					break;
				case 'Faculty/Staff':
					$class = 'faculty';
					break;
				default:
					$class = 'alumni';
					break;
			}
			
			$pending = ($row->access_level == 'Guest') ? 'Y' : 'N';
			$temparray = array(
				'username' => $row->username,
				'password' => $row->pwd,
				'firstname' => $row->first_Name,
				'lastname' => $row->last_Name,
				'email' => $row->email_Address,
				'email_pref' => $row->pref_email,
				'phone' => $row->user_phone,
				'class' => $class,
				'pending' => $pending,
				'created' => strtotime($row->timestamp),
				'lastlogin' => strtotime($row->last_login)
			);
			$this->db2->insert('users', $temparray);
			print_r($temparray);
			echo 'Okay!<br /><br />';
		}
		*/
	}
	
	public function courses() {
		/* DONE!
		$data['title'] = 'Migrating Courses';
		$query = $this->db1->get('course_list');
		foreach ($query->result() as $row) {
			$temparray = array(
				'course_number' => $row->course_number,
				'course_title' => $row->course_title
			);
			$this->db2->insert('courses', $temparray);
			print_r($temparray);
			echo 'Okay!<br /><br />';
		}
		*/
	}
	
	public function profs() {
		/* DONE!
		$data['title'] = 'Migrating Professors';
		$query = $this->db1->get('professor_list');
		foreach ($query->result() as $row) {
			$temparray = array(
				'firstname' => $row->prof_firstname,
				'lastname' => $row->prof_lastname,
				'middlename' => $row->prof_middle
			);
			$this->db2->insert('professors', $temparray);
			print_r($temparray);
			echo 'Okay!<br /><br />';
		}
		*/
	}
	
	public function ratings() {
		/* DONE
		$data['title'] = 'Migrating Ratings';
		$this->db1->select('ratings.*, professor_list.prof_lastname, professor_list.prof_firstname, course_list.course_number');
		$this->db1->from('ratings');
		$this->db1->join('professor_list', 'ratings.prof_ID = professor_list.prof_ID');
		$this->db1->join('course_list', 'ratings.class = course_list.course_title');
		
		$query = $this->db1->get();
		foreach($query->result() as $row) {
			$course = $this->db2->get_where('courses', array('course_number' => $row->course_number));
			$course = $course->result();
			$course_id = $course[0]->id;
			
			$professor = $this->db2->get_where('professors', array('firstname' => $row->prof_firstname, 'lastname' => $row->prof_lastname));
			$professor = $professor->result();
			$professor_id = $professor[0]->id;
			
			$temparray = array(
				'user_id' => '1',
				'course_id' => $course_id,
				'professor_id' => $professor_id,
				'semester' => $row->semester,
				'year' => $row->year,
				'helpful' => $row->helpfulness,
				'clear' => $row->clarity,
				'easy' => $row->easiness,
				'overall' => $row->overall,
				'comments' => $row->comment,
				'created' => strtotime($row->timestamp)
			);
			$this->db2->insert('ratings', $temparray);
			print_r($temparray);
			echo 'Okay! <br /><br />';
		}
		*/
	}
	
	public function orgs() {
		/* DONE!
		$data['title'] = 'Migrating Organizations';
		$this->db1->select('*');
		$this->db1->from('group_list');
		$this->db1->join('organizations', 'group_list.org_ID = organizations.org_ID');
		
		$query = $this->db1->get();
		
		$array1 = array(
			'name' => 'Student Government Association',
			'description' => '',
			'website' => 'sgalaw.bu.edu',
			'email' => 'youngo@bu.edu',
			'phone' => '',
			'account' => 'N/A',
			'status' => 'active',
			'created' => time()
		);
		$this->db2->insert('organizations', $array1);
		print_r($array1);
		echo 'Okay!<br /><br />';
		
		foreach ($query->result() as $row) {
			$status = ($row->hidden == 'N') ? 'active' : 'inactive';
			$temparray = array(
				'name' => $row->org_name,
				'description' => $row->description,
				'website' => $row->link,
				'email' => $row->email,
				'phone' => $row->phone,
				'account' => $row->account,
				'status' => $status,
				'created' => time()
			);
			$this->db2->insert('organizations', $temparray);
			print_r($temparray);
			echo 'Okay!<br /><br />';
		}
		*/
	}
	
	public function outlines() {
		/* DONE!
		$data['title'] = 'Migrating Outlines';
		$this->db1->select('outlines.*, user_info.email_Address, professor_list.prof_lastname, professor_list.prof_firstname, course_list.course_number');
		$this->db1->from('outlines');
		$this->db1->join('user_info', 'outlines.user_ID = user_info.user_ID');
		$this->db1->join('professor_list', 'outlines.professor = professor_list.prof_ID');
		$this->db1->join('course_list', 'outlines.course = course_list.course_ID');
		
		$query = $this->db1->get();
		
		foreach ($query->result() as $row) {
			$user = $this->db2->get_where('users', array('email' => $row->email_Address));
			$user = $user->result();
			$user_id = $user[0]->id;
			
			$course = $this->db2->get_where('courses', array('course_number' => $row->course_number));
			$course = $course->result();
			$course_id = $course[0]->id;
			
			$professor = $this->db2->get_where('professors', array('firstname' => $row->prof_firstname, 'lastname' => $row->prof_lastname));
			$professor = $professor->result();
			$professor_id = $professor[0]->id;
			
			$fileinfo = pathinfo($row->file_path);
			$file = $fileinfo['filename'];
			
			$temparray = array(
				'user_id' => $user_id,
				'course_id' => $course_id,
				'professor_id' => $professor_id,
				'semester' => $row->semester,
				'year' => $row->year,
				'path' => $file,
				'pending' => 'N',
				'created' => time()
			);
			
			$this->db2->insert('outlines', $temparray);
			print_r($temparray);
			echo 'Okay! <br /><br />';
		}
		*/
	}
	
	public function marketplace() {
		/* DONE!
		$data['title'] = 'Migrating Bookswap/Furniture Swap';
	
		#Bookswap
		$this->db1->select('bookswap.*, user_info.email_Address');
		$this->db1->from('bookswap');
		$this->db1->join('user_info', 'bookswap.user_ID = user_info.user_ID');
		
		$query = $this->db1->get();
		
		foreach ($query->result() as $row) {
			$user = $this->db2->get_where('users', array('email' => $row->email_Address));
			$user = $user->result();
			$user_id = $user[0]->id;
			
			$temparray1 = array(
				'mkt_cat_id' => 1,
				'user_id' => $user_id,
				'title' => $row->title,
				'description' => $row->description,
				'condition' => $row->status,
				'price' => $row->price, //FORMAT?
				'created' => strtotime($row->timestamp)
			);
			
			$this->db2->insert('market', $temparray1);
			
			$temparray2 = array(
				'market_id' => $this->db2->insert_id(),
				'author' => $row->author,
				'isbn' => $row->ISBN,
				'edition' => $row->edition
			);
			
			$this->db2->insert('market_books_meta', $temparray2);
			
			echo $row->title.' successfully added.<br />';
		}
		*/
	}
	
	public function mme() {
		/* DONE!
		$data['title'] = 'Migrating MMEs';
		
		#Issues
		$this->db1->select('*');
		$this->db1->from('mme_listing');
		$this->db1->order_by('end_date');
		
		$query = $this->db1->get();
		
		foreach ($query->result() as $row) {
			$temparray = array(
				'firstmonday' => strtotime($row->this_week),
				'secondmonday' => strtotime($row->next_week),
				'lastday' => strtotime($row->end_date)
			);
			
			# Checking overrides... no overrides... oy.
			$entries_in_issue = explode(',', $row->submissions);
			
			$this->db1->select('event_ID');
			$this->db1->from('mme_submissions');
			$this->db1->where( array('event_date >=' => $row->this_week, 'event_date <=' => $row->end_date) );
			
			$check = $this->db1->get();
			
			$should_be_in_issue = array();
			foreach($check->result() as $row1) {
				array_push($should_be_in_issue, "'".$row1->event_ID."'");
			}
			echo 'Should be in: '.implode(',', $should_be_in_issue);
			echo '<br />Is in: '.implode(',', $entries_in_issue);
			
			$overrides = array_diff( $entries_in_issue, $should_be_in_issue );
			
			echo '<br />Overrides: '.implode(',', $overrides);
			echo '<br /><br />';
			
			
			$this->db2->insert('mme_issues', $temparray);
		}
		
		#entries
		$this->db1->select('mme_submissions.*, user_info.email_Address');
		$this->db1->from('mme_submissions');
		$this->db1->join('user_info', 'mme_submissions.user_ID = user_info.user_ID');
		
		$query1 = $this->db1->get();
		
		foreach ($query1->result() as $row) {
			# Get user ID
			$user = $this->db2->get_where('users', array('email' => $row->email_Address));
			$user = $user->result();
			$user_id = $user[0]->id;
			$starts = strtotime($row->event_date.' '.$row->start_time);
			$ends = strtotime($row->event_date.' '.$row->end_time);
			
			# Get organization ID
			$org = $this->db2->get_where('organizations', array('name' => $row->group_name));
			$org = $org->result();
			$org_id = (!empty($org[0])) ? $org[0]->id : null;
			
			# Get dates
			$starts = strtotime($row->event_date.' '.$row->start_time);
			$ends = strtotime($row->event_date.' '.$row->end_time);	
			
			# Get status
			switch($row->event_status) {
				case 'Approved':
					$status = 'A';
					break;
				case 'Denied':
					$status = 'D';
					break;
				case 'Open':
					$status = 'P';
					break;
				default:
					$status = 'A';
					break;
			}
			
			$temparray = array(
				'user_id' => $user_id,
				'organization_id' => $org_id,
				'title' => $row->event_title,
				'starts' => $starts,
				'ends' => $ends,
				'location' => $row->event_location,
				'description' => $row->event_description,
				'link' => $row->website,
				'email' => $row->email,
				'status' => $status,
				'created' => strtotime($row->event_timestamp)
			);
			
			$this->db2->insert('mme_submissions', $temparray);
			echo '"' . $temparray['title'] . '" successfully added.<br /><br />';
		}
	}
	*/
}

?>