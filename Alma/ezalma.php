<?php
function auth_alma($user) {
require('config.php');
$ch = curl_init();
$url = 'https://api-na.hosted.exlibrisgroup.com/almaws/v1/users/{user_id}';
$templateParamNames = array('{user_id}');
$templateParamValues = array(urlencode($user));
$url = str_replace($templateParamNames, $templateParamValues, $url);
$queryParams = '?' . urlencode('user_id_type') . '=' . urlencode('all_unique') . '&' . urlencode('view') . '=' . urlencode('full') . '&' . urlencode('expand') . '=' . urlencode('none') . '&' . urlencode('apikey') . '=' . urlencode($key);
curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
//parse the response
$group_auth = $status_auth = $authenticate = 1;
$patron_type = $status = "";
$xmlObj = simplexml_load_string($response);
$patron_type = $xmlObj->user_group;
$status = $xmlObj->status;
if (in_array($patron_type, $allowable_patrons)) {
  $group_auth = 0;
} else {
  $group_auth = 1;
}
if ($status == "ACTIVE") {
  $status_auth = 0;
} else {
  $status_auth = 2;
}

$authenticate = $group_auth + $status_auth;
return $authenticate;

}

if (isset($_POST["user"])) {$user = $_POST["user"]; }
$result = auth_alma($user);
if ($result == 0) {
  echo "Yay!";
  require("ezticket.php");
  $ezproxy = new EzproxyTicket("http://libproxy.csun.edu", $secret, $user, $groups);
} elseif ($result == 1) {
  echo "User Group is not allowed remote access.";
} elseif ($result == 2) {
  echo "User is expired.";
} else {
  echo "Please see the desk for assistance.";
}
?>