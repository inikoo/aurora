<?php
include_once('conf/locale_defaults.php');

if(DEBUG){ error_reporting(E_ALL);}

if (!function_exists('_')){
	function _($str){
		return $str;
	}
	function gettext($str){
		return $str;
	}
function ngettext($str){
		return $str;
	}
	
	function bindtextdomain(){};
	function bind_textdomain_codeset(){};
	function textdomain(){};

}

$default_locale=$default_locale_data['lang'].'_'.$default_locale_data['country'].($default_locale_data['encoding']!=''?'.'.$default_locale_data['encoding']:'');
setlocale(LC_ALL,$default_locale );
$_SESSION['locale']=$default_locale;
if(isset($_REQUEST['_locale']) and preg_match('/[a-z]{2}_[A-Z]{2}\.UTF-8/',$_REQUEST['_locale']) ){
  $_SESSION['text_locale']=$_REQUEST['_locale'];
 }
if(!isset($_SESSION['text_locale'])){
	$_SESSION['text_locale']=$default_locale;
} 
 
setlocale(LC_MESSAGES,$_SESSION['text_locale'] );
setlocale(LC_TIME, $_SESSION['text_locale']);



$_SESSION['text_locale_country_code']=substr($_SESSION['text_locale'],3,2);
$_SESSION['text_locale_code']=substr($_SESSION['text_locale'],0,2);
$_SESSION['text_locale_encoding']=substr($_SESSION['text_locale'],6);

$other_langs=array();

bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus',$_SESSION['text_locale_encoding']);
textdomain('kaktus');


$regex['thousand_sep']=str_replace('.','\.','/'.$default_locale_data['thosusand_sep'].'/g');
$regex['number']=str_replace('.','\.','/^\d*'.$default_locale_data['decimal_point'].'?\d*$/i');
$regex['strict_number']=str_replace('.','\.','/^(\d{1,3}'.$default_locale_data['thosusand_sep'].')*\d{1,3}('.$default_locale_data['decimal_point'].'\d+)?$/i');
$regex['dimension1']=str_replace('.','\.','/^\d+'.$default_locale_data['decimal_point'].'?\d*$/i');
$regex['dimension2']=str_replace('.','\.','/^\d*'.$default_locale_data['decimal_point'].'?\d*x\d*'.$default_locale_data['decimal_point'].'?\d*$/i');
$regex['dimension3']=str_replace('.','\.','/^\d*'.$default_locale_data['decimal_point'].'?\d*x\d*'.$default_locale_data['decimal_point'].'?\dx\d*'.$default_locale_data['decimal_point'].'?\d*$/i');
$regex['key_filter_number']=str_replace('.','\.','/[\d\b'.$default_locale_data['decimal_point'].$default_locale_data['thosusand_sep'].']/i');
$regex['key_filter_dimension']=str_replace('.','\.','/[x\d\b'.$default_locale_data['decimal_point'].$default_locale_data['thosusand_sep'].']/i');

?>