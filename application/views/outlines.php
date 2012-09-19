<?php
	$blank = array();
	$post = ($this->session->userdata('search_post')) ? $this->session->userdata('search_post') : $blank;
	
	var_dump($post);
	echo form_open('resources/outlines/search', array('class' => 'sga-horizontal-form'));

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
	
	echo form_submit('submit', 'Search Database');
	
	echo form_close();
?>

<table class="sga-table">
    <tr>
        <th>Number</th>
        <th>Course</th>
        <th>Instructor</th>
        <th>Semester</th>
        <th>Year</th>
        <th>Link</th>
        <th>Uploaded</th>
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
        <td><?php echo anchor( site_url() . 'uploads/outlines/' . $outline->path . '.pdf', 'Download', 'class="outline-download" target="_blank"'); ?></td>
        <td><?php echo date('Y-m-d', $outline->created) . ' by ' . $outline->firstname . ' ' . $outline->lastname; ?></td>
    </tr>
<?php
        $i++;
    }
?>
</table>
<?php echo $page_links; ?>