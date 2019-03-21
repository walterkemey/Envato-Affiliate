<?php
if(!isset($_SESSION)) 
session_start();
require 'config/config.php';
$id=$_GET['id'];
if($id=="audio")
{	
	$data="audio/audio-image.jpg";
	set_time_limit(0);
	$url = trim($data);
}
else if($id=="flash")
{
	$data="images/flash.jpg";
	set_time_limit(0);
	$url = trim($data);
}
else
{
	$data=mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `id`='$id'"));
	$data=$data['image'];
	set_time_limit(0);
	$url = trim($data);
}
function fileGetContentsCurl($url) 
{
	$options  = array('http' => array('user_agent' => 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1667.0 Safari/537.36','timeout' => 10));
	$context  = stream_context_create($options);
	return file_get_contents($url, false, $context);
}
function resizeJpeg($url, $thumbWidth, $thumbHeight) 
{
	$data = fileGetContentsCurl($url);
	$image = imagecreatefromstring($data);
	$width = imagesx($image);
	$height = imagesy($image);
	$originalAspect = $width / $height;
	$thumbAspect = $thumbWidth / $thumbHeight;
	if ( $originalAspect >= $thumbAspect ) 
	{
		$newHeight = $thumbHeight;
		$newWidth = $width / ($height / $thumbHeight);
	}
	else 
	{
		$newWidth = $thumbWidth;
		$newHeight = $height / ($width / $thumbWidth);
	}
	$thumb = imagecreatetruecolor( $thumbWidth, $thumbHeight );
	imagecopyresampled($thumb,
	$image,
	0 - ($newWidth - $thumbWidth) / 2, // Center the image horizontally
	0 - ($newHeight - $thumbHeight) / 2, // Center the image vertically
	0, 0,
	$newWidth, $newHeight,
	$width, $height);
	header('Content-Type: image/jpeg');
	return $thumb;
}
if(isset($_GET['w']))
{
	$width=$_GET['w'];
	$height=$_GET['h'];
}
$imageData = resizeJpeg($url, $width, $height);
header('Content-Type: image/jpeg');
$imageSrc =imagejpeg($imageData);
?>