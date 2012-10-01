<?php 
if(!empty($listings))
{
?>
<table class="sga-table">
	<tr>
		<th>Title</th>
		<th>Category</th>
		<th>Condition</th>
		<th>Price</th>
		<th>Added</th>
		<th>Action</th>
	</tr>
<?php
	$i = 0;
	foreach($listings as $listing) {
		$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
?>
	<tr class="<?php echo $evenodd; ?>">
		<td><?php echo anchor('resources/marketplace/view/'.$listing->id, $listing->title); ?></td>
		<td><?php echo $listing->category; ?></td>
		<td><?php echo $listing->condition; ?></td>
		<td><?php echo '$ ' . $listing->price; ?></td>
		<td><?php echo date('Y-m-d', $listing->created); ?></td>
		<td><?php echo anchor('resources/marketplace/edit/'.$listing->id, 'Edit') . ' | ' . anchor('resources/marketplace/delete/'.$listing->id, 'Delete'); ?></td>
	</tr>
<?php 
		$i++; 
	} //endforeach
?>
</table>
<?php } //endif
else
{
?>
	<p>You currently don't have any listings.</p>
<?php
}
?>
<?php echo $page_links; ?>
<?php echo anchor('resources/marketplace', 'Back to All Listings', 'class="button"'); ?>