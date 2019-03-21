<?php 
include '../config/config.php';
include '../includes/functions.php';
$oid = $_POST['oid'];
$tid = $_POST['tid'];
$qry = mysqlQuery("UPDATE `categories` SET `parentId`='$tid' WHERE `parentId`='$oid'");
$qry2 = mysqlQuery("UPDATE `products` SET `cid`='$tid' WHERE `cid`='$oid'");
sleep(2);
deleteCategory($oid);
echo "1";
?>