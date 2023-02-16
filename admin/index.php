<?php
$_SERVROOT = '../../';
// $_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
// include $_DOCROOT."/.htactivity/VISIT.php";
// new VisitorActivity();

$GLOBALS['DB'] = $_SERVROOT.'/secrets/DB_CONNECT.php';

include_once($GLOBALS['DB']);


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
	<meta name="keywords" content="Fastreed - Admin Panel">
	<meta name="author" content="Audain Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Fastreed - Admin Panel</title>

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
							<li role="presentation"><a href="DATA.php">Terms & Privacy</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="content sidebar page-col col-lg-4 col-md-12 col-sm-12 col-xs-12">
				<div class="section-block admin-block-sidebar">
					<h1 id="tab"class=" table-content section-title">Activity <li id="toggle-icon" class="close-symbol fa fa-close fa-md" style="color:#69dbbd; float:right"></li></h1>
					<div id="tb-con">
						<ul>
							<!-- <a class="TOC-subh" href="#terms-of-use">User Activity</a> -->
							<li><a href="#1">All Visits</a></li>
							<li><a href="#2">Unique</a></li>
							<li><a href="#3">Existing</a></li>
							<li><a href="#4">Admin</a></li>
							<li><a href="#5">Sessions</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="content col-lg-8 col-md-12 col-sm-12 col-xs-12">
				<div class="section-block main-block">
					<div class="section-title" id="1">All Devices</div>
					<div class="activity-tables">
						
						<table class="table table-bordered">
							<thead>
								<tr>
								<th scope="col">#ID</th>
								<th scope="col">DEIVCESS</th>
								<th scope="col">BROWSER</th>
								<th scope="col">PLATFORM</th>
								</tr>
							</thead>

							<tbody id="devices-rows">
							</tbody>
						</table>


					</div>
				</div>	
			</div>
		</div>
	</div>
</div>
<footer class="footer">
<div class="container text-center">
		<div class="row copyright-row">
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
<script type="text/javascript" src="getdata.js"></script>
</body>
</html>
