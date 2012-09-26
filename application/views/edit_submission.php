<div class="errors"><?php echo validation_errors(); ?></div>

<?php
	echo form_open('mme/my_submissions/'.$submission->id, array( 'class' => 'sga-form' ) );
	echo form_label('Event Title', 'title');
	echo form_input('title', set_value('title', $submission->title));
	
	if(count($orgs_list) > 0) {
		echo form_label('On behalf of', 'organization_id');
		echo form_dropdown('organization_id', $orgs_list, set_value('organization_id', $submission->organization_id));
	}
	
	echo form_label('No Time', 'no_time');
	echo form_checkbox('no_time', 'Y', is_null($submission->starts));
	
	// FOR START TIME	
	echo form_label('Starts', 'starts_date');
	
	if(!is_null($submission->starts)) {
		$string = date('Y-m-d | g | i | A', $submission->starts);
		$arr = explode(' | ', $string);
		list( $starts_date, $starts_hour, $starts_minute, $starts_ampm ) = $arr;
	} else {
		$starts_date = $starts_hour = $starts_minute = $starts_ampm = null;
	}
	
	// set up date field
	$att = array(
		'id' => 'starts_date',
		'name' => 'starts_date',
		'class' => 'date',
		'value' => set_value('starts_date', $starts_date)
	);
	echo form_input( $att );
	
	// set up hour field
	$options = array();
	for($i = 1; $i <= 12; $i++) {
		$options[$i] = $i;
	}
	echo form_dropdown( 'starts_hour', $options, set_value('starts_hour', $starts_hour) );
	
	// set up minutes field
	$options = array();
	for($i = 0; $i < 60; $i+=5) {
		$ii = sprintf("%02s", $i);
		$options[$ii] = $ii;
	}	
	echo form_dropdown( 'starts_minute', $options, set_value('starts_minute', $starts_minute) );
	
	// set up AMPM
	$options = array( 'AM' => 'AM', 'PM' => 'PM' );
	echo form_dropdown( 'starts_ampm', $options, set_value('starts_ampm', $starts_ampm) );
	
	
	// FOR END TIME
	echo form_label('Ends', 'ends_date');
	
	if(!is_null($submission->ends)) {
		$string = date('Y-m-d | g | i | A', $submission->ends);
		$arr = explode(' | ', $string);
		list( $ends_date, $ends_hour, $ends_minute, $ends_ampm ) = $arr;
	} else {
		$ends_date = $ends_hour = $ends_minute = $ends_ampm = null;
	}
	
	// set up date field
	$att = array(
		'id' => 'ends_date',
		'name' => 'ends_date',
		'class' => 'date',
		'value' => set_value('ends_date', $ends_date)
	);
	echo form_input( $att );
	
	// set up hour field
	$options = array();
	for($i = 1; $i <= 12; $i++) {
		$options[$i] = $i;
	}
	echo form_dropdown( 'ends_hour', $options, set_value('ends_hour', $ends_hour) );
	
	// set up minutes field
	$options = array();
	for($i = 0; $i < 60; $i+=5) {
		$ii = sprintf("%02s", $i);
		$options[$ii] = $ii;
	}	
	echo form_dropdown( 'ends_minute', $options, set_value('ends_minute', $ends_minute) );
	
	// set up AMPM
	$options = array( 'AM' => 'AM', 'PM' => 'PM' );
	echo form_dropdown( 'ends_ampm', $options, set_value('ends_ampm', $ends_ampm) );	
	
	echo form_label( 'Location', 'location' );
	echo form_input( 'location', set_value('location', $submission->location) );
	
	echo form_label( 'Description', 'description' );
	echo form_textarea( 'description', set_value('description', $submission->description) );
	
	echo form_label( 'Website', 'link' );
	echo form_input( 'link', set_value('link', $submission->link) );
	
	echo form_label( 'Contact E-mail', 'email' );
	echo form_input( 'email', set_value('email', $submission->email) );
	
	echo form_hidden( 'id', $submission->id );
	
	echo form_submit('submit', 'Save Changes');
	echo anchor('mme/my_submissions', 'Back to My Submissions', 'class="button"');
	
	echo form_close();
?>