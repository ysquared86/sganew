<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php echo $title; ?></title>
		<?php echo link_tag('css/sga_main.css'); ?>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<div id="header-wrap">
					<h1 class="title"><a href="<?php echo site_url(); ?>">BU LAW SGA SITE</a></h1>
					<div id="nav">
						<ul>
							<li><a>MME</a>
								<ul>
									<li><a href="<?php echo site_url(); ?>mme/">This Week (<?php echo $this->session->userdata('latest_mme_date'); ?>)</a></li>
									<li><a href="<?php echo site_url(); ?>mme/archives">Archives</a></li>
									<li><a href="<?php echo site_url(); ?>mme/submit">Submit to MME</a></li>
								</ul>
							</li>
							<li><a>Student Resources</a>
								<ul>
									<li><a href="<?php echo site_url(); ?>resources/calendar">Events Calendar</a></li>
									<li><a href="http://www.youngjyoon.com" target="_blank">Bar Review</a></li>
									<li><a href="<?php echo site_url(); ?>resources/marketplace">Marketplace</a></li>
									<li><a href="<?php echo site_url(); ?>resources/outlines">Outline Database</a></li>
									<li><a href="<?php echo site_url(); ?>resources/rate_prof">Rate Professors</a></li>
									<li><a href="<?php echo site_url(); ?>resources/student_orgs">Student Organizations List</a></li>
								</ul>
							</li>
							<li><a>SGA</a>
								<ul>
									<li><a href="<?php echo site_url(); ?>sga/about">About Us</a></li>
									<li><a href="<?php echo site_url(); ?>sga/office_hours">Office Hours</a></li>
									<li><a href="<?php echo site_url(); ?>sga/documents">SGA Documents</a></li>
									<li><a href="<?php echo site_url(); ?>sga/merch">BU Law Merchandise</a></li>
								</ul>
							</li>
							
							<?php if( in_array('sga_liaison', $this->session->userdata('nav_items')) ) { ?>
							<li><a>SGA Liaison</a>
								<ul>
									<li><a href="<?php echo site_url(); ?>liaison/edit_orgs">Edit Your Organizations</a></li>
									<li><a href="<?php echo site_url(); ?>liaison/grants">Request SGA Grant</a></li>
								</ul>
							</li>
							<?php } //endif ?>

							<?php if( in_array('admin', $this->session->userdata('nav_items')) ){ ?>
							<li><a>Admin</a>
								<ul>
									<li><a href="<?php echo site_url(); ?>admin/manage_users">Manage Users</a></li>
									<li><a href="#">Manage MME</a></li>
									<li><a href="<?php echo site_url(); ?>admin/manage_outlines">Manage Outlines</a></li>
									<li><a href="<?php echo site_url(); ?>admin/manage_orgs">Manage Organizations</a></li>
									<li><a href="<?php echo site_url(); ?>admin/liaisons">Liaisons</a></li>
								</ul>
							</li>
							<?php } //endif?>							
						</ul>
					</div>
					
					<div id="login">
						<?php
							if($this->session->userdata('user')) { ?>
								Welcome, <strong><?php echo $this->session->userdata('user')->firstname; ?></strong>!
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
								echo ' | '. anchor('login/signup', 'Sign Up for an Account', 'class = "signup"');
							}
						?>
					</div>
				</div><!-- #header-wrap -->
			</div><!-- #header -->
			
			<div id="content">
				<h1><?php echo $heading; ?></h1>
				<div class="flashdata"><?php echo $this->session->flashdata('flash'); ?></div>