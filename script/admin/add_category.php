<?php 
include "../config/config.php";
include "../includes/functions.php";
function addCategory($parent, $permalink, $name) 
{
	mysqlQuery("INSERT INTO `categories` (parentId,permalink,name) VALUES ('$parent','$permalink','$name')");
	echo "inserted";
}
$name = $_POST["name"];
$parent = $_POST["parent"];
$permalink = $_POST["permalink"];
if($name=="" || $parent=="") {
header('Location: categories.php');
exit();
}
if(trim($permalink)=="")
	$permalink=genCategoryPermalink($name);
else
	$permalink=genCategoryPermalink($permalink);
addCategory($parent,$permalink,$name);
?>