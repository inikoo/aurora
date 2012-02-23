<?php
require_once './facebooksdk/src/facebook.php';  //download at https://github.com/facebook/php-sdk/downloads

$facebook = new Facebook(array(
  'appId' => '310000872390185', // enter your App's ID
  'secret' => '4b50ae7f02cab311e0a1c33cfffb4791', // enter your App's Secret
  'cookie' => true,
));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Welcome Page</title>
</head>
<body>

<?php
// Did they like a page?
$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

if (empty($data["page"]["liked"])) {
// DISPLAY TO: those who didn't LIKE the page
	?>
<!-- Didn't Like -->
You don't "Like" our page :-(
<!-- End of Didn't Like -->
    <?
} else {
// DISPLAY TO: those who LIKED the page
	?>
<!-- Liked -->
Thanks for "Liking" our page!
<!-- End of Liked -->
<? } ?>

<!-- follow @svolinsky on twitter -->

</body>
</html>