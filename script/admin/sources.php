<?php
ini_set('max_execution_time', 600);
include 'includes/header.php';
include 'includes/populate_categories.php"';
$error = "";
$log = "";
$isDeleted = false;
function deleteRss($id)
{
	$query = "DELETE FROM `envatoSources` WHERE `id`=".$id;
	mysqlQuery($query);
}
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	deleteRss($_GET['delete']);
	$isDeleted = true;
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Envato RSS Sources: <?php echo(getTitle()) ?></title>
<script type="text/javascript">
	$( document ).ready(function() {
		$( "#deleterss" ).hide();
	});
</script>
<style>
#post {
	font-family: FontAwesome, 'Helvetica Neue', Helvetica, Arial, sans-serif;
}
</style>
</head>    
<body>
<script type="text/javascript">
	$( document ).ready(function() 
	{
		$("#post").click(function() 
		{  
			var count_checked1 = $("[name='checkboxvar[]']:checked").length; // count the checked
			if(count_checked1 == 0) 
			{
				alert("Please Select Some Sources to post.");
				return false;
			}
			else
			{
				$('.wobblebar').show(); 
				$("[name='checkboxvar[]']:checked").each(function()
				{  
					var postId = $(this).val();
					$.ajax
					({
						type:"POST",
						url:"postSource.php",   
						data:{id:postId},
						success:function(result)
						{   
							var urls = result.split("-");
							cid=urls.pop();
							$.each(urls, function(index,url)
							{
								$.ajax
								({
									type:"POST",
									url:"postSource.php",   
									data:{URL:url,CID:cid},
									success:function(result)
									{   
										var results = result.split("_");
										$(".result").append(results[0]); 
										$('#allProducts').text(" All Products ("+results[1]+")"); 
									}
								});
							});
						}
					});
				});
			}
		});
	});
</script>
<script type='text/javascript'> 
$(document).ready(function () 
{
	$('.wobblebar').hide();
	$( document ).ajaxStop(function() 
	{
		$('.wobblebar').hide();
	});
	$('.getsource').click(function()
	{
		$(".result").html("");
		var postId=$(this).attr('id');
		$(this).attr("disabled", true);
		$('.wobblebar').show();
		$.ajax
		({
			type:"POST",
			url:"postSource.php",   
			data:{id:postId},
			success:function(result)
			{   
				var urls = result.split("-");
				cid=urls.pop();
				$.each(urls, function(index,url)
				{
					$.ajax
					({
						type:"POST",
						url:"postSource.php",   
						data:{URL:url,CID:cid},
						success:function(result)
						{   
							var results = result.split("_");
							$(".result").append(results[0]); 
							$('#allProducts').text(" All Products ("+results[1]+")"); 
						}
					});
				});
			}
		});
	});
	$('#myform').on('submit',(function(e)
	{  
		$(".error").hide();
		e.preventDefault();
		$.ajax
		({
			type:"POST",
			url:"add_source.php",   
			data:new FormData(this),    
			contentType: false,
			cache :false,  
			processData:false, 
			success:function(result)
			{  
				var result=result.trim();
				if(result=='skip')
				$(".error").show();
				else {  
					$(".success").show();
					location.reload();	
				}				
			}
		});
	}));  
	$('#source').click(function()
	{
		$('#md').show();
	});  
	$('#close').click(function()
	{
		$('#md').hide();  
		$(".error").hide();
	});
	$('#selectall').click(function () 
	{   
		if(this.checked)
		$('#post').show();
		else
		$('#post').hide();
		$('.selectedId').prop('checked', this.checked);
	});
	$('.selectedId').change(function () 
	{
	    var countSelected=$('.selectedId').filter(":checked").length;
	    if(countSelected > 0)
	    $('#post').show();
		else
		$('#post').hide();
		var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
		$('#selectall').prop("checked", check);
	});
});
</script>
<script type="text/javascript">
$( document ).ready(function() 
{
	$( "#addrss" ).hide();
});
</script>
<script type="text/javascript">
jQuery(document).ready(function()
{
	jQuery("select[name='website']").change(function()
	{            
		var optionValue = jQuery("select[name='website']").val();         
		jQuery.ajax
		({
			type: "POST",
			url: "./includes/populate_categories.php",
			data: ({website: optionValue}),
			success: function(response)
			{
				jQuery("#ajax_categories").html('');
				jQuery("#ajax_categories").html(response);
			}
		});        
	});
});
</script>
<script type="text/javascript">
jQuery(document).ready(function()
{             
	var optionValue = jQuery("select[name='website']").val();    
	jQuery.ajax
	({
		type: "POST",
		url: "./includes/populate_categories.php",
		data: ({website: optionValue}),
		success: function(response)
		{
			jQuery("#ajax_categories").html('');
			jQuery("#ajax_categories").html(response);
		}
	});        
});
</script>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-leaf color"></i> Envato Sources </h2>
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<form method="post" action="sources.php">
						<div class="awidget-head">
							<h3>Envato Sources</h3>
							<?php
							if($isDeleted)
							{
								$message = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Source Deleted Successfully</b></div>";
								echo $message;
							}
							?>
							<div class="btn-right-group pull-right">
								<a id="source" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Source</a>
								<a id="post" name="post" style="display:none" class='btn btn-success' title="Post From Selected Sources">&#xf00c; Post Selected</a>
							</div>
						</div> 
						<div class="awidget-body"> 
							<table class="table table-hover table-bordered "> 
									<?php
									$qry = mysqlQuery("SELECT * FROM `envatoSources` ORDER BY `id`");
									$numRows = mysql_num_rows($qry); 
									if ($numRows > 0) 
									{
									?>
								<thead>
									<tr>
										<th>
											<center>
												<input type="checkbox" id="selectall" />
											</center>
										</th>
										<th>Source Category</th>
										<th>Source Sub Category</th>
										<th>Destination Category</th>
										<th><center>Delete</center></th>
										<th><center>Status</center></th>
										<th><center>Action</center></th>
									</tr>
								</thead>
								<tbody>
									<?php
										while($row = mysql_fetch_array($qry))
										{ 
										$matchCategory = "SELECT * FROM `categories` WHERE `id`=". $row['cid']; 
										$qryCategory = mysqlQuery($matchCategory);
										$rowCategory = mysql_fetch_array($qryCategory);
										$category = $rowCategory['name'];
										$delete = '<a class="btn btn-xs btn-danger " data-toggle="modal" id="del" class="open-dialog" href="#delete_modal-'.trim($row['id']).'" title="Delete ' . $row['title'] . '" data-id=' . $row['id'] . '><i class="fa fa-trash-o"></i></a>';
										echo('<tr>');
										echo('<td><center><input class="selectedId" type="checkbox" id="multicheck" name="checkboxvar[]" value="'.$row['id'] . '"/></center></td>');
										echo('<td><a target="_blank" href="http://' . $row['websiteName'] . '.net">' . $row['websiteName'] . '</a></td>');
										echo('<td><a href="http://' . $row['websiteName'] . '.net/category/'.$row['websiteCategory'].'" target="_blank">' . str_replace("-"," ",$row['websiteCategory']).'</a></td>');
										echo('<td><a href="'.rootpath().'/category/'.$rowCategory['permalink'].'"><strong>'. $category .'</strong></a></td>');
										echo('<td><center>' . $delete . '</center></td>');
										echo("<td><center>"); 
										if($row['posts']==1){echo "Posted";}else echo "Pending </center></td>";
										if($row['posts']!=1)
											echo("<td><center><a class='btn getsource btn-small post".$row['$id']." btn-primary' id=".$row['id']."> Post Now </a></center></td>");
										else
											echo("<td><center><a class='btn getsource btn-small btn-success' id=".$row['id'].">Update Now</a></center></td>");
										echo("</tr>");
										?>
										<div class="modal fade" id="delete_modal-<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-header btn-green text-center">
														<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
														<h3 class="modal-title">Delete Source</h3>
													</div>
													<div class="modal-body">
														<center>
															<h5>Are you sure you want to delete this Source?</h5>
														</center>
													</div>
													<div class="modal-footer"> 						
														<a name="action" class="btn btn-danger" href="sources.php?delete=<?php echo $row['id']; ?>" id="delete">Yes</a>
														<button type="button" class="btn btn-info" data-dismiss="modal">No</button>	
													</div>
												</div><!-- /.modal-content -->
											</div><!-- /.modal-dialog -->
										</div>  
										<?php
										}
									} else {
									?><div class="notfound"><h3>No Source's Found</h3></div><?php
									}
									?> 
								</tbody>      
							</table>
							<div class="wobblebar" style="display:none"></div>
							<div class="result"></div>
						</div><!-- Awidget-body -->
					</form>
				</div><!-- Awidget --> 
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div id="md" class="bg" style="display:none"> 
		<div class="col-md-6 col-lg-offset-3">  
			<div class="awidget">
				<div class="page-title">
					<h2><i class="fa fa-plus-circle color"></i> Add Source</h2> 
					<hr />
					<div class="alert alert-success success"><li class="fa fa-check-square-o"></li><b> Source Added Successfully</b></div>
					<div class="alert alert-danger error"><li class='fa fa-warning'></li><b> Source Already Exists</b></div>
				</div>
				<?php
				$check=mysql_num_rows(mysqlQuery("SELECT `id`,`name` FROM `categories`"));
				if($check>0)   
				{
					?>
					<form id="myform" class="form-horizontal" role="form" action="add_source.php" method="post">
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Website</label>
							<div class="col-lg-10">
								<select class="form-control" name="website" id="web">
									<option value="themeforest">Themeforest</option>
									<option value="codecanyon">Codecanyon</option>
									<option value="videohive">Videohive</option>
									<option value="audiojungle">Audiojungle</option>
									<option value="graphicriver">Graphicriver</option>
									<option value="3docean">3docean</option>
									<option value="activeden">Activeden</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Select Category</label>
							<div id="ajax_categories" class="col-lg-10">
								<select class="form-control" name="categories" id="ajax_categories"></select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Assign To</label>
							<div class="col-lg-10">
								<select class="form-control" name="category" id="category">
								<?php 
								$match = "SELECT `id`,`name` FROM `categories` WHERE parentId='0'"; 
								$qry = mysqlQuery($match);
								$numRows = mysql_num_rows($qry); 
								if ($numRows > 0) 
								{
									while($rowx = mysql_fetch_array($qry)) 
									{
										echo('<option value="' . $rowx["id"] . '">' . $rowx["name"] . '</option>');
										$matchSub = "SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $rowx['id'] . " ORDER BY `id`"; 
										$qrySub = mysqlQuery($matchSub);
										$numRowsSub = mysql_num_rows($qrySub); 
										if ($numRowsSub > 0) 
										{
											while($rowxSub = mysql_fetch_array($qrySub))
											{
												echo('<option value="' . $rowxSub["id"] . '"> &raquo; ' . $rowxSub["name"] . '</option>'); 
											}
										}
									}
								}
								else
								{
									echo('<option value="0">None</option>');
								}
								?>
								</select>
							</div>
						</div> 
						<hr />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10"></div>
						</div>
						<button type="submit" id="submit" class="btn btn-success" value="Add"><i class="fa fa-floppy-o"></i> Save</button>
						<a id="close" class="btn btn-danger" value="close">Close</a>
					</form>
					<?php
				}
				else
				{
					echo ('<div style="padding-top:16px;padding-bottom:30px;text-align:center"><h3>No Sub Category Found</h3></div>');
					?>
					<hr />
					<a id="close" class="btn btn-danger" value="close">Close</a>
					<?php
				}
				?>
			</div><!-- Awidget -->
		</div><!-- col-md-6 -->
	</div>
	<div class="clearfix"></div>
	<button class="notify-without-image" id="deleterss"></button>
</div><!-- container -->
</div>
<?php include 'includes/footer.php'; ?>