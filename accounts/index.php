<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.htactivity/VISIT.php";
new VisitorActivity();

if (isset($_SESSION['LOGGED_USER'])){
	if($_SESSION['LOGGED_USER']){
		header('Location: /accounts/profile/');
	}
}elseif(isset($_SESSION['LOGGED_ADMIN'])) {
	if($_SESSION['LOGGED_ADMIN']){
		header('Location: /admin/');
	}
}
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
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>

	<style>
		.remember-me{
		    display: flex;
			align-items: center;
			width: 100%;
			margin: 10px 5px;
		}
		.remember-me p{
			margin-left: 10px;
		}
		.remember-me input{
			margin-bottom: 15px;
		}
		.agree p{
			margin-left :0px;
		}
	</style>
</head>
<body>
    <p id="rightsidebar" hidden>true</p>
	<p id="sidebarPosition" hidden>0</p>
	<p id="sidebarPositionLg" hidden>1</p>
	<header class="header-section"></header>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row">

				<div class="content col-12">
					<div id="header-section" class="section-block">
					<div class="brand">
							<i id="bars" class="t-icon fa fa-bars fa-lg"></i>
							<i id="bars-lg" class="t-icon-lg fa fa-bars fa-lg"></i>
							<h1> <a href="/">FastReed.com</a> </h1>
						</div>
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation" ><a href="/about/" >About</a></li>
								<li role="presentation" class="active"><a href="/accounts/" >Account</a></li>
							</ul>
					    </div>
					</div>
				</div>

				<div id="side-block" class="content sidebar col-lg-2 col-md-3 col-sm-0">
				    <div class="section-block sidebar-block">
					    <p id="sidebarPosition" hidden>0</p>
						<i id="close-bars" class=" t-icon fa-solid fa-arrow-left fa-lg"></i>	
						<?php
						include '../views/sidebar.php';
						echo $profileTab;
						$t = $p_Data->TYPE;
						echo $$t;
						?>				
					</div>
				</div>

				<div id="center-block" class=" content col-lg-7 col-md-6 col-sm-12 col-xs-12">
				    <div class="style-7 section-block main-block sign-log-block">

						<div class="login-div">
							<span class="title"> WELCOME BACK </span>
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
								<input class="lg-inputs" type="text"  name="usernameOrEMail" placeholder="Username or Email">
								<input class="lg-inputs" type="password" name="password" placeholder="Password">
								<select class="lg-inputs" name="login_as" id="">
									<option value="user">User</option>
									<option value="admin">Admin</option>
								</select>

								<div class="remember-me">
									<input type="checkbox" name="remember_me" value="checked">
									<p>Remember this device</p>
								</div>
								<div class="g-recaptcha" data-callback='onSubmit' data-sitekey="6LfHsUkjAAAAAI7vWP697QK0n8EMTwY1OqZSk1wC"></div>

								<input  class="lg-inputs btn" id="submit" type="submit" name="Submit" value="Login">
							</form>
							<a href="#">Forgotten Password</a>
							<br>
							<p class="changeSign" id="tlogup">Create an Account</p>
						</div>
                        <!-- Sign Up Div -->
						<div class="signup-div" style="display:none">
						<span class="title"> CREATE ACCOUNT </span>
						<form action="signup.php" method="post">
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
						<input class="lg-inputs" type="text"  name="username" placeholder="Username">
						<input class="lg-inputs" type="email"  name="email" placeholder="Email Address">
						<input class="lg-inputs" type="password" name="password" placeholder="Password">
						<select class="lg-inputs" name="gender" id="">
									<option value="male">Male</option>
									<option value="female">Female</option>
									<option value="others">Others</option>
								</select>
						<div class="agree">
							<p>By clicking Sign Up, you read and agree to our <a href="/terms-privacy/">Terms of Service</a> </p>

							<input  class="lg-inputs btn" id="submit" type="submit" name="Submit" value="Sign Up">
							<hr width="100%">
							<p class="changeSign" id="tlogin">Log In</p>
						</div>		
						</form>
						</div>
                    </div>
				</div>

				<div class="content r-sidebar col-lg-3 col-md-3">
					<div class="section-block right-sidebar">
					<div class="right-ad">
							Ad 01
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="../assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	<script>
		$('#accounts_link').removeAttr('href');
		$('#accounts_link').css('color','blue');
	</script>
	<!-- Template JS -->
	<script type="text/javascript" src="../assets/js/main.js"></script>
	<script type="text/javascript" src="../assets/js/page.js"></script>
</body>
</html>
