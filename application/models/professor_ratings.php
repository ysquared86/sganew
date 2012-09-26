<?php
class Professor_ratings extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
	
	public function get_all_ratings( $profs_arr ) {
		$arr = array();
		foreach( $profs_arr as $prof_id => $prof_name ) {
			$arr[$prof_id] = $this->get_ratings_for( $prof_id );
		}
		return $arr;
		/*
		$this->db->select('AVG(ratings.overall) as overall_avg, AVG(ratings.helpful) as helpful_avg, AVG(ratings.clear) as clear_avg, AVG(ratings.easy) as easy_avg, ratings.*, professors.lastname, professors.firstname');
		$this->db->from('professors');		
		$this->db->join('ratings', 'professors.id = ratings.professor_id', 'left');
		$this->db->group_by('ratings.professor_id');
		
		$arr = $this->db->get()->result();
		foreach($arr as $obj) {
			$obj->overall_avg = number_format($obj->overall_avg, 2);
			$obj->helpful_avg = number_format($obj->helpful_avg, 2);
			$obj->clear_avg = number_format($obj->clear_avg, 2);
			$obj->easy_avg = number_format($obj->easy_avg, 2);
			
			$obj->overall_rgb = $this->get_rgb( $obj->overall_avg );
			$obj->helpful_rgb = $this->get_rgb( $obj->helpful_avg );
			$obj->clear_rgb = $this->get_rgb( $obj->clear_avg );
			$obj->easy_rgb = $this->get_rgb( $obj->easy_avg );
		}
		return $arr;
		*/
	}
	
	public function get_ratings_for( $prof_id ) {
		$this->db->select('AVG(ratings.overall) as overall_avg, AVG(ratings.helpful) as helpful_avg, AVG(ratings.clear) as clear_avg, AVG(ratings.easy) as easy_avg, professors.lastname, professors.firstname, ratings.professor_id');
		$this->db->from('ratings');
		$this->db->join('courses', 'ratings.course_id = courses.id');
		$this->db->join('professors', 'ratings.professor_id = professors.id');
		$this->db->where('professors.id', $prof_id);
		$this->db->group_by('ratings.professor_id');
		
		$query = $this->db->get();
		$obj = new stdClass();
		
        if ($query->num_rows() > 0) {
            $arr = $query->result();
			$obj = $arr[0];
			$obj->overall_avg = number_format($obj->overall_avg, 2);
			$obj->helpful_avg = number_format($obj->helpful_avg, 2);
			$obj->clear_avg = number_format($obj->clear_avg, 2);
			$obj->easy_avg = number_format($obj->easy_avg, 2);
			$obj->overall_rgb = $this->get_rgb( $obj->overall_avg );
			$obj->helpful_rgb = $this->get_rgb( $obj->helpful_avg );
			$obj->clear_rgb = $this->get_rgb( $obj->clear_avg );
			$obj->easy_rgb = $this->get_rgb( $obj->easy_avg );
        }
		else {
			$result = $this->db->get_where('professors', array('id' => $prof_id))->result();
			$result = $result[0];
			$obj->lastname = $result->lastname;
			$obj->firstname = $result->firstname;
			$obj->professor_id = (string)$prof_id;
			$obj->overall_avg = 'N/A';
			$obj->helpful_avg = 'N/A';
			$obj->clear_avg = 'N/A';
			$obj->easy_avg = 'N/A';
			$obj->overall_rgb = 'none';
			$obj->helpful_rgb = 'none';
			$obj->clear_rgb = 'none';
			$obj->easy_rgb = 'none';
		}		
		return $obj;
	}
	
	public function get_comments_for( $prof_id ) {
		$this->db->select('ratings.comments, ratings.created, ratings.semester, ratings.year, courses.course_title');
		$this->db->from('ratings');
		$this->db->join('courses', 'ratings.course_id = courses.id', 'left');
		$this->db->where('ratings.professor_id', $prof_id);
		$this->db->where('ratings.comments !=', '');
		$this->db->order_by('ratings.created', 'desc');
		
		return $this->db->get()->result();
	}
	
	public function get_rgb( $number ) {
		$green = $number / 5 * 10;
		$red = 10 - $green + 2;
		if($green > $red) { $green = 'DD'; $red = round($red, 0) . 'C'; }
		if($red > $green) { $red = 'DD'; $green = round($green, 0) . 'C';  }
		
		$rgb = '#' . $red . $green . '00';
		if($red == $green) { $rgb = '#FFFF00'; }
		
		return $rgb;
	}
	
	public function check_rating_exists( $user_id, $post ) {
		$this->db->where(array( 'user_id' => $user_id, 'course_id' => $post['course_id'], 'professor_id' => $post['professor_id'] ));
		$this->db->from('ratings');
		return ($this->db->count_all_results() > 0);
	}
	
	public function insert_rating( $user_id, $post ) {
		$arr = array(
			'user_id' => $user_id,
			'course_id' => $post['course_id'],
			'professor_id' => $post['professor_id'],
			'semester' => $post['semester'],
			'year' => $post['year'],
			'helpful' => $post['helpful'],
			'clear' => $post['clear'],
			'easy' => $post['easy'],
			'overall' => $post['overall'],
			'comments' => $post['comments'],
			'created' => time()
		);
		$this->db->insert('ratings', $arr);
	}
}
?>