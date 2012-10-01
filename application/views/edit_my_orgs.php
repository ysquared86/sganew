<p>You are the SGA Liaison for:</p>

<table class="sga-table">
	<tr>
		<th>Organization</th>
		<th>Actions</th>
	</tr>
<?php
	$i = 0;
	foreach( $my_orgs as $org ) {
		$evenodd = ( $i % 2 == 0 ) ? 'even' : 'odd';
?>
	<tr class="<?php echo $evenodd; ?>">
		<td><?php echo $org->name; ?></td>
		<td><?php echo anchor('liaisons/edit_orgs/'.$org->id, 'Edit'); ?></td>
	</tr>
<?php
	$i++; } // endforeach
?>
</table>