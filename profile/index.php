<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.ht/controller/VISIT.php";
new showProfile();


class showProfile {
    public $version;
    public $captureVisit;
    protected $adminLogged = false;
    protected $userLogged = false;
    protected $DB_CONN;
    protected $AUTH;
    protected $FUNC;
    protected $userData;
    protected $pageCss;
    protected $extraStyle;
    protected $blackMode;
    protected $whiteMode;
    function __construct() {
        $this->const4Inherited();
        if ($this->userLogged && isset($_GET['u'])) {
            new loggedVother();
        }elseif ($this->adminLogged && isset($_GET['u'])) {
            new loggedAdminVother();
        }elseif (isset($_GET['u'])) {
            new nonLoggedVother();
        }elseif ($this->userLogged) {
            new loggedVself();
        }else {
            header("Location:/");
        }
    }
    // This function construct properties and methods for inherited classes
    protected function const4Inherited(){
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        $this->FUNC = new BasicFunctions();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));
        $this->pageCss[0] = '/profile/src/style.css';

        //Create an instance to get logged data
        // This will check weather user is logged or not
        include "../.ht/views/profile/colorMode.html";

        if (!isset($_COOKIE['colorMode'])) {
            $this->extraStyle = $this->blackMode;
        }elseif($_COOKIE['colorMode'] == 'light'){
            $this->extraStyle = $this->lightMode;
        }else{
            $this->extraStyle = $this->blackMode;
        }
        
        $this->userData = new getLoggedData();
        $this->adminLogged = $this->userData->adminLogged;
        $this->userLogged = $this->userData->userLogged;
    } 
   protected function addHead(){
        // *************/ Head Section /**************** //
            include "../.ht/views/homepage/head.html";
            echo "\n".<<<HTML
            <body class="scrollbar-style">
            HTML."\n".<<<HTML
                <div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
            HTML."\n";
            if ($this->userLogged) {
                if (!isset($this->userData->getDetails('self')['DOB']) || !isset($this->userData->getDetails('self')['Gender'])) {
                    include "../.ht/views/homepage/updateProfile.html";
                    echo "\n";
                }
            }


        //Header Section printer
            echo <<<HTML
            <header>
            HTML."\n";
            if ($this->userLogged) {
                include "../.ht/views/homepage/userHeader.html";
            }else{
                include "../.ht/views/homepage/anonHeader.html";
            }
            echo "\n";
            echo <<<HTML
            </header>
            HTML."\n";
        // ********************************************** //
        } 

    protected function addFooter(){
        //***************/ Footer Section /*****************//
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
    // ********************************************** //
    } 
    
}

class loggedAdminVother extends showProfile{
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $this->webTitle = "Add and Edit Your Profile Info";
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";

        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";


        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
   }
}

class loggedVself extends showProfile{
    // Url will be fastreed.com/profile/
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $this->webTitle = "Add and Edit Your Profile Info";
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";

        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";


        //***************/ Profile Section /**********//
        echo "\n";
        
        include "../.ht/views/profile/loggedVself.html";

        // ***************************************** //
        

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
   }
}

class loggedVother extends showProfile{ 
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $this->webTitle = $this->userData->NAME.'. Fastreed User';
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";

        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";


        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
   }
}
?>