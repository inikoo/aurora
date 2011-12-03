<?php

include_once('app_files/db/dns.php');
include_once('class.Image.php');
include_once('class.DummyPage.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}
date_default_timezone_set('UTC');

require_once 'common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');



include_once('class.Customer.php');
include_once('class.Store.php');
include_once('class.PageFooter.php');
include_once('class.Page.php');

include_once('class.Site.php');

if (!isset($_REQUEST['id'])  or  !is_numeric($_REQUEST['id']) ) {
   
    exit;
} 

require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';
$smarty->error_reporting = E_ERROR;






$page_footer_key=$_REQUEST['id'];
$page_footer=new PageFooter($page_footer_key);

if(!$page_footer->id)
    exit;
    
 
$site=new Site($page_footer->data['Site Key']);
//$store=new Store($page->data['Page Store Key']);


$css_files=array(
        $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
           );
           
           
           
           
//include_once('Theme.php');
$js_files=array(
              $yui_path.'utilities/utilities.js',
    //          $yui_path.'json/json-min.js',
  //            $yui_path.'paginator/paginator-min.js',
           
//			'js/page_footer_preview.js'
			
          );
          
   $sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page_footer_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='public_external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

       
 
          
          
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('title',_('Preview').' '.$page_footer->data['Page Footer Name']);

$smarty->assign('site',$site);
$page=new Dummy_Page();

$smarty->assign('page',$page);
$smarty->assign('page_footer',$page_footer);
//print_r($page_footer);

$smarty->display('page_footer_preview.tpl');




?>