<?php
include "includes/header.php";
$error = "";
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
function showMedRec1Ad1()
{
	$matchAd = "SELECT `medRec1` FROM `ads`";
	$qryAd   = mysqlQuery($matchAd);
	$rowAd   = mysql_fetch_array($qryAd);
	$code    = str_replace("<q>", "'", $rowAd["medRec1"]);
	$code    = htmlspecialchars_decode($code);
	return ($code);
}
function showMedRec2Ad2()
{
	$matchAd = "SELECT `medRec2` FROM `ads`";
	$qryAd   = mysqlQuery($matchAd);
	$rowAd   = mysql_fetch_array($qryAd);
	$code    = str_replace("<q>", "'", $rowAd["medRec2"]);
	$code    = htmlspecialchars_decode($code);
	return ($code);
}
function showMedRec3Ad3()
{
	$matchAd = "SELECT `medRec3` FROM `ads`";
	$qryAd   = mysqlQuery($matchAd);
	$rowAd   = mysql_fetch_array($qryAd);
	$code    = str_replace("<q>", "'", $rowAd["medRec3"]);
	$code    = htmlspecialchars_decode($code);
	return ($code);
}
function updateAds($medRec1, $rec1Status, $medRec2, $rec2Status, $medRec3, $rec3Status)
{
	$updateQuery = "UPDATE `ads` SET `medRec1`='" . $medRec1 . "',`rec1Status`='" . $rec1Status . "',`medRec2`='" . $medRec2 . "',`rec2Status`='" . $rec2Status . "',`medRec3`='" . $medRec3 . "',`rec3Status`='" . $rec3Status . "'";
	mysqlQuery($updateQuery);
}
if (isset($_POST['submit'])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
		$csrfError = true;
	$medRec1 = $_POST["medRec1"];
	$medRec2 = $_POST["medRec2"];
	$medRec3 = $_POST["medRec3"];
	if ($_POST["rec1Status"] == "on")
		$rec1Status = 1;
	else
		$rec1Status = 0;
	if ($_POST["rec2Status"] == "on")
		$rec2Status = 1;
	else
		$rec2Status = 0;
	if ($_POST["rec3Status"] == "on")
		$rec3Status = 1;
	else
		$rec3Status = 0;
	if(!$csrfError) { 
		updateAds($medRec1, $rec1Status, $medRec2, $rec2Status, $medRec3, $rec3Status);
		$successMessage = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li><b> Ads Detail Updated Successfully.</b></div>";
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Ads Management : <?php echo (getTitle()); ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'includes/sidebar.php'; ?>
		<div class="mainy">
			<!-- Page title -->
			<div class="page-title">
				<h2><a href="ads.php"><i class="fa fa-code color"></i></a> Ads Management </h2>
				<hr />
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget">
						<form class="form-horizontal" role="form" action="ads.php" method="post">
							<?php if($successMessage) echo $successMessage; ?>
							<div class="form-group">
								<label class="col-lg-2 control-label">
									Ad 1 - 300X250 
									<div class="form-group">
										<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
											<?php
											$sql   = mysqlQuery("SELECT `rec1Status` FROM ads");
											$fetch = mysql_fetch_Array($sql);
											if ($fetch['rec1Status']) 
											{
												?> 
												<input type="checkbox" name="rec1Status" checked>
												<?php
											} 
											else 
											{
												?>
												<input type="checkbox" name="rec1Status" >
												<?php
											}
											?>
										</div>
									</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="5" name="medRec1" required><?php echo(showMedRec1Ad1()); ?> </textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">
									Ad 2 - 728X90
									<div class="form-group">
										<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
											<?php
											$sql   = mysqlQuery("SELECT `rec2Status` FROM `ads`");
											$fetch = mysql_fetch_Array($sql);
											if ($fetch['rec2Status']) 
											{
												?> 
												<input type="checkbox" name="rec2Status" checked>
												<?php
											} else 
											{
												?>
												<input type="checkbox" name="rec2Status" >
												<?php
											}
											?>
										</div>
									</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="5" name="medRec2" required><?php echo (showMedRec2Ad2()); ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">
									Ad 3 - 468X60
									<div class="form-group">
										<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
											<?php
											$sql   = mysqlQuery("SELECT `rec3Status` FROM `ads`");
											$fetch = mysql_fetch_Array($sql);
											if ($fetch['rec3Status']) 
											{
												?> 
												<input type="checkbox" name="rec3Status" checked>
												<?php
											} 
											else 
											{
												?>
												<input type="checkbox" name="rec3Status" >
												<?php
											}
											?>
										</div>
									</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="5" name="medRec3" required><?php echo (showMedRec3Ad3()); ?></textarea>
								</div>
							</div>
							<hr />
							<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
						</form>
					</div>
				</div> 
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>
<?php include 'includes/footer.php'; ?>