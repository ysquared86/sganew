<?php
	echo form_open('liaisons/edit_orgs_process', array( 'class' => 'sga-form' ) );
	
	echo form_label('Description', 'description');
	echo form_textarea('description', set_value('description', $org->description));
	
	echo form_label('Website', 'website');
	echo form_input('website', set_value('website', $org->website));
	
	echo form_label('Contact E-mail', 'email');
	echo form_input('email', set_value('email', $org->email));
	
	echo form_label('Phone Number', 'phone');
	echo form_input('phone', set_value('phone', $org->phone));
	
	echo form_hidden('org_id', $org->id);
	
	echo form_label('Officers', 'role1');
	foreach($officers as $i => $officer) {
		echo form_dropdown('role1', $roles, set_value('role1', $officer->role_id));
		echo form_dropdown('user1', $users, set_value('user1', $officer->user_id));
		echo anchor('liaisons/delete_uor/'.$org->id.'/'.$officer->id, 'Delete', 'class="button"');
		echo '<br />';
	}
	
	echo form_submit('submit', 'Save Organization Info');
	
	echo anchor('liaisons/edit_orgs', 'Back to List', 'class = "button"');
	echo anchor('liaisons/add_officer/'.$org->id, 'Add Officer', 'class="button"');
	
	echo form_close();
?>