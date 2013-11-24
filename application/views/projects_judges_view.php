<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#project_judges_table").tablesorter( {sortList: [[0,0]]});
			$("[rel=tooltip]").tooltip({ placement:'top'});
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
				<li class="active"><a id="nav_projects" href="<?php echo base_url()."gerss/projects_judges"?>">Projects</a></li>
				<li><a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a></li>
				<li><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

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
						Projects Assigned
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(!empty($judge)){
						foreach($judge as $row){
							
							$parts=explode("@",$row->email);
							$username=$parts[0];
							echo 
							'<tr>
								<td>' . $row->lastname . '</td>
								<td>' . $row->firstname . '</td>
								<td>' . $row->department . '</td>

								
								<td>' . $row->assignment_count;
									if($row->assignment_count==0){
											echo ' <i class="icon-exclamation-sign pull-right wsu_tooltip" rel="tooltip" title="No Assigned Projects"></i>';
										}
									echo'
								</td>

								<td>
									<a class="btn wsu_btn wsu_tooltip" href="'.base_url()."manage_users/edit/".$row->usertype."/".$username.'" rel="tooltip" title="Edit User Info"><i class="icon-pencil"></i></a>
									<button class="btn wsu_btn wsu_tooltip" href="#" rel="tooltip" title="Print Scorecard"><i class="icon-print"></i></button>
								</td>
							</tr>';
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