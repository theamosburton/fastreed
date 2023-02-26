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
<body>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row">
				<div class="content col-12">
					<div id="header-section" class="section-block">
						<h1> <a href="/">FastReed.com</a> </h1>
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
				  <div class="content col-md-4 col-sm-12 col-xs-12 order-2">
					<!-- <div class="section-block summary">
						<div class="profile-contents">
							<img src="assets/img/profile.webp" class="profile-image img responsive" alt="John Doe">
							<a href="#" class="btn btn-contact"><i class="fa fa-user"></i>JAMES CAMERON</a>
						</div>
					</div> -->

					<div class="section-block sidebar-block">
						<h1 class="section-title ">Topics</h1>
						<!--Authors block-->
						<div class="credit-block sources">
							<ul class="list-unstyled">
								<li><a href="#"><i class="fa fa-link"></i>Social Media(10)</a></li>
								<li><a href="#"><i class="fa fa-link"></i>Technology(20)</a></li>
								<li><a href="#"><i class="fa fa-link"></i>Business(12)</a></li>
								<li><a href="#"><i class="fa fa-link"></i>Education(98)</a></li>
								<li><a href="#"><i class="fa fa-link"></i>Politics(12)</a></li>
								<li><a href="#"><i class="fa fa-link"></i>Entertainment(123)</a></li>
							</ul>
						</div>


						<h1 class="section-title">Writers</h1>
						<!--Channels block-->
						<div class="credit-block sources">
							<ul class="list-unstyled">
								<li><a href="#"><i class="fa fa-user-circle fa-xl"></i>Rakesh Malik</a></li>
								<li><a href="#"><i class="fa fa-user-circle fa-xl"></i>MD. Kabeer</a></li>
								<li><a href="#"><i class="fa fa-user-circle fa-xl"></i>Mahira Rajput</a></li>
								<li><a href="#"><i class="fa fa-user-circle fa-xl"></i>Sophia Vergara</a></li>
								<li><a href="#"><i class="fa fa-user-circle fa-xl"></i>Amrita Singh</a></li>
								<li><a href=""><i class="fa fa-user-circle fa-xl"></i>Anonymous</a></li>
							</ul>
						</div>
					</div>
					<!-- Collections -->

				</div>
				<!--/sidebar-->


				<!-- Right Main Bar -->
				<div class="content col-md-8 col-sm-12 col-xs-12 order-1">
				    
					
					<!--tab panes-->
					<div class="home-block section-block  ">
					<div class=" alert alert-danger" role="alert">
								The Website is currently in Development Mode!
					</div>
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
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<footer class="footer">
	<div class="container text-center">
			<div class="row copyright-row">
				<span class="copyright"><a href="#" target="_blank">@ Copyright www.Fastreed.com | 2023</a></span>
			</div>
		</div>
	</footer>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="assets/js/main.js"></script>
</body>
</html>
