<?php
$_SERVROOT = '../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include ".ht/controller/VISIT.php";

new showIndex();

class showIndex{
    public $version;
    public $captureVisit;

    private $adminLogged = false;
    private $userLogged = false;
    private $webTitle;
    private $webDescription;
    private $webKeywords;
    private $DB_CONN;
    private $AUTH;
   function __construct() {
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));

        $this->userData = new getLoggedData();
        $this->adminLogged = $this->userData->adminLogged;
        $this->userLogged = $this->userData->userLogged;

        $this->webTitle = $this->captureVisit->webTitle;
        $this->webDescription = $this->captureVisit->webDescription;;
        $this->webKeywords = $this->captureVisit->webKeywords;
        $this->canonUrl = "https://www.fastreed.com";


// *************/ Head Section /**************** //
        include ".ht/views/homepage/head.html";
        echo "\n".<<<HTML
        <body class="scrollbar-style">
        HTML."\n".<<<HTML
            <div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
        HTML."\n";
        if ($this->userLogged) {
            if (!isset($this->userData->getSelfDetails()['DOB']) || !isset($this->userData->getSelfDetails['Gender'])) {
                include ".ht/views/homepage/updateProfile.html";
                echo "\n";
            }
        }


    //Header Section printer
        echo <<<HTML
           <header>
        HTML."\n";

        if ($this->userLogged) {
            include ".ht/views/homepage/userHeader.html";
        }else{
            include ".ht/views/homepage/anonHeader.html";
        }
        echo "\n";
        echo <<<HTML
           </header>
        HTML."\n";
// ********************************************** //



//***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include ".ht/views/homepage/dropdowns.html";

    //***************/ Posts Section /**********//
        echo "\n";
        echo <<<HTML
                        <div id="center-block" class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="pin_container">
        HTML."\n";
        include ".ht/views/homepage/content.html";
        echo <<<HTML
                            </div>
                        </div>
        HTML."\n";

    // ***************************************** //

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;
// ********************************************** //



//***************/ Footer Section /*****************//
        echo <<<HTML
        <!-- Global jQuery -->
        <script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/assets/js/style.js?v=$this->version"></script>
        <script type="text/javascript" src="/assets/js/log.js?v=$this->version"></script>
        <script type="text/javascript" src="/assets/js/homepage.js?v=$this->version"></script>
        HTML."\n";
        if ($this->adminLogged) {
            echo <<<HTML
            <script type="text/javascript" src="/assets/js/user.js?v=$this->version"></script>
            <script type="text/javascript" src="/assets/js/admin.js?v=$this->version"></script>
            HTML."\n";

        }elseif ($this->userLogged) {
            echo <<<HTML
            <script type="text/javascript" src="/assets/js/user.js?v=$this->version"></script>
            HTML."\n";
        }
        echo <<<HTML
        </body>
        </html>
        HTML."\n";
        $this->userData->closeConnection();
        $this->captureVisit->closeConnection();
    }
// ********************************************** //
}
?>
