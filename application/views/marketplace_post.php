<p class="errors"><?php echo validation_errors(); ?></p>
<?php
	echo form_open_multipart('resources/marketplace/post', array('class' => 'sga-form'));
	
	echo form_label('Category', 'mkt_cat_id');
	echo form_dropdown('mkt_cat_id', $categories, set_value('mkt_cat_id'));
	
	echo form_label('Title', 'title');
	echo form_input('title', set_value('title'));
	
	echo form_label('Description', 'description');
	echo form_textarea('description', set_value('description'));
	
	echo form_label('Condition', 'condition');
	echo form_dropdown('condition', $conditions_array, set_value('condition'));
	
	echo form_label('Price', 'price');
	echo '$ ' . form_input('price', set_value('price'));
	
	echo form_label('Images (JPG/PNG/GIF only)', 'file');
	echo form_upload('file');
	
	echo '<br /><br /><hr />';
	echo '<h3>Details for books (optional)</h3>';
	
	echo form_label('Author', 'author');
	echo form_input('author', set_value('author'));
	
	echo form_label('Year', 'year');
	echo form_input('year', set_value('year'));
	
	echo form_label('Edition', 'edition');
	echo form_input('edition', set_value('edition'));
	
	echo form_label('ISBN', 'isbn');
	echo form_input('isbn', set_value('isbn'));
	
	echo form_hidden('user_id', $user_id);
	echo '<br />';
	
	echo form_submit('submit', 'Submit Listing');
	echo anchor('resources/marketplace', 'Back to All', 'class="button"');
	
	echo form_close();
?>