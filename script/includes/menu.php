<script>
$(document).ready(function(){
    $(".fly").click(function()
	{
        if($(this).siblings("li ul").hasClass('dd'))
		{
			$(this).siblings("li ul").removeClass('dd');
		}
		else
		{
			$('ul li ul').removeClass('dd');
			$(this).siblings("li ul").addClass('dd');
		}
    });
	$(".more").click(function()
	{
        if($(this).siblings("li ul").hasClass('dd'))
		{
			$(this).siblings("li ul").removeClass('dd');
		}
		else
		{
			$('ul li ul').removeClass('dd');
			$(this).siblings("li ul").addClass('dd');
		}
    });
});
</script>
<div id="sf-menu">
	<?php
	$categoriesLimit = categoriesLimit();
	$totalParentCat=mysql_num_rows(mysqlQuery("SELECT `id`,`name`,`permalink` FROM `categories` WHERE `parentId`='0'"));
	$queryParent = mysqlQuery("SELECT `id`,`name`,`permalink` FROM `categories` WHERE `parentId`='0' ORDER BY displayOrder LIMIT 0,$categoriesLimit");
	?>
	<ul id="nav" class="transition">
		<li>
			<a href="<?php echo(rootpath()); ?>/top">Top Products</a>
		</li>
		<?php
		if(mysql_num_rows($queryParent)) 
		{
			while($rowParent = mysql_fetch_array($queryParent)) 
			{
				$querySubCategories = mysqlQuery("SELECT `id`,`name`,`permalink` FROM `categories` WHERE `parentId`='".$rowParent["id"]."'");
				if (mysql_num_rows($querySubCategories)) 
				{
					echo('<li><a class="pointer fly" href="#">' . $rowParent["name"] . ' (' . countAllProducts($rowParent["id"]) . ')</a>');
				
				} 
				else 
				{
					echo('<li><a  href="' . rootpath() . '/category/' . $rowParent["permalink"] . '">' . $rowParent["name"] . ' (' . countAllProducts($rowParent["id"]) . ')</a>');
				}
				if (mysql_num_rows($querySubCategories)) 
				{
					?>
					<ul>
						<?php
						while($rowSubCategories = mysql_fetch_array($querySubCategories))  
						{
							echo ('<li><a href="' . rootpath() . '/category/' . $rowSubCategories["permalink"] . '">' . $rowSubCategories["name"] . ' (' . countProducts($rowSubCategories["id"]) . ')</a></li>');
						}
						echo ('<li><a href="' . rootpath() . '/category/' . $rowParent["permalink"] . '"> All Products</a></li>');
						?>
					</ul>
					<?php
				}
				?>
				</li>
				<?php 
			} 
		}
		if($totalParentCat > $categoriesLimit) 
		{
			?>
			<li>
				<a class="flyMore more" href="#">More</a>
				<ul class="dd">
				<?php
					$queryParent = mysqlQuery("SELECT `id`,`name`,`permalink` FROM `categories` WHERE `parentId`='0' ORDER BY `displayOrder` LIMIT $categoriesLimit,100");
					while($rowParent = mysql_fetch_array($queryParent)) 
					{
						?>
						<li>
							<a href="#" class="flyMore"><?php echo $rowParent["name"].' ('.countAllProducts($rowParent["id"]).')'?></a>
							<ul class="dd">
								<?php $querySubCategories = mysqlQuery("SELECT `id`,`name`,`permalink` FROM `categories` WHERE `parentId`='".$rowParent["id"]."'");
								while($rowSubCategories = mysql_fetch_array($querySubCategories)) 
								{
									?>
									<li><a href="<?php echo rootpath()?>/category/<?php echo $rowSubCategories["permalink"]?>"><?php echo $rowSubCategories["name"] .' ('.countProducts($rowSubCategories["id"]).')'?></a></li>
									<?php 
								} 
								?>
								<li><a href="<?php echo rootpath()?>/category/<?php echo $rowParent["permalink"]?>"> All Products</a></li>
							</ul>
						</li>
						<?php 
					} 
					?>
				</li>
			</ul>
		<?php
		}
		?>
	</ul>
</div>