<?php
libxml_use_internal_errors(true);
require "cache/phpfastcache.php";
include "simpleMail.php";
phpFastCache::setup("storage","auto");
function innerHTML(DOMNode $node) 
{
	$doc = new DOMDocument();
	foreach ($node->childNodes as $child) 
	{
		$doc->appendChild($doc->importNode($child, true));
	}
	return $doc->saveHTML();
}
function authenticate($username, $password) 
{
	$username = mysql_real_escape_string($username);
	$password = md5($password);
	$query    = mysqlQuery("SELECT `email` FROM `settings` WHERE `username`='$username' AND `password`='$password'");
	if (mysql_num_rows($query) > 0)
		return true;
	return false;
}
function cat_has_childs($cid) 
{
	$qry_sub = mysqlQuery("select id from categories where parent_id=" . $cid);
	$numRows_sub = mysql_num_rows($qry_sub);
	if ($numRows_sub > 0)
		return true;
	else
		return false;
}
function stringLimitWords($string, $word_limit) 
{
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}
function catPermalinkToId($permalink) 
{
	$qry   = mysqlQuery("SELECT `id` FROM `categories` WHERE `permalink`='$permalink'");
	$rows = mysql_num_rows($qry);
	if($rows>0) 
	{
		$fetch = mysql_fetch_array($qry);
		return $fetch['id'];
	}
	return false;
}
function catIdToPermalink($id) 
{
	$qry   = mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`=".$id);
	$fetch = mysql_fetch_array($qry);
	return $fetch['permalink'];
}
function productCategory($permalink) 
{
	$queryProductCategory = mysqlQuery("SELECT cid FROM `products` WHERE permalink='$permalink'");
	if (mysql_num_rows($queryProductCategory)) 
	{
		$rowProductCategory = mysql_fetch_array($queryProductCategory);
		$rowMatchCategory = mysql_fetch_array(mysqlQuery("SELECT name FROM `categories` WHERE `id`='" . $rowProductCategory['cid'] . "'"));
		return ($rowMatchCategory['name']);
	} 
	else 
	{
		return "";
	}
}
function productPermalink($permalink) 
{
	$queryProductCategory = mysqlQuery("SELECT cid FROM `products` WHERE permalink='$permalink'");
	if (mysql_num_rows($queryProductCategory)) 
	{
		$rowProductCategory = mysql_fetch_array($queryProductCategory);
		$rowMatchCategory = mysql_fetch_array(mysqlQuery("SELECT permalink FROM `categories` WHERE `id`='" . $rowProductCategory['cid'] . "'"));
		return ($rowMatchCategory['permalink']);
	} 
	else 
	{
		return "";
	}
}
function parentCategory($permalink) 
{
	$queryProductCategory = mysqlQuery("SELECT cid FROM `products` WHERE permalink='$permalink'");
	if (mysql_num_rows($queryProductCategory)) 
	{
		$rowProductCategory = mysql_fetch_array($queryProductCategory);	
		$rowMatchCategoryId = mysql_fetch_array(mysqlQuery("SELECT parentId FROM `categories` WHERE id='" . $rowProductCategory['cid'] . "'"));
		$rowMatchCategory = mysql_fetch_array(mysqlQuery("SELECT name FROM `categories` WHERE id='" . $rowMatchCategoryId['parentId'] . "'"));
		return ($rowMatchCategory['name']);
	} 
	else 
	{
		return "";
	}
}
function genPermalink($title) 
{
	$permalink     = stringLimitWords($title, 9);
	$permalink     = preg_replace('/[^a-z0-9]/i', ' ', $permalink);
	$permalink     = trim(preg_replace("/[[:blank:]]+/", " ", $permalink));
	$permalink     = strtolower(str_replace(" ", "-", $permalink));
	$count         = 1;
	$temppermalink = $permalink;
	while (isValidProduct($permalink)) 
	{
		$permalink = $temppermalink . '-' . $count;
		$count++;
	}
	return $permalink;
}
function genCategoryPermalink($title,$id=0) 
{
	$permalink     = stringLimitWords($title, 9);
	$permalink     = preg_replace('/[^a-z0-9]/i', ' ', $permalink);
	$permalink     = trim(preg_replace("/[[:blank:]]+/", " ", $permalink));
	$permalink     = strtolower(str_replace(" ", "-", $permalink));
	$count         = 1;
	$temppermalink = $permalink;
	while (isValidCategory($permalink,$id)) 
	{
		$permalink = $temppermalink . '-' . $count;
		$count++;
	}
	return $permalink;
}

function get_product_title($url) 
{
	$url     = httpify($url);
	$url     = strtok($url, "?");
	$itemid  = basename($url);
	$content = file_get_contents_curl("http://marketplace.envato.com/api/edge/item:$itemid.json");
	$content = json_decode($content, true);
	if ($content['item']['item'])
		return $content['item']['item'];
	else
		return "";
}
function get_cover($url) 
{
	$url     = httpify($url);
	$url     = strtok($url, "?");
	$itemid  = basename($url);
	$content = file_get_contents_curl("http://marketplace.envato.com/api/edge/item:$itemid.json");
	$content = json_decode($content, true);
	if ($content['item']['live_preview_url'])
		return $content['item']['live_preview_url'];
	else
		return "";
}
function get_product_tags($url) 
{
	$url     = httpify($url);
	$url     = strtok($url, "?");
	$itemid  = basename($url);
	$content = file_get_contents_curl("http://marketplace.envato.com/api/edge/item:$itemid.json");
	$content = json_decode($content, true);
	if ($content['item']['tags'])
		return $content['item']['tags'];
	else
		return "";
}
function fetchProductDescription($html) 
{
	$description = "";
	$dom = new DOMDocument();
	$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false;
	$xpath = new DOMXPath($dom);
	$divs_description_text   = $xpath->query('//div[@class="user-html"]');
	foreach ($divs_description_text as $div) 
	{
		$description = (strip_tags(trim(innerHTML($div)), '<strong><ul<br /><br><pre><img><ol><li><h1><h2><h3><h4><a>'));
	}
	return $description;
}
function getflash($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$html = curl_exec($ch);
	curl_close($ch);
	$dom = new DOMDocument();
	@$dom->loadHTML($html);
	foreach($dom->getElementsByTagName('span') as $link) 
	{
		$cb=$link->getAttribute('data-props');
		$str.= get_string_between($cb,'preview_url":"','","base":');
	}
	return $str;
}
function get_string_between($string, $start, $end)
{ 
	$string = " " . $string;
	$ini = strpos($string, $start);
	if ($ini == 0) return "";
	$ini+= strlen($start);
	$len = strpos($string, $end, $ini) - $ini;
	return substr($string, $ini, $len);
}
function getDemoUrl($html)
{
	$demo_url = "";
	$dom = new DOMDocument();   
	$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false;
	$xpath = new DOMXPath($dom);                
	$divs_demo_url = $xpath->query('//a[@class="btn-icon live-preview"]');
	foreach ($divs_demo_url as $url) 
	{
		$demo_url = $url->getAttribute('href'); 
	}
	return $demo_url;
}
function get3doceanDemoUrl($html)
{
	$demo_url = "";
	$dom = new DOMDocument();   
	$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false;
	$xpath = new DOMXPath($dom);                
	$divs_demo_url = $xpath->query('//a[@class="btn-icon screenshots"]');
	foreach ($divs_demo_url as $url) 
	{
		$demo_url = $url->getAttribute('href');
		break;
	}
	return $demo_url;
}
function getScreenshotsUrl($html)
{
	$screenshots_url = "";
	$dom = new DOMDocument();   
	$dom->loadHTML($html);
	$dom->preserveWhiteSpace = false;
	$xpath = new DOMXPath($dom);                
	$divs_screenshots_url = $xpath->query('//a[@class="btn-icon screenshots"]');
	foreach ($divs_screenshots_url as $url) 
	{
		$screenshots_url = $url->getAttribute('href'); 
	}
	return $screenshots_url;
}
function httpify($link) 
{
	if (preg_match("#https?://#", $link) === 0)
		$link = 'http://' . $link;
	return $link;
}
function get_price($url) 
{
	$url     = httpify($url);
	$url     = strtok($url, "?");
	$itemid  = basename($url);
	$content = file_get_contents_curl("http://marketplace.envato.com/api/edge/item:$itemid.json");
	$content = json_decode($content, true);
	if ($content['item']['cost'])
		return $content['item']['cost'];
	else
		return "0";
}
function rootpath() 
{
	$query = mysqlQuery("SELECT `rootpath` FROM `settings`");
	$fetch = mysql_fetch_array($query);
	if ($fetch['rootpath'] != "") 
	{
		return $fetch['rootpath'];
	} 
	else 
	{
		$server = $_SERVER['SERVER_NAME'];
		$root   = 'http://' . $server . dirname($_SERVER['SCRIPT_NAME']);
		if (substr($root, -1) == "/")
			return ('http://' . $server);
		else
			return $root;
	}
}
function trim_string($string) 
{
	$string = (strlen($string) > 500) ? substr($string, 0, 500) . '...' : $string;
	return $string;
}
function trim_title($string) 
{
	$string = (strlen($string) > 34) ? substr($string, 0, 34) . '...' : $string;
	return $string;
}
function trimTitleRelated($string) 
{
	$string = (strlen($string) > 25) ? substr($string, 0, 25) . '...' : $string;
	return $string;
}
function shortDescription($string) 
{
	$string = (strlen($string) > 160) ? substr($string, 0, 160) : $string;
	return $string;
}
function updateSettings($title,$websiteName,$description,$keywords,$envato,$rootpath,$logo,$favicon)
 {
	mysqlQuery("UPDATE `settings` SET `title`='" .$title. "', `websiteName`='" . $websiteName . "', `description`='" .$description. "', `metaTags`='" .$keywords. "', `envato`='" .$envato. "', `rootpath`='" .$rootpath. "', `logo`='" . $logo . "',`favicon`='" . $favicon . "'") or die(mysql_error());
}
function updateUser($username, $password, $email) 
{
	if ($password != "")
		$updateQuery = "UPDATE settings SET username='" . $username . "',password='" . $password . "',email='" . $email . "'";
	else
		$updateQuery = "UPDATE settings SET username='" . $username . "',email='" . $email . "'";
	$qry = mysqlQuery($updateQuery);
	return true;
}
function validFacebookUrl($field) 
{
	if (!preg_match('/^[a-z\d.]{5,}$/i', $field))
		return false;
	return true;
}
function validTwitterUsername($field) 
{
	if (!preg_match('/^[A-Za-z0-9_]+$/', $field)) 
		return false;
	return true;
}
function validGoogleUrl($field) 
{
	if (!preg_match('/^[A-Za-z0-9]+$/', $field))
		return false;
	return true;
}
function validPinterestPagename($field) 
{
	if (!preg_match('/^[A-Za-z0-9_]+$/', $field))
		return false;
	return true;
}
function curPageURL() 
{
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") 
	{
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") 
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	} 
	else 
	{
		$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
function getTwitter() 
{
	$qry   = mysqlQuery("SELECT `twitter` FROM `socialProfiles`");
	$row   = mysql_fetch_array($qry);
	return ($row['twitter']);
}
function getFacebook() {
	$qry   = mysqlQuery("SELECT `facebook` FROM `socialProfiles`");
	$row   = mysql_fetch_array($qry);
	return ($row['facebook']);
}
function getGoogle() {
	$qry   = mysqlQuery("SELECT `google` FROM `socialProfiles`");
	$row   = mysql_fetch_array($qry);
	return ($row['google']);  
}
function getPinterest() {
	$qry   = mysqlQuery("SELECT `pinterest` FROM `socialProfiles`");  
	$row   = mysql_fetch_array($qry);
	return ($row['pinterest']);
}
function updateSocial($facebook, $twitter, $google, $pinterest, $socialStatus) {
	mysqlQuery("UPDATE socialProfiles SET `facebook`='" . $facebook . "',`twitter`='" .$twitter . "',`google`='" . $google . "',`pinterest`='" . $pinterest . "',`status`='" .$socialStatus . "'");
}
function file_get_contents_curl($url) 
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
function sencrypt($text) 
{
	return strtr(base64_encode($text), '+/=', '-_,');
}
function sdecrypt($text) 
{
	return base64_decode(strtr($text, '-_,', '+/='));
}
function resetPass($email) 
{ 
	$qry_user = mysqlQuery("SELECT `username` FROM `settings` WHERE `email`='" . $email . "'");
	$row_user = mysql_fetch_array($qry_user);
	$username = $row_user['username'];
	$password = genPassword();
	sendEmail(getAdminEmail(),$email,"Password Received","Your Login Details Updated\nUsername: " .$username . "\nYour new password is: " . $password . "\nLogin Here: " . rootpath().'/admin');
	$qry = mysqlQuery("UPDATE `settings` SET `password`='". md5($password) ."' WHERE `email`='".$email."'");
}
function getdomain($url) 
{
	$parsed = parse_url($url);
	return str_replace('www.', '', strtolower($parsed['host']));
}
function getEnvatoUsername() 
{
	$qry = mysqlQuery("SELECT `envato` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['envato']);
	}
}
function getTitle() 
{
	$qry = mysqlQuery("SELECT `title` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['title']);
	} 
	else 
	{
		return ("Affiliate Portal Envato");
	}
}
function getWebsiteName() 
{
	$qry = mysqlQuery("SELECT `websiteName` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['websiteName']);
	} 
	else 
	{
		return ("Affiliate Portal Envato");
	}
}
function getAdminEmail() 
{
	$qry = mysqlQuery("select email from settings");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['email']);
	}
}
function getAdminUsername() 
{
	$qry = mysqlQuery("SELECT `username` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['username']);
	}
}
function getDescription() 
{
	$qry = mysqlQuery("SELECT `description` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) {
		$row = mysql_fetch_array($qry);
		return ($row['description']);
	}
}
function getTags() 
{
	$qry = mysqlQuery("SELECT `metaTags` FROM `settings`");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['metaTags']);
	}
}
function validUrl($url) 
{
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && (preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i", $url));
	if ($validation)
		return true; 
	else
		return false;
}
function sendEmail($from,$to,$subject,$body) 
{	
	$mail = new SimpleMail();
    $mail->setTo($to, "");
    $mail->setSubject($subject);
    $mail ->setFrom($from,getTitle());
	$mail->addMailHeader('Reply-To', $from,"");	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
    $mail->setMessage($body);
    $mail->setWrap(100);
	$send = $mail->send();
}
function getMetaTitle() 
{
	$array = mysql_fetch_array(mysqlQuery("SELECT `title` FROM `settings`"));	
	if ($array['title'] != "")
		return $array['title'];  
	return "Affiliate Portal";
}
function getUser() 
{
	$array = mysql_fetch_array(mysqlQuery("SELECT `username` FROM `settings`"));	
	if ($array['username'])
		return $array['username'];
}
function sendSimpleEmail($to, $from, $name, $subject, $body) 
{
	$admin = getUser();	
	$mail = new SimpleMail();
    $mail->setTo($to, 'Admin');
    $mail->setSubject($subject);
    $mail ->setFrom($from,$name);
	$mail->addMailHeader('Reply-To', $from, $name);	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
    $mail->setMessage("<html><body><p face='Georgia, Times' color='red'><p>Hello! <b>" . ucwords($admin) . "</b>,</p> <p>" . $body . "</p><br /><br /><p>Sent Via <a href='" . rootPath() . "'>" . getMetaTitle() . "</a></p>");
    $mail->setWrap(100);
	$send = $mail->send();
}
function genPassword() 
{
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	srand((double) microtime() * 1000000);
	$i    = 0;
	$pass = '';
	while ($i <= 8) 
	{
		$num  = rand() % 33;
		$tmp  = substr($chars, $num, 1);
		$pass = $pass . $tmp;
		$i++;
	}
	return $pass;
}
function isAlpha($val) 
{
	return (bool) preg_match("/^([0-9a-zA-Z ])+$/i", $val);
}
function validName($name) 
{
return (bool) preg_match("/^([a-zA-Z ])+$/i", $name);
}
function checkEmail($email) 
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}
function addProduct($cid,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating) 
{
	if($title != "" && $image != "" && $price != "")
	{
		$cache = phpFastCache();
		if($title=="")
			return "notfound";
		if (!valid_domain($url))
			return "not";
		$domain = getdomain($url);
		if($domain=="graphicriver.net")
			$demo = "";
		$permalink = genPermalink($title); 
		$qry       = mysqlQuery("SELECT `id` FROM `products` WHERE `url`='".trim($url)."'");
		$numRows  = mysql_num_rows($qry);
		if ($numRows > 0) 
		{
			return "skip";
		}
		mysqlQuery("INSERT INTO products(`cid`,`publishDate`,`updateDate`,`permalink`,`title`,`description`,`screens`,`image`,`url`,`demo`,`price`,`tags`,`rating`) values('$cid','$publishDate','$updateDate','$permalink','$title','$description','$screens','$image','$url','$demo','$price','$tags','$rating')");  
		$rows=mysql_num_rows(mysqlQuery("SELECT * FROM `products`"));
		$cache->set('total',$rows);
		$rec=mysql_fetch_array(mysqlQuery("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='$cid'"));
		$parentid=$rec['parentId'];
		$rec3=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`='$parentid'"));
		$pPermalink=$rec3['permalink'];
		$permalink=$rec['permalink'];
		clearCategorycache($permalink,$parentid,$pPermalink);
		clearRecentCache();
		clearTagsCache($tags);
		return "added";
	}
}
function clearTagsCache($tags)
{
	$cache = phpFastCache();
	$alltags= explode("," , $tags);
	foreach($alltags as $tag)
	{
		$tag_name = trim($tag);
		$cache->delete("tag_".$tag_name."idDESC1");
		$cache->delete("tag_".$tag_name."priceDESC1");
		$cache->delete("tag_".$tag_name."clicksDESC1");
		$cache->delete("tag_".$tag_name."idASC1");
		$cache->delete("tag_".$tag_name."priceASC1");
		$cache->delete("tag_".$tag_name."clicksASC1");
	}
}
function clearTopCache()
{
	$cache = phpFastCache();
	$cache->delete("top_today1");
	$cache->delete("top_weekly1");
	$cache->delete("top_monthly1");
	$cache->delete("top_alltime1");
}
function clearCategorycache($category,$pid,$pCategory)
{
	$cache = phpFastCache();
	$cache->delete("$category"."_idDESC1");
	$cache->delete("$category"."_priceDESC1");
	$cache->delete("$category"."_clicksDESC1");
	$cache->delete("$category"."_idASC1");
	$cache->delete("$category"."_priceASC1");
	$cache->delete("$category"."_clicksASC1");
	
	$cache->delete("$pCategory"."_".$pid."idDESC1");
	$cache->delete("$pCategory"."_".$pid."priceDESC1");
	$cache->delete("$pCategory"."_".$pid."clicksDESC1");
	$cache->delete("$pCategory"."_".$pid."idASC1");
	$cache->delete("$pCategory"."_".$pid."priceASC1");
	$cache->delete("$pCategory"."_".$pid."clicksASC1");
}
function clearRecentCache()
{
	$cache = phpFastCache();
	$cache->delete("recent_idDESC1");
	$cache->delete("recent_priceDESC1");
	$cache->delete("recent_clicksDESC1");
	$cache->delete("recent_idASC1");
	$cache->delete("recent_priceASC1");
	$cache->delete("recent_clicksASC1");
}
function isValidProduct($permalink) 
{
	if (mysql_num_rows(mysqlQuery("SELECT title FROM `products` WHERE `permalink`='$permalink'")))
		return true;
	else
		return false;
}

function isValidCategory($permalink,$id) 
{
	$id=0;
	$qry = mysqlQuery("SELECT `name` FROM `categories` WHERE permalink='$permalink' AND `id` NOT IN($id)");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
		return true;
	else
		return false;
}
function is_feed($url) 
{
	$rss = simplexml_load_file($url);
	foreach ($rss->entry as $item) 
	{
		return true;
	}
	return false;
}
function rss_title($url) 
{
	$rss = simplexml_load_file($url);
	return ($rss->title);
}
function getCategory($id) 
{
	$qry = mysqlQuery("SELECT `name` FROM `categories` WHERE `id`=".$id);
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return (ucfirst($row['name']));
	} 
	else 
	{
		return "";
	}
}
function getCategoryIdFromPermalink($permalink) 
{
	$qry = mysqlQuery("SELECT `id` FROM `categories` WHERE `permalink`='$permalink'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['id']);
	} 
	else 
	{
		return "";
	}
}
function getCategoryNameFromPermalink($permalink) 
{
	$qry = mysqlQuery("SELECT `name` FROM `categories` WHERE `permalink`='$permalink'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['name']);
	} 
	else 
	{
		return "";
	}
}
function getProduct($permalink) 
{
	$qry = mysqlQuery("SELECT `title` FROM `products` WHERE `permalink`='" . $permalink . "'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		$row = mysql_fetch_array($qry);
		return ($row['title']);
	} 
	else 
	{
		return "";
	}
}
function incrementsClicks() 
{
	mysqlQuery("UPDATE `stats` SET `clicks`=`clicks`+1 WHERE `datetime`=CURDATE()");
}
function updateMediaSettings($categoriesLimit,$limitPosts,$enablePossible,$enableSidebar,$indexThumbnail,$sidebarThumbnail,$sidebarProLimit,$possibleThumbnail,$possibleProLimit) 
{
	mysqlQuery("UPDATE `mediaSettings` SET `categoriesLimit`='$categoriesLimit',`limitPosts`='$limitPosts',`enablePossible`='$enablePossible',`enableSidebar`='$enableSidebar',`indexThumbnail`='$indexThumbnail',`sidebarThumbnail`='$sidebarThumbnail',`sidebarProLimit`='$sidebarProLimit',`possibleThumbnail`='$possibleThumbnail',`possibleProLimit`='$possibleProLimit'");
}   
function updateCaptchaSettings($adminForm,$contactForm) 
{
	mysqlQuery("UPDATE `captcha` SET `onOffAdminCaptcha`='$adminForm',`onOffContactCaptcha`='$contactForm'");
}
function updateCommentSettings($disqusStatus,$disqusName) 
{
	mysqlQuery("UPDATE `comments` SET `disqusStatus`='$disqusStatus',`disqusName`='$disqusName'");
}
function incrementHotClicks($permalink) 
{
	$sql  = mysqlQuery("SELECT * FROM `hotProducts` WHERE `permalink`='" . $permalink ."'");
	$rows = mysql_num_rows($sql);
	if ($rows > 0) 
	{
		$fetchData = mysql_fetch_array($sql);
		$todayDate = $fetchData['date'];
		$weekDate = $fetchData['weekUpdateDate'];
		$monthDate = $fetchData['monthUpdateDate'];
		if(strtotime(firstDayOfWeek(date('Y-m-d')))>strtotime($weekDate)) 
		{
			$weekDate = firstDayOfWeek(date('Y-m-d'));
			mysqlQuery("UPDATE `hotProducts` SET `weeklyClicks`='0' WHERE `permalink`='$permalink'");
		}
		if(strtotime(date('Y-m-1'))>strtotime($monthDate)) 
		{
			$monthDate = date('Y-m-1');
			mysqlQuery("UPDATE `hotProducts` SET `monthlyClicks`='0' WHERE `permalink`='$permalink'");
		}
		$weekValue = $fetchData['weeklyClicks']+1;
		$monthValue = $fetchData['monthlyClicks']+1;
		$alltimeValue = $fetchData['alltimeClicks']+1;
		$sqlUpdate = mysqlQuery("UPDATE `hotProducts` SET `alltimeClicks`='$alltimeValue',`monthlyClicks`='$monthValue',`weeklyClicks`='$weekValue',`weekUpdateDate`='$weekDate',`monthUpdateDate`='$monthDate' WHERE `permalink`='" . $permalink . "'");
		todayClicks($todayDate,$permalink);
	} 
	else 
	{
		$sql_insert = mysqlQuery("INSERT INTO `hotProducts`(`permalink`,`todayClicks`,`weeklyClicks`, `monthlyClicks`,`alltimeClicks`,`date`,`weekUpdateDate`,`monthUpdateDate`) VALUES('$permalink','1','1','1','1',CURDATE(),'" . firstDayOfWeek(date('Y-m-d')) . "','" . date('Y-m-1') . "')");
	}
}
function todayClicks($get_date,$permalink) 
{
	$todayDate=date("Y-m-d");
	if($get_date==$todayDate) 
	{
		mysqlQuery("UPDATE `hotProducts` SET `todayClicks`=`todayClicks` +1 WHERE `permalink`='$permalink'");
	} 
	else 
	{
		mysqlQuery("UPDATE `hotProducts` SET `todayClicks`='1',`date`=CURDATE() WHERE `permalink`='$permalink'");
	}
}
function firstDayOfWeek($date)
{
    $day = DateTime::createFromFormat('Y-m-d', $date);
    $day->setISODate((int)$day->format('o'), (int)$day->format('W'), 1);
    return $day->format('Y-m-d');
}
function getProductDescription($permalink) 
{
	$cache = phpFastCache();
	
	if(productCacheEnable())
{
	$row = $cache->get($permalink);
	$productCacheExpireTime = productCacheExpireTime(); 
	if($row == null)
	{	
			$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `permalink`='" .$permalink . "'"));
			$cache->set($permalink, $row, $productCacheExpireTime);
	
	}
}
else
{
		$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `permalink`='" .$permalink . "'"));
}
			$description = strip_tags($row['description']);
			$description = shortDescription(preg_replace('/\s+/', ' ', $description));
			$description = strip_tags($row['description']);
			$description = shortDescription(preg_replace('/\s+/', ' ', $description));
	
	return trim($description);
}
function get_product_thumb($permalink) 
{
	$match    = "SELECT `image` FROM `products` WHERE `permalink`='" . $permalink . "'";
	$qry      = mysqlQuery($match);
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		while ($row = mysql_fetch_array($qry)) 
		{
			$image = $row['image'];
		}
		return $image;
	}
	return "";  
}
function getItemDetails($id, $retries=5) 
{	
	$url="http://marketplace.envato.com/api/edge/item:$id.json";
	$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
	$result = "";
	if (extension_loaded('curl') === true) 
	{
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_USERAGENT, $USER_AGENT);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		curl_close($ch);
		unset($ch);
	} 
	else 
	{
		$options  = array('http' => array('user_agent' => $USER_AGENT, 'timeout' => 5));
		$context  = stream_context_create($options);
		$result = trim(file_get_contents($url,false, $context));
	}
	if (trim($result)=="") 
	{
		$retries-=1;
		if ($retries >= 1) 
		{
			return getItemDetails($id, $retries);
		}  
	}
	$data = json_decode($result,true);
	return $data;
}
function addSource($website,$websiteCat,$cid) 
{
	$qry = mysqlQuery("SELECT `id` FROM `envatoSources` WHERE `websiteCategory`='$websiteCat' AND `websiteName`='$website'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0) 
	{
		return "skip";
	}
	$title = strtolower($website);
	mysqlQuery("INSERT INTO envatoSources(websiteName,websiteCategory,cid,updated) VALUES('$title','$websiteCat','$cid','" . date('Y-m-d H:i:s') . "')");
	return "added";  
}
function updateCategory($parent, $permalink, $id, $name) 
{
	mysqlQuery("UPDATE categories SET parentId='$parent', permalink='$permalink', name='$name' WHERE id=".$id);
}
function updateProduct($val,$publishDate,$updateDate,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating,$permalink,$cat) 
{
	if($title != "" && $image != "" && $price!= "")
	{
		$cache = phpFastCache();
		mysqlQuery("UPDATE `products` SET `publishDate`='$publishDate',`updateDate`='$updateDate', `title`='$title',`description`='$description', `screens`='$screens',`image`='$image',`url`='$url',`demo`='$demo',`price`='$price',`tags`='$tags',`rating`='$rating' WHERE `id`=".$val);
		$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `permalink`='".$permalink."'"));
		$cache->set($permalink, $row);
		$rec=mysql_fetch_array(mysqlQuery("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='$cat'"));
		$parentid=$rec['parentId'];
		$rec3=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`='$parentid'"));
		$pPermalink=$rec3['permalink'];
		$permalink=$rec['permalink'];
		clearCategorycache($permalink,$parentid,$pPermalink);
		$gettags=mysql_fetch_array(mysqlQuery("SELECT `tags` FROM `products` WHERE id='$val'"));
		$tags=$gettags['tags'];
		clearTagsCache($tags);
		clearRecentCache();
		clearTopCache();
	}	
}
function updateProductDb($id,$cat,$title,$description,$screens,$image,$url,$demo,$price,$tags,$rating) {
	$cache = phpFastCache();
	$gettags=mysql_fetch_array(mysqlQuery("SELECT `tags` FROM `products` WHERE id='$id'"));
	$tagss=$gettags['tags'];
	clearTagsCache($tagss);
	mysqlQuery("UPDATE `products` SET `cid`='$cat',`title`='$title',`description`='$description', `screens`='$screens',`image`='$image',`url`='$url',`demo`='$demo',`price`=$price,`tags`='$tags',`rating`='$rating' WHERE `id`=".$id);
	$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `id`='".$id."'"));
	$cache->set($row['permalink'],$row);
	$rec=mysql_fetch_array(mysqlQuery("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='$cat'"));
	$parentid=$rec['parentId'];
	$rec3=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`='$parentid'"));
	$pPermalink=$rec3['permalink'];
	$permalink=$rec['permalink'];
	clearCategorycache($permalink,$parentid,$pPermalink);
	clearRecentCache();
	clearTopCache();
}
function root_cat() {
	$qry   = mysqlQuery("SELECT `name` FROM `categories` WHERE `id`='1'");
	$row   = mysql_fetch_array($qry);
	return $row['name'];
}
function deleteCategory($id) 
{
	mysqlQuery("DELETE FROM `categories` WHERE `id`=".$id);
	$updateProducts = "UPDATE `products` SET `cid`='1' WHERE `cid`=".$id;
	mysqlQuery($updateProducts);
}
function categoryExists($id) 
{
	$qry = mysqlQuery("SELECT `name` FROM `categories` WHERE `id`='$id'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
		return true;
	else
		return false;
}
function isCategoryExist($permalink) 
{
	$qry = mysqlQuery("SELECT * FROM `categories` WHERE `permalink` ='$permalink'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
		return true;
	else
		return false;
}
function category_name_exists($name) 
{
	$qry = mysqlQuery("SELECT `id` FROM `categories` WHERE `name` ='".$name."'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
		return true;
	else
		return false;
}
function emailExists($val)
 {
	$qry = mysqlQuery("SELECT `username` FROM `settings` WHERE `email` ='".$val."'");
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
		return true;
	else
		return false;
}
function valid_domain($url) 
{
	$list = array(
		"themeforest",
		"codecanyon",
		"activeden",
		"videohive",
		"graphicriver",
		"audiojungle",
		"3docean",
		"photodune"
	);
	foreach ($list as $list_word) {
		if (preg_match('/' . $list_word . '/', $url))
			return true;
	}
	return false;
}
function updateRssSettings($enable, $limit, $desc, $cat, $tag,$top,$recent) 
{
	mysqlQuery("UPDATE rssSettings SET rssEnable='" . mysql_real_escape_string($enable) . "',limitRss='" . mysql_real_escape_string($limit) . "',descLength='" . mysql_real_escape_string($desc) . "',catRssEnable='" . mysql_real_escape_string($cat) . "',tagRssEnable='" . mysql_real_escape_string($tag) . "',topRssEnable='" . mysql_real_escape_string($top)."',recentRssEnable='" . mysql_real_escape_string($recent)."'");   
}
function onOffAdminCaptcha() 
{
	$qry   = mysqlQuery("SELECT `onOffAdminCaptcha` FROM `captcha`");
	$array = mysql_fetch_array($qry);
	return $array['onOffAdminCaptcha'];
}
function onoffContactCaptcha() 
{
	$qry   = mysqlQuery("SELECT `onOffContactCaptcha` FROM `captcha`");
	$array = mysql_fetch_array($qry);
	return $array['onOffContactCaptcha'];
}
function onoffDisqus() 
{
	$qry   = mysqlQuery("SELECT `disqusStatus` FROM `comments`");
	$array = mysql_fetch_array($qry);
	return $array['disqusStatus'];
}
function rssEnable() 
{
	$qry   = mysqlQuery("SELECT `rssEnable` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['rssEnable'];
}
function enablePossible() 
{
	$qry   = mysqlQuery("SELECT `enablePossible` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['enablePossible'];
}
function enableSidebar() 
{
	$qry   = mysqlQuery("SELECT `enableSidebar` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['enableSidebar'];
}
function updateComments($enableComments) 
{
	$qry   = mysqlQuery("UPDATE  `mediaSettings` SET `enableComments`=$enableComments");
}
function rssCatEnable() 
{
	$qry   = mysqlQuery("SELECT `catRssEnable` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['catRssEnable'];
}
function rssTagEnable() 
{
	$qry   = mysqlQuery("SELECT `tagRssEnable` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['tagRssEnable'];
}
function rssTopEnable() 
{
	$qry   = mysqlQuery("SELECT `topRssEnable` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['topRssEnable'];
}
function rssRecentEnable() 
{
	$qry   = mysqlQuery("SELECT `recentRssEnable` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['recentRssEnable'];
}
function rssDescription()
{
	$qry   = mysqlQuery("SELECT `descLength` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['descLength'];
}
function limitPosts() 
{
	$qry   = mysqlQuery("SELECT `limitPosts` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['limitPosts'];
}
function indexThumbnail() 
{
	$qry   = mysqlQuery("SELECT `indexThumbnail` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['indexThumbnail'];
}
function sidebarThumbnail() 
{
	$qry   = mysqlQuery("SELECT `sidebarThumbnail` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['sidebarThumbnail'];
}
function possibleThumbnail() 
{
	$qry   = mysqlQuery("SELECT `possibleThumbnail` FROM `mediaSettings`");
	$array = mysql_fetch_array($qry);
	return $array['possibleThumbnail'];
}
function rssLimit() 
{
	$qry   = mysqlQuery("SELECT `limitRss` FROM `rssSettings`");
	$array = mysql_fetch_array($qry);
	return $array['limitRss'];
}
function incrementUniqueHits() 
{
	$sql  = mysqlQuery("SELECT `uniqueHits` FROM `stats` WHERE `datetime`=CURDATE()");
	$rows = mysql_num_rows($sql);
	if ($rows > 0) 
	{
		$sqlUpdate = mysqlQuery("UPDATE `stats` SET `uniqueHits`=`uniqueHits`+1 WHERE `datetime`=CURDATE()");
	} 
	else 
	{
		$sql_insert = mysqlQuery("INSERT INTO `stats`( `uniqueHits`, `datetime`) VALUES('1',CURDATE())");
	}
}
function incrementPageViews() 
{
	$sql  = mysqlQuery("SELECT `pageViews` FROM `stats` WHERE `datetime`=CURDATE()");
	$rows = mysql_num_rows($sql);
	if ($rows > 0) 
	{
		$sqlUpdate = mysqlQuery("UPDATE `stats` SET `pageViews`=`pageViews`+1 WHERE `datetime`=CURDATE()");
	} 
	else 
	{
		$sql_insert = mysqlQuery("INSERT INTO `stats`(`pageViews`,`datetime`) VALUES('1',CURDATE())");
	}
}
function incrementProductPageViews($permalink) 
{
	mysqlQuery("UPDATE `products` SET `pageViews`=`pageViews`+1 WHERE `permalink`='$permalink'");
}
function incrementProductuniqHits($permalink) 
{
	mysqlQuery("UPDATE `products` SET `uniqHits`=`uniqHits`+1 WHERE `permalink`='$permalink'");
}
function incrementProductClicks($permalink) 
{
	mysqlQuery("UPDATE `products` SET `clicks`=`clicks`+1 WHERE `permalink`='$permalink'");
}
function showMedrec1Ad() 
{
	$sql = mysqlQuery("SELECT `rec1Status` FROM `ads`");
	$fetch = mysql_fetch_array($sql);
	if ($fetch['rec1Status']) 
	{
		$qry_ad   = mysqlQuery("SELECT `medRec1` FROM `ads`");
		$row_ad   = mysql_fetch_array($qry_ad);
		$code     = str_replace("<q>", "'", $row_ad["medRec1"]);
		$code     = htmlspecialchars_decode($code);
		return ($code);
	}
	return "";
}
function showMedRec2Ad() 
{
	$sql   = mysqlQuery("SELECT `rec2Status` FROM `ads`");
	$fetch = mysql_fetch_array($sql);
	if ($fetch['rec2Status']) {
		$qry_ad   = mysqlQuery("SELECT `medRec2` FROM `ads`");
		$row_ad   = mysql_fetch_array($qry_ad);
		$code     = str_replace("<q>", "'", $row_ad["medRec2"]);
		$code     = htmlspecialchars_decode($code);
		return ($code);
	}
	return "";
}
function showMedRec3Ad() 
{
	$sql   = mysqlQuery("SELECT `rec3Status` FROM `ads`");
	$fetch = mysql_fetch_Array($sql);
	if ($fetch['rec3Status']) 
	{
		$qry_ad   = mysqlQuery("SELECT `medRec3` FROM `ads`");
		$row_ad   = mysql_fetch_array($qry_ad);
		$code     = str_replace("<q>", "'", $row_ad["medRec3"]);
		$code     = htmlspecialchars_decode($code);
		return ($code);
	}
	return "";
}
function listPages() 
{
	$query = mysqlQuery("SELECT `permalink`,`title` FROM `pages` WHERE `status`=1 ORDER BY `displayOrder`");
	while ($fetch = mysql_fetch_array($query)) 
	{
		$strpages .= '<a href="' . rootpath() . '/page/' . $fetch['permalink'] . '">' . $fetch['title'] . '</a>';
	}
	return $strpages;
}
function dbDecode($str) 
{
	$str = trim(str_replace("<q>", "'", $str));
	$str = htmlspecialchars_decode($str);
	return $str;
}
function validFileExtension($ext) 
{
	$allowedExts = array(
		"gif",
		"jpeg",
		"jpg",
		"png"
	);
	if (!in_array($ext, $allowedExts)) 
	{
		return false;
	}
	return true;
}
function getLogo() 
{
	$query = mysqlQuery("SELECT `logo` FROM `settings`");
	$fetch = mysql_fetch_array($query);
	return $fetch['logo'];
}
function validFaviconExtension($ext) 
{
	$allowedExts = array(
		"ico",
		"png"
	);
	if (!in_array($ext, $allowedExts)) 
	{
		return false;
	}
	return true;
}
function getFavicon() 
{
	$query = mysqlQuery("SELECT `favicon` FROM `settings`");
	$fetch = mysql_fetch_array($query);
	return $fetch['favicon'];
}
function countProducts($id)
{
	$query = mysqlQuery("SELECT count(id) AS total FROM `products` WHERE `cid`=$id");
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function countAllProducts($parent_id)
{
	$sql = "SELECT count(id) AS total FROM `products` WHERE cid IN(SELECT id FROM `categories` WHERE parentId='$parent_id' OR `id`='$parent_id')";
	$query = mysqlQuery($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function gatProductTags($permalink) 
{
	$query = mysqlQuery("SELECT tags FROM products where permalink='$permalink'");
	$count_rows = mysql_num_rows($query);
	if($count_rows>0) 
	{
		while($row = mysql_fetch_array($query))
		{
			return $row['tags'];
		}
	}
}
function truncateDescription($description) 
{
	if (strlen($description) > 100)
	{
		$stringCut = substr($description, 0, 100);
		$description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
	}
	return $description;
}
function getCategoryIdByPermalink($permalink) 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `cid` FROM `products` WHERE permalink='$permalink'"));
	return $row['cid'];
}
function deleteProduct($id) 
{
	if(validProduct($id))
	{
		$cache = phpFastCache();
		$query=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `products` WHERE `id`=".$id));
		mysqlQuery("DELETE FROM `hotProducts` WHERE `permalink`='".$query['permalink']."'");
		$permalink = $query['permalink'];
		$cache->delete($permalink);
		$cache->delete("sidebar_$permalink");
		$cache->delete("related_$permalink");
		$rows=mysql_num_rows(mysqlQuery("SELECT * FROM `products`"));
		$cache->set('total',$rows);
		$rec=mysql_fetch_array(mysqlQuery("SELECT `cid` FROM `products` WHERE `id`='$id'"));
		$cid=$rec['cid'];
		$rec2=mysql_fetch_array(mysqlQuery("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='$cid'"));
		$parentid=$rec2['parentId'];
		$rec3=mysql_fetch_array(mysqlQuery("SELECT `permalink` FROM `categories` WHERE `id`='$parentid'"));
		$pPermalink=$rec3['permalink'];
		$permalink=$rec2['permalink'];
		clearCategorycache($permalink,$parentid,$pPermalink);
		$gettags=mysql_fetch_array(mysqlQuery("SELECT `tags` FROM `products` WHERE id='$id'"));
		$tags=$gettags['tags'];
		clearTagsCache($tags);
		clearRecentCache();
		clearTopCache();
		mysqlQuery("DELETE FROM `products` WHERE `id`=".$id);
	}
}
function validProduct($id)
{
	$numRows = mysql_num_rows(mysqlQuery("SELECT * FROM `products` WHERE `id` = '$id'"));
	if($numRows > 0)
	{
		return true;
	}
	return false;
}
function getPermalinkById($id) 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `permalink`  FROM `products` WHERE `id`='$id'"));
	return $row['permalink'];
}
function sidebarProLimit() 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `sidebarProLimit`  FROM `mediaSettings`"));
	return $row['sidebarProLimit'];
}
function PossibleProLimit() 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `possibleProLimit`  FROM `mediaSettings`"));
	return $row['possibleProLimit'];
}
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
function getCatNameByPermalink($CategoryPermalink) 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT name FROM `categories` WHERE permalink='$CategoryPermalink'"));
	return $row['name'];
}
function showParentCategories($selectedCategoryPermalink) 
{
	$query=mysqlQuery("SELECT parentId,name,permalink FROM `categories` WHERE  parentId='0' AND permalink!='$selectedCategoryPermalink' ORDER BY `name` ASC");
	if($selectedCategoryPermalink=='all') 
	{
		?>
		<option value="all">All Categories</option>
		<?php
		while($row=mysql_fetch_array($query)) 
		{
			?>
			<option value="<?php echo $row['permalink']?>"><?php echo $row['name']?></option>
			<?php
		}
	}
	else 
	{
		?>
		<option value="<?php echo $selectedCategoryPermalink?>"><?php echo getCatNameByPermalink($selectedCategoryPermalink)?></option>
		<?php
		while($row=mysql_fetch_array($query)) 
		{
			?>
			<option value="<?php echo $row['permalink']?>"><?php echo $row['name']?></option>
			<?php
		}
		?>
		<option value="all">All Categories</option>
		<?php
	}
}
function getWebDate() 
{
	$result=mysql_fetch_array(mysqlQuery("SELECT * FROM `settings`"));
	return $result;
}
function getPageData($permalink) 
{
	$result=mysql_fetch_array(mysqlQuery("SELECT * FROM `pages` WHERE `permalink`='$permalink'"));
	return $result;
}
function getImgBypermalink($permalink) 
{
	$result=mysql_fetch_array(mysqlQuery("SELECT `image` FROM `products` WHERE permalink='$permalink'"));
	return $result['image'];
}
function disqusName() 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `disqusName` FROM `comments`"));
	return $row['disqusName'];
}
function disqusStatus() 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT `disqusStatus` FROM `comments`"));
	return $row['disqusStatus'];
}
function getAdsData() 
{
	$row=mysql_fetch_array(mysqlQuery("SELECT * FROM `ads`"));
	return $row;
}
function getAd1($adCode) 
{ 
	?>
	<div class="col-lg-4 col-sm-6 col-xs-6 thumb-height ad-rect hidden-xs hidden-sm">
		<div class="preview ad1">
			<div class="detail">	  
				<div class="ad-mrg-btm">
					<?php echo ($adCode) ?> 
				</div>
				<div class="info">
					<div class="btn-group">
						<a class="btn btn-small btn-info btn-disable"  target="_blank"><i class="fa fa-desktop"></i> Ad</a>				
					</div>	
					<div class="btn-group">
						<a class="btn btn-small btn-success btn-disable"  target="_blank"><i class="fa fa-puzzle-piece"></i> Sponsor Ad</a>				
					</div>											
				</div>
			</div>
		</div>	    
	</div>
	<?php 
}
function page404($lang_array) 
{
	?>
	<div class="clearfix"></div>
	<ol class="breadcrumb">
		<li>
			<a href="<?php echo(rootpath()) ?>/index.php"><?php echo($lang_array['breadcrumb_text_home']) ?></a>
		</li>
		<li class="active"><?php echo($lang_array['breadcrumb_404_text']) ?></li>
	</ol>
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><b><?php echo($lang_array['404_title_text']) ?></b></h3>
					</div>
					<div class="panel-body">
						<div class="error-template">
							<h1>
								<?php echo($lang_array['404_first_line_text']) ?>
							</h1>
							<h2>
								<?php echo($lang_array['404_second_line_text']) ?>
							</h2>
							<div class="error-details">
								<?php echo($lang_array['404_third_line_text'] )?>
							</div>
							<div class="error-actions" style="margin-top:18px;">
								<div class="btn-group">
									<a href="<?php echo(rootpath()) ?>/index.php" class="btn btn-primary btn-lg"><i class="fa fa-home"></i> <?php echo($lang_array['404_home_button_line_text']) ?> </a>
								</div>
								<div class="btn-group">
									<a href="<?php echo(rootpath()) ?>/contact/" class="btn btn-default btn-lg"><i class="fa fa-envelope"></i> <?php echo($lang_array['404_support_button_line_text']) ?> </a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require 'includes/footer.php';
}
function isSearchProduct($permalink) 
{
	$permalink = trim($permalink);
	$result = mysqlQuery("SELECT * FROM `products` WHERE `permalink`='$permalink'");
	$rows = mysql_num_rows($result);
	if($rows > 0) 
	{
		header("Location:" . rootpath() . '/product/' . $permalink . '.html');
	}
}
function cmp($a, $b)
{
	if (str_word_count($a) == str_word_count($b)) {
		return 0;
	}
	return (str_word_count($a) < str_word_count($b)) ? -1 : 1;
}
function getUpdateablePages($currentPage,$lastPage) 
{
	if ($currentPage > 1) 
	{
		$i = ((int) $currentPage / 5) * 5 - 1;
		if (!($i + 5 > $lastPage)) 
		{
			$tempLastPage = $i + 5;
		} 
		else 
		{
			if (($lastPage - 5) > 1)
				$i = $lastPage - 5;
			else
				$i = 1;
			$tempLastPage = $lastPage;
		}
	
	} 
	else 
	{
		$i = 1;		
		if (!($i + 5 > $lastPage))
			$tempLastPage = 5;
		else
			$tempLastPage = $lastPage;
	}
	$list = "";
	for ($i; $i <= $tempLastPage; $i++) 
	{	
		if ($i != $currentPage)
			$list .="$i,";
	}
	$list .= $lastPage;
	if($currentPage!=1)
		return "1,".$list;
	else
		return $list;
}
function getParentCategoryId($permalink) {
	$row=mysql_fetch_array(mysqlQuery("SELECT `id` FROM `categories` WHERE `permalink`='$permalink'"));
	return $row['id'];
}
function isParentCategory($permalink)
{
	$count=mysql_num_rows(mysqlQuery("SELECT * FROM `categories` WHERE `permalink`='$permalink' AND parentId='0'"));
	if($count > 0)
		return true;
	else
		return false;
}
function getWeekUpdateDate(){
	$fetch=mysql_fetch_array(mysqlQuery("SELECT * FROM `hotProducts` WHERE `weekUpdateDate` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 6 DAY ) AND CURDATE( ) ORDER BY `weekUpdateDate` DESC LIMIT 1"));	
	return $fetch['weekUpdateDate'];
}
function getMonthUpdateDate(){
	$fetch=mysql_fetch_array(mysqlQuery("SELECT * FROM `hotProducts` WHERE `monthUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE( ) ORDER BY `monthUpdateDate` DESC LIMIT 1"));
	return $fetch['monthUpdateDate'];
}
function isValidCategoryPermalink($permalink) {
	$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink'"));
	if($count>0)		
		return true;
	else
		return false;
}
function categoryNameByPermalink($permalink) {
	$row=mysql_fetch_array(mysql_query("SELECT `name` FROM `categories` WHERE `permalink`='$permalink'"));
	return $row['name'];
}
function isValidPage($permalink) {
	$count=mysql_num_rows(mysql_query("SELECT * FROM `pages` WHERE `permalink`='$permalink'"));
	if($count>0)
		return true;
	else
		return false;
}
function pageTitleByPermalink($permalink) {
	$row=mysql_fetch_array(mysql_query("SELECT `title` FROM `pages` WHERE `permalink`='$permalink'"));
	return $row['title'];
}  

function recentCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `recentCacheEnable` from `cacheSettings`"));
	return $row['recentCacheEnable'];
}
function recentCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `recentCacheExpireTime` from `cacheSettings`"));
	return $row['recentCacheExpireTime'];
}

function categoryCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `categoryCacheEnable` from `cacheSettings`"));
	return $row['categoryCacheEnable'];
}
function categoryCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `categoryCacheExpireTime` from `cacheSettings`"));
	return $row['categoryCacheExpireTime'];
}

function tagCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `tagCacheEnable` from `cacheSettings`"));
	return $row['tagCacheEnable'];
}
function tagCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `tagCacheExpireTime` from `cacheSettings`"));
	return $row['tagCacheExpireTime'];
}

function topCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `topCacheEnable` from `cacheSettings`"));
	return $row['topCacheEnable'];
}
function topCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `topCacheExpireTime` from `cacheSettings`"));
	return $row['topCacheExpireTime'];
}

function productCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `productCacheEnable` from `cacheSettings`"));
	return $row['productCacheEnable'];
}
function productCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `productCacheExpireTime` from `cacheSettings`"));
	return $row['productCacheExpireTime'];
}

function sidebarCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `sidebarCacheEnable` from `cacheSettings`"));
	return $row['sidebarCacheEnable'];
}
function sidebarCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `sidebarCacheExpireTime` from `cacheSettings`"));
	return $row['sidebarCacheExpireTime'];
}

function relatedCacheEnable() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `relatedCacheEnable` from `cacheSettings`"));
	return $row['relatedCacheEnable'];
}
function relatedCacheExpireTime() {
	$row = mysql_fetch_array(mysqlQuery("SELECT `relatedCacheExpireTime` from `cacheSettings`"));
	return $row['relatedCacheExpireTime'];
}

function updateCacheSettings($recentCacheEnable, $recentCacheExpireTime, $categoryCacheEnable, $categoryCacheExpireTime, $tagCacheEnable, $tagCacheExpireTime, $topCacheEnable, $topCacheExpireTime, $productCacheEnable, $productCacheExpireTime, $sidebarCacheEnable, $sidebarCacheExpireTime, $relatedCacheEnable, $relatedCacheExpireTime) {
	
	$rows = mysql_num_rows(mysqlQuery("SELECT * FROM `cacheSettings`"));
	
	if($rows>0) {
		
		mysqlQuery("UPDATE `cacheSettings` SET `recentCacheEnable`='$recentCacheEnable' ,`recentCacheExpireTime`='$recentCacheExpireTime',`categoryCacheEnable`='$categoryCacheEnable' ,`categoryCacheExpireTime`='$categoryCacheExpireTime',`tagCacheEnable`='$tagCacheEnable' ,`tagCacheExpireTime`='$tagCacheExpireTime',`topCacheEnable`='$topCacheEnable' ,`topCacheExpireTime`='$topCacheExpireTime',`productCacheEnable`='$productCacheEnable' ,`productCacheExpireTime`='$productCacheExpireTime',`sidebarCacheEnable`='$sidebarCacheEnable' ,`sidebarCacheExpireTime`='$sidebarCacheExpireTime',`relatedCacheEnable`='$relatedCacheEnable' ,`relatedCacheExpireTime`='$relatedCacheExpireTime'");
	
	}
	else {
			
		mysqlQuery("INSERT INTO `cacheSettings` (recentCacheEnable, recentCacheExpireTime, categoryCacheEnable, categoryCacheExpireTime, tagCacheEnable, tagCacheExpireTime, topCacheEnable, topCacheExpireTime, productCacheEnable ,productCacheExpireTime, sidebarCacheEnable, sidebarCacheExpireTime, relatedCacheEnable, relatedCacheExpireTime) VALUES ('$recentCacheEnable', '$recentCacheExpireTime' ,'$categoryCacheEnable', '$categoryCacheExpireTime', '$tagCacheEnable', '$tagCacheExpireTime', '$topCacheEnable', '$topCacheExpireTime', '$productCacheEnable', '$productCacheExpireTime', '$sidebarCacheEnable', '$sidebarCacheExpireTime', '$relatedCacheEnable', '$relatedCacheExpireTime')");
		
	}
}

function socialStatus()
{
	$row = mysql_fetch_array(mysqlQuery("SELECT `status` FROM `socialProfiles`"));
	
	return $row['status'];
}
function categoriesLimit()
{
	$row = mysql_fetch_array(mysqlQuery("SELECT `categoriesLimit` FROM `mediaSettings`"));
	
	return $row['categoriesLimit'];
}
?>