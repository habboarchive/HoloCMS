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

require_once('core.php');

if(session_is_registered(username)){ header("Location: index.php"); exit; }

include("locale/".$language."/login.php");

if(isset($_POST['bean_avatarName'])){
$refer = $_SERVER['HTTP_REFERER']; $pos = strrpos($refer, "register.php"); if($pos === false) { exit; }

// Collect the variables we should've recieved
$name = mysql_real_escape_string($_POST['bean_avatarName']);
$password = addslashes($_POST['password']);
$retypedpassword = addslashes($_POST['retypedPassword']);
$day = $_POST['bean_day'];
$month = $_POST['bean_month'];
$year = $_POST['bean_year'];
$email = addslashes($_POST['bean_email']);
$retypedemail = addslashes($_POST['bean_retypedEmail']);
$accept_tos = $_POST['bean_termsOfServiceSelection'];
$spam_me = $_POST['bean_marketing'];
$figure = $_POST['bean_figure'];
$gender = $_POST['bean_gender'];

// Start validating the stuff the user has submitted
$filter = preg_replace("/[^a-z\d]/i", "", $name);
$email_check = preg_match("/^[a-z0-9_\.-]+@([a-z0-9]+([\-]+[a-z0-9]+)*\.)+[a-z]{2,7}$/i", $email);

$tmp = mysql_query("SELECT id FROM users WHERE name = '".$name."' LIMIT 1") or die(mysql_error());
$tmp = mysql_num_rows($tmp);

// If this variable stays false, we're safe and can add the user. If not, it means that
// we've encountered errors and we can not proceed, so instead show the errors and do not
// add the user to the database.
$failure = false;

	// Name validation
	if($tmp > 0){
		$error['name'] = "This username is in use. Please choose another name.";
		$failure = true;
	} elseif($filter !== $name){
		$error['name'] = "Your username is invalid or contains invalid characters.";
		$failure = true;
	} elseif(strlen($name) > 24){
		$error['name'] = "The name you have chosen is too long.";
		$failure = true;
	} elseif(strlen($name) < 1){
		$error['name'] = "Please enter a username.";
		$failure = true;
	}

	// MOD- Names validation
	$pos = strrpos($refer, "MOD-");
	if ($pos === true) {
		$error['name'] = "This name is not allowed.";
		$failure = true;
	}

	// Password validation
	if($password !== $retypedpassword){
		$error['password'] = "The passwords do not match. Please try again.";
		$failure = true;
	} elseif(strlen($password) < 6){
		$error['password'] = "Your password is too short.";
		$failure = true;
	/*} elseif(strlen($password) > 20){
		$error['password'] = "Please shorten your password to 20 characters or less!";
		$failure = true;*/
	}

	// E-Mail validation
	if(strlen($email) < 6){
		$error['mail'] = "Please supply a valid e-mail address.";
		$failure = true;
	} elseif($email_check !== 1){
		$error['mail'] = "Please supply a valid e-mail address.";
		$failure = true;
	} elseif($email !== $retypedemail){
		$error['mail'] = "The e-mail addresses don't match.";
		$failure = true;
	}

	// Date of birth validation
	if($day < 1 || $day > 31 || $month > 12 || $month < 1 || $year < 1920 || $year > 2008){
		$error['dob'] = "Please supply a valid date of birth.";
		$failure = true;
	}
	
	// captcha check
	   if( $_SESSION['register-captcha-bubble'] == $_POST['bean.captchaResponse'] && !empty($_SESSION['register-captcha-bubble'] ) ) {
		// Insert you code for processing the form here, e.g emailing the submission, entering it into a database. 
		echo "";
		unset($_SESSION['security_code']);
   } else {
		$error['captcha'] = "The code that you filled in inn't right, are you sure you're a human?!";
   }

	// Terms of Service validation
	if($accept_tos !== "true"){
		$error['tos'] = "Please read and accept the Terms of Service to register.";
		$failure = true;
	}

	// Try to (we really can't properly) validate figure
	if(!empty($figure)){
		// Todo: Add some extra validation
	} else {
		$error['password'] = "An unknown error occured. Please wait for the figure editor to load the next time or refresh if it doesn't.";
		$failure = true;
	}

	// Check gender
	if($gender !== "M" && $gender !== "F"){
		$gender = "M";
		$failure = true;
	}
	
	// Finally, if everything's OK we add the user to the database, log him in, etc
	if($failure == false){
		$scredits = FetchCMSSetting('start_credits');

		$dob = $day . "-" . $month . "-" . $year;

		$password = HoloHash($password); // and HoloCMS will never know the password again...

		mysql_query("INSERT INTO users (name,password,email,birth,figure,sex,rank,hbirth,ipaddress_last,postcount,tickets,credits,lastvisit) VALUES ('".$name."','".$password."','".$email."','".$dob."','".$figure."','".$gender."','1','".$date_normal."','".$remote_ip."','0','0','".$scredits."','".$date_full."')") or die(mysql_error());

		$check = mysql_query("SELECT id FROM users WHERE name = '".$name."' ORDER BY id ASC LIMIT 1") or die(mysql_error());
		$row = mysql_fetch_assoc($check);
		$userid = $row['id'];

		if($scredits > 0){
			mysql_query("INSERT INTO users (name,password,email,birth,figure,sex,rank,hbirth,ipaddress_last,postcount,tickets,credits,lastvisit,screen,rea) VALUES ('".$name."','".$password."','".$email."','".$dob."','".$figure."','".$gender."','1','".$date_normal."','".$remote_ip."','0','100','".$scredits."','".$date_full."','wide','enabled')") or die(mysql_error());
		}

		$_SESSION['username'] = $name;
		$_SESSION['password'] = $password;

		header("Location: security_check.php?welcome=true");

		exit; // cut off the script

		// And we're done!
	}


}

include('templates/login/register_subheader.php');
include('templates/login/register_header.php');

?>

<div id="process-content">
	        	<div id="column1" class="column">
				<div class="habblet-container ">		
	
						<form method="post" action="register.php" id="registerform" autocomplete="off">
	<input type="hidden" name="bean.figure" id="register-figure" value="" />
	<input type="hidden" name="bean.gender" id="register-gender" value="" />
	<input type="hidden" name="bean.editorState" id="register-editor-state" value="" />

        <div id="register-column-left">
            <div id="register-avatar-editor-title">
                <h2 class="heading"><span class="numbering white">1.</span>Create Your Avatar</h2>
            </div>

            <div id="avatar-error-box">
            </div>
            <div id="register-avatar-editor">
                <p><b>You don't Flash installed. This is why we can only show you a selection of pre-generated avatars. If you install Flash, you'll be able to choose from the hundreds of different options!</b></p>
                <h3>Girls</h3>
                <div class="register-avatars clearfix">
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-505-39.hd-626-1.ch-691-83.lg-705-67.sh-735-91.ha-1001-.ea-1401-84,s-0.g-1.d-4.h-4.a-0,b72abee33635a00982228c1f9215ae9a.gif)">
	                    <input type="radio" name="randomFigure" value="F-hr-505-39.hd-626-1.ch-691-83.lg-705-67.sh-735-91.ha-1001-.ea-1401-84" checked="checked" />
	                </div>
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-530-44.hd-615-10.ch-630-89.lg-705-67.sh-740-62.fa-1206-91.wa-2001-,s-0.g-1.d-4.h-4.a-0,161dda9150b648cd14ff1aa9d9dcc5ad.gif)">
	                    <input type="radio" name="randomFigure" value="F-hr-530-44.hd-615-10.ch-630-89.lg-705-67.sh-740-62.fa-1206-91.wa-2001-" />
	                </div>
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-681-40.hd-629-1.ch-675-86.lg-700-78.sh-725-82,s-0.g-1.d-4.h-4.a-0,1aba810a364a5410115f463f6963f7e1.gif)">
	                    <input type="radio" name="randomFigure" value="F-hr-681-40.hd-629-1.ch-675-86.lg-700-78.sh-725-82" />
	                </div>
                </div>
                <h3>Boys</h3>
                <div class="register-avatars clearfix">
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-105-34.hd-205-6.ch-878-78.lg-281-76.sh-906-67,s-0.g-1.d-4.h-4.a-0,469e46ac01e81f244eb50b4ca7a43520.gif)">
	                    <input type="radio" name="randomFigure" value="M-hr-105-34.hd-205-6.ch-878-78.lg-281-76.sh-906-67" />
	                </div>
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-170-44.hd-207-9.ch-210-84.lg-285-75.sh-290-67,s-0.g-1.d-4.h-4.a-0,430994a364251faa1302b37ca50f6ea9.gif)">
	                    <input type="radio" name="randomFigure" value="M-hr-170-44.hd-207-9.ch-210-84.lg-285-75.sh-290-67" />
	                </div>
	                <div class="register-avatar" style="background-image: url(http://www.habbo.co.uk/habbo-imaging/avatar/hr-145-33.hd-180-10.ch-250-91.lg-270-64.sh-290-75.ea-1406-,s-0.g-1.d-4.h-4.a-0,c52a0478283776654483107d0ca2a2fd.gif)">
	                    <input type="radio" name="randomFigure" value="M-hr-145-33.hd-180-10.ch-250-91.lg-270-64.sh-290-75.ea-1406-" />
	                </div>
	            </div>
                <p><input type="submit" name="refresh" value="Show more <?php echo $shortname; ?>s" id="register-avatars-refresh" /></p>
            </div>

<script type="text/javascript" language="JavaScript">
var swfobj = new SWFObject("http://www.habbo.co.uk/flash/HabboRegistration.swf", "habboreg", "435", "400", "8");
swfobj.addParam("base", "http://www.habbo.co.uk/flash/");
swfobj.addParam("wmode", "opaque");
swfobj.addParam("AllowScriptAccess", "always");
swfobj.addVariable("figuredata_url", "http://www.habbohotel.co.uk/gamedata/figuredata");
swfobj.addVariable("draworder_url", "http://www.habbohotel.co.uk/gamedata/figurepartconfig/draworder");
swfobj.addVariable("localization_url", "http://www.habbo.co.uk/figure/figure_editor_xml");
swfobj.addVariable("figure", "");
swfobj.addVariable("gender", "");

swfobj.addVariable("showClubSelections", "0");

swfobj.write("register-avatar-editor");
</script>
        </div>
        <div id="register-column-right">
            <div id="register-section-2">
                <div class="rounded rounded-blue">
                    <h2 class="heading"><span class="numbering white">2.</span>CHOOSE YOUR NAME</h2>

                    <fieldset id="register-fieldset-name">
	                    <div class="register-label white"><?php echo $shortname; ?> name</div>
		                <input type="text" name="bean.avatarName" id="register-name" class="register-text" value="<?php echo $_POST['bean.avatarName']; ?>" size="25" />
		                <span id="register-name-check-container" style="display:none">
		                    <a class="new-button search-icon" href="#" id="register-name-check"><b><span></span></b><i></i></a>		                
		                </span>
                    </fieldset>
                    <div id="name-error-box">
				<?php if(isset($error['name'])){ ?>
                        <div class="register-error">
                            <div class="rounded rounded-red">
                                <div id="name-error-content">
                                    <?php echo $error['name']; ?>
                                </div>
                            </div>
                        </div>
				<?php } ?>
                    </div>

                </div>
            </div>

            <div id="register-section-3">
                <div id="registration-overlay"></div>
	            <div class="cbb clearfix gray">
    	            <h2 class="title heading"><span class="numbering white">3.</span>Your Details</h2>
    		        <div class="box-content">

			<?php if(isset($error['password'])){ ?>
	                    <div class="register-error"><div class="rounded rounded-red">
                            <div id="password-error-content">
	                            <div><?php echo $error['password']; ?></div>
	                        </div>
	                    </div>
			<?php } ?>

                        <fieldset id="register-fieldset-password">
	                        <div class="register-label"><label for="register-password">My password will be:</label></div>
	                        <div class="register-label"><input type="password" name="password" id="register-password" class="register-text" size="25" value="" /></div>
	                        <div class="register-label"><label for="register-password2">Confirm password</label></div>
	                        <div class="register-label"><input type="password" name="retypedPassword" id="register-password2" class="register-text" size="25" value="" /></div>
                        </fieldset>
                        <div id="password-error-box"></div>

				<?php if(isset($error['dob'])){ ?>
	                    <div class="register-error"><div class="rounded rounded-red">
                            <div id="birthday-error-content">
	                            <div><?php echo $error['dob']; ?></div>
	                        </div>
	                    </div>
				<?php } ?>


                        <fieldset>
	                        <div class="register-label"><label>I was born on:</label></div>
	                        <div id="register-birthday"><select name="bean.day" id="bean_day" class="dateselector"><option value="">Day</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> <select name="bean.month" id="bean_month" class="dateselector"><option value="">Month</option><option value="1">January</option><option value="2">February</option><option value="3">March</option><option value="4">April</option><option value="5">May</option><option value="6">June</option><option value="7">July</option><option value="8">August</option><option value="9">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> <select name="bean.year" id="bean_year" class="dateselector"><option value="">Year</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option></select> </div>
                        </fieldset>

                        <div id="email-error-box">
				<?php if(isset($error['mail'])){ ?>
	                        <div class="register-error">
	                            <div class="rounded rounded-red">
                                    <div id="email-error-content">
	                                    <div><?php echo $error['mail']; ?></div>
	                                </div>
	                            </div>
	                        </div>
				<?php } ?>
                        </div>


                        <fieldset>
	                        <div class="register-label"><label for="register-email">And my email address is:</label></div>
	                        <div class="register-label"><input type="text" name="bean.email" id="register-email" class="register-text" value="<?php echo $_POST['bean.email']; ?>" size="25" maxlength="48" /></div>
	                        <div class="register-label"><label for="register-email2">Retype your email address</label></div>
	                        <div class="register-label"><input type="text" name="bean.retypedEmail" id="register-email2" class="register-text" value="" size="25" maxlength="48" /></div>
                        </fieldset>

	                    <div id="register-marketing-box">
		                    <input type="checkbox" name="bean.marketing" id="bean_marketing" value="true" checked="checked" />
		                    <label for="bean_marketing">Yes, please send me <?php echo $shortname; ?> updates, including the newsletter!</label>
	                    </div>                  


                        <noscript>
                            <fieldset id="register-fieldset-captcha">
	                            <div class="register-label"><img src="demo/CaptchaSecurityImages.php?width=170&height=40&characters=10" /></div>
	     <div id="captcha-error-box">
				<?php if(isset($error['captcha'])){ ?>
	                        <div class="register-error">
	                            <div class="rounded rounded-red">
                                    <div id="email-error-content">
	                                    <div><?php echo $error['captcha']; ?></div>
	                                </div>
	                            </div>
	                        </div>
				<?php } ?>
                        </div>
	                            <div class="register-label"><label for="register-captcha">Type in the security code shown in the image above</label></div>
	                            <div><input type=\"text\" name=\"bean.captchaResponse\" id=\"register-captcha-bubble\" class=\"register-text-black\" value=\"\" size=\"15\" /\></div>
                            </fieldset>
                        </noscript>

                        <div id="terms-error-box">
				<?php if(isset($error['tos'])){ ?>
                            <div class="register-error">
                                <div class="rounded rounded-red">
                                    Please accept the terms of service
                                </div>
                            </div>
				<?php } ?>
                        </div>
                        <fieldset id="register-fieldset-terms">
                            <div class="rounded rounded-darkgray" id="register-terms">
	                            <div id="register-terms-content">
	                                <p><a href="./papers/disclaimer.php" target="_blank" id="register-terms-link">Terms of Service</a></p>
                                    <p class="last">
                                        <input type="checkbox" name="bean.termsOfServiceSelection" id="register-terms-check" value="true" />
                                        <label for="register-terms-check">I accept the Terms of Service</label>
                                    </p>
                                </div>
                            </div>
                        </fieldset>
		            </div>
	            </div>
	            <div id="form-validation-error-box" style="display:none">
                    <div class="register-error">
                        <div class="rounded rounded-red">
                            Sorry, registration failed. Please check the information you gave in the red boxes.
                        </div>
                    </div>
	            </div>
	        </div>
	    </div>
        <div id="register-buttons">
            <input type="submit" value="Done" class="continue" id="register-button-continue" />
            <a href="index.php?registerCancel=true" class="cancel">Exit registration</a>
        </div>
    </form>			
				</div>
				<script type="text/javascript">if (!$(document.body).hasClassName('process-template')) { Rounder.init(); }</script>
			 
</div>
<?php

include('templates/login/footer.php');

?>
