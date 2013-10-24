<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
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
					<li>
						<a id="nav_manageusers" href="<?php echo base_url()."manage_users/participants"?>">Manage Users</a>
					</li>
					<li class="active">
						<a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>

		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."settings/general"?>">General Settings</a></li>
			<li class="active"><a href="<?php echo base_url()."settings/categories"?>">Category Settings</a></li>
		</ul>

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
  				echo '<br>';
  			}
  		?>

		<form class="form-horizontal" name="general_settings" id="general_settings" method="post" accept-charset="utf-8" action='<?php echo base_url()."settings/general_settings_form";?>'>
			<div class="row-fluid">
				<div class="span6 offset3">
				<!--	
					<hr class="muted">
					<small class="muted text-center">Section Label</small>

	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					
					<small class="muted text-center">Section Label</small>

	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>


					
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					
					<hr class="muted">
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					<hr class="muted">
					<small class="muted text-center">Section Label</small>

				
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>
				

					
					<small class="muted text-center">Section Label</small>
	    			<div class="control-group">
						<label class="control-label" for="">Control Label</label>
						<div class="controls" name="">
							
						</div>
					</div>

					<hr class="muted">
					<div class="row-fluid">
						<div class="span2 offset5">
							<input type="submit" name="general_settings_submit" class="btn btn-medium wsu_btn" name="save_settings_btn" value="Save Settings"/>
						</div>
					</div>
-->
				</div><!--close settings span-->
			</div><!--close settings row-fluid-->
		</form><!--close form-->
	</div><!--close hero-unit-->
</body>
</html>