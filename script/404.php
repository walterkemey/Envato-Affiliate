<?php
defined("APP") or die();
if(!isset($_SESSION)) 
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
require "includes/header.php";
$adsdata=getAdsData();
?>
<title><?php echo($lang_array['404_title_text']) ?></title>
<?php
require 'includes/header_under.php';
?>
<div class="clearfix"></div>
<ol class="breadcrumb">
	<li>
		<a href="<?php echo(rootpath()) ?>/index.php"><?php echo($lang_array['breadcrumb_text_home']) ?></a>
	</li>
	<li class="active"><?php echo($lang_array['breadcrumb_404_text']) ?></li>
</ol>
<div class="col-md-12">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><b><?php echo($lang_array['404_title_text']) ?></b></h3>
				</div>
				<div class="panel-body">
					<div class="error-template">
						<h1>
							<?php echo($lang_array['404_first_line_text']) ?>
						</h1>
						<h2>
							<?php echo($lang_array['404_second_line_text']) ?>
						</h2>
						<div class="error-details">
							<?php echo($lang_array['404_third_line_text'] )?>
						</div>
						<div class="error-actions" style="margin-top:18px;">
							<div class="btn-group">
								<a href="<?php echo(rootpath()) ?>/index.php" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> <?php echo($lang_array['404_home_button_line_text']) ?> </a>
							</div>
							<div class="btn-group">
								<a href="<?php echo(rootpath()) ?>/contact/" class="btn btn-default btn-lg"><i class="fa fa-envelope"></i> <?php echo($lang_array['404_support_button_line_text']) ?> </a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
if($adsdata['rec2Status']) { ?>
<div class="col-md-12 hidden-xs ad_top_728x90">
<?php echo $adsdata['medRec2']; ?> 
</div>  
<?php } ?>
<?php require 'includes/footer.php'; ?>