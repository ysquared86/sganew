<?php
	echo form_open('admin/overrides_process/'.$issue->id);
?>
	<table class="sga-table">
		<tr>
			<th width="150px">Title</th>
			<th>Details</th>
			<th width="50px">Add</th>
			<th width="50px">Delete</th>
		</tr>
		<?php
			$i = 0;
			foreach( $entries_wo_time as $row ) {
				$evenodd = ($i % 2 == 0) ? 'even' : 'odd';
				$checked = in_array($row->id, $in_this_issue);
		?>
			<tr class="<?php echo $evenodd; ?>">
				<td><?php echo $row->title; ?></td>
				<td><?php echo $row->description; ?></td>
				<td style="text-align: center;"><?php echo form_checkbox('add_overrides[]', $row->id, $checked); ?></td>
				<td><?php echo anchor('admin/delete_submission/'.$row->id, 'Delete'); ?></td>
			</tr>
		<?php
			}
		?>
	</table>
	<br />
<?php
	echo form_submit('submit', 'Save overrides for this issue');
	echo anchor('admin/single_issue/'.$issue->id, 'Back to Issue', 'class="button"');
	echo form_close();
?>