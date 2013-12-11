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
			'<div class="control-group" id="control-group_'+$subcat_id+'">'+
				'<label class="control-label" for="subcategory'+$subcat_id+'">'+'Subcategory Name:</label>'+
				'<div class="controls inline" name="subcategory'+$subcat_id+'">'+
					'<input type="text" placeholder="Content, Display, Oral, etc." name="subcategory['+$subcat_id+'][name]" class="input-large"/>'+
					'<i class="icon-remove" onclick="delete_subcat('+$subcat_id+')"></i>'+
				'</div>'+
				'<div id="dynamic_subcat'+$subcat_id+'_criteria">'+
					'<button class="btn btn-medium wsu_btn" id="add_subcat'+$subcat_id+'_criteria" onclick="add_subcat_criteria('+$subcat_id+');return false;"><i class="icon-plus"></i> Add Criterion</button>'+
					'<div class="control-group">'+
						'<label class="control-label" for="subcategory'+$subcat_id+'_criteria">Criterion:</label>'+
						'<div class="controls inline" name="subcategory['+$subcat_id+'][criteria][]">'+
							'<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcategory['+$subcat_id+'][criteria]['+$subcat_id+'][desc]" class="input-large" rows="3"></textarea>'+
							'<input type="number" placeholder="Points Possible" name="subcategory['+$subcat_id+'][criteria]['+$subcat_id+'][points]" class="input-large"/>'+
						'</div>'+
					'</div>'+
				'</div>'+
				'<hr>'+
			'</div>';

			//append fields to the div with id dynamic_fields
			$('#dynamic_fields').append($input);
		};

		function delete_subcat(div_id){
			$('#control-group_'+div_id).remove();
		};

		//add_subcat_criteria function happens onclick of button with id add_subcat[#]_criteria
		//this will dynamically add three input fields for subcategory criteria: name, description, points possible to the div of subcat_id that is passed here
		function add_subcat_criteria($subcat_id){

			subcategory_criteria_count[$subcat_id]+=1;

			var $input = 
			'<div class="control-group" id="control-group_'+$subcat_id+'_'+subcategory_criteria_count[$subcat_id]+'">'+
				'<label class="control-label" for="subcategory'+$subcat_id+'_criteria">Criterion:</label>'+
				'<div class="controls inline" name="subcategory['+$subcat_id+'][criteria][]">'+
					'<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcategory['+$subcat_id+'][criteria]['+subcategory_criteria_count[$subcat_id]+'][desc]" class="input-large" rows="3"></textarea>'+
					'<i class="icon-remove" onclick="delete_criterion('+$subcat_id+","+subcategory_criteria_count[$subcat_id]+')"></i>'+
					'<input type="number" placeholder="Points Possible" name="subcategory['+$subcat_id+'][criteria]['+subcategory_criteria_count[$subcat_id]+'][points]" class="input-large"/>'+
				'</div>'+
			'</div>';

			//append fields to the div with id dynamic_subcat[#]_criteria
			$('#dynamic_subcat'+$subcat_id+'_criteria').append($input);
		};

		function delete_criterion(subcat,criteria){
			$('#control-group_'+subcat+'_'+criteria).remove();
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
				<li class="span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="span3 active "><a id="nav_systemsettings" href="<?php echo base_url()."settings/categories"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">
	
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

		<form class="form-horizontal" name="add_category" id="add_category" method="post" accept-charset="utf-8" action='<?php echo base_url()."settings/add_category_validation/";?>'>
			<div class="row-fluid">
				<div class="span12">

					<div class="row-fluid">

						<div class="span8 offset2">
							<div class="span6">							
								<button class="btn btn-medium wsu_btn span12" id="add_subcat" onclick="add_subcategory(); return false;"><i class="icon-plus"></i> Add Subcategory</button>
							</div>
							<div class="span6">
								<input type="submit" name="category_submit" class="btn span12 wsu_btn" id="submit_category_btn" value="Submit"/>
							</div>
						</div>
					</div>

					<div class="row-fluid">
						<div class="span8 offset2">

							<hr>
							
							<div class="control-group">
			    				<label class="control-label" for="category">Category Name:</label>
			    				<div class="controls inline" name="category">
			    					<input type="text" name="category_name" class="input-large" placeholder="Poster, Oral Presentation, etc."/>
			    				</div>
			    			</div>

			    			<hr>

			    			<div id="dynamic_fields">
			    				<div class="control-group">
			    					<label class="control-label" for="subcategory0_name">Subcategory Name:</label>
									<div class="controls inline" name="subcategory0_name">
										<input type="text" placeholder="Content, Display, Oral, etc." name="subcategory[0][name]" class="input-large"/>
									</div>
								</div>
								<div id="dynamic_subcat0_criteria">
									<button class="btn btn-medium wsu_btn" id="add_subcat0_criteria" onclick="add_subcat_criteria(0);return false;"><i class="icon-plus"></i> Add Criterion</button>

									<div class="control-group">
										<label class="control-label" for="subcategory0_criteria">Criterion:</label>
										<div class="controls inline" name="subcategory[0][criteria][]">
											<textarea type="text" placeholder="Ability to answer questions, Significance/relevance stated, etc." name="subcategory[0][criteria][0][desc]" class="input-large" rows="3"></textarea>
											<input type="number" placeholder="Points Possible" name="subcategory[0][criteria][0][points]" class="input-large"/>
										</div>
									</div>
								</div>
							<hr>
							</div>
			    		</div>

			    	<br/><br/>
			    	</div>

					

					<div class="row-fluid">

						<div class="span8 offset2">
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