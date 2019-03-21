<?php
error_reporting(0);
if(!isset($_SESSION)) session_start();

if(!isset($_SESSION['upgrade_step']))
$_SESSION['upgrade_step']=1;

include '../../config/config.php';
$row = mysql_fetch_array(mysqlQuery("SELECT `version` FROM `settings`"));
$version = $row['version'];
?>
<!DOCTYPE html> 
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Affiliate Portal 2.0 Upgrade Wizard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="../../static/images/favicon.png">
		<link href="../style/css/bootstrap.min.css" rel="stylesheet">
		<link href="../style/css/style.css" rel="stylesheet">
		<script src="../style/js/bootstrap.js"></script>
	</head>

	<body>
		<div class="hidden-xs">
			<div class="logo">
				<img src="../style/images/logo.png">
			</div>
			<div class="sub-logo">
				Affiliate Portal 2.0 Upgrade Wizard
			</div>
		</div>
		<div class="visible-xs logo-sm">
			<img src="../style/images/logo-sm.png">
		</div>
		  
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-default">
						  <div class="panel-heading">
							<strong>Thank you for Purchasing <a href="http://www.nexthon.com">Nexthon's</a> Product</strong>
							<div class="pull-right">
								<span class="badge badge-warning">Begin</span>
							</div>
						  </div>
						  <div class="panel-body">
							<h1>Affiliate Portal 2.0 Upgrade Wizard</h1>
							<h4>Upgrade Wizard</h4>
							<br />
							<p>Envato Affiliate Portal script is a script from which you can earn money very easily. The script has very friendly user interface and powerful admin panel to control the website.Products can be added using bulk add or direct from sources through Ajax. Products automatically updated daily.When user click on any product from your website it automatically add your referral user name and make it your affiliate link so you will get 30% on every users first deposit and also can earn money by ads.It is also social media ready so users can easily share products with other people. For any problem during installation <a href="http://nexthon.ticksy.com">Contact Our Support</a>.</p>
							<br>
							<?php
							if($version==2.3)
							{
								?>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<div class="alert alert-success">
										<i class="fa fa-check-square"></i> 
										You are Already using Latest Version !
									</div>
									<a href="../../" class="btn btn-success btn-lg" role="button">Go Back</a>
								</div>
								<?php
							}
							else if($version=="")
							{
								?>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
									<div class="alert alert-danger">
										<i class="fa fa-check-square"></i> 
											Affiliate Portal 2 Is Not Installed.
									</div>
									<a href="../" class="btn btn-success btn-lg" role="button">Install Now</a>
								</div>
								<?php
							}
							else
							{
								?>
								<p>
									<a href="requirements.php?<?php echo(time()); ?>" class="btn btn-info btn-lg" role="button">Upgrade</a>
								</p>
								<?php
							}
							?>
						  </div>
						  <div class="hidden-xs hidden-sm">
							  <center>All Rights Reserved <a href="http://www.nexthon.com">
								Nexthon.com</center>
							  </a>
						  </div>
					</div>
				</div>
			</div>
		</div>
	</body>

</html>