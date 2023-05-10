<?php
$_SERVROOT = '../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include ".ht/controller/VISIT.php";
include ".ht/views/homepage.php";
include ".ht/views/impTags.php";
new showIndex();

class showIndex{
    public $head = [];
    public $header = [];
    public $mainBody = [];
    public $footerScripts = [];

    private $adminLogged = false;
    private $userLogged = false;

    private $DB_CONN;
    private $AUTH;
   function __construct() {
        // Create an instance to create/save activity
        $captureVisit = new VisitorActivity();

        // Get css,js version from captureVisit
        $version = $captureVisit->VERSION;

        //Create an instance to get logged data
        // This will check wether user is logged or not
        $loggedData = new getLoggedData();

        $this->adminLogged = $loggedData->adminLogged;
        $this->userLogged = $loggedData->userLogged;
        if ($this->adminLogged) {
            // Admin is logged
        }elseif ($this->userLogged) {
            // Normal user is logged
        }else {
            // Anonymous user is visited
            $this->renderAnonPage();
        }
    }
    
    public function renderAnonPage(){
        echo $GLOBALS['htmlOpen']. "\n";
        echo $GLOBALS['headClose']. "\n";
        echo $GLOBALS['htmlClose'];
    }


}
?>