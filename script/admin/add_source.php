<?php
include "../config/config.php";
include "../includes/functions.php";
if(isset($_POST['website']))
{
	$website = $_POST["website"];
	$websiteCat = $_POST["categories"];
	$cid = $_POST["category"];
	$info = addSource($website,$websiteCat,$cid);
	echo $info;
}
else
{
	$match = "SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"; 
	$qry = mysqlQuery($match);  
	$numRows = mysql_num_rows($qry); 
	if ($numRows > 0) 
	{
		while($rowx = mysql_fetch_array($qry)) 
		{
			echo('<option value="' . $rowx["id"] . '" disabled>' . $rowx["name"] . '</option>');
			$qrySub = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $rowx['id'] . " ORDER BY `id`");
			$numRowsSub = mysql_num_rows($qrySub); 
			if ($numRowsSub > 0) 
			{
				while($rowxSub = mysql_fetch_array($qrySub))
				{
					echo('<option value="' . $rowxSub["id"] . '"> &raquo; ' . $rowxSub["name"] . '</option>'); 
				}
			}
		}
	}
	else
	{
		echo('<option value="0">None</option>');
	}
}
?>					