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
		<!-- Output user session details
		<?php
			//echo "<pre>";
			//print_r($this->session->all_userdata());
			//echo "</pre>";
		?>-->
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

				echo form_close();
			?>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center">Graduate Exhibition Registration &amp; Scoring System</h2>

			<?php 
				if(validation_errors() != false){
					echo '<div class="alert" id="wsu_alert"><strong>';
					echo validation_errors();
					echo '</div>';
	  			}
	  			else{
	  				echo '<p class="text-left" id="wsu_login_message">Fill out the form below to register:</p>';
	  			}
  			?>
			<?php 
				$form_attributes = 
					array('name'=>'registration_form','id'=>'registration_form');
				echo form_open('gerss/registration_validation', $form_attributes);

				$username_attributes = 
					array('name'=>'email','class'=>'input-medium','id'=>'email','placeholder'=>'Email');
				echo form_input($username_attributes,$this->input->post('email'));
				
				echo '</br>';

				$password_attributes = 
					array('name'=>'password','class'=>'input-medium','id'=>'password','placeholder'=>'Password');
				echo form_password($password_attributes);

				echo '</br>';
				
				$confirm_password_attributes = 
					array('name'=>'cpassword','class'=>'input-medium','id'=>'confirm_password','placeholder'=>'Confirm Password');
				echo form_password($confirm_password_attributes);
				
				echo '</br>';
				
				$submit_attributes = 
					array('name'=>'registration_submit','class'=>'btn wsu_btn','id'=>'register_btn');
				echo form_submit($submit_attributes,'Register');

				echo form_close();
			?>
	</div>

</body>
</html>