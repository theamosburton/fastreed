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


        //Create an instance to get logged data
        // This will check weather user is logged or not
        $loggedData = new getLoggedData();

        $this->adminLogged = $loggedData->adminLogged;
        $this->userLogged = $loggedData->userLogged;

        $this->userData = new getLoggedData();

        include ".ht/views/homepage/head.html";
        echo "\n".<<<HTML
        <body class="scrollbar-style">
        HTML."\n".<<<HTML
            <div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
        HTML."\n";
        if ($this->userLogged) {
            if (!isset($this->userData->getDetails('self')['DOB']) || !isset($this->userData->getDetails('self')['Gender'])) {
                include ".ht/views/homepage/updateProfile.html";
                echo "\n";
            }
        }


        // Printer Header Section
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
        // ***************** //

        // Main Container Starts
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include ".ht/views/homepage/dropdowns.html";
        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    

        echo <<<HTML
        <!-- Global jQuery -->
        <script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/assets/js/style.js?v=$this->version"></script>
        <script type="text/javascript" src="/assets/js/log.js?v=$this->version"></script>
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
    }
}
?>