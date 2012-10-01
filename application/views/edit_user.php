<div class="errors"><?php echo validation_errors(); ?></div>
<?php
	echo '<h2>'.$edit_user->username.'</h2>';
	echo 'Member since ' . date('F d, Y', $edit_user->created);
	
	echo form_open('admin/edit_user/'.$edit_user->id, array( 'class' => 'sga-form' ) );
	
	echo form_label('First Name', 'firstname');
	echo form_input('firstname', set_value('firstname', $edit_user->firstname));
	
	echo form_label('Last Name', 'lastname');
	echo form_input('lastname', set_value('lastname', $edit_user->lastname));
	
	echo form_label('E-mail', 'email');
	echo '<div class="unchangeable">'.$edit_user->email.'</div>';
	
	echo form_label('Preferred E-mail', 'email_pref');
	echo form_input('email_pref', set_value('email_pref', $edit_user->email_pref));
	
	echo form_label('Phone Number', 'phone');
	echo form_input('phone', set_value('phone', $edit_user->phone));
	
	echo form_label('Class', 'class');
	echo form_dropdown( 'class', $class_arr, set_value('class', $edit_user->class) );
	
	$positions = $edit_user->positions;
	
	for($i = 0; $i < 5; $i++) {
		$number = $i + 1;
		echo form_label('Position '.$number, 'position'.$number);
		if(isset($positions[$i])) {
			echo form_dropdown( 'position'.$number, $orgs, set_value('position'.$number, $positions[$i]->organization_id) );
			echo form_dropdown( 'role'.$number, $roles, set_value('role'.$number, $positions[$i]->role_id) );
		} else {
			echo form_dropdown( 'position'.$number, $orgs, set_value('position'.$number) );
			echo form_dropdown( 'role'.$number, $roles, set_value('role'.$number) );
		}
	}
	
	echo form_hidden('id', $edit_user->id);
	
	echo form_submit('submit', 'Save User Info');
	
	echo anchor('admin/delete_user/'.$edit_user->id, 'Delete User', 'class="button"');
	echo anchor('admin/manage_users/', 'Back to User Listing', 'class="button"');
	
	echo form_close();
?>