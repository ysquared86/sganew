<h2>Submissions yet to be published</h2>
<?php if(!empty($submissions)) { ?>
<table class="sga-table">
	<tr>
		<th>Title</th>
		<th>Starts</th>
		<th>Submitted</th>
		<th>Actions</th>
	</tr>
	<?php
		$i = 0;
		foreach( $submissions as $submission ) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $submission->title; ?></td>
			<td><?php if(is_null($submission->starts)) { echo 'N/A'; } else { echo date('F d, Y', $submission->starts); } ?></td>
			<td><?php echo date('F d, Y', $submission->created); ?></td>
			<td><?php echo anchor('mme/my_submissions/'.$submission->id, 'Edit') . ' | ' . anchor('mme/delete_submission/'.$submission->id, 'Delete'); ?></td>
		</tr>
	<?php
			$i++;
		}
	?>
</table>
<?php } else { ?>
<p>You do not have any submissions.</p>
<?php } ?>

<h2>Submissions published in past MMEs</h2>
<?php if(!empty($past_submissions)) { ?>
<table class="sga-table">
	<tr>
		<th>Title</th>
		<th>Starts</th>
		<th>Submitted</th>
		<th>Actions</th>
	</tr>
	<?php
		$i = 0;
		foreach( $past_submissions as $submission ) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $submission->title; ?></td>
			<td><?php if(is_null($submission->starts)) { echo 'N/A'; } else { echo date('F d, Y', $submission->starts); } ?></td>
			<td><?php echo date('F d, Y', $submission->created); ?></td>
			<td><?php echo anchor('mme/my_submissions/'.$submission->id, 'Edit') . ' | ' . anchor('mme/delete_submission/'.$submission->id, 'Delete'); ?></td>
		</tr>
	<?php
			$i++;
		}
	?>
</table>
<?php } else { ?>
<p>You do not have any submisisons.</p>
<?php } ?>