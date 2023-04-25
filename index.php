<?php
$_SERVROOT = '../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include ".htactivity/VISIT.php";
$visit = new VisitorActivity();
$basic_func = new BasicFunctions();
$version = $visit->VERSION;
$version = implode('.', str_split($version, 1));
$userLogged = false;
$adminLogged = false;
if(isset($_SESSION['LOGGED_USER'])){
	$userData = new getLoggedData();
	if ($userData->U_AUTH) {
		$userLogged = true;
		if ($userData->getAccess()['userType'] == 'admin') {
			$adminLogged = true;
		}
	}
}
?>

<!DOCTYPE html> 
<html lang="en">  
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Read, Write and Share Greate Ideas and Stories">
	<meta name="keywords" content="Read, Write and Share Greate Ideas and Stories">
	<meta name="author" content="MD. Shafiq Malik">
	<link rel="shortcut icon" href="favicon.ico">  
	<title>Fastreed - Read, Write and Share Greate Ideas and Stories</title>

	<!-- Gobal CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/homepage.css?v=<?php echo $version;?>" rel="stylesheet">
	<!--Fonts-->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
	

	<script src="https://accounts.google.com/gsi/client" async defer></script>
	<script src="https://apis.google.com/js/api.js"></script>
	<script src="https://apis.google.com/js/platform.js" async defer></script>

	<?php
	if ($basic_func->gitIsUpdated()) {
		echo '<script>
		let gitIsUpdated = true;
	</script>';
	}else {
		echo '<script>
		let gitIsUpdated = false;
	</script>';
	}
	?>
</head>
<body>
	<div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
	<header>
		<?php 
		if ($userLogged) {
			if ($adminLogged) {
				$adminNav = <<<HTML
				<div>
					<i id="adminNavIcon" class="fas fa-solid fa-code fa-xl" onclick="toggleAdmin()"></i>
				</div>
				HTML;
			}else {
				$adminNav = <<<HTML
				HTML;
			}

			$regHeader = <<<HTML
			<!-- Registered User Header -->
			<div class="brand"><h1><a href="/">Fastreed</a></h1></div>
			<div class="rightside">
				<div class="nav" id="nav">

					<!-- Profile Pic -->
					<div>
						<img onclick="toggleProfile()" src="$userData->PROFILE_PIC" alt="" id="profileImage">
						<i id="accountIcon" hidden></i>
					</div>
					<!-- Profile Pic -->

					<!-- Admin Nav -->
					$adminNav
					<!-- Admin Nav -->

					<!-- Notifications Nav-->
					<div class="navs">
						<i id="notificationIcon" class="fa fa-regular fa-bell fa-xl" onclick="toggleNotifications()"></i>
						<span class="badge" id="notiCount">1</span>
					</div>
					<!-- Notifications Nav-->

					<!-- Settings Nav -->
					<div class="navs">
					<i class="fa fa-gear fa-xl" onclick="toggleSetting()"></i>
					</div>
					<!-- Settings Nav -->

				</div>
			</div>
			HTML;
			echo $regHeader;
		}else {
		$anonHeader = <<<HTML
		<!-- Anonymous User Header -->
		<div class="brand"><h1><a href="/">Fastreed</a></h1></div>
		<div class="rightside">
			<div class="nav" id="nav">
				<!-- Accounts Nav -->
				<div>
					<i id="accountIcon" class="fa fa-regular fa-circle-user fa-xl" onclick="toggleAccounts()"></i>
				</div>
				<div class="spinner" id="MenuSpinner"></div>
				<!-- Accounts Nav -->

				<!-- Settings Nav -->
				<div class="navs">
					<i class="fa fa-gear fa-xl" onclick="toggleSetting()"></i>
				</div>
				<!-- Settings Nav -->
			</div>
		</div>
		HTML;
		echo $anonHeader;
		}
		?>
	</header>
	<!-- ***************************************************** Headers **************************************************** -->

	<!--main content-->
	<div class="main-content">
		<div class="container">
			<div class="row ">

			    <!-- Notifications -->
				<div class="dropdowns" id="noti-nav" style="display:none">
					<div class="menu-head">
						<span class="name">Notifications</span>
					</div>
					<div class="notifications">
						<?php
						if ($userLogged) {
							if ($userData->getAccess()['Gender'] === null || $userData->getAccess()['DOB'] == null) {
								$userSince = (int) $userData->getAccess()['userSince'];
								$userSince = date('M j, Y \a\t h:i A', $userSince);

								$profileNotify = <<<HTML
									<div class="notification">
										<img class="image" src="/assets/img/favicon2.jpg">
										<div class="body">
											<p class="noti-parts title"> <b>Hi $userData->NAME!</b> Please complete your profile to access all features. Thank you!</p>
											<span class="noti-parts time">$userSince</span>
											<a class="singleAction"href="#">Complete Profile</a>
										</div>
									</div>
								HTML;
								echo $profileNotify;
							}
						}
						
						?>
						
					</div>
				</div>
				<!-- ******************************************** Notifications ********************************************** -->

				<!-- Accounts -->
				<div class="dropdowns" id="accounts" style="display:none">
						<?php 
						// Logged Users
						if ($userLogged) {
							$accountDropdown = <<<HTML
							<div class="menu-head">
								<span class="name">My Account</span>
							</div>
							<div class="menus"><i class="left fa fa-user-circle"></i> <a href="">Profile</a> </div>
							<div class="menus"id="logout" onclick="logout()"><i class="left fa fa-power-off"></i>Log Out</div>
						HTML;
							echo $accountDropdown;
						// Anonymous Users
						}else {
							$loginWith = <<<HTML
							<div class="menu-head">
								<span class="name">Login or Create Account</span>
							</div>
							
							<div id="g_id_onload"
								data-client_id="878548651441-q7db04pmge7g6vlcieepnr21j9mkj2iu.apps.googleusercontent.com"
								data-context="use"
								data-ux_mode="popup"
								data-callback="onGoogleSignIn"
								data-auto_prompt="false">
							</div>

							<div id="g_id_signin" class="g_id_signin"
								data-type="standard"
								data-shape="pill"
								data-theme="filled_black"
								data-text="continue_with"
								data-size="large"
								data-logo_alignment="left">
							</div>

							<a id="contEmail" class="continueEmail" href="" style="pointer-events: none"> <i class="left fa fa-envelope"></i> Continue with email</a>
						HTML;

						echo $loginWith;
						}
						?>
				</div>
				<!--*********************************** Accounts ************************************************************ -->

				<!-- Admin Options -->
				<?php
				if ($userLogged){
					if($adminLogged){
						if (!$basic_func->gitIsUpdated()) {

							$refresh = <<<HTML
								<div id="refStyle" class="menus" onclick="refreshCss()"> 
									<div  class="spinner" id="RSpinner"></div>
									<i id="RefreshIcon" class="left fa fa-rotate"></i>
									<span>Refresh Style </span>
								</div>
								<div id="refHard" class="menus" onclick="hardRefresh()"> 
									<div  class="spinner" id="HRSpinner"></div>
									<i id="HPicon" class="left fa fa-code-pull-request"></i>
									<span> Hard Pull</span>
								</div>
							HTML;
						}else {
							$refresh = <<<HTML
								<div  id="refStyle" class="menus"> 
									<div class="spinner" id="RSpinner"></div>
									<i id="RefreshIcon" class="left fa fa-rotate"></i>
									<span>Refresh Style</span>
								</div>
								<div id="refHard" class="menus"> 
									<div class="spinner" id="HRSpinner"></div>
									<i id="HPicon" class="left fa fa-code-pull-request" style="color:grey"></i>
									<span> Hard Pull</span>
								</div>
							HTML;
						}
						$advOptions = <<<HTML
							<div class="dropdowns" id="advOptions" style="display:none">
								<div class="menu-head">
									<span class="name">Options</span>
								</div>

								<div class="menus"><i class="left fa fa-circle-plus"></i><a href="">Create</a></div>
								$refresh
								<div class="menus"><i class="left fa fa-message"></i><a href="">Feedbacks</a></div>
							</div>
						HTML;

						echo $advOptions;
					}
				}
				?>
				<!-- ********************************* Admin Options *************************************************** -->
					

				<!-- Settings-->
				<div class="dropdowns" id="settings" style="display:none">
					<div class="menu-head">
						<span class="name">Settings</span>
					</div>
					<div class="menus" onclick="toggleMode()"> <i class="left fa fa-circle-half-stroke"></i> <span> Dark Mode </span><i class="right fa fa-solid fa-toggle-on fa-lg"  id="toggleMode"></i></div>
					<div class="menus"><i class="left fa fa-table-list"></i><a href="">Reading List</a></div>
					<div class="menus"><i class="left fa fa-icons"></i> <a href="">Manage Interests</a> </div>
					<div class="menus"><i class="left fa fa-message"></i><a href="">Feedback</a></div>
					<div class="menus"> <i class="left fa fa-info-circle"></i><a href="/about/">About Fastreed</a></div>
					<div class="menus"><i class="left fa fa-file-circle-check"></i><a href="/terms-privacy/">Terms and Privacy</a></div>
				</div>
				<!-- ********************************* Settings *************************************************** -->


				<!-- Center Main Bar -->
				<div id="center-block" class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">		    

				    <div class="pin_container">
					
						<!-- 01 -->
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
						<!-- 02 -->
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
					</div>
				</div>
				<!-- ********************************************************************************************************* -->
			</div>
		</div>
	</div>
	
<!-- Global jQuery -->
<script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
<script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/assets/js/fun.js?v=<?php echo $version;?>"></script>
<?php
if ($adminLogged) {
	echo '<script type="text/javascript" src="/assets/js/admin.js?v='.$version.'"></script>';
}
?>
<script type="text/javascript" src="/assets/js/log.js?v=<?php echo $version;?>"></script>

</body>
</html>
