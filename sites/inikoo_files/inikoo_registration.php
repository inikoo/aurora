<?php

include_once('common_splinter.php');
include_once('class.LightCustomer.php');
include_once('class.Customer.php');
include_once('class.Store.php');
//include_once('header.php');


$data=array('type'=>'parent', 'width'=>1000, 'customer_profile'=>1);

set_parameters($data);

global $disable_redirect, $auto_load;

$disable_redirect=true;

if(isset($_REQUEST['dialog_box'])){
	$auto_load=$_REQUEST['dialog_box'];
}
else
	$auto_load=false;


include_once('top_navigation.php');
include_once('footer.php');
$smarty->assign('footer',$footer_);
if($path=="../../"){
	$path_id=2;
    $path_menu='../';
}elseif($path=="../"){
	$path_id=1;
	  $path_menu='../forms/';
}elseif($path=="../sites/"){
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

               'text_editor.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/profile.css',
               'css/upload.css'
           );
//include_once('Theme.php');
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
			//'top_navigation_logout.js.php?path='.$path_id
          );
          
          
          
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$categories=array();
include_once('class.Category.php');

$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d",$store_key);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$tmp=new Category($row['Category Key']);
$categories[$row['Category Key']]=$tmp;
}

//print_r($categories);
$smarty->assign('categories',$categories);
$smarty->assign('count',1);
$smarty->assign('path',$path);
if(!$logged_in)
$smarty->assign('St',$St);
$smarty->assign('authentication_type',$authentication_type);

if($logged_in){
$rnd=md5(rand()); 
$smarty->assign('rnd',$rnd);
$smarty->assign('epwcp1',md5($user->id.'insecure_key'.$rnd));
}


$smarty->display('inikoo_registration.tpl');
?>