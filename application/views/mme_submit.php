<p>This is where you can submit entries to be included in the Monday Morning E-mail ("MME"). All submissions received prior to 5pm on Sunday will be included (pending approval) in the following week's MME. Your entry will only be reviewed for appropriateness; it will not be reviewed for spelling, grammar, accuracy, etc. Please double-check your entry becasue the MME will replicate exactly what you have submitted in the form below.</p>
<p>All submissions will be included in the MME of the week of the event and the week prior to the event. Please do not submit the same event twice.</p>
<p>All submissions will be added to the calendar.</p>
<p>If you would like the MME to state that your submission is on behalf of an organization, you must be that organization's SGA Liaison. If you are not the SGA Liaison, you still may submit on behalf of your organization, but make sure you include your organization's name in the title and/or description.</p>

<?php if(validation_errors()) { ?><div class="errors"><?php echo validation_errors(); ?></div><?php } ?>

<?php
	echo form_open('mme/submit', array( 'class' => 'sga-form' ) );
	echo form_label('Event Title', 'title');
	echo form_input('title', set_value('title'));
	
	if(count($orgs_list) > 0) {
		echo form_label('On behalf of', 'organization_id');
		echo form_dropdown('organization_id', $orgs_list, set_value('organization_id'));
	}
	
	echo '<div class="checkboxes">';
	echo form_label('Check this box if your event has no specified time', 'no_time', array('class' => 'label-noclear'));
	echo form_checkbox('no_time', 'Y', false);
	echo '</div>';
	
	// FOR START TIME	
	echo form_label('Starts', 'starts_date');
	
	// set up date field
	$att = array(
		'id' => 'starts_date',
		'name' => 'starts_date',
		'class' => 'date',
		'value' => set_value('starts_date')
	);
	echo form_input( $att );
	
	// set up hour field
	$options = array();
	for($i = 1; $i <= 12; $i++) {
		$options[$i] = $i;
	}
	echo form_dropdown( 'starts_hour', $options, set_value('starts_hour') );
	
	// set up minutes field
	$options = array();
	for($i = 0; $i < 60; $i+=5) {
		$ii = sprintf("%02s", $i);
		$options[$ii] = $ii;
	}	
	echo form_dropdown( 'starts_minute', $options, set_value('starts_minute') );
	
	// set up AMPM
	$options = array( 'AM' => 'AM', 'PM' => 'PM' );
	echo form_dropdown( 'starts_ampm', $options, set_value('starts_ampm') );
	
	
	// FOR END TIME
	echo form_label('Ends', 'ends_date');
	// set up date field
	$att = array(
		'id' => 'ends_date',
		'name' => 'ends_date',
		'class' => 'date',
		'value' => set_value('ends_date')
	);
	echo form_input( $att );
	
	// set up hour field
	$options = array();
	for($i = 1; $i <= 12; $i++) {
		$options[$i] = $i;
	}
	echo form_dropdown( 'ends_hour', $options, set_value('ends_hour') );
	
	// set up minutes field
	$options = array();
	for($i = 0; $i < 60; $i+=5) {
		$ii = sprintf("%02s", $i);
		$options[$ii] = $ii;
	}	
	echo form_dropdown( 'ends_minute', $options, set_value('ends_minute') );
	
	// set up AMPM
	$options = array( 'AM' => 'AM', 'PM' => 'PM' );
	echo form_dropdown( 'ends_ampm', $options, set_value('ends_ampm') );	
	
	echo form_label( 'Location', 'location' );
	echo form_input( 'location', set_value('location') );
	
	echo form_label( 'Description', 'description' );
	echo form_textarea( 'description', set_value('description') );
	
	echo form_label( 'Website', 'link' );
	echo form_input( 'link', set_value('link') );
	
	echo form_label( 'Contact E-mail', 'email' );
	echo form_input( 'email', set_value('email') );
	
	echo '<br />';
	
	echo form_submit('submit', 'Submit to MME');
	echo form_close();
?>