<?php
defined("APP") or die();
ob_start();
if(!isset($_SESSION))
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
require 'includes/header.php';
$Type='search';
$SearchCategory=mres($this->SearchCategory);
$Search=$this->Search;
$Search=xssClean(mres($Search));
$SortOrder=$this->SortOrder;
$SortBy=$this->SortBy;
$Page=$this->Page;
$webdata=getWebDate();
$adsdata=getAdsData();?>

	<title>Search Result For <?php echo $Search?></title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="Search Result For <?php echo str_replace("-"," ",$Search)?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/search/'.$SearchCategory.'/'.$Search?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php require "includes/header_under.php"; 
if (isset($SortOrder)) 
{
	if (trim($SortOrder) == "ASC" || trim($SortOrder) == "DESC") 
	{
		$_SESSION['SortOrder'] = trim($SortOrder);
	}
}
if (isset($SortBy)) 
{
	if (trim($SortBy) == "relevence" || trim($SortBy) == "price" || trim($SortBy) == "clicks"  || trim($SortBy) == "id") 
	{
		if (trim($SortBy) == "relevence") {
			$_SESSION['SortBy'] = "relevence";
		} 
		else if(trim($SortBy) == "price") {
			$_SESSION['SortBy'] = "price";
		} 
		else if (trim($SortBy) == "clicks") {
			$_SESSION['SortBy'] = "clicks";
		}  
		else if (trim($SortBy) == "id") {
			$_SESSION['SortBy'] = "id";
		}
		else {
		$_SESSION['SortBy'] = "relevence";
		}
	}
}
if (!isset($_SESSION['SortBy'])) {
$_SESSION['SortBy'] = "relevence";
}
if (!isset($_SESSION['SortOrder'])) {
$_SESSION['SortOrder'] = "DESC";
}
if ($_SESSION['SortBy'] == "relevence") {
$sortBy = "relevence";
} 
else if ($_SESSION['SortBy'] == "price") {
$sortBy = "price";
} 
else if ($_SESSION['SortBy'] == "clicks") {
$sortBy = "clicks";
} 
else if ($_SESSION['SortBy'] == "id") {
$sortBy = "id";
} 
else {
$sortBy = "relevence";
}
isSearchProduct($Search);
$qs = "";
if(isset($Search) && trim($Search)!="")
{
	$str=str_replace("-",",",$Search);
	$S=explode(",",$str);
	$L=1;
	for ($i = count($S); $i>=1; $i--) {
		$a = $b = 0;
		$subset = [];
		while ($a < count($S)) {
			$current = $S[$a++];
			$subset[] = $current;
			if (count($subset) == $i) {
				$result .= json_encode($subset);
				array_pop($subset);
			}
		}
		
	}
	$rpAgain=str_replace('","',' ',$result);
	$afterReplace=str_replace('"]["',',',$rpAgain);
	$ltrim=ltrim($afterReplace,'["');
	$rtrim=rtrim($ltrim,'"],');
	$words = explode(',', $rtrim);
	$looplast=count($words)*2-2;
	for ($i = 0; $i < count($words); $i++)
	{
		$tagscount=count($words) + $i;
		$taglast=$tagscount+1;
		$search=$words[$i];
		$search=trim($search);
		$title .="`title` LIKE '%".$search."%' THEN ".$i." WHEN ";
		if($tagscount <=$looplast)
		$tags .= "`tags` LIKE '%".$search."%' THEN ".$tagscount." WHEN ";
		else
		$tags .= "`tags` LIKE '%".$search."%' THEN ".$tagscount." ELSE ".$taglast." END";
	}
	$string1=str_replace(",","%' OR `title` LIKE '%",trim($str));
	$string1="`title` LIKE '%".$string1."%'";
	$string2=str_replace(",","%' OR `tags` LIKE '%",trim($str));
	$string2="`tags` LIKE '%".$string2."%'";
	$queryString="SELECT * FROM `products` WHERE " . $string1 .  " OR " . $string2 .  "";
	$order=$title.$tags;
	$error = "";
	$page=1;//Default page
	if($adsdata['rec1Status']==1)
	$limit=limitPosts();
	else {
	$limit=limitPosts();
	$limit=$limit + 2;
	}
	$next=2;
	$prev=1;
		if($SearchCategory !='all') {
		$Pid=getCategoryIdFromPermalink($SearchCategory);
		$data = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT id FROM categories WHERE parentId='$Pid' OR `id`='$Pid') AND (".$string1." OR ".$string2.")");
		} else {
		$data=mysqlQuery($queryString);
		}
		$totalRows = mysql_num_rows($data);
		$last = ceil($totalRows/$limit);
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
	?>
	<div class="clearfix"></div>
	<ol class="breadcrumb">
		<li>
			<?php echo $lang_array['search_result_list'];?> <span class="tags-title"><?php echo $lang_array['search_result_for']?><strong><?php echo $lang_array['for'];?></strong>&nbsp;&nbsp;'<strong><?php echo $search ?></strong>'</span>
		</li>
		<div class="btn-group pull-right hidden-xs">
			<?php
				if ($_SESSION['SortOrder'] == "ASC") 
				{
					?>
					<a title="Ascending" href="<?php echo ($Page ? rootpath().'/search/'.$SearchCategory.'/'.$Search.'/DESC/'.$Page : rootpath().'/search/'.$SearchCategory.'/'.$Search.'/DESC')?>" class="btn btn-info asc-des btn-sm active"  type="button" data-toggle="Descending"><i class="fa fa-arrow-up"></i>
					</a>
					<?php
				} 
				else if ($_SESSION['SortOrder'] == "DESC") 
				{
					?>
					<a title="Descending" href="<?php echo ($Page ? rootpath().'/search/'.$SearchCategory.'/'.$Search.'/ASC/'.$Page : rootpath().'/search/'.$SearchCategory.'/'.$Search.'/ASC')?>" class="btn btn-info asc-des btn-sm active"  type="button" data-toggle="Ascending"><i class="fa fa-arrow-down"></i></a>
					<?php
				}
			?>
		</div>
		<div class="btn-group pull-right"> 
		<select class="btn btn-default option-btn btn-sm dropdown-toggle sort-by-btn" <?php echo ($Page ? 'onchange="sortByWithPageNO(this.value)"' : 'onchange="sortByWithOutPageNO(this.value)"') ?>>
		
			<option value="relevence" <?php echo ($_SESSION['SortBy'] == 'relevence' ? 'selected' : "") ?>><?php echo $lang_array['relevence']; ?></option>
			
			<option value="clicks" <?php echo ($_SESSION['SortBy'] == 'clicks' ? 'selected' : "") ?>><?php echo $lang_array['mostpopular']; ?></option>
			
			<option value="price" <?php echo ($_SESSION['SortBy'] == 'price' ? 'selected' : "") ?>><?php echo $lang_array['price']; ?></option>
			
			<option value="id" <?php echo ($_SESSION['SortBy'] == 'id' ? 'selected' : "") ?>><?php echo $lang_array['date']; ?></option>
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
	<div class="panel-body related-products1">
	<div class="clearfix"></div>
	<div class="row">
	<?php
	$startResult = ($page-1)*$limit;
	if($SearchCategory !='all') 
	{
		if($_SESSION['SortBy']=='relevence') 
		{
			$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT id FROM categories WHERE parentId='$Pid' OR `id`='$Pid') AND (".$string1." OR ".$string2.") ORDER BY CASE WHEN ".$order ." LIMIT ".$startResult.",".$limit);
		}
		else 
		{
		$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid` IN (SELECT id FROM categories WHERE parentId='$Pid' OR `id`='$Pid') AND (".$string1." OR ".$string2.") ORDER BY ".$_SESSION['SortBy']." ".$_SESSION['SortOrder']."  LIMIT ".$startResult.",".$limit);
		}
	} 
	else 
	{
		if($_SESSION['SortBy']=='relevence') 
		{
			$qry = mysqlQuery($queryString." ORDER BY CASE WHEN ".$order ." LIMIT ".$startResult.",".$limit);
		}
		else
		{
			$qry = mysqlQuery($queryString." ORDER BY ".$_SESSION['SortBy']." ".$_SESSION['SortOrder']." LIMIT ".$startResult.",".$limit);
		}
	}
	$numRows = mysql_num_rows($qry);
	if ($numRows > 0)
	{
		$i=1;
		while($row = mysql_fetch_array($qry))
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
			$thumbnailSize  = strtolower(indexThumbnail());
			$pieces = explode('x', $thumbnailSize);
			echo '<div class="col-lg-4 col-sm-6 col-xs-6">
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
			else
			{
				echo'<a title="' . getProductDescription($row['permalink']) . '" href="' . rootpath() . '/product/' . $row['permalink']. '.html">
				<img class="img-responsive center-block" src="' . rootpath() . '/thumb.php?id=' . $row['id'] . '&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'"/></a>';
			}
			echo '</div>
			<div class="details">
			<div class="info">
			<a title="' . getProductDescription($row['permalink']) . '" href="'  . rootpath().'/product/'. $row['permalink']. '.html">
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
			<a class="btn btn-small btn-success demo-purchase-btn purchase" id="'.$row['permalink'].'" href="' . $url . '" target="_blank"><i class="fa fa-fa fa-shopping-cart"></i> <span class="purchase-txt">'.$lang_array['purchase'].'</span>&nbsp';
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
		if($adsdata['rec2Status']) 
		{ 
			?>
			<div class="clearfix"></div>
			<div class="col-md-12 hidden-ads" style="text-align: center;margin-bottom: 10px">
				<?php echo $adsdata['medRec2']; ?>
			</div>
			<?php
		}
		?>
		<div class="centered btn-first">
			<?php 
			if($totalRows > $limit) 
			{ 
				?>
				<ul class="pagination">
					<?php
					echo('<li><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/">First</a></li>');
					if($page > 1) { 
					echo('<li><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/' . $prev . '">&laquo;</a></li>');
					}
					if ($page > 1 && $last > 5) 
					{
						$i = ((int)$page / 5) * 5 - 1;
						if (!($i + 5 > $last)) 
						{
							$temp_last = $i + 5;
						} else 
						{
							$i = $last - 5;
							$temp_last = $last;
						}
					} 
					else 
					{
						$i = 1;
						if (!($i + 5 > $last))
							$temp_last = 5;
						else
							$temp_last = $last;
					}
					for ($i; $i <= $temp_last; $i++) 
					{
						if ($i == $page)
							echo('<li class="active"><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/' .$i . '">' . $i . '</a></li>');
						else
							echo('<li><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/'. $i . '">' . $i . '</a></li>');
					}
					if($page !=$last) {
					echo('<li><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/' . $next . '">&raquo;</a></li>');
					}
					echo('<li><a class="btn-size" href="' . rootpath() . '/search/'.$SearchCategory.'/' . $Search . '/' . $last . '">Last</a></li>');
					?>
				</ul>
				<?php 
			} 
			?>
		</div>
		<?php
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
	?>
	</div>
	</div>
<?php
} else {
echo page404($lang_array);
}
?><script>
function sortByWithPageNO(sortBy) 
{
	window.location="<?php echo (rootpath()); ?>/search/<?php echo $SearchCategory ?>/<?php echo $Search?>/"+sortBy+"/<?php echo trim($page)?>";
}
function sortByWithOutPageNO(sortBy) 
{
	window.location="<?php echo (rootpath()); ?>/search/<?php echo $SearchCategory ?>/<?php echo $Search?>/"+sortBy+"";
}
</script><?php
require 'includes/footer.php';
?>