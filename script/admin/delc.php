<?php 
include '../config/config.php';
include '../includes/functions.php';
$oid = $_POST['oid_d']; 
$qry = mysqlQuery("SELECT `cid` FROM `envatoSources` WHERE `cid`='$oid'");
$numRows = mysql_num_rows($qry); 
if($numRows>0)
{
	echo "0";
}
else
{
	deleteCategory($oid);
	echo "1";
}
?>