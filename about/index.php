<?php
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.ht/controller/VISIT.php";
$visit = new VisitorActivity();
$version = $visit->VERSION;
?>
<!DOCTYPE html> 
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->  
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="unititled">
	<meta name="keywords" content="About - Fastreed.comAbout - Fastreed.com">
	<meta name="author" content="Fastreed Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>About - Fastreed.com</title>

	<!-- Gobal CSS -->
	<link href="../assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Template CSS -->
	<link href="../assets/css/style.css?V=<?php echo $version;?>" rel="stylesheet">
	<link href="../assets/css/page.css?V=<?php echo $version;?>" rel="stylesheet">
	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body class="style-7">
<p id="rightsidebar" hidden>false</p>
	<header class="header-section"></header>
	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row">

				<div class="content col-12">
					<div id="header-section" class="section-block">
					<div class="brand">
							<h1> <a href="/">FastReed.com</a> </h1>
						</div>
						<!--tabs-->
				        <div class="head-tabs">
							<ul class="nav nav-tabs" role="tablist">
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation" class="active"><a>About</a></li>
								<li role="presentation"><a href="/terms-privacy/" >Terms & Privacy</a></li>
							</ul>
					    </div>
					</div>
					
				</div>


				<!--Left Side bar -->
				
				<!--/Left sidebar-->

				<!-- Right Main Bar -->
				<div id="center-block" class="content col-md-12 col-sm-12 col-xs-12">
					<!--tabs-->
					<!--/tabs-->
					<div class="section-block style-7 main-block">
					<!-- <div class=" alert alert-danger" role="alert">
								The Website is currently in Development Mode!
					</div> -->
						<h2 class="section-title">About Fastreed.com</h2>
						<div class="paragraphs">
							<h3 id="what-is-fastreed">What is Fastreed?</h3>
							<p>Fastreed is a multi-blogging or Multi-Niche Blogging website. On fastreed website you will find articles on multiple topics such as: Sports, Business, Social Media, Technology, Education, Politics, Entertainment etc. written and compiled by many authors (Fastreed authors).</p>
							<p>There is no premium or paid membership on our platform. Fastreed is completey free for everyone.</p>
							<p>Fastreed allow everyone to Read, Write and Share their ideas and knowledge with the world.</p>
							<div class="alert alert-secondary" role="alert">
								As for now only<b> fastreed authors</b> can write articles but soon you will be able to write and share your articles on our platform.
							  </div>

							<h3 id="why-fastreed">Why Fastreed?</h3>
							<p>You want to write and share your deep ideas, reasearch or information you are on the right platform. But why you choose reading and writing articles;</p>
							<p>Online, there are many ways to transfer ideas and information among others like Forums, Discussion groups, Videos, Social Media posts etc. but reading and writing articles is a most prefered way to consume the deeper information. There are lots of benefits associated with reading and writing. It expand your knowledge and understanding, improve communication skills, enhnace your creativity, boost your critical thinking, sync you with updates and technologies.</p>
							<div class="alert alert-success" role="alert">
								You will be able to <b>earn money</b> by writing articles when it is enabled on our platform. We will let you know when it is enabled.
							</div>

							<h3 id="our-writers">Our Writers</h3>
							<div class="team">
								<div class="section-block members summary">
									<div class="profile-contents">
										<img src="../assets/img/dummy.png" class="profile-image img responsive" alt="John Doe">
										<a href="#" class="btn">Shikhar Pahariya</a>
										<p>Senior Writer</p>
									</div>
								</div>

								<div class="section-block members summary">
									<div class="profile-contents">
										<img src="../assets/img/dummy.png" class="profile-image img responsive" alt="John Doe">
										<a href="#" class="btn">MD. Kabeer</a>
										<p>Senior Writer</p>
									</div>
								</div>

								<div class="section-block members summary">
									<div class="profile-contents">
										<img src="../assets/img/w-dummy.png" class="profile-image img responsive" alt="John Doe">
										<a href="#" class="btn">Mahira Rajput</a>
										<p>Writer</p>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="../assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="../assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="../assets/js/main.js?v=<?php echo $version;?>"></script>
	<script type="text/javascript" src="../assets/js/page.js?v=<?php echo $version;?>"></script>
</body>
</html>
