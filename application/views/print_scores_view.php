<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'scorecard_print.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.tablesorter.pager.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery.metadata.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">		
		$(document).ready(function() {		
			$("#scores_table").tablesorter({
				sortList: [[5,1]]
			});
		});

		function printScores(){
			var printButton = document.getElementById("print_btn");
			printButton.style.visibility = 'hidden';
			window.print();
			printButton.style.visibility='visible';
		}
	</script>
</head>

<body>
	<div class="page-header" id="print_header">
		<div id="scorecard_title" class="text-center">
			<img src="<?php echo (IMG.'warrior_logo3.jpg');?>"/>
			<strong>Graduate Exhibition Scores</strong>
		</div>
	</div>

	<div class="pull-left">
		<button class="btn wsu_btn" id="print_btn" onClick="printScores()">Print</button>
	</div>

	<div class="pull-right">
		Year: <?php echo date('Y');?>
	</div>


	<table id="scores_table" class="table wsu_table table-bordered table-striped tablesorter table-condensed" style="border-collapse:collapse;">
			<colgroup>
				<col span="1" style="width: 20%;">				
				<col span="1" style="width: 20%;">
				<col span="1" style="width: 30%;">
				<col span="1" style="width: 15%;">
				<col span="1" style="width: 15%;">
			</colgroup>
			<thead>
				<tr>
					<th>
						Participant Name
					</th>
					<th>
						Project Title
					</th>
					<th>
						Category
					</th>
					<th>
						Total Score
					</th>
					<th>
						Percent
					</th>
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
								<td>' . $project->title . '</td>
								<td>' . $project->category . '</td>
								<td>
									<div>'.
										$project->total_averaged_score. ' / ' . $project->category_pts_possible.'
									</div>
								</td>
								<td>'.
										round($project->total_averaged_score/$project->category_pts_possible*100,2) .'%
									</div>
								</td>
							</tr>';
						}
					}
				?>
			</tbody>
		</table>
</body>
</html>