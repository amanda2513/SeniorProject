<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'datepicker.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-timepicker.min.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap-datepicker.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap-timepicker.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			//ready datepicker for all datepickers by referencing enclosing div classes
			$('.input-append.date').datepicker();

			//ready timepicker for exhibition start time by referencing input id
			$('#exhib_start').timepicker({
				showInputs:false,
				disableFocus:false,
				defaultTime: false
			});

			//ready timepicker for exhibition end time by referencing input id
			$('#exhib_end').timepicker({
				showInputs:false,
				disableFocus:false,
				defaultTime: false
			});
		});
	</script>

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
			<li class="active"><a href="<?php echo base_url()."settings/general"?>">General Settings</a></li>
			<li><a href="<?php echo base_url()."settings/categories"?>">Category Settings</a></li>
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
					
					<hr class="muted">
					<small class="muted text-center">Exhibition Date, Time &amp; Location</small>
					<!--bootstrap-datepicker for exhibition date-->
	    			<div class="control-group">
						<label class="control-label" for="exhib_date">Date: </label>
						<div class="controls" name="exhib_date">
							<div class="input-append date" id="exhib_datepicker" data-date="<?php echo $exhib_date['cal_selected']?>" data-date-format="m/d/yy">
								<input size="16" type="text" id="exhib_date" name="exhib_date" value="<?php echo set_value('exhib_date',$exhib_date['input'])?>" readonly>
								<span class="add-on"><i class="icon-calendar"></i></span>
							</div>
						</div>
					</div>

					<!--Exhibition start time-->
					<div class="control-group">
						<label class="control-label" for="exhib_start_time">Start Time: </label>
						<div class="controls" name="exhib_start_time">
							<div class="input-append bootstrap-timepicker" id="start_time">
								<input size="16" type="text" id="exhib_start" name="exhib_start" value="<?php echo set_value('exhib_start',$settings['exhib_start']);?>" readonly>
								<span class="add-on"><i class="icon-time"></i></span>
							</div>
						</div>
					</div>


					<!--Exhibition end time-->
					<div class="control-group">
						<label class="control-label" for="exhib_end_time">End Time: </label>
						<div class="controls" name="exhib_end_time">
							<div class="input-append bootstrap-timepicker" id="end_time">
								<input size="16" type="text" id="exhib_end" name="exhib_end" value="<?php echo set_value('exhib_end',$settings['exhib_end']);?>" readonly>
								<span class="add-on"><i class="icon-time"></i></span>
							</div>
						</div>
					</div>

					<!--Exhibition location-->
					<div class="control-group">
						<label class="control-label" for="exhib_end_time">Location: </label>
						<div class="controls" name="exhib_end_time">
							<div class="input" id="location">
								<input size="16" type="text" id="exhib_location" name="exhib_location" value="<?php echo set_value('exhib_location',$settings['exhib_location']);?>" >
							</div>
						</div>
					</div>

					<hr class="muted">
					<small class="muted text-center">Registration &amp; Judge Assignment</small>

					<!--bootstrap-datepicker for registration cutoff date-->
					<div class="control-group">
						<label class="control-label" for="reg_cutoff_date">Registration Cut-Off: </label>
						<div class="controls" name="reg_cutoff_date">
							<div class="input-append date" id="reg_cutoff_datepicker" data-date="<?php echo $reg_cutoff_date['cal_selected'];?>" data-date-format="m/d/yy">
								<input size="16" type="text" id="reg_cutoff_date" name="reg_cutoff_date" value="<?php echo set_value('reg_cutoff_date',$reg_cutoff_date['input']);?>"  readonly>
								<span class="add-on"><i class="icon-calendar"></i></span>
							</div>
						</div>
					</div>

					<!--Judges per project input field-->
					<div class="control-group">
						<label class="control-label" for="">Judges Per Project (Max)</label>
						<div class="controls">
							<input class="input" size="16" type="number" min="1" id="judges_per_project" name="judges_per_project" value="<?php echo set_value('judges_per_project',$settings['judges_per_project']);?>" >
						</div>
					</div>

					<!--Projects per judge input field-->
					<div class="control-group">
						<label class="control-label" for="">Projects Per Judge (Max)</label>
						<div class="controls">
							<input class="input" size="16" type="number" min="1" id="projects_per_judge" name="projects_per_judge" value="<?php echo set_value('projects_per_judge',$settings['projects_per_judge']);?>" >
						</div>
					</div>

					<!--Assign Judges button-->
					<div class="control-group">
						<label class="control-label" for="">System Generated Assignment</label>
						<div class="controls">
							<button class="btn btn-medium wsu_btn" size="16">Assign Judges</button>
						</div>
					</div>
				

					<hr class="muted">
					<small class="muted text-center">Restrict Access</small>
					<!--Restrict access to only admin and score entry users on/off dropdown-->
					<div class="control-group">
						<label class="control-label" for="restrict_access">Access to only Admin &amp; Score Entry Users</label>
						<div class="controls">
							<select name="restrict_access" id="restrict_access" value="<?php echo set_value('restrict_access',$settings['restrict_access']);?>" >
								<option>On</option>
								<option>Off</option>
							</select>
						</div>
					</div>

					<hr class="muted">
					<div class="row-fluid">
						<div class="span2 offset5">
							<input type="submit" name="general_settings_submit" class="btn btn-medium wsu_btn" name="save_settings_btn" value="Save Settings"/>
						</div>
					</div>
				</div><!--close settings span-->
			</div><!--close settings row-fluid-->
		</form><!--close form-->
	</div><!--close hero-unit-->
</body>
</html>