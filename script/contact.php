<?php
defined("APP") or die();
if(!isset($_SESSION))
session_start();  
require "config/config.php";
require "includes/functions.php";
require "includes/language.php";
include 'admin/captcha.php';
$csrfError = false;
$csrfVariable = 'csrf_' . basename($_SERVER['PHP_SELF']);
$success = false;
$error = "";
if (isset($_POST["name"])) 
{
	if($_SESSION[$csrfVariable] != $_POST['csrf'])
    $csrfError = true;
    $name = xssClean(mres(trim($_POST["name"])));
    $from = xssClean(mres(trim($_POST["email"])));
    $to = getAdminEmail();
    $message = xssClean(mres($_POST["message"]));
	$subject=xssClean(mres($_POST["subject"]));
    if(onoffContactCaptcha()==1)
	{
		if(isset($_POST["captchaCode"]) && trim($_POST["captchaCode"])!="") 
		{
			if (trim($_POST["captchaCode"])!=$_SESSION['captcha']['code'])
				$error = 'Incorrect Answer';
			else 
			{
				if(!$csrfError)
				sendSimpleEmail($to, $from, $name, $subject, $message);
			}
		}   
		else 
		{
			$error="Captcha Field Must Not Be Empty";
		} 
	}
	else
	{
		if ($error == "" && !$csrfError) 
		{
			sendSimpleEmail($to, $from, $name, $subject, $message);
		}
	}
}
$key = sha1(microtime());
$_SESSION[$csrfVariable] = $key;
$_SESSION['captcha'] = simple_php_captcha();
$webdata=getWebDate();
$adsdata=getAdsData();
require "includes/header.php";
?>
<title><?php echo $lang_array['contact_us_page_title']; ?></title>
<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
<meta property="og:title" content="<?php echo $lang_array['contact_us_page_title']; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/contact'?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
require 'includes/header_under.php';
?>
<div class="clearfix"></div>

<ol class="breadcrumb">
	<li>
		<a href="<?php echo rootpath() ?>/"><?php echo $lang_array['breadcrumb_text_home']; ?></a>
	</li>
	<li class="active"><?php echo $lang_array['contact_us'];?></li>
</ol>

<div class="row">
<div class="col-md-8">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">
						<b><?php echo $lang_array['contact_us'];?></b>
					</h3>
				</div>
				<div class="panel-body">
					<?php
					if (isset($_POST['name']) && $error == "") 
					{
						echo('<div class="alert alert-success"><i class="fa fa-paper-plane-o"></i> &nbsp; ' . $lang_array['email_sent'] . '</div>');
					}
					?>
					<form role="form" action="<?php echo rootpath()?>/contact/" method="post">
						<div class="form-group">
							<div class="col-md-7">
								<label for="exampleInputEmail1"><?php echo $lang_array['your_name'];
								?></label>
								<?php
								if (isset($_POST['name'])) 
								{
									?>
									<input type="text" class="form-control" id="name" name="name" value="<?php
									echo ($name);
									?>" placeholder="<?php echo $lang_array['enter_your_name'];?>" pattern="[A-Za-z].{2,51}" required >
									<?php
								} 
								else 
								{
									?>
									<input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $lang_array['enter_your_name'];?>" pattern="[A-Za-z].{3,50}" required >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-7" style="margin-top:10px;">
								<label><?php echo $lang_array['your_email'];
								?></label>
								<?php
								if (isset($_POST['email'])) 
								{
									?>
									<input type="email" class="form-control" id="email" name="email" value="<?php
									echo ($email);
									?>" placeholder="<?php echo $lang_array['enter_your_email'];?>" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required >
									<?php
								} 
								else 
								{
									?>
									<input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $lang_array['enter_your_email'];?>" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,6}" required >
									<?php
								}
								?>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-7" style="margin-top:10px;">
								<label><?php echo $lang_array['subject'];
								?></label>
								<?php
								if (isset($_POST['subject'])) 
								{
									?>
									<input type="text" class="form-control" name="subject" value="<?php echo ($subject);
									?>" placeholder="<?php echo $lang_array['enter_subject'];?>" required >
									<?php
								} 
								else 
								{
									?>
									<input type="text" class="form-control" name="subject" placeholder="<?php echo $lang_array['enter_subject'];?>" required >
									<?php
								}
								?>
							</div>
						</div>
						<input type="hidden" name="csrf" value="<?php echo $key; ?>" />
						<div class="form-group">
							<div class="col-md-12" style="margin-top:10px;">
								<label for="exampleInputEmail1"><?php echo $lang_array['your_message'];?></label>
								<?php
								if (isset($_POST['message'])) 
								{
									?>
									<textarea class="form-control summernote" id="message_box" rows="10" cols="20" name="message" placeholder="<?php echo $lang_array['enter_your_message'];?>" required style="resize:none"><?php
									echo ($message);
									?></textarea>
									<?php
								} 
								else 
								{
									?>
									<textarea class="form-control summernote" id="message_box" rows="10" cols="20" name="message" placeholder="<?php echo $lang_array['enter_your_message'];?>"
									required style="resize:none"></textarea>
									<?php
								}
								?>
							</div>
						</div>
						<?php 
						if (onoffContactCaptcha()) 
						{ 
							?>
							<div class="form-group captcha-set">
								<div class="col-md-7" style="margin-top:10px;">
									<img style="width:150px" src="<?php echo($_SESSION['captcha']['image_src']) ?>"/></br></br>
									<label><?php echo $lang_array['captcha'];?></label>
									<input type="text" class="form-control" name="captchaCode" placeholder="<?php echo $lang_array['captchaCode']?>" value="" required>
									<?php 
									if(isset($_POST) && $error!="") 
									{ 
										?>
										<span class="label label-danger">Invalid Code</span>
										<?php 
									} 
									?>
								</div>
							</div>
							<?php 
						} 
						?>
						<div class="col-md-9" style="margin-top:10px;">
							<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-paper-plane-o"></i> <?php echo $lang_array['send'];
							?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
<?php
if($adsdata['rec2Status']) { ?>
<div class="col-md-12 hidden-xs ad_top_728x90">
<?php echo $adsdata['medRec2']; ?> 
</div>  
<?php } ?>
	</div>
</div>
<div class="col-md-4 hidden-xs hidden-sm ads-margin">
<?php 
if($adsdata['rec1Status']) 
{ 
	?>
	<div class="col-md-12 ad-hidden" style="margin-bottom: 10px">
		<?php echo $adsdata['medRec1']; ?>  
	</div>
	<div class="col-md-12 ad-hidden">
		<?php echo $adsdata['medRec1']; ?>  
	</div> 
	<?php 
} 
?>        
</div>
</div>
<?php
require 'includes/footer.php';
?>