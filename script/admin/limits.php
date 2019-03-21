<?php
include '../config/config.php';
include '../includes/functions.php';
$limit=5;
if(isset($_POST['week']))
{
	if(isset($_POST['next'])) 
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit);	
	}
	else if(isset($_POST['previous'])) 
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit);
	}
	else if(isset($_POST['first'])) 
	{
		$i=1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' ORDER BY h.`weeklyClicks` DESC LIMIT 0," . $limit);
	}
	else if(isset($_POST['last'])) 
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit);
	}
	while($row = mysql_fetch_array($sql))
	{
		$permalink=$row['permalink'];
		$clicks=$row['weeklyClicks'];
		$pro=mysqlQuery("SELECT `title` FROM `products` WHERE `permalink`='$permalink'");
		$proResult=mysql_fetch_array($pro);
		if($proResult['title'] !="")
		{
			?>
			<tr class="record" id="<?php echo $i?>">
				<td><?php echo $i?></td>
				<td>
					<a href="<?php echo(rootpath() . '/product/' . $permalink . '.html')?>">
						<?php
						if (strlen($proResult['title']) > 37) 
						{
							$stringCut = substr($proResult['title'], 0, 37);
							$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
						}
						echo $proResult['title'] ?>
					</a>
				</td>
				<td><?php echo number_format($clicks) ?></td>
			</tr>
			<?php
		}
		$i++;
	}
}
else if(isset($_POST['month'])) 
{
	if(isset($_POST['next'])) 
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`monthUpdateDate`='".getMonthUpdateDate() ."' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit);
	}
	else if(isset($_POST['previous'])) 
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`monthUpdateDate`='".getMonthUpdateDate() ."' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit);
	}
	else if(isset($_POST['first'])) 
	{
		$i=1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`monthUpdateDate`='".getMonthUpdateDate() ."' ORDER BY h.`monthlyClicks` DESC LIMIT 0," . $limit);
	}
	else if(isset($_POST['last'])) 
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` AND h.`monthUpdateDate`='".getMonthUpdateDate() ."' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit);
	}
	while($row = mysql_fetch_array($sql))
	{		
		$permalink=$row['permalink'];
		echo $clicks=$row['weeklyClicks'];
		$pro=mysqlQuery("SELECT `title` FROM `products` WHERE `permalink`='$permalink'");
		$proResult=mysql_fetch_array($pro);
		if($proResult['title'] !="")
		{
		?>
		<tr class="mrecord" id="<?php echo $i?>">
			<td><?php echo $i?></td>
			<td>
				<a href="<?php echo(rootpath() . '/product/' . $permalink . '.html')?>">
					<?php
					if (strlen($proResult['title']) > 37) 
					{
						$stringCut = substr($proResult['title'], 0, 37);
						$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
					}
					echo $proResult['title'] ?>
				</a>
			</td>
			<td><?php echo number_format($clicks) ?></td>
		</tr>
		<?php
		}
		$i++;
	}
}
else if(isset($_POST['year']))
{
	if(isset($_POST['next'])) 
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," .$limit);	
	}
	else if(isset($_POST['previous'])) 
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," .$limit);
	}
	else if(isset($_POST['first'])) 
	{
		$i=1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` ORDER BY h.`alltimeClicks` DESC LIMIT 0," . $limit);
	}
	else if(isset($_POST['last'])) 
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$sql= mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," .$limit);
	}
	while($row = mysql_fetch_array($sql))
	{
		$permalink=$row['permalink'];
		$clicks=$row['alltimeClicks'];
		$pro=mysqlQuery("SELECT `title` FROM `products` WHERE `permalink`='$permalink'");
		$proResult=mysql_fetch_array($pro);
		if($proResult['title'] !="")
		{
			?>
			<tr class="yrecord" id="<?php echo $i?>">
				<td><?php echo $i?></td>
				<td>
					<a href="<?php echo(rootpath() . '/product/' . $permalink . '.html')?>">
						<?php
						if (strlen($proResult['title']) > 37) 
						{
							$stringCut = substr($proResult['title'], 0, 37);
							$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
						}
						echo $proResult['title'] ?>
					</a>
				</td>
				<td><?php echo number_format($clicks) ?></td>
			</tr>
			<?php
		}
		$i++;
	}
}
?>