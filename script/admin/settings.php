<?php
include "includes/header.php";
$error = false;
$LogoError = "";
$FaviconError = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if(isset($_POST['title']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	
	$title = $_POST["title"];
	$websiteName = $_POST["websiteName"];
	$description = $_POST["description"];
	$keywords = $_POST["keywords"];  
	$envato = $_POST["envato"];
	$rootpath = $_POST["rootpath"];
	if (trim($_FILES["logo"]["name"]) != "")
	{
		$base = explode(".", strtolower(basename($_FILES["logo"]["name"])));
		$ext = end($base);
		if (validFileExtension($ext))
		{
			$logo = "logo.".$ext;
			unlink("../style/images/".getLogo());
			move_uploaded_file($_FILES["logo"]["tmp_name"], "../style/images/".$logo);
		}
		else
		{
			$logo = getLogo();
			$logoError='<span class="label label-danger">Invalid Logo Extension</span>';
			$error=true;
		}
	}
	else
	{
		$logo = getLogo();
	}
	if (trim($_FILES["favicon"]["name"]) != "")
	{
		$base = explode(".", strtolower(basename($_FILES["favicon"]["name"])));
		$ext = end($base);
		if (validFaviconExtension($ext))
		{
			$favicon = "favicon." . $ext;
			unlink("../style/images/" . getFavicon());
			move_uploaded_file($_FILES["favicon"]["tmp_name"], "../style/images/".$favicon);
		}
		else
		{
			$favicon = getFavicon();
			$FaviconError='<span class="label label-danger">Invalid Favicon Extension</span>';
			$error=true;
		}
	}
	else
	{
		$favicon = getFavicon();
	}
	if(!$error && !$csrfError)
	{
		updateSettings($title,$websiteName,$description,$keywords,$envato,$rootpath,$logo,$favicon);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Edit Settings: <?php echo(getTitle()) ?></title>
<script src="style/js/ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><i class="fa fa-cog color"></i> General Settings </h2> 
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="settings.php" method="post" enctype="multipart/form-data">
						<?php 
						if($successMessage) echo $successMessage; 
						else if($error)
						{
							?>
							<div class='alert alert-danger'><li class='fa fa-warning'></li> <b>Some Error occurred can't update Settings.</b></div>
							<?php						
						}
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Title</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="title" value="<?php echo(getTitle()); ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Website Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="websiteName" value="<?php echo(getWebsiteName()); ?>" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-lg-10">
								<textarea id="des" class="form-control" rows="5" name="description" required ><?php echo(getDescription()); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Keywords</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" name="keywords" id="keyword" required ><?php echo(getTags()); ?></textarea>
							</div>
						</div>
							<div class="form-group">
							<label class="col-lg-2 control-label">Envato Username</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="envato" value="<?php echo(getEnvatoUsername()); ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Rootpath</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="rootpath" value="<?php echo(rootpath()); ?>" required>
							</div>
						</div>   
						<div class="form-group">
							<label class="col-lg-2 control-label">Logo</label>
							<div class="col-lg-10">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="fileupload-new thumbnail org" style="max-width:200px">
										<img src="<?php echo (rootpath() . "/style/images/" . getLogo());?>" />
									</div>
									<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
									<div>
								    <span class="btn btn-white btn-file btn-info">
										<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select Logo</span>
										<span class="fileupload-exists"><i class="fa fa-undo"></i> Change Logo</span>
										<input type="file" name="logo" class="default" />
								    </span>  
									</div>
									<?php if($logoError) { echo $logoError;} ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Favicon</label>
							<div class="col-lg-10">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="fileupload-new thumbnail org" style="max-width:200px">
										<img src="<?php echo (rootpath() . "/style/images/" . getFavicon()); ?>" />
									</div>
									<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
									<div>
								    <span class="btn btn-white btn-file btn-info">
										<span class="fileupload-new"><i class="fa fa-paper-clip"></i> Select Favicon</span>
										<span class="fileupload-exists"><i class="fa fa-undo"></i> Change Favicon</span>
										<input type="file" name="favicon" class="default" />
								    </span>  
									</div>
									<?php if($FaviconError) { echo $FaviconError;} ?>
								</div>
							</div>
						</div>
						<hr/>
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-success" type="submit" ><i class="fa fa-pencil-square-o"></i>Update</button>
							</div>
						</div>
					</form>
					<button class="notify-without-image" id="settings" style="display:none;"></button>
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div> 
</div><!-- container -->
<?php include 'includes/footer.php'; ?>
<script>
	$('#keyword').tagsInput({
	// my parameters here
	});
</script>