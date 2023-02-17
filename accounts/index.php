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
						<h1> <a href="/">FastReed.com</a> </h1>
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation" ><a href="/about/" >About</a></li>
								<li role="presentation"><a href="/terms-privacy/">Terms & Privacy</a></li>
								<li role="presentation" class="active"><a href="/accounts/" >Login/Signup</a></li>
							</ul>
					    </div>
					</div>
				</div>

				<div class="col-lg-3 col-md-0 col-sm-0">
				</div>

				<div class="content col-lg-6  col-sm-12 col-xs-12">
				    <div class="section-block main-block" style="max-height: fit-content">
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
									<option value="">Author</option>
									<option value="" disabled>User (disabled)</option>
								</select>

								<input  class="lg-inputs btn" type="submit" value="LOGIN">
							</form>
							<form action=""></form>
						</div>
                    </div>
				</div>

				<div class="col-lg-3 col-md-0 col-sm-0">
				</div>

			</div>
		</div>
	</div>
	<footer class="footer">
	<div class="container text-center">
			<div class="row copyright-row">
				<!--This template has been created under the Creative Commons Attribution 3.0 License. Please keep the attribution link below when using this template in your own project, thank you.-->
				<span class="copyright"><a href="/" target="_blank">@ Copyright www.Fastreed.com | 2023</a></span>
			</div>
		</div>
	</footer>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="../assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="../assets/js/main.js"></script>
	<script type="text/javascript" src="../assets/js/page.js"></script>
</body>
</html>
