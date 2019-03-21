<?php
include 'includes/header.php';
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_POST['rssLimit']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$enable = $_POST["status"];
	if ($enable == "on") 
		$enable = 1;
	else 
		$enable = 0;
	$limit = $_POST["rssLimit"];
	$desc = $_POST["desc"];
	if(!is_numeric($desc))
	{
		$error='<span class="label label-danger">Should be an Integer</span>';
	}
	$cat = $_POST["cat"];
	if ($cat == "on") $cat = 1;
	else $cat = 0;
	
	$tag = $_POST["tag"];
	if ($tag == "on") $tag = 1;
	else $tag = 0;
	
	$top = $_POST["top"];
	if ($top == "on") $top = 1;
	else $top = 0;
	
	$recent = $_POST["recent"];
	if ($recent == "on") $recent = 1;
	else $recent = 0;
	
	if ($error == "" && !$csrfError)
	{
		updateRssSettings($enable,$limit,$desc,$cat,$tag,$top,$recent);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> RSS Settings Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>RSS Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="./rss.php"><i class="fa fa-rss color"></i></a> RSS Settings</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="rss-setting.php" method="post">
						<?php if($successMessage) echo $successMessage; ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Feeds</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (rssEnable())
								{
									?>
									<input type="checkbox" name="status" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="status" >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Limit</label>
							<div class="col-lg-2">
								<select class="form-control" name="rssLimit">
								<option value="10" <?php
								if (rssLimit() == '10')
								{
									echo "selected";
								}
								?> >10</option>
								<option value="15" <?php
								if (rssLimit() == '15')
								{
									echo "selected";
								}
								?>>15</option>
								<option value="25" <?php
								if (rssLimit() == '25')
								{
									echo "selected";
								}
								?>>25</option>
								<option value="50" <?php
								if (rssLimit() == '50')
								{
									echo "selected";
								}
								?>>50</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Description Length</label>
							<div class="col-lg-2">
								<input type="text" class="form-control" name="desc" value="<?php echo rssDescription();?>" />
								<?php
								if($error!="")
								{
									echo $error;
								}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Recent</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (rssRecentEnable())
								{
									?>
									<input type="checkbox" name="recent" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="recent" >
									<?php
								}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Top</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (rssTopEnable())
								{
									?>
									<input type="checkbox" name="top" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="top" >
									<?php
								}
								?>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Category</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (rssCatEnable())
								{
									?>
									<input type="checkbox" name="cat" checked>
									<?php
								}
								else
									{
									?>
									<input type="checkbox" name="cat" >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">RSS Tags</label>
							<div class="make-switch switches" data-on="primary" data-off="info" >
								<?php
								if (rssTagEnable())
								{
									?>
									<input type="checkbox" name="tag" checked>
									<?php
								}
								else
								{
									?>
									<input type="checkbox" name="tag" >
									<?php
								}
								?>
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
							</div>
						</div>
					</form>
					<button class="notify-without-image" style="display:none" id="RSSsetting"></button> 
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div>
</div><!-- container -->
<?php include './includes/footer.php'; ?>