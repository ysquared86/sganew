<p>Resetting password for: <?php echo $user->username; ?></p>

<?php if(validation_errors()) { ?><div class="errors"><?php echo validation_errors(); ?></div><?php } ?>
<?php
	echo form_open('login/reset_pw/' . $user->username, array('class' => 'sga-form'));
	
	echo form_label('New Password', 'password');
	echo form_password('password');
	
	echo form_label('Re-type New Password', 'password1');
	echo form_password('password1');
	
	echo form_label('Security Code', 'token');
	echo form_input('token');
	
	echo form_hidden('id', $user->id);
	
	echo '<br />';
	
	echo form_submit('submit', 'Reset Password');
	
	echo anchor('login/forget_pw', 'Request Security Code', 'class="button"');
	echo form_close();
?>