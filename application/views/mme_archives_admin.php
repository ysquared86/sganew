<?php echo anchor('admin/manage_mmes/create', 'Create a new issue', 'class="button"'); ?>
<table class="sga-table">
	<tr>
		<th>Issue</th>
		<th>From</th>
		<th>Through</th>
		<th>To</th>
		<th>Published</th>
		<th>Actions</th>
	</tr>
	<?php
		$i = 0;
		foreach($issues as $issue) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $issue->id; ?></td>
			<td><?php echo date('Y-m-d', $issue->firstmonday); ?></td>
			<td><?php echo date('Y-m-d', $issue->secondmonday); ?></td>
			<td><?php echo date('Y-m-d', $issue->lastday); ?></td>
			<td><?php echo $issue->published; ?></td>
			<td><?php echo anchor('admin/single_issue/'.$issue->id, 'View') . ' | ' . anchor('admin/manage_mmes/emailview/'.$issue->id, 'E-mail Version', 'target="_blank"') . ' | ' . anchor('admin/manage_mmes/delete/'.$issue->id, 'Delete');?></td>
		</tr>
	<?php
			$i++;
		}
	?>	
</table>