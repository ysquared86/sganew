<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<script src="<?php echo site_url() . 'js/jquery.js'; ?>"></script>
		<script src="<?php echo site_url() . 'js/jquery-ui.js'; ?>"></script>
		<script>
			$(function() {
				$( "input.date" ).datepicker();
			});
		</script>
		
		<?php echo link_tag('css/sga_main.css'); ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="header-wrap">
					<h1 class="title"><?php echo anchor(site_url(), 'BU School of Law SGA'); ?></h1>
					<div id="nav">
						<ul>
							<li><a<?php if($this->session->userdata('nav_items')->active_menu == 'mme') { echo ' class="active"'; } ?>>MME</a>
								<ul>
									<li><?php echo anchor('mme', 'This Week ('.$this->session->userdata('nav_items')->latest_mme_date.')'); ?></li>
									<li><?php echo anchor('mme/archives', 'Archives'); ?></li>
									<?php if($this->session->userdata('nav_items')->logged_in) { ?>
										<li><?php echo anchor('mme/my_submissions', 'My MME Submissions'); ?></li>
									<?php } ?>
									<li><?php echo anchor('mme/submit', 'Submit to MME'); ?></li>
								</ul>
							</li>
							<li><a<?php if($this->session->userdata('nav_items')->active_menu == 'resources') { echo ' class="active"'; } ?>>Student Resources</a>
								<ul>
									<li><?php echo anchor('resources/calendar', 'Events Calendar'); ?></li>
									<li><?php echo anchor('http://www.youngjyoon.com', 'Bar Review', 'target="_blank"'); ?></li>
									<li><?php echo anchor('resources/marketplace', 'Marketplace'); ?></li>
									<li><?php echo anchor('resources/outlines', 'Outline Database'); ?></li>
									<li><?php echo anchor('resources/prof_ratings', 'Professor Ratings'); ?></li>
									<li><?php echo anchor('resources/student_orgs', 'Student Organizations List'); ?></li>									
								</ul>
							</li>
							<li><a<?php if($this->session->userdata('nav_items')->active_menu == 'sga') { echo ' class="active"'; } ?>>SGA</a>
								<ul>
									<li><?php echo anchor('sga/about', 'About Us'); ?></li>
									<li><?php echo anchor('sga/office_hours', 'Office Hours'); ?></li>
									<li><?php echo anchor('sga/documents', 'SGA Documents'); ?></li>
									<li><?php echo anchor('sga/merch', 'BU Law Merchandise'); ?></li>
								</ul>
							</li>
							
							<?php if( $this->session->userdata('nav_items')->sga_liaison ) { ?>
							<li><a<?php if($this->session->userdata('nav_items')->active_menu == 'liaisons') { echo ' class="active"'; } ?>>SGA Liaison</a>
								<ul>
									<li><?php echo anchor('liaisons/edit_orgs', 'Edit Your Organizations'); ?></li>
									<li><?php echo anchor('liaisons/grants', 'Request SGA Grant'); ?></li>
								</ul>
							</li>
							<?php } //endif ?>

							<?php if( $this->session->userdata('nav_items')->admin || $this->session->userdata('nav_items')->sga ) { ?>
							<li><a<?php if($this->session->userdata('nav_items')->active_menu == 'admin') { echo ' class="active"'; } ?>>Admin</a>
								<ul>
									<li><?php echo anchor('admin/manage_users', 'Manage Users'); ?></li>
									<li><?php echo anchor('admin/manage_mmes', 'Manage MME'); ?></li>
									<li><?php echo anchor('admin/manage_outlines', 'Manage Outlines'); ?></li>
									<li><?php echo anchor('admin/manage_orgs', 'Manage Organizations'); ?></li>
									<li><?php echo anchor('admin/liaisons', 'Manage SGA Liaisons'); ?></li>
								</ul>
							</li>
							<?php } //endif?>							
						</ul>
					</div>
					
					<div id="login">
						<?php
							if($this->session->userdata('user')) { ?>
								Welcome, <strong><?php echo anchor('home/my_account', $this->session->userdata('user')->firstname); ?></strong>!
							<?php
								echo form_open('login/logout', array('class' => 'login-form'));
								echo form_hidden('url', current_url());
								echo form_submit('submit', 'Logout');
								echo form_close();
							} else {
								echo form_open('login/submit', array('class' => 'login-form'));
								echo form_label('Username', 'username');
								echo form_input('username');
								echo form_label('Password', 'password');
								echo form_password('password');
								echo form_hidden('url', current_url());
								echo form_submit('submit', 'Login');
								echo form_close();
								echo anchor('login/signup', 'Sign Up for an Account', 'class = "signup"') . ' | Forget your ' . anchor('login/forget_pw', 'password') . ' or ' . anchor('login/forget_username', 'username') . '?' ;
							}
						?>
					</div>
				</div><!-- #header-wrap -->
			</div><!-- #header -->
			
			<div id="content">
				<h1><?php echo $heading; ?></h1>
				<?php if($this->session->flashdata('flash')) { ?><div class="flashdata"><?php echo $this->session->flashdata('flash'); ?></div><?php } ?>