<?php
$_SERVROOT = '../../../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.htactivity/VISIT.php";
$visit = new VisitorActivity();
$version = $visit->VERSION;

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
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Template CSS -->
	<link href="/assets/css/style.css?V=<?php echo $version;?>" rel="stylesheet">
	<link href="/assets/css/page.css?V=<?php echo $version;?>" rel="stylesheet">
	<link href="/admin/style.css?V=<?php echo $version;?>" rel="stylesheet">
	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
	<style>
		.main-block{
			min-height: 80vh;
		}
		.activity-tables{
			display: flex;
			justify-content: center;
			align-items: center;
			
		}
		.tb-devices, .tb-sessions{
			filter: blur(3px);
		}
		.loader{
			position: absolute;
		}
	</style>
</head>
<body class="style-7">
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
					<!--tabs-->
					<div class="head-tabs">
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation"><a href="/admin/">Dashboard</a></li>
							<li role="presentation" class="active" ><a>Activty</a></li>
							<li role="presentation"><a href="/profile/">Profile</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="content sidebar page-col col-lg-3 col-md-12 col-sm-12 col-xs-12">
			<div class="section-block sidebar-block">
					    <p id="sidebarPosition" hidden>0</p>
						<i id="close-bars" class=" t-icon fa-solid fa-arrow-left fa-lg"></i>	
						<?php
						include '../../../views/sidebar.php';
						echo $profileTab;
						$t = $p_Data->TYPE;
						echo $$t;
						?>				
					</div>
			</div>

			<div class="content col-lg-9 col-md-12 col-sm-12 col-xs-12">
				<!-- All Devices Block -->
				<div class="section-block main-block">

				        <div class="section-tabs">
							<div class="home-tabs ">
								<ul class="nav nav-tabs" role="tablist">
									<li role="presentation" class="active"><a href="#featured" aria-controls="featured" role="tab" data-toggle="tab">Featured</a></li>

									<li role="presentation"><a href="#recent-posts" aria-controls="recent-posts" role="tab" data-toggle="tab">Recent</a></li>

								</ul>
							</div>
						</div>


					<div class="section-title" id="1">All Devices</div>
					<div class="filter">
					    <label for="dateRange">Date:</label>
						<input id="dateRange" type="date">
					    <label for="order">Order:</label>
						<select id="order" value="order">
						    <option value="desc">Descending Order</option>
							<option value="asc">Ascending Order</option>	
					    </select>
						<label for="rows">Rows:</label>
						<select name="" id="rows">
						    <option value="10">10</option>
						    <option value="25">25</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
						<label for="columns">Range:</label>
						<select id="range" value="range">
							<option value="1,25">1-25</option>
							<option value="25,50">25-50</option>
					    </select>
						<span id="filter-button-device" class="filter-button">Apply</span>
	                </div>
					<div class="activity-tables device-table">
					    <div class="loader device-loader"></div>
						<table class="table table-bordered tb-devices">
							<thead>
								<tr>
								<th scope="col">S.NO.</th>
								<th scope="col">Unique ID</th>
								<th scope="col">DEIVCESS</th>
								<th scope="col">BROWSER</th>
								<th scope="col">PLATFORM</th>
								</tr>
							</thead>
							<tbody id="devices-rows">
							    <tr>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>	

								<tr>
									<th></th>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</div>

				</div>	
				<!-- All Devices Block End -->
			</div>
			<div class="content col-12 order-3 footer">
					<div class="section-block footer-section"></div>
				</div>
		</div>
	</div>
</div>

<!-- Global jQuery -->
<script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>

<!-- Template JS -->
<script type="text/javascript" src="/assets/js/main.js?v=<?php echo $version;?>"></script>
<script type="text/javascript" src="/assets/js/page.js?v=<?php echo $version;?>"></script>
<script type="text/javascript" src="getdata.js?v=<?php echo $version;?>"></script>
</body>
</html>
