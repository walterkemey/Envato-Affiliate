<?php
 include "../config/config.php";
function mres($var) 
{
    if (get_magic_quotes_gpc()) 
	{
        $var = stripslashes(trim($var));	
    }
	return mysql_real_escape_string(trim($var));
}
function xssClean($data) 
{
	return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');	
}
$q=trim(strip_tags($_GET['query']));
$q=mres($q);
$q=xssClean($q);
$result = mysqlQuery("SELECT `title` FROM `products` WHERE `title` LIKE '%$q%' ORDER BY CASE WHEN title LIKE '$q%' THEN 0 ELSE 1 END LIMIT 5");
$data = array();
if($result) 
{
	while($row=mysql_fetch_array($result)) 
	{
		array_push($data,$row['title']);
	}
	echo json_encode($data);
	unset($data);
}
?>