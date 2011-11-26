<?php

include_once('common.php');




include_once('class.Customer.php');
include_once('class.Store.php');
include_once('class.Page.php');
include_once('class.Site.php');

if (!isset($_REQUEST['id'])  or  !is_numeric($_REQUEST['id']) ) {
     header('Location: index.php');
    exit;
} 


$page_key=$_REQUEST['id'];
$page=new Page($page_key);

$site=new Site($page->data['Page Site Key']);
$store=new Store($page->data['Page Store Key']);


$css_files=array(

           );
//include_once('Theme.php');
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
           
			'js/page_preview.js'
			
          );
          
          
 
          
          
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('title',_('Preview').' '.$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);

$smarty->assign('template_string',$page->data['Page Store Source']);
$smarty->display('page_preview.tpl');
?>