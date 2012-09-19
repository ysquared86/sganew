<strong>Description:</strong>
<p>
	<?php echo $org->description; ?>
</p>

<strong>Website:</strong>
<p>
	<?php if(isset($org->website)) { echo anchor($org->website, $org->website, 'target="_blank"'); } else { echo 'N/A'; } ?>
</p>

<strong>Contact:</strong>
<p>
	<?php if(isset($org->email)) { echo mailto($org->email, $org->email); } else { echo 'N/A'; } ?>
</p>

<?php echo anchor('resources/student_orgs', 'Back to List', 'class="button"'); ?>