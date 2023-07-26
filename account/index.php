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
    protected $pageJs;
    protected $otherUsername;
    protected $extraScript;
    protected $adminIsEditing;
    private $DOCROOT;
    private $SERVROOT;
    protected $getStoriesData;
    function __construct() {
        
        $this->const4Inherited();
        if ($this->adminLogged && isset($_GET['u']) && $this->checkUserExits($_GET['u'])) {
            $this->adminIsEditing = true;
            new loggedAdminVother();
        }elseif ($this->userLogged && isset($_GET['u']) && $this->checkUserExits($_GET['u'])) {
            new loggedVother();
        }elseif (isset($_GET['u']) && $this->checkUserExits($_GET['u'])) {
            new nonLoggedVother();
        }elseif ($this->userLogged && isset($_GET['u']) && !$this->checkUserExits($_GET['u'])) {
            header("Location:/");
        }elseif($this->userLogged) {
            new loggedVself();
        }else{
            header("Location:/");
        }
    }
    // This function construct properties and methods for inherited classes
    protected function const4Inherited(){
        if (isset($_GET['u'])) {
            $this->otherUsername = $_GET['u'];
        }

        $this->DOCROOT = $_SERVER['DOCUMENT_ROOT'];
        
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        $this->FUNC = new BasicFunctions();
        $DB = new DataBase();
        $this->DB_CONN = $DB->DBConnection();
        $this->AUTH = new Auth();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));

        //Create an instance to get logged data
        // This will check weather user is logged or not
        include "../.ht/views/account/colorMode.html";

        if (!isset($_COOKIE['colorMode'])) {
            $this->extraStyle = $this->blackMode;
        }elseif($_COOKIE['colorMode'] == 'light'){
            $this->extraStyle = $this->lightMode;
        }else{
            $this->extraStyle = $this->blackMode;
        }
        
        $this->userData = new getLoggedData();
        $this->uploadData = new getUploadData();
        $this->adminLogged = $this->userData->adminLogged;
        $this->userLogged = $this->userData->userLogged;
        $this->getStoriesData = new getStoriesData();
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
                if (!isset($this->userData->getSelfDetails()['DOB']) || !isset($this->userData->getSelfDetails()['Gender'])) {
                    include "../.ht/views/homepage/updateProfile.html";
                    echo "\n";
                }
            }

            echo <<<HTML
                    <div id="uploadDP" class="uploadDpDiv">
                        <div class="uploadDpContainer">
                            <i id="cancelDpUpload" onclick="new showMenus().cancelDpUpload()" class="fa fa-xmark"></i>
                        <form action="" enctype="multipart/form-data">
                            <small id="message">Zoom to adjust the photo</small>
                            <label for="uploadInputFile" id="uploadFileLabel"> <i class="fa fa-file-upload"></i> Browse file to upload <small>Photo will be cropped to 1:1</small></label>
                            <div id="croppieContainer"></div>
                            <img id="croppedImage" stye="width:50px; height:50px; display:none;">
                            <input onchange="new showMenus().uploadImage()" type="file" id="uploadInputFile" hidden>
                            <span id="removeImage" onclick="new showMenus().removeImage()">
                                <i class="fa fa-rotate-right"></i>
                            </span>
                            <span id="errorMessage"></span>
                            <span id="uploadDbButton">Upload</span>
                        </form>
                        </div>
                    </div>
                    <div class="imageShowDiv" id="imageShowDiv" style="display:none">
                        <div class="imageContainer">
                        </div>
                    </div>
            HTML;


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
        <!-- Global jS -->
        <script type="text/javascript" src="/assets/js/jquery-1.12.3.min.js"></script>
        <script type="text/javascript" src="/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="/assets/js/style.js?v=$this->version"></script>
        <script type="text/javascript" src="/assets/js/log.js?v=$this->version"></script>
        <script type="text/javascript" src="/assets/js/lazysizes.min.js?v=$this->version"></script>
        HTML."\n";

        if(isset($this->pageJs)){
            if(!(count($this->pageJs) <= 0)){
                for($i =0; $i < count($this->pageJs); $i++){
                    echo '<script type="text/javascript" src="'.$this->pageJs[$i].'?v='.$this->version.'"></script>';
                    echo "\n";
                }
            }
        }
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

    protected function checkUserExits($x){
        $return = false;
        $sql = "SELECT * FROM account_details WHERE username = '$x'";
        $result = mysqli_query($this->DB_CONN, $sql);
        if ($result) {
            if (mysqli_num_rows($result)) {
                $row = mysqli_fetch_assoc($result);
                $return = true;
            }
        }
        return $return;
    }
    public function closeConnection(){
        if ($this->DB_CONN) {
            mysqli_close($this->DB_CONN);
            $this->DB_CONN = null; // Set the connection property to null after closing
        }
    }

}

class loggedAdminVother extends showProfile{
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $ePID = $this->AUTH->encrypt($this->userData->getOtherData('username', $this->otherUsername)['dPID']);
        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name'].'. Fastreed User';
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";
        $this->pageCss = ['/account/src/style.css'];
        
        $this->pageJs = ['/account/src/style.js', '/account/src/editDetails.js', '/assets/js/cropper.js','/account/src/user.js', '/account/src/deleteAccount.js'];
        $this->extraScript = '
        <script> 
            // other
            var ePID = "'.$ePID.'";
            var currentEmail = "'.$this->userData->getOtherData('username', $this->otherUsername)['email'].'"; 
            var currentUsername = "'.$this->userData->getOtherData('username', $this->otherUsername)['username'].'";
         </script>';
       
         $selfId = $this->userData->getSelfDetails()['UID'];
         $otherID = $this->userData->getOtherData('username', $this->otherUsername)['UID'];
         $userSettings = $this->userData->getSettings($otherID);
         $isFollowingMe = $this->userData->isFollowingMe($selfId, $otherID);
         $canViewMail = $userSettings['canViewMail'];
         $canViewAge = $userSettings['canViewAge'];
         $canViewContent = $userSettings['canViewContent'];
         $canViewUploads = $userSettings['canViewUploads'];
         $canCreate = $userSettings['canCreate'];
        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";
        include "../.ht/views/account/adminVOther/index.html";

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
        $this->closeConnection();
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
        $this->pageCss = ['/account/src/style.css'];
        
        $this->pageJs = ['/account/src/style.js', '/account/src/editDetails.js', '/assets/js/cropper.js','/account/src/user.js', '/account/src/deleteAccount.js'];

        $this->extraScript = 
        '<script> 
            var ePID = "'.$this->userData->getSelfDetails()['ePID'].'";
            var currentEmail = "'.$this->userData->getSelfDetails()['email'].'"; 
            var currentUsername = "'.$this->userData->getSelfDetails()['username'].'";
         </script>';


         $selfId = $_SESSION['LOGGED_USER'];
         $userSettings = $this->userData->getSettings($selfId);
         $canViewMail = $userSettings['canViewMail'];
         $canViewAge = $userSettings['canViewAge'];
         $canViewContent = $userSettings['canViewContent'];
         $canViewUploads = $userSettings['canViewUploads'];
         $canCreate = $userSettings['canCreate'];
        $this->addHead();
        $this->DOCROOT = $_SERVER['DOCUMENT_ROOT'];
    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include $this->DOCROOT."/.ht/views/homepage/dropdowns.html";


        //***************/ Profile Section /**********//
        echo "\n";
        
        include $this->DOCROOT."/.ht/views/account/loggedVSelf/index.html";

        // ***************************************** //
        

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
        $this->closeConnection();
   }
}

class loggedVother extends showProfile{ 
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name'].'. Fastreed User';
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";
        $this->pageCss = ['/account/src/style.css'];
        $this->pageJs = ['/account/src/style.js', '/profile/src/user.js', '/account/src/user.js'];

        $this->extraScript = '<script> var ePID = "'.$this->userData->getOtherData('username', $this->otherUsername)['email'].'"; 
        var currentUsername = "'.$this->userData->getOtherData('username', $this->otherUsername)['username'].'";
         </script>';
        $selfId = $this->userData->getSelfDetails()['UID'];
        $otherID = $this->userData->getOtherData('username', $this->otherUsername)['UID'];
        $userSettings = $this->userData->getSettings($otherID);
        $isFollowingMe = $this->userData->isFollowingMe($selfId, $otherID);
        $canViewMail = $userSettings['canViewMail'];
        $canViewAge = $userSettings['canViewAge'];
        $canViewContent = $userSettings['canViewContent'];
        $canViewUploads = $userSettings['canViewUploads'];
        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";
        include "../.ht/views/account/loggedVOther/index.html";

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
        $this->closeConnection();
   }
   
}

class nonLoggedVother extends showProfile{
    protected $webTitle;
    protected $webDescription;
    protected $webKeywords;

   function __construct() {
        $this->const4Inherited();
        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name'].'. Fastreed User';
        $this->webDescription = "Add and Edit Your Profile Info";
        $this->webKeywords = "Add and Edit Your Profile Info";
        $this->pageCss = ['/account/src/style.css'];
        $this->pageJs = ['/account/src/style.js'];


        $otherID = $this->userData->getOtherData('username', $this->otherUsername)['UID'];
        $userSettings = $this->userData->getSettings($otherID);
        $canViewMail = $userSettings['canViewMail'];
        $canViewAge = $userSettings['canViewAge'];
        $canViewContent = $userSettings['canViewContent'];
        $canViewUploads = $userSettings['canViewUploads'];

        $this->addHead();

    //***************/ Main Container Starts /**********//
        echo <<<HTML
            <div class="main-content">
                <div class="container">
                    <div class="row ">
        HTML."\n";
        include "../.ht/views/homepage/dropdowns.html";
        include "../.ht/views/account/nonLoggedVOther/index.html";

        echo <<<HTML
                    </div>
                </div>
            </div>
        HTML;    
    // ********************************************** //
        $this->addFooter();
        $this->closeConnection();
   }
   
}

?>