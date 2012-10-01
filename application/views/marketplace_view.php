<table class="sga-table">
	<tr>
		<td class="table-label">Title:</td>
		<td><strong><?php echo $listing->title?></strong></td>
	</tr>
	
	<tr>
		<td class="table-label">Price:</td>
		<td><strong><?php echo $listing->price?></strong></td>
	</tr>
	
	<tr>
		<td class="table-label">Category:</td>
		<td><?php echo $listing->category; ?></td>
	</tr>
	
	<?php if(isset($listing->author)) { ?>
	<tr>
		<td class="table-label">Author:</td>
		<td><?php echo $listing->author; ?></td>
	</tr>
	<tr>
		<td class="table-label">Edition:</td>
		<td><?php echo $listing->edition; ?></td>
	</tr>
	<tr>
		<td class="table-label">Year:</td>
		<td><?php echo $listing->year; ?></td>
	</tr>
	<?php } ?>
	
	<tr>
		<td class="table-label">Condition:</td>
		<td><?php echo $listing->condition; ?></td>
	</tr>
	<tr>
		<td class="table-label">Description:</td>
		<td><?php echo $listing->description; ?></td>
	</tr>
	<tr>
		<td class="table-label">Seller Contact:</td>
		<td><?php echo mailto($listing->email_pref); ?></td>
	</tr>
	
	<?php if($attachments) { ?>
	<tr>
		<td class="table-label">Images:</td>
		<td>
			<?php
			foreach($attachments as $attachment)
			{
				$img_properties = array(
					'src' => 'uploads/marketplace/'.$attachment->path,
					'class' => 'post_images'
				);
				echo img($img_properties) . '<br />';
			}
			?>
		</td>
	</tr>
	<?php } ?>
</table>
<br />
<?php echo anchor('resources/marketplace', 'Back to All', 'class="button"'); ?>
<?php
	if( $admin_links ) {
		echo anchor('resources/marketplace/edit/'.$listing->id, 'Edit Listing', 'class="button"');
		echo anchor('resources/marketplace/delete/'.$listing->id, 'Delete Listing', 'class="button"');
	}
?>