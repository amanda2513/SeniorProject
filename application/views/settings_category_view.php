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
			$("#category_settings_table").tablesorter( {sortList: [[0,0]]});
		});	
	</script>
	<script type="text/javascript">
		function confirmModal(id){
			bootbox.dialog("Are you sure you want to delete this?",[{
				"label": "Delete",
				"class":"btn wsu_btn pull-left",
				"callback": function(){
					window.location.href='<?php echo base_url()."settings/categories/delete/";?>'+id;
				}
			}, {
				"label": "Cancel",
				"class":"btn wsu_btn pull-right"
			}]);
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
					<div class="span6" id="wsu_login_message">
						Welcome, <?php echo $this->session->userdata('fn')?> <?php echo $this->session->userdata('ln')?>
					</div>
					<div class="span6">
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

	<div class="navbar wsu_navbar row-fluid">
		<div class="navbar-inner span12">
			<ul class="nav text-center">
				<li class="span1"><a id="nav_home" href="<?php echo base_url()."gerss/home"?>"><img alt="home" src="<?php echo (IMG.'home.png');?>"></img></a></li>
				<li class="span2"><a id="nav_projects" href="<?php echo base_url()."gerss/projects_participants"?>">Projects</a></li>
				<li class="span2"><a id="nav_scores" href="<?php echo base_url()."scores/input"?>">Scores</a></li>
				<li class="span3"><a id="nav_manageusers" href="<?php echo base_url()."manage_users/participant"?>">Manage Users</a></li>
				<li class="active span3"><a id="nav_systemsettings" href="<?php echo base_url()."settings/categories"?>">System Settings</a></li>
			</ul>
		</div>
	</div>

	<div class="hero-unit wsu_hero_unit">

		<ul class="nav nav-tabs">
			<li><a href="<?php echo base_url()."settings/general"?>">General</a></li>
			<li class="active"><a href="<?php echo base_url()."settings/categories"?>">Project Categories</a></li>
			<a class="btn wsu_btn pull-right" href="<?php echo base_url()."settings/categories/add";?>" id="btn_add_category"><i class="icon-plus"></i> Add Category</a>
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
  		?>

		<table id="category_settings_table" class="table wsu_table table-bordered table-striped tablesorter">
			<thead>
				<tr>
					<th>
						Category
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Subcategories
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>
						Total Points Possible
						<i class="pull-right icon-resize-vertical"></i>
					</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(!empty($category)&&!empty($subcategory)&&!empty($subcat_criteria)){

						foreach($category as $index=>$row)
						{
							echo 
								'<tr>
									<td>' . 
										$row->category  . '
									</td>

									<td>';

										$current_cat_id = $row->cat_id;
										$total_points = 0;

										foreach($subcategory as $subcat)
										{
											$subcat_points = 0;

											$current_subcat_id = $subcat->subcat_id;

											if($subcat->cat_id == $current_cat_id)
											{
												echo $subcat->subcat_name;
											

												foreach($subcat_criteria as $criteria)
												{
													if($criteria->subcat_id == $current_subcat_id)
													{
														$subcat_points += $criteria->criteria_points;
														$total_points +=$criteria->criteria_points;
													}
												}
												echo  ' (' . $subcat_points . ' pts) </br>';
											}
										}
								
							echo '</td>';

							echo '<td>' . $total_points . '</td>';
							echo '<td>
							<a class="btn wsu_btn" href="'.base_url()."settings/categories/edit/".$row->category.'"><i class="icon-pencil"></i></a>
							<button class="btn wsu_btn" onClick="confirmModal('."'".$row->cat_id ."'".')"><i class="icon-trash"></i></button>							</td>';

							echo '</tr>';

						}
					}
					else
					{
						echo "<td>No results found.</td>";
					}
				?>
			</tbody>
		</table>
	</div>
	</div><!--close hero-unit-->

</body>
</html>