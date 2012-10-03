<p>For security reasons, we cannot provide your password through your web browser.</p>
<p>Please enter your username and e-mail address below. This e-mail address must be associated with an account on this website.</p>
<p>When you submit the form, a security code will be sent to the specified e-mail address. By clicking this link, you will be directed to a page on which you may reset your password.</p>

<?php if(validation_errors()) { ?><div class="errors"><?php echo validation_errors(); ?></div><?php } ?>
<?php
	echo form_open('login/forget_pw', array('class' => 'sga-form'));
	
	echo form_label('Username', 'username');
	echo form_input('username', set_value('username'));
	
	echo form_label('E-mail Address', 'email');
	echo form_input('email', set_value('email'));
	
	echo form_submit('submit', 'Send me the security code');
	echo form_close();
?>