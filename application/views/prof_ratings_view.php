<?php echo anchor('resources/prof_ratings', 'Back to Ratings List', 'class="button top-right"'); ?>
	
<div id="ratings-left">
<?php
	echo form_open('resources/prof_ratings/add', array( 'class' => 'sga-form' ));
?>
		<table class="ratings-table">
			<tr>
				<th>S/he was...</th>
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
			echo form_dropdown('course_id', $courses, set_value('course_id'), 'class="short-select"');
			
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
	
	<div id="ratings-right">
		<div id="ratings-numbers">
			<div class="ratings-box" style="background: <?php echo $ratings->overall_rgb; ?>">
				<h3>Overall</h3>
				<p><?php echo $ratings->overall_avg; ?></p>
			</div>
			<div class="ratings-box" style="background: <?php echo $ratings->helpful_rgb; ?>">
				<h3>Helpful</h3>
				<p><?php echo $ratings->helpful_avg; ?></p>
			</div>
			<div class="ratings-box" style="background: <?php echo $ratings->clear_rgb; ?>">
				<h3>Clear</h3>
				<p><?php echo $ratings->clear_avg; ?></p>
			</div>
			<div class="ratings-box" style="background: <?php echo $ratings->easy_rgb; ?>">
				<h3>Easy</h3>
				<p><?php echo $ratings->easy_avg; ?></p>
			</div>
			<div class="clear"></div>
		</div><!-- .ratings-numbers -->
		
		<div id="ratings-comments">
			<h3>Comments</h3>
			<?php 
				if($comments) {
					foreach($comments as $comment) { ?>
					<div class="ratings-comment">
						<h4>Class: <?php echo $comment->course_title; ?> (<?php echo $comment->semester . ' ' . $comment->year; ?>)</h4>
						<p><?php echo $comment->comments; ?></p>
						<p class="ratings-comment-meta">Reviewed: <?php echo date('F d, Y', $comment->created) . ' at ' . date('g:i a', $comment->created); ?></p>
						<div class="clear"></div>
					</div>
			<?php 
					} //endforeach 
				} //endif 
				else {
			?>
					<div class="ratings-comment">
						<p>There are no comments for this professor.</p>
					</div>
			<?php
				}
			?>
		</div>
	</div>
	<div class="clear"></div>