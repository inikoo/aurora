<?php

$default_lang='en_GB';

function curPageURL() {
 $pageURL = 'http';
 if (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
 $pageURL .= "://";
 if ($_SERVER["SERVER_PORT"] != "80") {
  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 } else {
  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 }
 return $pageURL;
}

function curPage(){
	$name = explode("/",$_SERVER["PHP_SELF"]);
	return $name[count($name)-1];
}

function language(){
	if(!isset($_REQUEST['lang']))
		$lang = 'en_GB';
	else
		$lang = $_REQUEST['lang'];

	return $lang;
}
?>