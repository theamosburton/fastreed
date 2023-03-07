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
	</style>
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

				<div class="content sidebar col-lg-3 col-md-3 col-sm-0">
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

				<div class=" content col-lg-6 col-md-6 col-sm-12 col-xs-12">
				    <div class="style-7 section-block main-block">
						<div class="login-signup">
							<span class="title"> Welcome Back </span>
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
							<hr width="100%">
							<a href="#">Forgotten Password</a>
							<br>
							<a>Create an Account</a>
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
