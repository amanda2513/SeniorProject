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
			$("#users_participants_table").tablesorter( {sortList: [[0,0]]});
		});	
	</script>
</head>
<body>
	
	<div class="page-header" id="wsu_header">
		<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
		<div class="wsu_sign_in_container pull-right">
            <a class="btn btn-small wsu_btn" id="sign_out_btn" href='<?php echo base_url(). "gerss/logout"; ?>'>Sign Out</a>
		</div>
	</div>

	<div class="navbar wsu_navbar">
		<div class="navbar-inner">
				<a class="btn btn-navbar" id="collapsed_menu_btn" data-toggle="collapse" data-target=".nav-collapse">
					<span class = "icon-th-list"></span>
				</a>
				<div class = "nav-collapse collapse">
					<ul class="nav text-center">
						<li><a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a></li>
						<li><a id="nav_scores" href="#">Scores</a></li>
						<li class="active"><a id="nav_manageusers" href="#">Manage Users</a></li>
						<li><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
					</ul>
				</div>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		
		<!-- Output user session details-->
		<?php
			/*echo "<pre>";
			print_r($this->session->all_userdata());
			echo "</pre>";*/
		?>


		<h2 class="wsu_h2 text-center">Graduate Exhibition Registration &amp; Scoring System</h2>
        
<!----------------------------------------------------------Search Form---------------------------------------------------------------->
		
		<form class="form-search"  method="post" action='<?php $id=1; echo base_url()."manage_users/search_users_participant_view/$id"; ?>'>
			<div class="input-append">
				<input type="text" name="search_participant" id="search_participant" class="input-medium search-query" placeholder="Participant's Last Name">
				<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
			</div>
			<a class="btn wsu_btn" id="clear" href="<?php echo base_url()."manage_users/participants";?>">Clear</a>
		</form>

		

		<ul class="nav nav-tabs">
			<li class="active"><a href='<?php echo base_url()."manage_users/participants"?>'>Participants</a></li>
			<li><a href='<?php echo base_url()."manage_users/judges"?>'>Judges</a></li>
			<li><a href='<?php echo base_url()."manage_users/seu"?>'>Score Entry Users</a></li>
			<li><a href='<?php echo base_url()."manage_users/admin"?>'>Admin</a></li>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."manage_users/add?type=".urlencode("participant");?>" id="btn_add_participant"><i class="icon-plus"></i> Add Participant</a>
		</ul>

		<table id="users_participants_table" class="table wsu_table table-bordered table-striped tablesorter">
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
						Status
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(!empty($participant)){
						foreach($participant as $row){
							echo '<tr>';
							
							echo '<td>' . $row->lastname . '</td>';
							echo '<td>' . $row->firstname . '</td>';
							echo '<td> TODO</td>';
							echo '<td><button class="btn wsu_btn" href="#"><i class="icon-lock"></i></button> <button class="btn wsu_btn" href="#"><i class="icon-pencil"></i></button>
							<button class="btn wsu_btn" href="#"><i class="icon-trash"></i></button>';

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