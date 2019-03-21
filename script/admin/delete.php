<?php
include "../config/config.php";
include "../includes/functions.php";
if (isset($_POST['delete']))
{
	$val=$_POST['delete'];
	deleteProduct($val);
}
?>