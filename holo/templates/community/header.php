<?php
/*===================================================+
|| # HoloCMS - Website and Content Management System
|+===================================================+
|| # Copyright © 2008 Meth0d. All rights reserved.
|| # http://www.meth0d.org
|+===================================================+
|| # HoloCMS is provided "as is" and comes without
|| # warrenty of any kind. HoloCMS is free software!
|+===================================================*/

if (!defined("IN_HOLOCMS")) { header("Location: index.php"); exit; }

if(empty($body_id)){
$body_id = "home";
}

?>
<body id="<?php echo $body_id; ?>" class="<?php if(!$logged_in){ echo "anonymous"; } ?> ">
<div id="overlay"></div>

<div id="header-container">
	<div id="header" class="clearfix">
		<h1><a href="index.php"></a></h1>
       <div id="subnavi">
			<div id="subnavi-user">
                            <?php if($logged_in){ ?>
				<ul>
					<li id="myfriends"><a href="#"><span>My Friends</span></a><span class="r"></span></li>
					<li id="mygroups"><a href="#"><span>My Groups</span></a><span class="r"></span></li>
					<li id="myrooms"><a href="#"><span>My Rooms</span></a><span class="r"></span></li>
				</ul>
                            <?php } elseif(!$logged_in){ ?>
                                <div class="clearfix">&nbsp;</div>
                                <p>
				    <a href="client.php" id="enter-hotel-open-medium-link" target="client" onclick="openOrFocusHabbo(this); return false;">Enter <?php echo $sitename; ?></a>
                                </p>
                            <?php } ?>
<?php if($online == "online" && $logged_in){ ?>
				    <a href="client.php" id="enter-hotel-open-medium-link" target="client" onclick="openOrFocusHabbo('client.php'); return false;">Enter <?php echo $sitename; ?></a>
<?php } elseif($logged_in){ ?>
					<div id="hotel-closed-medium"><?php echo $sitename; ?> is offline</div>
<?php } ?>
			</div>
        <?php if(!$logged_in){ ?>
            <div id="subnavi-login">
                <form action="index.php?anonymousLogin" method="post" id="login-form">
            		<input type="hidden" name="page" value="<?php echo $pageid; ?>" />
                    <ul>
                        <li>
                            <label for="login-username" class="login-text"><b>Username</b></label>
                            <input tabindex="1" type="text" class="login-field" name="username" id="login-username" />
		                    <a href="#" id="login-submit-new-button" class="new-button" style="float: left; display:none"><b>Sign in</b><i></i></a>
                            <input type="submit" id="login-submit-button" value="Sign in" class="submit"/>
                        </li>
                        <li>
                            <label for="login-password" class="login-text"><b>Password</b></label>
                            <input tabindex="2" type="password" class="login-field" name="password" id="login-password" />
                            <input tabindex="3" type="checkbox" name="_login_remember_me" value="true" id="login-remember-me" />
                            <label for="login-remember-me" class="left">Remember me</label>
                        </li>
                    </ul>
                </form>
                <div id="subnavi-login-help" class="clearfix">
                    <ul>
                        <li class="register"><a href="forgot.php" id="forgot-password"><span>I forgot my password/username</span></a></li>
                    	<li><a href="register.php"><span>Register for free</span></a></li>
                    </ul>
                </div>
<div id="remember-me-notification" class="bottom-bubble" style="display:none;">
	<div class="bottom-bubble-t"><div></div></div>
	<div class="bottom-bubble-c">
					By selecting 'remember me' you will stay signed in on this computer until you click 'Sign Out'. If this is a public computer please do not use this feature.
	</div>
	<div class="bottom-bubble-b"><div></div></div>
</div>
            </div>
        </div>
		<script type="text/javascript">
			LoginFormUI.init();
			RememberMeUI.init("right");
		</script>
        <?php } else { ?>
            <div id="subnavi-search">
                <div id="subnavi-search-upper">
                <ul id="subnavi-search-links">
                    <li><a href="./iot/go.php" target="habbohelp" onclick="openOrFocusHelp(this); return false">Help</a></li>
					<li><a href="logout.php?reason=site" class="userlink">Sign Out</a></li>
				</ul>
                </div>
                <form name="tag_search_form" action="user_profile.php" class="search-box clearfix">
					<a id="search-button" class="new-button search-icon" href="#" onclick="$('search-button').up('form').submit(); return false;"><b><span></span></b><i></i></a>
					<input type="text" name="tag" id="search_query" value="User Profile.." class="search-box-query search-box-onfocus" style="float: right"/>
				</form>
                <script type="text/javascript">SearchBoxHelper.init();</script>
            </div>
        </div>
        <script type="text/javascript">
		L10N.put("purchase.group.title", "Create a group");
        </script>
        <?php } ?>
<ul id="navi">
        <?php if($pageid > 0 && $pageid < 4 || $pageid == "myprofile" && $logged_in == true){ ?>
        <li class="selected"><strong><?php echo $name; ?></strong><span></span></li>
        <?php } elseif($logged_in == true){ ?>
        <li class=" "><a href="index.php"><?php echo $name; ?></a><span></span></li>
        <?php } elseif($logged_in !== true){ ?>
        <li id="tab-register-now"><a href="register.php" target="_self">Register now!</a><span></span></li>
        <?php } ?>

        <?php if($pageid == 4 || $pageid > 3  && $pageid < 6 || $pageid == "profile" || $pageid == "com" || $pageid == "8" || $pageid == "forum"){ ?>
        <li class="selected"><strong>Community</strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="community.php">Community</a><span></span></li>
        <?php } ?>

        <?php if($pageid == 6 || $pageid > 5  && $pageid < 8){ ?>
        <li class="selected"><strong>Credits</strong><span></span></li>
        <?php } else { ?>
        <li class=" "><a href="credits.php">Credits</a><span></span></li>
        <?php } ?>

        <?php if($user_rank > 5 && $logged_in == true){ ?>
        <li id="tab-register-now"><a href="./housekeeping/" target="_self"><b>Housekeeping</b></a><span></span></li>
        <?php } ?>
</ul>

	<div id="habbos-online"><div class="rounded"><span><?php echo $online_count; ?> <?php echo $shortname; ?>s online now</span></div></div>
	</div>
</div>

<div id="content-container">

<div id="navi2-container" class="pngbg">
    <div id="navi2" class="pngbg clearfix">

	<ul>
        <?php if($pageid > 0 && $pageid < 4 || $pageid == "myprofile"){ ?>
                <?php if($pageid == "1"){ ?>
                <li class="selected">
    				Homepage
                <?php } else { ?>
                <li class="">
    				<a href="index.php">Homepage</a>
                <?php } ?>
        		</li>

                <?php if($pageid == "myprofile"){ ?>
                <li class="selected">
    				My Page
                <?php } else { ?>
                <li class="">
    				<a href="user_profile.php?name=<?php echo $rawname; ?>">My Page</a>
                <?php } ?>
        		</li>

                <?php if($pageid == "2" && $logged_in){ ?>
                <li class="selected">
    				Account Settings
                <?php } elseif($logged_in){ ?>
                <li class="">
	    			<a href="account.php">Account Settings</a>
                <?php } ?>
    	    	</li>

		<li class="last">
				<a href="club.php">Obbah Club</a>
		</li>
        <?php } else if($pageid == 4 || $pageid > 3  && $pageid < 6 || $pageid == "profile" || $pageid == "com" || $pageid == "8" || $pageid == "forum" || $pageid == "rotm" || $pageid == "about"){ ?>
                <?php if($pageid == "com"){ ?>
                <li class="selected">
    				Community
                <?php } else { ?>
                <li class=" ">
	    			<a href="community.php">Community</a>
                <?php } ?>
                <?php if($pageid == "4"){ ?>
                <li class="selected">
    				News
                <?php } else { ?>
                <li class=" ">
	    			<a href="news.php">News</a>
                <?php } ?>
                <?php if($pageid == "8"){ ?>
                <li class="selected">
    				Staff
                <?php } else { ?>
                <li class=" ">
	    			<a href="staff.php">Staff</a>
                <?php } ?>
                <?php if($pageid == "forum"){ ?>
                <li class="selected">
    				Discussion Board
                <?php } else { ?>
                <li class=" ">
	    			<a href="forum.php">Discussion Board</a>
                <?php } ?>
                <?php if($pageid == "5"){ ?>
                <li class="selected">
    				Tags
                <?php } else { ?>
                <li class=" ">
	    			<a href="tags.php">Tags</a>
                <?php } ?>
				<?php /* if($pageid == "applications"){ ?>
				<li class="selected last">
					Applications
				<?php } else { ?>
				<li class=" last">
					<a href="applications.php">Applications</a>
                <?php } */ ?>
        <?php } else if($pageid == "6" || $pageid > 5 && $pageid < 8 || $pageid == "6b" || $pageid = "collectables"){ ?>
                <?php if($pageid == "6"){ ?>
                <li class="selected ">
    				Credits
                <?php } else { ?>
                <li class=" ">
	    			<a href="credits.php">Credits</a>
                <?php } ?>
                <?php if($pageid == "7"){ ?>
                <li class="selected">
    				<?php echo $shortname; ?> Club
                <?php } else { ?>
                <li class=" ">
	    			<a href="club.php"><?php echo $shortname; ?> Club</a>
                <?php } ?>
                <?php if($pageid == "collectables"){ ?>
                <li class="selected last">
    				Collectables
                <?php } else { ?>
                <li class=" last">
	    			<a href="collectables.php">Collectables</a>
                <?php } ?>
        <?php } ?>
    	    	</li>
</ul>


	</div>
</div>

<?php if($notify_maintenance){ ?>
<div align="center" style="color: red; background-color: white; border: 1px solid black; padding:2px; width: 75%"><b>Alert:</b> The site is currently turned off!</div>
<?php } ?>