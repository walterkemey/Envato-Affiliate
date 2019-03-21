<?php
include "includes/header.php";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
$error="";
if(isset($_POST['submit'])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
        $csrfError = true;
	$website = $_POST["website"];
	$websiteCat = $_POST["categories"];
	$cid = $_POST["category"];
	if($cid==0)
	{
		$error = "<div class='alert alert-danger'><li class='fa fa-warning'></li> <b>No Category found please Add some</b>.</div>";
	}
	if(!$csrfError && $error == "")
	{
		$info = addSource($website,$websiteCat,$cid);
		if(trim($info)!="skip")
			$Message = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Source Added Successfully</b>.</div>";
		else
			$Message = "<div class='alert alert-danger'><li class='fa fa-warning'></li> <b>Source Already Exists</b>.</div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add New Source : <?php echo(getTitle()) ?></title>
<script src="style/js/ckeditor/ckeditor.js"></script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-plus-circle color"></i> Add New Source </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="addSource.php" method="post">
						<?php if($Message) echo $Message; ?>
						<?php if($error) echo $error; ?>
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Website</label>
							<div class="col-lg-10">
								<select class="form-control" name="website" id="web">
									<option value="themeforest">Themeforest</option>
									<option value="codecanyon">Codecanyon</option>
									<option value="videohive">Videohive</option>
									<option value="audiojungle">Audiojungle</option>
									<option value="graphicriver">Graphicriver</option>
									<option value="3docean">3docean</option>
									<option value="activeden">Activeden</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Category</label>
							<div id="ajax_categories" class="col-lg-10">
								<select class="form-control" name="categories" id="ajax_categories"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Assign To</label>
							<div class="col-lg-10">
								<select class="form-control" name="category" id="category">
								<?php 
								$match = "SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"; 
								$qry = mysqlQuery($match);
								$numRows = mysql_num_rows($qry); 
								if ($numRows > 0) 
								{
									while($rowx = mysql_fetch_array($qry)) 
									{
										echo('<option value="' . $rowx["id"] . '">' . $rowx["name"] . '</option>');
										$matchSub = "SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $rowx['id'] . " ORDER BY `id`"; 
										$qrySub = mysqlQuery($matchSub);
										$numRowsSub = mysql_num_rows($qrySub); 
										if ($numRowsSub > 0) 
										{
											while($rowxSub = mysql_fetch_array($qrySub))
											{
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
<script type="text/javascript">
jQuery(document).ready(function()
{             
	var optionValue = jQuery("select[name='website']").val();    
	jQuery.ajax
	({
		type: "POST",
		url: "includes/populate_categories.php",
		data: ({website: optionValue}),
		success: function(response)
		{
			jQuery("#ajax_categories").html('');
			jQuery("#ajax_categories").html(response);
		}
	});  
	jQuery("select[name='website']").change(function()
	{            
		var optionValue = jQuery("select[name='website']").val();         
		jQuery.ajax
		({
			type: "POST",
			url: "includes/populate_categories.php",
			data: ({website: optionValue}),
			success: function(response)
			{
				jQuery("#ajax_categories").html('');
				jQuery("#ajax_categories").html(response);
			}
		});        
	});
});
</script>