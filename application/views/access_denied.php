<p>You do not have the privileges to access the requested page.</p>
<p>Please login or <?php echo anchor('login/signup', 'sign up for an account'); ?>.</p>
<?php
	echo form_open('login/submit', array('class' => 'sga-form'));
	echo form_label('Username', 'username');
	echo form_input('username', '', 'class="login-page"');
	echo form_label('Password', 'password');
	echo form_password('password', '', 'class="login-page"');
	echo form_hidden('url', $this->session->userdata('after_login'));
	
	echo '<br />';
	
	echo form_submit('submit', 'Login');
	echo form_close();
?>
<p>Forgot your password? <?php echo anchor('login/forget_pw', 'Click here.'); ?></p>
<p>Forgot your username? <?php echo anchor('login/forget_username', 'Click here.'); ?></p>