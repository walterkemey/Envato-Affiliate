<?php
include "includes/header.php";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Stats: <?php echo(getTitle()) ?></title>
<script type="text/javascript" src="style/js/exporting.js" ></script>
<script type="text/javascript" src="style/js/highcharts.js" ></script>
<link rel="stylesheet" href="style/css/liteaccordion.css">	
<script>
$(document).ready(function()
{
	//week total records
	var week_total_rec=$(".week_total_rec").text();
	week_total_rec=Number(week_total_rec);
	//month total records
	var month_total_rec=$(".month_total_rec").text();
	month_total_rec=Number(month_total_rec);
	//year total records
	var year_total_rec=$(".year_total_rec").text();
	year_total_rec=Number(year_total_rec);
	//week last records
	var last=$(".total").text();
	//month last record
	var mlast=$(".m_total").text();
	//year last record
	var ylast=$(".y_total").text();
	//hide week pagination
	if(week_total_rec <=5)
	$('.week').hide();
	//hide month pagination
	if(month_total_rec <=5)
	$('.month').hide();
	//hide year pagination
	if(year_total_rec <=5)
	$('.year').hide();
	end=Number(last);
	mend=Number(mlast);
	yend=Number(ylast);
	//NEXT BUTTON
	$("#next").click(function()
	{
		var next=$(".record:last").attr("id");
		var previous=$(".record:first").attr("id");
		if(end==previous)
		$('#preload').hide();
		else
		$('#preload').show();
		last=Number(last);
		end=Number(last)+1;
		if(previous==last+1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'last':end,'id':end,'week':'week'},
				success: function(msg)
				{
					$('#preload').hide();
					$('#test').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'next':next,'id':next,'week':'week'},
				success: function(msg)
				{
					$('#preload').hide();
					$('#test').html(msg);
				}
			});
		}
	});
	//PREVIOUS BUTTON
	$("#previous").click(function()
	{
		var previous=$(".record:first").attr("id");
		var next=$(".record:last").attr("id");
		if(previous==1)
		$('#preload').hide();
		else
		$('#preload').show();
		if(previous==1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'first':previous,'id':previous,'week':'week'},
				success: function(msg)
				{
					$('#preload').hide();
					$('#test').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'previous':previous,'id':previous,'week':'week'},
				success: function(msg)
				{
					$('#preload').hide();
					$('#test').html(msg);
				}
			});
		}
	});
	//FIRST Button
	$("#first").click(function()
	{
		var previous=$(".record:first").attr("id");
		if(previous==1)
		$('#preload').hide();
		else
		$('#preload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'first':'first','week':'week'},
			success: function(msg)
			{
				$('#preload').hide();
				$('#test').html(msg);
			}
		});
	});
	//LAST BUTTON
	$("#last").click(function()
	{
		var next=$(".record:last").attr("id");
		end=Number(last)+1;
		var previous=$(".record:first").attr("id");
		if(previous==end)
		$('#preload').hide();
		else
		$('#preload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'last':end,'id':end,'week':'week'},
			success: function(msg)
			{
				$('#preload').hide();
				$('#test').html(msg);
			}
		});
	});
	//MONTH RECORDS
	//MONTH NEXT BUTTON
	$("#mnext").click(function()
	{
		var next=$(".mrecord:last").attr("id");
		var previous=$(".mrecord:first").attr("id");
		if(mend==previous)
		$('#mpreload').hide();
		else
		$('#mpreload').show();
		mlast=Number(mlast);
		mend=Number(mlast)+1;
		if(previous==mlast+1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'last':mend,'id':mend,'month':'month'},
				success: function(msg)
				{
					$('#mpreload').hide();
					$('#m_record').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'next':next,'id':next,'month':'month'},
				success: function(msg){
				$('#mpreload').hide();
				$('#m_record').html(msg);
				}
			});
		}
	});
	//MONTH PREVIOUS BUTTON
	$("#mprevious").click(function()
	{
		var previous=$(".mrecord:first").attr("id");
		var next=$(".mrecord:last").attr("id");
		if(previous==1)
		$('#mpreload').hide();
		else
		$('#mpreload').show();
		if(previous==1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'first':previous,'id':previous,'month':'month'},
				success: function(msg)
				{
					$('#mpreload').hide();
					$('#m_record').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'previous':previous,'id':previous,'month':'month'},
				success: function(msg)
				{
					$('#mpreload').hide();
					$('#m_record').html(msg);
				}
			});
		}
	});
	//MONTH FIRST Button
	$("#mfirst").click(function()
	{
		var previous=$(".mrecord:first").attr("id");
		if(previous==1)
		$('#mpreload').hide();
		else
		$('#mpreload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'first':'first','month':'month'},
			success: function(msg)
			{
				$('#mpreload').hide();
				$('#m_record').html(msg);
			}
		});
	});
	//MONTH LAST BUTTON
	$("#mlast").click(function()
	{
		var next=$(".mrecord:last").attr("id");
		mend=Number(mlast) + 1;
		var previous=$(".mrecord:first").attr("id");
		if(previous==mend)
		$('#mpreload').hide();
		else
		$('#mpreload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'last':mend,'id':mend,'month':'month'},
			success: function(msg)
			{
				$('#mpreload').hide();
				$('#m_record').html(msg);
			}
		});
	});
	//YEAR RECORDS
	//YEAR NEXT BUTTON
	$("#ynext").click(function()
	{
		var next=$(".yrecord:last").attr("id");
		var previous=$(".yrecord:first").attr("id");
		ylast=Number(ylast);
		yend=Number(ylast)+1;
		if(yend==previous)
		$('#ypreload').hide();
		else
		$('#ypreload').show();
		if(previous==ylast+1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'last':yend,'id':yend,'year':'year'},
				success: function(msg)
				{
					$('#ypreload').hide();
					$('#y_record').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'next':next,'id':next,'year':'year'},
				success: function(msg)
				{
					$('#ypreload').hide();
					$('#y_record').html(msg);
				}
			});
		}
	}); 
	//YEAR PREVIOUS BUTTON
	$("#yprevious").click(function()
	{
		var previous=$(".yrecord:first").attr("id");
		var next=$(".yrecord:last").attr("id");
		if(previous==1)
		$('#ypreload').hide();
		else
		$('#ypreload').show();
		if(previous==1)
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'first':previous,'id':previous,'year':'year'},
				success: function(msg)
				{
					$('#ypreload').hide();
					$('#y_record').html(msg);
				}
			});
		}
		else
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath();?>/admin/limits.php",
				data: {'previous':previous,'id':previous,'year':'year'},
				success: function(msg)
				{
					$('#ypreload').hide();
					$('#y_record').html(msg);
				}
			});
		}
	});
	//YEAR FIRST Button
	$("#yfirst").click(function()
	{
		var previous=$(".yrecord:first").attr("id");
		if(previous==1)
		$('#ypreload').hide();
		else
		$('#ypreload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'first':'first','year':'year'},
			success: function(msg)
			{
				$('#ypreload').hide();
				$('#y_record').html(msg);
			}
		});
	});
	//YEAR LAST BUTTON
	$("#ylast").click(function()
	{
		var next=$(".yrecord:last").attr("id");
		yend=Number(ylast) + 1;
		var previous=$(".yrecord:first").attr("id");
		if(previous==yend)
		$('#ypreload').hide(); 
		else
		$('#ypreload').show();
		$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath();?>/admin/limits.php",
			data: {'last':yend,'id':yend,'year':'year'},
			success: function(msg)
			{
				$('#ypreload').hide();
				$('#y_record').html(msg);
			}
		});
	});
});
</script>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container">
	<?php include 'includes/sidebar.php'; ?>                             
	<div class="mainy">
		<div class="page-title">
			<h2><a href="./dashboard.php"><i class="fa fa-bar-chart-o color"></i></a> Weekly Stats</h2> 
			<hr />
		</div>   
		<div class="row">                                                           
			<div class="col-md-12">
				<div class="awidget">
					<div class="awidget-head">
						<h3>Hits of Last 7 Days</h3>
					</div>
					<div class="awidget-body">
						<div class="chart-container">
							<div id="home-chart" class="chart-placeholder"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
			   <div class="awidget">
				  <div class="awidget-head">
					 <h3>Hits of This Week</h3>
				  </div>
				  <div class="awidget-body">
					 <div class="chart-container">
						<div id="video_chart" class="chart-placeholder"></div>
					 </div>
				  </div>
			   </div>
			</div>
			<div class="col-md-6">
				<div class="awidget">
					<div class="awidget-head">
						<h3>Hits of This Week</h3>
					</div>
					<?php 
					$sql = mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.permalink=p.permalink AND h.`weekUpdateDate`='".getWeekUpdateDate()."' ORDER BY h.`weeklyClicks` DESC LIMIT 5");
					$i=1; 
					?>
					<div class="awidget-body">
					<?php
					$totalCounts=mysql_num_rows(mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.permalink=p.permalink AND h.`weekUpdateDate`='".getWeekUpdateDate()."'"));
					if($totalCounts>0)
					{
						?>
						<table class="table table-hover table-bordered table-bordered">  
							<thead>
								<tr class="alert-info">
									<th>#</th>
									<th>Product</th>
									<th>Clicks</th>					
								</tr>
							</thead>
							<tbody id="test">
								<div class="total" style="display:none"><?php echo lastId($totalCounts);?></div><div class="week_total_rec" style="display:none"><?php echo $totalCounts;?></div>
								<?php
								while($row = mysql_fetch_array($sql))
								{
									$permalink=$row['permalink'];
									$clicks=$row['weeklyClicks'];
									$pro=mysqlQuery("SELECT `title` FROM `products` where `permalink`='$permalink'");
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
									$i++;
									}
								}
							?>
							</tbody>
					   </table>
						<!--Next Previous Button-->
						<div class="centerd week">
							<ul class="pagination">
								<li  id="first" class="first"><a>FIRST</a></li>
								<li id="previous" class="previous"><a>&laquo;</a></li>
								<li id="next" class="next"><a>&raquo;</a></li>
								<li id="last" class="last"><a>LAST</a></li>
							</ul>
							<div id="preload" class="load" style="display:none">
								<img src="<?php echo rootpath()?>/admin/style/img/fb.gif" width="40%">
							</div>
						</div>
					<?php
					}
					else
					{
						echo ('<div style="padding-top:16px;padding-bottom:30px;text-align:center"><h3>No Products Found</h3></div>');
					}
					?>
					</div>
				</div>
			</div>
		</div>
		<div class="page-title">
			<h2><i class="fa fa-bar-chart-o color"></i> Monthly Stats</h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="awidget">
					<div class="awidget-head">
						<h3>Hits of This Month</h3>
					</div>
					<div class="awidget-body">
						<div class="chart-container">
							<div id="total_month_chart" class="chart-placeholder"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="awidget">
					<div class="awidget-head">
						<h3>Top Products of This Month</h3>
					</div>
					<?php 
					$sql = mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.permalink=p.permalink AND h.`monthUpdateDate`='".getMonthUpdateDate() ."' ORDER BY h.`monthlyClicks` DESC LIMIT 5");
					$i=1;
					?>
					<div class="awidget-body">
						<?php
						$mTotalCounts=mysql_num_rows(mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.permalink=p.permalink AND h.`monthUpdateDate`='".getMonthUpdateDate() ."'"));
						if($mTotalCounts>0)
						{
							?>
							<table class="table table-hover table-bordered table-bordered" style="margin-top:0px;">							  
								<thead>
									<tr class="alert-info">
										<th>#</th>
										<th>Product</th>
										<th>Clicks</th>
																		
									</tr>
								</thead>
								<tbody id="m_record">
									<div class="m_total" style="display:none">
										<?php echo lastId($mTotalCounts);?>
									</div>
									<div class="month_total_rec" style="display:none">
										<?php echo $mTotalCounts;?>
									</div>
									<?php
									while($row = mysql_fetch_array($sql))
									{
										$permalink=$row['permalink'];
										$clicks=$row['monthlyClicks'];
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
											$i++;
										}
									}
								?>
								</tbody>
						   </table>
							<div class="centerd month">
								<ul class="pagination">
									<li class="first" id="mfirst"><a>FIRST</a></li>
									<li id="mprevious" class="previous"><a>&laquo;</a></li>
									<li id="mnext" class="next"><a>&raquo;</a></li>
									<li id="mlast" class="last"><a>LAST</a></li>
								</ul>
								<div id="mpreload" class="load" style="display:none">
									<img src="<?php echo rootpath()?>/admin/style/img/fb.gif" width="40%">
								</div>
							</div> 
						<?php
						}
						else
						{
							echo ('<div style="padding-top:16px;padding-bottom:30px;text-align:center"><h3>No Products Found</h3></div>');
						}
						?>
					</div>
				</div>
			</div>
		</div>
		<div class="page-title">
			<h2><i class="fa fa-bar-chart-o color"></i> All Time Stats</h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-6">										
				<div class="awidget">
					<div class="awidget-head">
						<h3>All Time Hits</h3>
					</div>
					<div class="awidget-body">
						<div class="chart-container">
							<div id="total_chart" class="chart-placeholder"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="awidget">
					<div class="awidget-head">
						<h3>Top Products of All Time</h3>
					</div>
					<?php 
					$sql = mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink` ORDER BY h.`alltimeClicks` DESC LIMIT 5");
					$i=1;
					?>
					<div class="awidget-body">
						<?php
						$yTotalCounts=mysql_num_rows(mysqlQuery("SELECT h.*,p.* FROM `hotProducts` h,`products` p WHERE h.`permalink`=p.`permalink`"));
						if($yTotalCounts>0)
						{
							?>
							<table class="table table-hover table-bordered table-bordered">
								<thead>
									<tr class="alert-info">
										<th>#</th>
										<th>Product</th>
										<th>Clicks</th>
																		
									</tr>
								</thead>
								<tbody id="y_record">
									<div class="y_total" style="display:none">
										<?php echo lastId($yTotalCounts);?>
									</div>
									<div class="year_total_rec" style="display:none">
										<?php echo $yTotalCounts;?>
									</div>
									<?php
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
											$i++;
										}
									}
								?>
								</tbody>
						   </table>
							<div class="centerd year">
								<ul class="pagination">
									<li class="first" id="yfirst"><a>FIRST</a>
									</li><li  id="yprevious" class="previous"><a>&laquo;</a></li>
									<li id="ynext" class="next"><a>&raquo;</a></li>
									<li id="ylast" class="last"><a>LAST</a></li>
								</ul>
								<div id="ypreload" class="load" style="display:none">
									<img src="<?php echo rootpath()?>/admin/style/img/fb.gif" width="40%">
								</div>
							</div>
						<?php
						}
						else
						{
							echo ('<div style="padding-top:16px;padding-bottom:30px;text-align:center"><h3>No Products Found</h3></div>');
						}
						?>
					</div>
				</div>
			</div>	
		</div>
	</div>
<div class="clearfix"></div>   
</div>	
<?php include 'includes/footer.php'; ?> 
<!--1.1--Views,unique hits and total downloads-->
<?php
$sql = @mysqlQuery("SELECT `pageViews`,`uniqueHits`,`clicks`,`datetime` FROM `stats` WHERE `datetime` >= CURDATE() - INTERVAL 6 DAY ORDER BY `datetime` ASC");
$countValues = 0;
while ($result = @mysql_fetch_array($sql))
{
	$views[] = $result['pageViews'];
	$hits[] = $result['uniqueHits'];
	$download[] = $result['clicks'];
	$date[] = $result['datetime'];
	$countValues+= 1;
}
$viewarray = array(
	0 => (int)$views[0],
	1 => (int)$views[1],
	2 => (int)$views[2],
	3 => (int)$views[3],
	4 => (int)$views[4],
	5 => (int)$views[5],
	6 => (int)$views[6]
);
$hitsarray = array(
	0 => (int)$hits[0],
	1 => (int)$hits[1],
	2 => (int)$hits[2],
	3 => (int)$hits[3],
	4 => (int)$hits[4],
	5 => (int)$hits[5],
	6 => (int)$hits[6]
);
$downloadarray = array(
	0 => (int)$download[0],
	1 => (int)$download[1],
	2 => (int)$download[2],
	3 => (int)$download[3],
	4 => (int)$download[4],
	5 => (int)$download[5],
	6 => (int)$download[6]
);
if ($countValues > 0)
{
	?>
	<script type="text/javascript">
		var vArray= <?php echo json_encode($viewarray); ?>;
		var dArray= <?php echo json_encode($date); ?>;	
		var hArray= <?php echo json_encode($hitsarray); ?>;	
		var dsArray= <?php echo json_encode($downloadarray); ?>			
	</script>
	<script type="text/javascript">
    $(function () {
    		$('#home-chart').highcharts({
    			chart: {
    				type: 'column'
    			},
    			title: {
    				text: ''
    			},
    			subtitle: {
    				text: ''
    			},
    			xAxis: {
    				categories: [
    					dArray[0],
    					dArray[1],
    					dArray[2],
    					dArray[3],
    					dArray[4],
    					dArray[5],
    					dArray[6]
    				]
    			},
    			credits: { enabled: false },
    			yAxis: {
    				min: 0,
    				title: {
    					text: 'Total Value'
    				}
    			},
    			tooltip: {
    				headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
    				pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
    					'<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
    				footerFormat: '</table>',
    				shared: true,
    				useHTML: true
    			},
    			plotOptions: {
    				column: {
    					pointPadding: 0.2,
    					borderWidth: 0
    				}
    			},
    			series: [{
    				name: 'Views',
    				data: [vArray[0],vArray[1],vArray[2],vArray[3],vArray[4],vArray[5],vArray[6]]
    	
    			}, {
    				name: 'Unique Hits',
    				data: [hArray[0],hArray[1],hArray[2],hArray[3],hArray[4],hArray[5],hArray[6]]
    	
    			},{
    				name: 'Clicks',
    				data: [dsArray[0],dsArray[1],dsArray[2],dsArray[3],dsArray[4],dsArray[5],dsArray[6]]
    	
    			}]
    		});
    	});   
  </script>
  <!--1.2--This Week Total Calculation graphs of Hits,views,downloads-->
  <?php
}
else
{
	?>
	<script>
		$("#home-chart").html("<div style='position: absolute; left: 44%; top: 50%;'><h4>No Downloads in this week</h4></div>");
	</script>
	<?php
}
$sql = @mysqlQuery("SELECT SUM(pageViews) AS `pageViews`,SUM(uniqueHits) AS `uniqueHits`,SUM(clicks) AS `clicks` ,`datetime` FROM `stats` WHERE `datetime` >= CURDATE() - INTERVAL 6 DAY ORDER BY `datetime` ASC");
$count_value = 0;
while($result_total_week=@mysql_fetch_array($sql))
{
	$total_week[0]= $result_total_week['pageViews'];
	$total_week[1]= $result_total_week['uniqueHits'];
	$total_week[2] = $result_total_week['clicks'];
	$count_value +=$result_total_week['pageViews']+$result_total_week['uniqueHits']+$result_total_week['clicks']; 
} 
$totalarray_week = array(
	0 => (int)$total_week[0], 
	1 => (int)$total_week[1], 
	2 => (int)$total_week[2],
					  
);
if($count_value>0) 
{
	?>
	<script type="text/javascript">
	var t_week_Array= <?php echo json_encode($totalarray_week); ?>;
	$(function () {
		var chart;
		$(document).ready(function () {
			
			// Build the chart
			$('#video_chart').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
				},
				credits: { enabled: false },
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: [{
					type: 'pie',
					name: 'Value',
					data: [
						['Views',       t_week_Array[0] ],				
						['Unique Visitors',   t_week_Array[1] ],
						['Clicks',       t_week_Array[2] ]
					]
				}]
			});
		});
		
	});
	</script>
	<!--1.3--Videos Downloads This Week-->	
	<?php
}
else 
{ 
	?>
	<script>
		$("#video_chart").html("<div style='position: absolute; left: 38%; top: 50%;'><h4>No Clicks in this Week</h4></div>");
	</script>
	<?php 
}
$count_value = 0;
$sql_total_month = @mysqlQuery("SELECT SUM(pageViews) AS `pageViews`,SUM(uniqueHits) AS `uniqueHits`,SUM(clicks) AS `clicks`,`datetime` FROM `stats` WHERE YEAR(datetime) = YEAR(CURDATE()) AND MONTH(datetime) = MONTH(CURDATE()) ");
while($result_total_month=@mysql_fetch_array($sql_total_month))
{
	$total_month[0]= $result_total_month['pageViews'];
	$total_month[1]= $result_total_month['uniqueHits'];
	$total_month[2] = $result_total_month['clicks'];
	$count_value += $result_total_month['pageViews']+$result_total_month['uniqueHits']+$result_total_month['clicks'];
}
$totalarray_month = array(
	0 => (int)$total_month[0], 
	1 => (int)$total_month[1], 
	2 => (int)$total_month[2],  				  
);
if($count_value>0)
{
	?>
	<script type="text/javascript">
	var t_month_Array= <?php echo json_encode($totalarray_month); ?>;
	$(function () {
		var chart;
		$(document).ready(function () {
			
			// Build the chart
			$('#total_month_chart').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
				},
				credits: { enabled: false },
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				series: [{
					type: 'pie',
					name: 'Value',
					data: [
						
						['Views',       t_month_Array[0] ],                   
						['Unique Visitors',   t_month_Array[1] ],
						['Clicks',   t_month_Array[2] ]
					]
				}]
			});
		});
		
	});
	</script>
	<!--2.2 Videos Stats monthly-->	 
	<?php
} 
else
{
	?>
	<script>
		$("#total_month_chart").html("<div style='position: absolute; left: 38%; top: 50%;'><h4>No Records Found</h4></div>");
	</script>
	<?php
}
$sql_total = @mysqlQuery("SELECT SUM(pageViews) AS pageViews,SUM(uniqueHits) AS `uniqueHits`,SUM(clicks) AS `clicks` FROM stats");
$count_vals = 0;
while($result_total=@mysql_fetch_array($sql_total))
{
	$total[0]= $result_total['pageViews'];
	$total[1]= $result_total['uniqueHits'];
	$total[2]= $result_total['clicks'];
	$count_vals +=$result_total['pageViews']+$result_total['uniqueHits']+$result_total['clicks'];
}
$totalarray = array(
	0 => (int)$total[0], 
	1 => (int)$total[1],
	2 => (int)$total[2],
					  
);
if($count_vals>0) 
{ 
	?>
	<script type="text/javascript">
	var tArray= <?php echo json_encode($totalarray); ?>;
	$(function () {
		var chart;
		
		$(document).ready(function () {
			
			// Build the chart
			$('#total_chart').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: ''
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				credits: { enabled: false },
				series: [{
					type: 'pie',
					name: 'Value',
					data: [
						
						['Views',       tArray[0] ],                    
						['Unique Visitors',   tArray[1] ],   
						['Clicks',   tArray[2] ]
					]
				}]
			});
		});
		
	});
	</script>
	<?php 
} 
else 
{ 
	?>
	<script>
		$("#total_chart").html("<div style='position: absolute; left: 38%; top: 50%;'><h4>No Records Found</h4></div>");
	</script>
	<?php 
} 
function lastId($totalRecords) 
{
	if($totalRecords%5==0) 
	{
		return $totalRecords-5;
	}
	else 
	{
		$val=floor($totalRecords/5);
		return $val * 5;
	}
}
?> 