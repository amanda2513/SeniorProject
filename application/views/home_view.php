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
		<a href="http://www.wayne.edu"><img src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
			<p class="text-center">Already Registered?</p>
			<form class="form-inline pull-right" id="sign_in_form">
				<input type="text" class="input-small" id="username" placeholder="Username">
				<input type="password" class="input-small" id="password" placeholder="Password">
			<!-- This will be the submit button when functionaity is implemented
				<button class="btn btn-small wsu_btn" id="sign_in_btn" type="submit">Sign In</button>
			below is just a link for navigation
			-->
				<a class="btn btn-small wsu_btn" id="sign_in_btn" href="projects_participants">Sign In</a>

			</form>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center">Graduate Exhibition Registration &amp; Scoring System</h2>
				<p class="text-center">The Graduate School is pleased to invite the campus community to the third annual Graduate Exhibition to celebrate the achievements of Wayne State University graduate students across our campus. The event will include an exhibition of graduate student research and scholarship from all disciplines, and will feature 100 posters and art exhibits and six oral presentations.</p>
				</br>
				<p class="text-center">The event will be held [DATE] from [START - END] in [LOCATION]. We hope many will join us for this wonderful event!</p>
			</br>
			<div class="reg_button_container">
				<a class="btn btn-lg wsu_btn" id="participant_register_btn" href="#"><strong>Participants</strong></br>Register Here</a>
				<a class="btn btn-lg wsu_btn" id="judge_register_btn" href="#"><strong>Judges</strong></br>Register Here</a>
			</div>
	</div>

</body>
</html>