<p>You do not have the privileges to access the requested page.</p>
<p>Please Login:</p>
<?php
	echo form_open('login/submit', array('class' => 'sga-form'));
	echo form_label('Username', 'username');
	echo form_input('username');
	echo form_label('Password', 'password');
	echo form_password('password');
	echo form_hidden('url', $this->session->userdata('after_login'));
	echo form_submit('submit', 'Login');
	echo form_close();
?>