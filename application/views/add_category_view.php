<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
</head>
<body>
	<div class="page-header" id="wsu_header">
		<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
            <a class="btn btn-small wsu_btn" id="sign_out_btn" href='<?php echo base_url(). "gerss/logout"; ?>'>Sign Out</a>
		</div>
	</div>

	<div class="navbar wsu_navbar">
		<div class="navbar-inner">
			<a class="btn btn-navbar" id="collapsed_menu_btn" data-toggle="collapse" data-target=".nav-collapse">
				<span class = "icon-th-list"></span>
			</a>
			<div class = "nav-collapse collapse">
				<ul class="nav text-center">
					<li>
						<a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a>
					</li>
					<li>
						<a id="nav_scores" href="#">Scores</a>
					</li>
					<li class="active">
						<a id="nav_manageusers" href="<?php echo base_url()."manage_users/participants"?>">Manage Users</a>
					</li>
					<li>
						<a id="nav_systemsettings" href="#">System Settings</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>

		
		
		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  			//If I successfully added a user, show success div
  			if($this->session->flashdata('success')){
  				echo '<div class="alert alert-success text-center" id="wsu_alert">';
  				echo $this->session->flashdata('success');
  				echo '</div>';
  			}
  			//If there are no errors, show "Fill out form" message
  			else{
  				echo '<p class="span10 offset2">Fill out the form below to add a project category:</p>';
  			}
  		?>

		<form class="form-horizontal" name="add_category" id="add_category" method="post" accept-charset="utf-8" action='<?php echo base_url()."settings/add_category_validation";?>'>
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<div class="span6 offset3">
							<p class="text-center muted"><small>All Fields Are Required</small></p>

			    			<div class="control-group">
			    				<label class="control-label" for="category">Category Name:</label>
			    				<div class="controls inline" name="category">
			    					<input type="text" name="category_name" class="input-large" placeholder="Poster, Oral Presentation, etc."/>
			    				</div>
			    			</div>
			    		</div>
					</div><!--close form column row-fluid-->

					<hr class="muted"/>

					<div class="row-fluid">
						<div class="span12">
							<input type="submit" name="category_submit" class="btn btn-medium wsu_btn" id="submit_category_btn" value="Submit"/>
						</div>
					</div><!--close submit button row-fluid-->
				</div><!--close span12-->
			</div><!--close form's row-fluid-->
		</form><!--close registration form-->
	</div><!--close hero-unit-->
</body>
</html>