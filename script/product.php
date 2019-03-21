<?php
defined("APP") or die();
if(!isset($_SESSION))
session_start();
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
$cache = phpFastCache();
$Product=$this->Product;
$Product=substr($Product, 0, strlen($Product) - 5);
$categoryId=getCategoryIdByPermalink($Product);
$targetArray = array("top","bottom");
$rand = array_rand($targetArray);
$webdata=getWebDate();
$adsdata=getAdsData();
if(!isset($_SESSION['clicks'])) $_SESSION['clicks'] = array();
require 'includes/header.php'; 
include 'includes/header_under.php'; 
$found=0;
$permalink = $Product;
if(productCacheEnable())
{
	$row = $cache->get($permalink);
	$productCacheExpireTime = productCacheExpireTime(); 
	if($row == null)
	{	
		if(!isset($Product) || !isValidProduct($Product))
		{
			$found=1;
			echo page404($lang_array);	
			exit();
		}
		else
		{
			$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `permalink`='" .$permalink . "'"));
			$cache->set($permalink, $row, $productCacheExpireTime);
		}
	}
}
else
{
	if(!isset($Product) || !isValidProduct($Product))
	{
		$found=1;
		echo page404($lang_array);	
		exit();
	}
	else
	{
		$row = mysql_fetch_array(mysqlQuery("SELECT * FROM `products` WHERE `permalink`='" .$permalink . "'"));
	}
}
?>
<title><?php echo $row['title'] ?></title>
<meta name="description" content="<?php echo strip_tags(getProductDescription($Product))?>" />
<meta name="keywords" content="<?php echo $row['tags'] ?>" />
<meta property="og:title" content="<?php echo $row['title'] ?>" />
<meta property="og:type" content="product" />
<meta property="og:url" content="<?php echo rootpath().'/product/'.$Product.'.html'?>" />
<meta property="og:image" content="<?php echo $row['image'] ?>" />
<meta property="og:description" content="<?php echo strip_tags(getProductDescription($Product))?>" /> 
<meta property="og:site_name" content="<?php echo (getTitle())?>" />
<?php  
if($found==0)
{
	?>
	<ol class="breadcrumb hidden-xs">
		<li>
			<a href="<?php echo rootpath().'/index.php' ?>"><?php echo $lang_array['breadcrumb_text_home'];?> </a>
		</li>
		<?php 
		$pProduct = parentCategory($Product);
		if($pProduct != "")
		{
			?>
			<li>
				<?php echo $pProduct; ?>
			</li>
			<?php
		}
		?>
		<li>
			<a href="<?php echo(rootpath()) ?>/category/<?php echo (productPermalink($Product)) ?>"><?php echo  productCategory($Product); ?> </a>
		</li>
		<li class="active"><?php echo str_replace("-"," ",$Product) ?></li>
	</ol>
	<?php
	echo '<div class="related-products1">';
	$catname=productCategory($permalink);
	$qry=mysqlQuery("SELECT `permalink` FROM `categories` WHERE `name`='" . $catname. "'");
	$qryfetch=mysql_fetch_array($qry);
	$catpermalink=$qryfetch['permalink'];
	$title = $row['title'];
	$datePublish=$row['publishDate'];
	$image = $row['image'];
	$description = truncateDescription(strip_tags($row['description']));
	$rating = $row['rating'];
	?>  
	<div class="clearfix"></div>
	<div class="row">
	<div class="col-md-8 product-res-img">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row"> 
							<div class="col-sm-10">
								<h3 class="panel-title"><b><?php echo($title) ?></b></h3>
							</div>
							<div class="col-sm-2 pull-right">
								<?php
								if($rating == 0.0)
								{
									for($i = 0; $i < 5; $i++)
									{
										?>
										<i class="fa fa-star-o"></i>
										<?php
									}
								}
								else
								{
									$j=0;
									$star = floor($rating);
									for($i = 0; $i < $star; $i++)  
									{
										$j++;
										?>
										<i class="fa fa-star star-active"></i>
										<?php
									}
									$half = $rating/$star;
									$half = ($half - (int)$half)*$star;
									if($half > 0)
									{
										$j++;
										?>
										<i class="fa fa-star-half-o star-active"></i>
										<?php
									}  
									$remaining = 5-$j;
									for($k = 0; $k < $remaining; $k++)  
									{
										?>
										<i class="fa fa-star-o star-active"></i>
										<?php
									}
								}
								?>
							</div>
						</div>
					</div>
					<div class="panel-body">
						<h6 class="published-on">
							<?php echo $lang_array['product_publish_on'];?> <i class="fa fa-clock-o"></i> <?php echo date("l, F d, Y,H:i:s", strtotime($datePublish)); ?> &nbsp; <?php echo $lang_array['in'];?> &nbsp; <i class="fa fa-folder-open"></i>&nbsp;<a href="<?php echo(rootpath()) ?>/category/<?php echo($catpermalink) ?>"><?php echo  productCategory($permalink); ?></a>
						</h6>
						<hr />
						<div class="row">
							<div class="col-xs-12">
								<div class="input-group social-btns">
									<a target="_blank" class="social-btn fb-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo curPageURL(); ?>">
										<i class="fa fa-facebook-square"></i>
									</a>
									<a target="_blank" class="social-btn twitter-btn" href="http://twitter.com/home?status=<?php echo(ucfirst($title)); ?>%20-%20<?php echo curPageURL(); ?>">
										<i class="fa fa-twitter"></i>
									</a>
									<a target="_blank" class="social-btn google-btn" href="https://plus.google.com/share?url=<?php echo curPageURL(); ?>">
										<i class="fa fa-google-plus"></i>
									</a>  
									<a target="_blank" class="social-btn pin-btn" href="http://pinterest.com/pin/create/button/?url=<?php echo curPageURL(); ?>&amp;media=<?php echo($image); ?>&amp;description=<?php echo($description); ?>">
										<i class="fa fa-pinterest"></i>
									</a>
									<a target="_blank" class="social-btn li-btn" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php	echo curPageURL();?>&amp;title=<?php echo($title); ?>">
										<i class="fa fa-linkedin-square"></i>
									</a>
									<a target="_blank" class="social-btn red-btn" href="https://www.reddit.com/login?dest=http://www.reddit.com/submit?url=<?php echo curPageURL(); ?>/&amp;title=<?php echo($title); ?>">
										<i class="fa fa-reddit"></i>   
									</a>
									<a target="_blank" class="social-btn vk-btn" href="https://vk.com/share.php?url=<?php echo curPageURL(); ?>&amp;title=<?php echo($title); ?>&amp;description=<?php echo($description); ?>&amp;image=https://www.reddit.com/login?dest=http://www.reddit.com/submit?url=<?php echo curPageURL(); ?>&amp;noparse=true">
										<i class="fa fa-vk"></i>
									</a>
									<a target="_blank" class="social-btn digg-btn" rel="nofollow" title="<?php echo($title); ?>" href="http://digg.com/submit?phase=2&amp;url=<?php echo curPageURL(); ?>">
										<i class="fa fa-digg"></i>
									</a>	
								</div>
							</div>
						</div>
						<?php
						$cid = $row['cid'];
						$id = $row['id']; 
						$description = $row['description'];
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
						
						if(getdomain($url)=='audiojungle.net')  
						{
							echo' <img class="img-responsive" src="' . rootpath() . '/thumb.php?id=audio&w=590&h=300" class="thumbnail img-responsive img-rounded" style="margin-left: auto; margin-right: auto; margin-bottom: 15px;" alt="image"> ';
							echo '<div class="audio-playr-product">';   
							echo' <div class="audio-js-box hu-css">
									<audio class="audio-js" preload="none" controls>
									  <source src="'.$row['image'].'" type="audio/mpeg">
									</audio>
								  </div>';  
							echo '</div>';
						}
						else if(getdomain($url)=='videohive.net')  
						{
							echo '<div style="margin-left: auto; margin-right: auto; margin-bottom: 15px;">';
							echo '<video id="demo1-'.$row['id'].'" class="video-js vjs-default-skin" controls
								 preload="none" width="auto" height="300" data-setup="{}" poster="'.$row['image'].'">
								 <source src="'.$row['demo'].'" type="video/mp4">
								</video>';
							echo '</div>';
						}
						else if(getdomain($url)=='activeden.net')
						{
							$check=explode(".",$row['image']);
							$extension=end($check);
							$extension=trim($extension);
							if($extension=="swf")
							{
								echo '<object class="object-product" type="application/x-shockwave-flash" data="'.$row['image'].'">
									<param name="movie" value="'.$row['image'].'" />
									<param name="quality" value="high" />
									</object>';
							}
							else
							{
								echo' <img class="img-responsive" src="' . rootpath() . '/thumb.php?id=' . $row["id"] . '&w=590&h=300" class="thumbnail img-responsive img-rounded" style="margin-left: auto; margin-right: auto; margin-bottom: 15px;" alt="image"> ';
							}
						}
						else
						{
							echo' <img class="img-responsive" src="' . rootpath() . '/thumb.php?id=' . $row["id"] . '&w=590&h=300" class="thumbnail img-responsive img-rounded" style="margin-left: auto; margin-right: auto; margin-bottom: 15px;" alt="image"> ';
						}
						echo '<div class="product-btns">';
						if(getdomain($url)=='videohive.net')
						{
							echo '<div class="btn-group">
							<a class="btn btn-small btn-info btn-demo hidden-xs" target="_blank" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;Demo</a>
							</div>
							<div class="btn-group">
							<a class="btn btn-small btn-info btn-demo-visible  hidden-sm hidden-md hidden-lg" target="_blank" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><i class="fa fa-desktop"></i></a>
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
							<a class="btn btn-small btn-info btn-demo hidden-xs" target="_blank" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;Demo</a>
							</div>
							<div class="btn-group">
							<a class="btn btn-small btn-info btn-demo-visible  hidden-sm hidden-md hidden-lg" target="_blank" data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><i class="fa fa-desktop"></i></a>
							</div>';
		
							echo'<div id="myModal'.$row['id'].'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
								<div class="modal-dialog mod">
									<div class="modal-content">
										<div class="modal-header modh">
											<button type="button" class="closevideo close modhc" id="'.$row['id'].'" data-dismiss="modal" aria-hidden="true">&times;</button>
										</div>
										<div class="modal-body mbody">    
											<object class="object-product" type="application/x-shockwave-flash" data="'.$row['image'].'">
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
								<a class="btn btn-small btn-info btn-demo hidden-xs" target="_blank" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;Demo</a>
								</div>
								<div class="btn-group">
								<a class="btn btn-small btn-info btn-demo-visible  hidden-sm hidden-md hidden-lg" target="_blank" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i></a>
								</div>';
							}
						}
						else 
						{
							if($row['screens']!="")  
							{
								echo '<div class="btn-group">
								<a class="btn btn-small btn-warning btn-snapshot hidden-xs" target="_blank" href="' . $urlScreens . '" ><i class="fa fa-picture-o"></i> &nbsp; Screenshots</a>
								</div>';
								echo '<div class="btn-group">
									<a class="btn btn-small btn-warning btn-snapshot-visible hidden-sm hidden-md hidden-lg" target="_blank" href="' . $urlScreens . '" ><i class="fa fa-picture-o"></i></a>
								</div>';
							}
							else
							{
								echo '<div class="btn-group">
								<a class="btn btn-small btn-warning btn-snapshot hidden-xs" target="_blank" href="' . $url . '" ><i class="fa fa-picture-o"></i> &nbsp; Screenshots</a>
								</div>';
								echo '<div class="btn-group">
								<a class="btn btn-small btn-warning btn-snapshot-visible hidden-sm hidden-md hidden-lg" target="_blank" href="' . $url . '" ><i class="fa fa-picture-o"></i></a>
								</div>';
							}
							
							if($row['demo']!="")
							{
								echo '<div class="btn-group">
								<a class="btn btn-small btn-info btn-demo hidden-xs" target="_blank" href="' . $demoUrl . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;Demo</a>
								</div>
								<div class="btn-group">
								<a class="btn btn-small btn-info btn-demo-visible  hidden-sm hidden-md hidden-lg" target="_blank" href="' . $demoUrl . '"  target="_blank"><i class="fa fa-desktop"></i></a>
								</div>';
							}
							else
							{
								echo '<div class="btn-group">
								<a class="btn btn-small btn-info btn-demo hidden-xs" target="_blank" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i>&nbsp;Demo</a>
								</div>
								<div class="btn-group">
								<a class="btn btn-small btn-info btn-demo-visible  hidden-sm hidden-md hidden-lg" target="_blank" href="' . $url . '"  target="_blank"><i class="fa fa-desktop"></i></a>
								</div>';
							}
						}
						echo '<div class="btn-group">
						<a class="btn btn-small btn-success btn-price  hidden-xs" target="_blank" style="margin-left: 3px;" href="' . $url . '"><i class="fa fa-shopping-cart"></i>&nbsp'.$lang_array['purchase'].'&nbsp';
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
						<div class="btn-group">
						<a class="btn btn-small btn-success btn-price-visible hidden-sm hidden-md hidden-lg" style="margin-left: 3px;" target="_blank" href="' . $url . '"><i class="fa fa-shopping-cart"></i> ');
						if($showBefore ==  1)
							echo " " . $currencySymbol." ". $row['price'] * $priceInDollar; 
						else 
							echo $row['price'] * $priceInDollar." ". $currencySymbol; 
						echo ('</a>
						</div>
						</div>
						<hr/>');     			  
						echo $description;
						?>
						<div class="clearfix"></div>  
						<?php 
						if($adsdata['rec3Status']) 
						{
							?>
							<div class="col-md-12 visible-sm" style="text-align: center; margin-top: 10px">
							<?php echo($adsdata['medRec3']) ?>
							</div>
							<?php
						}
						?>
						<div class="tags-heading">
							<h3><i class="fa fa-tags"></i> Product Tags</h3> 
							<div class="col-md-12">
								<div class="navbar-left">
									<div class="row">
										<h3><?php $lang_array['tags'] ?></h3>
										<?php
										$t = $row['tags'];
										if ($t!="")  
										{  
											$tags= explode("," , $t);
											foreach($tags as $tag)
											{
												$tag_name = trim($tag);
												$tag = str_replace(" ","-",$tag_name);            
												echo("<a href='" . rootpath() . "/tags/$tag'><ul class='products-tag'><li><span>$tag_name</span></li></ul></a>");
											}
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php if($targetArray[$rand]=='top')
			{
				?>
				<div>
					<div class="col-xs-12">
						<?php
						if($adsdata['rec2Status']) { ?>
						<div class="visible-lg" style="text-align: center; margin-bottom: 10px">
						<?php echo $adsdata['medRec2'] ?>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="clearfix"></div>
				<?php 
			}
			if(enablePossible())
			{  
				if(relatedCacheEnable())
				{
					$relatedCacheExpireTime = relatedCacheExpireTime();
					$data=$cache->get("related_$permalink");
					if($data==null)
					{
						$query=mysqlQuery("SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id)");
						$countTotal=mysql_num_rows($query);
						if($countTotal > PossibleProLimit()) 
						{
							$qry = mysqlQuery("SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id) LIMIT ".PossibleProLimit());
						} 
						else 
						{
							$newCount=PossibleProLimit()-$countTotal;
							$qry=mysqlQuery("(SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id) LIMIT $countTotal) UNION (SELECT * FROM `products` WHERE `cid`='$categoryId' AND `permalink`!='$Product' ORDER BY RAND() LIMIT $newCount)");
						}
						if($qry)
						{ 
							$data = array();
							while($row = mysql_fetch_array($qry))
							{
								$data[] = $row;
							}
						}
						$cache->set("related_$permalink", $data, $relatedCacheExpireTime);
					}
				}
				else
				{
					$query=mysqlQuery("SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id)");
					$countTotal=mysql_num_rows($query);
					if($countTotal > PossibleProLimit()) 
					{
						$qry = mysqlQuery("SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id) LIMIT ".PossibleProLimit());
					} 
					else 
					{
						$newCount=PossibleProLimit()-$countTotal;
						$qry=mysqlQuery("(SELECT * FROM `products` WHERE MATCH(tags,title) AGAINST('" . $title . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND `id` NOT IN($id) LIMIT $countTotal) UNION (SELECT * FROM `products` WHERE `cid`='$categoryId' AND `permalink`!='$Product' ORDER BY RAND() LIMIT $newCount)");
					}
					if($qry)
					{ 
						$data = array();
						while($row = mysql_fetch_array($qry))
						{
							$data[] = $row;
						}
					}
				}
				if (count($data) > 0)
				{
					?>
					<div class="col-md-12">
						<div class="panel panel-default">  
							<div class="panel-heading">
								<h3 class="panel-title"><b><?php echo $lang_array['possibly_related_products'];?></b></h3>
							</div>
							<div class="panel-body related-products">  
								<div class="row">
									<?php
									foreach($data as $row)
									{
										$cache->set($row['permalink'], $row);
										$thumbnail_size  = strtolower(possibleThumbnail());
										$pieces = explode('x', $thumbnail_size);
										echo'<div class="col-lg-4 col-sm-6 col-xs-6">';
										if(getdomain($row['url'])=="audiojungle.net")
										{
											echo'<a href="' . rootpath() . '/product/' . $row['permalink']. '.html" class="thumbnail">
											<img class="img-responsive" src="' . rootpath() . '/thumb.php?id=audio&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'" class="img-responsive img-rounded" title="' . getProductDescription($row['permalink']). '" style="margin-left: auto; margin-right: auto;" alt="image">';
											
										}
										else if(getdomain($row['url'])=="activeden.net")
										{
											echo'<a href="' . rootpath() . '/product/' . $row['permalink']. '.html" class="thumbnail">
											<img class="img-responsive" src="' . rootpath() . '/thumb.php?id=flash&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'" class="img-responsive img-rounded" title="' . getProductDescription($row['permalink']). '" style="margin-left: auto; margin-right: auto;" alt="image">';
											
										}
										else
										{
											echo'<a href="' . rootpath() . '/product/' . $row['permalink']. '.html" class="thumbnail">
											<img class="img-responsive" src="' . rootpath() . '/thumb.php?id=' . $row["id"] . '&w='.trim($pieces[0]).'&h='.trim($pieces[1]).'" class="img-responsive img-rounded" title="' . getProductDescription($row['permalink']). '" style="margin-left: auto; margin-right: auto;" alt="image">';
										}
										echo '<p class="pic-title" align="center" title="' . getProductDescription($row['permalink']). '">' . trimTitleRelated($row['title']). '</p>
										</a>
										</div>';
									} 
									?>
								</div>
							</div>
						</div>   
					</div>
					<?php 
				}
			} 
			if($targetArray[$rand]=='bottom')
			{ 
				?>
				<div>
					<div class="col-xs-12">
						<?php
						if($adsdata['rec2Status']) { ?>
						<div class="ad_bottom_728x90 hidden-xs hidden-md" style="text-align: center; margin-bottom: 10px">
						<?php echo $adsdata['medRec2']; ?>
						</div>
						<?php } ?>
					</div>
				</div>
				<?php 
			 }
			if(disqusStatus() && disqusName())
			{
				?>
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><b><?php echo $lang_array['leave_comment'];?></b></h3>
						</div>
						<div class="panel-body">
							<div id="disqus_thread"></div>
								<script type="text/javascript">
									var disqus_shortname = '<?php echo disqusName()?>';
									(function() {
										var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
										dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
										(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
									})();
								</script>
						</div>
					</div>   
				</div>
				<?php 
				$limit=limitPosts();
			} ?>
			<script>
			$( document ).ready(function() {
				var permalink='<?php echo $Product?>';
				$.ajax({
					type:'POST',
					url: '<?php echo rootpath()?>/increment.php',
					data: {'permalink':permalink},
					success: function(res) {
					}
				});
			});
			</script>      
		</div>
	</div>  
	<?php   
	require 'includes/sidebar.php';  
	echo '</div>'; 
	require 'includes/footer.php';
} 
?>	