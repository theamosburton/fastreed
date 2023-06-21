<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.ht/controller/VISIT.php";

new createContent();

class createContent{
    public $version;
    public $captureVisit;
    private $userData;
    private $DB_CONN;
    private $AUTH;
    private $FUNC;
   function __construct() {
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        $this->FUNC = new BasicFunctions();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));
        $this->userData = new getLoggedData();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="public, max-age=3600">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="MD. Shafiq Malik">
    <title></title>
    <!-- Gobal CSS -->
    <link rel="shortcut icon" href="/favicon.ico"> 
    <link href="style.css?v=<?php $v = new createContent(); echo $v->version; ?>" rel="stylesheet">
    <link href="/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="/assets/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="editContainer">
        <!-- Left Section -->
        <div class="sections leftSection" id="leftSection">
            <div class="uploadsDiv">
                <div class="uploadHead">
                    <div class="uploadsTitle">Upload New &nbsp;&nbsp;<i class="fa-solid fa-arrow-up-from-bracket"></i></div>
                    <div class="lefthideMe" id="lefthideMe" onclick="hideSection('leftSection', 'hsLeft')">
                        <i class="fa-solid fa-x whatIcon"></i>
                    </div>
                </div>
                <div class="uploads">
                    <div draggable="true" class="uploadContent">
                        <img  src="/assets/img/port11.png">
                        <div class="fileInfo">
                            <i class="fa fa-image fa-sm whatIcon"></i>
                        </div>
                    </div>
                    <div class="uploadContent">
                        <!-- <video src="/assets/VID202306210000000.mp4" draggable="true"></video> -->
                        <div class="fileInfo">
                            <i class="fa fa-film fa-sm whatIcon"></i>
                        </div>
                    </div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                    <div class="uploadContent"></div>
                </div>
            </div>
        </div>
        <div class="hideShow hideShowLeft" id="hsLeft">
            <i class="fa-solid fa-arrow-up-from-bracket whatIcon" onclick="showSection('leftSection', 'hsLeft', 'lefthideMe')"></i>
        </div>
        <!-- Left Section -->

        <!-- Editor Section -->
        <div class="sections editorSection">
            <div class="editorBox" id="editTab" ondrop="dropHandler(event)" ondragover="dragOverHandler(event)">
                <span> Add media</span>
            </div>
            <div class="editorNav">
                <div class="navs backArrow"> <i class="fa-sharp fa-solid fa-angle-left"></i> </div>
                <div class="navs backPlus"><i class="fa fa-plus"></i></div>
                <div class="navs deleteAdd"><i class="fa-regular fa-trash fa-2x"></i></div>
                <div class="navs frontPlus"><i class="fa fa-plus"></i></div>
                <div class="navs frontArrow"><i class="fa-sharp fa-solid fa-angle-right"></i></div>
            </div>
        </div>
        <!-- Editor Section -->

        <!-- Right Section -->
        <div class="hideShow hideShowRight" id="hsRight" onclick="showSection('rightSection', 'hsRight', 'righthideMe')">
            <i class="fa-solid fa-bars whatIcon"></i>
        </div>
        <div class="sections rightSection" id="rightSection">
            <div class="rightDiv">
                <div class="rightHead">
                    <div class="righthideMe" id="righthideMe" onclick="hideSection('rightSection', 'hsRight')">
                        <i class="fa-solid fa-x whatIcon"></i>
                    </div>
                </div>
            </div>
           

        </div>
        <!-- Right Section -->
       
    </div>
</body>
<script src="function.js"></script>
</html>