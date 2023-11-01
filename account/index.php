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
    protected $userImage;
    private $DOCROOT;
    private $SERVROOT;
    protected $profileUsername;
    protected $userFullname;
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
            header("Location:/account/sign/");
        }elseif($this->userLogged) {
            new loggedVself();
        }else{
            header("Location:/account/sign");
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
        $this->whoAmI = $this->userData->whoAmI();
        if ($this->whoAmI == 'Admin') {
          $this->adminLogged = true;
          $this->userLogged = true;
        }elseif ($this->whoAmI == 'User') {
          $this->adminLogged = false;
          $this->userLogged = true;
        }else{
          $this->adminLogged = false;
          $this->userLogged = false;
        }
        $this->getStoriesData = new getStoriesData();
    }
    protected function addHead(){
        // *************/ Head Section /**************** //
            include "../.ht/views/account/metaTags.html";

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
                          <div class="uploadTopBar">
                              <i id="cancelDpUpload" onclick="new showMenus().cancelDpUpload()" class="fa fa-xmark"></i>
                          </div>
                        <form action="" enctype="multipart/form-data">
                            <small id="message">Zoom to adjust the photo</small>
                            <label for="uploadInputFile" id="uploadFileLabel"> <i class="fa fa-file-upload"></i> Browse file to upload <small>Photo will be cropped to 1:1</small></label>
                            <div id="croppieContainer"></div>
                            <img id="croppedImage" stye="width:50px; height:50px; display:none;">
                            <input onchange="new showMenus().uploadProfile()" type="file" id="uploadInputFile" hidden>
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
        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name']. ' - Fastreed User';
        $this->userImage = $this->userData->getOtherData('username', $this->otherUsername)['profilePic'];
        $this->canonUrl = 'https://fastreed.com/u/'.$this->otherUsername.'/';
        $this->webDescription = "Fastreed Account : Manage, edit and view profile";
        $this->webKeywords = "Fastreed Account : Manage, edit and view profile";
        $this->pageCss = ['/account/src/style.css'];
        $this->userFullname = $this->userData->getOtherData('username', $this->otherUsername)['name'];


        $this->pageJs = ['/account/src/style.js', '/account/src/editDetails.js', '/assets/js/cropper.js','/account/src/user.js', '/account/src/deleteAccount.js', '/account/src/adminVOtherStories.js'];
        $this->extraScript = '
        <script>
            // other
            var ePID = "'.$ePID.'";
            var currentEmail = "'.$this->userData->getOtherData('username', $this->otherUsername)['email'].'";
            var currentUsername = "'.$this->userData->getOtherData('username', $this->otherUsername)['username'].'";
         </script>';
        $this->structure = '';

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
        $this->webTitle = "Fastreed Account : Manage, edit and view profile";
        $this->webDescription = "Manage, edit and view your profile and request for creator access from here";
        $this->webKeywords = "Manage, edit and view profile, fastreed account setting, updating account details, request for creater access";
        $this->userImage = $this->userData->getSelfDetails()['profilePic'];
        $this->profileUsername = $this->userData->getSelfDetails()['username'];
        $this->canonUrl = 'https://fastreed.com/account/';
        $this->pageCss = ['/account/src/style.css'];
        $this->pageJs = ['/account/src/review.js','/account/src/style.js', '/account/src/editDetails.js', '/assets/js/cropper.js','/account/src/user.js', '/account/src/deleteAccount.js', '/account/src/adminVOtherStories.js', '/account/src/selfStories.js'];
        $this->userFullname = $this->userData->getSelfDetails()['name'];




        $this->extraScript =
        '<script>
            var ePID = "'.$this->userData->getSelfDetails()['ePID'].'";
            var currentEmail = "'.$this->userData->getSelfDetails()['email'].'";
            var currentUsername = "'.$this->userData->getSelfDetails()['username'].'";
         </script>';

         $this->structure = '';

         $selfId = $_SESSION['LOGGED_USER'];
         $userSettings = $this->userData->getSettings($selfId);
         $canViewMail = $userSettings['canViewMail'];
         $canViewAge = $userSettings['canViewAge'];
         $canViewContent = $userSettings['canViewContent'];
         $canViewUploads = $userSettings['canViewUploads'];
         $canCreate = $userSettings['canCreate'];
         $rejectionReason = $userSettings['rejectionReason'];

         if ($rejectionReason == NULL) {
           $rejectionReason = 'Not Given';
         }
        $this->userImage = $this->userData->getSelfDetails()['profilePic'];
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
        $selfId = $this->userData->getSelfDetails()['UID'];
        $otherID = $this->userData->getOtherData('username', $this->otherUsername)['UID'];
        $userSettings = $this->userData->getSettings($otherID);
        $isFollowingMe = $this->userData->isFollowingMe($selfId, $otherID);
        $canViewMail = $userSettings['canViewMail'];
        $canViewAge = $userSettings['canViewAge'];
        $canViewContent = $userSettings['canViewContent'];
        $canViewUploads = $userSettings['canViewUploads'];
        $canCreate = $userSettings['canCreate'];
        if ($canCreate == 'ACC') {
          $userStatus = 'Author';
        }else{
          $userStatus = 'User';
        }

        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name'].' - Fastreed '.$userStatus;
        $this->canonUrl = 'https://fastreed.com/u/'.$this->otherUsername.'/';
        $this->webDescription = "Fastreed Account: View profile";
        $this->webKeywords = "Fastreed Account: View profile";
        $this->userImage = $this->userData->getOtherData('username', $this->otherUsername)['profilePic'];
        $this->userFullname = $this->userData->getOtherData('username', $this->otherUsername)['name'];

        $this->pageCss = ['/account/src/style.css'];
        $this->pageJs = ['/account/src/style.js', '/profile/src/user.js', '/account/src/user.js', '/account/src/viewStories.js'];

        $this->extraScript = '<script> var ePID = "'.$this->userData->getOtherData('username', $this->otherUsername)['email'].'";
        var currentUsername = "'.$this->userData->getOtherData('username', $this->otherUsername)['username'].'";
         </script>';
        $this->structure = '';
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
        $otherID = $this->userData->getOtherData('username', $this->otherUsername)['UID'];
        $userSettings = $this->userData->getSettings($otherID);
        $canViewMail = $userSettings['canViewMail'];
        $canViewAge = $userSettings['canViewAge'];
        $canViewContent = $userSettings['canViewContent'];
        $canViewUploads = $userSettings['canViewUploads'];
        $canCreate = $userSettings['canCreate'];
        if ($canCreate == 'ACC') {
          $userStatus = 'Auhtor';
        }else{
          $userStatus = 'User';
        }
        $this->webTitle = $this->userData->getOtherData('username', $this->otherUsername)['name'].' - Fastreed '.$userStatus;
        $this->canonUrl = 'https://fastreed.com/u/'.$this->otherUsername.'/';
        $this->webDescription = "Fastreed Account: View profile";
        $this->webKeywords = "Fastreed Account: View profile";
        $this->userImage = $this->userData->getOtherData('username', $this->otherUsername)['profilePic'];
        $this->userFullname = $this->userData->getOtherData('username', $this->otherUsername)['name'];
        $webStories = $this->getWebstories($otherID);
        $this->pageCss = ['/account/src/style.css'];
        $this->pageJs = ['/account/src/style.js', '/account/src/viewStories.js'];
        $webStories = $this->getWebstories($otherID);
        $allStories = [];
        for ($i=0; $i < count($webStories) ; $i++) {
          $uniqueUrl = $webStories[$i][9];
          $uniqueData = json_decode($webStories[$i][6], true);
          $uniqueTitle = $uniqueData['metaData']['title'];
          $uniqueDescription = $uniqueData['metaData']['description'];
          $uniqueMedia = $uniqueData['layers']['L0']['media']['url'];
          $publishedData =  json_decode($webStories[$i][5], true);
          $verificationData =  json_decode($webStories[$i][8], true);
          $publishedData = $publishedData['status'];
          $verificationData = $verificationData['status'];
          if ($publishedData == 'published') {
            $allStories[$i]['url'] = 'https://www.fastreed.com/webstories/'.$uniqueUrl .'/';
            $allStories[$i]['title'] = $uniqueTitle;
            $allStories[$i]['image'] = $uniqueMedia;
            $allStories[$i]['description'] = $uniqueDescription;
          }
        }
        $this->extraScript = '
        <script>
            var currentUsername = "'.$this->userData->getOtherData('username', $this->otherUsername)['username'].'";
            var userFullname = "'.$this->userData->getOtherData('username', $this->otherUsername)['name'].'";
         </script>';

         // Start building the JSON-LD structured data script
       $this->structure = '
       <script data-rh="true" type="application/ld+json">
         {
           "@context":"https://schema.org",
           "@type":"Organization",
           "name":"Fastreed",
           "alternateName":"fastreed",
           "description":"Discover the world of web storytelling with Fastreed, the premier platform for creating and sharing captivating webstories. Fastreed empowers individuals to unleash their creativity, share their insights, and engage with a global audience. Whether you are a passionate reader, a budding writer, or an expert in your field, Fastreed welcomes everyone to Read, Write, and Share their ideas and knowledge, making it the ultimate destination for webstory enthusiasts.",
           "logo":"https://www.fastreed.com/assets/img/logo500x500.jpg",
           "url":"https://www.fastreed.com",
           "contactPoint": {
             "@type": "ContactPoint",
             "email": "support@fastreed.com",
             "contactType": "customer service"
             }
         }
       </script>

       <script data-rh="true" type="application/ld+json">
       {
           "@context": "http://schema.org",
           "@type": "ProfilePage",
           "mainEntityOfPage": {
               "@type": "webSite",
               "@id": "'.$this->canonUrl.'"
           },
           "name": "'.$this->userFullname.'",
           "description": "'.$this->userFullname.' is a user at Fastreed. Check out the latest visual stories written.",
           "author": {
               "@type": "Person",
               "image": "'.$this->userImage.'",
               "name": "'.$this->otherUsername.'"
           },
           "url": "'.$this->canonUrl.'",
           "hasPart": [';

           // Add each web story to the "hasPart" property
           foreach ($allStories as $story) {
               $this->structure .= '{
                   "@type": "CreativeWork",
                   "name": "'.$story['title'].'",
                   "url": "'.$story['url'].'",
                   "description": "'.$story['description'].'",
                   "image": "'.$story['image'].'"
               }';

               // Add a comma if there are more web stories
               if ($story !== end($allStories)) {
                   $this->structure .= ', ';
               }
           }

       // Complete the JSON-LD script
       $this->structure .= '
           ]
       }
       </script>';

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

   private function getWebstories($UID){
     $row = [];
     $sql = "SELECT * FROM stories WHERE personID = '$UID'";
     $result = mysqli_query($this->DB_CONN, $sql);
     if ($result) {
        $row = mysqli_fetch_all($result);
        for ($i=0; $i <  mysqli_num_rows($result); $i++) {
          $storyID = $row[$i][1];
          $sql1 = "SELECT * FROM metaData WHERE postID = '$storyID'";
          $result1 = mysqli_query($this->DB_CONN, $sql1);
          if ($result1) {
            $row1 = mysqli_fetch_assoc($result1);
            $moniStatus = $row1['moniStatus'];
            $url = $row1['url'];
            $row[$i][8] = $moniStatus;
            $row[$i][9] = $url;
          }
        }
     }
     return $row;
   }

}

?>
