<?php
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.htactivity/VISIT.php";
new VisitorActivity();

if(!isset($_COOKIE['AID'])){

	header('Location: /accounts/index.php');
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
	<style>
		.main-block{
			height: auto;
			max-height: none;
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
						<h1> <a href="/">FastReed.com</a> </h1>
						<!--tabs-->
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation" ><a href="/about/" >About</a></li>
								<li role="presentation"><a href="/terms-privacy/">Terms & Privacy</a></li>
							</ul>
					    </div>
					</div>
					
				</div>
				  <!--Side bar -->
			      <!--Collections-->
				<!-- <div class="content sidebar page-col col-md-4 col-sm-12 col-xs-12">
					<div class="section-block sidebar-block">
						
						<h1 id="tab"class=" table-content section-title">Table of content <li id="toggle-icon" class="close-symbol fa fa-close fa-md" style="color:#69dbbd; float:right"></li></h1>
					</div> -->
					<!-- Collections -->
				<!-- </div> -->
				<!--/sidebar-->
				<!-- Right Main Bar -->
				<div class="col-md-2 col-lg-2 col-sm-0"></div>
				<div class="content col-md-8 col-sm-12 col-xs-12">
					<!--tabs-->
					<!--/tabs-->
					<div class="section-block main-block">
				</div>
				<div class="col-md-2 col-lg-2 col-sm-0"></div>
				
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
