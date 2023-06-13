<div class="contentTopics">
  <!-- for showing info -->
  <div class="personalInfoTab" id="personalInfoShow">
    <div class="title">
        Personal Details
    </div>

    <div class="infoDiv">
        <span class="infoType"> <i class="fa-solid fa-user fa-xl"></i></span>
        <span class="infoValue"><?php echo $this->userData->getSelfDetails()['name'];?>
        <?php
        if ($this->userData->getOtherData('username', $this->otherUsername)['DOB'] != null) {
        echo '('.$this->userData->getOtherData('username', $this->otherUsername)['age'].')';
        }
        ?>
        </span>
    </div>


    <div class="infoDiv">
    <span class="infoType"><i class="fa-solid fa-hashtag fa-xl"></i></span>
    <span class="infoValue"><?php echo $this->userData->getOtherData('username', $this->otherUsername)['username'];?>
    </span>
    </div>

    <div class="infoDiv">
    <span class="infoType"><i class="fa-solid fa-at fa-xl"></i></span>
    <?php
        if($canViewMail == 'everyone'){
            echo <<<HTML
            <span class="infoValue"><?php echo $this->userData->getOtherData('username', $this->otherUsername)['email'];?>
            HTML;
        }elseif($canViewMail ==  'followers' && $isFollowingMe){
            echo <<<HTML
            <span class="infoValue"><?php echo $this->userData->getOtherData('username', $this->otherUsername)['email'];?>
            HTML;
        }
        ?>
    </span>
    </div>

    <?php
    if($this->userData->getOtherData('username', $this->otherUsername)['websiteUrl'] != null){
    echo <<<HTML
    <div class="infoDiv">
        <span class="infoType"><i class="fa-solid fa-earth-asia fa-xl"></i></span>
        <span class="infoValue">{$this->userData->getOtherData('username', $this->otherUsername)['websiteUrl']}
        </span>
    </div>
    HTML;
    }
    ?>

    <?php
    if($this->userData->getOtherData('username', $this->otherUsername)['bio'] != null || !empty($this->userData->getOtherData('username', $this->otherUsername)['bio'])){
    echo <<<HTML
    <div class="infoDiv">
        <span class="infoType"><i class="fa-solid fa-circle-info fa-xl"></i></span>
        <span class="infoValue">{$this->userData->getOtherData('username', $this->otherUsername)['bio']}
        </span>
    </div>
    HTML;
    }
    ?>   
  
  <!-- /For showing Info -->
</div>