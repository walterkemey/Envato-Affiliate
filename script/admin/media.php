<?php
include 'includes/header.php';
$error="";
$error1="";
$error2="";
$error3="";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_POST['update']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$limitPosts = $_POST["limitPosts"];
	if(!is_numeric($limitPosts))
	{
		$error='<span class="label label-danger">Only Integers Allowed</span>';
	}
	
	$categoriesLimit = $_POST["categoriesLimit"];
	if(!is_numeric($categoriesLimit))
	{
		$error='<span class="label label-danger">Only Integers Allowed</span>';
	}
	
	
	$enablePossible = $_POST["enablePossible"];
	if ($enablePossible == "on")   
		$enablePossible = 1;
	else 
		$enablePossible = 0;  
		
	$relatedProLimit = $_POST["relatedProLimit"];
		
	$sidebarProLimit = $_POST["sidebarProLimit"];
		
	$enableSidebar = $_POST["enableSidebar"];
	if ($enableSidebar == "on") 
		$enableSidebar = 1;
	else 
		$enableSidebar = 0;
	
	$indexThumbnail = strtolower($_POST["indexThumbnail"]);
	if(!preg_match('/(?P<digit>\d+)x(?P<digit1>\d)/', $indexThumbnail))
	{
		$error1='<span class="label label-danger">Syntax Error</span>';
	}
	$sidebarThumbnail = $_POST["sidebarThumbnail"];
	if(!preg_match('/(?P<digit>\d+)x(?P<digit1>\d)/', $sidebarThumbnail))
	{
		$error2='<span class="label label-danger">Syntax Error</span>';
	}
	$possibleThumbnail = $_POST["possibleThumbnail"];
	if(!preg_match('/(?P<digit>\d+)x(?P<digit1>\d)/', $possibleThumbnail))
	{
		$error3='<span class="label label-danger">Syntax Error</span>';
	}
	
	if ($error == "" && $error1 == "" && $error2 == "" && $error3 == "" && !$csrfError)
	{
		updateMediaSettings($categoriesLimit, $limitPosts,$enablePossible,$enableSidebar,$indexThumbnail,$sidebarThumbnail,$sidebarProLimit,$possibleThumbnail,$relatedProLimit);	
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Media Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Media Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="./media.php"><i class=" fa fa-caret-square-o-right"></i></a> Media Settings</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="media.php" method="post">
						<?php if($successMessage) echo $successMessage; ?> 
						<div class="form-group">
							<label class="col-lg-3 control-label">Categories Limit</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="categoriesLimit" value="<?php echo categoriesLimit();?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Products Per Page</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="limitPosts" value="<?php echo limitPosts();?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Related Products Limit</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="relatedProLimit" value="<?php echo PossibleProLimit();?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Related Products</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (enablePossible())
							{
								?>
								<input type="checkbox" name="enablePossible" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="enablePossible" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Sidebar Products Limit</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="sidebarProLimit" value="<?php echo sidebarProLimit();?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Sidebar Products</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (enableSidebar())
							{
								?>
								<input type="checkbox" name="enableSidebar" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="enableSidebar" >
								<?php
							}
							?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label">Main Thumbnails Size</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="indexThumbnail" value="<?php echo indexThumbnail();?>" required>(e.g WidthxHeight)
								<?php
								if(isset($_POST['indexThumbnail']) && $error1!="")
								{
									echo $error1;
								}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label">Sidebar Thumbnails Size</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="sidebarThumbnail" value="<?php echo sidebarThumbnail();?>" required>(e.g WidthxHeight)
								<?php
								if(isset($_POST['sidebarThumbnail']) && $error2!="")
								{
									echo $error2;
								}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-3 control-label">Related Thumbnails Size</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="possibleThumbnail" value="<?php echo possibleThumbnail();?>" required>(e.g WidthxHeight)
								<?php
								if(isset($_POST['possibleThumbnail']) && $error3!="")
								{
									echo $error3;
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
					<button class="notify-without-image" style="display:none" id="Mediasetting"></button> 
				</div> <!-- Awidget -->
			</div> <!-- col-md-12 -->
		</div> <!-- row -->
	</div> <!-- mainy -->
	<div class="clearfix"></div>
</div> <!-- container -->
<?php include './includes/footer.php'; ?>