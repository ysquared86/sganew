<p>Please complete the form below to request access to the secure areas of this website. You must enter at least one BU e-mail address to verify your affiliation with Boston University. A confirmation e-mail will be sent to your preferred e-mail address. Please check this e-mail for instructions to complete your sign-up. If you do not receive this e-mail, please contact the webmaster at <a href="mailto:youngo@bu.edu">youngo@bu.edu</a>.</p>

<div class="errors"><?php echo validation_errors(); ?></div>
<?php
	echo form_open('login/signup', array('class' => 'sga-form'));
	
	echo form_label('First Name', 'firstname');
	echo form_input('firstname', set_value('firstname'));
	
	echo form_label('Last Name', 'lastname');
	echo form_input('lastname', set_value('lastname'));
	
	echo form_label('Choose Username', 'username');
	echo form_input('username', set_value('username'));
	
	echo form_label('Choose Password', 'password');
	echo form_password('password');
	
	echo form_label('Retype Password', 'password1');
	echo form_password('password1');
	
	echo form_label('E-mail', 'email');
	echo form_input('email', set_value('email'));
	
	echo form_label('Preferred E-mail', 'email_pref');
	echo form_input('email_pref', set_value('email_pref'));
	
	echo form_label('Phone Number', 'phone');
	echo form_input('phone', set_value('phone'));
	
	echo form_label('Class', 'class');
	echo form_dropdown( 'class', $class_options, set_value('class') );
	
	echo form_submit('submit', 'Sign Up for an Account');
	echo form_close();
?>