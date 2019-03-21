<div class="col-md-4 hidden-xs hidden-sm">
	<div id="top_content" class="panel panel-default">  
		<div class="panel-body">   
			<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FNexthon&amp;width&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;header=false&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:62px;" allowTransparency="true"></iframe>
		</div>
		<div class="panel-footer">
			<div class="col-lg-6" style="border:none; overflow:hidden;"><a href="https://twitter.com/nexthon" class="twitter-follow-button" data-show-count="false">Follow @nexthon</a></div>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<div class="g-follow col-lg-6" data-annotation="bubble" data-height="20" data-href="https://plus.google.com/104682548360379303861" data-rel="publisher"></div>
			<script type="text/javascript">
			(function() 
			{
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/plusone.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
			</script>
		</div>
	</div>	
	<?php
	if($adsdata['rec1Status']) 
	{
		?>
		<div class="visible-lg ad_300x250">
			<?php echo $adsdata['medRec1']; ?> 
		</div> 
		<?php   
	}
	if(enableSidebar())  
	{
		?>				     
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>You May Also Like</b></h3>
			</div>
			<div class="panel-body related-products">
				<div class="row">
					<?php
					$categoryId=getCategoryIdByPermalink($Product);
					if(sidebarCacheEnable())
					{
						$sidebarCacheExpireTime = sidebarCacheExpireTime();
						$data2=$cache->get("sidebar_$product");
						if($data2==null)
						{
							$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid`='$categoryId' AND `permalink`!='$Product' ORDER BY RAND() LIMIT ".sidebarProLimit());
							if($qry)
							{ 
								$data2 = array();
								while($row = mysql_fetch_array($qry))
								{
									$data2[] = $row;
								}
							}
							$cache->set("sidebar_$product", $data2,$sidebarCacheExpireTime);
						}
					}
					else
					{
						$qry = mysqlQuery("SELECT * FROM `products` WHERE `cid`='$categoryId' AND `permalink`!='$Product' ORDER BY RAND() LIMIT ".sidebarProLimit());
						if($qry)
						{ 
							$data2 = array();
							while($row = mysql_fetch_array($qry))
							{
								$data2[] = $row;
							}
						}
					}
					if (count($data2) > 0)
					{
						foreach($data2 as $row)
						{
							$url = $row['url'];
							if(strpos($url, '?') !== FALSE)
								$url .= "&ref=" . getEnvatoUsername();
							else
								$url .= "?ref=" . getEnvatoUsername();
							$demo_url = $row['demo'];
							if(strpos($demo_url, '?') !== FALSE)
								$demo_url .= "&ref=" . getEnvatoUsername();
							else
								$demo_url .= "?ref=" . getEnvatoUsername();
							$cache->set($row['permalink'], $row);
							$thumbnail_size  = strtolower(sidebarThumbnail());
							$pieces = explode('x', $thumbnail_size);
							echo"
							<div class='col-md-12 col-xs-6 col-sm-6'>
								<div class='purchase_btns'>
									<a href='" . rootpath() . "/product/" . $row['permalink']. ".html' class='thumbnail' style='margin-bottom: 0px;'>";
										if(getdomain($url)=='audiojungle.net')  
										{  
											echo "<img class='img-responsive center-block sidebar-img' src='" . rootpath() . "/thumb.php?id=audio&w=".trim($pieces[0])."&h=".trim($pieces[1])."' 
											class='img-responsive img-rounded' title='" . getProductDescription($row['permalink']) . "' style='margin-left: auto; margin-right: auto;'>";
										}
										else if(getdomain($url)=='activeden.net')    
										{ 
											$check=explode(".",$row['image']);  
											$extension=end($check);
											$extension=trim($extension);  
											if($extension=="swf")  
											{
												echo "<img class='img-responsive center-block sidebar-img' src='" . rootpath() . "/thumb.php?id=flash&w=".trim($pieces[0])."&h=".trim($pieces[1])."'     
												class='img-responsive img-rounded' title='" . getProductDescription($row['permalink']) . "' style='margin-left: auto; margin-right: auto;'>";
											}
											else 
											{
												echo "<img class='img-responsive center-block sidebar-img' src='" . rootpath() . "/thumb.php?id=" . urlencode($row['id']) . "&w=".trim($pieces[0])."&h=".trim($pieces[1])."' 
												class='img-responsive img-rounded' title='" . getProductDescription($row['permalink']) . "' style='margin-left: auto; margin-right: auto;'>";
											}
										}
										else
										{
											echo "<img class='img-responsive center-block sidebar-img' src='" . rootpath() . "/thumb.php?id=" . urlencode($row['id']) . "&w=".trim($pieces[0])."&h=".trim($pieces[1])."' 
											class='img-responsive img-rounded' title='" . getProductDescription($row['permalink']) . "' style='margin-left: auto; margin-right: auto;'>";
										}
										echo "<div class='purchase-btn-position'>";
										if(getdomain($url)=='videohive.net') 
										{					
											echo '<a data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><button class="btn btn-info on-h"><i class="fa fa-desktop"></i> Demo</button></a>';
											echo'<div id="myModal'.$row['id'].'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
												<div class="modal-dialog mod">
													<div class="modal-content">
														<div class="modal-header modh">
															<button type="button" class="closevideo2 close modhc" id="'.$row['id'].'" data-dismiss="modal" aria-hidden="true">&times;</button>
														</div>
														<div class="modal-body mbody">    
															<video id="demo2-'.$row['id'].'" class="video-js vjs-default-skin" controls preload="none" width="auto" height="400" data-setup="{}" poster="'.$row['image'].'">
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
												echo '<a data-backdrop="static" data-toggle="modal" data-target="#myModal'.$row['id'].'" target="_blank"><button class="btn btn-info on-h"><i class="fa fa-desktop"></i> Demo</button></a>';
												echo'<div id="myModal'.$row['id'].'" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
												<div class="modal-dialog mod">
													<div class="modal-content">
														<div class="modal-header modh">
															<button type="button" class="closevideo2 close modhc" id="'.$row['id'].'" data-dismiss="modal" aria-hidden="true">&times;</button>
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
												echo "<a target='_blank' href=".$url."><button class='btn btn-info on-h'><i class='fa fa-desktop'></i> Demo</button></a>";
											}
										}  
										else
										{					
											echo "<a target='_blank' href=".$demo_url."><button class='btn btn-info on-h'><i class='fa fa-desktop'></i> Demo</button></a>";
										}
										echo "<a target='_blank' href=".$url."><button style='margin-left:6px' class='btn btn-success on-h'><i class='fa fa-shopping-cart'></i> Buy</button></a>";
										echo "<p class='on-h'><a href='" . rootpath() . "/product/" . $row['permalink']. ".html'>".truncateDescription(strip_tags($row['description']))."</a></p>
										</div>
									</a>
									<a href='" . rootpath() . "/product/" . $row['permalink']. ".html'>
									<div class='p_b-hover'></div>
									</a>
								</div>
								<a href='" . rootpath() . "/product/" . $row['permalink']. ".html'>
									<p class='pic-title' style='margin-bottom: 20px;' align='center' title='" . getProductDescription(strip_tags($row["permalink"])). "'>" . trim_title($row['title']) . "</p>
								</a>
							</div>  
							<div class='clearfix'></div>";
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}
	if($adsdata['rec1Status']) 
	{
		?>
		<div class="visible-lg ad_300x250">
			<?php echo $adsdata['medRec1'] ?> 
		</div> 
		<?php
	} 
	?>
</div>