<!DOCTYPE html>
<html>
<head lang="en">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'bootstrap-responsive.css');?>">
	<link rel="stylesheet" type="text/css" href="<?php echo (CSS.'scorecard_print.css');?>">
	<link rel="icon" type="image/ico" href="http://wayne.edu/global/favicon.ico"/>
	<script type="text/javascript" src="<?php echo (JS.'jquery-1.9.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'bootstrap.js');?>"></script>
	<script type="text/javascript" src="<?php echo (JS.'spin.min.js');?>"></script>
	<script type="text/javascript">
		function printScorecard(){
			//var printButton = document.getElementsByTagName("button");
			$('.printButton').hide();
			window.print();
			$('.printButton').show();
		}

	</script>
</head>

<body>