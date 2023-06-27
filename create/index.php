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
    <script>
        <?php
            $v = new createContent();
            echo 'var ePID = "'.$v->userData->getSelfDetails()['ePID'].'";';
            if ($v->userData->getSelfDetails()['userType'] == 'Admin') {
                echo 'var whoIs = "admin"';
            }elseif ($v->userData->getSelfDetails()['userType'] == 'User') {
                echo 'var whoIs = "user"';
            }
        ?>
    </script>
</head>
<body>
    <div class="editContainer">
        <!-- Left Section -->
        <div class="sections leftSection" id="leftSection">
            <div class="uploadsDiv" id="uploadDiv">
                <div class="uploadHead">
                    <div class="top">
                        <div class="uploadsTitle">Media Library</div>
                            <div class="lefthideMe" id="lefthideMe" onclick="hideSection('leftSection')">
                                <i class="fa-solid fa-x whatIcon"></i>
                            </div>
                        </div>
                    <div class="refreshUpload" >
                        <div class="uploadNew" id="uploadNew">
                            <div>
                                <label for="uploadNewMedia">Upload New</label>
                                <input onchange="new uploadMedia()" type="file" id="uploadNewMedia" hidden="">
                                <i class="fa fa-plus-square"></i>
                            </div>
                        </div>
                        <div class="refreshDiv">
                            <i onclick="uploadsDataClass.fetchUploads()" class="fa-solid fa-arrows-rotate" id="rotateRefresh"></i>
                        </div>
                    </div>
                    <div class="uploadingBar" id="uploadingBar" style="display:none">
                        <div class="uploadMessage" id="uploadMessage"></div>
                        <div class="uploadProgress" id="uploadProgress" >
                            <div style="display:none"></div>
                        </div> 
                    </div>
                </div>
                <div class="uploads" id="uploads">
                    <!-- Uploads will be set here -->
                </div>
            </div>
        </div>

        <div class="hideShow hideShowLeft" id="hsLeft">
            <i class="fa-solid fa-arrow-up-from-bracket whatIcon" onclick="showSection('leftSection', 'lefthideMe')"></i>

        </div>
        <!-- Left Section -->

        <!-- Editor Section -->
        <div class="sections editorSection">
            <div class="layersNumber" id="layerCount">1/1</div>
            <div class="editorBox" id="editTab">
            </div>
            <div class="editorNav">
                <div class="navs backArrow" onclick="layers.moveBackward()"> <i class="fa-sharp fa-solid fa-angle-left"></i> </div>
                <div class="navs minus" id="minusIcon" onclick="layers.deleteLayer()"><i class="fa fa-minus-circle"></i></div>
                <div class="navs deleteAdd" id="deleteMedia"><i class="fa-regular fa-trash fa-2x"></i></div>
                <div class="navs frontPlus" id="plusIcon" onclick="layers.createNewLayer()" ><i class="fa fa-plus-circle"></i></div>
                <div class="navs frontArrow" onclick="layers.moveForward()"><i class="fa-sharp fa-solid fa-angle-right"></i></div>
            </div>
        </div>
        <!-- Editor Section -->

        <!-- Right Section -->
        <div class="hideShow hideShowRight" id="hsRight" onclick="showSection('rightSection', 'righthideMe')">
            <i class="fa-solid fa-bars whatIcon"></i>
        </div>
        <div class="sections rightSection" id="rightSection">
            <div class="rightDiv">
                <div class="rightHead">
                    <div class="top">
                        <div class="righthideMe" id="righthideMe" onclick="hideSection('rightSection')">
                            <i class="fa-solid fa-x whatIcon"></i>
                        </div>
                        <div class="buttonsDiv">
                            <div class="buttons">Draft</div>
                            <div class="buttons">Save</div>
                            <div class="buttons">Publish</div>
                        </div>
                    </div>
                    

                </div>
                <div id="selectedObject" class="selectedObject">
                    <div class="objectHead">
                        <span>LAYER 1</span>
                    </div>

                    <div class="objectOptions">
                        <div class="objectOptionsmenus">
                            <div class="objectImage">MEDIA</div>
                            <div class="objectText">TEXT</div>
                            <div class="objectCaption">CAPTION</div>
                        </div>

                        <div class="objectOptionsbody" style="display:flex;">
                            <div class="options">
                                <span class="property">Text</span>
                                <input type="text" class="value inputText">
                            </div>

                            <div class="options">
                                <span class="property">Colors</span>
                                <div class="div">
                                    <span>Text</span>
                                    <input class="value inputText" type="color" id="favcolor" name="favcolor">
                                </div>
                                <div class="div">
                                    <span>Background</span>
                                    <input class="value inputText" type="color" id="favcolor" name="favcolor">
                                </div>
                            </div>

                            <div class="options">
                                <span class="property">Background Opacity</span>
                                <input class="value inputText" type="range" id="fontSize" name="points" min="-2" max="4">
                            </div>

                            <div class="options">
                                <span class="property">Font Wieght</span>
                                <select name="" id="" class="value inputText">
                                    <option value="">Light</option>
                                    <option value="">Bold</option>
                                    <option value="">Bolder</option>
                                </select>
                            </div>

                            <div class="options">
                                <span class="property">Font Size</span>
                                <input class="value inputText" type="range" id="fontSize" name="points" min="-2" max="4">
                            </div>
                        </div>


                        <div class="objectOptionsbody" style="display:none;">
                            <div class="options">
                                <span class="property">Object Fit</span>
                                <select name="" class="value" id="">
                                    <option value="">Contain</option>
                                    <option value="">Cover</option>
                                </select>
                            </div>

                            <div class="options">
                                <span class="property">Overlay Opacity</span>
                                <input class="value inputText" type="range" id="fontSize" name="points" min="-4" max="4">
                            </div>

                        </div>
                    </div>
                </div>
            </div>
           

        </div>
        <!-- Right Section -->
       
    </div>
</body>
<script src="layers.js?v=<?php $v = new createContent(); echo $v->version; ?>"></script>
<script src="function.js?v=<?php $v = new createContent(); echo $v->version; ?>"></script>
<script src="upload.js?v=<?php $v = new createContent(); echo $v->version; ?>"></script>
</html>