<!-- Logo & Navigation starts -->
<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-md-4">
				<div class="logo">
					<h1>
						<a href="<?php echo(rootpath()) ?>/admin"><?php echo(getTitle()) ?></a>
					</h1>
				</div>
			</div>
			<div class="col-md-8">
				<div class="navbar navbar-inverse" role="banner">
					<div class="navbar-header">
						<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
							<span>Menu</span>
						</button>
					</div>
					<nav class="collapse navbar-collapse bs-navbar-collapse navbar-right" role="navigation">
						<ul class="nav navbar-nav">
							<li class="dropdown"></li>
							<li class="dropdown">
							<a target="_blank" href="<?php echo (rootpath())?>">
								<i class="fa fa-external-link"></i> Visit Website
							</a>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-user"></i> My Account <b class="caret"></b>
								</a>
								<ul class="dropdown-menu animated fadeInUp">
									<li>
										<a href="user.php"><i class="fa fa-user"></i>&nbsp;Profile</a>
									</li>
									<li>
										<a href="logout.php"><i class="fa fa-power-off"></i>&nbsp;Logout</a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Logo & Navigation ends -->