<table class="sga-table">
	<tr>
		<th>Professor</th>
		<th>Overall</th>
		<th>Helpful</th>
		<th>Clear</th>
		<th>Easy</th>
	</tr>
	
	<?php 
		$i = 0;
		foreach($ratings as $rating) { 
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
	<tr class="<?php echo $evenodd; ?>">
		<td><?php echo anchor('resources/prof_ratings/view/'.$rating->professor_id, $rating->lastname . ', ' . $rating->firstname); ?></td>
		<td style="background: <?php echo $rating->overall_rgb; ?>"><?php echo $rating->overall_avg; ?></td>
		<td style="background: <?php echo $rating->helpful_rgb; ?>"><?php echo $rating->helpful_avg; ?></td>
		<td style="background: <?php echo $rating->clear_rgb; ?>"><?php echo $rating->clear_avg; ?></td>
		<td style="background: <?php echo $rating->easy_rgb; ?>"><?php echo $rating->easy_avg; ?></td>
	</tr>
	<?php $i++; } //endforeach ?>
</table>