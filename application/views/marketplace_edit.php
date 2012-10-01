<p class="errors"><?php echo validation_errors(); ?></p>
<?php
	echo form_open_multipart('resources/marketplace/edit/'.$listing->id, array('class' => 'sga-form'));
	
	echo form_label('Category', 'mkt_cat_id');
	echo form_dropdown('mkt_cat_id', $categories, set_value('mkt_cat_id', $listing->mkt_cat_id));
	
	echo form_label('Title', 'title');
	echo form_input('title', set_value('title', $listing->title));
	
	echo form_label('Description', 'description');
	echo form_textarea('description', set_value('description', $listing->description));
	
	echo form_label('Condition', 'condition');
	echo form_dropdown('condition', $conditions_array, set_value('condition', $listing->condition));
	
	echo form_label('Price', 'price');
	echo '$ ' . form_input('price', set_value('price', $listing->price));
	
	echo form_label('Image(s)', 'image');
	if($attachments)
	{		
		foreach($attachments as $attachment)
		{
			$img_properties = array(
				'src' => 'uploads/marketplace/'.$attachment->path,
				'class' => 'post_images'
			);
			echo img($img_properties) . '<br />';
			echo form_label('Delete this image', 'delete_img[]');
			echo form_checkbox('delete_img[]', $attachment->id, false);
		}
	}	
	
	echo form_label('Upload New File (JPG/PNG/GIF only)', 'file');
	echo form_upload('file');
		
	if($listing->mkt_cat_id == 1) {
		echo '<hr />';
		echo '<h3>Details for books (optional)</h3>';
		
		echo form_label('Author', 'author');
		echo form_input('author', set_value('author', $listing->author));
		
		echo form_label('Year', 'year');
		echo form_input('year', set_value('year', $listing->year));
		
		echo form_label('Edition', 'edition');
		echo form_input('edition', set_value('edition', $listing->edition));
		
		echo form_label('ISBN', 'isbn');
		echo form_input('isbn', set_value('isbn', $listing->isbn));
	} //endif
	
	echo form_hidden('user_id', $user_id);
	echo form_hidden('id', $listing->id);
	
	echo form_submit('submit', 'Update Listing');

	echo anchor('resources/marketplace/delete/'.$listing->id, 'Delete Listing', 'class="button"');
	echo anchor('resources/marketplace/my_listings', 'Back to My Listings', 'class="button"');
	echo form_close();
?>