
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
	
	if($this->uri->segment(3)==true || !empty($this->uri->segment(3))){
		echo'
		<div class="page">
			<button class="printButton pull-right btn wsu_btn btn-medium" onclick="printScorecard();">Print</button>';
			if(isset($project->abstract)){
				echo '<iframe width="100%" height="100%" name="plugin" src="'.base_url().'abstract_uploads/'.$project->username.'_abstract.pdf"></iframe';
			}
		echo'
		</div>';
	}
	?>

	<div class="page" id="page">
	<?php
		if($this->uri->segment(3)==false || empty($this->uri->segment(3))){
			echo'<button class="printButton pull-right btn wsu_btn btn-medium" onclick="printScorecard();">Print</button>';
		}
	?>

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
