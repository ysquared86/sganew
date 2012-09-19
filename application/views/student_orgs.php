<table class="sga-table">
	<?php 
		$i = 0;
		foreach($orgs as $org) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
		?>
		<tr class="<?php echo $evenodd; ?>">
			<td>
				<h3><?php echo anchor('resources/view_org/'.$org->id, $org->name, 'class="org-name"'); ?></h3>
				<?php echo $org->description; ?>			
			</td>
		</tr>
	<?php $i++; } //endforeach ?>
</table>