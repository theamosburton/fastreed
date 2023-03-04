<?php
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.htactivity/VISIT.php";
new VisitorActivity();
?>

<!DOCTYPE html> 
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="unititled">
	<meta name="keywords" content="Fastreed - Terms and Privacy">
	<meta name="author" content="Audain Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Fastreed - Terms and Privacy</title>

	<!-- Gobal CSS -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Template CSS -->
	<link href="../assets/css/style.css?V=1.1.1" rel="stylesheet">
	<link href="../assets/css/page.css?V=1.1.4" rel="stylesheet">
	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
	<header class="header-section"></header>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row">

				<div class="content col-12">
					<div id="header-section" class="section-block">
					<div class="brand">
							<i id="bars" class="t-icon fa fa-bars fa-lg"></i>
							<h1> <a href="/">FastReed.com</a> </h1>
						</div>
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation" ><a href="/about/" >About</a></li>
								<li role="presentation" class="active"><a href="/accounts/" >Login/Signup</a></li>
							</ul>
					    </div>
					</div>
				</div>

				<div class=" content sidebar col-lg-3 col-md-0 col-sm-0">
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

						
						<a href=""><div class="s-tabs">  <i class="fa fa-hashtag fa-lg"></i>Tags </div></a>

						<a href=""><div class="s-tabs"> <i class="fa fa-table-list fa-lg"></i>Topics</div></a>
						
						<a href="/contact-us"><div class="s-tabs" > <i class="fa fa-headset fa-lg"></i>Contact Us</div></a>

						<a href="/my-interests"><div class="s-tabs">  <i class="fa fa-icons fa-lg"></i>My Interests</div></a>

						<a style="color:blue"><div class="s-tabs"> <i class="fa fa-user-plus fa-lg"></i>Sign Up/Log In</div></a>

						<a href="/terms-privacy"><div class="s-tabs"> <i class="fa fa-solid fa-file-contract fa-lg"></i>Terms & Privacy</div></a>
						
										
					</div>
				</div>

				<div class=" content col-lg-6  col-sm-12 col-xs-12">
				    <div class="style-7 section-block main-block">
						<div class=" alert alert-warning" role="alert">
							Only admins and Writers are allowed!
					    </div>

						<div class="login-signup">
							<span class="title"> Login </span>
							<form action="login.php" method="post">
								<?php
								        if (isset($_COOKIE['authStatus'])) {
											echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<strong>'.$_COOKIE['authStatus'].'</strong>
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											  <span aria-hidden="true">&times;</span>
											</button>
										  </div>';
										  }
								?>
								<input class="lg-inputs" type="text"  name="usernameOrEMail" placeholder="Username">
								<input class="lg-inputs" type="password" name="password" placeholder="Password">
								<select class="lg-inputs" name="" id="">
									<option value="">Admin</option>
									<option value="">Writer</option>
									<option value="" disabled>User (disabled)</option>
								</select>

								<input  class="lg-inputs btn" type="submit" value="LOGIN">
							</form>
							<hr width="100%">
							<a href="#">Forgotten Password</a>
							<br>
							<a>Create an Account</a>
						</div>
                    </div>
				</div>

				<div class="col-lg-3 col-md-0 col-sm-0">
				</div>

				<div class="content col-12 order-3 footer">
					<div class="section-block footer-section"></div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="../assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="../assets/js/main.js"></script>
	<script type="text/javascript" src="../assets/js/page.js"></script>
</body>
</html>
