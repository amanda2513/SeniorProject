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
						<a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a>					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>

		<!--Invalid add-user forms redirect back to this page with errors-->
		<?php		
			//Get the type of add-user from the URL .../add?type=[whateverthisis] and store it in variable selected_option
		 	$selected_type = $_GET['type'];

		 	//The values of registration type dropdown
		 	$options = array("participant","judge","seu","admin");

		 	//If type from URL is participant, make that the default selected dropdown option, else judge
		 	if($selected_type == "participant"){
		 		$options[0]="selected='selected'";
		 	}
		 	elseif($selected_type == "judge"){
		 		$options[1]="selected='selected'";
		 	}
		 	elseif($selected_type == "seu"){
		 		$options[2]="selected='selected'";
		 	}
		 	else{
		 		$options[3]="selected='selected'";
		 	}
		?>

		<!--
			Form for registration type = Drop down with user type options defined above
			When it submits, it will change the URL to manage_users/add?type=[selected dropdown option]
			This is the form's only job.
			Selected option will be appended to the actual registration submission
		-->
		<form action="" method="GET" class="form-horizontal" id="registration_type">
			<div class="row-fluid">
				<div class="span6 offset3">
					<div class="control-group">
						<label class="control-label" for='type'> New User's Type: </label>
						<div class="controls">
							<select name="type" id="type" onChange="this.form.submit()">
								<option value="participant" <?php echo $options[0];?>>Participant</option>
								<option value="judge" <?php echo $options[1];?>>Judge</option>
								<option value="seu" <?php echo $options[2];?>>Score Entry User</option>
								<option value="admin" <?php echo $options[3];?>>Admin</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form><!--end add-user-type form-->
		
		<hr class="muted"/>
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
  				echo '<p class="span10 offset2">Fill out the form below to add a user:</p>';
  			}
  		?>

		<!--
			Actual Add user Submission Form
			Name, Department, Email, Password
			Same for all users until IF statement
		-->
		<form class="form-horizontal" name="registration" id="registration" method="post" accept-charset="utf-8" action='<?php echo base_url()."manage_users/add_user_validation";?>'>
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<?php
						//Change form layout based on registration type
						//start form's left column
							if($selected_type=='participant'){
								echo '<div class="span6" id="basic_info_span">';
							}
							else{
								echo '<div class="span6 offset3">';
							}
						?>
							<p class="text-center muted"><small>Basic Information: All Fields Are Required</small></p>

							<input type="hidden" name="type" id="type" value="<?php echo $selected_type?>">

			    			<div class="control-group">
			    				<label class="control-label" for="full_name">Name:</label>
			    				<div class="controls inline" name="full_name">
			    					<input type="text" name="firstname" class="input-large" placeholder="First Name"/>
			    					<input type="text" name="lastname" class="input-large" placeholder="Last Name"/>
			    				</div>
			    			</div>

							<div class="control-group">
								<label class="control-label" for="department">Department:</label>
								<div class="controls">
									<select name="department">
										<option value="">Select User's Department</option>
										<option value="Computer Science">Computer Science</option>
										<option value="English">English</option>
										<option value="Science">Science</option>
									</select>
								</div>
							</div>


							<div class="control-group">
								<label class="control-label" for="email">WSU Email:</label>
								<div class="controls">
									<input type="email" name="email" class="input-large" id="email" placeholder="Email" action="<?php echo $this->input->post('email');?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="password">Temporary Password:</label>
								<div class="controls">
									<input type="password" name="password" class="input-large" id="password" placeholder="Password">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="cpassword">Confirm Temporary Password:</label>
								<div class="controls">
									<input type="password" name="cpassword" class="input-large" id="confirm_password" placeholder="Password">
								</div>
							</div>
						</div><!--close left span-->

					<?php
						//If the registration type is Participant, add these fields about their project
						if($selected_type=='participant'){
							echo 
							//open right column - span6 - participant only
							'<div class="span6" id="project_info_span">

								<p class="text-center muted"><small>Project Information: All Fields are Optional</small></p>

								<div class="control-group">
									<label class="control-label" for="category">Category:</label>
									<div class="controls">
										<select name="category" id="category">
											<option value="Poster">Poster</option>
											<option value="Oral Presentation">Oral Presentation</option>
										</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="project_title">Title:</label>
									<div class="controls">
										<input type="text" name="project_title" class="input-xlarge" id="title" placeholder="Title">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="project_desc">Description:</label>
									<div class="controls">
										<textarea name="project_desc" class="input-xlarge" id="description" rows="10" placeholder="Abstract should not exceed 250 words"></textarea>
									</div>
								</div>
							</div><!--close right column (participant only) span-->';
						}
						else{
						//If the registration type is a non-participant, add these fields
							
						}
					?>
					</div><!--close form column row-fluid-->
					<div class="row-fluid">
						<div class="span12">
							<input type="submit" name="registration_submit" class="btn btn-medium wsu_btn" id="register_btn" value="Add User"/>
						</div>
					</div><!--close submit button row-fluid-->
				</div><!--close span12-->
			</div><!--close form's row-fluid-->
		</form><!--close registration form-->
	</div><!--close hero-unit-->
</body>
</html>