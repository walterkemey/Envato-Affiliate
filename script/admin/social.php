<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if(isset($_POST['submit']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	if(isset($_POST['facebook']) && $_POST["facebook"]!="")
	{
		if(validFacebookUrl($_POST["facebook"]) && $_POST["facebook"]!="")
			$facebook = $_POST["facebook"];
		else
			$error .="o. Invalid Facebook Page ID/Name";
	}
	if(isset($_POST['twitter']) && $_POST["twitter"]!="")
	{
		if(validTwitterUsername($_POST["twitter"]) && $_POST["twitter"]!="")
			$twitter = $_POST["twitter"];
		else
			$error .="o. Invalid Twitter Username";
	}
	if(isset($_POST['google']) && $_POST["google"]!="")
	{
		if(validGoogleUrl($_POST["google"]))
			$google = $_POST["google"];
		else
			$error .="o. Invalid Google+ Page ID";
	}
	if(isset($_POST['pinterest']) && $_POST["pinterest"]!="")
	{
		if(validPinterestPagename($_POST["pinterest"]) && $_POST["pinterest"]!="")
			$pinterest= $_POST["pinterest"];
		else
			$error .="o. Invalid Pinterest Page Name";
	}
	$socialStatus = $_POST["socialStatus"];
	if ($socialStatus == "on") 
		$socialStatus = 1;
	else 
		$socialStatus = 0;
	if($error=="" && !$csrfError)
	{
		updateSocial($facebook,$twitter,$google,$pinterest,$socialStatus);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Social Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Social Profiles : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">  
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><i class="fa fa-group color"></i> Social Profiles </h2> 
			<hr />
		</div>
		<!-- Page title -->
		<span class="toggle_msg label label-success" style="display:none;">
		<i class="fa fa-check"></i> Social Profiles Updated successfully</span>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="social.php" method="post">
						<?php if($successMessage) echo $successMessage; ?>
						<?php
						if(isset($_POST['facebook']))
						{
							?>
							<legend><h3>&nbsp;<i class="fa fa-facebook color"></i>&nbsp;&nbsp;Facebook Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.facebook.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="facebook" value="<?php echo($_POST["facebook"]) ?>" />
									<?php
									if(!validFacebookUrl($_POST["facebook"]))
									{
										$error="123";
										echo('<span class="label label-danger">Invalid Facebook Page ID/Name</span>');
									}
									?>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-twitter color"></i>&nbsp;&nbsp;Twitter Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.twitter.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="twitter" value="<?php echo($_POST["twitter"]) ?>"/>
									<?php
									if(!validTwitterUsername($_POST["twitter"]))
									{
										$error="123";
										echo('<span class="label label-danger">Invalid Twitter Username</span>');
									}
									?>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-google-plus color"></i>&nbsp;&nbsp;Google+ Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://plus.google.com/ </label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="google" value="<?php echo($_POST["google"]) ?>"/>
									<?php
									if(!validGoogleUrl($_POST["google"]))
									{
										$error="123";
										echo('<span class="label label-danger">Invalid Google+ Page ID</span>');
									}
									?>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-pinterest color"></i>&nbsp;&nbsp;Pinterest User Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">https://www.pinterest.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="pinterest" value="<?php echo($_POST["pinterest"]) ?>" />
									<?php
									if(!validPinterestPagename($_POST["pinterest"]))
									{
										$error="123";
										echo('<span class="label label-danger">Invalid Pinterest Page Name</span>');
									}
									?>
								</div>  
							</div>
							<legend><h3>&nbsp;<i class="fa fa-users color"></i>&nbsp;&nbsp;Social Profiles</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">Social profiles</label>
								<div class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if (socialStatus())
									{
										?>
										<input type="checkbox" name="socialStatus" checked>
										<?php
									}
									else
									{
										?>
										<input type="checkbox" name="socialStatus" >
										<?php
									}
									?>
								</div>
							</div>
							<hr />
							<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-6">
									<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i>Update</button>
								</div>
							</div>
							<?php 
						}
						else
						{
							$socialProfile = mysql_fetch_array(mysqlQuery("SELECT * FROM `socialProfiles`"));
							?>
							<legend><h3>&nbsp;<i class="fa fa-facebook color"></i>&nbsp;&nbsp;Facebook Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.facebook.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="facebook" value="<?php echo $socialProfile['facebook']?>" />
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-twitter color"></i>&nbsp;&nbsp;Twitter Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.twitter.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="twitter" value="<?php echo $socialProfile['twitter']?>"/>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-google-plus color"></i>&nbsp;&nbsp;Google+ Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://plus.google.com/ </label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="google" value="<?php echo $socialProfile['google'] ?>"/>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-pinterest color"></i>&nbsp;&nbsp;Pinterest Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">https://www.pinterest.com/</label>
								<div class="col-lg-6">
									<input type="text" class="form-control" name="pinterest" value="<?php echo $socialProfile['pinterest']?>"/>
								</div>
							</div>  
							<legend><h3>&nbsp;<i class="fa fa-users color"></i>&nbsp;&nbsp;Social Profiles</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">Social profiles</label>
								<div class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if (socialStatus())
									{
										?>
										<input type="checkbox" name="socialStatus" checked>
										<?php
									}
									else
									{
										?>
										<input type="checkbox" name="socialStatus" >
										<?php
									}
									?>
								</div>
							</div>
							<hr />
							<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
							<div class="form-group"> 
								<div class="col-lg-offset-3 col-lg-6">
									<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
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