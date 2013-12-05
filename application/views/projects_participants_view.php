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
			$("#project_participants_table").tablesorter( {
				sortList: [[0,0]],
				cssChildRow: "tablesorter-childRow"
			});

			$("[rel=tooltip]").tooltip({ placement:'top'});
		});	

		function printScorecard(id){
			window.open('<?php echo base_url()."scores/participant_scorecard/";?>'+id, 'Scorecard', 'width=700, height=700');
		};

		function printAllScorecards(){
			window.open('<?php echo base_url()."scores/all_participant_scorecards";?>', 'Scorecard', 'width=700, height=700');
		};
	</script>
</head>
<body>
	<div class="page-header" id="wsu_header">
		<div class="row-fluid">
			<div class="span12">
				<div class="span3 pull-left">
					<a href="http://www.wayne.edu"><img id="wsu_logo" src="<?php echo (IMG.'wsu-wordmark.gif');?>"/></a>
				</div>
				<div class="span2 offset7">
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

<?php 
	if($this->session->userdata('role')=='admin'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="active span2"><a id="nav_projects">Projects</a></li>
					<li class="span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
					<li class="span3"><a id="nav_manageusers" href="'.base_url()."manage_users/participant".'">Manage Users</a></li>
					<li class="span3"><a id="nav_systemsettings" href="'.base_url()."settings/general".'">System Settings</a></li>
				</ul>
			</div>
		</div>';
	}
	elseif($this->session->userdata('role')=='seu' || $this->session->userdata('role')=='judge'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="active span2"><a id="nav_projects">Projects</a></li>
					<li class="span2"><a id="nav_scores" href="'.base_url()."scores/input".'">Scores</a></li>
				</ul>
			</div>
		</div>';
	}
	elseif($this->session->userdata('role')=='participant'){
		echo '
		<div class="navbar wsu_navbar row-fluid">
			<div class="navbar-inner span12">
				<ul class="nav text-center">
					<li class="span1"><a id="nav_home" href="'.base_url()."gerss/home".'"><img alt="home" src="'.IMG.'home.png'.'"></img></a></li>
					<li class="active span2"><a id="nav_projects">Projects</a></li>
				</ul>
			</div>
		</div>';
	}
?>

	<div class="hero-unit wsu_hero_unit">
        <?php
        if($this->session->userdata('role')=='admin' || $this->session->userdata('role')=='seu'){
        	$id=1;
        	echo'
			<form class="form-search" method="post" action="'.base_url()."gerss/search/$id".'">
				<div class="input-append">
					<input type="text" name="search"  id="search" class="input-medium search-query" placeholder="'."Participant's".' Last Name">
					<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
				</div>
				<a class="btn wsu_btn" id="clear" href="'.base_url()."gerss/projects_participants".'">Clear</a>
			</form>';
		}
		?>

		

		<ul class="nav nav-tabs">
			<?php if($this->session->userdata('role')!='participant'){echo '<div id="group_by_text">Group By:</div>';}?>
			<li class="active"><a href="<?php echo base_url()."gerss/projects_participants"?>">Participants</a></li>
			<?php if($this->session->userdata('role')!='participant'){echo '<li><a href="'.base_url()."gerss/projects_judges".'">Judges</a></li>';}?>
			<?php if($this->session->userdata('role')=='admin'){echo '<a class="btn wsu_btn pull-right" href="'.base_url()."manage_users/add?type=".urlencode("participant").'" id="btn_add_judge"><i class="icon-plus"></i> Add Participant</a>';}?>
			<?php if($this->session->userdata('role')=='admin'){echo '<button class="btn wsu_btn pull-right enable_buttons" onclick="printAllScorecards();" id="btn_print_scorecards"><i class="icon-print"></i> Print All Scorecards</button>';}?>

		</ul>

		<table id="project_participants_table" class="table wsu_table table-bordered table-striped tablesorter" style="border-collapse:collapse;">
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
					if(!empty($participant)){
						foreach($participant as $row){
							
							$parts=$row->username;
							$username=$parts;

							echo '<tr>';
							
							echo '<td>' . $row->lastname . '</td>';
							echo '<td>' . $row->firstname . '</td>';
							echo '<td>' . $row->category . '</td>';

							$judge_count=0;
							foreach($judge_assignment as $judge){
								if($judge->project_id == $row->project_id){
									$judge_count+=1;
								}
							}
							echo 
							'<td>' . $judge_count;
								if($judge_count==0){
									echo '<i class="icon-exclamation-sign pull-right wsu_tooltip" rel="tooltip" title="No Judges Assigned"></i>';
								}
							echo'
							</td>
							<td>
								<button data-toggle="collapse" data-target="#project_'.$row->project_id.'_info" class="accordion-toggle btn wsu_btn wsu_tooltip"  rel="tooltip" title="Project Info"><i class="icon-eye-open"></i> </button> ';
								if($this->session->userdata('role')=='admin' || $this->session->userdata('role')=='participant'){
									echo ' <a class="btn wsu_btn wsu_tooltip" href="'.base_url()."manage_users/edit/".$row->usertype."/".$username.'" rel="tooltip" title="Edit Project Info"><i class="icon-pencil"></i> </a>';
								}
								if($this->session->userdata('role')!='participant'){
									echo' <button class="btn wsu_btn wsu_tooltip" onClick="printScorecard('.$row->id.')" rel="tooltip" title="Print Scorecard"><i class="icon-print"></i></button>';
								}
							echo'
							</td>
							
						</tr>
						<tr class="tablesorter-childRow">
							<td colspan="5" class="hiddenRow">'.'
								<div class="accordian-body collapse" id="project_'.$row->project_id.'_info">
									<div class="row-fluid hidden_row_headers">
										<div class="span3 offset1 text-center">
											<em>Project Title</em>
										</div>
										<div class="span6 offset1 text-center">
											<em>Project Description</em>
										</div>
									</div>
									<hr>
									<div class="row-fluid">
										<div class="span3 offset1 text-center">'.
											$row->title .'
										</div>
										<div class="span6 offset1 text-center">'.
											$row->description .'
										</div>
									</div>
									<hr>
								</div>
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
<!--value="-->
</html>