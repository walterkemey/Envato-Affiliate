</head>
<body>

<div id="wrap">  
<form>
	<div class="navbar navbar-default navbar-static-top hg"> 
		<div class="container">
		<?php $SearchResult=str_replace("-"," ",$this->Search);?>
			<div class="navbar-header">
				<a href="<?php echo(rootpath()); ?>/" class="navbar-brand"><img class="image_logo" src="<?php echo(rootpath() . "/style/images/" . getLogo()); ?>" alt="logo" onclick="s_h('navbar-main');" /></a>
				<button class="navbar-toggle" type="button" onclick="s_h('navbar-main');">
					<i class="fa fa-search 3x"></i>
				</button>
			</div>
			<?php if(!isset($_SESSION))
				session_start();
				if(!isset($_SESSION['selectedCategory']))
				$_SESSION['selectedCategory']='all';
				else if($SearchCategory !="")
				$_SESSION['selectedCategory']=$SearchCategory;
			?>
			<div class="row search-div hidden-sm hidden-md hidden-lg" id="navbar-main" style="display: none;">
				<div class="searchbar">
					<div class="col-lg-6">
					<div class="input-group">
						<?php if(isset($SearchResult)){ ?>
						<input spellcheck="false" autocomplete="off" dir="auto" data-provide="typeahead" class="typeahead tt-query search_box form-control mobileFeild" type="search" name="search" value="<?php echo(strip_tags($SearchResult)) ?>" placeholder="<?php echo($lang_array['searchbox_placeholder']) ?>"  id="mobileSearch" required />
						<span class="input-group-addon category-dropdown rowElem">
							<select name="select" id="selectmobileCategory">
								<?php echo showParentCategories($_SESSION['selectedCategory'])?>
							</select>
						</span>
						<span id="mobileFeild" class="input-group-addon clickMe"><i class="fa fa-search"></i></span>
						<?php }						
						else {
						?> 
						<input spellcheck="false" autocomplete="off" dir="auto" data-provide="typeahead" class="typeahead tt-query search_box form-control mobileFeild" type="search" name="search" placeholder="<?php echo($lang_array['searchbox_placeholder']) ?>" required id="mobileSearch"/>
						<span class="input-group-addon category-dropdown rowElem">
							<select name="select" id="selectmobileCategory">
								<?php echo showParentCategories($_SESSION['selectedCategory'])?>
							</select>
						</span>
						<span id="mobileFeild" class="input-group-addon clickMe"><i class="fa fa-search"></i></span>
						<?php } ?>
					</div>
					</div><!-- /.col-lg-6 -->
				</div>
			</div>
			
			<div class="hidden-xs">
				<div class="col-lg-6 col-xs-12 col-sm-8 col-md-5 right">
					<div class="searchbar-margin">
						
						<div class="row">
							<div class="col-xs-12">
								<div class="input-group">
									<?php if(isset($SearchResult)){ ?>
									<input spellcheck="false" autocomplete="off" dir="auto" data-provide="typeahead" class="typeahead tt-query search_box form-control systemFeild" type="search" name="search" value="<?php echo(strip_tags($SearchResult)) ?>" id="systemSearch" placeholder="<?php echo($lang_array['searchbox_placeholder']) ?>"  required />
									<span class="input-group-addon category-dropdown rowElem">
										<select name="select" id="selectsystemCategory">
											<?php echo showParentCategories($_SESSION['selectedCategory'])?>
										</select>
									</span>
									<span id="systemFeild" class="input-group-addon clickMe"><i class="fa fa-search"></i></span>
									<?php }						
									else {
									?> 
									<input spellcheck="false" autocomplete="off" dir="auto" data-provide="typeahead" class="typeahead tt-query search_box form-control systemFeild" type="search" name="search" placeholder="<?php echo($lang_array['searchbox_placeholder']) ?>" id="systemSearch" required />
									<span class="input-group-addon category-dropdown rowElem">
										<select name="select" id="selectsystemCategory">
											<?php echo showParentCategories($_SESSION['selectedCategory'])?>
										</select>
									</span>
									<span id="systemFeild" class="input-group-addon clickMe"><i class="fa fa-search"></i></span>
									<?php } ?>
								</div>
							</form>
							</div><!-- /.col-lg-6 -->
						</div>
						
					</div>
				</div>
			<div class="social-icons navbar-catright hidden-sm" style="margin-top:10px;">
				<ul>
					<?php
					if(socialStatus())
					{
						?>
						<li class="twitter">
							<a href="https://www.twitter.com/<?php echo(getTwitter())?>" target="_blank">Twitter</a>
						</li>

						<li class="facebook">
							<a href="https://www.facebook.com/<?php echo(getFacebook())?>" target="_blank">Facebook</a>
						</li>

						<li class="googleplus">
							<a href="https://plus.google.com/<?php echo(getGoogle())?>" target="_blank">Google+</a>
						</li>

						<li class="pinterest">
							<a href="https://www.pinterest.com/<?php echo(getPinterest())?>" target="_blank">Pinterest</a>
						</li>
						<?php
					}
					if(rssEnable())	
					{						
						?>
						
						<li class="rss">
						<?php
						if ($Type=='recent' && rssRecentEnable())
						{
						?>
						<a href="<?php echo(rootpath()); ?>/rss/<?php echo $Type?>" target="_blank">RSS</a>
						<?php
						}
						else if ($Type=='top' && rssTopEnable())
						{
						?>
						<a href="<?php echo(rootpath()); ?>/rss/<?php echo $Type?>/<?php echo mres($this->sortBy);?>" target="_blank">RSS</a>
						<?php
						}
						else if ($Type=='category' && $this->categoryName!="" && rssCatEnable())
						{
						?>
						<a href="<?php echo(rootpath()); ?>/rss/<?php echo $Type?>/<?php echo mres($this->categoryName);?>" target="_blank">RSS</a>
						<?php
						}
						else if ($Type=='tag' && $this->tagName!="" && rssTagEnable())
						{
						?>
						<a href="<?php echo(rootpath()); ?>/rss/<?php echo $Type?>/<?php echo mres($this->tagName);?>" target="_blank">RSS</a>
						<?php
						}
						else {
						?>
						<a href="<?php echo(rootpath()) ?>/rss/" target="_blank">RSS</a>
						<?php
						}
						?>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			</div>
		</div>
	</div>
	</form>
			<div class="clearfix"></div> 
			
			<div class="container">
				<div class="row">
					<div class="col-xs-12">
						<div class="bars-btn">
							<button type="button" class="pull-right" onclick="s_h('sf-menu');">
								<i class="fa fa-bars"></i>
							</button>
						</div>
					</div>
				</div>
				<?php include 'includes/menu.php'?>
			</div>

			<div class="clearfix"></div>
				<br>
				<br>
				<div class="container">
				<div id="main">
				<div class="clearfix"></div>