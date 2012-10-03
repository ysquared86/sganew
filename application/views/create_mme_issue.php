<?php if(validation_errors()) { ?><div class="errors"><?php echo validation_errors(); ?></div><?php } ?>
<?php
	echo form_open('admin/manage_mmes/create', array( 'class' => 'sga-form' ));
	
	echo form_label('First Monday', 'firstmonday');
	$att = array(
		'id' => 'firstmonday',
		'name' => 'firstmonday',
		'class' => 'date',
		'value' => set_value('firstmonday')
	);
	echo form_input( $att );
	
	echo form_label('Second Monday', 'secondmonday');
	$att = array(
		'id' => 'secondmonday',
		'name' => 'secondmonday',
		'class' => 'date',
		'value' => set_value('secondmonday')
	);
	echo form_input( $att );
	
	echo form_label('Last Day', 'lastday');
	$att = array(
		'id' => 'lastday',
		'name' => 'lastday',
		'class' => 'date',
		'value' => set_value('lastday')
	);
	echo form_input( $att );
	echo '<br />';
	echo form_submit('submit', 'Create Issue');
	echo form_close();
?>