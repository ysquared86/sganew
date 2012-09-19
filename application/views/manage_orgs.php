<p>Manage student organizations here.</p>

<table class="sga-table">
	<tr>
		<th>Organization</th>
		<th>Contact</th>
		<th>Account</th>
		<th>Since</th>
		<th>Action</th>
	</tr>
	<?php
		$i = 0;
		foreach($results as $org) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $org->name; ?></td>
			<td><?php echo $org->email; ?></td>
			<td><?php echo $org->account; ?></td>
			<td><?php echo date('Y-m-d', $org->created); ?></td>
			<td><?php echo anchor('/admin/edit_org/'.$org->id, 'Edit'); ?></td>
		</tr>
	<?php $i++; } //endforeach ?>
</table>

<?php echo $page_links; ?>