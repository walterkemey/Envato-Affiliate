<?php 
include '../config/config.php';
include '../includes/functions.php';
$oid = $_POST['oid'];
$qry = mysqlQuery("SELECT `id` FROM `categories` WHERE `parentId`='$oid'");
$numRows = mysql_num_rows($qry); 
$numRowsp = mysql_num_rows(mysqlQuery("SELECT * FROM `products` WHERE `cid`='$oid'"));
if($numRows>0 || $numRowsp>0)
{
	$qry = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0' AND `id` !='$oid'");
	$numRows = mysql_num_rows($qry); 
	if ($numRows > 0) 
	{
		while($rowx = mysql_fetch_array($qry)) 
		{
			echo('<option value="' . $rowx["id"] . '">' . $rowx["name"] . '</option>'); 
		}
	}
	else
	{
		echo('<option value="0">None</option>');
	}
}
else
{
	echo "1";
}
?>