
<?php
$p_Data = new getLoggedData();
if (isset($_SESSION['LOGGED_USER']) || isset($_SESSION['LOGGED_ADMIN'])) {
    if($_SESSION['LOGGED_USER'] || $_SESSION['LOGGED_ADMIN']){
        $profilePic = '<img height="50px" widht="50px" src="'.$p_Data->PROFILE_PIC.'" alt="" class="s-photo">';
    }else {
        $profilePic = '<i class="fa fa-circle-plus fa-2xl profilePlus"></i>';
    }
}else {
    $profilePic = '<i class="fa fa-circle-plus fa-2xl profilePlus"></i>';
}

$profileTab = '<a href="dsfsdf" class="s-tabs profile-tab">'.$profilePic.'
<div class="side-menu-name">
<p class="name">'.$p_Data->NAME.'</p>
<p class="desig">'.$p_Data->DESIG.'</p>
</div>
</a>';

							
$logout = '<a href="/accounts/logout.php/" id="logout"><div class="s-tabs">  <i class="fa fa-power-off fa-xl"></i><span class="side-menu-name">Log Out </span></div></a>';

$tags =  '<a href="/tags/" id="tags_link"><div class="s-tabs">  <i class="fa fa-hashtag fa-xl"></i><span class="side-menu-name">Tags</span> </div></a>';

$contact_us = '<a href="/contact-us/" id="contactUs_link"><div class="s-tabs" > <i class="fa fa-headset fa-xl"></i><span class="side-menu-name">Contact Us</span></div></a>';

$topics = '<a href="/topics/" id="topics_link"><div class="s-tabs"><i class="fa fa-table-list fa-xl"></i><span class="side-menu-name">Topics</span></div></a>
';

$writers = '<a href="/writers/" id="writers_link"><div class="s-tabs"> <i class="fa fa-user-pen fa-xl"></i><span class="side-menu-name">Writers</span></div></a>';

$new_article = '<a href="/new-article/" id="newArticle_link"><div class="s-tabs">  <i class="fa fa-square-plus fa-xl"></i><span class="side-menu-name">New Article</span></div></a>';

$new_user = '<a href="/admin/create-user/" id="newUser_link"><div class="s-tabs">  <i class="fa fa-square-plus fa-xl"></i><span class="side-menu-name">Create User</span></div></a>';

$my_interests = '<a href="/my-interests/" id="interests_link"><div class="s-tabs">  <i class="fa fa-icons fa-xl"></i><span class="side-menu-name">My Interests</span</div></a>';


$profile = '<a href="/accounts/profile/" id="profile_link"><div class="s-tabs"> <i class="fa fa-solid fa-circle-user fa-xl"></i><span class="side-menu-name">Profile</span></div></a>';

$login_up = '<a href="/accounts/" id="accounts_link"><div class="s-tabs"> <i class="fa fa-user-plus fa-xl"></i><span class="side-menu-name">Sign Up/Log In</span></div></a>';

$t_c = '<a href="/terms-privacy/" id="tc_link"><div class="s-tabs"><i class="fa fa-solid fa-file-contract fa-xl"></i><span class="side-menu-name">Terms & Privacy</span></div></a>';

$User = $tags.$topics.$writers.$profile.$logout.$contact_us.$my_interests.$t_c;

$Guest = $tags.$topics.$writers.$contact_us.$login_up.$t_c;


$Admin = $new_user.$tags.$topics.$writers.$profile.$logout;

?>

                        

						

						
						
						

						


						

						

						

						