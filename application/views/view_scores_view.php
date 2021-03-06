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
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.pager.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.metadata.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#scores_table").tablesorter({
				sortForce: [[4,1]],
				sortList: [[4,1]],
				cssChildRow: "tablesorter-childRow"
			});

			var row_count= ($('#scores_table tr').length-1)/2;

			$('#top_n_filter').attr('max',row_count);

			$("#scores_table")
		    .bind("pagerChange pagerComplete pagerInitialized pageMoved",function(e, table) {
		      	var visible_rows= document.getElementById('top_n_filter').value;
				$("tr").eq(visible_rows*2).removeAttr("style");
		    }).tablesorterPager({
				container: $("#pager"), size: row_count});;

			$("[rel=tooltip]").tooltip({ placement:'top'});
		});

		function printScores(){
			window.open('<?php echo base_url()."scores/view/print";?>', 'Scores', 'width=900, height=700');
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
				<div class="span4 pull-right">
					<div class="span4 offset4" id="wsu_user_welcome">
						Welcome,<br><?php echo $this->session->userdata('fn')?> <?php echo $this->session->userdata('ln')?>
					</div>
					<div class="span4">
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
		<div class="row-fluid">
			<div class="span5">
				<form class="form-search" method="post" action='<?php echo base_url()."scores/view/";?>'>
					<div class="input-append">
						<input type="text" name="search_participants" id="search_participants" class="input-large search-query" placeholder="Participant's Last Name">
						<button class="btn wsu_btn" type="submit" id="search"><i class="icon-search"></i> Search</button>
					</div>
					<a class="btn wsu_btn" id="clear" href="<?php echo base_url()."scores/view";?>">Clear</a>
				</form>
			</div>
			<div class="span7">
				<div class="row-fluid">
					<div class="span5">
						<form class="form-horizontal" id="filter_score_results" method="post" action="<?php echo base_url().'scores/view/filter';?>">
							<select name="category" class="input-large" id="category_filter" onChange="this.form.submit()">
							<?php
								echo' <option value="0">Filter by Category</option>';
								foreach($categories as $category){
									if($selected_category&&$selected_category==$category->category){
										$selected = 'selected="selected"';
									}
									else{
										$selected = '';
									}
									echo '<option value="'.$category->category.'" '.$selected.'>'. $category->category.'</option>';
								}
							?>
							</select>
						</form>
					</div>
					<div class="span3 ">
						<div id="pager">
							Top N: <input type="number" min="1" class="pagesize input-mini text-center" placeholder="Top N" name="top_n" id="top_n_filter">
						</div>
					</div>
					<div class="span3">
						<a class="btn wsu_btn" id="clear_filter" href="<?php echo base_url()."scores/view";?>">Clear Filters</a>
					</div>
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."scores/input"?>">Input Scores</a></li>
			<li class="active"><a href="<?php echo base_url()."scores/view"?>">View Scores</a></li>
			<li class="pull-right"><button class="btn wsu_btn" onclick="printScores()"><i class="icon-print"></i> Print Scores</button>
		</ul>

		
		<table id="scores_table" class="table wsu_table table-bordered table-striped tablesorter table-condensed" style="border-collapse:collapse;">
			<thead>
				<tr>
					<th>
						Participant Name
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Category
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th class="{sorter:'digit'}">
						Scores Entered
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th class="{sorter:'digit'}">
						Total Score
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th class="{sorter: 'digit'}">
						Percent
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(!empty($projects)){
						foreach($projects as $project){
							$id = $project->project_id;
							echo 

							'<tr>
							
								<td>' . $project->lastname. ', '. $project->firstname . '</td>
								<td>' . $project->category . '</td>
								<td>';
									if($project->judge_entry_count!=$project->judge_count){
										echo ' <i class="icon-exclamation-sign pull-right wsu_tooltip" rel="tooltip" title="Missing Judge Scores"></i>';
									}
									echo $project->judge_entry_count.'/'.$project->judge_count.'
								</td>
								<td>
									<div>'.
										$project->total_averaged_score. ' / ' . $project->category_pts_possible.'
									</div>
								</td>
								<td>'.
										round($project->total_averaged_score/$project->category_pts_possible*100,2) .'%
									</div>
								</td>
								<td>
									<button data-toggle="collapse" data-target="#scores_'.$project->lastname.'" class="accordion-toggle btn wsu_btn wsu_tooltip"  rel="tooltip" title="Score Breakdown"><i class="icon-eye-open"></i></button>
								</td>

							</tr>
							<tr class="tablesorter-childRow">
								<td colspan="7" class="hiddenRow">
									<div class="accordian-body collapse score_breakdown" id="scores_'.$project->lastname.'">
										<div class="row-fluid hidden-phone hidden_row_headers">
											<div class="span3 text-center">
												<strong>Subcategory</strong>
											</div>
											<div class="span6 text-center">
												<strong class="text-center">Criteria Scores</strong>
											</div>
											<div class="span3 text-center">
												<strong>Subcategory Total</strong>
											</div>
										</div>
										<hr>';
										foreach($project->subcategories as $subcat){
										echo'<div class="row-fluid">';
											echo 
												'<div class="span3 text-center">'.
													$subcat->subcat_name . '
												</div>
												<div class="span6">';
												$subcat_pts_possible=0;
												$subcat_pts_earned=0;
											foreach($subcat->criteria as $criterion){
												echo ' 
													<div class="row-fluid">
														<div class="span5 offset2">'.
															$criterion->criteria_description . ': '.'
														</div>
														<div class="span2 text-center">'.
															 round($criterion->avg_points,2) . ' / '. $criterion->criteria_points
														 .'
														 </div>
													</div>';
												$subcat_pts_earned+=round($criterion->avg_points,2);
												$subcat_pts_possible+=$criterion->criteria_points;
											}
										  echo '</div>
											  	<div class="span3 text-center">'.
											  		$subcat_pts_earned. ' / ' . $subcat_pts_possible .'
											  	</div>
											</div>
											<hr>';
										}
								echo'</div>
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