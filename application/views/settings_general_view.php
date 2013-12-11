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
	<script type="text/javascript" src="<?php echo (JS.'bootbox.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'spin.min.js');?>"></script>
	
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

	<script type="text/javascript">
		function spinner(){
			var opts = {
			  lines: 13, // The number of lines to draw
			  length: 20, // The length of each line
			  width: 10, // The line thickness
			  radius: 30, // The radius of the inner circle
			  corners: 1, // Corner roundness (0..1)
			  rotate: 0, // The rotation offset
			  direction: 1, // 1: clockwise, -1: counterclockwise
			  color: '#000', // #rgb or #rrggbb or array of colors
			  speed: 1, // Rounds per second
			  trail: 60, // Afterglow percentage
			  shadow: false, // Whether to render a shadow
			  hwaccel: false, // Whether to use hardware acceleration
			  className: 'spinner', // The CSS class to assign to the spinner
			  zIndex: 2e9, // The z-index (defaults to 2000000000)
			  top: 'auto', // Top position relative to parent in px
			  left: 'auto' // Left position relative to parent in px
			};
			var target = document.getElementById('general_settings');
			var spinner = new Spinner(opts).spin(target);
		}

		function not_yet_prompt(scoring_started){
			if(scoring_started){
				bootbox.alert('Sorry, scores have already been entered. Automatic judge assignment is disabled. Please assign judges manually from the Manage Users page.');
			}
			else{
				bootbox.alert('Sorry, judges cannot be assigned at this time. <br> Please verify that the settings have been saved and the registration cut-off date has passed.');
			}
		}

		function resetSystemPrompt(){
			bootbox.dialog("<p class='text-center'>This will <strong><em>permanently</em></strong> delete all Participants, Judges, Scorers, Scores, and General Settings. Admin and Project Category Settings will not be deleted. <br> <br> Are you sure you want to reset the system? <br> <em>You may want to export scores first!</em></p>",[{
				"label": "Reset System",
				"class":"btn wsu_btn_danger pull-left",
				"callback": function(){
					window.location.href='<?php echo base_url()."settings/reset_system";?>';
				}
			}, {
				"label": "Cancel",
				"class":"btn wsu_btn pull-right"
			}]);
		}
	</script>

</head>
<body>
	<div class="page-header" id="wsu_header">
		<div class="row-fluid">
			<div class="span12">
				<div class="span3 pull-left">
					<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
				</div>
				<div class="span5">
				</div>
				<div class="span2 offset2">
		            <a class="btn wsu_btn" id="sign_out_btn" href='<?php echo base_url(). "gerss/logout"; ?>'>Sign Out</a>
				</div>			
			</div>
		</div>
		<div class="row-fluid">
			<div class="wsu_title text-center span12">
				Graduate Exhibition Registration &amp; Scoring System
			</div>
		</div>
	</div>

	<div class="navbar wsu_navbar row-fluid">
		<div class="navbar-inner span12">
			<ul class="nav text-center">
				<li class="span1"><a id="nav_home" href="<?php echo base_url()."gerss/home"?>"><img alt="home" src="<?php echo (IMG.'home.png');?>"></img></a></li>
				<li class="span2"><a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a></li>
				<li class="span2"><a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a></li>
				<li class="span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="active span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo base_url()."settings/general"?>">General</a></li>
			<li><a href="<?php echo base_url()."settings/categories"?>">Project Categories</a></li>
		</ul>

		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<script>not_yet_prompt(true);</script>';
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
  		<hr>
  		<p><strong>Current Year's Settings</strong></p>

		<form class="form-horizontal" name="general_settings" id="general_settings" method="post" accept-charset="utf-8" action='<?php echo base_url()."settings/general_settings_form";?>'>
			<div class="row-fluid">
				<div class="span8 offset2">

					<div class="row-fluid">
						<div class="span2">
							<a class="btn btn-medium wsu_btn" href="<?php echo base_url()."settings/general"?>">Cancel</a>
						</div>
						<div class="span2 offset7">
							<input type="submit" name="general_settings_submit" class="btn btn-medium wsu_btn" name="save_settings_btn" value="Save Settings"/>
						</div>
					</div>

					<hr>
					<small class="muted text-center">Site - Home Page</small>
					<!--message that will display on the home screen-->
	    			<div class="control-group">
						<label class="control-label" for="welcome">Welcome Message: </label>
						<div class="controls" name="welcome">
							<textarea size="16" rows="5" type="text" id="home_msg" name="home_msg"><?php echo set_value('exhib_location',$settings['homepage_message']);?></textarea>
							<br><small class="muted text-center">For line breaks type &lt;br&gt;</small>
						</div>
					</div>

					<hr>
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

					<hr>
					<small class="muted text-center">Registration &amp; Judge Assignment</small>

					<!--bootstrap-datepicker for registration start date-->
					<div class="control-group">
						<label class="control-label" for="reg_start_date">Registration Start: </label>
						<div class="controls" name="reg_start_date">
							<div class="input-append date" id="reg_start_datepicker" data-date="<?php echo $reg_start_date['cal_selected'];?>" data-date-format="m/d/yy">
								<input size="16" type="text" id="reg_start_date" name="reg_start_date" value="<?php echo set_value('reg_start_date',$reg_start_date['input']);?>"  readonly>
								<span class="add-on"><i class="icon-calendar"></i></span>
							</div>
						</div>
					</div>

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
		<?php
			if($reg_cutoff_date['input'] && $reg_cutoff_date['input'] < date('m/d/y') && $scoring_started==false)
				$status = 'onclick="spinner()" href="'.base_url().'settings/assign_judges"';
			else
				$status = "disabled='disabled' onclick='not_yet_prompt(".$scoring_started.")'";
		?>

					<!--Assign Judges button-->
					<div class="control-group">
						<label class="control-label" for="">System Generated Assignment</label>
						<div class="controls">
							<a class="btn btn-medium wsu_btn" size="16" <?php echo $status;?>>Assign Judges</a>
						</div>
					</div>
				

		<?php		
			$options = array(0,1);

		 	//If type from URL is participant, make that the default selected dropdown option, else judge
		 	if($settings['restrict_access'] == 0){
		 		$options[0]="selected='selected'";
		 	}
		 	else{
		 		$options[1]="selected='selected'";
		 	}
		?>

					<hr>
					<small class="muted text-center">Restrict Access</small>
					<!--Restrict access to only admin and score entry users on/off dropdown-->
					<div class="control-group">
						<label class="control-label" for="restrict_access">Access to only Admin &amp; Score Entry Users</label>
						<div class="controls">
							<select name="restrict_access" id="restrict_access" value="<?php echo set_value('restrict_access',$settings['restrict_access']);?>" >
								<option value="1" <?php echo $options[1]?>>On</option>
								<option value="0" <?php echo $options[0]?>>Off</option>
							</select>
						</div>
					</div>

					<hr>
					<div class="row-fluid">
						<div class="span2">
							<a class="btn btn-medium wsu_btn" href="<?php echo base_url()."settings/general"?>">Cancel</a>
						</div>
						<div class="span2 offset7">
							<input type="submit" name="general_settings_submit" class="btn btn-medium wsu_btn" name="save_settings_btn" value="Save Settings"/>
						</div>
					</div>
				</div><!--close settings span-->
			</div><!--close settings row-fluid-->
		</form><!--close form-->
		<br>
		<hr>
		<div class="end_year">
			<p><strong>Export Scores &amp; Reset System</strong></p>
			<br>
			<div class="row-fluid">
				<div class="span12">
					<div class="span2 offset4">
						<a class="btn wsu_btn" href="<?php echo base_url()?>settings/export_scores">Export Scores</a>
					</div>
					<div class="span3">
						<button class="btn wsu_btn_danger" onclick="resetSystemPrompt();">Reset System</button>
					</div>
				</div>
			</div>
			<br>
			<br>
		</div>
	</div><!--close hero-unit-->
</body>
</html>