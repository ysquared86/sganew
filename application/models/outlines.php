<?php
class Outlines extends CI_Model
{	
    public function __construct() {
        parent::__construct();
    }
    
    public function fetch_outlines($limit = null, $start = null) {
		$this->db->select('outlines.*, courses.course_number, courses.course_title, users.firstname, users.lastname, users.username, professors.lastname as instructor');
		$this->db->from('outlines');
                $this->db->join('users', 'outlines.user_id = users.id', 'left');
                $this->db->join('courses', 'outlines.course_id = courses.id', 'left');
                $this->db->join('professors', 'outlines.professor_id = professors.id', 'left');
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
}
?>