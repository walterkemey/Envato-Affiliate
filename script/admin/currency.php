<?php
include "includes/header.php";
$error = false;
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
function updateCurrency($currencySymbol,$priceInDollar,$showBefore) 
{
	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `currencySettings`"));
	if($rows>0) 
	{
		mysqlQuery("UPDATE `currencySettings` SET `currencySymbol`='$currencySymbol',`priceInDollar`='$priceInDollar',`showBefore`='$showBefore'");
	} 
	else 
	{
		mysqlQuery("INSERT INTO `currencySettings`(currencySymbol,priceInDollar,showBefore) VALUES('$currencySymbol','$priceInDollar','$showBefore')");
	}
}
if(isset($_POST['submit'])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$currencySymbol = mysql_real_escape_string(trim($_POST["currencySymbol"]));
	$priceInDollar = trim($_POST["priceInDollar"]);
	if((is_numeric($priceInDollar) || is_float($priceInDollar)) && $currencySymbol!="" )
		$error = false;
	else
		$error = true;
	$showBefore= $_POST["showBefore"];
	if(!$error && !$csrfError)
		updateCurrency($currencySymbol,$priceInDollar,$showBefore);
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
 ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Settings: <?php echo(getTitle()) ?></title>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$('#settings').hide();
	});
</script>  
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-dollar color"></i> currency Settings </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form class="form-horizontal" role="form" action="currency.php" method="post">
						<?php if(isset($_POST['currencySymbol'])) 
						{ 
						 if(!$error) {
							?>
							<div class="alert alert-success alert-dismissable">
								<i class="fa fa-check-square-o"></i><b> Currency Settings Updated successfully</b>
							</div>
							<?php } else {?>
							<div class="alert alert-danger alert-dismissable">
								<i class="fa fa-warning"></i><b> Currency Settings Not Updated</b>
							</div>
							<?php } ?>
							<div class="form-group">
								<label class="col-lg-3 control-label">Currency Symbol</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="currencySymbol" value="<?php echo $_POST['currencySymbol']; ?>" placeholder="Enter your currency name/symbol"  />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">One US Dollar = ??</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="priceInDollar" value="<?php echo $_POST['priceInDollar']; ?>" placeholder="Your currency equivalent to 1 USD ?"  />
								</div>
							</div>
							<div class="form-group">
							<label class="col-lg-3 control-label">Where to Show Currency Symbol</label>
							<div class="col-lg-9">
								<?php 
								if($_POST['showBefore']) 
								{  
									?>
									<div class="radio">
										<label><input type="radio" name="showBefore" value="1" checked>Before Value</label>
									</div>
									<div class="radio">
										<label><input type="radio" name="showBefore" value="0">After Value</label>
									</div>
									<?php 
								} 
								else 
								{ 
									?>
									<div class="radio">
										<label>
											<input type="radio" name="showBefore" value="1">Before Value
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="showBefore" value="0" checked>After Value
										</label>
									</div>
									<?php 
								} 
								?> 
							</div>
							<?php 
						} 
						else 
						{
							$array = mysql_fetch_array(mysqlQuery("SELECT * FROM `currencySettings`"));
							$currencySymbol = $array['currencySymbol'];
							$priceInDollar = $array['priceInDollar'];
							$showBefore = $array['showBefore'];
							?>
							<div class="form-group">
								<label class="col-lg-3 control-label">Currency Symbol</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="currencySymbol" value="<?php echo $currencySymbol; ?>" placeholder="Enter your currency name/symbol"  />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">One US Dollar = ??</label>
								<div class="col-lg-9">
									<input type="text" class="form-control" name="priceInDollar" value="<?php echo $priceInDollar; ?>" placeholder="Your currency equivalent to 1 USD ?"  />
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">Where to Show Currency Symbol</label>
								<div class="col-lg-9">
									<?php 
									if($showBefore) 
									{  
										?>
										<div class="radio">
											<label><input type="radio" name="showBefore" value="1" checked>Before Value</label>
										</div>
										<div class="radio">
											<label><input type="radio" name="showBefore" value="0">After Value</label>
										</div>
										<?php 
									} 
									else 
									{ 
										?>
										<div class="radio">
											<label>
												<input type="radio" name="showBefore" value="1">Before Value
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="showBefore" value="0" checked>After Value
											</label>
										</div>
										<?php 
									} 
									?> 
								</div>
							</div>
							<?php 
						} 
						?>	
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<hr/>
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-success" name="submit" type="submit" ><i class="fa fa-pencil-square-o"></i>Update</button>
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