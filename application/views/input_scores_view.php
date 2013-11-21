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
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>

	<script type="text/javascript">
	$(window).load(function() {
		$("#select_judge").change(function() {
			if($(this).data('options') == undefined){
				$(this).data('options',$('#select_participant option').clone());
			}
			var id = $(this).val();
			var options = $(this).data('options').filter('[value=' + id + ']');
			$('#select_participant').html(options);

			document.getElementById('select_participant').disabled = false;
		});
	});
	</script>
	<script type="text/javascript">
		function set_url(){
			var initialURL = <?php echo "'".base_url()."scores/input/"."'";?>;
			var judge_id = select_judge.options[select_judge.selectedIndex].value;
			var participant = select_participant.options[select_participant.selectedIndex].text;
			var participant_name = participant.split(', ');
			window.location.href = initialURL + judge_id +  '/' + participant_name[0]  + '/' +participant_name[1];
		};

		
		var $subcategory_count=0;
		//Each subcategory has 1 set of criteria by default, this is incremented in add_subcat_criteria method
		var subcategory_criteria_count = new Array();
		subcategory_criteria_count[0]=1; 

		//used for populating subcategory fields with what's already in database when page loads
		function populate_subcategory(subcat_name,db_id){
			//count is used to assign unique names to each element and is assigned to subcat_id for readability below. subtracting 1 because array is zerobased and i want id = array[]
			$subcategory_count += 1;
			var $subcat_id=$subcategory_count-1;
			//add another item to subcategories array
			subcategory_criteria_count[$subcat_id]=0;

			var $input = 
			'<div class="row-fluid">' +
				'<div class="span-center span3 text-center">' +
					subcat_name +
				'</div>' +
				'<div class="span6">' +
					'<div id="dynamic_subcat'+$subcat_id+'_criteria">'+
						'<hr class="gray">' +
					'</div>' +
				'</div>'+
				'<div class="span-center span2 offset1">' +
					'<div id="subcat_'+$subcat_id+'_score">'+
						
					'</div>' +
				'</div>'+
			'</div>';

			//append fields to the div with id dynamic_fields
			$('#dynamic_fields').append($input);
		};

		//used for populating criteria fields with what's already in the database when page loads
		function populate_subcat_criteria(subcat_id, desc, points, db_criteria_id){
			subcategory_criteria_count[subcat_id]+=1;

			var $input = 
			'<div class="control-group" id="control-group_'+subcat_id+'_'+subcategory_criteria_count[subcat_id]+'">'+
				'<div class="span4 offset3">' +
					desc +
				'</div>' +
				'<div class="span5">' +
					'<input type="hidden" name="subcategory['+subcat_id+'][criteria]['+subcategory_criteria_count[subcat_id]+'][id]" value="'+db_criteria_id+'">'+
					'<input class="input-mini" type="number" min="0" max="'+points+'" name="subcategory['+subcat_id+'][criteria]['+subcategory_criteria_count[subcat_id]+'][score]" id="total_subcat'+subcat_id+'_criteria'+subcategory_criteria_count[subcat_id]+'_score" onchange="updateSubtotal('+subcat_id+')">/'+points+
				'</div>'+
			'</div>' +

			'<hr class="gray">';

			//append fields to the div with id dynamic_subcat[#]_criteria
			$('#dynamic_subcat'+subcat_id+'_criteria').append($input);
		};

		function append_subtotal(subcat_id,subcat_points){
			var field = '<input id="total_subcat_'+subcat_id+'_score" class="input-mini" type="text" disabled>/'+subcat_points;
			$('#subcat_'+subcat_id+'_score').append(field);
		};

		function updateSubtotal(subcat_id){
			var subtotal = 0;
			for(var i=1;i<=subcategory_criteria_count[subcat_id];i++){
				var criteria_value = document.getElementById('total_subcat'+subcat_id+'_criteria'+i+'_score').value;
				if(criteria_value != "")
					subtotal+= parseInt(criteria_value);
			}
			var subtotal_field = document.getElementById('total_subcat_'+subcat_id+'_score');
			subtotal_field.value = subtotal;

			updateTotal();
		};

		function updateTotal(){
			var total = 0;
			for(var i=0;i<$subcategory_count;i++){
				var subcat_value = document.getElementById('total_subcat_'+i+'_score').value;
				if(subcat_value != "")
					total+= parseInt(subcat_value);
			}
			var total_field = document.getElementById('total_score');
			total_field.value = total;
		};


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
					<li>
						<a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a>
					</li>
					<li class="active">
						<a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a>
					</li>
					<li>
						<a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a>
					</li>
					<li>
						<a id="nav_systemsettings" href="<?php echo base_url()."settings/categories"?>">System Settings</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>
		
		<ul class="nav nav-tabs">
			<li class="active"><a href="<?php echo base_url()."scores/input"?>">Input Scores</a></li>
			<li><a href="<?php echo base_url()."scores/view"?>">View Scores</a></li>
			<?php
				if($this->uri->segment(3)){
					echo'<a href="'.base_url().'scores/input" class="btn wsu_btn pull-right">Change Judge & Participant</a>';
				}
			?>
		</ul>

		<?php
		//If there are errors print them all in a bootstrap alert div
			if($this->session->flashdata('errors')){
				echo '<div class="alert text-center" id="wsu_alert">';
				echo $this->session->flashdata('errors');
				echo '</div>';
  			}
  			//If I successfully added a user, show success div
  			if($this->session->flashdata('success')){
  				echo '<div class="alert alert-success text-center" id="wsu_alert">';
  				echo $this->session->flashdata('success');
  				echo '</div>';
  			}
  			//If there are no errors, show "Fill out form" message
  			else{
  				echo '<br>';
  			}

  			if($this->uri->segment(3)){
  				$option_selected= 'disabled';
  			}
  			else{
  				$option_selected= '';
  			}
  		?>

		<form class="form-horizontal" name="set_users" id="set_users" method="">
			<div class="row-fluid">
				<div class="span12">
					<div class="control-group span6">
						<label class="control-label" for="judge">Judge:</label>
						<div class="controls inline" name="judge">
							<select name="select_judge" id="select_judge" <?php echo $option_selected;?>>
								<option value="0">Select a Judge</option>
								<?php
									foreach($judges as $judge){
										$parts=explode("@",$judge->email);
										$username=$parts[0];
										if($option_selected){
											$selected = " selected='selected'";
										}
										else{
											$selected = "";
										}
										echo '<option value="'.$judge->id.'"'.$selected.'>'.$judge->lastname.', '.$judge->firstname.' - '.$username.'</option>';
									}
								?>
							</select>
						</div>
					</div>

					<div class="control-group span6">
						<label class="control-label" for="participant">Participant:</label>
						<div class="controls inline" name="judge">
							<select name="select_participant" id="select_participant" onChange="set_url()" disabled>
								<option value="0"></option>
								<?php
									foreach($judges as $judge){
										echo '<option value="'.$judge->id.'"></option>';
									}
									foreach($assignments as $assignment){
										if($assignment->lastname==$this->uri->segment(4) && $assignment->firstname==$this->uri->segment(5)){
											$selected = " selected='selected'";
										}
										else{
											$selected = "";
										}
										echo '<option value="'.$assignment->judge_id.'"'.$selected.'>'.$assignment->lastname.', '.$assignment->firstname.'</option>';
									}
								?>
							</select>
						</div>
					</div>
				</div>
			</div>
		</form><!--close judge/participant form-->

		<?php
			
		if($option_selected){

		echo '

			<form class="form-horizontal" name="score_entry" id="score_entry" method="post" accept-charset="utf-8" action='.base_url()."scores/score_validation".'>
				<div class="row-fluid">
					<div class="span12">
						<strong>Category: </strong>'.$category->category.'
					</div>
				</div>

				<hr>

				<div class="row-fluid hidden-phone">
					<div class="span3 text-center">
						<strong>Subcategory</strong>
					</div>
					<div class="span6 text-center">
						<strong class="text-center">Criteria Scores</strong>
					</div>
					<div class="span3 text-center">
						<strong>Total Scores</strong>
					</div>
				</div>

				<hr>


				<div id="dynamic_fields">';
				
					$current_cat_id = $category->cat_id;
					$total_points = 0;
					$subcat_count = 0;
					foreach($subcategory as $subcat)
					{
						$subcat_points = 0;
						$current_subcat_id = $subcat->subcat_id;

						if($subcat->cat_id == $current_cat_id)
						{

						echo '<script type="text/javascript">
								populate_subcategory("'.$subcat->subcat_name.'",'.$subcat->subcat_id.');
							  </script>';

							foreach($subcat_criteria as $criteria)
							{
								if($criteria->subcat_id == $current_subcat_id)
								{
									echo 
									'<script type="text/javascript">
										populate_subcat_criteria('.$subcat_count.',"'.$criteria->criteria_description.'",'.$criteria->criteria_points.','.$criteria->criteria_id.');
							  		</script>';
									$subcat_points += $criteria->criteria_points;
								}
							}
							echo
								'<script type="text/javascript">
									append_subtotal('.$subcat_count.','.$subcat_points.');
							  	</script>';
							$subcat_count += 1;
							$total_points +=$subcat_points;
						echo '<hr>';
						}
					}
				
			
		   echo '</div>
		   		<div class="row-fluid hidden-phone">
			   		<div class="span12">
			   			<div class="span9 offset1 text-right">
			   				Total Score:
			   			</div>
			   			<div class="span2">
				   			<input type="text" name="total_score" id="total_score" class="input-mini" disabled>/'.$total_points.'
				   		</div>
			   		</div>	
			   	</div>


		   		<div class="row-fluid visible-phone">
			   		<div class="span12">
		   				Total Score:
			   			<input type="text" name="total_score" id="total_score" class="input-mini" disabled>/'.$total_points.'				   		
			   		</div>	
			   	</div>

			   	<div class="row-fluid">
			   		<div class="span12">
			   		</div>
			   	</div>

			   	<div class="row-fluid">
			   		<a  class="btn wsu_btn pull-left" href="'.base_url()."scores/input/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/">Clear Scorecard</a>
			   		<a class="btn wsu_btn pull-right" href="'.base_url()."score_validation/".$project->project_id."/".$judge->id.'">Submit</a>
			   	</div>

			</form><!--close scores form-->';


		}
		?>

	</div><!--close hero-unit-->
</body>
</html>