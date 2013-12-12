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
				<div class="span2 offset2">
		            <a class="btn wsu_btn" id="sign_out_btn" href='<?php echo base_url(). "gerss/logout"; ?>'>Back</a>
				</div>			
			</div>
		</div>		
	</div><!--close header-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>
		<h4 class="wsu_h2 text-center visible-phone visible-tablet">Graduate Exhibition Registration &amp; Scoring System</h4>
		<!--Invalid registrations redirect back to this page with errors-->
		<?php
			if(isset($_GET['type'])){
				//Get the type of registration from the URL .../registration?type=[whateverthisis] and store it in variable selected_option
				$selected_type = $_GET['type'];
			}else{
				$selected_type = 0;
			}
				//The values of registration type dropdown
				$options = array("0","participant","judge");

		 	//If type from URL is participant, make that the default selected dropdown option, else judge
			if($selected_type == "0"){
				$options[0]="selected='selected'";
			}		
		 	elseif($selected_type == "participant"){
		 		$options[1]="selected='selected'";
		 	}
		 	else{
		 		$options[2]="selected='selected'";
		 	}
		?>
		<hr>
		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  			//If there are no errors, show "Fill out form" message
  			else{
  			}
  		?>
		<p class="span10 offset2">Fill out the form below to register:</p>

		<!--
			Form for registration type = Drop down with Judge & Participant options defined above
			When it submits, it will change the URL to registration?type=[selected dropdown option]
			This is the form's only job.
			Selected option will be appended to the actual registration submission
		-->
		<form action="" method="GET" class="form-horizontal" id="registration_type">
			<div class="row-fluid">
				<div class="span6 offset3">
					<div class="control-group">
						<label class="control-label" for='type'> Registration Type: </label>
						<div class="controls">
							<select name="type" id="type" onChange="this.form.submit()">
								<option value="0" <?php echo $options[0]?>>Select a Type</option>
								<option value="participant" <?php echo $options[1]?>>Participant</option>
								<option value="judge" <?php echo $options[2]?>>Judge</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form><!--end registration-type form-->
		
		


		<!--
			Actual Registration Submission Form
			Name, Department, Email, Password
			Same for participants & judges until IF statement
		-->
		<form class="form-horizontal" enctype="multipart/form-data" name="registration" id="registration" method="post" accept-charset="utf-8" action='<?php echo base_url()."gerss/registration_validation";?>'>
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
							<p class="text-center muted"><small>Basic Information: Cannot Edit</small></p>

							<input type="hidden" name="type" id="type" value="<?php echo $selected_type?>">

			    			<div class="control-group">
								<label class="control-label" for="userid">WSU Access ID:</label>
								<div class="controls">
									<input type="text" name="userid" class="input-large" id="userid" placeholder="UserID" value="<?php echo $this->session->userdata('username');?>" readonly>
								</div>
							</div>
							
							<div class="control-group">
			    				<label class="control-label" for="full_name">Name:</label>
			    				<div class="controls inline" name="full_name">
									<input type="text" name="firstname" class="input-large" id="firstname" placeholder="Firstname" value="<?php echo $this->session->userdata('fn');?>" readonly>                               
			    					<input type="text" name="lastname" class="input-large" placeholder="Last Name" value="<?php echo $this->session->userdata('ln');?>" readonly />
			    				</div>
			    			</div>

							<div class="control-group">
								<label class="control-label" for="department">College:</label>
								<div class="controls">
									<input type="text" name="college" class="input-large" placeholder="College" value="<?php echo $this->session->userdata('coll');?>" readonly />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="department">Department:</label>
								<div class="controls">
									<input type="text" name="department" class="input-large" placeholder="Department" value="<?php echo $this->session->userdata('dept');?>" readonly />
								</div>
							</div>
							
						</div><!--close left span-->

					<?php
						//If the registration type is Participant, add these fields about their project
						if($selected_type=='participant'){
							echo 
							//open right column - span6 - participant only
							'<div class="span6" id="project_info_span">

								<p class="text-center muted"><small>Project Information: All Fields Are Required</small></p>

								<div class="control-group">
									<label class="control-label" for="category">Category:</label>
									<div class="controls">
										<select name="category" id="category">
											<option value="0">Select a Category</option>';
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
									</div>
								</div>
							</div><!--close right column (participant only) span-->
					
							
							<p class="text-center span6 offset3">
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
		<hr>
	</div><!--close hero-unit-->
</body>
</html>