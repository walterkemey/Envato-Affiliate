<?php
include '../includes/functions.php';
include '../config/config.php';
if((isset($_POST['email']) && $_POST['email']!="") || (isset($_POST['username']) && $_POST['username']!=""))
{
	$email = "";
	$username="";
	if($_POST["email"]!="")
	{
		$email = $_POST["email"];
		$username=$_POST["username"]; 
		$qry = mysqlQuery("SELECT `username` FROM `settings`");
		$numRows = mysql_num_rows($qry); 
		if ($numRows > 0)
		{
			while($row = mysql_fetch_array($qry)) 
			{
				$username = $row['username'];
			}
		}
		if(!checkEmail($email))
		{
			$error .="Invalid Email Address<br />";
		}
		else if(!emailExists($email))
		{
			$error .="Email Doesn't Exists<br />";
		}
	}
	else if($_POST["username"]!="")
	{
		$username=$_POST["username"];
		$match = "SELECT `email` FROM `settings`"; 
		$qry = mysqlQuery($match);
		$numRows = mysql_num_rows($qry); 
		if ($numRows > 0)
		{
			while($row = mysql_fetch_array($qry)) 
			{
				$email = $row['email'];
			}
		}
		else
		{
			$error .="Username Doesn't Exists<br/>";
		}
	}
	if(emailExists($email,0) && $error=="")
	{
		sendEmail(getAdminEmail(),$email,"Password reset request",$username . " Please click on the link below to reset your password<br/>" . rootpath() . "/admin/reset.php?rid=" . sencrypt($email));
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style/css/bootstrap.min.css" rel="stylesheet">	
	<link href="style/css/font-awesome.min.css" rel="stylesheet">
	<link href="style/css/style.css" rel="stylesheet">
	<title>Reset Password: <?php echo(getTitle()) ?></title>
</head>
<body> 
	<!-- Logo & Navigation starts -->
	<div class="header">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<!-- Logo -->
					<div class="logo text-center">
						<h1>
							<a href="index.php">New Affiliate Portal</a>
						</h1>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Logo & Navigation ends -->
	<!-- Page content -->
	<div class="page-content blocky">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="awidget login-reg">
						<div class="awidget-head"></div>
						<div class="awidget-body">
							<!-- Page title -->
							<div class="page-title text-center">
								<h2>Reset Password</h2>
								<hr />
							</div>
							<!-- Page title -->
							<form class="form-horizontal" role="form" method="POST" action="reset.php" accept-charset="UTF-8">
								<?php
								if($error!="") 
								{ 
									$error = "<b>Error:" . $error; ?>
									<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php echo($error); ?></div>
									<?php 
								} 
								else 
								{			
									if(isset($_GET['rid']) && $_GET['rid']!="")
									{
										$dec_email = sdecrypt($_GET['rid']);
										if(emailExists($dec_email,0))
										{
											resetPass($dec_email);
											?>
											<div class="alert alert-success alert-dismissable">
												<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check"></i> New Password Generated And Emailed To You!
											</div>
											<?php 
										} 
										else 
										{ 
											?>
											<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Request Session Timed Out Or Invalid Request</div>
											<?php 
										} 
									} 
									else if($_POST['email'] || $_POST['username'])   
									{ 
										?>
										<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><i class="fa fa-check"></i> Password Reset Link Sent To Your Email!</div>
										<?php 
									} 
								} 
								?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Email</label>
									<div class="col-lg-8">
										<input type="text" id="username" class="form-control" name="email" placeholder="Email" required >
									</div>
								</div>
								<div align="center"> 
									<legend>OR</legend>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Username</label>
									<div class="col-lg-8">
										<input type="text" id="username" class="form-control" name="username" placeholder="Username" >
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-info">Reset</button>
										<a href="index.php" class="btn btn-success">Login</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div> 
	</div>
	<script src="style/js/jquery.1.9.1.js"></script>
	<script src="style/js/bootstrap.min.js"></script>
</body>
</html>
