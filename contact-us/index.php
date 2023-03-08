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
	<meta name="keywords" content="HTML5 Crowdfunding Profile Template">
	<meta name="author" content="Audain Designs">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Fastreed - Read, Write and Share Greate Ideas and Stories</title>

	<!-- Gobal CSS -->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Template CSS -->
	<link href="/assets/css/style.css?V=1.2.3" rel="stylesheet">

	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        form{
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        form .button{
            display: flex;
            justify-content: center;
        }
        .i-elements{
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            font-weight: 700;
        }
        .i-e:hover, .i-e:focus, .i-e:active{
            outline: none;
            border-bottom: 3px solid lime;
        }
        .i-e{
            color: black;
            font-weight: bold;
            padding:10px;
            border: 0;
            border-bottom: 3px solid purple;
            background-color: transparent;
        }
        #submit{
            margin-top: 40px;
            padding: 10px 60px;
            color: white;
            background-color: cornflowerblue;
            border: 0;
            border-radius: 40px;
            font-weight: 700;
        }
        #submit:hover{
            cursor: pointer;
            color: #e9ecef;
            background-color: #6489cc;
        }
		#contactUs_link{
			color: blue;
		}
    </style>
</head>
<body class="style-7">
<p id="rightsidebar" hidden>true</p>
<p id="sidebarPosition" hidden>0</p>
<p id="sidebarPositionLg" hidden>1</p>
	<!--main content-->
	<div class="main-content">
		<div class="container ">
			<div class="row ">
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
								<li role="presentation"><a href="/">Home</a></li>
								<li role="presentation"><a href="/about/" >About</a></li>

								<?php
								if(isset($_COOKIE['AID'])){
									echo '<li role="presentation"><a href="/admin/" >Admin Panel</a></li>';
								}else{
									echo '<li role="presentation"><a href="/accounts/" >Accounts</a></li>';
								}
								?>
							</ul>
					    </div>
					</div>
					
				</div>
				<!--Left Side bar -->
				<div id="side-block" class="content sidebar col-md-2 col-sm-12 col-xs-12">

					<div class="section-block sidebar-block">
						<i id="close-bars" class=" t-icon fa-solid fa-arrow-left fa-lg"></i>
					    <?php
						include '../views/sidebar.php';
						echo $profileTab;
						$t = $p_Data->TYPE;
						echo $$t;
						?>			
										
					</div>

				</div>
				<!--/Left sidebar-->


				<!-- Right Main Bar -->
				<div id="center-block" class="content col-md-7 col-sm-12 col-xs-12">
				    
					
					<!--tab panes-->
					<div class="home-block style-7 section-block  ">
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="featured">
								<div class="section-block featured-block">
									<h1 class="section-title">Contact Page</h1>
									<!--reward blocks-->
                                    <form action="send.php">
                                        <div class="i-elements">
                                            <input class="i-e" type="text" placeholder="Enter Your Name" required>
                                        </div>
                                        

                                        <div class="i-elements">
                                            <input class="i-e" type="email" placeholder="Enter Your Email" required>
                                        </div>
                                        

                                        <div class="i-elements">
                                            <textarea class="i-e" name="" id="message" placeholder="Enter Your Message" required></textarea>
                                        </div>
                                        
                                        <div class="button"> <input id="submit" type="Submit" value="Submit"></div>
                                       
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Global jQuery -->
	<script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
	
	<!-- Template JS -->
	<script type="text/javascript" src="/assets/js/main.js?v=1.1.0"></script>
</body>
</html>
