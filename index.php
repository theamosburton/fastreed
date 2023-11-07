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
    private $DB_CONNECT;
    private $DB;
    private $AUTH;
   function __construct() {
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));
        $this->userData = new getLoggedData();
        $this->AUTH = new Auth();
        $this->whoAmI = $this->userData->whoAmI();
        $this->webTitle = "Create and Publish Visual Stories";
        $this->webDescription = "Discover the world of web storytelling with Fastreed, the premier platform for creating and sharing captivating webstories. Fastreed empowers individuals to unleash their creativity, share their insights, and engage with a global audience. Whether you're a passionate reader, a budding writer, or an expert in your field, Fastreed welcomes everyone to Read, Write, and Share their ideas and knowledge, making it the ultimate destination for webstory enthusiasts.";
        $this->webKeywords = "Webstories, Webstory platform, Fastreed, Creating webstories, Publishing webstories, Read and write webstories, Share ideas and knowledge, Web storytelling, Storytelling platform, Creative writing, Global audience, Engaging content, User-generated stories, Story sharing, Online publishing, Web content creation, Storytelling community, Creative expression, Web-based stories, Fastreed web platform.";
        $this->canonUrl = "https://www.fastreed.com";
        $this->DB_CONNECT = new Database();
        $this->DB = $this->DB_CONNECT->DBConnection();
        // var_dump($this->showLatestToAnon());
        $totalStoriesJSON = []; // Initialize an array to hold the JSON-LD data for all web stories
        $showLastesAnon = $this->showLatestToAnon();
        foreach ($showLastesAnon as $webstoryDataSE) {
            $firstmod = $this->istToGMT($webstoryDataSE['firstPublished']);
            $lastmod = $this->istToGMT($webstoryDataSE['lastPublished']);
            $firstmod = gmdate("D, d M Y H:i:s", $firstmod) . " GMT";
            $lastmod = gmdate("D, d M Y H:i:s", $lastmod) . " GMT";
            if ($webstoryDataSE['moniStatus'] == 'true') {
              $totalStoriesJSON[] = '{
                  "@context": "http://schema.org",
                  "@type": "NewsArticle",
                  "headline": "' . $webstoryDataSE['title'] . '",
                  "description": "' . $webstoryDataSE['description'] . '",
                  "datePublished": "' . $firstmod . '",
                  "url": "https://www.fastreed.com/webstories/' . $webstoryDataSE['url'] . '",
                  "dateModified": "' . $lastmod . '",
                  "author": {
                      "@type": "Person",
                      "name": "' . $webstoryDataSE['authorName'] . '",
                      "url": "https://www.fastreed.com/u/' . $webstoryDataSE['authorUsername'] . '",
                      "image": {
                          "@type": "ImageObject",
                          "url": "https://www.fastreed.com' . $webstoryDataSE['authorProfilePic'] . '",
                          "width": 1,
                          "height": 1
                      }
                  },
                  "image": {
                      "@type": "ImageObject",
                      "url": "https://www.fastreed.com' . $webstoryDataSE['image'] . '",
                      "width": 2,
                      "height": 3
                  }
              }';
            }
            if ($webstoryDataSE !== end($showLastesAnon)) {
                $totalStoriesJSON[] = ', ';
            }
        }


// *************/ Head Section /**************** //
        include ".ht/views/homepage/head.html";
        echo "\n".<<<HTML
        <body class="scrollbar-style">
        HTML."\n".<<<HTML
            <div class="option-overlay" onclick="removeOptions()" id="opt-overlay"></div>
        HTML."\n";
        include ".ht/views/homepage/alerts.html";
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
                              </div>
                              <div class="homepageLoadMore" id="homepageLoader">
                                <span>  Loading.....</span>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;
// ********************************************** //



//***************/ Footer Section /*****************//

        echo <<<HTML
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
        $this->DB_CONNECT->closeConnection();
    }
// ********************************************** //
    public function showLatestToAnon(){
      $sql = "SELECT personID, storyID, firstEdit, lastEdit, storyData  FROM stories WHERE JSON_EXTRACT(storyStatus, '$.status') = 'published'";
      $result = mysqli_query($this->DB, $sql);
      if (!$result) {
        showMessage(false, 'Server Error');
        return;
      }
      $row = mysqli_fetch_all($result, true);
      if (count($row) > 8) {
        $totalStories = 8;
      }else{
        $totalStories = count($row);
      }
      $storiesToRender = [];
      for ($i=0; $i < $totalStories ; $i++) {
        $storyMetaData = $this->getStoryMetaData($row[$i]['storyID']);
        $storiesToRender[$i]['storyID'] = $row[$i]['storyID'];
        $storiesToRender[$i]['firstPublished'] = $row[$i]['firstEdit'];
        $storiesToRender[$i]['lastPublished'] = $row[$i]['lastEdit'];
        $storiesToRender[$i]['personID'] = $this->AUTH->encrypt($row[$i]['personID']);
        $authorData = $this->getAuthorData($row[$i]['personID']);
        $storiesToRender[$i]['authorName'] = $authorData['fullName'];
        $storiesToRender[$i]['authorProfilePic'] = $authorData['profilePic'];
        $storiesToRender[$i]['authorUsername'] = $authorData['username'];
        $storiesToRender[$i]['description'] = $storyMetaData['description'];
        $storiesToRender[$i]['title'] = $storyMetaData['title'];
        $storiesToRender[$i]['category'] = $storyMetaData['category'];
        $storiesToRender[$i]['url'] = $storyMetaData['url'];
        $moniStatus = json_decode($storyMetaData['moniStatus'], true);
        $storiesToRender[$i]['moniStatus'] = $moniStatus['status'];
        $storyData = json_decode($row[$i]['storyData'], true);
        $storiesToRender[$i]['image'] = $storyData['layers']['L0']['media']['url'];
        unset($row[$i]['storyData']);
      }
      usort($storiesToRender, function($a, $b) {
          $timestampA = $a['lastPublished'] / 1000; // Convert milliseconds to seconds
          $timestampB = $b['lastPublished'] / 1000; // Convert milliseconds to seconds
          if ($timestampA == $timestampB) {
              return 0;
          }
          return ($timestampA > $timestampB) ? -1 : 1;
      });
      return $storiesToRender;
    }

    public function getStoryMetaData($storyID){
      $return = [];
      $sql = "SELECT * FROM metaData WHERE postID ='$storyID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        if (mysqli_num_rows($result)) {
          $row = mysqli_fetch_assoc($result);
          $return = $row;
        }
      }
      return $return;
    }
    public function getAuthorData($personID){
      $return = [];
      $sql = "SELECT * FROM account_details WHERE personID ='$personID'";
      $result = mysqli_query($this->DB, $sql);
      if ($result) {
        if (mysqli_num_rows($result)) {
          $row = mysqli_fetch_assoc($result);
          $return = $row;
        }
      }
      return $return;
    }
    private function istToGMT($istUnixTimestamp){
                  // Create a DateTime object with the JavaScript timestamp in milliseconds
         $istDateTime = new DateTime("@" . ($istUnixTimestamp / 1000));

         // Set the input time zone to IST (Indian Standard Time)
         $istTimeZone = new DateTimeZone('Asia/Kolkata');
         $istDateTime->setTimezone($istTimeZone);

         // Set the output time zone to GMT (Greenwich Mean Time)
         $gmtTimeZone = new DateTimeZone('GMT');
         $istDateTime->setTimezone($gmtTimeZone);

         // Get the GMT Unix timestamp in seconds (not milliseconds)
         $gmtUnixTimestamp = $istDateTime->getTimestamp();
      return $gmtUnixTimestamp;
    }
}
?>
