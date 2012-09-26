<?php
class Outlines extends CI_Model
{	
    public function __construct() {
        parent::__construct();
    }
    
    public function outlines_count() {
        return $this->db->count_all('outlines');
    }
    
    public function fetch_outlines($limit = null, $start = null) {
		$this->db->select('outlines.*, courses.course_number, courses.course_title, users.firstname, users.lastname, users.username, professors.lastname as instructor');
		$this->db->from('outlines');
		$this->db->join('users', 'outlines.user_id = users.id', 'left');
		$this->db->join('courses', 'outlines.course_id = courses.id', 'left');
		$this->db->join('professors', 'outlines.professor_id = professors.id', 'left');
		$this->db->where('outlines.pending', 'N');
		$this->db->order_by('created', 'desc');
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}
		
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return false;
   }
   
   
    
    public function fetch_pending_outlines() {
		$this->db->select('outlines.*, courses.course_number, courses.course_title, users.firstname, users.lastname, users.username, professors.lastname as instructor');
		$this->db->from('outlines');
		$this->db->join('users', 'outlines.user_id = users.id', 'left');
		$this->db->join('courses', 'outlines.course_id = courses.id', 'left');
		$this->db->join('professors', 'outlines.professor_id = professors.id', 'left');
		$this->db->where('outlines.pending', 'Y');
		$this->db->order_by('created', 'desc');
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
   }
   
   public function search_outlines($post, $limit = null, $start = null) {
		$this->db->select('outlines.*, courses.course_number, courses.course_title, users.firstname, users.lastname, users.username, professors.lastname as instructor');
		$this->db->from('outlines');
		$this->db->join('users', 'outlines.user_id = users.id', 'left');
		$this->db->join('courses', 'outlines.course_id = courses.id', 'left');
		$this->db->join('professors', 'outlines.professor_id = professors.id', 'left');
		
		if($post['course_number'] != '') { $this->db->like('courses.course_number', $post['course_number']); }
		if($post['course_title'] != '') { $this->db->like('courses.course_title', $post['course_title']); }
		if($post['instructor'] != '') { $this->db->like('professors.lastname', $post['instructor']); }
		if($post['semester'] != '') { $this->db->where('outlines.semester', $post['semester']); }
		if($post['year'] != '') { $this->db->where('outlines.year', $post['year']); }
		
		$this->db->where('outlines.pending', 'N');		
		$this->db->order_by('created', 'desc');
		
		if(isset($limit) && isset($start)) {
			$this->db->limit($limit, $start);
		}

		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
   }
   
   
   public function insert_outline( $post ) {
		$filename = $post['user_id'] . '_' . time();
		
		$outline = array(
			'course_id' => $post['course_id'],
			'professor_id' => $post['professor_id'],
			'semester' => $post['semester'],
			'year' => $post['year'],
			'user_id' => $post['user_id'],
			'path' => $filename,
			'pending' => 'Y',
			'created' => time()
		);
		
		$config['upload_path'] = './uploads/outlines/';
		$config['file_name'] = $filename . '.pdf';
		$config['allowed_types'] = 'pdf';
		$config['max_size']	= '2000';

		$this->load->library('upload', $config);

		if ( !$this->upload->do_upload('file'))
		{
			return $this->upload->display_errors();
		}
		else
		{
			$this->db->insert('outlines', $outline);
			return $this->upload->data();
		}
   }
   
   public function delete_outline( $id ) {
		$this->db->delete('outlines', array('id' => $id));
   }
   
   public function approve_outline( $id ) {
		$this->db->update('outlines', array('pending' => 'N'), array('id' => $id));
   }
}
?>