<?php
if (!isset($_SESSION))
session_start();
include '../config/config.php';
include '../includes/functions.php';
include 'captcha.php';
$error = false;
if(isset($_POST['username']) && isset($_POST['password'])) 
{
	$user = $_POST['username'];
	$pass = $_POST['password'];
	if(onOffAdminCaptcha()==1) 
	{ 
		if(isset($_POST["captcha_code"]) && trim($_POST["captcha_code"])!="") 
		{ 
			if (trim($_POST["captcha_code"])!=$_SESSION['captcha']['code']) 
			{
				$error = 'Invalid Captcha';
			}
			else
			{
				if (authenticate(trim($user) , trim($pass))) 
				{
					$_SESSION['admin_eap_secure'] = 1;
				}
				else
				{
					$error .= "Invalid username and password combination.";
				}
			}	
		}
		else
		{
			$error="Captcha field must not be empty.";
		}
	}	
	else 
	{
		if (authenticate(trim($user) , trim($pass))) 
		{  
			$_SESSION['admin_eap_secure'] = 1;
		}
		else
		{
			$error .= "Invalid username and password combination";
		}
	}
}
$_SESSION['captcha'] = simple_php_captcha();
if (isset($_SESSION['admin_eap_secure']) && !$error)
{
	header('Location: ./dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="style/css/bootstrap.min.css" rel="stylesheet">	
	<link href="style/css/font-awesome.min.css" rel="stylesheet">
	<link href="style/css/style.css" rel="stylesheet">
	<title>Login: <?php echo (getTitle()); ?></title>
</head>
<body>
<div class="page-content blocky">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-lg-offset-3 col-md-offset-3">
				<div class="awidget login-reg">
					<div class="awidget-body">
						<div class="page-title text-center">
							<img src="../style/images/n2.png" height="100">
							<hr />
						</div>
						<br />
						<?php
						if ($error) 
						{
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#">×</a><i class="icon-remove-sign"></i> <?php echo $error; ?>
							</div>
							<?php
						}
						?>
						<form class="form-horizontal" role="form" method="POST" action="index.php" accept-charset="UTF-8">
							<div class="input-group">
								<span class="input-group-addon" id="basic-addon1"><li class="fa fa-user"></li></span>
								<input type="text" id="username" class="form-control" name="username" placeholder="Username" value="<?php echo $user?>" required aria-describedby="basic-addon1">
							</div>
							<div class="input-group">
								  <span class="input-group-addon" id="basic-addon1"><li class="fa fa-lock"></li></span>
								  <input type="password" id="password" class="form-control" name="password" placeholder="Password" value="<?php echo $pass?>" required aria-describedby="basic-addon1">
							</div>
							<?php 
							if(onOffAdminCaptcha()==1) 
							{ 
								?>
								<div class="form-group">
									<div class="col-lg-5 col-xs-5 col-md-5 col-sm-5">
										<img style="width:150px" src="<?php echo($_SESSION['captcha']['image_src']) ?>" class="captchaImg" />
									</div>								
									<div class="col-lg-7 col-xs-7 col-md-7 col-sm-7" style="margin-top: 14px;">
										<input type="text" class="form-control"  name="captcha_code" placeholder="Enter Code" value="" required />
									</div>
								</div>
								<?php
							} 
							?>
							<hr>
							<div class="form-group">
								<div class="col-lg-offset-6 col-lg-6">
									<button type="submit" name="submit" class="btn btn-success"><li class="fa fa-sign-in"></li> Sign in</button>
									<a href="reset.php" class="btn btn-info"><li class="fa fa-edit"></li> Reset</a>
								</div>
							</div>
						</form>
					</div><!-- awidget-body -->
				</div><!-- awidget login-reg -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- container -->
</div>
	<script src="style/js/jquery.1.9.1.js"></script>
	<script src="style/js/bootstrap.min.js"></script>
</body>
</html>