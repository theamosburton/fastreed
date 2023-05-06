<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.htactivity/VISIT.php";
// include $_SERVROOT."secrets/AUTH.php";

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

if (isset($_GET['u']) && !empty($_GET['u'])) {
    if ($userDtails = getUserDetails($_GET['u'])) {
         
        if ($userLogged && $adminLogged) {
            var_dump($userDtails);
            
        }else if($userLogged){
            // If user is logged and want to watch other profile
           
        }else {
             // If user is not logged and want to watch other profile
        }
    }else {
        // No Other User found with this id
    }
}else if($userLogged){
    // If user is logged and want to watch own profile
        /*** Making Head ****/
        $title = <<<HTML
        <title>$userData->NAME - Fastreed User</title>
     HTML;
     // If bio is not set by user
     if (strlen($userData->getAccess()['bio']) < 2) {
        $description = <<<HTML
            <meta name="description" content="$userData->NAME is a writer at Fastreed">
            <meta name="keywords" content="$userData->NAME is a writer at Fastreed">
        HTML;
     }else {
        $description = <<<HTML
            <meta name="description" content="$userData->getAccess()['bio']">
            <meta name="keywords" content="$userData->getAccess()['bio']">
        HTML;
     }
    /********************************************************/
    
}else {
    header("Location :discover");
    exit();
}

function getUserDetails($email){
    $sql = "SELECT * FROM account_details WHERE personID = '$decUserID'";
    $result = mysqli_query($visit->DB, $sql);
    if ($result) {
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $DOB = $row['DOB'];
            $Gender = $row['gender'];
            $accountType = $row['accType'];
            $userSince = $row['userSince'];
            $bio = $row['bio'];
            $return = array("DOB"=>$DOB, "Gender"=>$Gender,"userType" => $accountType, "userSince" => $userSince, "bio"=>$bio);
        }else {
            $return = false;
        }
    }else {
        $return = false;
    }

    return $return;
  }
?>
<!DOCTYPE html> 
<html lang="en">  
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php
            echo $description."\n";
            echo $title;
        ?>

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