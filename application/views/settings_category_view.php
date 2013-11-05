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
			bootbox.confirm("Are you sure you delete this?",function(result){
				if(result){
					window.location.href='<?php echo base_url()."settings/categories/delete/";?>'+id;
				}
			});
		}
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
						<a id="nav_systemsettings" href="<?php echo base_url()."settings/general"?>">System Settings</a>
					</li>
				</ul>
			</div>
		</div>
	</div><!--close nav-->

	<div class="hero-unit wsu_hero_unit">
		<h2 class="wsu_h2 text-center hidden-phone hidden-tablet">Graduate Exhibition Registration &amp; Scoring System</h2>

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
										$subcats[]= array();
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
							<a class="btn wsu_btn" href="#"><i class="icon-pencil"></i></a>
							<!--<a class="btn wsu_btn" href="';echo base_url()."settings/categories/edit/".$row->category;echo'"><i class="icon-pencil"></i></a>-->
							<button class="btn wsu_btn" onClick="confirmModal('; echo "'".$row->cat_id ."'";echo')"><i class="icon-trash"></i></button>							</td>';

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