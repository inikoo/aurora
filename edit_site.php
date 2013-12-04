<?php
/*
 File: site.php

 UI site page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Site.php');
include_once('class.Store.php');

include_once('assets_header_functions.php');


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $site_id=$_REQUEST['id'];

} else {
    $site_id=$_SESSION['state']['site']['id'];
}


if (!($user->can_view('sites')  ) ) {
    header('Location: index.php');
    exit;
}
if (!$user->can_edit('sites') ) {
    header('Location: site.php?error=cannot_edit');
    exit;
}


$site=new Site($site_id);
$_SESSION['state']['site']['id']=$site->id;
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);





$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');





$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);



get_header_info($user,$smarty);





$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'theme.css.php'
           );
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'dragdrop/dragdrop-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',

              'js/search.js',
              'js/pages_common.js',
		'js/upload_image.js'
          );

$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');


if (isset($_REQUEST['block_view'])) {
    $valid_views=array('general','components','theme','style','pages');
    if (in_array($_REQUEST['block_view'], $valid_views))
        $_SESSION['state']['site']['editing']=$_REQUEST['block_view'];

}

if (isset($_REQUEST['components_block_view'])) {
    $valid_views=array('head','headers','footers','menu','website_search','email','client_profile','checkout');
    if (in_array($_REQUEST['components_block_view'], $valid_views))
        $_SESSION['state']['site']['editing_components']=$_REQUEST['components_block_view'];

}





$smarty->assign('block_view',$_SESSION['state']['site']['editing']);
$smarty->assign('components_block_view',$_SESSION['state']['site']['editing_components']);
$smarty->assign('style_block_view',$_SESSION['state']['site']['editing_style']);
$smarty->assign('general_block_view',$_SESSION['state']['site']['editing_general']);


$css_files[]='css/edit.css';

$js_files[]='js/edit_common.js';
$js_files[]='country_select.js.php';
$js_files[]='edit_site.js.php';
$js_files[]='email_credential.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('pages_view',$_SESSION['state']['site']['edit_pages']['view']);


$_SESSION['state']['assets']['page']='site';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['site']['view']=$_REQUEST['view'];

}
$smarty->assign('view',$_SESSION['state']['site']['view']);




$subject_id=$site_id;


$smarty->assign('site',$site);

//print_r($site->get_images_slidesshow());

$smarty->assign('parent','websites');
$smarty->assign('title', _('Editing Website').': '.$site->data['Site Code']);




$smarty->assign('show_history',$_SESSION['state']['site']['show_history']);

$tipo_filter=$_SESSION['state']['site']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))

             );
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['site']['edit_headers']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['site']['edit_headers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>'Headers with name *<i>x</i>*','label'=>_('Name'))

             );
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['site']['edit_footers']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['site']['edit_footers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>'Footers with name *<i>x</i>*','label'=>_('Name'))

             );
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu3',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);


$tipo_filter=$_SESSION['state']['site']['edit_pages']['f_field'];
$smarty->assign('filter4',$tipo_filter);
$smarty->assign('filter_value4',$_SESSION['state']['site']['edit_pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
                 'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

             );
$smarty->assign('filter_menu4',$filter_menu);
$smarty->assign('filter_name4',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu4',$paginator_menu);


$tipo_filter5='code';
$filter_menu5=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name5',$filter_menu5[$tipo_filter5]['label']);
$smarty->assign('filter_menu5',$filter_menu5);
$smarty->assign('filter5',$tipo_filter5);
$smarty->assign('filter_value5','');

$tipo_filter6='code';
$filter_menu6=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name6',$filter_menu6[$tipo_filter6]['label']);
$smarty->assign('filter_menu6',$filter_menu6);
$smarty->assign('filter6',$tipo_filter6);
$smarty->assign('filter_value6','');

$tipo_filter7='code';
$filter_menu7=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name7',$filter_menu7[$tipo_filter7]['label']);
$smarty->assign('filter_menu7',$filter_menu7);
$smarty->assign('filter7',$tipo_filter7);
$smarty->assign('filter_value7','');


$tipo_filter8='code';
$filter_menu8=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name8',$filter_menu8[$tipo_filter8]['label']);
$smarty->assign('filter_menu8',$filter_menu8);
$smarty->assign('filter8',$tipo_filter8);
$smarty->assign('filter_value8','');

$tipo_filter9='code';
$filter_menu9=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name9',$filter_menu9[$tipo_filter9]['label']);
$smarty->assign('filter_menu9',$filter_menu9);
$smarty->assign('filter9',$tipo_filter9);
$smarty->assign('filter_value9','');



$credentials=array();
if($site->get_email_credentials()){
foreach($site->get_email_credentials() as $key=>$value){
	$key=preg_replace('/\s/', '_', $key);
	$credentials[$key]=$value;
}
}
else{
	$credentials['Email_Address_Gmail']='';
	$credentials['Password_Gmail']='';
	$credentials['Email_Address_Other']='';
	$credentials['Login_Other']='';
	$credentials['Password_Other']='';
	$credentials['Incoming_Mail_Server']='';
	$credentials['Outgoing_Mail_Server']='';
	$credentials['Email_Address_Direct_Mail']='';
	$credentials['Email_Address_Amazon_Mail']='';
	$credentials['API_Key_MadMimi']='';
	$credentials['API_Email_Address_MadMimi']='';
	$credentials['Email_Address_MadMimi']='';

}

$smarty->assign('email_credentials',$credentials);

$smarty->display('edit_site.tpl');

?>