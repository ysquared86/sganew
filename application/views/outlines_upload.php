<p class="errors"><?php echo validation_errors(); ?></p>
<?php 
	echo anchor('resources/outlines', 'Back to Outline Database', 'class="button top-right"');
	
	echo form_open_multipart('resources/outlines/upload', array('class' => 'sga-form'));
	
	echo form_label('File (PDFs only)', 'file');
	echo form_upload('file');
	
	echo form_label('Course', 'course_id');
	echo form_dropdown('course_id', $courses, set_value('course_id'));
	
	echo form_label('Instructor', 'professor_id');
	echo form_dropdown('professor_id', $profs, set_value('professor_id'));
	
	echo form_label('Semester', 'semester');
	echo form_dropdown('semester', array('Fall' => 'Fall', 'Spring' => 'Spring'), set_value('semester'));
	
	echo form_label('Year', 'year');
	echo form_dropdown('year', $years, set_value('years'));
	
	echo form_hidden('user_id', $this->session->userdata('user')->id);
	
	echo form_submit('submit', 'Upload');
	
	echo form_close();
?>