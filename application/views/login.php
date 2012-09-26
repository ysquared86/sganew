<div class="errors">
	<p>The username / password combination you entered could not be found in the database.</p>
	<p>Please try again or <?php echo anchor('login/signup', 'sign up for an account'); ?>.</p>
</div>

<?php
	echo form_open('/login/submit', array('class' => 'sga-form'));
	echo form_label('Username', 'username');
	echo form_input('username');
	echo form_label('Password', 'password');
	echo form_password('password');
	echo form_hidden('url', $this->session->userdata('after_login'));
	echo form_submit('submit', 'Login');
	echo form_close();
?>

<p>Forgot your password? <?php echo anchor('login/forget_pw', 'Click here.'); ?></p>
<p>Forgot your username? <?php echo anchor('login/forget_username', 'Click here.'); ?></p>