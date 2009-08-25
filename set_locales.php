<?php

//print "Locale:". $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:'')."\n";
setlocale(LC_ALL, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang'])){
  $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang'];
 }
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $_SESSION['lang']=$_REQUEST['_lang'];
 }
if(!isset($_SESSION['lang'])){
  
  $sql=sprintf("select `Language Key`  from `Language Dimension` where `Language Code`=%s and `Country 2 Alpha Code`=%s  "
	       ,prepare_mysql($myconf['lang'])
	       ,prepare_mysql($myconf['country'])
	       );
  
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

    $_SESSION['lang']=$row['Language Key'];
  }else{
    $_SESSION['lang']=1;

  }
  mysql_free_result($result);
 }

$other_langs=array();
$sql="select   `EAI Locale Code` ,`Language Code` ,`Country 2 Alpha Code` , `Locale Code` from `Language Country Bridge`   where `Language Key`=".$_SESSION['lang'];
$result=mysql_query($sql);
if($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  setlocale(LC_MESSAGES, $sql_data['EAI Locale Code']);
  setlocale(LC_TIME, $sql_data['EAI Locale Code']);
  if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang']))
    $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang']=$_SESSION['lang'];
  $lang_country_code=$sql_data['Country 2 Alpha Code'];
  $lang_code=$sql_data['Language Code'];
 }else{
  $lang_country_code='gb';
  $lang_code='EN';
 }
mysql_free_result($result);
bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus', $myconf['encoding']);
textdomain('kaktus');
require('locale.php');
$_SESSION['locale_info'] = localeconv();

$smarty->assign('lang_code',$lang_code);
$smarty->assign('lang_country_code',strtolower($lang_country_code));

$args="?";
foreach($_GET as $key => $value){
  if($key!='_lang')
    $args.=$key.'='.$value.'&';
}
$lang_menu=array();
$sql="select `Language Key`,`Country 2 Alpha Code` from `Language Country Bridge` where `EAI Access`=1 and `Language Key`!=".$_SESSION['lang'];


 $result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_lang='.$row['Language Key'],strtolower($row['Country 2 Alpha Code']),$_lang[$row['Language Key']]);
 }
 mysql_free_result($result);

$smarty->assign('lang_menu',$lang_menu);
$smarty->assign('page_layout','doc4');

//regex expresions
$regex['thousand_sep']=str_replace('.','\.','/'.$myconf['thousand_sep'].'/g');
$regex['number']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*$/i');
$regex['strict_number']=str_replace('.','\.','/^(\d{1,3}'.$myconf['thousand_sep'].')*\d{1,3}('.$myconf['decimal_point'].'\d+)?$/i');
$regex['dimension1']=str_replace('.','\.','/^\d+'.$myconf['decimal_point'].'?\d*$/i');
$regex['dimension2']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*x\d*'.$myconf['decimal_point'].'?\d*$/i');
$regex['dimension3']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*x\d*'.$myconf['decimal_point'].'?\dx\d*'.$myconf['decimal_point'].'?\d*$/i');

//$regex['strict_number']=str_replace('.','\.','/^\d{1,3}$/');



$regex['key_filter_number']=str_replace('.','\.','/[\d\b'.$myconf['decimal_point'].$myconf['thousand_sep'].']/i');
$regex['key_filter_dimension']=str_replace('.','\.','/[x\d\b'.$myconf['decimal_point'].$myconf['thousand_sep'].']/i');
?>