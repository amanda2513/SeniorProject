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
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#assign_judges").tablesorter( {sortList: [[0,0]]});
			$("[rel=tooltip]").tooltip({ placement:'top'});
		});	
	</script>
	<script type="text/javascript">
		function changeType(){
			var dropdown = document.getElementById("type");
			var selected = dropdown.options[dropdown.selectedIndex].value;

			var hiddenInput = document.getElementById("hidden_type");
			hiddenInput.value = selected;

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
				<div class="span2 offset7">
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
				<li class="active span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

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
  		?>

		<!--Invalid add-user forms redirect back to this page with errors-->
		<?php		
			//Get the type of add-user from the URL .../add?type=[whateverthisis] and store it in variable selected_option
		 	$selected_type = $this->uri->segment(3);

		 	//The values of registration type dropdown
		 	$options = array("participant","judge","seu","admin");

		 	//If type from URL is participant, make that the default selected dropdown option, else judge
		 	if($selected_type == "participant"){
		 		$dropdown_control = "disabled";
		 		$options[0]='<option value="participant" selected="selected">Participant</option>';
		 	}
		 	elseif($selected_type == "judge"){
		 		$dropdown_control = "";
		 		$options[0]='<option value="judge" selected="selected">Judge</option>';
		 		$options[1]='<option value="seu">Score Entry User</option>';
		 		$options[2]='<option value="admin">Admin</option>';
		 	}
		 	elseif($selected_type == "seu"){
		 		$dropdown_control = "";
		 		$options[0]='<option value="judge">Judge</option>';
		 		$options[1]='<option value="seu" selected="selected">Score Entry User</option>';
		 		$options[2]='<option value="admin">Admin</option>';
		 	}
		 	else{
		 		$dropdown_control = "";
		 		$options[0]='<option value="judge">Judge</option>';
		 		$options[1]='<option value="seu">Score Entry User</option>';
		 		$options[2]='<option value="admin" selected="selected">Admin</option>';
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
						<label class="control-label" for='type'> User's Type: </label>
						<div class="controls">
							<select name="type" id="type" <?php echo $dropdown_control;?> onChange="changeType()">
								<?php
									foreach($options as $option){
										echo $option;
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form><!--end add-user-type form-->
		
		<hr>
		<p><strong>Edit <?php echo ucfirst($selected_type);?> Info</strong></p>
		<!--
			Actual Add user Submission Form
			Name, Department, Email, Password
			Same for all users until IF statement
		-->
		<form class="form-horizontal" name="registration" id="registration" method="post"
		accept-charset="utf-8" action='<?php echo base_url()."manage_users/edit_user_validation/".
		$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$user_data['id'];?>'>


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

							<input type="hidden" name="type" id="hidden_type" value="<?php echo $selected_type;?>">

			    			<div class="control-group">
			    				<label class="control-label" for="full_name">Name:</label>
			    				<div class="controls inline" name="full_name">
			    					<input type="text" name="firstname" class="input-large" placeholder="First Name" value="<?php echo set_value('firstname',$user_data['firstname']);?>"/>
			    					<input type="text" name="lastname" class="input-large" placeholder="Last Name"value="<?php echo set_value('lastname',$user_data['lastname']);?>"/>
			    				</div>
			    			</div>

			    			

							<div class="control-group">
								<label class="control-label" for="department">Department:</label>
								<div class="controls">
									<select name="department" value="<?php echo set_value('department',$user_data['department']);?>">
										<option value="">Select User's Department</option>
										<?php		
											$departments = array('Computer Science', 'English', 'Science');

											foreach($departments as $department){
												if($user_data['department'] == $department){
										 			$selected="selected='selected'";
											 	}
											 	else{
											 		$selected = "";
											 	}
											 	echo '<option value="'.$department.'"'.$selected.'>'.$department.'</option>';
										 	}
										?>
									</select>
								</div>
							</div>


							<div class="control-group">
								<label class="control-label" for="email">WSU Email:</label>
								<div class="controls">
									<input type="email" name="email" class="input-large" id="email" placeholder="Email" action="<?php echo $this->input->post('email');?>" value="<?php echo set_value('email',$user_data['email']);?>" readonly>
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
											<option value="0">Select a Category</option>';
											foreach($categories as $category){
												if($project_data->category==$category->category){
													$selected = "selected='selected'";
												}
												else{
													$selected = "";
												}
												echo '<option value="'.$category->category.'"'.$selected.'>'. $category->category.'</option>';
											}
									echo'</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="project_title">Title:</label>
									<div class="controls">
										<input type="text" name="project_title" class="input-xlarge" id="title" placeholder="Title" value="'.set_value("project_title",$project_data->title).'">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="project_desc">Description:</label>
									<div class="controls">
										<textarea name="project_desc" class="input-xlarge" id="description" rows="10" placeholder="Abstract should not exceed 250 words">'. $project_data->description.'</textarea>
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
							<input type="submit" name="registration_submit" class="btn wsu_btn span2 offset5" id="register_btn" value="Update User Info"/>
						</div>
					</div><!--close submit button row-fluid-->
				</div><!--close span12-->
			</div><!--close form's row-fluid-->
		</form><!--close registration form-->

		<hr>
		<?php
		if($logged_in_as == 'admin' && $selected_type=='participant'){

			echo '
			<div class="row-fluid">
				<p class="span4 text-left"><strong>Manually Assign Judges</strong></p>
				<p class="span8 text-right">
					<small class="muted">Current Settings:  Max Judges Per Project='.$judges_per_project.'  &amp;  Max Projects Per Judge='.$projects_per_judge.' </small> <i class="icon-exclamation-sign wsu_tooltip" rel="tooltip" title="Manual assignment allows you to disregard these settings. Please use your best judgement."></i>
				</p>
			</div>
			<form method="post" accept-charset="utf-8" action="'.base_url()."manage_users/manual_assignment_participant_validation/".$this->uri->segment(3).'/'.$this->uri->segment(4).'">
			<input type="hidden" name="project_id" value="'.$project_data->project_id.'">
			<table id="assign_judges" class="table table-bordered table-striped tablesorter">
				<thead>
					<tr>
						<th>
							Last Name
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							First Name
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Department
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Current Assignments
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Assign?
							<i class="pull-right icon-resize-vertical"></i>
						</th>
					</tr>
				</thead>
				<tbody>';

				foreach($all_judges as $judge){
					echo
					'<tr>
						<td>'.
							$judge->lastname.'
						</td>
						<td>'.
							$judge->firstname.'
						</td>
						<td>'.
							$judge->department.'
						</td>
						<td>'.
							$judge->assignment_count.'
						</td>
						<td>
							<input type="checkbox" name="assign_judge[]" value="'.$judge->id.'" ';

					foreach($assigned_judges as $assigned){
						if($judge->id == $assigned->judge_id){
							echo'checked';
						}
					}
					echo'>';

					if($judge->department == $user_data['department']){
						echo ' <i class="icon-exclamation-sign wsu_tooltip" rel="tooltip" title="Judge and Participant are from the same department. Consider assigning a different judge."></i>';
					}

					echo'
						</td>
					</tr>';
				}
				echo'
				</tbody>
			</table>
			<div class="row-fluid">
				<div class="span8 offset2">
					<input type="submit" class="btn wsu_btn span2 offset5" name="manual_assign_submit" id="manual_assign_submit" value="Assign Judges">
				</div>
			</div>
			</form>';
		}
		?>

		<?php
		if($logged_in_as == 'admin' && $selected_type=='judge'){

			echo '
			<div class="row-fluid">
				<p class="span4 text-left"><strong>Manually Assign Judge</strong></p>
				<p class="span8 text-right">
					<small class="muted">Current Settings:  Max Judges Per Project='.$judges_per_project.'  &amp;  Max Projects Per Judge='.$projects_per_judge.' </small> <i class="icon-exclamation-sign wsu_tooltip" rel="tooltip" title="Manual assignment allows you to disregard these settings. Please use your best judgement."></i>
				</p>
			</div>
			<form method="post" accept-charset="utf-8" action="'.base_url()."manage_users/manual_assignment_judge_validation/".$this->uri->segment(3).'/'.$this->uri->segment(4).'">
			<input type="hidden" name="judge_id" value="'.$user_data['id'].'">
			<table id="assign_judges" class="table table-bordered table-striped tablesorter">
				<thead>
					<tr>
						<th>
							Last Name
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							First Name
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Department
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Judges Assigned
							<i class="pull-right icon-resize-vertical"></i>
						</th>
						<th>
							Assign?
							<i class="pull-right icon-resize-vertical"></i>
						</th>
					</tr>
				</thead>
				<tbody>';

				foreach($all_participants as $participant){
					$judge_count=0;
					foreach($judge_assignment as $assigned){
						if($assigned->project_id == $participant->project_id){
							$judge_count+=1;
						}
					}
					echo
					'<tr>
						<td>'.
							$participant->lastname.'
						</td>
						<td>'.
							$participant->firstname.'
						</td>
						<td>'.
							$participant->department.'
						</td>
						<td>'.
							$judge_count.'
						</td>
						<td>
							<input type="checkbox" name="assign_project[]" value="'.$participant->project_id.'" ';

					foreach($judge_assignment as $assigned){
						if($assigned->project_id == $participant->project_id && $assigned->judge_id == $user_data['id']){
							echo'checked';
							break;
						}
					}
					echo'>';

					if($participant->department == $user_data['department']){
						echo ' <i class="icon-exclamation-sign wsu_tooltip" rel="tooltip" title="Judge and Participant are from the same department. Consider assigning the judge to a different project."></i>';
					}

					echo'
						</td>
					</tr>';
				}
				echo'
				</tbody>
			</table>
			<div class="row-fluid">
				<div class="span8 offset2">
					<input type="submit" class="btn wsu_btn span2 offset5" name="manual_assign_submit" id="manual_assign_submit" value="Assign Judges">
				</div>
			</div>
			</form>';
		}
		?>
	</div><!--close hero-unit-->
</body>
</html>