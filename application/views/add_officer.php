<p>Add an officer to your organization:</p>
<?php
	echo form_open('liaisons/add_officer_process', array( 'class' => 'sga-form' ));
	
	echo form_label('Role', 'role_id');
	echo form_dropdown('role_id', $roles);
	echo form_dropdown('user_id', $users);
	
	echo form_hidden('org_id', $org_id);
	echo form_submit('submit', 'Add Officer');
	
	echo form_close();
?>