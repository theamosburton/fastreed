<?php
$_SERVROOT = '../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include ".htactivity/VISIT.php";
$visit = new VisitorActivity();
$version = $visit->VERSION;
$version = implode('.', str_split($version, 1));
?>

<!DOCTYPE html> 
<html lang="en">  
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
	<link href="assets/css/style.css?v=<?php echo $version;?>" rel="stylesheet">

	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body class="style-7">
	<p id="rightsidebar" hidden>true</p>
	<p id="sidebarPosition" hidden>1</p>
	<p id="sidebarPositionLg" hidden>1</p>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row ">

			    <!-- Header Section -->
				<div class="content col-12">
					<div id="header-section" class="section-block">
						<div class="brand">
							<i id="bars" class="t-icon fa fa-bars fa-lg"></i>
							<i id="bars-lg" class="t-icon-lg fa fa-bars fa-lg"></i>
							<h1> <a href="/">FastReed.com</a> </h1>
						</div>
					
						
						<!--tabs-->
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation" class="active"><a href="#home">Home</a></li>
								<li role="presentation"><a href="about/" >About</a></li>

								<?php
								if(isset($_SESSION['LOGGED_ADMIN'])){
									if($_SESSION['LOGGED_ADMIN']){
										echo '<li role="presentation"><a href="/admin/">Admin Panel</a></li>';
									}else {
										echo '<li role="presentation"><a href="/accounts/" >Login</a></li>';
									}
								}elseif(isset($_SESSION['LOGGED_USER'])){
									if($_SESSION['LOGGED_USER']){
										echo '<li role="presentation"><a href="/accounts/profile/">Profile</a></li>';
									}else {
										echo '<li role="presentation"><a href="/accounts/">Login</a></li>';
									}
								}else{
									echo '<li role="presentation"><a href="/accounts/">Login</a></li>';
								}
								?>
							</ul>
					    </div>
					</div>
					
				</div>
				<!--Side bar -->
			      <!--Collections-->
				<div id="side-block" class="content sidebar col-md-3 col-sm-12 col-xs-12">
					<div class="style-7 section-block sidebar-block">
						<i id="close-bars" class=" t-icon fa-solid fa-arrow-left fa-lg"></i>
						<?php include 'views/sidebar.php' ;
						echo $profileTab;
						$t = $p_Data->TYPE;
						echo $$t;
						?>			
					</div>
				</div>
				<!--/sidebar-->


				<!-- Right Main Bar -->
				<div id="center-block" class="content col-lg-9 col-md-9 col-sm-12 col-xs-12">
					<!--tab panes-->
					<div class="home-block style-7 section-block">
						<div class="featured-block" style="">
							<div class="reward-block border-1 section-featured">
								<a href="#">
									<div class="post-image"><img src="assets/img/aws.avif" alt=""></div>
									<h2 class="update-title">Amazon Web Services(AWS) is still a choice of new web developers for its flexible free tier</h2>
								</a>
								<div class="post-meta">
								<a href=""><i class="fa fa-tag"></i> Technology</a>
								<a href="">1h</a> 
								</div>
							</div>
						</div>

						<div class=" featured-block">
							<div class="reward-block border-1 section-featured">
								<a href="#">
									<div class="post-image"><img src="assets/img/aws.avif" alt=""></div>
									<h2 class="update-title">Amazon Web Services(AWS) is still a choice of new web developers for its flexible free tier</h2>
								</a>
								<div class="post-meta">
								<a href=""><i class="fa fa-tag"></i> Technology</a>
								<a href="">1h</a> 
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
<!-- Global jQuery -->
<script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

<!-- Template JS -->
<script type="text/javascript" src="assets/js/main.js?v=<?php echo $version;?>"></script>
</body>
</html>
