<?php
$_SERVROOT = '../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include ".ht/controller/VISIT.php";

new showIndex();

class showIndex{
    public $version;
    public $captureVisit;

    private $whoAmI;
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
        $this->whoAmI = $this->userData->whoAmI();

        $this->webTitle = "Create and Publish Visual Stories";
        $this->webDescription = "Discover the world of web storytelling with Fastreed, the premier platform for creating and sharing captivating webstories. Fastreed empowers individuals to unleash their creativity, share their insights, and engage with a global audience. Whether you're a passionate reader, a budding writer, or an expert in your field, Fastreed welcomes everyone to Read, Write, and Share their ideas and knowledge, making it the ultimate destination for webstory enthusiasts.";
        $this->webKeywords = "Webstories, Webstory platform, Fastreed, Creating webstories, Publishing webstories, Read and write webstories, Share ideas and knowledge, Web storytelling, Storytelling platform, Creative writing, Global audience, Engaging content, User-generated stories, Story sharing, Online publishing, Web content creation, Storytelling community, Creative expression, Web-based stories, Fastreed web platform.";
        $this->canonUrl = "https://www.fastreed.com";


// *************/ Head Section /**************** //
        include ".ht/views/homepage/head.html";
        echo "\n".<<<HTML
        <body class="scrollbar-style">
        HTML."\n".<<<HTML
            <div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
        HTML."\n";
        if ($this->whoAmI != 'Anonymous') {
            if (!isset($this->userData->getSelfDetails()['DOB']) || !isset($this->userData->getSelfDetails['Gender'])) {
                include ".ht/views/homepage/updateProfile.html";
                echo "\n";
            }
        }


    //Header Section printer
        echo <<<HTML
           <header>
        HTML."\n";

        if ($this->whoAmI != 'Anonymous') {
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

    //***************/ Stories Section /**********//
        echo "\n";
        echo <<<HTML
                        <div id="center-block" class="content col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 0px; padding-right: 0px">
                            <div>
                              <div class="homePageFilter" id="homePageFilter">
                                <div class="navs active">Latest
                                  <i class="fa fa-solid fa-check-circle"></i>
                                </div>
                              </div>
                              <div class="pin_container" id="storiesDiv">
        HTML."\n";
        include ".ht/views/homepage/stories.html";
        echo <<<HTML
                              </div>
                              <div class="homepageLoadMore" id="homepageLoader">
                                <span>  Loading.....</span>
                              </div>
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
          <script type="text/javascript" src="/assets/js/homepageLoader.js?v=$this->version"></script>
        HTML."\n";
        if ($this->whoAmI == 'Admin') {
            echo <<<HTML
            <script type="text/javascript" src="/assets/js/user.js?v=$this->version"></script>
            <script type="text/javascript" src="/assets/js/admin.js?v=$this->version"></script>
            HTML."\n";

        }elseif ($this->whoAmI == 'User') {
            echo <<<HTML
            <script type="text/javascript" src="/assets/js/user.js?v=$this->version"></script>
            HTML."\n";
        }
        echo <<<HTML
        </body>
        </html>
        HTML."\n";
        $this->userData->DB_CONNECT->closeConnection();

    }
// ********************************************** //
}
?>
