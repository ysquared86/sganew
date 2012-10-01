<?php echo form_open('admin/liaisons_process'); ?>
<table class="sga-table">
	<tr>
		<th>Organization</th>
		<th>SGA Liaison</th>
	</tr>
	
	<?php
		$i = 0;
		foreach( $orgs as $org_id => $org_name ) {
			$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
		?>
			<tr class="<?php echo $evenodd; ?>">
				<td><?php echo $org_name; ?></td>
				<td>
				<?php
					$liaison_id = isset($orgs_liaisons[$org_id]) ? $orgs_liaisons[$org_id] : '';
					echo form_dropdown( 'liaison_' . $org_id, $users, $liaison_id ); 
				?>
				</td>
			</tr>
		<?php
			$i++;
		}
	
	?>
</table>
<br />
<?php echo form_submit('submit', 'Save Liaisons List'); ?>
<?php echo form_close(); ?>