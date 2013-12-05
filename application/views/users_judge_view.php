<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootbox.min.js');?>"></script>
	
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#users_judge_table").tablesorter( {sortList: [[0,0]]});

			$("[rel=tooltip]").tooltip({ placement:'top'});
		});	
	</script>
	<script type="text/javascript">
		function confirmModal(id){
			bootbox.confirm("Are you sure you delete this?",function(result){
				if(result){
					window.location.href='<?php echo base_url()."manage_users/delete/judge/";?>'+id;
				}
			});
		}
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
				<li class="span2"><a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a></li>
				<li class="active span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/judge"?>">Manage Users</a></li>
				<li class="span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
		
		<!-- Output user session details-->
		<?php
			/*echo "<pre>";
			print_r($this->session->all_userdata());
			echo "</pre>";*/
		?>

		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  			//If I successfully added/edited/deleted, show success div
  			if($this->session->flashdata('success')){
  				echo '<div class="alert alert-success text-center" id="wsu_alert">';
  				echo $this->session->flashdata('success');
  				echo '</div>';
  			}
  		?>

		<form class="form-search" method="post" action='<?php $id=2; echo base_url()."manage_users/search_users_participant_view/$id"; ?>'>
			<div class="input-append">
				<input type="text" name="search_judge" id="search_judge" class="input-medium search-query" placeholder="Judge's Last Name">
				<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
			</div>
			<a class="btn wsu_btn" id="clear" href="<?php echo base_url()."manage_users/judge";?>">Clear</a>
		</form>

		

		<ul class="nav nav-tabs">
			<li><a href='<?php echo base_url()."manage_users/participant"?>'>Participants</a></li>
			<li class="active"><a href='<?php echo base_url()."manage_users/judge"?>'>Judges</a></li>
			<li><a href='<?php echo base_url()."manage_users/seu"?>'>Scorers</a></li>
			<li><a href='<?php echo base_url()."manage_users/admin"?>'>Admin</a></li>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."manage_users/add?type=".urlencode("judge");?>" id="btn_add_judge"><i class="icon-plus"></i> Add Judge</a>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."manage_users/disable_all/judge";?>" id="disable_all_judge"><i class="icon-lock"></i> Disable All</a>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."manage_users/enable_all/judge";?>" id="enable_all_judge"><i class="icon-lock"></i> Enable All</a>
		</ul>

		<table id="users_judge_table" class="table wsu_table table-bordered table-striped tablesorter">
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
						Access
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if(!empty($judge)){
						foreach($judge as $row){
							
							$parts=$row->username;
							$username=$parts;

							echo 

							'<tr>
								<td>' . $row->lastname . '</td>
								<td>' . $row->firstname . '</td>';
								if($row->status=="Enabled"){
									echo'
									<td>' . $row->status .'</td>
									<td>
										<a class="btn wsu_btn wsu_tooltip" rel="tooltip" title="Disable Judge Access" href="'.base_url()."manage_users/change_user_status/judge/Disabled/".$row->id.'"><i class="icon-lock"></i></a>';
								}
								else{
									echo'
									<td>' . $row->status .' <i class="icon-lock"></i></td>
									<td>
										<a class="btn wsu_btn wsu_tooltip" rel="tooltip" title="Enable Judge Access" href="'.base_url()."manage_users/change_user_status/judge/Enabled/".$row->id.'"><i class="icon-lock"></i></a>';
								}
								echo'
									<a class="btn wsu_btn wsu_tooltip" rel="tooltip" title="Edit Judge Info" href="'.base_url()."manage_users/edit/".$row->usertype."/".$username.'"><i class="icon-pencil"></i></a>
									<button class="btn wsu_btn wsu_tooltip" rel="tooltip" title="Delete Judge" onClick="confirmModal('."'".$row->id ."'".')"><i class="icon-trash"></i></button>
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