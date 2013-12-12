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
	$(document).ready(function() {
		$("[rel=tooltip]").tooltip({ placement:'right'});
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
					<div class="span6" id="wsu_login_message">
						Welcome, <?php echo $this->session->userdata('fn')?> <?php echo $this->session->userdata('ln')?>
					</div>
					<div class="span6">
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

<?php
	if($this->session->userdata('role')=='admin'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
					<li class="active span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
					<li class="span3"><a id="nav_manageusers" href="'.base_url()."manage_users/participant".'">Manage Users</a></li>
					<li class="span3"><a id="nav_systemsettings" href="'.base_url()."settings/general".'">System Settings</a></li>
				</ul>
			</div>
		</div>';
	}
	elseif($this->session->userdata('role')=='seu'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
					<li class="active span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
				</ul>
			</div>
		</div>';
	}
	elseif($this->session->userdata('role')=='judge'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="span2"><a id="nav_projects" href="'.base_url()."gerss/projects_participants".'">Projects</a></li>
					<li class="active span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
				</ul>
			</div>
		</div>';
	}
?>
	<div class="hero-unit wsu_hero_unit">
		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."scores/input"?>">Input Scores</a></li>
			<li><a href="<?php echo base_url()."scores/view"?>">View Scores</a></li>
		</ul>
		<div class="text-center row-fluid">
			<div class="row-fluid">
				<em><strong>Sorry, scores cannot be entered at this time.</strong></em>
			
		<?php
			if($user_type=='seu'||$user_type=='admin'){
				$message="";
				if($scoring_requirements['all_projects_assigned']!=1){
					$message=$message." Not all projects have been assigned judges. ";
				}
				if($scoring_requirements['exhibition_started']!=1){
					$message=$message." The exhibition has not started.";
				}
				echo'
				<i class="icon icon-exclamation-sign wsu_tooltip" rel="tooltip" title="'.$message.'"></i>'
				;
			}
		?>
			</div>
		</div>
	</div><!--close hero-unit-->
</body>
</html>