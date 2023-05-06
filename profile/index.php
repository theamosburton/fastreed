<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.htactivity/VISIT.php";
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

if ($userLogged) {

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
        <title>Fastreed - Read, Write and Share Greate Ideas and Stories</title>

        <!-- Gobal CSS -->
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
        <!-- Local Css -->
        <link href="src/style.css" rel="stylesheet">
        <!--Fonts-->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" rel="stylesheet">
        <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
    </head>
    <body>

    </body>
</html>