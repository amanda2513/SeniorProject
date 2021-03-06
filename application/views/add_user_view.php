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
		<div class="row-fluid">
			<div class="span12">
				<div class="span3 pull-left">
					<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
				</div>
				<div class="span5">
				</div>
				<div class="span4 pull-right">
					<div class="span4 offset4" id="wsu_user_welcome">
						Welcome,<br><?php echo $this->session->userdata('fn')?> <?php echo $this->session->userdata('ln')?>
					</div>
					<div class="span4">
            			<a class="btn wsu_btn" id="sign_out_btn" href='<?php echo base_url(). "gerss/logout";?>'>Sign Out</a>
            		</div>
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
				<li class="active span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

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
								<option value="seu" <?php echo $options[2];?>>Scorer</option>
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
		<form class="form-horizontal" enctype="multipart/form-data" name="registration" id="registration" method="post" accept-charset="utf-8" action='<?php echo base_url()."manage_users/add_user_validation";?>'>
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
			    				<label class="control-label" for="userid">WSU Access ID:</label>
			    				<div class="controls inline" name="userid">
			    					<input type="text" name="userid" class="input-large"/>
			    				</div>
			    			</div>
						</div><!--close left span-->

					<?php
						//If the registration type is Participant, add these fields about their project
						if($selected_type=='participant'){
							echo 
							//open right column - span6 - participant only
							'<div class="span6" id="project_info_span">

								<p class="text-center muted"><small>Project Information: Category &amp; Abstract are Required</small></p>

								<div class="control-group">
									<label class="control-label" for="category">Category:</label>
									<div class="controls">
										<select name="category" id="category">
											<option value="">Select a Category</option>';
											foreach($categories as $category){
												echo '<option value="'.$category->category.'">'. $category->category.'</option>';
											}
								echo	'</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="project_abstract_pdf">Upload Abstract: </label>
									<div class="controls">
										<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
										<input name="project_abstract_pdf" type="file" id="abstract_pdf">
										<p class="muted"><small class="muted">PDF files only</small></p>
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