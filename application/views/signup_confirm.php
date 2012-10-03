<p>Finalizing registration for: <?php echo $user->username; ?></p>

<?php if(validation_errors()) { ?><div class="errors"><?php echo validation_errors(); ?></div><?php } ?>
<?php
	echo form_open('login/confirm/' . $user->username, array('class' => 'sga-form'));
	
	echo form_label('Security Code', 'token');
	echo form_input('token');
	
	echo form_hidden('id', $user->id);
	
	echo '<br />';
	
	echo form_submit('submit', 'Finalize my registration');
	echo form_close();
?>