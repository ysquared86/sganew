<div class="errors"><?php echo validation_errors(); ?></div>
<?php
	echo '<h2>'.$edit_user->username.'</h2>';
	echo 'Member since ' . date('F d, Y', $edit_user->created);
	
	echo form_open('home/my_account', array( 'class' => 'sga-form' ) );
	
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
	
	echo form_hidden('id', $edit_user->id);
	
	echo '<br />';
	
	echo form_submit('submit', 'Update Account Information');
	
	echo form_close();
?>