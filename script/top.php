<?php
defined("APP") or die();
if(!isset($_SESSION)) 
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
$cache = phpFastCache();
$error = "";
$qs = "";
$Type='top';
$adsdata=getAdsData();
$Page=mres($this->Page);
if (isset($this->sortBy)) 
{
	if (trim($this->sortBy) == "today" || trim($this->sortBy) == "weekly" || trim($this->sortBy) == "monthly" || trim($this->sortBy) == "alltime") 
	{
		if (trim($this->sortBy) == "today") {
			$_SESSION['sorTBy'] = "today";
		} 
		else if(trim($this->sortBy) == "weekly") {
			$_SESSION['sorTBy'] = "weekly";
		} 
		else if (trim($this->sortBy) == "monthly") {
			$_SESSION['sorTBy'] = "monthly";
		}
		else if (trim($this->sortBy) == "alltime") {
			$_SESSION['sorTBy'] = "alltime";
		} 
		else {
			$_SESSION['sorTBy'] = "weekly";
		}
	}
}
if (!isset($_SESSION['sorTBy'])) {
	$_SESSION['sorTBy'] = "weekly";
}
$page=1;
$limit=limitPosts();
$next=2;
$prev=1;
if($_SESSION['sorTBy']=='today') {
	$data = mysqlQuery("SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`todayClicks` >0 AND `date`=CURDATE()");
} 
else if($_SESSION['sorTBy']=='weekly') {
	$data = mysqlQuery("SELECT p.*,h.weeklyClicks FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`weeklyClicks` >0 AND h.`weekUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)");
} 
else if($_SESSION['sorTBy']=="monthly") {
	$data = mysqlQuery("SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`monthlyClicks` >0 AND h.`monthUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
} 
else if($_SESSION['sorTBy']=="alltime") {
	$data = mysqlQuery("SELECT p.* FROM `products` p ,`hotProducts` h WHERE p.permalink=h.permalink AND h.`alltimeClicks` >0");
} 
else {
	$data = mysqlQuery("SELECT p.*,h.weeklyClicks FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`weeklyClicks` >0 AND h.`weekUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)");
} 
$rows = mysql_num_rows($data);
$last = ceil($rows/$limit);
if(isset($Page) && $Page!='' && ($Page>=1 && $Page<=$last))
{
	$page=$Page;
	if($page>1) 
		$prev=$page-1;
	else
		$prev=$page;
	if($page<$last)
		$next=$page+1;
	else
		$next=$page;
}
$webdata=getWebDate();
require 'includes/header.php';
?>

	<title><?php echo $lang_array['top_products_page_title'].'- Page '.$page?> </title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="<?php echo $lang_array['top_products_page_title'].'- Page '.$page?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/top'?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />

<?php require 'includes/header_under.php'; ?>
<div class="clearfix"></div>
<ol class="breadcrumb">
	<li>
		<a href="<?php echo rootpath().'/index.php' ?>"><?php echo $lang_array['breadcrumb_text_home'];?> </a>
	</li>
	<li><?php echo $lang_array['top_products'];?></li>
	<div class="btn-group pull-right"> 
			<select class="btn btn-default option-btn btn-sm dropdown-toggle sort-by-btn" <?php echo ($Page ? 'onchange="sortByWithPageNO(this.value)"' : 'onchange="sortByWithOutPageNO(this.value)"') ?>>
			
			<option value="today" <?php echo ($_SESSION['sorTBy'] == 'today' ? 'selected' : "") ?>><?php echo $lang_array['today']; ?></option>
			
			<option value="weekly" <?php echo ($_SESSION['sorTBy'] == 'weekly' ? 'selected' : "") ?>><?php echo $lang_array['weekly']; ?></option>
			
			<option value="monthly" <?php echo ($_SESSION['sorTBy'] == 'monthly' ? 'selected' : "") ?>><?php echo $lang_array['monthly']; ?></option>
			
			<option value="alltime" <?php echo ($_SESSION['sorTBy'] == 'alltime' ? 'selected' : "") ?>><?php echo $lang_array['alltime']; ?></option>
		</select>
	</div>
</ol>
<?php
if($adsdata['rec2Status']) 
{ 
	?>
	<div class="col-md-12 hidden-ads" style="text-align: center;margin: 15px 0">
	<?php echo ($adsdata['medRec2']) ?>
	</div>
	<?php 
} 
?>
<div class='panel-body related-products1'>
<div class='row'>
<?php
$start_result = ($page-1)*$limit;
if($_SESSION['sorTBy']=='today')
{
	$match = "SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`todayClicks` >0 AND `date`=CURDATE() ORDER BY h.todayClicks DESC LIMIT $start_result,$limit";
} 
else if($_SESSION['sorTBy']=='weekly')
{
	 $match = "SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`weeklyClicks` >0 AND h.`weekUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) ORDER BY h.weeklyClicks DESC LIMIT $start_result,$limit";
} 
else if($_SESSION['sorTBy']=='monthly')
{
	$match = "SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`monthlyClicks` >0 AND h.`monthUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ORDER BY h.monthlyClicks DESC LIMIT $start_result,$limit";
}
else if($_SESSION['sorTBy']=='alltime')
{
	$match = "SELECT p.* FROM `products` p ,`hotProducts` h WHERE p.permalink=h.permalink AND h.`alltimeClicks` >0 ORDER BY h.alltimeClicks DESC LIMIT $start_result,$limit";
}
else
{
	 $match = "SELECT p.* FROM `products` p,`hotProducts` h WHERE p.permalink=h.permalink AND h.`weeklyClicks` >0 AND h.`weekUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) ORDER BY h.weeklyClicks DESC LIMIT $start_result,$limit";
} 
if(topCacheEnable())
{
	$sortBy=$_SESSION['sorTBy'];
	$var = "top_".$sortBy.$page;
	$topCacheExpireTime = topCacheExpireTime(); 
	$data=$cache->get($var);
	if($data == null)  
	{   
		$data = array(); 
		$qry = mysqlQuery($match);
		while($row = mysql_fetch_array($qry))
		{
			$data[] = $row;
		}
		$cache->set($var,$data, $topCacheExpireTime);
	}
}
else
{
	$data = array();
	$qry = mysqlQuery($match);
	while($row = mysql_fetch_array($qry))
	{
		$data[] = $row;
	}
}
if (count($data) > 0)
{
	foreach($data as $row)
	{
		$id=$row['id'];
		$urlScreens = $row['screens'];
		if(strpos($urlScreens, '?') !== FALSE)
			$urlScreens .= "&ref=" . getEnvatoUsername();
		else
			$urlScreens .= "?ref=" . getEnvatoUsername();
		$url = $row['url'];
		if(strpos($url, '?') !== FALSE)
			$url .= "&ref=" . getEnvatoUsername();
		else
			$url .= "?ref=" . getEnvatoUsername();
		$demoUrl = $row['demo'];
		if(strpos($demoUrl, '?') !== FALSE)
			$demoUrl .= "&ref=" . getEnvatoUsername();
		else
			$demoUrl .= "?ref=" . getEnvatoUsername();
		$thumbnail_size  = strtolower(indexThumbnail());
		$pieces = explode('x', $thumbnail_size);
		echo'<div class="col-lg-4 col-sm-6 col-xs-6">
		<div class="preview">
		<div class="image newtest">';
		if(getdomain($url)=='audiojungle.net')  
		{
			echo'<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/' . $row['permalink']. '.html">
					<img class="img-responsive center-block" src="' . rootpath() . '/thumb.php?id=audio&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'"/></a>';
			echo '<div class="audio-playr">';  
			echo' <div class="audio-js-box hu-css">
					<audio class="audio-js" preload="none" controls>
					  <source src="'.$row['image'].'" type="audio/mpeg">
					</audio>
				  </div>';
			echo '</div>';
		}
		else if(getdomain($url)=='videohive.net')  
		{
			echo '<video id="demo1-'.$row['id'].'" class="video-js vjs-default-skin" controls
					 preload="none" width="auto" height="208" data-setup="{}" poster="'.$row['image'].'">
					 <source src="'.$row['demo'].'" type="video/mp4">
					</video>';
		}
		else if(getdomain($url)=='activeden.net')
		{
			$check=explode(".",$row['image']);
			$extension=end($check);
			$extension=trim($extension);
			if($extension=="swf")
			{
				echo '<object type="application/x-shockwave-flash" data="'.$row['image'].'" class="img-responsive center-block">
						<param name="movie" value="'.$row['image'].'" />
						<param name="quality" value="high" />
					</object>';
			}
			else
			{
				echo'<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/' . $row['permalink']. '.html">
				<img class="img-responsive center-block" src="' . rootpath() . '/thumb.php?id=' . $row['id'] . '&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'"/></a>';
			}
		}
		else
		{
			echo'<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/' . $row['permalink']. '.html">
			<img class="img-responsive center-block" src="' . rootpath() . '/thumb.php?id=' . $row['id'] . '&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'"/></a>';
		}
		echo '</div>
		<div class="details">
		<div class="info">
		<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/' . $row['permalink']. '.html">
		<h4>'.trim($row['title']).'</h4></a>';
		$description=trim(strip_tags($row['description']));
		if (strlen($description) > 100)
		{
			$stringCut = substr($description, 0, 100);
			$description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
		}
		echo'<p class="dis_hide">'.$description.' </p>
		</div>'; 	  
		if(getdomain($url)=='videohive.net')
		{
			echo '<div class="btn-group">
			<a class="btn btn-small btn-info demo-purchase-btn" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
			</div>';
			echo'<div id="myModal'.$row['id'].'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
				<div class="modal-dialog mod">
					<div class="modal-content">
						<div class="modal-header modh">
							<button type="button" class="closevideo close modhc" id="'.$row['id'].'" data-dismiss="modal" aria-hidden="true">&times;</button>
						</div>
						<div class="modal-body mbody">    
							<video id="demo-'.$row['id'].'" class="video-js vjs-default-skin" controls
							 preload="none" width="auto" height="400" data-setup="{}" poster="'.$row['image'].'">
							 <source src="'.$row['demo'].'" type="video/mp4">
							</video>
						</div>
					</div>
				</div>
			</div>';
		} 
		else if(getdomain($url)=='activeden.net')
		{
			$check=explode(".",$row['image']);
			$extension=end($check);
			$extension=trim($extension);
			if($extension=="swf")
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
				echo'<div id="myModal'.$row['id'].'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
					<div class="modal-dialog mod">
						<div class="modal-content">
							<div class="modal-header modh">
								<button type="button" class="closevideo close modhc" id="'.$row['id'].'" data-dismiss="modal" aria-hidden="true">&times;</button>
							</div>
							<div class="modal-body mbody">    
								<object type="application/x-shockwave-flash" data="'.$row['image'].'" class="img-responsive center-block">
									<param name="movie" value="'.$row['image'].'" />
									<param name="quality" value="high" />
								</object>
							</div>
						</div>
					</div>
				</div>';
			}
			else
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
			}
		}
		else if(getdomain($url)=='graphicriver.net' || getdomain($url)=='photodune.net')
		{
			if($row['screens']!="")  
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" href="' . $urlScreens . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
			}
			else
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
			}
		}
		else
		{
			if($row['demo']!="")
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" href="' . $demoUrl . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
			}
			else
			{
				echo '<div class="btn-group">
				<a class="btn btn-small btn-info demo-purchase-btn" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;'.$lang_array['demo'].'</a>				
				</div>';
			}
		}
		echo '<div class="btn-group">
		<a class="btn btn-small btn-success demo-purchase-btn purchase" id="'.$row['permalink'].'" href="' . $url . '" target="_blank"><i class="fa fa-shopping-cart"></i> <span class="purchase-txt">'.$lang_array['purchase'].'</span>&nbsp';
		$array = mysql_fetch_array(mysqlQuery("SELECT * FROM currencySettings"));
		$currencySymbol = $array['currencySymbol'];
		$priceInDollar = $array['priceInDollar'];
		$showBefore = $array['showBefore'];
		if($showBefore ==  1)
			echo '('." " . $currencySymbol." ". $row['price'] * $priceInDollar.')'; 
		else 
			echo '('.$row['price'] * $priceInDollar." ". $currencySymbol.')'; 
		echo ('</a>							
		</div>
		</div>
		</div>	    
		</div>
		');
	}
} 
else 
{
	echo ('
	<div class="col-md-12">
	   <div class="row">
		  <div class="col-md-12">
			<div class="error-template">
				<h1>
					Oops!
				</h1>
				<h2>
					<i class="fa fa-times-circle"></i> No Products Found
				</h2>   
			</div>
		  </div>
	   </div>
	</div>');
} 
if($adsdata['rec2Status']) 
{ 
	?>
	<div class="clearfix"></div>
	<div class="col-md-12 hidden-ads" style="text-align: center;margin-bottom: 10px">
		<?php echo $adsdata['medRec2']; ?>
	</div>	
	<?php
}
if($rows > $limit) 
{ 
	?>
	<div class="centered btn-first">
		<ul class="pagination"> 
			<li>
				<a class="btn-size" href="<?php echo(rootpath()) ?>/top"><?php echo $lang_array['first'];?></a>
				<?php $paging.="1,"; ?>
			</li>
			<?php if($page > 1) { ?>
			<li>
				<a class="btn-size" href="<?php echo(rootpath()) ?>/top/<?php echo($prev) ?>">&laquo;</a>
				<?php $paging.="$prev,"; ?>
			</li>
			<?php
			}
			if($page>1 && $last>5)
			{
				$i=((int)$page/5)*5-1;
				if(!($i+5>$last))
				{
					$temp_last = $i+5;
				}
				else
				{
					$i = $last - 5;
					$temp_last = $last;
				}
			}
			else
			{
				$i=1;
				if(!($i+5>$last))
					$temp_last = 5;
				else
					$temp_last = $last;
			}
			for($i;$i<=$temp_last;$i++)
			{ 
				if($i==$page)
					echo('<li class="active"><a class="btn-size" href="' . rootpath() . '/top/' . $i . '">' . $i . '</a></li>');
				else
					echo('<li><a class="btn-size" href="' . rootpath() . '/top/' . $i . '">' . $i . '</a></li>');
					$paging.="$i,";
			}
			if($page !=$last) {
			?>
			<li>
				<a class="btn-size" href="<?php echo(rootpath()) ?>/top/<?php echo($next) ?>">&raquo;</a>
				<?php $paging.="$next," ?>
			</li>
			<?php } ?>
			<li>
				<a class="btn-size" href="<?php echo(rootpath()) ?>/top/<?php echo($last) ?>"><?php echo $lang_array['last'];?></a>
				<?php $paging.="$last" ?>
			</li>
		</ul>
	</div>
	<?php
} 
?>
</div>
</div>

<script>
function sortByWithPageNO(sortBy) {
	window.location="<?php echo (rootpath()); ?>/top/"+sortBy+"/<?php echo trim($Page)?>";
}
function sortByWithOutPageNO(sortBy) {
	window.location="<?php echo (rootpath()); ?>/top/"+sortBy+"";
}
</script>
<?php require 'includes/footer.php'; ?>