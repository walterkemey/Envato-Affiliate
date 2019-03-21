<?php 
include 'includes/header.php';
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_POST['update']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$disqusStatus = $_POST["disqusStatus"];
	if ($disqusStatus == "on") 
		$disqusStatus = 1;
	else 
		$disqusStatus = 0;
	
	$disqusName = $_POST["disqusName"];
	if(!$csrfError)
	{
		updateCommentSettings($disqusStatus,$disqusName);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Comment's Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Comments Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="./manageComments.php"><i class=" fa fa-comments"></i></a> Comments Settings</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="manageComments.php" method="post">
						<?php if($successMessage) echo $successMessage; ?> 
						<div class="form-group">
							<label class="col-lg-3 control-label">Comments Status</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (onOffDisqus())
								{
									?>
									<input type="checkbox" name="disqusStatus" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="disqusStatus" >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Disqus Username</label>
							<div class="col-lg-6">
							<input type="text" name="disqusName" class="form-control" value="<?php echo disqusName()?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label"></label>
							<div class="col-lg-6">
								Get Your Disqus Username From Here <a href="https://disqus.com/admin/signup/?utm_source=New-Site">Add Disqus to your site</a> 
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