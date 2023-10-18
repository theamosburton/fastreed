<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.ht/controller/VISIT.php";
header('Content-type: application/xml');

new createAuthorsSitemap();

class createAuthorsSitemap{
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
         $storiesList = $this->verifiedStories();
         $this->createXML($storiesList);
         $this->closeConnection();
         $this->userData->closeConnection();
         $this->uploadData->closeConnection();
         $this->BASIC_FUNC->closeConnection();
         $this->captureVisit->closeConnection();
       }
       public function closeConnection(){
           if ($this->DB) {
               mysqli_close($this->DB);
               $this->DB = null; // Set the connection property to null after closing
           }
       }
       private function verifiedStories(){
         $sql = "SELECT FROM metaData WHERE JSON_EXTRACT(moniStatus, '$.status') = true";
         $result = mysqli_query($this->DB, $sql);
         $personIDs = [];
         if ($result) {
           $personIDs = mysqli_fetch_all($result);
         }
         return $personIDs;
      }

      private function createXML($list){
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        for ($i=0; $i < count($list); $i++) {
           $lastMod = $this->lastMod($list[$i][0]);
           $lastMod = gmdate("Y-m-d\TH:i:sP", $lastMod);
           $url = DOMAIN.'/webstories/'.$list[$i][4];
           $xml .= '<url>';
           $xml .= '<loc>' . $url . '</loc>';
            $xml .= '<lastmod>' . $lastMod . '</lastmod>';
           $xml .= '</url>';
        }
        $xml .= '</urlset>';
        echo $xml;
      }

      private function lastMod($id){
        $sql = "SELECT * FROM stories WHERE storyID = '$id'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
          $row = mysqli_fetch_assoc($result);
          $lastmod = $row['lastEdit'];
          $lastModGMT = $this->istToGMT($lastmod);
        }
        return $lastModGMT;
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
