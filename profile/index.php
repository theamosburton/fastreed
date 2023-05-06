<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.htactivity/VISIT.php";
ini_set('display_errors', 1);
error_reporting(E_ALL);

$visit = new VisitorActivity();
$basic_func = new BasicFunctions();
$DB_CONNECT = new Database();
$DB = $DB_CONNECT->DBConnection();
$version = $visit->VERSION;
$version = implode('.', str_split($version, 1));
$userLogged = false;
$adminLogged = false;
if(isset($_SESSION['LOGGED_USER'])){
	$userData = new getLoggedData();
	if ($userData->U_AUTH) {
		$userLogged = true;
		if ($userData->getAccess()['userType'] == 'Admin') {
            $type = 'Admin';
			$adminLogged = true;
		}else {
            $type = 'User';
        }
	}
}

if (isset($_GET['u']) && !empty($_GET['u'])) {
    if ($userDtails = getUserDetails($_GET['u'], $DB)) {
         
        if ($userLogged && $adminLogged) {
            var_dump($userDtails);
            $description = <<<HTML
             HTML;
             $title = <<<HTML
             HTML;
            
        }else if($userLogged){
            // If user is logged and want to watch other profile
            $description = <<<HTML
             HTML;
             $title = <<<HTML
             HTML;
           
        }else {
             // If user is not logged and want to watch other profile
             $description = <<<HTML
             HTML;
             $title = <<<HTML
             HTML;
        }
    }else {
        // No Other User found with this id
    }
}else if($userLogged){
    // If user is logged and want to watch own profile
    /*** Making Head ****/
    $title = <<<HTML
    <title>$userData->NAME - Fastreed $type</title>
    HTML;
     // If bio is not set by user
     if (strlen($userData->getDetails()['bio']) < 2) {
        $description = <<<HTML
            <meta name="description" content="$userData->NAME is a  $type of Fastreed">
            <meta name="keywords" content="$userData->NAME is a  $type of Fastreed">
        HTML;
     }else {
        $description = <<<HTML
            <meta name="description" content="$userData->getAccess()['bio']">
            <meta name="keywords" content="$userData->getAccess()['bio']">
        HTML;
     }
    /********************************************************/
    
}else {
    header("Location:/");
    exit();
}

function getUserDetails($username, $DB){
    $sql = "SELECT * FROM account_details WHERE username = '$username'";
    $result = mysqli_query($DB, $sql);
    if ($result) {
        if (mysqli_num_rows($result)) {
            $row = mysqli_fetch_assoc($result);
            $name = $row['fullName'];
            $profilePic = $row['profilePic'];
            $userSince = $row['userSince'];
            $username = $row['username'];
            $DOB = $row['DOB'];
            $Gender = $row['gender'];
            $accountType = $row['accType'];
            $userSince = $row['userSince'];
            $bio = $row['bio'];
            $today = new DateTime();
            $diff = $today->diff(new DateTime($DOB));
            $age = $diff->y;
            $return = array("name"=>$name, "username"=>$username, "profilePic"=>$profilePic, "userSince"=>$userSince, "age"=>$age, "Gender"=>$Gender,"userType" => $accountType, "userSince" => $userSince, "bio"=>$bio);
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