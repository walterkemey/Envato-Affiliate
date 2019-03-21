<?php 
define("APP",1);
define("ROOT",dirname(dirname(__FILE__)));				
include(ROOT."/includes/App.class.php");
$app = new App($config);