<?php
if (!isset($_SESSION)) session_start();
if(isset($_SESSION['admin_eap_secure']))
{
	unset($_SESSION['admin_eap_secure']);
}
header("location:./index.php");
?>