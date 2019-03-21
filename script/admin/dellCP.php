<?php 
include '../config/config.php';
include '../includes/functions.php';
require "../includes/cache/phpfastcache.php";
phpFastCache::setup("storage","auto");
$cache = phpFastCache();
$tid = $_POST['tid'];
$match = mysqlQuery("SELECT `id` FROM `categories` WHERE `parentId`='$tid' OR `id`='$tid'");
while($row = mysql_fetch_array($match))
{
	$id = $row['id'];
	$data = mysqlQuery("SELECT `permalink` FROM `products` WHERE `cid`='$id'");
	while($row=mysql_fetch_array($data))
	{
		$gettags=mysql_fetch_array(mysqlQuery("SELECT `tags` FROM `products` WHERE `permalink`='".$row['permalink']."'"));
		$tags=$gettags['tags'];  
		clearTagsCache($tags);
		mysqlQuery("DELETE FROM `hotProducts` WHERE `permalink`='".$row['permalink']."'");
		mysqlQuery("DELETE FROM `products` WHERE `permalink`='".$row['permalink']."'");
		$permalink=$row['permalink'];
		$cache->delete($permalink);
		$cache->delete("sidebar_$permalink");
		$cache->delete("related_$permalink");
		clearRecentCache();
		clearTopCache();
	}
	$match = mysqlQuery("DELETE FROM `envatoSources` WHERE `cid`='$id'");
	$match = mysqlQuery("DELETE FROM `envatoSources` WHERE `cid`='$tid'");
}
$rec=mysql_fetch_array(mysqlQuery("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='$tid'"));
$parentid=$rec['parentId'];
$rec3=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`='$parentid'"));
$pPermalink=$rec3['permalink'];
$permalink=$rec['permalink'];
clearCategorycache($permalink,$parentid,$pPermalink);
$match = mysqlQuery("DELETE FROM `categories` WHERE `id`='$tid'"); 
$match = mysqlQuery("DELETE FROM `categories` WHERE `parentId`='$tid'"); 
$rows=mysql_num_rows(mysqlQuery("SELECT * FROM `products`"));
$cache->set('total',$rows);
echo "1";
?>