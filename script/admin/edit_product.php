<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
if (isset($_GET['update']))
{
	$val = $_GET['update'];
	$val = mres($val);
	$qry   = mysqlQuery("SELECT * FROM `products` WHERE `id`='$val'");
	$fetch = mysql_fetch_array($qry);
	$arr = explode("?", $fetch['url'], 2);
	$first = $arr[0];
	$id = basename($first);
	$url = $fetch['url'];
	$cat = $fetch['cid'];
	$permalink = $fetch['permalink'];
	$itemDetails =getItemDetails($id);
	$domain = getdomain($url);
	if($domain=='audiojungle.net') {
		$image = $itemDetails['item']['preview_url'];
		$demo = $itemDetails['item']['preview_url'];
	}
	else if($domain=='videohive.net') {
		$image = $itemDetails['item']['live_preview_url'];
		$demo = $itemDetails['item']['live_preview_video_url'];
	}
	else if($domain=='activeden.net') {
		$image = $itemDetails['item']['live_preview_url'];
		if($image=="")
		{
			$image=getflash($url);
		}
	}
	else {
		$image = $itemDetails['item']['live_preview_url'];
	}
	$publishDate = $itemDetails['item']['uploaded_on'];  
	$updateDate = $itemDetails['item']['last_update'];
	$title = $itemDetails['item']['item'];
	$html = file_get_contents_curl($url);
	$description = mysql_real_escape_string(fetchProductDescription($html));
	$domain = getdomain($url);
	if($domain=="codecanyon.net" || $domain=="themeforest.net" || $domain=="graphicriver.net" || $domain=="activeden.net") 
	{
		$demo = trim(getDemoUrl($html));
		if($demo) 
		{
			$demo = "http://" . $domain . $demo;
		}
		$screens = trim(getScreenshotsUrl($html));
		if($screens) 
		{
			$screens = "http://" . $domain . $screens;
		}
	}
	else if($domain=="3docean.net") 
	{
		$demo = trim(get3doceanDemoUrl($html));
		if($demo) 
		{
			$demo = "http://" . $domain . $demo;
		}
		$screens = trim(getScreenshotsUrl($html));
		if($screens) 
		{
			$screens = "http://" . $domain . $screens;
		}
	}
	$tags = $itemDetails['item']['tags'];
	$price = $itemDetails['item']['cost'];
	$rating = $itemDetails['item']['rating'];
	updateProduct($val,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating,$permalink,$cat);
	$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Product Updated Successfully.</b></div>";			
}
$id=$val;
if(isset($_GET["id"]))
{
	$id = $_GET["id"];
}
if(isset($_POST['title']))
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$id = $_GET['id'];
	$title = htmlspecialchars($_POST["title"]);
	$description = mysql_real_escape_string($_POST["description"]);
	$screens = $_POST["screens"];
	$cat = $_POST["category"];
	$image = $_POST["image"];
	$url = $_POST["url"];
	$demo = $_POST["demo"];
	$price = $_POST["price"];
	$tags = $_POST["tags"];
	$rating = $_POST["rating"];
	if(strlen($title)<5 || strlen($title)>50)
		$error .="o. Title Must Be Between 5 to 50 Characters<br />";
	if(!validUrl($url))
		$error .="o. Invalid Purchase URL<br />";
	if(!is_numeric($price))
		$error .="o. Price Is Not In Valid Format<br />";
	if($error=="" && !$csrfError)
	{
		updateProductDb($id,$cat,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b>  Product saved Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Edit Product: <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'includes/sidebar.php'; ?>
		<div class="mainy">
			<!-- Page title -->
			<div class="page-title">
				<h2><i class="fa fa-pencil-square color"></i> Edit Product </h2> 
				<hr />
			</div>
			<!-- Page title -->
			<div class="row">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="edit_product.php?id=<?php echo($id) ?>" method="post">
					<?php if($successMessage) echo $successMessage; 
					if(isset($_POST['title']))
					{ 
						$title = htmlspecialchars($_POST["title"]);
						$description = $_POST["description"];
						$screens = $_POST["screens"];
						$cat = $_POST["category"];
						$image = $_POST["image"];
						$url = $_POST["url"];
						$demo = $_POST["demo"];
						$price = $_POST["price"];
						$tags = $_POST["tags"];
						$rating = $_POST["rating"];
						?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Title</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="title" value="<?php echo($_POST['title']); ?>"/>
								<?php
								if(strlen($title)<5 || strlen($title)>50)
								{
									echo('<span class="label label-danger">Title Must Be Between 5 to 15 Characters</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Product Description</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="5" name="description"><?php echo($_POST['description']); ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Screens</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="screens" value="<?php echo($_POST['screens']); ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Category</label>
							<div class="col-lg-10">
							<select class="form-control" name="category">
								<?php 
								$match = "SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"; 
								$qry = mysqlQuery($match);
								$numRows = mysql_num_rows($qry); 
								if ($numRows > 0) 
								{
									while($rowx = mysql_fetch_array($qry)) 
									{
										echo('<option value="' . $rowx["id"] . '">' . $rowx["name"] . '</option>');
										$qrySub = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $rowx['id'] . " ORDER BY `id`");
										$numRowsSub = mysql_num_rows($qrySub); 
										if ($numRowsSub > 0) 
										{
											while($rowxSub = mysql_fetch_array($qrySub)) 
											{
												if($_POST['category']==$rowxSub["id"])
													echo('<option value="' . $rowxSub["id"] . '" selected> &raquo; ' . $rowxSub["name"] . '</option>');
												else
													echo('<option value="' . $rowxSub["id"] . '"> &raquo; ' . $rowxSub["name"] . '</option>'); 
											}
										}
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
						<div class="form-group">
							<label class="col-lg-2 control-label">Image URL</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="image" value="<?php echo($_POST['image']); ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Purchase URL</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="url" value="<?php echo($_POST["url"]); ?>"/>
								<?php
								if(!validUrl($url))
								{
									echo('<br /><span class="label label-danger">Invalid Purchase URL</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Demo URL</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="demo" value="<?php echo($_POST['demo']); ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Price $</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="price" value="<?php echo($_POST['price']); ?>"/>
								<?php
								if(!is_numeric($price))
								{
									echo('<br /><span class="label label-danger">Invalid Price</span>');
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Tags</label>
							<div class="col-lg-10">
								<textarea id="teg" type="text" rows="8" name="tags" class="form-control tags"><?php echo $_POST['tags'];?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Rating</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" name="rating" value="<?php echo($_POST["rating"]); ?>"/>
							</div>
						</div>
						<hr />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button type="submit" class="btn btn-success" value="Add"><i class="fa fa-floppy-o"></i> Save</button>
								<a href="edit_product.php?update=<?php echo $id; ?>" class="btn btn-info">Update</a>
							</div>
						</div>
						<?php 
					}
					else
					{
						$match = "SELECT * FROM `products` WHERE `id`='$id'";
						$qry = mysqlQuery($match);
						$numRows = mysql_num_rows($qry); 
						if ($numRows > 0)
						{
							while($row = mysql_fetch_array($qry)) 
							{
							?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Product Title</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="title" value="<?php echo($row['title']); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Product Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" name="description"><?php echo($row['description']); ?></textarea>
									 </div>
								</div> 
								<div class="form-group">
									<label class="col-lg-2 control-label">Screens</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="screens" value="<?php echo($row['screens']); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Category</label>
									<div class="col-lg-10">
										<select class="form-control" name="category">
											<?php 
											$match = "SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"; 
											$qry = mysqlQuery($match);
											$numRows = mysql_num_rows($qry); 
											if ($numRows > 0) 
											{
												while($rowx = mysql_fetch_array($qry)) 
												{
													echo('<option value="' . $rowx["id"] . '">' .$rowx["name"] . '</option>');
													$qrySub = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $rowx['id'] . " ORDER BY `id`");
													$numRowsSub = mysql_num_rows($qrySub); 
													if ($numRowsSub > 0) 
													{
														while($rowxSub = mysql_fetch_array($qrySub)) 
														{
															if($row["cid"]==$rowxSub["id"])
																echo('<option value="' . $rowxSub["id"] . '" selected> &raquo; ' . $rowxSub["name"] . '</option>');
															else
																echo('<option value="' . $rowxSub["id"] . '"> &raquo; ' . $rowxSub["name"] . '</option>'); 
														}
													}
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
								<div class="form-group">
									<label class="col-lg-2 control-label">Image URL</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="image" value="<?php echo($row["image"]); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Purchase URL</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="url" value="<?php echo($row["url"]); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Demo URL</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="demo" value="<?php echo($row["demo"]); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Price</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="price" value="<?php echo($row["price"]); ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Tags</label>
									<div class="col-lg-10">
										<textarea id="teg" type="text" rows="8" name="tags" class="form-control tags"><?php echo $row['tags'];?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Rating</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="rating" value="<?php echo($row["rating"]); ?>"/>
									</div>
								</div>
								<hr />
								<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-floppy-o"></i> Save</button>
										<a href="edit_product.php?update=<?php echo $id; ?>" class="btn btn-info">Update</a>
									</div>
								</div>
								<?php 
							} 
						}
						?>
						</form>
						<?php    
					}           
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div> 
</div>
<?php include 'includes/footer.php'; ?>
<script>
	$('#teg').tagsInput({
	// my parameters here
	});
</script>