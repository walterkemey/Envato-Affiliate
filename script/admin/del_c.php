<?php 
include '../config/config.php';
include '../includes/functions.php';
$oid = $_POST['oid'];  
$qry = mysqlQuery("SELECT `cid` FROM `products` WHERE `cid`='$oid'");
$numRows = mysql_num_rows($qry); 
$found=0;
if($numRows>0)
{ 
	$qry = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0' AND `id`!='$oid'");
	$numRows = mysql_num_rows($qry);     
	if ($numRows > 0) 
	{
		while($rowx = mysql_fetch_array($qry)) 
		{
			$qrySub = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`=".$rowx['id']." AND `id`!='$oid'");
			$numRowsSub = mysql_num_rows($qrySub); 
			if ($numRowsSub > 0) 
			{
				$found=1;
				echo('<option value="' . $rowx["id"] . '" disabled>' . $rowx["name"] . '</option>');
				while($rowxSub = mysql_fetch_array($qrySub))
				{
					echo('<option value="' . $rowxSub["id"] . '"> &raquo; ' . $rowxSub["name"] . '</option>'); 
				}
			}
		}
		if($found==0)
		{
			echo('<option value="0">None</option>');
		}
	}
}
else
{
	echo "1";
}
?>