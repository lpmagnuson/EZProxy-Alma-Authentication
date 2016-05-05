<?php
require('config.php');
function ShowForm($error)
{
    global $url;

$mobile_browser = '0';
 
if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
    $mobile_browser++;
}

    $filename = "/home/httpd/cgi-bin/ezplogin.head";
    $filename2 = "/home/httpd/cgi-bin/ezplogin.foot";
    $fromHomeLink = ' <a href="http://library.csun.edu/Services/FromHome">current CSUN students, faculty, and staff.</a> ';
    $proxyTroubleshoot = '<p>For additional help consult our <a href="http://library.csun.edu/ResearchAssistance/ResolvingProblems">Proxy Server Troubleshooting</a> page.</p>';
    $recordTroubleshoot = 'submit our <a href="http://library.csun.edu/Services/LoginProblem">Login to Library Resources Problem Report</a></p></div>';

    $fd = fopen ($filename, "rb");

    while(!feof($fd))
    {
        $line = fgets($fd);
        echo $line;
    }

    echo '<div id="maincontent" class="mobileregion"><h1>Access to Restricted Resources</h1><hr />';


    if ($error == 1) {
        echo '<div class="mobile_error"><p>The User ID or password you entered is incorrect. Please enter your <strong>CSUN User ID</strong> (NOT your Student ID or Library barcode).</p>';
        echo $proxyTroubleshoot . '</div>';
    }
    else if ($error == 2) {
        echo '<div class="mobile_error"><p>Your library record has expired.  If you are a current CSUN student, faculty or staff member you need to come to the Library circulation desk to have it renewed</p></div>';
    }
    else if ($error == 3) {
        echo '<div class="mobile_error"><p>Your patron type is not granted remote access.  If you feel this is incorrect you need to come to the Library circulation desk to have your patron type changed</p></div>';
    }

    else if ($error == 4) {  
        echo '<div class="mobile_error"><p>We are unable to locate your Library record.  If you are a current CSUN student, faculty or staff member please ';
	echo $recordTroubleShoot;
    }
    else  {
        echo '<div class="mobile_proxy"><p>Remote access to this database is restricted to'. $fromHomeLink; 
        echo 'Please enter your CSUN User ID and password in the fields provided below:</p></div>';
    }

    echo '<form method="post" action="ezproxy.php">';
    echo '<input type="hidden" name="url" value="' . $url . '"/>';

    echo '<div class="proxyform">';
    echo '<label for="user"><b>CSUN User ID:</b></label>';
    echo '<input type="text" name="user" size="25" maxlength="50" id="user" /><br />';
    echo '<label for="password"><b>Password:</b></label>';
    echo '<input type="password" name="pass" size="25" maxlength="35" id="password"/><br />';
    echo '<div class="buttons"><input type="submit" value="Submit" />';
    echo '<input type="reset" value="Clear"/></div>';
    echo '</div>';
    echo '</form>';

echo

'<div class="proxyhelp">
<p><strong>
<a href="https://auth.csun.edu/idm/forgot_uid">Forgot CSUN User ID ?</a>
</strong>
</p>
<p>
<strong>
<a href="https://auth.csun.edu/idm/forgotpwd">Forgot CSUN Password ?</a>
</strong>
</p>
</div>
</div>';
}
    $fd = fopen ($filename2, "rb");

    while(!feof($fd))
    {
        $line = fgets($fd);
        echo $line;
    }

}

function auth_ldap($user, $pass, $domain)
{
  global $uid;
  if ($user ==  "") return 0;

  $ldap_server = "sdir.csun.edu";
  $base = "o=csun";
  $ds=ldap_connect($ldap_server);
  $query = "maillocaladdress=" . $user;
  if (strpos($user, "@") === false) { $query = $query . $domain ; }

  if ($ds) {
      $r=ldap_bind($ds);
      $sr=ldap_search($ds, $base, $query);

      $info = ldap_get_entries($ds, $sr);
      $found = 0;
      for ($i=0; $i<$info["count"]; $i++) {
        $dn = $info[$i]["dn"];
        $ds2=ldap_connect($ldap_server);
        $ldapbind = @ldap_bind($ds2, $dn, $pass);
        if($ldapbind) {
            $uid = $info[$i]["uid"][0];
            ldap_unbind($ds2);
            $found=1;
            break;
        }
      } 
  }
  ldap_close($ds);
  return $found;
}

function auth_iii($user)
{
# AuthIII return 0 if authenticated, 2 if expired, 3 if wrong ptype, 4 if not found
  
  global $groups;

  $page = get_api_contents($paturl); 

  $arrRawData = explode("\n", $page);

  foreach ($arrRawData as $strLine)
  {
      $arrLine = explode("=", $strLine);
      // strip out the code, leaving just the attribute name
      $arrLine[0] = preg_replace("/\[[^\]]{1,}\]/", "", $arrLine[0]);
      $key = trim($arrLine[0]); 
      $arrData[$key] = trim( $arrLine[1] );
  }

  if ( array_key_exists("ERRMSG", $arrData )) { return 4; }

  $d = explode("-",$arrData["EXP DATE"]);
  $mon = $d[0];
  $day = $d[1];
  $year = $d[2];
  $expiry = mktime(0, 0, 0, $mon, $day, $year);

# Return 0 on unexpired number, 2 if found but expired
  $expired = 0;
  if($expiry < time() ) {$expired = 2; }

  $type = $arrData["P TYPE"];
    
  $typeresult = 1; 
  if ($type == "1" )  {$typeresult = 0;};
  if ($type == "2" )  {$typeresult = 0;};
  if ($type == "3" )  {$typeresult = 0;};
  if ($type == "4" )  {$typeresult = 0;};
  if ($type == "20" ) {$typeresult = 0;};
  if ($type == "21" )  {$typeresult = 0;}; 
  if ($type == "22" )  {$typeresult = 0;};
  if ($type == "23" )  {$typeresult = 0;};
  if ($type == "24" )  {$typeresult = 0;};
  if ($type == "30" )  {$typeresult = 0;};
  if ($type == "40" )  {$typeresult = 0;};
  if ($type == "42" )  {$typeresult = 0;};
  if ($type == "45" )  {$typeresult = 0;};
  if ($type == "46" )  {$typeresult = 0;};
  if ($type == "47" )  {$typeresult = 0;};
  if ($type == "48" )  {$typeresult = 0;};
  if ($type == "51" )  {$typeresult = 0;};
  if ($type == "72" )  {$typeresult = 0;};

  
  $pcode = $arrData["PCODE1"];

  $groups = "ezproxy_group=Default";
  if (($type == "1") || ($type == "2") || ($type == "3" ) || ($pcode == "v")) {
      $groups = $groups . "+Faculty";
  }

  $result = 0;
  if ($expired == 2) {
      $result = 2;
  } elseif ($typeresult == 1) {
     $result = 3;  
  }

  return $result;
}

function get_api_contents($apiurl) {
        $api_contents = file_get_contents($apiurl);
        $api_contents = trim(strip_tags($api_contents));
        return $api_contents;
}


/* ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

if (isset($_POST["user"])) {$user = $_POST["user"]; }
if (isset($_POST["pass"])) {$pass = $_POST["pass"]; }
if (isset($_REQUEST["url"])) {$url = $_REQUEST["url"]; }

$ip=$_SERVER['REMOTE_ADDR'];

if (isset($user) == false) {
    ShowForm(0); exit;
 }  

$auth = auth_ldap($user, $pass, "@my.csun.edu");
if ($auth == 0) {
    $auth = auth_ldap($user, $pass, "@csun.edu");
}


    $logfile = "/var/log/logins.log";
    $today = date("D M j G:i:s Y"); 

    if ($auth == 0) {$message = "$user : FAILED LDAP $auth : $today : $ip\n"; }
    else {$message = "$user : PASSED LDAP : $today : $ip\n"; }
    file_put_contents($logfile,$message, FILE_APPEND);  

    if ($auth == 0) { showform(1); exit; }

    $user = $uid;
    $auth = auth_iii($user);
    if ($auth == 0) {

        require("ezticket.php");
        $ezproxy = new EzproxyTicket("http://libproxy.csun.edu", $secret, $user, $groups);

        $message = "$user : PASSED III : $today : $ip\n"; 
    }
    else { $message = "$user : FAILED III $auth : $today : $ip\n"; }

    file_put_contents($logfile,$message, FILE_APPEND);

    if ($auth == 0) {
        $ticket = $ezproxy->url($url);
        $header = "Location: " . $ticket;
        header($header);
    } else { showform($auth); exit; }

?>

