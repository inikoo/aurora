<?
include_once('app_files/db/key.php');

include_once('aes.php');


$Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
$St=AESEncryptCtr($Sk,SKEY, 256);

//print AESDecryptCtr($St,SKEY,256);
//exit;
$css_files=array(
		 $yui_path.'xreset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'login.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		'js/sha256.js.php',
		'js/aes.js',
		'js/login.js.php'
		);




$smarty->assign('st',$St);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




// if(basename($_SERVER['PHP_SELF'])=='login.php'){
//   header("Location: index.php"); 
//   exit;
//  }





setlocale(LC_MESSAGES, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
$current_lang=$myconf['lang'];
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $sql="select  lang.code as code ,country.code2 as country_code  from lang left join list_country as country on (country.id=country_id) where lang.id=".$_REQUEST['_lang'];
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($sql_data=$res->fetchRow()) {
    setlocale(LC_MESSAGES, $sql_data['code'].'_'.strtoupper($sql_data['country_code']).($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
    $current_lang=$sql_data['code'];
  }
 }


bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus', $myconf['encoding']);
textdomain('kaktus');

$smarty->assign('theme', $myconf['theme']);
$smarty->assign('title', _('Authentication'));
$smarty->assign('welcome', _('Welcome'));
$smarty->assign('user', _('User'));
$smarty->assign('password', _('Password'));
$smarty->assign('log_in', _('Log in'));



$sql="select id,original_name,code from lang";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
$other_langs=array();
while($row=$res->fetchRow()) {
  if($row['code']==$current_lang){
    $smarty->assign('lang_id', $row['id']);
    $smarty->assign('lang_code', $row['code']);
  }else
    $other_langs[$row['id']]=$row['original_name'];
}



$smarty->assign('other_langs', $other_langs);

$smarty->display('login.tpl');
exit;

?>
