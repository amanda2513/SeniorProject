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
			<?php 
				if(validation_errors() != false){
					echo '<div class="alert" id="wsu_alert">
					<strong>Could not validate your credentials.</div>';
	  			}
	  			else{
	  				echo '<p class="text-center" id="wsu_login_message">Already Registered?</p>';
	  			}
  			?>
			<?php 
				$form_attributes = 
					array('name'=>'signinform','class'=>'form-inline','id'=>'sign_in_form');
				echo form_open('gerss/login_validation', $form_attributes);

				$username_attributes = 
					array('name'=>'email','class'=>'input-medium','id'=>'email','placeholder'=>'Username');
				echo form_input($username_attributes);
			
				$password_attributes = 
					array('name'=>'password','class'=>'input-medium','id'=>'password','placeholder'=>'Password');
				echo form_password($password_attributes);
				
				$submit_attributes = 
					array('name'=>'signin','class'=>'btn btn-small wsu_btn','id'=>'sign_in_btn');
				echo form_submit($submit_attributes,'Sign In');

				echo form_close();
			?>
		</div>
	</div><!--close header-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>
		<h4 class="wsu_h2 text-center visible-phone visible-tablet">Graduate Exhibition Registration &amp; Scoring System</h4>
		<!--Invalid registrations redirect back to this page with errors-->
		<?php		
			//Get the type of registration from the URL .../registration?type=[whateverthisis] and store it in variable selected_option
		 	$selected_type = $_GET['type'];

		 	//The values of registration type dropdown
		 	$options = array("participant","judge");

		 	//If type from URL is participant, make that the default selected dropdown option, else judge
		 	if($selected_type == "participant"){
		 		$options[0]="selected='selected'";
		 	}
		 	else{
		 		$options[1]="selected='selected'";
		 	}
		?>

		<!--
			Form for registration type = Drop down with Judge & Participant options defined above
			When it submits, it will change the URL to registration?type=[selected dropdown option]
			This is the form's only job.
			Selected option will be appended to the actual registration submission
		-->
		<form action="" method="GET" class="form-horizontal" id="registration_type">
			<div class="control-group">
				<label class="control-label" for='type'> Registration Type: </label>
				<div class="controls">
					<select name="type" id="type" onChange="this.form.submit()">
						<option value="participant" <?php echo $options[0]?>>Participant</option>
						<option value="judge" <?php echo $options[1]?>>Judge</option>
					</select>
				</div>
			</div>
		</form><!--end registration-type form-->
		
		<hr class="muted"/>
		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  			//If there are no errors, show "Fill out form" message
  			else{
  				echo '<p class="span10 offset2">Fill out the form below to register:</p>';
  			}
  		?>

		<!--
			Actual Registration Submission Form
			Name, Department, Email, Password
			Same for participants & judges until IF statement
		-->
		<form class="form-horizontal" name="registration" id="registration" method="post" accept-charset="utf-8" action='<?php echo base_url()."gerss/registration_validation";?>'>
			<div class="row-fluid">
				<div class="span12">
					<div class="row-fluid">
						<?php
						//Change form layout based on registration type
						//start form's left column
							if($selected_type=='participant'){
								echo '<div class="span6">';
							}
							else{
								echo '<div class="span6 offset3">';
							}
						?>
							<p class="text-center muted"><small>Basic Information</small></p>

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
										<option value="">Select Your Department</option>
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
								<label class="control-label" for="password">Create a Password:</label>
								<div class="controls">
									<input type="password" name="password" class="input-large" id="password" placeholder="Password">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="cpassword">Confirm Password:</label>
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
							'<div class="span6">

								<p class="text-center muted"><small>Project Information</small></p>

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
							</div><!--close right column (participant only) span-->
					
							
							<p class="text-center span8 offset2">
								<em><small>By registering, you attest to having followed all federal, state and institutional regulatory requirements in the performance of the research/scholarship.</small></em>
							</p>';
						}
						else{
						//If the registration type is Judges, add these fields
							
						}
					?>
					</div><!--close form column row-fluid-->
					<div class="row-fluid">
						<div class="span12">
							<input type="submit" name="registration_submit" class="btn btn-medium wsu_btn span2 offset5", id="register_btn" value="Register"/>
						</div>
					</div><!--close submit button row-fluid-->
				</div><!--close span12-->
			</div><!--close form's row-fluid-->
		</form><!--close registration form-->
	</div><!--close hero-unit-->
</body>
</html>