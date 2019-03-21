<?php
include "includes/header.php";
$error = "";
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add New Product : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'includes/navbar_admin.php'; ?>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
	<?php include 'includes/sidebar.php'; ?>
	<div class="mainy">
		<div class="page-title">
			<h2><i class="fa fa-check-square-o color"></i> Update Products </h2> 
			<hr />
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget">  
					<script>
						$(document).ready(function () 
						{
							$('.wobblebar').hide();
							$( document ).ajaxStop(function() 
							{
								$('.wobblebar').hide();
							});
							$('#submit').click(function(e)
							{  
								$('.wobblebar').show();
								$(".result").html("");
								e.preventDefault();
								var urls = $('#urls').val().split(/\n/);
								$.each(urls, function(index,url)   
								{
									$.ajaxq("myQueue", 
									{
										type:"POST",
										url:"productUpdate.php",   
										data:{URL:url},
										success:function(result)
										{   
											$(".result").append(result); 
										}
									});
								});
							});
						});
					</script>
					<form class="form-horizontal" id="myform" role="form" action="add_product.php" method="post"> 
						<div class="form-group">
							<label class="col-lg-2 control-label">Product URLs</label>
							<div class="col-lg-10">
								<textarea class="form-control" placeholder="Enter one URL per line" rows="10" id='urls' name="urls" required><?php echo($_POST["urls"]) ?></textarea>
								<div class="wobblebar">
									Loading...
								</div>
								<div class="result"></div>
								<small>e.g: http://codecanyon.net/item/website-value-calculator-script/9990820</small>
							 </div>
						</div>
						<hr />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-success" id="submit"><i class="fa fa-check-square-o"></i> Update</button>
							</div>
						</div>
					 </form>
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
	</div><!-- mainy -->
	<div class="clearfix"></div> 
</div><!-- container -->
<?php include 'includes/footer.php'; ?> 