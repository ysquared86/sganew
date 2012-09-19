<table class="sga-table">
	<tr>
		<th>Issues</th>
	</tr>
	<?php 
		$i = 0;
		foreach($archive_array as $issue) : 
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
	?>
		<tr class="<?php echo $evenodd; ?>">
			<td><a href="<?php echo site_url(); ?>mme/archives/<?php echo $issue->id; ?>">Week of <?php echo date('F d, Y', $issue->firstmonday); ?></a></td>
		</tr>
	<?php 
			$i++;
		endforeach; 
	?>
</table>