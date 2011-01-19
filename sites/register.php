<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.Page.php');

$css_files=array(

               'css/common.css',
               'css/home.css',
               'css/info.css',
               'css/register.css',
               'css/dropdown.css'
           );
$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-debug.js',
              'js/md5.js',
              'js/sha256.js',

              'js/dropdown.js'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if (isset($_REQUEST['we'])) {
    $smarty->assign('error',true);

}

$store=new Store($store_key);
$smarty->assign('store',$store);


$page=new Page('store_page_code',$store_key,'register');

if (!$page->id) {
    header('Location: index.php');
}

$smarty->assign('home_header_template' , "templates/home_header.".$store->data['Store Locale'].".tpl" );
$smarty->assign('right_menu_template'  , "templates/right_menu.".$store->data['Store Locale'].".tpl"  );
$smarty->assign('left_menu_template'   , "templates/left_menu.".$store->data['Store Locale'].".tpl"   );



$smarty->assign('page',$page);
$page_data=$page->get_data_for_smarty($page_data);
$smarty->assign('page_data',$page_data);




update_page_key_visit_log($page->data['Page Key']);
$_SESSION['prev_page_key']=$page->data['Page Key'];

//$options=$page->get_options();


//print_r($page_data);
if ($site->data['Registration Type']=='Steps') {
    $js_files[]='js/register.js.php';
    $template="templates/register_steps.".$store->data['Store Locale'].".tpl";
} else {
    $js_files[]='js/register_2.js.php';
    $template="templates/register_simple.".$store->data['Store Locale'].".tpl";

}

$smarty->assign('js_files',$js_files);
$smarty->display($template);




?>
