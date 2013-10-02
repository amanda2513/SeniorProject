<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery-latest.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#project_judges_table").tablesorter( {sortList: [[0,0]]});
		});	
	</script>
</head>
<body>
		
	<div class="page-header" id="wsu_header">
		<a href="http://www.wayne.edu"><img src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
		<!-- This will be the actual button when it's implemented
			<button class="btn btn-small wsu_btn" id="sign_out_btn">Sign Out</button>
		This next link is just for navigation
		-->
			<a class="btn btn-small wsu_btn" id="sign_out_btn" href="home">Sign Out</a>
		</div>
	</div>

	<div class="navbar wsu_navbar">
		<div class="navbar-inner">
			<a class="btn btn-navbar" id="collapsed_menu_btn" data-toggle="collapse" data-target=".nav-collapse">
				<span class = "icon-th-list"></span>
			</a>
			<div class = "nav-collapse collapse">
				<ul class="nav text-center">
					<li class="active"><a id="nav_projects" href="#">Projects</a></li>
					<li><a id="nav_scores" href="#">Scores</a></li>
					<li><a id="nav_manageusers" href="#">Manage Users</a></li>
					<li><a id="nav_systemsettings" href="#">System Settings</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

		<h2 class="wsu_h2 text-center">Graduate Exhibition Registration &amp; Scoring System</h2>

		<form class="form-search">
			<div class="input-append">
				<input type="text" class="input-medium search-query" placeholder="Judge's Last Name">
				<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
			</div>
			<button class="btn wsu_btn" type="reset" id="clear">Clear</button>
		</form>

		<ul class="nav nav-tabs">
			<div id="group_by_text">Group By:</div>
			<li><a href="projects_participants">Participants</a></li>
			<li class="active"><a href="localhost/SeniorProject/GERSS/projects_judges">Judges</a></li>
			<button class="btn wsu_btn pull-right" href="#" id="btn_add_participant"><i class="icon-plus"></i> Add Judge</button>
		</ul>

		<table id="project_judges_table" class="table table-bordered table-striped tablesorter">
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
						Assigned</br>Project Title
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Assigned</br>Project Category
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Imajudge</td>
					<td>John</td>
					<td>Computer Science</td>
					<td>Some Title</td>
					<td>Poster</td>
					<td>
						<button class="btn wsu_btn" href="#"><i class="icon-pencil"></i></button>
						<button class="btn wsu_btn" href="#"><i class="icon-print"></i></button>
					</td>
				</tr>
				<tr>
					<td>Smith</td>
					<td>Forinstance</td>
					<td>English</td>
					<td>Different Title</td>
					<td>Oral Presentation</td>
					<td>
						<button class="btn wsu_btn" href="#"><i class="icon-pencil"></i></button>
						<button class="btn wsu_btn" href="#"><i class="icon-print"></i></button>
					</td>
				</tr>
				<tr>
					<td>Teacherguy</td>
					<td>Mister</td>
					<td>Physics</td>
					<td>Unique Title</td>
					<td>Oral Presentation</td>
					<td>
						<button class="btn wsu_btn" href="#"><i class="icon-pencil"></i></button>
						<button class="btn wsu_btn" href="#"><i class="icon-print"></i></button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>