<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
function updateSitemapSettings($cats,$pages, $contactForm, $videos, $outputPath)
{
	$update_query = "UPDATE `sitemaps` SET `catsStatus`='$cats',`pagesStatus`='$pages', `contFormStatus`='$contactForm', `postsStatus`='$videos', `outputPath`='$outputPath'";
	mysqlQuery($update_query);
}
function sitemapCatsStatus()
{
	$qry = mysqlQuery("SELECT `catsStatus` FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["catsStatus"];
}
function sitemapPagesStatus()
{
	$qry = mysqlQuery("SELECT `pagesStatus`FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["pagesStatus"];
}
function sitemapContFormStatus()
{
	$qry = mysqlQuery("SELECT `contFormStatus` FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["contFormStatus"];
}
function sitemapPostsStatus()
{
	$qry = mysqlQuery("SELECT `postsStatus` FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["postsStatus"];
}
function sitemapOutputPath()
{
	$qry = mysqlQuery("SELECT `outputPath` FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["outputPath"];
}
function sitemapLastModified()
{
	$qry = mysqlQuery("SELECT `lastModified` FROM `sitemaps`");
	$array = mysql_fetch_array($qry);
	return $array["lastModified"];
}
if (isset($_POST['postMethod']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$cats = $_POST["cats"];
	$pages = $_POST["pages"];
	$contactForm = $_POST["contact"];
	$videos = $_POST["videos"];
	$outputPath = trim($_POST["outputPath"]);
	if ($cats == "on") 
		$cats = 1;
	else 
		$cats = 0;
	if ($pages == "on") 
		$pages = 1;
	else 
		$pages = 0;
	if ($contactForm == "on") 
		$contactForm = 1;
	else 
		$contactForm = 0;
	if ($videos == "on") 
		$videos = 1;
	else 
		$videos = 0;
	if ($outputPath == "") 
		$error = "error";
	if ($error == "" && !$csrfError)
	{
		updateSitemapSettings($cats, $pages, $contactForm, $videos, $outputPath);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Sitemap Settings Updated Successfully.</b></div>";
	}
}
if (isset($_GET['rg']) && trim($_GET['rg']) == "true")
{
	function genCatsSitemap()
	{
		$sitemap = "";
		$match = "SELECT * FROM `categories` ORDER BY `id` DESC";
		$qry = mysqlQuery($match);
		while ($array = mysql_fetch_array($qry))
		{
			$sitemap.= '<url>' . PHP_EOL;
			$sitemap.= "<loc>" . rootpath() . "/category/" . $array['permalink'] . "</loc>" . PHP_EOL;
			$sitemap.= "<priority>0.8</priority>" . PHP_EOL;
			$sitemap.= '</url>' . PHP_EOL;
		}
		return $sitemap;
	}
	function genVideosSitemap()
	{
		$sitemap = "";
		$match = "SELECT * FROM `products` ORDER BY `id` DESC";
		$qry = mysqlQuery($match);
		while ($array = mysql_fetch_array($qry))
		{
			$sitemap.= '<url>' . PHP_EOL;
			$sitemap.= "<loc>" . rootpath() . "/product/" . $array['permalink'] . ".html</loc>" . PHP_EOL;
			$sitemap.= "<priority>0.8</priority>" . PHP_EOL;
			$sitemap.= '</url>' . PHP_EOL;
		}
		return $sitemap;
	}
	function genPagesSitemap()
	{
		$sitemap = "";
		$match = "SELECT * FROM `pages` WHERE `status`='1' ORDER BY `id` DESC";
		$qry = mysqlQuery($match);
		while ($array = mysql_fetch_array($qry))
		{
			$sitemap.= '<url>' . PHP_EOL;
			$sitemap.= "<loc>" . rootpath() . "/page/" . $array['permalink'] . "</loc>" . PHP_EOL;
			$sitemap.= "<priority>0.6</priority>" . PHP_EOL;
			$sitemap.= '</url>' . PHP_EOL;
		}
		return $sitemap;
	}
	function genRootSitemap()
	{
		$sitemap = "";
		$sitemap.= '<url>' . PHP_EOL;
		$sitemap.= "<loc>" . rootpath() . "/</loc>" . PHP_EOL;
		$sitemap.= "<priority>1.0</priority>" . PHP_EOL;
		$sitemap.= '</url>' . PHP_EOL;
		return $sitemap;
	}
	function genContactSitemap()
	{
		$sitemap = "";
		$sitemap.= '<url>' . PHP_EOL;
		$sitemap.= "<loc>" . rootpath() . "/contact</loc>" . PHP_EOL;
		$sitemap.= "<priority>0.7</priority>" . PHP_EOL;
		$sitemap.= '</url>' . PHP_EOL;
		return $sitemap;
	}
	$sitemaps = "";
	$filename = sitemapOutputPath();
	$sitemaps.= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
	$sitemaps.= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
	$sitemaps.= genRootSitemap();
	if (sitemapCatsStatus()) $sitemaps.= genCatsSitemap();
	if (sitemapPostsStatus()) $sitemaps.= genVideosSitemap();
	if (sitemapContFormStatus()) $sitemaps.= genContactSitemap();
	if (sitemapPagesStatus()) $sitemaps.= genPagesSitemap();
	$sitemaps.= '</urlset>';
	$file = fopen("../" . $filename, "w+");
	fwrite($file, $sitemaps);
	fclose($file);
	$sql = "UPDATE `sitemaps` SET `lastModified`='" . date('Y-m-d H:i:s') . "'";
	mysqlQuery($sql);
	$regenerated = true;
	$regen_msg = "Sitemap Generated and Saved in <a href='" . rootpath() . "/" . $filename . "'> <strong>" . rootpath() . "/" . $filename . "</a>";
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>  
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Sitemap Settings : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2><a href="./sitemaps.php"><i class="fa fa-sitemap color"></i></a> Sitemap Settings</h2>
			<hr />
		</div>
		<!-- Page title --> 
		<span class="toggle_msg label label-success" style="display:none;">
		<i class="fa fa-check"></i> Sitemap Updated successfully</span>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="sitemaps.php" method="post">
					<?php if($successMessage) echo $successMessage; ?>
					<div class="form-group">
						<label class="col-lg-2 control-label">Products</label>
						<div class="make-switch switches" data-on="primary" data-off="info" >
						<?php
						if (sitemapPostsStatus())
						{
							?>
							<input type="checkbox" name="videos" checked>
							<?php
						}
						else
						{
							?>
							<input type="checkbox" name="videos" >
							<?php
						}
						?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Categories</label>
						<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (sitemapCatsStatus())
							{
								?>
								<input type="checkbox" name="cats" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="cats" >
								<?php
							}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Pages</label>
						<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (sitemapPagesStatus())
							{
								?>
								<input type="checkbox" name="pages" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="pages" >
								<?php
							}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Contact Form</label>
						<div class="make-switch switches" data-on="primary" data-off="info" >
							<?php
							if (sitemapContFormStatus())
							{
								?>
								<input type="checkbox" name="contact" checked>
								<?php
							}
							else
							{
								?>
								<input type="checkbox" name="contact" >
								<?php
							}
							?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">File Name</label>
						<div class="col-lg-4">
							<input type="text" class="form-control" name="outputPath" placeholder="e.g: sitemap.xml" value="<?php echo (sitemapOutputPath()); ?>" required/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-2 control-label">Last Generated</label>
						<div class="col-lg-3">
						<h4><span class="label label-info"><?php echo (sitemapLastModified()); ?> <i class="fa fa-clock-o"></i></span></h4>
						</div>
					</div>
					<input type="hidden" name="postMethod" value="1">
					<hr />
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<button type="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Save Settings</button>
							<a href="./sitemaps.php?rg=true" class="btn btn-success"><i class="fa fa-refresh"></i> Generate Sitemap</a>
						</div> 
					</div>
					<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
					<div class="form-group">
						<label class="col-lg-2 control-label"></label>
						<div class="col-lg-8">
							<?php
							if (!is_writable("../" . sitemapOutputPath()))
							{
								?>
								<span class="label label-danger"><i class="fa fa-exclamation-triangle"></i> <?php
								echo (sitemapOutputPath()) ?> is not Writeable Please CHMOD 777</span> 
								<?php
							}
							else if (isset($_POST['categories']) && $error == "")
							{
								?>
								<span class="label label-success">Saved Successfully</span>
								<?php
							}
							else if (isset($_GET['rg']) && trim($_GET['rg']) == "true" && $regenerated)
							{
								echo '<div class="alert alert-success"><li class="fa fa-check-square-o"></li><b> ' . $regen_msg . '</b></div>';
							}
							?>
						</div>
					</div>
					</form>
					<button class="notify-without-image" style="display:none" id="sitemaps_update"></button> 
				</div> <!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div>
</div><!-- container -->
<?php include 'includes/footer.php'; ?>