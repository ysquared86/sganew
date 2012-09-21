<?php
	echo anchor('resources/prof_ratings', 'Back to Ratings List', 'class="button"');
	echo form_open('resources/prof_ratings/add', array( 'class' => 'sga-form' ));
?>
	<div class="ratings-left">
		<table class="ratings-table">
			<tr>
				<th>Professor was...</th>
				<th>1<br />(lowest)</th>
				<th>2</th>
				<th>3</th>
				<th>4</th>
				<th>5<br />(highest)</th>
			</tr>
			
			<tr>
				<td><?php echo form_label('Helpful', 'helpful'); ?></td>
				<td><?php echo form_radio('helpful', 1); ?></td>
				<td><?php echo form_radio('helpful', 2); ?></td>
				<td><?php echo form_radio('helpful', 3); ?></td>
				<td><?php echo form_radio('helpful', 4); ?></td>
				<td><?php echo form_radio('helpful', 5, true); ?></td>
			</tr>
			
			<tr>
				<td><?php echo form_label('Clear', 'clear'); ?></td>
				<td><?php echo form_radio('clear', 1); ?></td>
				<td><?php echo form_radio('clear', 2); ?></td>
				<td><?php echo form_radio('clear', 3); ?></td>
				<td><?php echo form_radio('clear', 4); ?></td>
				<td><?php echo form_radio('clear', 5, true); ?></td>
			</tr>
			
			<tr>
				<td><?php echo form_label('Easy', 'easy'); ?></td>
				<td><?php echo form_radio('easy', 1); ?></td>
				<td><?php echo form_radio('easy', 2); ?></td>
				<td><?php echo form_radio('easy', 3); ?></td>
				<td><?php echo form_radio('easy', 4); ?></td>
				<td><?php echo form_radio('easy', 5, true); ?></td>
			</tr>
			
			<tr>
				<td><?php echo form_label('Overall', 'overall'); ?></td>
				<td><?php echo form_radio('overall', 1); ?></td>
				<td><?php echo form_radio('overall', 2); ?></td>
				<td><?php echo form_radio('overall', 3); ?></td>
				<td><?php echo form_radio('overall', 4); ?></td>
				<td><?php echo form_radio('overall', 5, true); ?></td>
			</tr>
		</table>
		
		<?php 
			echo form_label('Class', 'class');
			echo form_dropdown('class', $courses, set_value('class'));
			
			echo form_label('Semester', 'semester');
			echo form_dropdown('semester', array('Fall' => 'Fall', 'Spring' => 'Spring'), set_value('semester'));
			
			echo form_label('Year', 'year');
			echo form_dropdown('year', $years, set_value('year'));
						
			echo form_label('Comments', 'comments');
			echo form_textarea('comments');
			
			echo form_hidden('professor_id', $ratings->professor_id);
			echo form_submit('submit', 'Submit Ratings');
			echo form_close();
		?>

	</div>
<?php var_dump($ratings); ?>