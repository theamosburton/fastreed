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
	<link href="assets/css/homepage.css?v=<?php echo $version;?>" rel="stylesheet">
	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>
	<header>
		<div class="brand"><h1><a href="">Fastreed</a></h1></div>
		<div class="rightside">
			<div class="nav">
				<i class="fa fa-regular fa-circle-user fa-xl"></i>
				<i class="fa fa-gear fa-xl"></i>
				<i class="fa fa-ellipsis-v fa-xl"></i>
			</div>
		</div>
		
	</header>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row ">
					
				</div>
				<!-- Right Main Bar -->
				<div id="center-block" class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="pin_container">
					<div class="options"></div>
						<!-- 01 -->
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port1.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
										<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 02 -->
						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb2.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>

							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 03 -->
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port2.jpg');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 04 -->
						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port4.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 05 -->
						
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port3.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						
						<!-- 06 -->
						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb2.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 07-->
						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb3.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!-- 08 -->
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port5.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<!--09  -->
						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port6.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb4.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port7.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb5.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port8.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_medium">
							<div class="image" style="background-image:url('assets/img/port9.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port10.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb6.avif');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>

						<div class="f-card f-card_large">
							<div class="image" style="background-image:url('assets/img/port11.png');">
								<a href="#">
									<div class="overlay">
										<div class="top-overlay"> <div class="st-logo"></div> </div>
										<div class="title"> 
											<p> This is the dummy text for this website for testing</p>
										</div>
									</div>
								</a>
							</div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
						<div class="f-card f-card_small">
							<div class="image" style="background-image:url('/assets/img/thumb2.png');">
								<a href="">
									<div class="overlay"></div>
								</a>
							</div>
							<div class="title"> <a href="#">This is the dummy text used for testing this website design and for other purposes also</a></div>
							<div class="meta">
								<span class="cat"> <a href="">Technology</a> </span>
								<span class="date">2h</span>
								<a href=""><i class="fa fa-ellipsis-v"></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<!-- Global jQuery -->
<script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>

</body>
</html>
