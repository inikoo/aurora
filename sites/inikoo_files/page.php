<?php

include_once('common_splinter.php');
include_once('class.LightCustomer.php');
include_once('class.Customer.php');
include_once('class.Store.php');
include_once('class.Page.php');

$page_key=$_REQUEST['id'];
$page=new Page($page_key);

//include_once('header.php');


$data=array('type'=>'parent', 'width'=>1000, 'customer_profile'=>1);

set_parameters($data);

global $disable_redirect, $auto_load;

$disable_redirect=true;

if (isset($_REQUEST['dialog_box'])) {
    $auto_load=$_REQUEST['dialog_box'];
} else
    $auto_load=false;


//include_once('top_navigation.php');
//include_once('footer.php');




//$smarty->assign('footer',$footer_);
if ($path=="../../") {
    $path_id=2;
    $path_menu='../';
}
elseif($path=="../") {
    $path_id=1;
    $path_menu='../forms/';
}
elseif($path=="../sites/") {
    $path_id=3;
    $path_menu='../sites/forms/';
}
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               // 'text_editor.css',
               //        'css/common.css',
               // 'button.css',
               // 'container.css',
               // 'table.css',
               //     'css/profile.css',
               // 'css/upload.css',
               //   'css/ui.css.php',
               //        'css/styles_5lour.css'
           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              $yui_path.'uploader/uploader-min.js',

              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'upload_common.js.php',
              'js/page.js'
              //'top_navigation_logout.js.php?path='.$path_id
          );


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='external_file.php?id='.$row['external_file_key'];

}
$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    if ($row['External File Type']=='CSS')
        $css_files[]='external_file.php?id='.$row['external_file_key'];
    else
        $js_files[]='external_file.php?id='.$row['external_file_key'];

}






$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$categories=array();
include_once('class.Category.php');

$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",$store_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $tmp=new Category($row['Category Key']);
    $categories[$row['Category Key']]=$tmp;
}

//print_r($categories);
$smarty->assign('categories',$categories);
$smarty->assign('count',1);
$smarty->assign('path',$path);
if (!$logged_in)
    $smarty->assign('St',$St);
$smarty->assign('authentication_type',$authentication_type);

if ($logged_in) {
    $rnd=md5(rand());
    $smarty->assign('rnd',$rnd);
    $smarty->assign('epwcp1',md5($user->id.'insecure_key'.$rnd));
}
$menubar=file_get_contents("$path".'inikoo_files/templates/menubar2011.html');
$smarty->assign('menubar',$menubar);

$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);

$template_string = 'display  here';
$smarty->assign('template_string',$page->data['Page Store Source']);
$smarty->display('page.tpl');
?>