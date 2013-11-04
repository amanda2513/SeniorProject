<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'grad-project.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script>
		//Each category has 1 subcategory by default
		var $subcategory_count=1;
		//Each subcategory has 1 set of criteria by default, this is incremented in add_subcat_criteria method
		var subcategory_criteria_count = new Array();
		subcategory_criteria_count[0]=1; 
	</script>

	<script type="text/javascript">
		//add_subcategory function happens onclick of button with id add_subcat
		//this will dynamically add an input field for a new subcategory
		//the first criteria group shows automatically. the button is to add more
		function add_subcategory(){
			//count is used to assign unique names to each element and is assigned to subcat_id for readability below. subtracting 1 because array is zerobased and i want id = array[]
			$subcategory_count += 1;
			var $subcat_id=$subcategory_count-1;
			//add another item to subcategories array
			subcategory_criteria_count[$subcat_id]=1;

			var $input = 
			'<div class="control-group"><label class="control-label" for="subcategory'+$subcat_id+'">'+'Subcategory Name:</label>'+
				'<div class="controls inline" name="subcategory'+$subcat_id+'">'+
					'<input type="text" placeholder="Content, Display, Oral, etc." name="subcategory'+$subcat_id+'_name" class="input-large"/>'+
				'</div>'+
				'<div id="dynamic_subcat'+$subcat_id+'_criteria">'+
					'<button class="btn btn-medium wsu_btn" id="add_subcat'+$subcat_id+'_criteria" onclick="add_subcat_criteria('+$subcat_id+');return false;"><i class="icon-plus"></i> Add Criteria</button>'+
					'<div class="control-group">'+
						'<label class="control-label" for="subcategory'+$subcat_id+'_criteria">Criteria:</label>'+
						'<div class="controls inline" name="subcategory'+$subcat_id+'">'+
							'<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcategory1_desc" class="input-large" rows="3"></textarea>'+
							'<input type="number" placeholder="Points Possible" name="subcategory'+$subcat_id+'_points" class="input-large"/>'+
						'</div>'+
					'</div>'
				'</div>'+
				'<hr/>'+
			'</div>';

			$('#dynamic_fields').append($input);
		};

		//add_subcat_criteria function happens onclick of button with id add_subcat[#]_criteria
		//this will dynamically add three input fields for subcategory criteria: name, description, points possible to the div of subcat_id that is passed here
		function add_subcat_criteria($subcat_id){

			subcategory_criteria_count[$subcat_id-1]+=1;

			var $input = 
			'<div class="control-group">'+
				'<label class="control-label" for="subcategory'+$subcat_id+'_criteria">Criteria:</label>'+
				'<div class="controls inline" name="subcategory'+$subcat_id+'">'+
					'<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcategory'+$subcat_id+'_desc" class="input-large" rows="3"></textarea>'+
					'<input type="number" placeholder="Points Possible" name="subcategory'+$subcat_id+'_points" class="input-large"/>'+
				'</div>'+
			'</div>';

			$('#dynamic_subcat'+$subcat_id+'_criteria').append($input);
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
					<li>
						<a id="nav_scores" href="#">Scores</a>
					</li>
					<li>
						<a id="nav_manageusers" href="<?php echo base_url()."manage_users/participants"?>">Manage Users</a>
					</li>
					<li class="active">
						<a id="nav_systemsettings" href="<?php echo base_url()."settings/categories"?>">System Settings</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>
		
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
  				echo '<p class="span10 offset2">Fill out the form below to add a project category:</p>';
  			}
  		?>

		<form class="form-horizontal" name="add_category" id="add_category" method="post" accept-charset="utf-8" action='<?php echo base_url()."settings/add_category_validation";?>'>
			<div class="row-fluid">
				<div class="span12">

					<div class="row-fluid">

						<div class="span6 offset3">
						<div class="span6">							
							<button class="btn btn-medium wsu_btn span12" id="add_subcat" onclick="add_subcategory(); return false;"><i class="icon-plus"></i> Add Subcategory</button>
						</div>
						<div class="span6">
							<input type="submit" name="category_submit" class="btn span12 wsu_btn" id="submit_category_btn" value="Submit"/>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span6 offset3">

							<hr/><hr/>
							
							<div class="control-group">
			    				<label class="control-label" for="category">Category Name:</label>
			    				<div class="controls inline" name="category">
			    					<input type="text" name="category_name" class="input-large" placeholder="Poster, Oral Presentation, etc."/>
			    				</div>
			    			</div>

			    			<hr/><hr/>

			    			<div id="dynamic_fields">
			    				<div class="control-group">
			    					<label class="control-label" for="subcategory0_name">Subcategory Name:</label>
									<div class="controls inline" name="subcategory0_name">
									<input type="text" placeholder="Content, Display, Oral, etc." name="subcategory[0][name]" class="input-large"/>
								</div>
								<div id="dynamic_subcat0_criteria">
									<button class="btn btn-medium wsu_btn" id="add_subcat0_criteria" onclick="add_subcat_criteria('0');return false;"><i class="icon-plus"></i> Add Criteria</button>

									<div class="control-group">
										<label class="control-label" for="subcategory0_criteria">Criteria:</label>
										<div class="controls inline" name="subcategory0_criteria">
											<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcat_criteria[0][desc]" class="input-large" rows="3"></textarea>
											<input type="number" placeholder="Points Possible" name="subcat_criteria[0][points]" class="input-large"/>
										</div>
									</div>
								</div>
								<hr/>
							</div>
			    			</div>

			    			<br/><br/>
			    		</div>
					</div><!--close form column row-fluid-->

					

					<div class="row-fluid">

						<div class="span6 offset3">
						<div class="span6">							
							<button class="btn btn-medium wsu_btn span12" id="add_subcat" onclick="add_subcategory(); return false;"><i class="icon-plus"></i> Add Subcategory</button>
						</div>
						<div class="span6">
							<input type="submit" name="category_submit" class="btn span12 wsu_btn" id="submit_category_btn" value="Submit"/>
						</div>
					</div>
					</div><!--close submit button row-fluid-->
				</div><!--close span12-->
			</div><!--close form's row-fluid-->
		</form><!--close registration form-->
	</div><!--close hero-unit-->
</body>
</html>