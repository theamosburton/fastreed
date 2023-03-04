<?php
$_SERVROOT = '../';
include ".htactivity/VISIT.php";
new VisitorActivity();
?>

<!DOCTYPE html> 
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="unititled">
	<meta name="keywords" content="HTML5 Crowdfunding Profile Template">
	<meta name="author" content="Audain Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Fastreed - Read, Write and Share Greate Ideas and Stories</title>

	<!-- Gobal CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Template CSS -->
	<link href="assets/css/style.css?V=1.2.3" rel="stylesheet">

	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body class="style-7">
	<!--main content-->
	<div class="main-content">
		<div class="container ">
			<div class="row ">
				<div class="content col-12">
					<div id="header-section" class="section-block">
						<div class="brand">
							<i id="bars" class="t-icon fa fa-bars fa-lg"></i>
							<h1> <a href="/">FastReed.com</a> </h1>
						</div>
					
						
						<!--tabs-->
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#home">Home</a></li>
								<li role="presentation"><a href="about/" >About</a></li>
								<li role="presentation"><a href="terms-privacy/" >Terms & Privacy</a></li>

								<?php
								if(isset($_COOKIE['AID'])){
									echo '<li role="presentation"><a href="/admin/" >Admin Panel</a></li>';
								}else{
									echo '<li role="presentation"><a href="/accounts/" >Login/Signup</a></li>';
								}
								?>
							</ul>
					    </div>
					</div>
					
				</div>
				<!--Side bar -->
			      <!--Collections-->
				  <div class="content sidebar col-md-3 col-sm-12 col-xs-12 order-3">

					<div class="section-block sidebar-block">
					<p id="sidebarPosition" hidden>0</p>
						<i id="close-bars" class=" t-icon fa-solid fa-arrow-left fa-lg"></i>
					    <div class="s-tabs profile-tab">
							<img height="50px" widht="50px" src="/assets/img/dummy.png" alt="" class="s-photo">
							<div>
							<p class="name">Anonymous</p>
							<p class="desig">New User</p>
							</div>
							
						</div>	

						<div class="s-tabs"> <i class="fa fa-user"></i> Sign Up/Log In</div>
							
						<div class="s-tabs">Topics</div>	
						<div class="s-tabs">Support</div>	
						<div class="s-tabs">My Activity</div>
										
					</div>
					<!-- Collections -->

				</div>
				<!--/sidebar-->


				<!-- Right Main Bar -->
				<div class="content col-md-6 col-sm-12 col-xs-12 order-2">
				    
					
					<!--tab panes-->
					<div class="home-block style-7 section-block  ">
					<!-- <div class=" alert alert-danger" role="alert">
								The Website is currently in Development Mode!
					</div> -->
						<div class="section-tabs">
							<div class="home-tabs ">
								<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active"><a href="#featured" aria-controls="featured" role="tab" data-toggle="tab">Featured</a></li>

									<li role="presentation"><a href="#recent-posts" aria-controls="recent-posts" role="tab" data-toggle="tab">Recent</a></li>

								</ul>
							</div>
							
						</div>

						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="featured">
								<div class="section-block featured-block">
									<h1 class="section-title">Featured Posts</h1>
									<!--reward blocks-->
									<div class="reward-block border-1 section-featured">
										<h2 class="update-title">Amazon Web Services(AWS) is still a choice of new web developers for its flexible free tier</h2>
										<span class="update-date">Posted 2 hours ago</span>
										<div class="post-meta">
											<a href=""><i class="fa fa-tag"></i> Technology</a>
										    <a href=""><i class="fa fa-user"></i> Mahira Rajput</a> 
											<a href=""><i class="fa fa-eye"></i>123k</a>
										</div>
										
										<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
										
										
										<a href="" class="btn btn-reward">READ FULL ARTICLE</a>
									</div>
								</div>
							</div>


							<div role="tabpanel" class="tab-pane" id="recent-posts">
								<div class="section-block featured-block">
									<h1 class="section-title">Recent Posts</h1>
									<!--reward blocks-->
									<div class="reward-block border-1 section-featured">
										<h2 class="update-title">We've started shipping!</h2>
										<span class="update-date">Posted 2 days ago</span>
										<div class="post-meta">
											<a href=""><i class="fa fa-tag"></i> NASA</a>
										    <a href=""><i class="fa fa-user"></i> Justin Hall</a> 
											<a href=""><i class="fa fa-eye"></i>123k</a>
										</div>
										
										<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
										
										
										<a href="" class="btn btn-reward">READ FULL ARTICLE</a>
									</div>

									<div class="reward-block border-1 section-featured">
										<h2 class="update-title">We've started shipping!</h2>
										<span class="update-date">Posted 2 days ago</span>
										<div class="post-meta">
											<a href=""><i class="fa fa-tag"></i> NASA</a>
										    <a href=""><i class="fa fa-user"></i> Justin Hall</a> 
											<a href=""><i class="fa fa-eye"></i>123k</a>
										</div>
										
										<p>Curabitur accumsan sem sed velit ultrices fermentum. Pellentesque rutrum mi nec ipsum elementum aliquet. Sed id vestibulum eros. Nullam nunc velit, viverra sed consequat ac, pulvinar in metus.</p>
										
										
										<a href="" class="btn btn-reward">READ FULL ARTICLE</a>
									</div>
									<div class="block"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="content col-md-3 col-sm-12 col-xs-12 order-1">
					<div class="section-block"></div>
				</div>

				<div class="content col-12 order-4 footer">
					<div class="section-block footer-section"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="assets/js/main.js?v=1.1.0"></script>
</body>
</html>
