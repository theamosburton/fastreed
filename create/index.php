<?php
$_SERVROOT = '../../';
$_DOCROOT = $_SERVER['DOCUMENT_ROOT'];
include "../.ht/controller/VISIT.php";

$createContent = new createContent();

class createContent{
    public $version;
    public $captureVisit;
    public $userData;
    protected $DB_CONN;
    protected $AUTH;
    protected $FUNC;
    public $uploadData;
   function __construct() {
        // Create an instance to create/save activity
        $this->captureVisit = new VisitorActivity();
        $this->FUNC = new BasicFunctions();
        // Get css,js version from captureVisit
        $this->version = $this->captureVisit->VERSION;
        $this->version = implode('.', str_split($this->version, 1));
        $this->userData = new getLoggedData();
        $this->uploadData = new getUploadData();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="public, max-age=3600">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="MD. Shafiq Malik">
    <title></title>
    <!-- Gobal CSS -->
    <link rel="shortcut icon" href="/favicon.ico"> 
    <link href="style.css?v=<?php $v = new createContent(); echo $v->version; ?>" rel="stylesheet">
    <link href="/assets/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="/assets/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="/assets/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="editContainer">
        <!-- Left Section -->
        <div class="sections leftSection" id="leftSection">
            <div class="uploadsDiv">
                <div class="uploadHead">
                    <div class="uploadsTitle">Upload New &nbsp;&nbsp;<i class="fa-solid fa-arrow-up-from-bracket"></i></div>
                    <div class="lefthideMe" id="lefthideMe" onclick="hideSection('leftSection', 'hsLeft')">
                        <i class="fa-solid fa-x whatIcon"></i>
                    </div>
                </div>

                <div class="uploads">
                    <?php
                    $id = $createContent->userData->getSelfDetails()['UID'];
                    $username = $createContent->userData->getSelfDetails()['username'];
                    $data = $createContent->uploadData->getAllData($id);
                    $data = array_reverse($data);
                    $length = count($data);
                    for($i=0;$i < $length; $i++ ){
                        $idn = $i+1;
                        $self = '';
                        $everyone = '';
                        $followers = '';
                        $self = '';
                        if($data[$i][8] == 'followers'){
                        $followers = 'selected';
                        }elseif($data[$i][8] == 'everyone'){
                        $everyone = 'selected';
                        }elseif($data[$i][8] == 'self'){
                            $self = 'selected';
                        }
                        $pathImg = '/uploads/photos/'.$username.'/'.$data[$i][2].$data[$i][7];
                        $pathVid = '/uploads/videos/'.$username.'/'.$data[$i][2].$data[$i][7];
                        $whatToShow;
                        $onclick;
                        $what = '';
                        if($data[$i][6] == 'photos'){
                            $whatToShow = <<<HTML
                                <div draggable="true" class="uploadContent" id="media{$i}" onclick="selectMedia('{$i}', '{$pathImg}', 'image')">
                                    <img src="{$pathImg}" alt="">
                            HTML;
                            $what = 'image';
                            $icon = 'image';
                        }elseif($data[$i][6] == 'videos'){
                            $whatToShow = <<<HTML
                                <div draggable="true" class="uploadContent" id="media{$i}" onclick="selectMedia('{$i}', '{$pathVid}', 'video')">
                                    <video><source src="{$pathVid}" type="video/mp4"></video>
                            HTML;
                            $what = 'video';
                            $icon = 'film';
                        }
                        
                        echo <<<HTML
                                {$whatToShow}
                                    <div class="fileInfo">
                                        <i class="fa fa-{$what} fa-sm whatIcon"></i>
                                    </div>
                                </div>
                        HTML;
                    }
                    ?>

                    <div class="uploads">
                        <div draggable="true" class="uploadContent" id="media{$i}" onclick="selectMedia('{$i}', '{$pathVid}', 'video')">
                            <video><source src="{$pathVid}" type="video/mp4"></video>
                            <div class="fileInfo">
                                <i class="fa fa-{$what} fa-sm whatIcon"></i>
                            </div>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="hideShow hideShowLeft" id="hsLeft">
            <i class="fa-solid fa-arrow-up-from-bracket whatIcon" onclick="showSection('leftSection', 'hsLeft', 'lefthideMe')"></i>

        </div>
        <!-- Left Section -->

        <!-- Editor Section -->
        <div class="sections editorSection">
            <div class="editorBox" id="editTab" ondrop="dropHandler(event)" ondragover="dragOverHandler(event)">
                <span > Add media</span>
            </div>
            <div class="editorNav">
                <div class="navs backArrow"> <i class="fa-sharp fa-solid fa-angle-left"></i> </div>
                <div class="navs backPlus"><i class="fa fa-plus"></i></div>
                <div class="navs deleteAdd"><i class="fa-regular fa-trash fa-2x"></i></div>
                <div class="navs frontPlus"><i class="fa fa-plus"></i></div>
                <div class="navs frontArrow"><i class="fa-sharp fa-solid fa-angle-right"></i></div>
            </div>
        </div>
        <!-- Editor Section -->

        <!-- Right Section -->
        <div class="hideShow hideShowRight" id="hsRight" onclick="showSection('rightSection', 'hsRight', 'righthideMe')">
            <i class="fa-solid fa-bars whatIcon"></i>
        </div>
        <div class="sections rightSection" id="rightSection">
            <div class="rightDiv">
                <div class="rightHead">
                    
                    <div class="righthideMe" id="righthideMe" onclick="hideSection('rightSection', 'hsRight')">
                        <i class="fa-solid fa-x whatIcon"></i>
                    </div>
                    <div class="buttonsDiv">
                        <div class="buttons">Draft</div>
                        <div class="buttons">Save</div>
                        <div class="buttons">Publish</div>
                    </div>
                </div>
            </div>
           

        </div>
        <!-- Right Section -->
       
    </div>
</body>
<script src="function.js?v=<?php $v = new createContent(); echo $v->version; ?>"></script>
</html>