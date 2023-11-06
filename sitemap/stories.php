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
    public $DB_CONNECT;
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
         $this->DB_CONNECT = new DataBase();
         $this->DB = $this->DB_CONNECT->DBConnection();
         $this->AUTH = new Auth();
         // Get css,js version from captureVisit
         $this->version = $this->captureVisit->VERSION;
         $this->version = implode('.', str_split($this->version, 1));
         $this->userData = new getLoggedData();
         $this->uploadData = new getUploadData();
         $storiesList = $this->verifiedStories();
         $storiesList = array_reverse($storiesList);
         $this->createXML($storiesList);
         $this->DB_CONNECT->closeConnection();
         $this->userData->DB_CONNECT->closeConnection();
         $this->uploadData->DB_CONNECT->closeConnection();
         $this->BASIC_FUNC->DB_CONNECT->closeConnection();
       }
       public function closeConnection(){
           if ($this->DB) {
               mysqli_close($this->DB);
               $this->DB = null; // Set the connection property to null after closing
           }
       }
       private function verifiedStories(){
         $sql = "SELECT * FROM metaData WHERE JSON_EXTRACT(moniStatus, '$.status') = 'true'";
         $result = mysqli_query($this->DB, $sql);
         $personIDs = [];
         if ($result) {
           $personIDs = mysqli_fetch_all($result);

         }
         return $personIDs;
      }

      private function createXML($list){
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">';
        for ($i=0; $i < count($list); $i++) {
           $lastmod = $this->storyData($list[$i][0])['lastEdit'];
           $lastmod = $this->istToGMT($lastmod);
           $lastmod = gmdate("Y-m-d\TH:i:sP", $lastmod);
           $url = DOMAIN.'/webstories/'.$list[$i][4];
           $xml .= '<url>';
           $xml .= '<loc>' . $url . '</loc>';
           $xml .= '<news:news>';
           $xml .= '<news:publication>';
           $xml .= '<news:name>Fastreed</news:name>';
           $xml .= '<news:language>en</news:language>';
           $xml .= '</news:publication>';
           $xml .= '<news:publication_date>' . $lastmod . '</news:publication_date>';
           $xml .= '<news:title>' . $list[$i][1] . '</news:title>';
           $xml .= '</news:news>';
           $xml .= '</url>';
        }
        $xml .= '</urlset>';
        echo $xml;
      }

      private function storyData($id){
        $sql = "SELECT * FROM stories WHERE storyID = '$id'";
        $result = mysqli_query($this->DB, $sql);
        if ($result) {
          $row = mysqli_fetch_assoc($result);
        }
        return $row;
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
