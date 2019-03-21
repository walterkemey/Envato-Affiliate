<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600italic,600' rel='stylesheet' type='text/css'>
  
	<?php
		if (!isset($_SESSION)) session_start();
		if (!isset($_SESSION['admin_eap_secure'])) 
		{
			header('Location: ./index.php');
		}
		include "../config/config.php";
		include "../includes/functions.php";
	?>
  
    <!-- CSS Files -->
	<link href="style/css/bootstrap.min.css" rel="stylesheet">
	<link href="style/css/animate.min.css" rel="stylesheet">
	<link href="style/css/jquery.tagsinput.css" rel="stylesheet">
	<link href="style/css/jquery.gritter.css" rel="stylesheet">
	<link href="style/css/jquery-ui.css" rel="stylesheet">  
	<link href="style/css/bootstrap-switch.css" rel="stylesheet">
	<link href="style/ui/jquery.ui.all.css" rel="stylesheet">	
	<link href="style/css/font-awesome.min.css" rel="stylesheet">
	<link href="style/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="style/css/upload.css" rel="stylesheet">
	<link href="style/css/style.css" rel="stylesheet">
		
    <!-- Javascript files -->
	<script src="style/js/jquery.1.9.1.js"></script>
	<script src="style/js/bootstrap.min.js"></script>
	<script src="style/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="style/js/jquery.bootstrap-growl.min.js"></script>
	<script src="style/js/bootstrap-switch.min.js"></script>
	<script src="style/js/respond.min.js"></script>
	<script src="style/js/html5shiv.js"></script>
	<script src="style/js/jquery.tagsinput.js"></script>
	<script src="style/js/bootstrap-select.min.js"></script>
	<script src="style/js/ajaxq.js"></script>
	<script src="style/js/upload.js"></script>
	<script src="style/js/custom.js"></script>
	  