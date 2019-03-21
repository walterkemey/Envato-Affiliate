<?php
include "../config/config.php";
		include "../includes/functions.php";
if(isset($_POST['id']))
{
	$id=$_POST['id'];  
	$qry = mysqlQuery("SELECT `websiteName`,`websiteCategory`,`cid` FROM `envatoSources` WHERE id=".$_POST['id']);
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		$website = strtolower($row['websiteName']);
		$category = str_replace(" ","-",trim(strtolower($row['websiteCategory'])));
		$cid = $row['cid'];
		$data = file_get_contents_curl("http://marketplace.envato.com/api/edge/new-files:$website,$category.json");
		$data = json_decode($data,true);
		foreach($data['new-files'] as $file) 
		{
			$allId.=$file['id']."-";
		}
		$allId.=$cid;
		$update = "UPDATE `envatoSources` SET `posts`= '1' WHERE `id`='$id'";
		mysqlQuery($update);
		echo $allId;
	}
}
if(isset($_POST['URL']) && isset($_POST['CID']))
{
	$cid=$_POST['CID'];
	$itemDetails = getItemDetails($_POST['URL']);
	$url = $itemDetails['item']['url'];
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
	$publisDate = $itemDetails['item']['uploaded_on'];
	$updateDate = $itemDetails['item']['last_update'];
	$title = mysql_real_escape_string($itemDetails['item']['item']);
	$html = file_get_contents_curl($url);
	$description = mysql_real_escape_string(fetchProductDescription($html));
	if($domain=="codecanyon.net" || $domain=="themeforest.net" || $domain=="graphicriver.net" || $domain=="activeden.net") 
	{
		$demo = trim(getDemoUrl($html));
		if($demo) {
			$demo = "http://" . $domain . $demo;
		}
		$screens = trim(getScreenshotsUrl($html));
		if($screens) {
			$screens = "http://" . $domain . $screens;
		}
	}
	else if($domain=="3docean.net") 
	{
		$demo = trim(get3doceanDemoUrl($html));
		if($demo) {
			$demo = "http://" . $domain . $demo;
		}
		$screens = trim(getScreenshotsUrl($html));
		if($screens) {
			$screens = "http://" . $domain . $screens;
		}
	}
	$tags = $itemDetails['item']['tags'];
	$price = $itemDetails['item']['cost'];
	$rating = $itemDetails['item']['rating'];
	$info  = addProduct($cid,$publisDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating);
	$total=mysql_num_rows(mysqlQuery("SELECT * FROM `products`"));
	if($info=="added")
	{
		$added='<span class="label label-success">Added : ' . $title . '</span><br />';
		$added.="_".$total;
		echo $added;
	}
	else if($info=="skip")
	{
		$skipped='<span class="label label-info">Already Exists : ' . $title . '</span><br />';
		$skipped.="_".$total;
		echo $skipped;
	}
	else if($info=="not")
	{
		$invalid='<span class="label label-danger">Invalid URL : ' . $url . '</span><br />';
		$invalid.="_".$total;
		echo $invalid;
	}
}
?>