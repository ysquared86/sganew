<div class="top-right">
	<?php echo anchor('resources/marketplace/post', 'Sell an Item', 'class="button"'); ?>
	<?php echo anchor('resources/marketplace/my_listings', 'My Listings', 'class="button"'); ?>
</div>
<?php
	echo form_open('resources/marketplace/search', array('class' => 'sga-horizontal-form'));
	
	$blank = array(
		'search' => '',
		'cat_id' => '',
		'price_min' => '',
		'price_max' => '',
		'sort_by' => ''
	);
	
	$sort_by = array(
		'created' => 'Date Added',
		'title' => 'Title',
		'price_lth' => 'Price - Low to High',
		'price_htl' => 'Price - High to Low'			
	);
	
	$post = ($this->session->userdata('search_post')) ? $this->session->userdata('search_post') : $blank;
	
	echo form_label('Search', 'search');
	echo form_input('search', set_value('search', $post['search']));
	
	echo form_label('In', 'cat_id');
	echo form_dropdown('cat_id', $categories, set_value('cat_id', $post['cat_id']));
	
	echo form_label('Price', 'price_min');
	echo '$ ' . form_input('price_min', set_value('price_min', $post['price_min']), 'placeholder="min" class="short-input"');
	echo '- ';
	echo form_input('price_max', set_value('price_max', $post['price_max']), 'placeholder="max" class="short-input"');
	
	echo form_label('Sort By', 'sort_by');
	echo form_dropdown('sort_by', $sort_by, set_value('sort_by', $post['sort_by']));
	
	echo form_submit('submit', 'Search');
	echo anchor('resources/marketplace/clear', 'Clear', 'class="button"');
	
	echo form_close();
?>

<?php if(!empty($listings)) { ?>
<table class="sga-table">
	<tr>
		<th>Title</th>
		<th>Author</th>
		<th>Edition</th>
		<th>Year</th>
		<th>Condition</th>		
		<th class="th-price">Price</th>
		<th>Added</th>
	</tr>
<?php
	$i = 0;
	foreach($listings as $listing) {
		$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
?>
	<tr class="<?php echo $evenodd; ?>">
		<td><?php echo anchor('resources/marketplace/view/'.$listing->id, $listing->title); ?></td>
		<td><?php echo $listing->author; ?></td>
		<td><?php echo $listing->edition; ?></td>
		<td><?php echo $listing->year; ?></td>
		<td><?php echo $listing->condition; ?></td>
		<td><?php echo '$ ' . $listing->price; ?></td>
		<td><?php echo date('Y-m-d', $listing->created); ?></td>
	</tr>
<?php 
		$i++; 
	}
?>
</table>
<?php echo $page_links; ?>
<?php } //endif


else
{
?>
	<p>No results found.</p>
<?php
}
?>