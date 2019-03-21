<?php 
include 'includes/header.php';
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_POST['update']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$adminForm = $_POST["adminForm"];
	if ($adminForm == "on") 
		$adminForm = 1;
	else 
		$adminForm = 0;
	
	$contactForm = $_POST["contactForm"];
	if ($contactForm == "on") 
		$contactForm = 1;
	else 
		$contactForm = 0;
	
	if(!$csrfError)
	{
		updateCaptchaSettings($adminForm,$contactForm);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Captcha Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Captcha Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="./manageCaptcha.php"><i class=" fa fa-eye-slash"></i></a> Capcha Settings</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="manageCaptcha.php" method="post" enctype="multipart/form-data">
						<?php if($successMessage) echo $successMessage; ?> 
						<div class="form-group">
							<label class="col-lg-3 control-label">Admin Login Form</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (onOffAdminCaptcha())
								{
									?>
									<input type="checkbox" name="adminForm" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="adminForm" >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Contact Form</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (onoffContactCaptcha())
							{
								?>
								<input type="checkbox" name="contactForm" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="contactForm" >
								<?php
							}
							?>
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-3 col-lg-9">
								<button type="submit" name="update" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
							</div>
						</div>
					</form>
					<button class="notify-without-image" style="display:none"></button> 
				</div> <!-- Awidget -->
			</div> <!-- col-md-12 -->
		</div> <!-- row -->
	</div> <!-- mainy -->
	<div class="clearfix"></div>
</div> <!-- container -->
<?php include './includes/footer.php'; ?>