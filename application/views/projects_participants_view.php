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
			$("#project_participants_table").tablesorter( {sortList: [[0,0]]});
		});	
	</script>
</head>
<body>
	
	<div class="page-header" id="wsu_header">
		<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
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
				<input type="text" class="input-medium search-query" placeholder="Participant's Last Name">
				<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
			</div>
			<button class="btn wsu_btn" type="reset" id="clear">Clear</button>
		</form>

		

		<ul class="nav nav-tabs">
			<div id="group_by_text">Group By:</div>
			<li class="active"><a href="projects_participants">Participants</a></li>
			<li><a href="projects_judges">Judges</a></li>
			<button class="btn wsu_btn pull-right" href="#" id="btn_add_participant"><i class="icon-plus"></i> Add Participant</button>
		</ul>

		<table id="project_participants_table" class="table wsu_table table-bordered table-striped tablesorter">
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
						Project Title
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Project Category
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Judges Assigned
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($participant as $row){
						echo '<tr>';
						
						echo '<td>' . $row->pln . '</td>';
						echo '<td>' . $row->pfn . '</td>';
						echo '<td>' . $row->ppt . '</td>';
						echo '<td>' . $row->pjc . '</td>';
						echo '<td>' . $row->ppc . '</td>';
						echo '<td><button class="btn wsu_btn" href="#"><i class="icon-pencil"></i></button>
						<button class="btn wsu_btn" href="#"><i class="icon-print"></i></button>';
					}
				?>
			</tbody>
		</table>
	</div>

</body>
</html>