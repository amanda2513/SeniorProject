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
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#scores_table").tablesorter( {sortList: [[0,0]]});

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

	<div class="navbar wsu_navbar row-fluid">
		<div class="navbar-inner span12">
			<ul class="nav text-center">
				<li class="span1"><a id="nav_home" href="<?php echo base_url()."gerss/home"?>"><img alt="home" src="<?php echo (IMG.'home.png');?>"></img></a></li>
				<li class="span2"><a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a></li>
				<li class="active span2"><a id="nav_scores" href="<?php echo base_url()."scores/view"?>">Scores</a></li>
				<li class="span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		
		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."scores/input"?>">Input Scores</a></li>
			<li class="active"><a href="<?php echo base_url()."scores/view"?>">View Scores</a></li>
		</ul>

		
		<table id="scores_table" class="table wsu_table table-bordered table-striped tablesorter">
			<thead>
				<tr>
					<th>
						Participant Name
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Project Title
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Category
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Scores Entered
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Total Score
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(!empty($projects)){
						foreach($projects as $project){

							echo 

							'<tr>
							
								<td>' . $project->lastname. ', '. $project->firstname . '</td>
								<td>' . $project->title . '</td>
								<td>' . $project->category . '</td>
								<td>';
									if($project->judge_entry_count!=$project->judge_count){
										echo ' <i class="icon-exclamation-sign pull-right wsu_tooltip" rel="tooltip" title="Missing Judge Scores"></i>';
									}
									echo $project->judge_entry_count.' / '.$project->judge_count.'
								</td>
								<td>
									<div >'.
										$project->total_averaged_score.' / '.$project->category_pts_possible.'</td>
									</div>
								<td>
									<a class="btn wsu_btn wsu_tooltip" href="#"  rel="tooltip" title="View More"><i class="icon-eye-open"></i></a>
								</td>

							</tr>';
						}
					}else{
						echo "<td>No results found.</td>";
					}
				?>
                

			</tbody>
		</table>


	</div><!--close hero-unit-->
</body>
</html>