<?php
class Mme_issues extends CI_Model {
	public $id;
	public $firstmonday = '';
	public $secondmonday = '';
	public $lastday = '';
	public $published = '';
	public $entries_sga;
	public $entries_this_week;
	public $entries_next_week;
	public $entries_overrides;
	public $counter = 1;
	
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('America/New_York');
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
	
	public function fetch_all_published_issues( $limit = null, $start = null )
	{
		$this->db->select('*')->from('mme_issues');
		$this->db->where('published', 'Y');
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
	
	public function insert_issue( $post )
	{
		$issue = array(
			'firstmonday' => strtotime($post['firstmonday']),
			'secondmonday' => strtotime($post['secondmonday']),
			'lastday' => strtotime($post['lastday']),
			'published' => 'N'
		);
		$this->db->insert('mme_issues', $issue);
		return $this->db->insert_id();
	}
	
	public function get_most_recent() {
		$this->db->select('id');
		$this->db->from('mme_issues');
		$this->db->where('published', 'Y');
		$this->db->order_by('firstmonday', 'desc');
		$this->db->limit(1);
		
		$result = $this->db->get()->result();
		$id = $result[0]->id;
		return $this->get_issue_by_id( $id );
	}
	
	public function latest_mme_date() {
		$this->db->select('firstmonday');
		$this->db->from('mme_issues');
		$this->db->where('published', 'Y');
		$this->db->order_by('firstmonday', 'desc');
		$this->db->limit(1);
		$last_mme = $this->db->get()->result();
		return date('Y/m/d', $last_mme[0]->firstmonday);
		//return($last_mme[0]->firstmonday);
	}
	
	public function get_issue_by_id( $id, $admin = false )
	{
		$result = $this->db->get_where('mme_issues', array( 'id' => $id ))->result();
		$result = $result[0];
		
		// Store data
		$this->id = $id;
		$this->firstmonday = $result->firstmonday;
		$this->secondmonday = $result->secondmonday;
		$this->lastday = $result->lastday;
		$this->published = $result->published;
		
		$this->entries_sga = $this->load_entries( $this->firstmonday, $this->secondmonday, $this->lastday, 'sga', $admin );
		$this->entries_this_week = $this->load_entries( $this->firstmonday, $this->secondmonday, $this->lastday, 'this_week', $admin );
		$this->entries_next_week = $this->load_entries( $this->firstmonday, $this->secondmonday, $this->lastday, 'next_week', $admin );
		$this->entries_overrides = $this->load_entries( $this->firstmonday, $this->secondmonday, $this->lastday, 'overrides', $admin );
		
		return $this;
	}
	
	public function load_entries( $firstmonday, $secondmonday, $lastday, $type, $admin = false )
	{
		$this->db->select('s.*, o.name');
		$this->db->from('mme_submissions s');
		$this->db->join('organizations o', 's.organization_id = o.id', 'left');
		
		switch( $type ) {
			case 'sga':
				$this->db->where('s.starts >=', $this->firstmonday); // between first monday and...
				$this->db->where('s.starts <=', $this->lastday); // second monday.
				$this->db->where('s.organization_id', 1); // SGA is ID'ed 1 under organizations table
				break;
			case 'this_week':
				$this->db->where('s.starts >=', $this->firstmonday); // between first monday and...
				$this->db->where('starts <', $this->secondmonday); // second monday.
				$this->db->where('(s.organization_id != 1 OR s.organization_id IS NULL)'); // non-SGA
				break;
			case 'next_week':
				$this->db->where('s.starts >=', $this->secondmonday); // between first monday and...
				$this->db->where('s.starts <=', $this->lastday); // second monday.
				$this->db->where('(s.organization_id != 1 OR s.organization_id IS NULL)'); // non-SGA
				break;
			case 'overrides':
				$this->db->join('mme_overrides r', 'r.mme_submission_id = s.id', 'right');
				$this->db->where('r.mme_issue_id', $this->id);
				break;
		}
		if(!$admin) { $this->db->where('s.status', 'A'); }
		$this->db->order_by('s.starts', 'asc');
		
		return $this->db->get()->result();
	}
	
	public function print_entries( $group = 'this_week', $admin = false )
	{
		$entries = $this->{'entries_' . $group};
		$i = 1; // to tell the end of each array
		$end = count( $entries );
		foreach( $entries as $entry )
		{ ?>
			<div id="mme-<?php echo $entry->id; ?>" class="mme-entry">
				<h5 class="mme-title"><?php echo $this->counter . ') ' . $entry->title; ?></h5>
				<?php if(!$admin) { ?><a href="#top" class="back-to-top">(top)</a><?php } ?>
				
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
				
				<?php if($admin) { ?>
					<?php if($entry->status == 'P') { ?>
					<div class="mme-admin-meta unpublished">
						Submitted <?php echo date('r', $entry->created); ?> | <?php echo anchor('admin/approve_submission/'.$entry->id, 'Approve') . ' | ' . anchor('admin/delete_submission/'.$entry->id, 'Delete');; ?>
					</div>
					<?php } else { ?>
					<div class="mme-admin-meta published">
						Submitted <?php echo date('r', $entry->created); ?> | <?php echo anchor('admin/delete_submission/'.$entry->id, 'Delete');; ?>
					</div>
					<?php } ?>
				<?php } ?>
			</div>
			
			<?php if($i != $end) { ?><hr class="mme-small-hr" /><?php } ?>
		<?php
		$i++;
		$this->counter++;
		} //endforeach
	} //end function
	
	public function print_entries_email( $group = 'this_week' )
	{
		$entries = $this->{'entries_' . $group};
		$i = 1; // to tell the end of each array
		$end = count( $entries );
		foreach( $entries as $entry )
		{ ?>
			<layout label="Text only">
				<table id="mme-<?php echo $entry->id; ?>" class="w580" width="580" cellpadding="0" cellspacing="0" border="0">
					<tbody><tr>
						<td class="w580" width="580">
							<p align="left" class="article-title" style="display: inline;"><singleline label="Title"><?php echo $this->counter . ') ' . $entry->title; ?></singleline></p>
							<a href="#top" style="font-size: 11px;">(top)</a>
							<div align="left" class="article-content" style="margin-top: 10px;">
								<multiline label="Description">
									<table class="mme-details">
										<?php if($entry->name) { ?>
											<tr>
												<td valign="top" width="50px"><strong>Who:</strong></td>
												<td valign="top"><?php echo $entry->name; ?></td>
											</tr>
										<?php } ?>					
										<tr>
											<td valign="top"><strong>When:</strong></td>
											<td valign="top">
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
												<td valign="top"><strong>Where:</strong></td>
												<td valign="top"><?php echo $entry->location; ?></td>
											</tr>
										<?php } ?>
										
										<tr>
											<td valign="top"><strong>What:</strong></td>
											<td valign="top"><?php echo $entry->description; ?></td>
										</tr>
										
										<?php if($entry->link) { ?>
											<tr>
												<td valign="top"><strong>Website:</strong></td>
												<td valign="top"><a href="<?php echo $entry->link; ?>"><?php echo $entry->link; ?></a></td>
											</tr>
										<?php } ?>

										<?php if($entry->email) { ?>
											<tr>
												<td valign="top"><strong>Contact:</strong></td>
												<td valign="top"><a href="mailto:<?php echo $entry->email; ?>"><?php echo $entry->email; ?></a></td>
											</tr>
										<?php } ?>						
									</table>
								</multiline>
							</div>
						</td>
					</tr>
					<tr><td class="w580" width="580" height="10"></td></tr>
				</tbody></table>
			</layout>
			<?php if($i != $end) { ?><hr style="color: #CC0000; background-color: #CC0000; height: 1px; margin-bottom: 16px;" /><?php } ?>
		<?php
		$i++;
		$this->counter++;
		} //endforeach
	} //end function
	
	public function delete_issue( $id )
	{
		$this->db->delete('mme_issues', array('id' => $id));
	}
	
	public function publish_issue( $id )
	{
		$this->db->where('id', $id);
		$this->db->update('mme_issues', array('published' => 'Y'));
	}
	
	public function unpublish_issue( $id )
	{
		$this->db->where('id', $id);
		$this->db->update('mme_issues', array('published' => 'N'));
	}
	
	public function delete_entry( $id ) 
	{
		$this->db->delete('mme_submissions', array('id' => $id));
	}
	
	public function approve_entry( $id )
	{
		$this->db->where('id', $id);
		$this->db->update('mme_submissions', array('status' => 'A'));
	}
	
	public function insert_submission( $post )
	{		
		if( isset($post['no_time']) && $post['no_time'] == 'Y' ) {
			$starts = null;
			$ends = null;
		} else {
			$starts = strtotime( $post['starts_date'] . ' ' . $post['starts_hour'] . ':' . $post['starts_minute'] . ' ' . $post['starts_ampm'] );
			$ends = strtotime( $post['ends_date'] . ' ' . $post['ends_hour'] . ':' . $post['ends_minute'] . ' ' . $post['ends_ampm'] );
		}
		$mme_submission = array(
			'user_id' => $this->session->userdata('user')->id,
			'organization_id' => $post['organization_id'],
			'title' => $post['title'],
			'starts' => $starts,
			'ends' => $ends,
			'location' => $post['location'],
			'description' => $post['description'],
			'link' => $post['link'],
			'email' => $post['email'],
			'status' => 'P',
			'created' => time()
		);
		$this->db->insert('mme_submissions', $mme_submission);
	}
	
	public function fetch_submissions_by( $user_id, $published = false )
	{
		$this->db->select('*');
		$this->db->from('mme_submissions');
		$this->db->where('user_id', $user_id);
		if($published) {
			$this->db->where('starts > ' . strtotime('next monday') . ' OR starts IS NULL');
		} else {
			$this->db->where('starts <= ' . strtotime('next monday') . ' OR starts IS NULL');
		}
		return $this->db->get()->result();
	}
	
	public function fetch_submission( $submission_id )
	{
		$result = $this->db->get_where('mme_submissions', array('id' => $submission_id))->result();
		return $result[0];
	}
	
	public function update_submission( $post )
	{
		if( isset($post['no_time']) && $post['no_time'] == 'Y' ) {
			$starts = null;
			$ends = null;
		} else {
			$starts = strtotime( $post['starts_date'] . ' ' . $post['starts_hour'] . ':' . $post['starts_minute'] . ' ' . $post['starts_ampm'] );
			$ends = strtotime( $post['ends_date'] . ' ' . $post['ends_hour'] . ':' . $post['ends_minute'] . ' ' . $post['ends_ampm'] );
		}
		$mme_submission = array(
			'organization_id' => $post['organization_id'],
			'title' => $post['title'],
			'starts' => $starts,
			'ends' => $ends,
			'location' => $post['location'],
			'description' => $post['description'],
			'link' => $post['link'],
			'email' => $post['email'],
			'status' => 'P',
			'created' => time()
		);
		$this->db->where( array('id' => $post['id'], 'user_id' => $this->session->userdata('user')->id) );
		$this->db->update('mme_submissions', $mme_submission);
	}
	
	public function delete_submission( $id )
	{
		$this->db->delete('mme_submissions', array( 'id' => $id, 'user_id' => $this->session->userdata('user')->id) );
	}
} // end class
?>