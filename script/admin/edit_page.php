<?php
include "includes/header.php";
if(isset($_GET['id']) && is_numeric($_GET['id']))
	$_SESSION['pageId'] = $_GET['id'];
if(!$_SESSION['pageId'])
	header("location: index.php");  
else
	$id = $_SESSION['pageId'];

$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

function updatePage($id,$permalink,$title,$content,$keywords,$description,$status)
{
	$permalink = mysql_real_escape_string($permalink);
	$title = mysql_real_escape_string($title);
	$content = mysql_real_escape_string($content);
	$keywords = mysql_real_escape_string($keywords);
	$tags = mysql_real_escape_string($tags);
	$sql = "UPDATE `pages` SET `permalink`='$permalink',`title`='$title',`content`='$content',`keywords`='$keywords',`description`='$description',`status`='$status' WHERE `id`='$id'";
	mysqlQuery($sql) or die(mysql_error());
}
if(isset($_POST['submit']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$title = $_POST["title"];  
	$content = $_POST["content"];
	$keywords = $_POST["keywords"];  
	$description = $_POST["description"];
	$sql="SELECT `title`,`permalink` FROM `pages` WHERE `id`='$id'";
	$query = mysqlQuery($sql);
	$fetch = mysql_fetch_array($query);
	if(trim($_POST["permalink"])!="")
		$permalink = genPermalink($_POST["permalink"]);
	else   
		$permalink = genPermalink($_POST["title"]);
	$status = $_POST["status"];
	if(!$csrfError)
	{
		updatePage($id,$permalink,$title,$content,$keywords,$description,$status);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Page Updated Successfully.</b></div>";  
	}
}
if($id)
{
	$id=mres($id);
	$sql = "SELECT * FROM `pages` WHERE `id`='$id'";
	$query = mysqlQuery($sql);
	$fetch = mysql_fetch_array($query);
	$title = $fetch['title'];
	$content =$fetch['content'];
	$keywords = $fetch['keywords'];
	$description =$fetch['description'];
	$permalink = $fetch['permalink'];
	$status = $fetch['status'];
}

$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;

?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Edit Page : <?php echo(getTitle()) ?></title>
<script src="style/js/ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?> 
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-pencil-square-o color"></i> Edit Page </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="./edit_page.php" method="post">
						<?php if($successMessage) echo $successMessage; ?>  
						<div class="form-group">
							<label class="col-lg-2 control-label">Page Name</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="title" placeholder="Page Name" value="<?php echo($title) ?>"required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Permalink</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="permalink" placeholder="Permalink" value="<?php echo($permalink) ?>" />
							</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label">Content</label>
						<div class="col-lg-10">
							<textarea class="form-control" rows="5" cols="5" name="content"><?php echo($content)?></textarea>  
							<script>
									// Replace the <textarea id="editor"> with an CKEditor
									// instance, using default configurations.
									CKEDITOR.replace( 'content', {
									uiColor: '#16cbe6'
									});
							</script>
						</div> 
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Description</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" cols="5" name="description"><?php echo $description ?></textarea>
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
								<?php if($status) 
								{ 
									?>
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
								else 
								{ 
									?>
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
								} 
								?>
							</div>
						</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" name="submit" value="Add"><i class="fa fa-floppy-o"></i> Save</button>
							</div>
						</div>
					</form>
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
})
</script>