<?php
include "../config/config.php";
include "../includes/functions.php";
if (isset($_POST['update']) && $_POST['up']=="1")
{
	$val=$_POST['update'];
	$qry   = mysqlQuery("SELECT * FROM `products` WHERE `id`='$val'");
	$fetch = mysql_fetch_array($qry);
	$id = basename($fetch['url']);
	$url = $fetch['url'];
	$cat = $fetch['cid'];
	$permalink = $fetch['permalink'];
	$itemDetails = getItemDetails($id);
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
	$title = mysql_real_escape_string($itemDetails['item']['item']);
	$html = file_get_contents_curl($url);
	$description = mysql_real_escape_string(fetchProductDescription($html));
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
	updateProduct($val,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$permalink,$cat);
}
?>