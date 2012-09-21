<?php
class Professor_ratings extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
	
	public function get_ratings_for( $prof_id ) {
		$this->db->select('AVG(ratings.overall) as overall_avg, AVG(ratings.helpful) as helpful_avg, AVG(ratings.clear) as clear_avg, AVG(ratings.easy) as easy_avg, professors.lastname, professors.firstname, ratings.professor_id');
		$this->db->from('ratings');
		$this->db->join('courses', 'ratings.course_id = courses.id');
		$this->db->join('professors', 'ratings.professor_id = professors.id');
		$this->db->where('professors.id', $prof_id);
		$this->db->group_by('ratings.professor_id');
		
		$query = $this->db->get();

        if ($query->num_rows() > 0) {
            $arr = $query->result();
			return $arr[0];
        }
        return false;
	}
	
	public function get_comments_for( $prof_id ) {
		$this->db->select('*');
		$this->db->from('ratings');
		$this->db->where('professor_id', $prof_id);
		$this->db->order_by('created', 'desc');
	}
}
?>