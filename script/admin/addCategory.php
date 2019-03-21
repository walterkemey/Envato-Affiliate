<?php
include "includes/header.php";
function addCategory($parent, $permalink, $name) 
{
	mysqlQuery("INSERT INTO `categories` (parentId,permalink,name) VALUES ('$parent','$permalink','$name')");
}
$error="";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);

if(isset($_POST['submit'])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
        $csrfError = true;
	$name = $_POST["name"];
	if(!isAlpha($name))
	{
		$error .="Category Name Can Only Contain Letters a-Z and Numbers 0-9";
	}
	$parent = $_POST["parent"];
	$permalink = $_POST["permalink"];
	if(trim($permalink)=="")
		$permalink=genCategoryPermalink($name);
	else
		$permalink=genCategoryPermalink($permalink);
	if(!$csrfError && $error == "")
	{
		addCategory($parent, $permalink, $name);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Category Added Successfully</b>.</div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add New Category : <?php echo(getTitle()) ?></title>
<script src="style/js/ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-plus-circle color"></i> Add New Category </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="addCategory.php" method="post">
						<?php if($successMessage) echo $successMessage; ?>
							<div class="form-group">
								<label class="col-lg-2 control-label">Category Name</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="name" placeholder="Category Name" id="cat" value="" required>
									<?php
									if($error!="")
									{ 
										?>
										<span class="label label-danger"><?php echo($error); ?></span>
										<?php 
									} 
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Permalink</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="permalink" placeholder="(Optional)" id="permalink" value=""/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Parent</label>
								<div class="col-lg-10">
									<select class="form-control" name="parent" id="parent">
										<option value="0">None</option>
										<?php
										$queryCategories = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"); 
										if (mysql_num_rows($queryCategories) > 0) 
										{
											while($row = mysql_fetch_array($queryCategories)) 
											{
												echo('<option value="' . $row["id"] . '">' . $row["name"] . '</option>'); 
											}
										} 
										?>
									</select>
								</div>
							</div>
						<hr />
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" name="submit" value="Add"><i class="fa fa-plus"></i> Add</button>
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