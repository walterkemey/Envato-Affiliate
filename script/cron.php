<?php
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);
include "config/config.php";
include "includes/functions.php";
$query = "SELECT * FROM `envatoSources`";
$results = mysql_query($query);
while($row = mysql_fetch_array($results))
{
	$website = strtolower($row['websiteName']);
	$category = str_replace(" ","-",trim(strtolower($row['websiteCategory'])));
	$cid = $row['cid'];
	$data = file_get_contents_curl("http://marketplace.envato.com/api/edge/new-files:$website,$category.json");
	$data = json_decode($data,true);
	foreach($data['new-files'] as $file) 
	{
		$id = $file['id'];
		$itemDetails = getItemDetails($id);
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
		$publishDate = $itemDetails['item']['uploaded_on'];
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
		$record = mysql_query("SELECT * FROM `products` WHERE `url`='$url'");
		if(mysql_num_rows($record) > 0)
		{
			$rec = mysql_fetch_array($record);
			$updateDateDb = strtotime($rec['updateDate']);
			$updateDateApi = strtotime($updateDate);
			if($updateDateApi > $updateDateDb)
			{
				$cat = $rec['cid'];
				$val = $rec['id'];
				$permalink = $rec['permalink'];	
				updateProduct($val,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating,$permalink,$cat);
			}
		}
		else
		{
			$info  = addProduct($cid,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating);
		}
	}
}
?>