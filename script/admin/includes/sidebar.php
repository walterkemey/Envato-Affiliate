<?php
function countTotalProducts()
{
	$query = mysqlQuery("SELECT count(id) as total FROM products");
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function countCategory()
{
	$query = mysqlQuery("SELECT count(id) as totalCategory FROM `categories` WHERE `parentId`='0'");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalCategory'];
}
function totalPagesCount()
{
	$query = mysqlQuery("SELECT count(id) as totalPages FROM `pages`");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalPages'];
}
function totalSourcesCount()
{
	$query = mysqlQuery("SELECT count(id) as totalFeeds FROM envatoSources");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalFeeds'];
}
?> 
<div class="sidebar-dropdown"><a href="#">MENU</a></div>
<div class="sidey">
	<div class="side-cont">
		<ul class="nav">
			<?php 
			if(basename($_SERVER['PHP_SELF'])=="dashboard.php")
			{ 
				?>
				<li class="current"><a href="dashboard.php">
				<i class="fa fa-bar-chart-o"></i> Website Stats</a></li>
				<?php 
			} 
			else 
			{ 
				?>
				<li ><a href="dashboard.php">
				<i class="fa fa-bar-chart-o"></i> Website Stats</a></li>
				<?php 
			} 
			?>

			<?php 
			if(basename($_SERVER['PHP_SELF'])=="add_product.php" || 
			basename($_SERVER['PHP_SELF'])=="products.php" || basename($_SERVER['PHP_SELF'])=="update_product.php")
			{ 
				?> 
				<li class="has_submenu open">
				<?php 
			} 
			else 
			{ 
				?>
				<li class="has_submenu">
				<?php 
			}  
			?>
			<a href="#">
				<i class="fa fa-th"></i> Products
				<span class="caret pull-right"></span>
			</a>
			<!-- Sub menu -->
			<ul>
				<li>
					<a href="add_product.php"><i class="fa fa-plus-circle"></i> Bulk Add Products</a>
				</li>
				<li>
					<a href="update_product.php"><i class="fa fa-check-square-o"></i> Bulk Update Products</a>
				</li>
				<li>
					<a href="products.php"> <i class="fa fa-th"></i><span id="allProducts"> All Products (<?php echo(countTotalProducts()) ?>)</span></a>
				</li> 
			</ul>
			</li>


			<?php if(basename($_SERVER['PHP_SELF'])=="categories.php" || basename($_SERVER['PHP_SELF'])=="addCategory.php")
			{ 
				?> 
				<li class="has_submenu open">
				<?php 
			} 
			else 
			{ 
				?>
				<li class="has_submenu">
				<?php 
			}  
			?>
			<a href="#">
				<i class="fa fa-folder-open"></i> Categories
				<span class="caret pull-right"></span>
			</a>
			<!-- Sub menu -->
			<ul>
				<li>
					<a href="addCategory.php"><i class="fa fa-plus-circle"></i> Add Category </a>
				</li>
				<li>
					<a href="categories.php"><i class="fa fa-folder-open"></i> All Categories (<?php echo(countCategory()) ?>)</a>
				</li>				
			</ul>
			</li>


			<?php 
			if(basename($_SERVER['PHP_SELF'])=="add_page.php" || 
			basename($_SERVER['PHP_SELF'])=="pages.php")
			{ 
				?> 
				<li class="has_submenu open">
				<?php 
			} 
			else 
			{ 
				?>
				<li class="has_submenu">
				<?php 
			}  
			?>
			<a href="#">
				<i class="fa fa-file"></i> Pages
				<span class="caret pull-right"></span>
			</a>
			<!-- Sub menu -->
			<ul>
				<li>
					<a href="add_page.php"><i class="fa fa-plus-circle"></i> Add Page</a>
				</li>
				<li>
					<a href="pages.php"><i class="fa fa-file"></i> All Pages (<?php echo(totalPagesCount()) ?>)</a>
				</li> 
			</ul>
			</li>

			<?php 
			if(basename($_SERVER['PHP_SELF'])=="sources.php" || basename($_SERVER['PHP_SELF'])=="addSource.php")
			{ 
				?> 
				<li class="has_submenu open">
				<?php 
			} 
			else 
			{ 
				?>
				<li class="has_submenu">
				<?php 
			}  
			?>
				<a href="#">
				<i class="fa fa-leaf"></i> Envato Sources
				<span class="caret pull-right"></span>
			</a>
			<!-- Sub menu -->
			<ul>
				<li>
					<a href="addSource.php"><i class="fa fa-leaf"></i> Add Source </a>  
				</li>
				<li>
					<a href="sources.php"><i class="fa fa-leaf"></i> All Sources (<?php echo(totalSourcesCount()) ?>)</a>
				</li>
			</ul>
			</li>

			<?php 
			if(basename($_SERVER['PHP_SELF'])=="settings.php" || basename($_SERVER['PHP_SELF'])=="user.php" || basename($_SERVER['PHP_SELF'])=="social.php" || basename($_SERVER['PHP_SELF'])=="currency.php"|| basename($_SERVER['PHP_SELF'])=="manageCaptcha.php"|| basename($_SERVER['PHP_SELF'])=="ads.php"|| basename($_SERVER['PHP_SELF'])=="media.php"|| basename($_SERVER['PHP_SELF'])=="manageComments.php" || basename($_SERVER['PHP_SELF'])=="sitemaps.php" || basename($_SERVER['PHP_SELF'])=="rss-setting.php" || basename($_SERVER['PHP_SELF'])=="cache.php") 
			{
				?> 
				<li class="has_submenu open">
				<?php 
			} 
			else 
			{ 
				?>
				<li class="has_submenu">
				<?php 
			}  
			?>
			<a href="#">
				<i class="fa fa-cogs"></i> Website Settings
				<span class="caret pull-right"></span>
			</a>
			<!-- Sub menu -->
			<ul>
				<li>
					<a href="settings.php"><i class="fa fa-cog"></i> General Settings</a>
				</li> 
				<li>	
					<a href="user.php"><i class="fa fa-user"></i> Login Details</a>
				</li>
				<li>
					<a href="social.php"><i class="fa fa-group"></i> Social Profiles</a>
				</li>
				<li>
					<a href="currency.php"><i class="fa fa-dollar"></i> Currency Settings</a>
				</li>
				<li>
					<a href="manageCaptcha.php"><i class="fa fa-eye-slash"></i> Captcha Settings</a>
				</li>
				<li>
					<a href="cache.php"><i class="fa fa-barcode"></i>  Cache Settings</a>
				</li>
				<li>
					<a href="manageComments.php"><i class="fa fa-comments"></i> Comments Settings</a>
				</li>
				<li>
					<a href="ads.php"><i class="fa fa-code"></i> Ad Management</a>
				</li>
				<li>
					<a href="media.php"><i class=" fa fa-caret-square-o-right"></i> Media Settings</a>
				</li>
				<li>
					<a href="sitemaps.php"><i class="fa fa-sitemap"></i> Sitemaps</a>
				</li>
				<li>
					<a href="rss-setting.php"><i class="fa fa-rss"></i> RSS-Settings</a>
				</li>
			</ul>
			</li>
			<li>
				<a href="logout.php"><i class="fa fa-power-off"></i>Logout</a>
			</li>
		</ul>
	</div>
</div>