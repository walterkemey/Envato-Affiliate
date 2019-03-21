<?php
error_reporting(0);
if(!isset($_SESSION)) session_start();

if(!isset($_SESSION['install_step']))
$_SESSION['install_step']=1;
?>
<!DOCTYPE html> 
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Affiliate Portal 2.0 Installation Wizard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="style/images/favicon.png">
		<link href="style/css/bootstrap.min.css" rel="stylesheet">
		<link href="style/css/style.css" rel="stylesheet">
		<script src="style/js/bootstrap.js"></script>
	</head>

	<body>
		<div class="hidden-xs">
			<div class="logo">
				<img src="style/images/logo.png">
			</div>
			<div class="sub-logo">
				Affiliate Portal 2.0
			</div>
		</div>
		<div class="visible-xs logo-sm">
			<img src="style/images/logo-sm.png">
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
							<h1>Affiliate Portal 2.0</h1>
							<h4>Installation Wizard</h4>
							<br />
							<p>Envato Affiliate Portal script is a script from which you can earn money very easily. The script has very friendly user interface and powerful admin panel to control the website.Products can be added using bulk add or direct from sources through Ajax. Products automatically updated daily.When user click on any product from your website it automatically add your referral user name and make it your affiliate link so you will get 30% on every users first deposit and also can earn money by ads.It is also social media ready so users can easily share products with other people. For any problem during installation <a href="http://nexthon.ticksy.com">Contact Our Support</a>.</p>
							<br>
							<p>
							<a href="requirements.php?<?php echo(time()); ?>" class="btn btn-success btn-lg" role="button">Install</a>
							OR
							<a href="upgrade/index.php?<?php echo(time()); ?>" class="btn btn-info btn-lg" role="button">Upgrade</a>
							</p>
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