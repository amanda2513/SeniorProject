<!DOCTYPE html>
<html>
<head lang="en">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
</head>
<body>		
	<div class="page-header" id="wsu_header">
		
			<?php
			if($logged_in){
				echo'
				<div class="row-fluid">
					<div class="row-fluid">
						<div class="span12">
							<div class="span3 pull-left">
								<a href="http://www.wayne.edu"><img id="wsu_logo" src="'.IMG.'wsu-wordmark.gif'.'"/></a>
							</div>
							<div class="span2 offset7">
								<div class="span6" id="wsu_login_message">
									Welcome, '.$this->session->userdata('fn').' '.$this->session->userdata('ln').'
								</div>
								<div class="span6">
			            			<a class="btn wsu_btn" id="sign_out_btn" href='.base_url(). "gerss/logout".'>Sign Out</a>
			            		</div>
							</div><!--end sign-out div-->		
						</div>
					</div>
					<div class="row-fluid">
						<div class="wsu_title text-center span12">
							Graduate Exhibition Registration &amp; Scoring System
						</div>
					</div>
					<div class="row-fluid">
						
					</div>
				</div><!--End Header Row-fluid-->
			</div><!--End Header-->';
				if($this->session->userdata('role')=='admin'){
					echo '
					<div class="navbar wsu_navbar row-fluid">
						<div class="navbar-inner span12">
							<ul class="nav text-center">
								<li class=" active span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
								<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
								<li class="span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
								<li class="span3"><a id="nav_manageusers" href="'.base_url()."manage_users/participant".'">Manage Users</a></li>
								<li class="span3"><a id="nav_systemsettings" href="'.base_url()."settings/general".'">System Settings</a></li>
							</ul>
						</div>
					</div><!--End Navbar-->';
				}
				elseif($this->session->userdata('role')=='seu'){
					echo '
					<div class="navbar wsu_navbar row-fluid">
						<div class="navbar-inner span12">
							<ul class="nav text-center">
								<li class=" active span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
								<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
								<li class="span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
							</ul>
						</div>
					</div><!--End Navbar-->';
				}
				elseif($this->session->userdata('role')=='judge'){
					echo '
					<div class="navbar wsu_navbar row-fluid">
						<div class="navbar-inner span12">
							<ul class="nav text-center">
								<li class=" active span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
								<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
								<li class="span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
							</ul>
						</div>
					</div><!--End Navbar-->';
				}
				else{
					echo '
					<div class="navbar wsu_navbar row-fluid">
						<div class="navbar-inner span12">
							<ul class="nav text-center">
								<li class=" active span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
								<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
							</ul>
						</div>
					</div><!--End Navbar-->';
				}
			}
			else{
				echo'
				<div class="row-fluid">
					<div class="span5">
						<div class="row-fluid">
							<div class="span12 pull-left">
								<a href="http://www.wayne.edu"><img id="wsu_logo" src="'.IMG.'wsu-wordmark.gif'.'"/></a>
							</div>
						</div>
					</div>
						<div class="span7">';
		
					if($this->session->flashdata('credentials_error')){
						echo '
							<div class="alert text-center span6 offset5 wsu_sign_in_alert" id="wsu_alert">
								<strong>'.$this->session->flashdata('credentials_error').'</strong>
							</div>';
		  			}
		  			else{
		  				echo '
		  					<div class="span7 text-center pull-right" id="wsu_login_message">
		  						Register / Sign In
		  					</div>';
		  			}
					echo' <div class="pull-right">';
					$form_attributes = 
						array('name'=>'signinform','class'=>'span12 form-inline','id'=>'sign_in_form');
					echo form_open('gerss/login_validation', $form_attributes);

					$username_attributes = 
						array('name'=>'username','class'=>'input-medium','id'=>'username','placeholder'=>'WSU Access ID');
					echo form_input($username_attributes);
				
					$password_attributes = 
						array('name'=>'password','class'=>'input-medium','id'=>'password','placeholder'=>'Password');
					echo form_password($password_attributes);
					
					$submit_attributes = 
						array('name'=>'signin','class'=>'btn btn-small wsu_btn','id'=>'sign_in_btn');
					echo form_submit($submit_attributes,'Sign In');

					form_close();
					echo'
							</div>
						</div>
					</div><!--End Header row-fluid-->';
				}
				
			?>
	</div><!--End Header-->

	<div class="hero-unit wsu_hero_unit">
		<?php
		//Successful registrations redirect here. Check for any successes, if so, print message in bootstrap div
			if($this->session->flashdata('success')){
  				echo '<div class="alert alert-success text-center" id="wsu_alert">';
  				echo $this->session->flashdata('success');
  				echo '</div>';
  			}
  			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  		?>

  		<div class="content">
  			<?php
  			if(!$logged_in){
  				echo'
				<h4 class="wsu_h2 text-center visible-phone">Graduate Exhibition Registration &amp; Scoring System</h4>
				<h2 class="wsu_h2 text-center hidden-phone">Graduate Exhibition Registration &amp; Scoring System</h2>
				';
			}
			?>
			<p class="text-center"><?php echo $settings['homepage_message'];?></p>
			<br>
			<p class="text-center">
				Event Information:
			<br>
				<div class="row-fluid">
					<div class="span4 text-right">
						<?php
							echo "Date: ";
							//If exhib date isn't set, show TBA.
							//If exhib date is set, show date in Day, Month date, Year format
							if($settings['exhib_date']!='0000-00-00')
								echo date("D, M d".","." Y",strtotime($settings["exhib_date"]));
							else
								echo "TBA";
						?>
					</div>

					<div class="span4 text-center">
						<?php	
							echo "Time: ";
							//If exhib start time AND exhib end time aren't set, show TBA
							//If exhib start time AND exhib end time are set, show  Start - End
							if($settings['exhib_start']!=NULL && $settings['exhib_end']!=NULL)
								echo $settings["exhib_start"]. " - " . $settings["exhib_end"];
							else
								echo "TBA";
						?>
					</div>

					<div class="span4 text-left">
						<?php
							echo "Location: ";
							//If exhib location isn't set, show TBA
							//If exhib location is set, show Location
							if($settings['exhib_location'] != NULL)
								echo $settings["exhib_location"];
							else
								echo "TBA";
						?>
					</div>
				</div>
			</p>
			<br>
		<?php
			if(!$logged_in && $in_registration_period){
				echo'
				<div class="row-fluid">
             		<div class="text-center">
						<strong class="muted"><small>Registration Ends: '. date("D, M d".","." Y",strtotime($reg_cutoff)).'<br>Enter your WSU credentials at the top of the page to register.</small></strong>
					</div>
				</div>';
			}
			elseif (!$logged_in && !$in_registration_period){
				echo'
				<div class="text-center row-fluid">
             		<div class="span6 offset3">
             			<strong class="muted"><small>Sorry, you cannot register at this time.</small></strong>
					</div>
				</div>';
			}
		?>
		</div>
	</div>
</body>
</html>