<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if(isset($_POST['username']) && isset($_POST['oldpassword']) && isset($_POST['email']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$username = $_POST["username"];
	$oldpassword = md5($_POST["oldpassword"]);
	$match = "SELECT `password` FROM `settings`"; 
	$qry = mysqlQuery($match);
	$numRows = mysql_numRows($qry); 
	$row = mysql_fetch_array($qry);
	if($row['password']!=$oldpassword || $_POST['oldpassword']=="")
		$error .="o. Invalid Password User Details Can't Be Changed";
	if(isset($_POST['password']) && $_POST['password']!="")
		$password = md5($_POST["password"]);
	$email = $_POST["email"];
	if(!isAlpha($username)) {
		$error .="o. Username Can Only Contain Letters a-Z and Numbers 0-9";
	}
	if(strlen($username)<5 || strlen($username)>15) {
		$error .="o. Username Length Must Be Between 5 to 15 Characters";
	}
	if(strlen($_POST["password"])<6 || strlen($_POST["password"])>18) {
		if($_POST["password"]!="")
			$error .="o. Password Length Must Be Between 6 to 18 Characters";
	}
	if(!checkEmail($email)) {
		$error .="o. Invalid Email Address";
	}
	if($error=="" && !$csrfError) {
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> User Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Login Details : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-user color"></i> Login Details </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="user.php" method="post">
					<?php
					if(isset($_POST['username']))
					{
						$username = $_POST["username"];
						$oldpassword = md5($_POST["oldpassword"]);
						$email = $_POST["email"];
						$qry = mysqlQuery("SELECT `password` FROM settings");
						$numRows = mysql_num_rows($qry); 
						$row = mysql_fetch_array($qry);
						if($successMessage) echo $successMessage;
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="username" value="<?php echo($_POST["username"]) ?>" />
								<?php
								if(!isAlpha($username))
								{
									echo('<span class="label label-danger">Username Can Only Contain Letters a-Z</span>');
								}
								if(strlen($username)<5 || strlen($username)>15)
								{
									echo('<span class="label label-danger">Username Length Must Be Between 5 to 15 Characters</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="email" value="<?php echo($_POST["email"]) ?>" />
								<?php
								if(!checkEmail($email))
								{
									echo('<span class="label label-danger">Invalid Email Address</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">New Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" name="password" placeholder="Optional" value="<?php echo($_POST["password"]) ?>"/>
								<?php
								if(strlen($_POST["password"])<6 || strlen($_POST["password"])>18)
								{
									if($_POST["password"]!="")
										echo('<span class="label label-danger">Password Length Must Be Between 6 to 18 Characters</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"> Old Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" name="oldpassword" placeholder="Required" value="<?php echo($_POST["oldpassword"]) ?>" />
								<?php
								if(($row['password']!=$oldpassword) || trim($_POST['oldpassword']==""))
								{
									echo('<span class="label label-danger">Invalid Password User Details Cant Be Changed</span>');
								}
								?>
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" value="Add"><i class="fa fa-pencil-square-o"></i> Update</button>
							</div>
						</div>
						<?php 
						if($error=="" && !$csrfError)  
						{
							updateUser($username,$password,$email);  
						}
					}
					else
					{
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">User Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="username" value="<?php echo(getAdminUsername()) ?>" />
							</div>
						</div>  
						<div class="form-group">
							<label class="col-lg-2 control-label">Email</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="email" value="<?php echo(getAdminEmail()) ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">New Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" placeholder="Optional" name="password" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Old Password</label>
							<div class="col-lg-10">
								<input type="password" class="form-control" placeholder="Required" name="oldpassword" />
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" value="Add"><i class="fa fa-pencil-square-o"></i> Update</button>
							</div>
						</div>
						<?php 
					} 
					?>
					</form>
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div> 
</div><!-- container -->
<?php include 'includes/footer.php'; ?>