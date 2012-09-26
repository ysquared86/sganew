<?php
	echo form_open('login/forget_username', array('class' => 'sga-form'));
	
	echo form_label('Your E-mail Address', 'email');
	echo form_input('email', set_value('email'));
	
	echo form_submit('submit', 'Retrieve my username!');
	
	echo form_close();
?>
<p><?php echo $string; ?></p>