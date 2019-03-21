<?php
include "includes/header.php";   
$error = "";
$isDeleted = false;
if(isset($_POST['parent'])) 
{
	$parent = mysql_real_escape_string($_POST['parent']);
	$name = mysql_real_escape_string($_POST['name']);
	mysqlQuery("UPDATE `categories` SET `parentId`=" . $parent . " WHERE `name`='" . $name . "'") or die(mysql_error());
}
$parent = $_POST['arrayorder'];
for ($i = 0; $i < count($parent); $i++) 
{
	mysqlQuery("UPDATE `categories` SET `displayOrder`=" . $i . " WHERE `id`='" . $parent[$i] . "'") or die(mysql_error());
}
if(isset($_POST['subarrayorder']))
	$sub = $_POST['subarrayorder'];
for ($i = 0; $i < count($sub); $i++) 
{
	mysqlQuery("UPDATE `categories` SET `displayOrder`=" . $i . " WHERE `id`='" . $sub[$i] . "'") or die(mysql_error());
}
 ?>  
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->
<title>Categories: <?php echo(getTitle()) ?></title>
<script src="style/ui/jquery.ui.core.js"></script>
<script src="style/ui/jquery.ui.widget.js"></script>
<script src="style/ui/jquery.ui.mouse.js"></script>
<script src="style/ui/jquery.ui.sortable.js"></script>
<script src="style/ui/jquery.ui.accordion.js"></script>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script src="style/ui/jquery.ui.touch-punch.min.js"></script> 
<script> 
	$(function() 
	{
		$( ".connectedSortable" )
		.sortable({
			connectWith:['.connectedSortable'],
			placeholder: "ui-sortable-placeholder", 
			forcePlaceholderSize: true,
			update : function () {
				serial = $(this).sortable('serialize');
				$.ajax({
					url: "categories.php",
					type: "post",
					data: serial,
					error: function(){
						alert("theres an error with AJAX");
					}
				});
			}
		})
		.droppable({
			accept: '.connectedSortable > div',
			drop: function(event,ui) {
				var cat_id=ui.draggable.attr('name');
				var parent=$(this).attr('id');
				set(parent,cat_id);
			}
		})
		.disableSelection();
		$(".edit").click(function(){
			 var url = $(this).attr("href");
			$(location).attr('href',url);
			return false;
		});
		$(".delete").click(function(){
			 var url = $(this).attr("href");
			$(location).attr('href',url);
			return false;
		});	 		
			var $tabs = $( "#tabs" ).accordion({
			heightStyle: "content",
			collapsible: true,
			header: ".h3",
			active:false,
			beforeActivate: function(event, ui) {
				// The accordion believes a panel is being opened
				if (ui.newHeader[0]) {
					var currHeader  = ui.newHeader;
					var currContent = currHeader.next('.ui-accordion-content');
				// The accordion believes a panel is being closed
				}
				else {
					var currHeader  = ui.oldHeader;
					var currContent = currHeader.next('.ui-accordion-content');
				}
				// Since we've changed the default behavior, this detects the actual status
				var isPanelSelected = currHeader.attr('aria-selected') == 'true';

				// Toggle the panel's header
				currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));

				// Toggle the panel's icon
				currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);

				// Toggle the panel's content
				currContent.toggleClass('accordion-content-active',!isPanelSelected)    
				if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

				return false; // Cancel the default action
			}
		})
	});
	function set(parent,cat_id)
	{
		$.ajax({
			url: "categories.php",
			type: "post",
			data:  "parent="+parent+"&name="+cat_id,
		});
	}
</script>
<script>
	$(function() { 
		$( "#tabs" ).sortable({
			axis: "y",
			placeholder: "ui-sortable-placeholder", 
			forcePlaceholderSize: true,
			handle: ".h3",
			stop: function( event, ui ) {
				ui.item.children( ".h3" ).triggerHandler( "focusout" );
			},
			update : function () {
				serial = $('#tabs').sortable('serialize');
				$.ajax({
					url: "categories.php",
					type: "post",
					data: serial,
					error: function(){
						alert("theres an error with AJAX");
					}
				});
			}
		});
	});
</script>
<script type="text/javascript">
$( document ).ready(function() 
{
	$( "#deletecategory" ).hide();
	$( ".succeed").hide();
	$( ".error").hide();
	$('#category').click(function()
	{
		$('#md').show();
	});
	$('#close').click(function()
	{
		$('#md').hide();
	});
	$('#submit').click(function()
	{
		var name=$('#cat').val();
		var permalink=$('#permalink').val();
		var parent=$('#parent').val(); 
		if(name == "")
		{
			$(".succeed").hide();
			$(".error").show();
		} 
		else 
		{
			$.ajax({
				type: "POST",  
				url: "add_category.php",
				data: {'name':name, 'permalink':permalink, 'parent':parent},
				success: function(server_response) 
				{
					if(server_response) 
					{
						$(".error").hide();
						$(".succeed").show();
						window.location.reload();
					}
				}
			});
		}
	});
});
</script>
<style>
.cat_header {
	margin-left: 10px;
	margin-right: 10px;
	color: #fff;
	background-color: #0fa6bc;
	padding: 15px;
	margin-bottom: 20px;
	border: 1px solid transparent;
	border-radius: 4px;
}
.main_cat {
	margin-bottom: 0px;
	margin-left: 0px;
	background: #16cbe6;
	color: #fff;
}
</style>
</head>
<body>
<div class="msg-bg">
	<div class="col-lg-5 msg">
		<h2><b>Confirm Delete</b></h2>
		<hr />
		You are going to delete this category?<br><br><br>
		<div align="right">
			<button class="btn btn-danger del" style="margin-right:1%;" >Ok</button><button class="btn btn-primary cancel">Cancel</button>
		</div>
	</div>
</div>
<div class="msg-bg-c">
	<div class="col-lg-5 msg-c">
		<h2><b>Move Products</b></h2>
		<hr>
		<div class="wait1 alert alert-success"><i class="fa fa-spinner fa-spin"></i> Please wait....</div>
		Select a subcategory to move products of this.<br><br><br>
		<div class="form-group move-products">
			<label class="col-lg-4 control-label">New Category</label>
			<div class="col-lg-8">
				<select class="form-control name" name="parent">
				</select>
			</div>
		</div>
		<div id="msg1"class="check"><input type="checkbox" class="alsoDellC"/><small>Also Delete Its Products</small></div>
		<br><br>
		<div class="dellBoxC">
			<div class="form-group" align="right">
				<div class="col-lg-offset-2 col-lg-10">
					<button class="btn btn-danger dellP" style="margin-right:1%;">Delete</button><button class="btn btn-primary cancel" >Cancel</button>
				</div>
			</div>
		</div>
		<div class="moveBoxC">
			<div class="form-group" align="right">
				<div class="col-lg-offset-2 col-lg-10">
					<button class="btn btn-success move" style="margin-right:1%;">Move</button><button class="btn btn-primary cancel">Cancel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="msg-bg-mc">
	<div class="col-lg-5 msg-mc">
		<h2><b>Move Subcategories</b></h2>
		<hr>
		<div class="wait2 alert alert-success"><i class="fa fa-spinner fa-spin"></i> Please wait....</div>
		<input type="hidden" class="oid" value="<?php echo $_GET['delete'];?>">
		Select a category to move subcategories and products of this.<br><br><br>
		<div class="form-group move-categories">
			<label class="col-lg-4 control-label">New Category</label>
			<div class="col-lg-8">
				<select class="form-control name1" name="parent">
				</select>
			</div>
		</div>
		<div id="msg" class="check"><input type="checkbox" class="alsoDellCP"/><small>Also Delete Its Categories and Products</small></div>
		<br><br>
		<div class="dellBox">
			<div class="form-group" align="right">
				<div class="col-lg-offset-2 col-lg-10">
					<button class="btn btn-danger dell_mc" style="margin-right:1%;">Delete</button><button class="btn btn-primary cancel" >Cancel</button>
				</div>
			</div>
		</div>
		<div class="moveBox">
			<div class="form-group" align="right">
				<div class="col-lg-offset-2 col-lg-10">
					<button class="btn btn-success move_mc" style="margin-right:1%;">Move</button><button class="btn btn-primary cancel" >Cancel</button>
				</div>
			</div>
		</div>  
	</div>
</div>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'includes/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-folder-open color"></i> All Categories </h2>
				<hr />
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget">
						<div class="awidget-head hideit">
							<a id="category">
								<div class="btn btn-success pull-right" style="margin-right: 13px;"><i class="fa fa-plus-circle"></i> Add Category</div>
							</a>
						</div>
						<div class="awidget-body"> 
							<div class="row cat_header">
								<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4 overflow-dots">
									<strong>Category Name</strong>
								</div> 
								<div class="hidden-xs col-sm-4 col-md-4 col-lg-4">
									<center><strong>Subcategories</strong></center>
								</div>
								<div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
									<center><strong>Action</strong></center>
								</div>
							</div>
							<div id="tabs">
								<?php
								$qry = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0' ORDER BY `displayOrder` ASC");
								while($row = mysql_fetch_array($qry))
								{
									echo '<div style="margin-bottom:10px;margin-top:10px" class="col-md-12 tabs-1" id="arrayorder_' . $row['id'] . '">' ;
									$categoryid=$row['id'];
									$matchProducts = "SELECT `id` FROM `categories` WHERE `parentId`=".$row['id'].""; 
									$qryProducts = mysqlQuery($matchProducts);
									$numRowsProducts = mysql_num_rows($qryProducts);  
									$edit = '<a style="color:white" class="btn btn-xs btn-success edit" href="edit_category.php?id=' . $row['id'] . '" title="Edit ' . $row['name'] . '"><i class="fa fa-pencil"></i></a>';
									$delete = '<i id='.$row['id'].' class="fa fa-trash-o btn btn-xs btn-danger delete del_mc" style="padding:3%;"></i>';
									?>
									<div id="main_cat" class="row h3 main_cat">
										<div id="name" class="col-xs-3 col-sm-4 col-md-4 col-lg-4 overflow-dots" >
											<?php echo $row['name'];?>
										</div> 
										<div class="hidden-xs col-sm-4 col-md-4 col-lg-4 no-open">
											<center><?php echo $numRowsProducts; ?></center>
										</div>
										<div class="col-xs-9 col-sm-4 col-md-4 col-lg-4 action main-cate-padding">
											<div class="cate-btns">
												<?php echo  $edit . ' ' . $delete ?>
											</div>
										</div>
									</div>
									<div id="<?php echo $row['id'];?>" class="connectedSortable ui-helper-reset">
										<?php
										$qrySub=mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`=" . $row['id'] . " ORDER BY displayOrder ASC"); 
										$totalSub=mysql_num_rows($qrySub);
										if($totalSub>0) 
										{
											while($rowSub = mysql_fetch_array($qrySub))
											{
												$subCatId=$rowSub['id']; 
												$qryProductsSub = mysqlQuery("SELECT `id` FROM `products` WHERE `cid`=" . $rowSub['id']);
												$numRowsProductsSub = mysql_num_rows($qryProductsSub);
												$edit = '<a  style="color:white" class="btn btn-xs btn-success edit " href="edit_category.php?id=' . $rowSub['id'] . '" title="Edit ' . $rowSub['name'] . '"><i class="fa fa-pencil"></i></a>';
												$id=$rowSub['id']; 
												$delete = "<i  id=".$rowSub['id']." class='fa fa-trash-o btn btn-xs btn-danger delete del_oid' style='padding:4%;'></i>";
												echo '<div class="ui-state-default row col-lg-12 sub-cate " id="subarrayorder_' . $rowSub['id'] . '" name="' . $rowSub['name'] . '">
												<div class="col-xs-3 col-sm-4 col-md-4 col-lg-4 overflow-dots">'.$rowSub['name'].'</div>
												<div class="hidden-xs col-sm-4 col-md-4 col-lg-4" style="text-align:center;">'.$numRowsProductsSub.'</div>
												<div class="col-xs-9 col-sm-4 col-md-4 col-lg-4 sub-cate-padding"><div class="cate-btns">' . $edit . ' ' . $delete .'
												</div></div>
												</div>';
											}
										}
										?>
										<div class="getid" style="display:none;"></div>
									</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div id="md"  class="bg" style="display:none;">
			<div class="col-md-6 col-lg-offset-3">
				<!-- Page title -->
				<div class="row">
					<div class="col-md-12">
						<div class="awidget">
							<div class="page-title">
								<h2><i class="fa fa-plus-circle color"></i> Add New Category </h2> 
								<hr />
								<div class="alert alert-success succeed"> 
									<li class='fa fa-check-square-o'></li><b> Category Added Successfully.</b>
								</div> 
								<div class="alert alert-danger error"> 
									<li class='fa fa-warning'></li><b> Name Field can not be Empty.</b>
								</div> 
							</div>
							<form class="form-horizontal" role="form" action="add_category.php" method="post">
								<?php if($successMessage) echo $successMessage; ?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Category Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="name" placeholder="Category Name" id="cat" value="" required>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="(Optional)" id="permalink" value=""/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Parent</label>
									<div class="col-lg-10">
										<select class="form-control" name="parent" id="parent">
											<option value="0">None</option>
											<?php
											$queryCategories = mysqlQuery("SELECT `id`,`name` FROM `categories` WHERE `parentId`='0'"); 
											if (mysql_num_rows($queryCategories) > 0) 
											{
												while($row = mysql_fetch_array($queryCategories)) 
												{
													echo('<option value="' . $row["id"] . '">' . $row["name"] . '</option>'); 
												}
											} 
											?>
										</select>
									</div>
								</div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<a id="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Save</a>
										<a id="close" class="btn btn-danger" value="close">Close</a>
									</div>
								</div>
							</form>
						</div><!-- Awidget -->
					</div><!-- col-md-12 -->
				</div><!-- row -->
			</div>
		</div>  
	</div><!-- container -->
</div>
<?php include 'includes/footer.php'; ?>
<script>
$(".cancel").click(function() 
{ 
	$(".msg-bg").fadeOut();
	$(".msg").fadeOut();
	$(".msg-bg-mc").fadeOut();
	$(".msg-mc").fadeOut();
	$(".msg-bg-c").fadeOut();
	$(".msg-c").fadeOut();
});
$(".del_mc").click(function() 
{ 
	var oid = this.id;
	$(".getid").html(oid);
	$.ajax
	({
		type: "POST",  
		url: "del_mc.php",
		data: {'oid':oid},
		success: function(server_response1) 
		{
			if(server_response1 == '1')
			{
				$(".msg-bg").show();
				$(".msg").fadeIn();
			}
			else
			if(server_response1 != '1')
			{
				$(".msg-bg-mc").show();
				$(".msg-mc").fadeIn();
				$(".name1").html(server_response1);
				if(server_response1=='<option value="0">None</option>')
				{
					$('.move_mc').prop("disabled",true);
					//$('.dell_mc').prop("disabled",true);
					//$('#msg').html("<b>Note : </b> Single cateogry cannot be delete or moved");
				}
			}
		}
	});
});
$(".del_oid").click(function() 
{ 
	var oid = this.id;
	$(".getid").html(oid);
	$.ajax
	({
		type: "POST",  
		url: "del_c.php",
		data: {'oid':oid},
		success: function(server_response1) 
		{
			if(server_response1 == '1')
			{
				$(".msg-bg").show();
				$(".msg").fadeIn();
			}
			else if(server_response1 != '1')
			{
				$(".msg-bg-c").show();
				$(".msg-c").fadeIn();
				$(".name").html(server_response1);
				if(server_response1=='<option value="0">None</option>') {
					$('.move').prop("disabled",true);
					//$('.dellP').prop("disabled",true);
					//$('#msg1').html("<b>Note : </b> Single cateogry cannot be delete or moved");
				}
			}
		}
	});
});
$(".move").click(function() 
{ 
	$(".wait1").show();
	var oid=$(".getid").html();
	var tid = $(".name").val();
	$.ajax
	({
		type: "POST",  
		url: "move-p.php",
		data: {'oid':oid, 'tid':tid},
		success: function(server_response1) 
		{
			if(server_response1== '1')
			{
				$(".msg-c").fadeOut();
				$(".msg-bg-c").hide();
				location.reload();
			}
		}
	});
});
$(".move_mc").click(function() 
{ 
	$(".wait2").show();
	var oid=$(".getid").html();
	var tid = $(".name1").val();
	$.ajax
	({
		type: "POST",  
		url: "move.php",
		data: {'oid':oid, 'tid':tid},
		success: function(server_response1) 
		{
			if(server_response1== '1')
			{
				$(".msg-mc").fadeOut();
				$(".msg-bg-mc").hide();
				location.reload();
			}
		}
	});
});
$(".del").click(function() 
{ 
	var oid_d=$(".getid").html();
	$.ajax
	({
		type: "POST",  
		url: "delc.php",
		data: {'oid_d':oid_d},
		success: function(server_response1) 
		{
			if(server_response1 == '1')
			{
				$(".msg").fadeOut();
				$(".msg-bg").hide();
				location.reload();
			}
		}
	});
});
$(".alsoDellC").click(function()
{
	if($(".alsoDellC").prop('checked') == true)
	{
		$(".moveBoxC").hide();
		$(".dellBoxC").fadeIn();
		$(".move-products").hide();
	}
	if($(".alsoDellC").prop('checked') == false)
	{
		$(".dellBoxC").hide();
		$(".moveBoxC").fadeIn();
		$(".move-products").fadeIn();
	}
});
$(".alsoDellCP").click(function()
{
	if($(".alsoDellCP").prop('checked') == true)
	{
		$(".moveBox").hide();
		$(".dellBox").fadeIn();
		$(".move-categories").hide();
	}
	if($(".alsoDellCP").prop('checked') == false)
	{
		$(".dellBox").hide();
		$(".moveBox").fadeIn();
		$(".move-categories").fadeIn();
	}
});	
$(".dell_mc").click(function() 
{ 
	$(".wait2").show();
	var tid=$(".getid").html();
	$.ajax
	({
		type: "POST",  
		url: "dellCP.php",
		data: {'tid':tid},
		success: function(server_response1) 
		{
			if(server_response1== '1')
			{
				$(".msg-mc").fadeOut();
				$(".msg-bg-mc").hide();
				location.reload();
			}
		}
	});
});	
$(".dellP").click(function() 
{ 
	$(".wait1").show();
	var tid=$(".getid").html();
	$.ajax
	({
		type: "POST",  
		url: "dellP.php",
		data: {'tid':tid},
		success: function(server_response1)    
		{
			if(server_response1== '1')
			{
				$(".msg-mc").fadeOut();
				$(".msg-bg-mc").hide();
				location.reload();
			}
		}
	});
});	
</script>