<strong>Description:</strong>
<p>
	<?php if(isset($org->description)) { echo $org->description; } else { echo 'N/A'; } ?>
</p>

<strong>Website:</strong>
<p>
	<?php if(isset($org->website)) { echo anchor($org->website, $org->website, 'target="_blank"'); } else { echo 'N/A'; } ?>
</p>

<strong>Contact:</strong>
<p>
	<?php if(isset($org->email)) { echo mailto($org->email, $org->email); } else { echo 'N/A'; } ?>
</p>

<strong>Officers:</strong>
<p>
<table class="sga-table">
	<?php if(!empty($officers)) { 
		foreach($officers as $officer)
		{ ?>
			<tr>
				<td class="role"><?php echo $officer->role; ?></td>
				<td><?php echo mailto($officer->email, $officer->firstname . ' ' .$officer->lastname); ?></td>
			</tr>
		<?php }
	} else { ?>
		N/A
	<?php } ?>
</table>
</p>

<?php echo anchor('resources/student_orgs', 'Back to List', 'class="button"'); ?>