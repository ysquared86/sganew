<?php
	echo anchor('resources/outlines/upload', 'Upload an Outline', 'class="button"');
	echo form_open('resources/outlines/search', array('class' => 'sga-horizontal-form'));

	if($this->session->userdata('search_post')) 
	{
		$post = $this->session->userdata('search_post');
		// if it's a search results page
		echo form_label('Number', 'course_number');
		echo form_input('course_number', set_value('course_number', $post['course_number']));
		
		echo form_label('Course', 'course_title');
		echo form_input('course_title', set_value('course_title', $post['course_title']));
		
		echo form_label('Instructor', 'instructor');
		echo form_input('instructor', set_value('instructor', $post['instructor']));
		
		echo form_label('Semester', 'semester');
		echo form_dropdown('semester', array('' => '---', 'Fall' => 'Fall', 'Spring' => 'Spring'), set_value('semester', $post['semester']));
		
		echo form_label('Year', 'year');
		echo form_dropdown('year', $years, set_value('year', $post['year']));
	} 
	else 
	{
		echo form_label('Number', 'course_number');
		echo form_input('course_number', set_value('course_number'));
		
		echo form_label('Course', 'course_title');
		echo form_input('course_title', set_value('course_title'));
		
		echo form_label('Instructor', 'instructor');
		echo form_input('instructor', set_value('instructor'));
		
		echo form_label('Semester', 'semester');
		echo form_dropdown('semester', array('' => '---', 'Spring' => 'Spring', 'Fall' => 'Fall'), set_value('semester'));
		
		echo form_label('Year', 'year');
		echo form_input('year', set_value('year'));
	}
	
	echo form_submit('submit', 'Search Database');
	echo anchor('resources/outlines', 'Clear', 'class="button"');
	
	echo form_close();
?>

<table class="sga-table">
    <tr>
        <th>Number</th>
        <th>Course</th>
        <th>Instructor</th>
        <th>Semester</th>
        <th>Year</th>
        <th>Action</th>
    </tr>
<?php
    $i = 0;
    foreach($results as $outline) {
		$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
?>
    <tr class="<?php echo $evenodd; ?>">
        <td><?php echo $outline->course_number; ?></td>
        <td><?php echo $outline->course_title; ?></td>
        <td><?php echo $outline->instructor; ?></td>
        <td><?php echo $outline->semester; ?></td>
        <td><?php echo $outline->year; ?></td>
        <td><?php echo anchor( site_url() . 'uploads/outlines/' . $outline->path . '.pdf', 'View', 'class="outline-download" target="_blank"') . ' | ' . anchor(site_url() . 'admin/delete_outline/' . $outline->id, 'Delete'); ?></td>
    </tr>
<?php
        $i++;
    }
?>
</table>
<?php echo $page_links; ?>