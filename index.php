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
	<link href="st.css?v=<?php echo $version;?>" rel="stylesheet">
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
				<!-- <div id="side-block" class="content sidebar col-md-1 col-sm-12 col-xs-12">
					<div class="style-7 section-block sidebar-block">
									
					</div>
				</div> -->
				<!--/sidebar-->


				<!-- Right Main Bar -->
				<div id="center-block" class="content col-lg-12 col-md-9 col-sm-12 col-xs-12">
				    <div class="pin_container">
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port1.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
										<p> This is the dummy text for this website for testing</p>
										<span class="cat">Technology</span>
										<span class="date">2h</span>
										</div>
										
										
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb2.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port2.jpg');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port4.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port3.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb2.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port5.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port6.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb1.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>
						<div class="f-card f-card_large">

							<div class="image" style="background-image:url('assets/img/port7.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb2.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port8.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port9.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port10.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb2.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port11.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
											<span class="cat">Technology</span>
											<span class="date">2h</span>
										</div>
									</div>
								</a>
							</div>
						</div>
						<div class="f-card f-card_small">
							<div class="image">
								<img src="assets/img/thumb1.png" alt="">
							</div>
							<div class="title">This is the dummy text for this website for testing</div>
						</div>
					</div>
				</div>
				<!-- <div id="side-block" class="content sidebar col-md-1 col-sm-12 col-xs-12">
					<div class="style-7 section-block sidebar-block">
									
					</div>
				</div> -->
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
