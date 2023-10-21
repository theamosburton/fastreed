<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.ht/controller/VISIT.php";

new storyPreview();

class storyPreview{
    public $version;
    public $captureVisit;
    protected $userLogged = false;
    protected $DB;
    protected $AUTH;
    protected $FUNC;
    protected $userData;
    private $DOCROOT;
    private $SERVROOT;

    function __construct() {
         // Create an instance to create/save activity
         $this->captureVisit = new VisitorActivity();
         $this->BASIC_FUNC = new BasicFunctions();
         $DB = new DataBase();
         $this->DB = $DB->DBConnection();
         $this->AUTH = new Auth();
         // Get css,js version from captureVisit
         $this->version = $this->captureVisit->VERSION;
         $this->version = implode('.', str_split($this->version, 1));
         $this->userData = new getLoggedData();
         $this->uploadData = new getUploadData();
         $this->adminLogged = $this->userData->adminLogged;
         $this->userLogged = $this->userData->userLogged;
         $link = $_GET['url'];
         if($this->getWebstoryData($link)){
           $data = $this->getWebstoryData($link);
           $JSONlayers = $data['storyData'];
           $layers = json_decode($JSONlayers, true);
           $image = $layers['layers']['L0']['media']['url'];
           $title = $data['title'];
           $description = $data['description'];
           $url = $data['url'];
           $keywords = $data['keywords'];
           $unixLastGMT = $this->istToGMT($data['lastEdit']);
           $unixFirstGMT = $this->istToGMT($data['firstEdit']);
           $noIndex = $data['noIndex'];

           // $unixLastGMT = $data['lastEdit'];
           // $unixFirstGMT = $data['firstEdit'];

           $lastmod = gmdate("D, d M Y H:i:s", $unixLastGMT). " GMT";
           $firstmod = gmdate("D, d M Y H:i:s", $unixFirstGMT) . " GMT";
           echo "<script>
           var storyData = ".$JSONlayers.";
           </script>";

           include '../.ht/views/webstories/index.html';
         }
         $this->closeConnection();
         $this->userData->closeConnection();
         $this->uploadData->closeConnection();
         $this->BASIC_FUNC->closeConnection();
         $this->captureVisit->closeConnection();
       }

       function getWebstoryData($link){
         $return = false;
         $sql = "SELECT * FROM metaData WHERE url = '$link'";
         $result = mysqli_query($this->DB, $sql);
         if ($result) {
           if (mysqli_num_rows($result)) {
             $row = mysqli_fetch_assoc($result);
             $postID = $row['postID'];
             $title = $row['title'];
             $description = $row['description'];
             $url = $row['url'];
             $keywords = $row['keywords'];
             $moniStat = $row['moniStatus'];
             $moniStat = json_decode($moniStat);
             $moniStat = $moniStat->status;
             $row = mysqli_fetch_assoc($result);
             $sql1 = "SELECT * FROM stories WHERE storyID = '$postID'";
             $result1 = mysqli_query($this->DB, $sql1);
             if ($result1) {
               if (mysqli_num_rows($result1)) {
                  $webstoryData = mysqli_fetch_assoc($result1);
                  $webstoryData['title'] = $title;
                  $webstoryData['description'] = $description;
                  $webstoryData['url'] = $url;
                  $webstoryData['keywords'] = $keywords;
                  if ($moniStat == "false" || $moniStat == "none") {
                    $webstoryData['noIndex'] = '<meta name="robots" content="noindex">';
                  }else{
                    $webstoryData['noIndex'] = '';
                  }
                  $return = $webstoryData;
               }
             }
           }
         }
         return $return;
       }
       public function closeConnection(){
           if ($this->DB) {
               mysqli_close($this->DB);
               $this->DB = null; // Set the connection property to null after closing
           }
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
