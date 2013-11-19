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

		//Each category has 1 subcategory by default
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
			'<div class="row-fluid border">'+
				'<div class="span3">'+
					'<div name="subcategory'+$subcat_id+'">'+
						subcat_name +
					'</div>'+
				'</div>'+
				'<div class="span6" id="dynamic_subcat'+$subcat_id+'_criteria">'+

				'</div>'+
				'<div class="span6" id="dynamic_subcat'+$subcat_id+'_criteria">'+
					'<input type="text" '
				'</div>'
			'</div>';

			//append fields to the div with id dynamic_fields
			$('#dynamic_fields').append($input);
		};

		//used for populating criteria fields with what's already in the database when page loads
		function populate_subcat_criteria(subcat_id, desc, points, db_criteria_id){
			subcategory_criteria_count[subcat_id]+=1;

			var $input = 
			'<div class="control-group" id="control-group_'+subcat_id+'_'+subcategory_criteria_count[subcat_id]+'">'+
				'<div class="controls inline" name="subcategory['+subcat_id+'][criteria][]">'+
					'<input type="hidden" name="subcategory['+subcat_id+'][criteria]['+subcategory_criteria_count[subcat_id]+'][id]" value="'+db_criteria_id+'">'+
					desc + ' ' +
					'<input type="text" class="input-mini">'+
					'/'+ points +
				'</div>'+
			'</div>';

			//append fields to the div with id dynamic_subcat[#]_criteria
			$('#dynamic_subcat'+subcat_id+'_criteria').append($input);
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
			<a href="<?php echo base_url()."scores/input"?>" class="btn wsu_btn pull-right">Reset</a>
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

			<form class="form-horizontal" name="add_category" id="add_category" method="post" accept-charset="utf-8" action='.base_url()."scores/score_validation".'>

				<div class="control-group  border">
					<label class="control-label" for="category">Category:</label>
					<div class="controls inline" name="category">
						'.$category->category.'
					</div>
				</div>

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
									echo '<script type="text/javascript">
									populate_subcat_criteria('.$subcat_count.',"'.$criteria->criteria_description.'",'.$criteria->criteria_points.','.$criteria->criteria_id.');
							  		</script>';
									$subcat_points += $criteria->criteria_points;
									$total_points +=$criteria->criteria_points;
								}
							}
							$subcat_count += 1;
						}
					}		
				echo '</div>

				

								
			</form><!--close scores form-->';
		}
		?>

	</div><!--close hero-unit-->
</body>
</html>