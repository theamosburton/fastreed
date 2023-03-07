
<?php
$p_Data = new getLoggedData();

$profileTab = '<div class="s-tabs profile-tab">
<img height="50px" widht="50px" src="'.$p_Data->PROFILE_PIC.'" alt="" class="s-photo">
<div>
<p class="name">'.$p_Data->NAME.'</p>
<p class="desig">'.$p_Data->DESIG.'</p>
</div>
</div>';

							
$logout = '<a href="/accounts/logout.php/" id="logout"><div class="s-tabs">  <i class="fa fa-power-off fa-lg"></i>Log Out </div></a>';

$tags =  '<a href="/tags/" id="tags_link"><div class="s-tabs">  <i class="fa fa-hashtag fa-lg"></i>Tags </div></a>';

$contact_us = '<a href="/contact-us/" id="contactUs_link"><div class="s-tabs" > <i class="fa fa-headset fa-lg"></i>Contact Us</div></a>';

$topics = '<a href="/topics/" id="topics_link"><div class="s-tabs"> <i class="fa fa-table-list fa-lg"></i>Topics</div></a>
';

$writers = '<a href="/writers/" id="writers_link"><div class="s-tabs"> <i class="fa fa-user-pen fa-lg"></i>Writers</div></a>';

$new_article = '<a href="/new-article/" id="newArticle_link"><div class="s-tabs">  <i class="fa fa-square-plus fa-lg"></i>New Article</div></a>';

$new_user = '<a href="/admin/create-user/" id="newUser_link"><div class="s-tabs">  <i class="fa fa-square-plus fa-lg"></i>Create User</div></a>';

$my_interests = '<a href="/my-interests/" id="interests_link"><div class="s-tabs">  <i class="fa fa-icons fa-lg"></i>My Interests</div></a>';


$profile = '<a href="/accounts/profile/" id="profile_link"><div class="s-tabs"> <i class="fa fa-solid fa-circle-user fa-lg"></i>Profile</div></a>';

$login_up = '<a href="/accounts/" id="accounts_link"><div class="s-tabs"> <i class="fa fa-user-plus fa-lg"></i>Sign Up/Log In</div></a>';

$t_c = '<a href="/terms-privacy/" id="tc_link"><div class="s-tabs"> <i class="fa fa-solid fa-file-contract fa-lg"></i>Terms & Privacy</div></a>';

$User = $tags.$topics.$writers.$profile.$logout.$contact_us.$my_interests.$t_c;

$Guest = $tags.$topics.$writers.$login_up.$t_c;


$Admin = $new_user.$tags.$topics.$writers.$profile.$logout;

?>

                        

						

						
						
						

						


						

						

						

						