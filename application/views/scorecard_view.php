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
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript">	
		$(document).ready(function() {		
			$("#project_participants_table").tablesorter( {sortList: [[0,0]]});
		});	

		function printScorecard(){
			//var printButton = document.getElementsByTagName("button");
			$('.printButton').hide();
			window.print();
			$('.printButton').show();
		}
	</script>
</head>

<body>
	<?php
		if($this->session->userdata('role')=='judge'){
			$judge_name = $this->session->userdata('ln').', '.$this->session->userdata('fn');
		}
		elseif(isset($judge)){
			$judge_name = $judge->lastname.', '.$judge->firstname;
		}
		else{
			$judge_name = "<input type='text' class='input-medium'>";
		}
	?>

	<div class="page">
		<button class="printButton pull-right btn wsu_btn btn-medium" onclick="printScorecard();">Print</button>	
		<?php
		
		if(isset($project->abstract)){
			//echo '<iframe src="'.base_url().'abstract_uploads/'.$project->username.'_abstract.pdf" width="100%" height="810" seamless></iframe>';
			echo '<iframe width="100%" height="100%" name="plugin" src="'.base_url().'abstract_uploads/'.$project->username.'_abstract.pdf"></iframe';
		}
		?>
	</div>

	<div class="page">
		


		<div class="page-header" id="print_header">
			<div id="scorecard_title" class="text-center">
				<img src="<?php echo (IMG.'warrior_logo3.jpg');?>"/>
				<strong>Graduate Exhibition Scorecard</strong>
			</div>
		</div>

		<table class="table wsu_table table-bordered table-striped">
		<tr>
			<td><strong>Judge: </strong><?php echo $judge_name;?></td>
			<td><strong>Participant: </strong><?php echo $project->lastname.', '.$project->firstname;?></td>
			<td><strong>Category: </strong><?php echo $project->category?></td>
		</tr>
		</table>

		<table class="table wsu_table table-bordered table-striped">
		<thead>
			<tr>
				<th>Subcategories</th>
				<th>Criteria</th>
				<th>Criteria Scores</th>
				<th>Subtotals</th>
			</tr>
		</thead>
		<tbody>
		<?php
			for($i=0;$i<count($subcats);$i++){

				echo'
					<tr>
						<td rowspan="'.$subcats[$i]['criteria_count'].'">'.$subcats[$i]['name'].'</td>
						<td>'.$subcats[$i]['crits'][0]['desc'].'</div></td>
						<td><div class="text-right">/'.$subcats[$i]['crits'][0]['points'].'</div></td>
						<td rowspan="'.$subcats[$i]['criteria_count'].'"><div class="text-right">/'.$subcats[$i]['subcat_score'].'</div></td>
					</tr>';


				for($j=1;$j<$subcats[$i]['criteria_count'];$j++){
				echo'
					<tr>
						<td>'.$subcats[$i]['crits'][$j]['desc'].'</td>
						<td><div class="text-right">/'.$subcats[$i]['crits'][$j]['points'].'</div></td>
					</tr>';
				}
			}
			
		echo'
			<tr><td colspan="4"><div class="text-right"><strong>TOTAL SCORE: </strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; /'.$project->category_pts_possible.'</div></td></tr>
			</tbody>
			</table>';
		?>
	</div>
</body>
</html>