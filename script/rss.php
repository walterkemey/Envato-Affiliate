<?php
defined("APP") or die();
if(!isset($_SESSION))
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
$dataType=mres($this->Type);
$data=mres($this->data);
if(!rssEnable())
header("Location: " . rootpath());
header("Content-type: text/xml");
echo "<?xml version='1.0' encoding='UTF-8' ?>" . PHP_EOL;
echo "<feed xml:lang='en-US' xmlns='http://www.w3.org/2005/Atom'>". PHP_EOL;
echo "<link rel='alternate' type='text/html' href='" . rootpath() . "'/>". PHP_EOL;
echo "<link rel='self' type='application/atom+xml' href='" . curPageURL() . "'/>". PHP_EOL;
if($dataType=='category' || $dataType=='search') {
if(isParentCategory($data)) {
$Pid=getParentCategoryId($data);
}
echo $Category=$data;
}
if(isset($dataType) && ($dataType=='category') && trim($data)!="" && rssCatEnable())
{
	if(isParentCategory($Category)) {
	$result = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT `id` FROM `categories` WHERE `parentId`='$Pid' OR `id` = '$Pid') ORDER BY `id` DESC LIMIT " . rssLimit());
	}
	else {
	$permalink = trim($Category);
	if(isValidCategory(trim($permalink)))
	$id = catPermalinkToId($permalink);
	$result = mysqlQuery("SELECT * FROM `products` WHERE `cid` ='$id' ORDER BY `id` DESC LIMIT " . rssLimit());
	}
	if(isParentCategory($Category))
	echo "<title> All Products RSS Feeds</title>";
	else
	echo "<title>" . getCategory($id) . " RSS Feeds</title>";
}
 else if($dataType=='tag' && trim($data)!=""  && rssTagEnable())
{
	$tagName=str_replace("-"," ",$data);
	$result =mysqlQuery("SELECT * FROM `products` WHERE `tags` LIKE '%" . $tagName. "%' ORDER BY `id` DESC LIMIT " . rssLimit());
	echo "<title>" . $tagName . " RSS Feeds</title>";
}
else if($dataType=='top' && rssTopEnable())
{
	if($data=='today')
	{
		$result = mysqlQuery("SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND `todayClicks` >0 AND `date`=CURDATE() ORDER BY h.todayClicks DESC LIMIT " . rssLimit());
	} 
	else if($data=='weekly')
	{
		$result = mysqlQuery("SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND `weeklyClicks` >0 AND `weekUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) ORDER BY h.weeklyClicks DESC LIMIT " . rssLimit());
	} 
	else if($data=='monthly')
	{
		$result = mysqlQuery("SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND `monthlyClicks` >0 AND `monthUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ORDER BY h.monthlyClicks DESC LIMIT " . rssLimit());
	}
	else if($data=='alltime')
	{
		$result = mysqlQuery("SELECT p.* FROM `products` p ,`hotProducts` h WHERE p.permalink=h.permalink AND `alltimeClicks` >0 ORDER BY h.alltimeClicks DESC LIMIT " . rssLimit());
	}
	echo "<title> Top Products RSS Feeds</title>";
}
else if($dataType=='recent')
{
	$result =mysqlQuery("SELECT * FROM `products` ORDER BY `id` DESC LIMIT " . rssLimit());
	echo "<title> Recent Products RSS Feeds</title>";
}
else
{
	$result =mysqlQuery("SELECT * FROM `products` ORDER BY `id` ASC LIMIT " . rssLimit());
	echo "<title> RSS Feeds</title>";
}
echo "<id>tag:" . getdomain(rootpath()) . "," . date("Y-m-d") . ":Item/" . rand(100,1000) . "</id>" .PHP_EOL;
echo "<updated>" . date("l, F d, Y,H:i:s" , strtotime(date('Y-m-d H:i:s'))) . "</updated>" . PHP_EOL; 
while($array= mysql_fetch_array($result))
{
	$id   = $array['id'];         
	$title = htmlentities($array['title']); 
	$link  = $array['permalink'];
	$image = "<div><a href='" . rootpath() . '/product/' . $link . ".html'><img src='".$array['image']."' /></a></div><br />";
	$description = htmlspecialchars($image . substr(preg_replace('/\n+|\t+|\s+/', ' ', strip_tags($array['description'])) ,0,rssDescription()) . " ... <a href='" . rootpath() . "/product/" . $link . ".html'><strong>Read More</strong></a>",ENT_QUOTES);
	$publishedDate	=	date(DATE_ATOM , strtotime($array['publishDate']));
	$updatedDate	=	date(DATE_ATOM , strtotime($array['updateDate']));
	$author = getAdminUsername();
	echo "<entry>" . PHP_EOL;
	echo "<title>|$title</title>" . PHP_EOL;
	echo "<link rel='alternate' type='text/html' href='" . rootpath() . '/product/' . $link . ".html'/>" . PHP_EOL;
	echo "<content type='html'>$description</content>" . PHP_EOL;
	echo "<id>tag:" . getdomain(rootpath()) . "," . date("Y-m-d") . ":Item/$id</id>" . PHP_EOL;
	echo "<published>".date("l, F d, Y,H:i:s", strtotime($publishedDate))."</published>" . PHP_EOL;
	echo "<updated>".date("l, F d, Y,H:i:s", strtotime($updatedDate))."</updated>" . PHP_EOL;
	echo "<author>" . PHP_EOL;
	echo "<name>$author</name>" . PHP_EOL;
	echo "</author>" . PHP_EOL;
	echo "</entry>" . PHP_EOL;
}
echo "</feed>";
?>