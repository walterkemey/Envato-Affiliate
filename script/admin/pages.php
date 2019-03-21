<?php
include "includes/header.php";
if(isset($_POST['arrayorder']))
{
	$array	= $_POST['arrayorder'];
	if ($_POST['update'] == "update")
	{
		$count = 1;
		foreach ($array as $idval) 
		{
			$query = "UPDATE `pages` SET `displayOrder` = " . $count . " WHERE id = " . $idval;
			mysqlQuery($query) or die(mysql_error());
			$count ++;	
		}
	}
}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.js"></script>
<style>
ul {
	padding:0px;
	margin: 0px;
}
#list li {
	 margin: 0px 0px 3px;
	 padding: 18px;
	 background-color: #EEE;
	 list-style: none outside none;
}
.table_header {
	margin: 0 0 3px;
	padding:18px;
	background-color:#16cbe6;
	color:#fff;
	list-style: none;
	font-size: 14px;
	font-weight: bold;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$(function() {
	$("#list ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize") + '&update=update'; 
			$.post("pages.php", order); 															 
		}								  
		});
	});

});	
</script>
<?php
$error = "";
$isDeleted = false;
function deletePage($id)
{
	$sql = "DELETE FROM `pages` WHERE id=".$id;
	mysqlQuery($sql);
}
function changePageStatus($id,$status)
{
	$sql = "UPDATE `pages` SET `status`=".$status." WHERE `id`=" . $id;
	mysqlQuery($sql);
}
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	deletePage($_GET['delete']);
	$isDeleted = true;
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Pages: <?php echo(getTitle()) ?></title>
<script type="text/javascript">
	$(document).ready(function() {
		$("#deletepage").hide();
	});
</script>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<!-- Page title -->
		<div class="page-title">
			<h2>
				<i class="fa fa-file color"></i> Pages (Sortable : Drag Pages to set their Display Order) 
			</h2>
			<hr />
		</div>
		<!-- Page title -->
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">
					<div class="awidget-head">
					<?php
					if($isDeleted)
					{
						$message = "<div class='alert alert-success'><li class='fa fa-check-square-o'></li> <b>Page Deleted Successfully</b></div>";
						echo $message;
					}
					?>
						<div align="right" style="padding-bottom:10px;">
							<a href="add_page.php" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Page</a>
						</div>
					</div>
					<?php
					$result = mysqlQuery("SELECT * FROM `pages` ORDER BY `displayOrder` ASC");
					$countRows = mysql_num_rows($result);
					if ($countRows == 0)
					{
						echo ('<div style="padding-top:0px;padding-bottom:40px;text-align:center"><h3>No Pages Found</h3></div>');
					}
					else
					{
						?>
						<div class="awidget-body">
						<div class="table_header" style="width:100%;line-height: 1px">
							<div style="width:35%;float:left">Page Name</div> 
							<div style="width:32.5%;float:left;text-align: center">Action</div>
							<div style="width:32.5%;float:left;text-align: center">Status</div>
						</div>
						<div id="list">
						<ul>
						<?php
						while($row = mysql_fetch_array($result, MYSQL_ASSOC))
						{				
							$id = stripslashes($row['id']);
							$text = stripslashes($row['title']);
							$edit = '<a href="edit_page.php?id=' . $row['id'] . '" title="Edit ' . $row['title'] . '"><i class="fa fa-pencil-square-o"></i> Edit</a>';
							$delete = '<a class="small" data-toggle="modal" id="del" class="open-dialog" href="#delete_modal'.$row['id'].'" title="Delete ' . $row['title'] . '" data-id=' . $row['id'] . '><i class="fa fa-trash-o"></i> Delete</a>';
							$stat=stripslashes($row['status']);
							?>
							<li id="arrayorder_<?php echo $id ?>">
								<div style="width:100%;float:left;line-height: 1px;font-weight: bold;">
									<div style="width:35%;float:left;line-height: 1px">
										<?php echo $text; ?>
									</div> 
									<div style="width:32.5%;float:left;line-height: 1px;text-align: center;margin-top: -6px;">
										<?php echo($edit . " - " . $delete) ?>
									</div>
									<div style="width:32.5%;float:left;line-height: 1px;text-align: center"><?php 
									if($stat==0)
									{
										echo('Pending');
									}
									else
									{
										echo('Published');
									}
									?>
									</div>
								</div>
							</li>
							<div class="modal fade" id="delete_modal<?php echo $id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header btn-green text-center">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h3 class="modal-title">Delete Page</h3>
										</div>
										<div class="modal-body">
											<center>
												<h5>Are you sure you want to delete this page?</h5>
											</center>
										</div>
										<div class="modal-footer">
											<a name="action" class="btn btn-danger" href="./pages.php?delete=<?php
											echo ($row['id']);
											?>" id="delete" value="Delete">Yes</a>
											<button type="button" class="btn btn-info" data-dismiss="modal">No</button>	
										</div>
									</div><!-- /.modal-content -->
								</div><!-- /.modal-dialog -->
							</div>
							<?php 
						}
					}
					?>
					</ul>
					</div>
					<div class="clearfix"></div>
					</div><!-- Awidget-body -->
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div> 
</div><!-- container -->
<?php include 'includes/footer.php'; ?>