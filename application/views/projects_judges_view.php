<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#project_judges_table").tablesorter( {sortList: [[0,0]]});
		});	
	</script>
</head>
<body>
		
	<div class="page-header" id="wsu_header">
		<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
			<a class="btn btn-small wsu_btn" id="sign_out_btn" href="logout">Sign Out</a>
		</div>
	</div>

	<div class="navbar wsu_navbar">
		<div class="navbar-inner">
			<a class="btn btn-navbar" id="collapsed_menu_btn" data-toggle="collapse" data-target=".nav-collapse">
				<span class = "icon-th-list"></span>
			</a>
			<div class = "nav-collapse collapse">
				<ul class="nav text-center">
					<li class="active"><a id="nav_projects" href="gerss/projects_participants">Projects</a></li>
					<li><a id="nav_scores" href="#">Scores</a></li>
					<li><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participants"?>">Manage Users</a></li>
					<li><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
				</ul>
			</div>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

		<h2 class="wsu_h2 text-center">Graduate Exhibition Registration &amp; Scoring System</h2>
<!----------------------------------------------------------Search Form---------------------------------------------------------------->

		<form class="form-search" method="post" action='<?php $id=2; echo base_url()."gerss/search/$id"; ?>'>
			<div class="input-append">
				<input type="text" name="search_judges"  id="search_judges" class="input-medium search-query" placeholder="Judge's Last Name">
				<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
			</div>
			<a class="btn wsu_btn" id="clear" href="<?php echo base_url()."gerss/projects_judges";?>">Clear</a>
		</form>

		<ul class="nav nav-tabs">
			<div id="group_by_text">Group By:</div>
			<li><a href="<?php echo base_url()."gerss/projects_participants"?>">Participants</a></li>
			<li class="active"><a href="<?php echo base_url()."gerss/projects_judges"?>">Judges</a></li>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."manage_users/add?type=".urlencode("judge");?>" id="btn_add_judge"><i class="icon-plus"></i> Add Judge</a>
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
				<?php
					if(!empty($judge)){
						foreach($judge as $row){
							echo '<tr>';
							
							echo '<td>' . $row->lastname . '</td>';
							echo '<td>' . $row->firstname . '</td>';
							echo '<td>' . $row->department . '</td>';
							echo '<td>' . $row->title . '</td>';
							echo '<td>' . $row->category . '</td>';
							echo '<td>
							<a class="btn wsu_btn" href="';echo base_url()."manage_users/edit/".$row->id."?type=".$row->usertype;echo'"><i class="icon-pencil"></i></a>
							<button class="btn wsu_btn" href="#"><i class="icon-print"></i></button>';
						echo '</tr>';
						}
					}
					else{
						echo "<td>No results found.</td>";
					}
				?>
			</tbody>
		</table>
	</div>
</body>
</html>