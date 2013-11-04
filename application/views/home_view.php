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
		<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
			<?php 
				if($this->session->flashdata('errors')){
					echo '<div class="alert" id="wsu_alert">
					<strong>Could not validate your credentials.</div>';
					//echo validation_errors();
	  			}
	  			else{
	  				echo '<p class="text-center" id="wsu_login_message">Already Registered?</p>';
	  			}
  			?>
  			
			<?php 
				$form_attributes = 
					array('name'=>'signinform','class'=>'form-inline pull-right','id'=>'sign_in_form');
				echo form_open('gerss/login_validation', $form_attributes);

				$username_attributes = 
					array('name'=>'email','class'=>'input-medium','id'=>'email','placeholder'=>'Email');
				echo form_input($username_attributes);
			
				$password_attributes = 
					array('name'=>'password','class'=>'input-medium','id'=>'password','placeholder'=>'Password');
				echo form_password($password_attributes);
				
				$submit_attributes = 
					array('name'=>'signin','class'=>'btn btn-small wsu_btn','id'=>'sign_in_btn');
				echo form_submit($submit_attributes,'Sign In');

				form_close();
				
			?>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		<?php
		//Successful registrations redirect here. Check for any successes, if so, print message in bootstrap div
			if($this->session->flashdata('success')){
  				echo '<div class="alert alert-success text-center" id="wsu_alert">';
  				echo $this->session->flashdata('success');
  				echo '</div>';
  			}
  		?>
  		<div class="content">
			<h2 class="wsu_h2 text-center hidden-phone">Graduate Exhibition Registration &amp; Scoring System</h2>
			<h4 class="wsu_h2 text-center visible-phone">Graduate Exhibition Registration &amp; Scoring System</h4>
			<p>The Graduate School is pleased to invite the campus community to the third annual Graduate Exhibition to celebrate the achievements of Wayne State University graduate students across our campus. The event will include an exhibition of graduate student research and scholarship from all disciplines, and will feature 100 posters and art exhibits and six oral presentations.</p>
			</br>
			<p class="text-center">We hope many will join us for this wonderful event!</p></br>
			<p class="text-center">
				Event Information:
			</br>
				<div class="row-fluid">
					<div class="span3 offset1 text-center">
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

					<div class="span3 text-center">
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
			</br>
			<div class="reg_button_container row-fluid">
             	
				<?php 
					$participant="participant";
					$judge="judge";
				?>

				<div class="span6">
					<a class="btn btn-lg wsu_btn span5 offset3" id="participant_register_btn" href='<?php echo base_url()."gerss/registration?type=",urlencode($participant)?>'><strong>Participants</strong></br>Register Here</a>
				</div>
				<div class="span6">
					<a class="btn btn-lg wsu_btn span5 offset3" id="judge_register_btn" href='<?php echo base_url()."gerss/registration?type=",urlencode($judge)?>'><strong>Judges</strong></br>Register Here</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>