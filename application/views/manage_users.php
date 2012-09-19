<p>Manage users here.</p>

<table class="sga-table">
	<tr>
		<th>Last Name</th>
		<th>First Name</th>
		<th>Username</th>
		<th>Class</th>
		<th>Preferred E-mail</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	<?php
		$i = 0;
		foreach($results as $user) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><?php echo $user->lastname; ?></td>
			<td><?php echo $user->firstname; ?></td>
			<td><?php echo $user->username; ?></td>
			<td><?php echo $user->class; ?></td>
			<td><?php echo $user->email_pref; ?></td>
			<td><?php echo ($user->pending == 'Y') ? 'Pending' : 'Approved'; ?></td>
			<td><?php echo anchor('/admin/edit_user/'.$user->id, 'Edit'); ?><?php if($user->pending == 'Y') { echo ' | ' . anchor('/admin/approve_user/'.$user->id, 'Approve'); } ?></td>
		</tr>
	<?php $i++; } //endforeach ?>
</table>

<?php echo $page_links; ?>