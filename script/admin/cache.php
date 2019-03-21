<?php
include 'includes/header.php';
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_POST['update']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;

	$recentCacheEnable = ($_POST['recentCacheEnable'] == 'on') ? 1 : 0;
	
	$recentCacheExpireTime = mres($_POST['recentCacheExpireTime']);
	
	$categoryCacheEnable = ($_POST['categoryCacheEnable'] == 'on') ? 1 : 0;
	
	$categoryCacheExpireTime = mres($_POST['categoryCacheExpireTime']);
	
	$tagCacheEnable = ($_POST['tagCacheEnable'] == 'on') ? 1 : 0;
	
	$tagCacheExpireTime = mres($_POST['tagCacheExpireTime']);
	
	$topCacheEnable = ($_POST['topCacheEnable'] == 'on') ? 1 : 0;
	
	$topCacheExpireTime = mres($_POST['topCacheExpireTime']);
	
	$productCacheEnable = ($_POST['productCacheEnable'] == 'on') ? 1 : 0;
	
	$productCacheExpireTime = mres($_POST['productCacheExpireTime']);
	
	$sidebarCacheEnable = ($_POST['sidebarCacheEnable'] == 'on') ? 1 : 0;
	
	$sidebarCacheExpireTime = mres($_POST['sidebarCacheExpireTime']);
	
	$relatedCacheEnable = ($_POST['relatedCacheEnable'] == 'on') ? 1 : 0;
	
	$relatedCacheExpireTime = mres($_POST['relatedCacheExpireTime']);
	
	if(!is_numeric($recentCacheExpireTime) || !is_numeric($categoryCacheExpireTime) || !is_numeric($tagCacheExpireTime) || !is_numeric($topCacheExpireTime) || !is_numeric($productCacheExpireTime) || !is_numeric($sidebarCacheExpireTime) || !is_numeric($relatedCacheExpireTime))
	{	
		$error = true; 
		$errorMessage = "<div class='alert alert-danger'><li class='fa fa-warning'></li><b> Some Error Occurred Changes Can't be Updated .</b></div>";
	}
	
	if (!$error && !$csrfError)
	{  
		updateCacheSettings($recentCacheEnable, $recentCacheExpireTime, $categoryCacheEnable, $categoryCacheExpireTime, $tagCacheEnable, $tagCacheExpireTime, $topCacheEnable, $topCacheExpireTime, $productCacheEnable, $productCacheExpireTime, $sidebarCacheEnable, $sidebarCacheExpireTime, $relatedCacheEnable, $relatedCacheExpireTime);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Cache Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cache Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="cache.php"><i class=" fa fa-barcode"></i></a> Cache Settings</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="cache.php" method="post">
						<?php if($successMessage) echo $successMessage; ?> 
						<?php if($errorMessage) echo $errorMessage; ?> 
						<div class="form-group">
							<label class="col-lg-3 control-label">Recent Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (recentCacheEnable())
							{
								?>
								<input type="checkbox" name="recentCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="recentCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Recent Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="recentCacheExpireTime" value="<?php echo recentCacheExpireTime();?>">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label">Category Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (categoryCacheEnable())
							{
								?>
								<input type="checkbox" name="categoryCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="categoryCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Category Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="categoryCacheExpireTime" value="<?php echo categoryCacheExpireTime();?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Tags Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (tagCacheEnable())
							{
								?>
								<input type="checkbox" name="tagCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="tagCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Tags Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="tagCacheExpireTime" value="<?php echo tagCacheExpireTime();?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Top Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (topCacheEnable())
							{
								?>
								<input type="checkbox" name="topCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="topCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Top Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="topCacheExpireTime" value="<?php echo topCacheExpireTime();?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Product Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (productCacheEnable())
							{
								?>
								<input type="checkbox" name="productCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="productCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">product Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="productCacheExpireTime" value="<?php echo productCacheExpireTime();?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Sidebar Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (sidebarCacheEnable())
							{
								?>
								<input type="checkbox" name="sidebarCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="sidebarCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Sidebar Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="sidebarCacheExpireTime" value="<?php echo sidebarCacheExpireTime();?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Related Cache Enable</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (relatedCacheEnable())
							{
								?>
								<input type="checkbox" name="relatedCacheEnable" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="relatedCacheEnable" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Related Cache Expire Time</label>
							<div class="col-lg-4">
								<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="relatedCacheExpireTime" value="<?php echo relatedCacheExpireTime();?>">
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
					<button class="notify-without-image" style="display:none" id="Mediasetting"></button> 
				</div> <!-- Awidget -->
			</div> <!-- col-md-12 -->
		</div> <!-- row -->
	</div> <!-- mainy -->
	<div class="clearfix"></div>
</div> <!-- container -->
<?php include './includes/footer.php'; ?>