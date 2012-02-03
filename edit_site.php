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


if (!($user->can_view('stores')  ) ) {
    header('Location: index.php');
    exit;
}
if (!$user->can_edit('stores') ) {
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
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
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
              'js/pages_common.js'
          );

$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');

$smarty->assign('block_view',$_SESSION['state']['site']['editing']);

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

$smarty->assign('parent','products');
$smarty->assign('title', $site->data['Site Name']);





$tipo_filter=$_SESSION['state']['site']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['site']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
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
$smarty->assign('filter6',$tipo_filter);
$smarty->assign('filter_value6',$_SESSION['state']['site']['edit_pages']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Page code starting with  <i>x</i>','label'=>'Code'),
                 'title'=>array('db_key'=>'code','menu_label'=>'Page title like  <i>x</i>','label'=>'Code'),

             );
$smarty->assign('filter_menu6',$filter_menu);
$smarty->assign('filter_name6',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu6',$paginator_menu);
$elements_number=array('FamilyCatalogue'=>0,'DepartmentCatalogue'=>0,'ProductDescription'=>0,'Other'=>0);
$sql=sprintf("select count(*) as num,`Page Store Section` from  `Page Store Dimension` where `Page Site Key`=%d group by `Page Store Section`",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$_key=preg_replace('/ /','',$row['Page Store Section']);

   if(in_array($_key,array('FamilyCatalogue','DepartmentCatalogue','ProductDescription')))
   $elements_number[$_key]=$row['num'];
   else{
    $elements_number['Other']+=$row['num'];
   }
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['site']['edit_pages']['elements']);



$credentials=array();
if($site->get_site_email_credentials()){
foreach($site->get_site_email_credentials() as $key=>$value){
	$key=preg_replace('/\s/', '_', $key);
	$credentials[$key]=$value;
}
}
else{
	$credentials['Email_Address']='';
	$credentials['Password']='';
	$credentials['Incoming_Mail_Server']='';
	$credentials['Outgoing_Mail_Server']='';
}

$smarty->assign('email_credentials',$credentials);

$smarty->display('edit_site.tpl');

?>