<div class="errors"><?php echo validation_errors(); ?></div>
<?php
	echo '<h2>'.$org->name.'</h2>';
	echo 'Created ' . date('F d, Y', $org->created);
	
	echo form_open('admin/edit_org', array( 'class' => 'sga-form' ) );
	
	echo form_label('Name', 'name');
	echo form_input('name', set_value('name', $org->name));
	
	echo form_label('Description', 'description');
	echo form_textarea('description', set_value('description', $org->description));
	
	echo form_label('Website', 'website');
	echo form_input('website', set_value('website', $org->website));
	
	echo form_label('SGA Liaison', 'liaison');
	echo form_dropdown( 'liaison', $users, set_value('liaison', $liaison_id) );
	
	echo form_label('Contact E-mail', 'email');
	echo form_input('email', set_value('email', $org->email));
	
	echo form_label('Phone Number', 'phone');
	echo form_input('phone', set_value('phone', $org->phone));
	
	echo form_label('Account Number', 'account');
	echo form_input('account', set_value('account', $org->account));
	
	echo form_label('Status', 'status');
	echo form_dropdown('status', array('active' => 'Active', 'inactive' => 'Inactive'), set_value('status', $org->status));
	
	echo form_hidden('id', $org->id);
	
	echo form_submit('submit', 'Save Organization Info');
	
	echo anchor('admin/manage_orgs', 'Back to List', 'class = "button"');
	
	echo form_close();
	
?>