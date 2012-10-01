<div class="home-panels">
	<div class="home-panel">
		<h2>Monday Morning E-mail</h2>
		<ul>
			<li><?php echo anchor('mme', 'This Week ('.$this->session->userdata('nav_items')->latest_mme_date.')'); ?></li>
			<li><?php echo anchor('mme/archives', 'Archives'); ?></li>
			<?php if($this->session->userdata('nav_items')->logged_in) { ?>
				<li><?php echo anchor('mme/my_submissions', 'My MME Submissions'); ?></li>
			<?php } ?>
			<li><?php echo anchor('mme/submit', 'Submit to MME'); ?></li>
		</ul>	
	</div>
	
	<div class="home-panel">
		<h2>Student Resources</h2>
		<ul>
			<li><?php echo anchor('resources/calendar', 'Events Calendar'); ?></li>
			<li><?php echo anchor('http://www.youngjyoon.com', 'Bar Review', 'target="_blank"'); ?></li>
			<li><?php echo anchor('resources/marketplace', 'Marketplace'); ?></li>
			<li><?php echo anchor('resources/outlines', 'Outline Database'); ?></li>
			<li><?php echo anchor('resources/prof_ratings', 'Professor Ratings'); ?></li>
			<li><?php echo anchor('resources/student_orgs', 'Student Organizations List'); ?></li>									
		</ul>
	</div>
	
	<div class="home-panel">
		<h2>SGA</h2>
		<ul>
			<li><?php echo anchor('sga/about', 'About Us'); ?></li>
			<li><?php echo anchor('sga/office_hours', 'Office Hours'); ?></li>
			<li><?php echo anchor('sga/documents', 'SGA Documents'); ?></li>
			<li><?php echo anchor('sga/merch', 'BU Law Merchandise'); ?></li>
		</ul>
	</div>
	
	<div class="clear"></div>
</div>