<?php
require('config.php');
require('functions.php');

function ShowForm() {
	$desturl = "";
	$errors = "";
	if (isset($_REQUEST["url"])) {$desturl = $_REQUEST["url"]; }
    	echo '<form method="post" action="ezalma.php">';
    	echo '<input type="hidden" name="desturl" value="' . $desturl . '"/>';
		echo '<div class="proxyform">';
   		echo '<label for="user"><b>User ID:</b></label>';
    	echo '<input type="text" name="user" size="25" maxlength="50" id="user" /><br />';
    	echo '<label for="password"><b>Password:</b></label>';
    	echo '<input type="password" name="pass" size="25" maxlength="35" id="password"/><br />';
    	echo '<div class="buttons"><input type="submit" name="submit" value="Submit" />';
    	echo '<input type="reset" value="Clear"/></div>';
    	echo '</div>';
    	echo '</form>';
    if (isset($_SESSION['errors'])) { $errors = $_SESSION["errors"]; echo $errors;}
    
}
if (isset($_POST['submit'])) {
	if (isset($_POST["user"])) {$user = $_POST["user"]; }
	if (isset($_POST["pass"])) {$pass = $_POST["pass"]; }
	if (isset($_POST["desturl"])) {$desturl = $_POST["desturl"]; }
	$result = auth_alma($user);
	$result = auth_ldap($user,$pass);
		if ($result == "0") {
  			require("ezticket.php");
  				$ezproxy = new EzproxyTicket($libproxy, $secret, $user, $groups);
  				$ticket = $ezproxy->url($desturl);
        		$header = "Location:" . $ticket;
        		header($header);
		} elseif ($result == "1") {
  			//Usergroup not allowed remote access;
  			$_SESSION['errors'] = "User Group is not allowed remote access.";
  			ShowForm();
		} elseif ($result == "2") {
  			//User is expired;
  			$_SESSION['errors'] = "User is expired.";
  			ShowForm();
		} elseif ($result == "3") {
		    //Username or Password incorrect;
		    $_SESSION['errors'] = "Username or password incorect.";
  			ShowForm();
		} else {
		    //All other errors;
  			$_SESSION['errors'] = "Please see the circulation desk for assistance.";
  			ShowForm();
		}
} else {
ShowForm();
exit;
}
?>
