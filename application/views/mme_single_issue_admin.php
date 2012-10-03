<div class="mme">
	<h2>Monday Morning E-mail</h2>
	<h3>Week of <?php echo date('F d, Y', $issue->firstmonday); ?></h3>
	
	<p>Greetings BU Law!</p>
	<p>Below please find the SGA Monday Morning E-mail for the week of <?php echo date('F d, Y', $issue->firstmonday); ?>.  If you would like to submit to the Monday Morning E-mail, please register  <?php echo anchor('login/signup', 'here'); ?>.  Remember, <strong>all submissions are due by <u>5pm on Sunday</u>.</strong></p>
	<p>If you have any comments or suggestions, please e-mail us at <?php echo mailto('sgalaw@bu.edu'); ?></a>.</p>
	
	<p id="top">What's in this edition?</p>
	<ol class="mme-toc">
		<?php foreach( $issue->entries_sga as $entry ) { ?>
			<li><a href="#<?php echo 'mme-'.$entry->id; ?>"><?php echo $entry->title; ?></a></li>
		<?php } ?>
		<?php foreach( $issue->entries_this_week as $entry ) { ?>
			<li><a href="#<?php echo 'mme-'.$entry->id; ?>"><?php echo $entry->title; ?></a></li>
		<?php } ?>
		<?php foreach( $issue->entries_next_week as $entry ) { ?>
			<li><a href="#<?php echo 'mme-'.$entry->id; ?>"><?php echo $entry->title; ?></a></li>
		<?php } ?>
		<?php foreach( $issue->entries_overrides as $entry ) { ?>
			<li><a href="#<?php echo 'mme-'.$entry->id; ?>"><?php echo $entry->title; ?></a></li>
		<?php } ?>
	</ol>
	
	<hr class="mme-big-hr" />
	
	<?php if(!empty($issue->entries_sga)) { ?>
		<h4 class="mme-heading">Student Government Association</h4>
		<?php $issue->print_entries( 'sga', true ); ?>
		<hr class="mme-medium-hr" />
	<?php } ?>
	
	<?php if(!empty($issue->entries_sga)) { ?>
		<h4 class="mme-heading">This Week</h4>
		<?php $issue->print_entries( 'this_week', true ); ?>
		<hr class="mme-medium-hr" />
	<?php } ?>
	
	<?php if(!empty($issue->entries_next_week)) { ?>
		<h4 class="mme-heading">Next Week</h4>
		<?php $issue->print_entries( 'next_week', true ); ?>
		<hr class="mme-big-hr" />
	<?php } ?>
	
	<?php if(!empty($issue->entries_overrides)) { ?>
	<?php $issue->print_entries( 'overrides', true ); ?>
	<?php } ?>
	
	<p><strong>Again, if you have any questions about the SGA, please write to <?php echo mailto('sgalaw@bu.edu'); ?></strong>. If you would like to submit to the Monday Morning E-mail, please register <?php echo anchor('login/signup', 'here'); ?>. Remember, <strong>all submissions are due by <u>5pm on Sunday</u></strong>.</p>
	<p>Thanks and have a great week!</p>
	<p>All the best,</p>
	<p>Your SGA</p>
</div>

<?php if($issue->published == 'Y') { ?>
<div class="mme-admin-pub published">
	<p>This issue has been published. <?php echo anchor('admin/unpublish_issue/'.$issue->id, 'Unpublish', 'class="button"'); ?> <?php echo anchor('admin/delete_issue/'.$issue->id, 'Delete', 'class="button"'); ?> <?php echo anchor('admin/mme_emailpreview/'.$issue->id, 'View E-mail Version', 'class="button" target="_blank"'); ?></p>
</div>
<?php } else { ?>
<div class="mme-admin-pub unpublished">
	<p>This issue has not been published. <?php echo anchor('admin/publish_issue/'.$issue->id, 'Publish', 'class="button"'); ?> <?php echo anchor('admin/overrides/'.$issue->id, 'Edit Overrides', 'class="button"'); ?></p>
</div>
<?php } ?>