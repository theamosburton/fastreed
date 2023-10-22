<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include $_DOCROOT."/.ht/controller/VISIT.php";
header('Content-type: application/xml');

new createUsersSitemap();

class createUsersSitemap{
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
         $usernameList = $this->usersList();
         $this->createXML($usernameList);
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
       private function usersList(){
         $sql = "SELECT personID FROM settings";
         $result = mysqli_query($this->DB, $sql);
         $usernames = [];
         if ($result) {
           $personIDs = mysqli_fetch_all($result);
           for ($i=0; $i < mysqli_num_rows($result); $i++) {
             $personID = $personIDs[$i][0];
             $sql1 = "SELECT username FROM account_details WHERE personID = '$personID'";
             $result1 = mysqli_query($this->DB, $sql1);
             $row = mysqli_fetch_assoc($result1);
             $usernames[$i] = $row;
           }
         }
         return $usernames;
      }

      private function createXML($list){
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        for ($i=0; $i < count($list); $i++) {
           $url = DOMAIN.'/u/'.$list[$i]['username'];
           $xml .= '<url>';
           $xml .= '<loc>' . $url . '/</loc>';
           $xml .= '</url>';
        }
        $xml .= '</urlset>';
        echo $xml;
      }
}
 ?>
