<?php
include "includes/header.php";

$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

function addPage($permalink,$title,$content,$status,$displayOrder,$keywords,$description) {
	$permalink = mysql_real_escape_string($permalink);
	$title = mysql_real_escape_string($title); 
	$content = mysql_real_escape_string($content);
	$keywords = mysql_real_escape_string($keywords);
	$tags = mysql_real_escape_string($tags);
	$sql = mysqlQuery("INSERT INTO pages(`permalink`,`title`,`content`,`status`,`displayOrder`,`description`,`keywords`) VALUES ('$permalink','$title','$content','$status','$displayOrder','$description','$keywords')");
}
if(isset($_POST['title'])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
        $csrfError = true;
	$title = $_POST["title"];
	$content = $_POST["content"];
	$keywords= $_POST["keywords"];
	$description= $_POST["description"];
	if(isset($_POST["permalink"]) && trim($_POST["permalink"])!="")
		$permalink = genPermalink($_POST["permalink"]);
	else
		$permalink = genPermalink($_POST["title"]);
	if($displayOrder=="")   
		$result = mysqlQuery("SELECT COUNT(displayOrder) FROM `pages`");
	$row = mysql_fetch_array($result);
	$total = $row[0];
	$displayOrder=$total+1;
	$status = $_POST["status"];
	if(!$csrfError)
	{
		addPage($permalink,$title,$content,$status,$displayOrder,$keywords,$description);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Page Added Successfully</b>.</div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add New Page : <?php echo(getTitle()) ?></title>
<script src="style/js/ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-plus-circle color"></i> Add New Page </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="add_page.php" method="post">
						<?php if($successMessage) echo $successMessage; ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Page Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="title" placeholder="Page Name" value="<?php echo $title ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Permalink</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="permalink" placeholder="Permalink" value="<?php echo $permalink ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Content</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" cols="5" name="content"><?php echo $content ?></textarea>
								<script>
									CKEDITOR.replace( 'content', {
									uiColor: '#16cbe6'
									});
								</script>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" cols="5"  name="description"><?php echo $description ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Keywords</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" cols="5" id="keyword" name="keywords"><?php echo $keywords ?></textarea>     
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Action</label>
							<div class="col-lg-10">
								<?php if($status) { ?>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="1" checked>
											Publish
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="0">
											Save
										</label>
									</div>
									<?php 
								} 
								else { ?>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="1" >
											Publish
										</label>   
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="status" value="0" checked>
											Save
										</label>
									</div>
								<?php 
								} ?>
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" value="Add"><i class="fa fa-plus"></i> Add</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div> 
</div>
<?php include 'includes/footer.php'; ?>
<script>
	$('#keyword').tagsInput({
		
	});
</script>