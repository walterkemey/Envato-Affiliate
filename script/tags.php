<?php
defined("APP") or die();
if(!isset($_SESSION))
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
$cache = phpFastCache();
$Type='tag';
$Tagname=$this->tagName;
$SortBy=$this->sortBy;
$SortOrder=$this->sortOrder;
$Page=$this->Page;
$webdata=getWebDate();
$adsdata=getAdsData();
$qs = "";
if (isset($SortOrder))
{
	if (trim($SortOrder) == "ASC" || trim($SortOrder) == "DESC") 
		$_SESSION['soRtOrder'] = trim($SortOrder);
}
if (isset($SortBy))   
{  
	if (trim($SortBy) == "id" || trim($SortBy) == "price" || trim($SortBy) == "clicks") 
	if (trim($SortBy) == "id") $_SESSION['sortBy'] = "id";
	else if (trim($SortBy) == "price") $_SESSION['sortBy'] = "price";
	else if (trim($SortBy) == "clicks") $_SESSION['sortBy'] = "clicks";
	else $_SESSION['sortBy'] = "id";
}
if (!isset($_SESSION['sortBy'])) $_SESSION['sortBy'] = "id";
if (!isset($_SESSION['soRtOrder'])) $_SESSION['soRtOrder'] = "DESC";
if ($_SESSION['sortBy'] == "id")  $sortBy = "id";
else if ($_SESSION['sortBy'] == "price") $sortBy = "price";
else if ($_SESSION['sortBy'] == "clicks") $sortBy = "clicks";
else $sortBy = "id";
require 'includes/header.php';
?>
<title><?php echo $lang_array['tag_page_title'] . ' ' . str_replace("-"," ",$Tagname)?></title>
<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
<meta property="og:title" content="<?php echo $lang_array['tag_page_title'] . ' ' . $Tagname . ' - ' . getTitle() ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/tags/'.$Tagname?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php require 'includes/header_under.php';?>
<div class="clearfix"></div>
<?php 
if(isset($Tagname) && trim($Tagname)!="")
{
	$tagName = str_replace("-"," ",trim(strip_tags($Tagname)));
	$Tagname = str_replace(" ","-",$tagName);
	$error = "";
	$page=1;
	$limit=limitPosts();
	$next=2;
	$prev=1;
	$data = mysqlQuery("SELECT `id` FROM `products`  WHERE `tags` LIKE '%" . $tagName. "%' OR `title` LIKE '%".$tagName."%'");
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
	?>
	<ol class="breadcrumb">
		<li><?php echo $lang_array['tag_page_title'] . '  &#x0226B; <strong>' . $tagName ?></strong></li>
		<div class="btn-group pull-right asc-des-btn">
			<?php
				if ($_SESSION['soRtOrder'] == "ASC")
				{
					?>											
					<span><a title="Ascending" href="<?php echo ($Page ? rootpath().'/tags/'.$Tagname.'/DESC/'.$Page : rootpath().'/tags/'.$Tagname.'/DESC')?>" class="btn btn-info asc-des btn-sm active"  type="button" data-toggle="Ascending"><i class="fa fa-arrow-up"></i></a></span>
					<?php
				}
				else if ($_SESSION['soRtOrder'] == "DESC")
				{
					?>											
					<span><a title="Descending" href="<?php echo ($Page ? rootpath().'/tags/'.$Tagname.'/ASC/'.$Page : rootpath().'/tags/'.$Tagname.'/ASC')?>" class="btn btn-info asc-des btn-sm active" type="button" data-toggle="Descending"><i class="fa fa-arrow-down"></i></a></span>
					<?php
				}
			?>
		</div>
		<div class="btn-group pull-right">
				<select class="btn btn-default btn-sm sort-by-btn" <?php echo ($Page ? 'onchange="sortByWithPageNO(this.value)"' : 'onchange="sortByWithOutPageNO(this.value)"') ?>>
				
				<option value="id" <?php echo ($_SESSION['sortBy'] == 'id' ? 'selected' : "") ?>><?php echo $lang_array['date'];?></option>
				
				<option value="price" <?php echo ($_SESSION['sortBy'] == 'price' ? 'selected' : "") ?>><?php echo $lang_array['price'];?></option>
				
				<option value="clicks" <?php echo ($_SESSION['sortBy'] == 'clicks' ? 'selected' : "") ?>><?php echo $lang_array['popularity'];?></option>
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
		if(tagCacheEnable())
		{
			$sOrder=$_SESSION['soRtOrder']; 
			$var = "tag_".$tagName.$sortBy.$sOrder.$page;
			$tagCacheExpireTime = tagCacheExpireTime();
			$data=$cache->get($var);			
			if($data == null)
			{   
				$data = array();
				$qry = mysqlQuery("SELECT * FROM `products` WHERE `tags` LIKE '%" . $tagName . "%' OR `title` LIKE '%".$tagName."%' ORDER BY " . $sortBy . " " . $_SESSION['soRtOrder'] . "  LIMIT ".$startResult.",".$limit);
				while($row = mysql_fetch_array($qry))
				{
					$data[] = $row; 
				}  
				$cache->set($var, $data, $tagCacheExpireTime); 
			}
		}
		else
		{
			$data = array();
			$qry = mysqlQuery("SELECT * FROM `products` WHERE `tags` LIKE '%" . $tagName . "%' OR `title` LIKE '%".$tagName."%' ORDER BY " . $sortBy . " " . $_SESSION['soRtOrder'] . "  LIMIT ".$startResult.",".$limit);
			while($row = mysql_fetch_array($qry))
			{
				$data[] = $row; 
			}  
		}
		if(count($data)>0)  
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
				<div class="image test">';
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
				$description=truncateDescription(trim(strip_tags($row['description'])));
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
			 if($adsdata['rec2Status']) 
			{
				?><div class="col-md-12 hidden-xs ad_top_728x90">
				<?php echo $adsdata['medRec2']; ?> 
				</div> <?php
			} 
			if($rows > $limit) 
			{
				?>
				<div class="centered btn-first">
					<ul class="pagination"> 
						<?php
						echo("<li><a href='" . rootpath() . "/tags/$Tagname'>" . $lang_array['first'] . "</a></li>");
						$paging.="1,";
						if($page > 1)
						echo("<li><a href='" . rootpath() . "/tags/$Tagname/$prev'>&laquo;</a></li>");
						$paging.="$prev,";
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
								echo("<li class='active'><a href='" . rootpath() . "/tags/$Tagname/$i'>$i</a></li>");
							else
								echo("<li><a href='" . rootpath() . "/tags/$Tagname/$i'>$i</a></li>");
								$paging.="$i,";
						}
						if($page !=$last)
						echo("<li><a href='" . rootpath() . "/tags/$Tagname/$next'>&raquo;</a></li>");
						$paging.="$next,";
						echo("<li><a href='" . rootpath() . "/tags/$Tagname/$last'>" . $lang_array['last']. "</a></li>");
						$paging.="$last,";
						?>
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
}
else
{
	echo page404($lang_array);
}
?>
<script>
function sortByWithPageNO(sortBy) {
	window.location="<?php echo (rootpath()); ?>/tags/<?php echo($Tagname) ?>/"+sortBy+"/<?php echo trim($Page)?>";
}
function sortByWithOutPageNO(sortBy) {
	window.location="<?php echo (rootpath()); ?>/tags/<?php echo($Tagname) ?>/"+sortBy+"";
}
</script>
<?php require 'includes/footer.php';?>