<?php
	/*
	* Author: Young J. Yoon
	* Written: Fall 2012
	* For: SGA website
	* Description: Each issue of the Monday Morning E-mail is cast into this model. Also see Model Mme_article for each article
	*/
	
	class Mme_issue extends CI_Model {
		public $id;
		public $firstmonday = '';
		public $secondmonday = '';
		public $lastday = '';
		public $published = '';
		public $entries = array( 'sga' => array(), 'thisweek' => array(), 'nextweek' => array(), 'overrides' => array() );
		
		public function __construct()
		{
			parent::__construct();
		}
		
		// function to load entry IDs for each issue
		public function load_issue() {
			// get SGA announcements first
			$this->db->select('id');
			$this->db->from('mme_submissions');
			$this->db->where('starts >=', $this->firstmonday); // between first monday and...
			$this->db->where('starts <=', $this->lastday); // second monday.
			$this->db->where('organization_id', 1); // SGA is ID'ed 1 under organizations table
			$this->db->where('status', 'A'); // approved listings
			$this->db->order_by('starts', 'asc');
			
			// execute query
			$query = $this->db->get();
			
			// add to the SGA listings ID array
			$sga_ids = array();
			foreach($query->result() as $row) {
				array_push($sga_ids, $row->id);
			}
			$this->entries['sga'] = $sga_ids;
			
			// get entry IDs for this week from mme_submissions
			$this->db->select('id');
			$this->db->from('mme_submissions');
			$this->db->where('starts >=', $this->firstmonday); // between first monday and...
			$this->db->where('starts <', $this->secondmonday); // second monday.
			$this->db->where('(organization_id != 1 OR organization_id IS NULL)'); // non-SGA
			$this->db->where('status', 'A'); // approved listings
			$this->db->order_by('starts', 'asc');
			
			// execute query
			$query1 = $this->db->get();
			
			// add to this week's ID array
			$thisweek_ids = array();
			foreach($query1->result() as $row) {
				array_push($thisweek_ids, $row->id);
			}
			$this->entries['thisweek'] = $thisweek_ids;
			
			// get entry IDs for next week from mme_submissions
			$this->db->select('id');
			$this->db->from('mme_submissions');
			$this->db->where('starts >=', $this->secondmonday); // between first monday and...
			$this->db->where('starts <=', $this->lastday); // second monday.
			$this->db->where('(organization_id != 1 OR organization_id IS NULL)'); // non-SGA
			$this->db->where('status', 'A'); // approved listings
			$this->db->order_by('starts', 'asc');
			
			// execute query
			$query2 = $this->db->get();
			
			// add to next week's ID array
			$nextweek_ids = array();
			foreach($query2->result() as $row) {
				array_push($nextweek_ids, $row->id);
			}
			$this->entries['nextweek'] = $nextweek_ids;
			
			// get overrides
			$this->db->select('mme_submissions.id');
			$this->db->from('mme_overrides');
			$this->db->join('mme_submissions', 'mme_overrides.mme_submission_id = mme_submissions.id', 'left');
			$this->db->where('mme_overrides.mme_issue_id', $this->id);
			
			$query3 = $this->db->get();
			
			// add to overrides ID array
			$override_ids = array();
			foreach($query3->result() as $row) {
				array_push($override_ids, $row->id);
			}
			$this->entries['overrides'] = $override_ids;
		}
		
		// get the most recently published issue
		public function most_recent() {
			// build query
			$this->db->select('*');
			$this->db->from('mme_issues');
			$this->db->where('published', 'Y');
			$this->db->order_by('firstmonday', 'desc');
			$this->db->limit(1);
			
			// execute query
			$query = $this->db->get();
			$result = $query->result();
			
			// create object
			$this->id = $result[0]->id;
			$this->firstmonday = $result[0]->firstmonday;
			$this->secondmonday = $result[0]->secondmonday;
			$this->lastday = $result[0]->lastday;
			$this->published = $result[0]->published;
			
			$this->load_issue();
			
			// return
			return $this;
		}
		
		// get an issue by issue ID
		public function get_by_id( $id ) {
			// build query
			$this->db->select('*');
			$this->db->from('mme_issues');
			$this->db->where('published', 'Y');
			$this->db->where('id', $id);
			$this->db->limit(1);
			
			// execute query
			$query = $this->db->get();
			$result = $query->result();
			
			// create object
			$this->id = $result[0]->id;
			$this->firstmonday = $result[0]->firstmonday;
			$this->secondmonday = $result[0]->secondmonday;
			$this->lastday = $result[0]->lastday;
			$this->published = $result[0]->published;
			
			$this->load_issue();
			
			// return
			return $this;
		}
		
		// funciton to create an mme_issues entry as a draft	
		public function create_issue() {
			// TODO: TEST THIS FUNCTION
			$this->firstmonday = strtotime($this->input->post('firstmonday'));
			$this->secondmonday = ($this->input->post('firstmonday') == '') ? $this->firstmonday + 604800 : strtotime($this->input->post('secondmonday'));
			$this->lastday = ($this->input->post('lastday') == '') ? $this->secondmonday + 432000 : strtotime($this->input->post('lastday'));
			$this->published = 'N';
			
			$this->db->insert('mme_issues', $this);
		}
		
		// function to format the HTML for each issue
		public function format_html() {
			$entries = $this->entries_array();
		?>
			<div class="mme">
				<h2>Monday Morning E-mail</h2>
				<h3>Week of <?php echo date('F d, Y', $this->firstmonday); ?></h3>
				
				<p>Greetings BU Law!</p>
				<p>Below please find the SGA Monday Morning E-mail for the week of <?php echo date('F d, Y', $this->firstmonday); ?>.  If you would like to submit to the Monday Morning E-mail, please register at REGISTER-LINK.  The secret code is in the emailed version of the MME.  Remember, <strong>all submissions are due by <u>5pm on Sunday</u>.</strong></p>
				<p>If you have any comments or suggestions, please e-mail us at <a href="mailto:sgalaw@bu.edu">sgalaw@bu.edu</a>.</p>
				<p id="top">What's in this edition?</p>
				
				<ol class="mme-toc">
					<?php 
						foreach($entries as $entrygroup) { 
							foreach($entrygroup as $entry) { 
					?>
						<li><a href="#<?php echo 'mme-'.$entry->id; ?>"><?php echo $entry->title; ?></a></li>
					<?php 
							} //endforeach - entrygroup
						} //endforeach - entries
					?>
				</ol>
				
				<hr class="mme-big-hr" />
				
				<h4 class="mme-heading">Student Government Association</h4>
				<?php
					$counter = 1;
					$i = 0;
					$last = count($entries['sga']);
					foreach($entries['sga'] as $entry) {
						$this->print_entry_html($entry, $counter);
						$counter++;
						$i++;
						if($i != $last) {
							echo '<hr class="mme-small-hr" />';
						}
					}
				?>
				<hr class="mme-medium-hr" />
				<h4 class="mme-heading">This Week</h4>
				<?php
					$i = 0;
					$last = count($entries['thisweek']);
					foreach($entries['thisweek'] as $entry) {
						$this->print_entry_html($entry, $counter);
						$counter++;
						$i++;
						
						if($i != $last) {
							echo '<hr class="mme-small-hr" />';
						}
					}
				?>
				<?php if(!empty($entries['nextweek'])) { ?>
					<hr class="mme-medium-hr" />
					<h4 class="mme-heading">Next Week</h4>
					<?php
						$i = 0;
						$last = count($entries['nextweek']);
						foreach($entries['nextweek'] as $entry) {
							$this->print_entry_html($entry, $counter);
							$counter++;
							$i++;
							
							if($i != $last) {
								echo '<hr class="mme-small-hr" />';
							}
						}
					} // endif
				?>
				
				<hr class="mme-big-hr" />
				
				<p><strong>Again, if you have any questions about the SGA, please write to <a href="mailto:sgalaw@bu.edu">sgalaw@bu.edu</a></strong>. If you would like to submit to the Monday Morning E-mail, please register at REGISTER-LINK. Remember, <strong>all submissions are due by <u>5pm on Sunday</u></strong>.</p>
				<p>Thanks and have a great week!</p>
				<p>All the best,</p>
				<p>Your SGA</p>
			</div>
		<?php
		}
		
		public function print_entry_html($entry, $counter = 1) {
		?>
			<div id="mme-<?php echo $entry->id; ?>">
				<h5 class="mme-title"><?php echo $counter . ') ' . $entry->title; ?></h5>
				<a href="#top" class="back-to-top">(top)</a>
				
				<table class="mme-details">
					<?php if($entry->name) { ?>
						<tr>
							<td class="mme-details-label"><strong>Who:</strong></td>
							<td><?php echo $entry->name; ?></td>
						</tr>
					<?php } ?>
					
					<tr>
						<td class="mme-details-label"><strong>When:</strong></td>
						<td>
						<?php // deal with dates
							$startdate = date('l, F d, Y', $entry->starts);
							$starttime = date('g:i A', $entry->starts);
							$enddate = ($startdate == date('l, F d, Y', $entry->ends)) ? '' : date('l, F d, Y', $entry->ends);
							$endtime = date('g:i A', $entry->ends);
							
							if($enddate == '') {
								if($starttime == $endtime) {
									echo $startdate . ' at ' . $starttime;
								} else {
									echo $startdate . ' from ' . $starttime . ' to ' . $endtime;
								}
							} else {
								echo 'From ' . $startdate . ', ' . $starttime . ' to ' . $enddate . ', ' . $endtime;
							}?>
						</td>
					</tr>
					
					<?php if($entry->location) { ?>
						<tr>
							<td class="mme-details-label"><strong>Where:</strong></td>
							<td><?php echo $entry->location; ?></td>
						</tr>
					<?php } ?>
					
					<tr>
						<td class="mme-details-label"><strong>What:</strong></td>
						<td><?php echo $entry->description; ?></td>
					</tr>
					
					<?php if($entry->link) { ?>
						<tr>
							<td class="mme-details-label"><strong>Website:</strong></td>
							<td><a href="<?php echo $entry->link; ?>"><?php echo $entry->link; ?></a></td>
						</tr>
					<?php } ?>

					<?php if($entry->email) { ?>
						<tr>
							<td class="mme-details-label"><strong>Contact:</strong></td>
							<td><a href="mailto:<?php echo $entry->email; ?>"><?php echo $entry->email; ?></a></td>
						</tr>
					<?php } ?>						
				</table>									
			</div>
			<?php
		}

		// function to fetch all the entries, in order, in an array
		public function entries_array() {
			$temparray = array( 'sga' => array(), 'thisweek' => array(), 'nextweek' => array() );
			
			// add the SGA first
			foreach($this->entries['sga'] as $listing_id) {
				$this->db->select('mme_submissions.*, organizations.name, users.firstname, users.lastname');
				$this->db->from('mme_submissions');
				$this->db->join('organizations', 'mme_submissions.organization_id = organizations.id', 'left'); // join organization to get affiliation
				$this->db->join('users', 'mme_submissions.user_id = users.id', 'left'); // join users to get author
				$this->db->where('mme_submissions.id', $listing_id); // for each entry
				
				$query = $this->db->get();
				$result = $query->result();
				if(!empty($result)) { array_push($temparray['sga'], $result[0]); }
			}
			
			// then add this week's
			foreach($this->entries['thisweek'] as $listing_id) {
				$this->db->select('mme_submissions.*, organizations.name, users.firstname, users.lastname');
				$this->db->from('mme_submissions');
				$this->db->join('organizations', 'mme_submissions.organization_id = organizations.id', 'left'); // join organization to get affiliation
				$this->db->join('users', 'mme_submissions.user_id = users.id', 'left'); // join users to get author
				$this->db->where('mme_submissions.id', $listing_id); // for each entry
				
				$query = $this->db->get();
				$result = $query->result();

				if(!empty($result)) { array_push($temparray['thisweek'], $result[0]); }
			}
			
			// then add next week's
			foreach($this->entries['nextweek'] as $listing_id) {
				$this->db->select('mme_submissions.*, organizations.name, users.firstname, users.lastname');
				$this->db->from('mme_submissions');
				$this->db->join('organizations', 'mme_submissions.organization_id = organizations.id', 'left'); // join organization to get affiliation
				$this->db->join('users', 'mme_submissions.user_id = users.id', 'left'); // join users to get author
				$this->db->where('mme_submissions.id', $listing_id); // for each entry
				
				$query = $this->db->get();
				$result = $query->result();
				if(!empty($result)) { array_push($temparray['nextweek'], $result[0]); }
			}
			
			return $temparray;
			//return $this->entries;
		}
		
		public function fetch_all_issues( $limit = null, $start = null )
		{
			$this->db->select('*')->from('mme_issues');
			$this->db->order_by('firstmonday', 'desc');
			if(isset($limit) && isset($start)) {
				$this->db->limit($limit, $start);
			}
			$result = $this->db->get()->result();
			return $result;
		}
	
		public function issues_count()
		{
			return $this->db->count_all('mme_issues');
		}
	}
?>