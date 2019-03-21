<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if(isset($_GET['id']))
$id=$_GET['id'];
$id=mres($id);
if(isset($_POST['name']) && $_POST['name']!="")
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$name = $_POST["name"];
	$parent = $_POST["parent"];
	if(!isAlpha($name))
	{
		$error .="Category Name Can Only Contain Letters a-Z and Numbers 0-9";
	}
	else if(strlen($name)<3 || strlen($name)>30)
	{
		$error .="Category Length Must Be Between 3 to 30 Characters";
	}
	$permalink = $_POST["permalink"];
	if(($permalink_old!=$permalink) || $catname!=$name)
	{
		if(trim($permalink)=="")
			$permalink=genCategoryPermalink($name,$id);
		else
			$permalink=genCategoryPermalink($permalink,$id);
	}
	if($error=="" && !$csrfError)
	{
		updateCategory($parent,$permalink,$id,$name);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Category Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Category : <?php echo(getTitle()) ?></title>
</head>  
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-pencil-square-o color"></i> Edit Category </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
				<?php
				if(isset($_GET['id']) && is_numeric($_GET['id']))
				{
					$qry = mysqlQuery("SELECT * FROM `categories` WHERE `id` ='$id'");
					$numRows = mysql_num_rows($qry); 
					if ($numRows > 0)
					{
						while($row = mysql_fetch_array($qry)) 
						{
							$parent = $row['parentId'];
							$permalink_old=$row['permalink'];
							$catname = $row['name'];
							?>
							<form action="edit_category.php?id=<?php echo($id); ?>"  class="form-horizontal" role="form" method="post">
							<?php if($successMessage) echo $successMessage; ?>
							<div class="form-group">
								<label class="col-lg-2 control-label">Category Name</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="name" placeholder="Category Name" value="<?php echo($row['name']); ?>"/>
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
									<input type="text" class="form-control" name="permalink" placeholder="Leave Empty To Auto Generate" value="<?php echo($row['permalink']); ?>"/>
								</div>
							</div>			
							<div class="form-group">
								<label class="col-lg-2 control-label">Parent Category</label>
								<div class="col-lg-10">
									<select class="form-control" name="parent">
									<?php 
									$qry = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0' AND `id` NOT IN('$id')");
									$numRows = mysql_num_rows($qry);
									if ($numRows > 0) 
									{
										echo('<option value="0">None</option>');
										while($rowx = mysql_fetch_array($qry)) 
										{
											if($parent==$rowx["id"])
												echo('<option value="' . $rowx["id"] . '" selected>' . $rowx["name"] . '</option>');
											else
												echo('<option value="' . $rowx["id"] . '">' . $rowx["name"].'</option>');
										}
									}
									else
									{
										echo('<option value="0">None</option>');
									}
									?>
									</select>
								</div>
							</div>  
							<hr />
							<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button type="submit" class="btn btn-success" value="Update"><i class="fa fa-floppy-o"></i> Save</button>
									<a href="categories.php" class="btn btn-danger"><i class="fa fa-times"></i> Cancel</a>
								</div>
							</div>
							<?php
						}
					}
				}
				else
				{
					header("location:categories.php");
				}
				?>
				</form>
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div> 
</div><!-- container -->
<?php include 'includes/footer.php'; ?>