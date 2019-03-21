<?php
if(!isset($_SESSION))
session_start();
include 'config/config.php';
include 'includes/functions.php';
if(isset($_POST['PageViews'])) {
//Increment PageViews,UniqHits In Stats
incrementPageViews();
if (!isset($_SESSION['uniqueHit'])) {
$_SESSION['uniqueHit'] = 1;
incrementUniqueHits(); 
}
}
//Increment Product Clicks,UniqHits,Views
if(isset($_POST['PermaLink'])) {
$permalink=$_POST['PermaLink'];
incrementProductClicks($permalink);
incrementProductPageViews($permalink);
if (!isset($_SESSION['ProductUniqHits'])) {
$_SESSION['ProductUniqHits'] = 1;
incrementProductuniqHits($permalink); 
}
}
//Increment HotProduct Clicks
if(isset($_POST['permalink'])) {
$permalink=$_POST['permalink'];
if(!(in_array($permalink, $_SESSION['clicks']))) {
array_push($_SESSION['clicks'],$permalink);
//Increment Clicks In Stats
incrementsClicks();
incrementHotClicks($permalink);
}
}
?>