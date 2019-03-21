<?php
defined("APP") or die();
if(!isset($_SESSION)) 
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
$Permalink=$this->Permalink;
$pagedata=getPageData($Permalink);
$adsdata=getAdsData();
require 'includes/header.php'; 
?>
<title><?php echo (isValidPage($Permalink)?pageTitleByPermalink($Permalink):'404-Page Not Found :('); ?></title>
<meta name="description" content="<?php echo strip_tags($pagedata['description'])?>" />
<meta name="keywords" content="<?php echo ($pagedata['keywords'])?>" />
<meta property="og:title" content="<?php echo pageTitleByPermalink($Permalink); ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/page/'.$Permalink?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($pagedata['description'])?>" /> 
<meta property="og:site_name" content="<?php echo (getWebsiteName())?>" />
<?php
require 'includes/header_under.php';
if (isset($Permalink) && $Permalink != '')
{
	$page = $Permalink;
	$query = mysqlQuery("SELECT * FROM `pages` WHERE `permalink`='" . $page . "' AND `status`='1'");
	$fetch = mysql_fetch_array($query);
	$id = $fetch['id'];
	if ($id)
	{
		$title = dbDecode($fetch['title']);
		$content = dbDecode($fetch['content']);
	}
	else
	{
		echo page404($lang_array);
			exit();
	}
}
?>
<div class="clearfix"></div>
<ol class="breadcrumb">
	<li>
		<a href="<?php echo(rootpath()) ?>"><?php echo $lang_array['breadcrumb_text_home'];?></a>
	</li>
	<li class="active"><?php echo($fetch['title']) ?></li>
</ol>
<div class="row">
<div class="col-lg-8">
   <div class="row">
      <div class="col-lg-12">
         <div class="panel panel-default">
            <div class="panel-heading">
				<h3 class="panel-title"><b><?php echo($fetch['title']) ?></b></h3>
            </div>
            <div class="panel-body pages">
				<?php echo($fetch['content']) ?>
            </div>
         </div>
      </div>
	     <?php
   if($adsdata['rec2Status']) { ?>
<div class="col-md-12 hidden-xs ad_top_728x90">
<?php echo($adsdata['medRec2']) ?> 
</div>  
<?php } ?>
   </div>
</div>
<?php if($adsdata['rec1Status']) { ?>
<div class="col-md-4 hidden-xs hidden-sm ads-margin">
        <div class="col-md-12 ad-hidden" style="margin-bottom: 10px">
		<?php echo ($adsdata['medRec1']) ?>  
		</div>
		<div class="col-md-12 ad-hidden">
		<?php echo ($adsdata['medRec1']) ?>  
		</div>         
</div>
</div>
<?php
}
require 'includes/footer.php';
?>