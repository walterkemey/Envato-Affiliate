<?php
defined("APP") or die();
if (!isset($_SESSION)) { session_start(); }
require 'config/config.php';
require 'includes/functions.php';
require 'includes/language.php';
require 'includes/header.php';
$Type='category';
$getCategory=xssClean(mres($this->categoryName));
$cache = phpFastCache();
if(isParentCategory($getCategory))
	$Pid=getParentCategoryId($getCategory);

$SortBy=$this->sortBy;
$SortOrder=$this->sortOrder;
if(is_numeric($this->Page))    
	$Page=$this->Page;

$webdata=getWebDate();
$adsdata=getAdsData();
if(!isParentCategory($getCategory))
$id=catPermalinkToId($getCategory);
?>
<title><?php echo (isValidCategoryPermalink($getCategory) ? (categoryNameByPermalink($getCategory) ."- Page ".($Page > 1 ? $Page : '1')) : ('404-Page Not Found :('));?></title>
<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/category/'.$getCategory?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php require 'includes/header_under.php'; 
if (isset($SortOrder)) 
{
	if (trim($SortOrder) == "ASC" || trim($SortOrder) == "DESC") 
	{
		$_SESSION['soRTOrder'] = trim($SortOrder);
	}
}
if (isset($SortBy)) 
{
	if (trim($SortBy) == "id" || trim($SortBy) == "price" || trim($SortBy) == "clicks") 
	{
		if (trim($SortBy) == "id") {
			$_SESSION['soRTBy'] = "id";
		} 
		else if(trim($SortBy) == "price") {
			$_SESSION['soRTBy'] = "price";
		} 
		else if (trim($SortBy) == "clicks") {
			$_SESSION['soRTBy'] = "clicks";
		} 
		else {
		$_SESSION['soRTBy'] = "id";
		}
	}
}
if (!isset($_SESSION['soRTBy'])) {
$_SESSION['soRTBy'] = "id";
}
if (!isset($_SESSION['soRTOrder'])) {
$_SESSION['soRTOrder'] = "DESC";
}
if ($_SESSION['soRTBy'] == "id") {
$sortBy = "id";
} 
else if ($_SESSION['soRTBy'] == "price") {
$sortBy = "price";
} 
else if ($_SESSION['soRTBy'] == "clicks") {
$sortBy = "clicks";
} 
else {
$sortBy = "id";
}
if (isset($getCategory) && isCategoryExist($getCategory)) 
{    
	$permalink=$getCategory;
	$error = "";
	$page=1;
	$limit=limitPosts();
	$next=2;
	$prev=1;
	if(isParentCategory($getCategory))
	{
		$data = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT `id` FROM `categories` WHERE `parentId`='$Pid' OR `id`='$Pid')");
	}
	else 
	{
		$data = mysqlQuery("SELECT * FROM `products` WHERE `cid` ='$id'");
	}
	$totalRows = mysql_num_rows($data);
	$last = ceil($totalRows/$limit);
	if (isset($Page) && $Page!='' && ($Page>=1 && $Page<=$last)) 
	{
		$page=$Page;
		if ($page>1) {
			$prev=$page-1;
		} 
		else {
			$prev=$page;
		}
		if ($page<$last) {
			$next=$page+1;
		} 
		else {
			$next=$page;
		}
	}
?>
	<div class="clearfix"></div>
	<ol class="breadcrumb">
		<?php
		echo('<li class="hidden-xs">'.$lang_array['all_products'] . '  </li>');
			?>
			<li class="active">
				<a href="<?php echo(rootpath()) ?>/category/<?php echo $getCategory ?>">
					<span><?php echo (isParentCategory($getCategory) ? getCategoryNameFromPermalink($getCategory) : getCategory($id))?></span>
				</a>
			</li>
		<div class="btn-group pull-right hidden-xs">
			<?php
				if ($_SESSION['soRTOrder'] == "ASC") 
				{
					?>
					<a title="Ascending" href="<?php echo ($Page ? rootpath().'/category/'.$permalink.'/DESC/'.$Page : rootpath().'/category/'.$permalink.'/DESC')?>"
					class="btn btn-info asc-des btn-sm active"  type="button" data-toggle="Descending"><i class="fa fa-arrow-up"></i>
					</a>
					<?php
				} 
				else if ($_SESSION['soRTOrder'] == "DESC") 
				{
					?>
					<a title="Descending" href="<?php echo ($Page ? rootpath().'/category/'.$permalink.'/ASC/'.$Page : rootpath().'/category/'.$permalink.'/ASC')?>" class="btn btn-info asc-des btn-sm active"  type="button" data-toggle="Ascending"><i class="fa fa-arrow-down"></i></a>
					<?php
				}
			?>
		</div>
		<div class="btn-group pull-right"> 
				<select class="btn btn-default option-btn btn-sm dropdown-toggle sort-by-btn" <?php echo ($Page ? 'onchange="sortByWithPageNO(this.value)"' : 'onchange="sortByWithOutPageNO(this.value)"') ?>>
				
				<option value="id" <?php echo ($_SESSION['soRTBy'] == 'id' ? 'selected' : "") ?>><?php echo $lang_array['date']; ?></option>
				
				<option value="price" <?php echo ($_SESSION['soRTBy'] == 'price' ? 'selected' : "") ?>><?php echo $lang_array['price']; ?></option>
				
				<option value="clicks" <?php echo ($_SESSION['soRTBy'] == 'clicks' ? 'selected' : "") ?>><?php echo $lang_array['popularity']; ?></option>
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
	$sortOrder=$_SESSION['soRTOrder'];
	$startResult = ($page-1) * $limit;
	if(categoryCacheEnable())
	{
		$var = $getCategory."_".$Pid.$sortBy.$sortOrder.$page;
		$categoryCacheExpireTime = categoryCacheExpireTime();
		$data = $cache->get($var);
		if($data==null)
		{
			if(isParentCategory($getCategory)) 
			{
				$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT `id` FROM `categories` WHERE `parentId`='$Pid' OR `id`='$Pid') ORDER BY $sortBy $sortOrder LIMIT $startResult , $limit");
			} 
			else 
			{
				$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` = '$id' OR `cid` IN(SELECT `id` FROM `categories` WHERE `parentId`='$id') ORDER BY $sortBy $sortOrder LIMIT $startResult , $limit");
			}
			$data = array();
			while($row = mysql_fetch_array($qry))
			{
				$data[] = $row;
			}
			$cache->set($var,$data,$categoryCacheExpireTime);	
		}
	}
	else
	{
		if(isParentCategory($getCategory)) 
		{
			$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT `id` FROM `categories` WHERE `parentId`='$Pid' OR `id`='$Pid') ORDER BY $sortBy $sortOrder LIMIT $startResult , $limit");
		} 
		else 
		{
			$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` = '$id' OR `cid` IN(SELECT `id` FROM `categories` WHERE `parentId`='$id') ORDER BY $sortBy $sortOrder LIMIT $startResult , $limit");
		}
		$data = array();
		while($row = mysql_fetch_array($qry))
		{
			$data[] = $row;
		}
	}
	if (count($data) > 0) 
	{
		foreach($data as $row)
		{
			$urlScreens = $row['screens'];
			if (strpos($urlScreens, '?') !== FALSE) {
				$urlScreens .= "&ref=" . getEnvatoUsername();
			} 
			else {
				$urlScreens .= "?ref=" . getEnvatoUsername();
			}
			$url = $row['url'];
			if (strpos($url, '?') !== FALSE) {
				$url .= "&ref=" . getEnvatoUsername();
			} 
			else {
				$url .= "?ref=" . getEnvatoUsername();
			}
			$demoUrl = $row['demo'];
			if (strpos($demoUrl, '?') !== FALSE) {
				$demoUrl .= "&ref=" . getEnvatoUsername();
			} 
			else {
				$demoUrl .= "?ref=" . getEnvatoUsername();
			}
			$thumbnailSize  = strtolower(indexThumbnail());
			$pieces = explode('x', $thumbnailSize);  
			
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
			<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/'. $row['permalink']. '.html">
			<h4>'.trim($row['title']).'</h4></a>';
			$description=trim(strip_tags($row['description']));
			if (strlen($description) > 100) {
				$stringCut = substr($description, 0, 100);
				$description = substr($stringCut, 0, strrpos($stringCut, ' ')).'...';
			}
			echo '<p class="dis_hide">'.$description.' </p>
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
		?>
		<div class="clearfix"></div>
		<?php if($adsdata['rec2Status']) 
		{ 
			?>
			<div class="col-md-12 hidden-ads" style="text-align: center;margin-bottom: 10px">
				<?php echo ($adsdata['medRec2']) ?>
			</div>
			<?php
		}
		if($totalRows > $limit) 
		{ 
			?>
			<div class="centered btn-first">
				<ul class="pagination">
						<li>
							<a class="btn-size" href="<?php echo(rootpath()) ?>/category/<?php echo($getCategory) ?>"><?php echo $lang_array['first'];?></a>
							<?php $paging.="1,"?>
						</li>
						<?php if($page > 1) 
						{ 
							?>
							<li>
								<a class="btn-size" href="<?php echo(rootpath()) ?>/category/<?php echo($getCategory) ?>/<?php echo($prev) ?>">&laquo;</a>
								<?php $paging.="$prev,"?>
							</li>
							<?php
						}
					if ($page>1 && $last>5) 
					{
						$i=((int)$page/5)*5-1;
						if (!($i+5>$last)) 
						{
							$tempLast = $i+5;
						} 
						else 
						{
							$i = $last - 5;
							$tempLast = $last;
						}
					} 
					else 
					{
						$i=1;
						if (!($i+5>$last)) 
						{
							$tempLast = 5;
						} 
						else 
						{
						$tempLast = $last;
						}
					}
						for ($i; $i<=$tempLast; $i++) 
						{
							if ($i==$page) 
							{
								echo('<li class="active"><a class="btn-size" href="' . rootpath() . '/category/' . $getCategory . '/' . $i  . '">' . $i . '</a></li>');
							} 
							else 
							{
								echo('<li><a class="btn-size" href="' . rootpath() . '/category/' .  $getCategory . '/' . $i  . '">' . $i . '</a></li>');
								$paging.="$i,";
							}
						}
						if($page!=$last) 
						{
							?>
							<li>
								<a class="btn-size" href="<?php echo(rootpath()) ?>/category/<?php echo($getCategory) ?>/<?php echo($next) ?>">&raquo;</a>
								<?php $paging.="$next," ?>
							</li>
							<?php 
						} 
						?>
						<li>
							<a class="btn-size" href="<?php echo(rootpath()) ?>/category/<?php echo($getCategory) ?>/<?php echo($last) ?>"><?php echo $lang_array['last'];?></a>
							<?php $paging.="$last," ?>
						</li>
				</ul>
			</div>
			<?php 
		}
	} 
	else 
	{
		echo ('
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<div class="error-template">
					<h1>'.$lang_array['404_oops'].'</h1>
					<h2>
						<i class="fa fa-times-circle"></i> ' . $lang_array['products_not_found'] . '
					</h2>
					</div>
				</div>
			</div>
		</div>');
		}
	echo ('</div></div>');
} else
{
	echo page404($lang_array);
}
?>
<script>
function sortByWithPageNO(sortBy) 
{
	window.location="<?php echo (rootpath()); ?>/category/<?php echo($permalink) ?>/"+sortBy+"/<?php echo trim($Page)?>";
}
function sortByWithOutPageNO(sortBy) 
{
	window.location="<?php echo (rootpath()); ?>/category/<?php echo($permalink) ?>/"+sortBy+"";
}
</script>
<?php require 'includes/footer.php';?>