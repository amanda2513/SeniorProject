<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootbox.min.js');?>"></script>

	<script type="text/javascript">
	$(window).load(function() {
		$("#select_judge").change(function() {
			if($(this).data('options') == undefined){
				$(this).data('options',$('#select_participant option').clone());
			}
			var id = $(this).val();
			var options = $(this).data('options').filter('[value=' + id + ']');
			$('#select_participant').html(options);

			document.getElementById('select_participant').disabled = false;
		});
	});
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

	<div class="navbar wsu_navbar">
		<div class="navbar-inner">
			<ul class="nav text-center">
				<li><a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a></li>
				<li class="active"><a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a></li>
				<li><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."scores/input"?>">Input Scores</a></li>
			<li><a href="<?php echo base_url()."scores/view"?>">View Scores</a></li>
		</ul>
		<div class="text-center row-fluid">
			<div class="row-fluid">
				<em><strong>Sorry, scores cannot be entered at this time.</strong></em>
			</div>
		<?php
			if($user_type=='seu'){
				echo
				'<div class="row-fluid">
					<div class="span12 text-center">
						-----
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12 text-center">
						Check back on the day of the exhibition and when all projects have been assigned judges.
					</div>
				</div>
				<div class="row-fluid
					<div class="span12 text-center">
						-----<br>
						Contact an admin if you believe this is an error.
					</div>
				</div>';
			}
			if($user_type=='admin'){
				echo
				'<div class="row-fluid text-center">
					-----
				</div>
				<div class="row-fluid">
					<div class="span6 offset3">
						<ul class="text-left">
							<li>
								The page will be automatically enabled on the day of the exhibition.
								<br>
								<em>Ensure the date of the event is corrent in the System Settings page.</em>
							</li>
							<br>
							<li>
								All projects must have at least one judge assigned to it at the start of the exhibition.
								<br>
								<em>Automatically assign judges on the System Settings page or edit participant info on the Manage Users page to manually assign judges.</em>
							</li>
						</ul>
					</div>
				</div>';
			}
		?>
		</div>
	</div><!--close hero-unit-->
</body>
</html>