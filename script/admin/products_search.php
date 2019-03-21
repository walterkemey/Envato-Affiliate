<?php 
include '../config/config.php';
$q=trim(strip_tags($_GET['query']));
$result = mysqlQuery("SELECT * FROM `products` WHERE `title` like '%" . $q . "%' LIMIT 5");
$data = array();
if($result) 
{
	while($row=mysql_fetch_array($result)) 
	{
		array_push($data,mysql_real_escape_string($row['title']));
	}
	echo json_encode($data);
	unset($data);
}
?>