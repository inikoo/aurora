<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Page.php');
include_once('class.Site.php');
include_once('class.Store.php');


if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {
    $page_key=$_REQUEST['id'];
    $_SESSION['state']['page']['id']=$page_key;

}
elseif($_SESSION['state']['page']['id']) {
    $page_key=$_SESSION['state']['page']['id'];
}
else {
    exit('page ID not specified');
}



if (!($user->can_view('stores')    ) ) {
    header('Location: index.php');
    exit;
}




$page=new Page($page_key);
if (!$page->id) {
    header('Location: index.php');
    exit;
}


if (isset($_REQUEST['update_heights'])  and  $_REQUEST['update_heights']) {
    $smarty->assign('update_heights',1);
}else{
 $smarty->assign('update_heights',0);
}


if (isset($_REQUEST['redirect_review']) and $_REQUEST['redirect_review']==1  ) {
    $smarty->assign('redirect_review',1);
}else{
    $smarty->assign('redirect_review',0);
}


if (isset($_REQUEST['take_snapshot']) and $_REQUEST['take_snapshot']  ) {
    $smarty->assign('take_snapshot',1);
}else{
    $smarty->assign('take_snapshot',0);
}


$_SESSION['state']['page']['id']=$page->id;

$store=new Store($page->data['Page Store Key']);
$smarty->assign('store',$store);
$site=new Site($page->data['Page Site Key']);
$smarty->assign('site',$site);
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$smarty->assign('page',$page);





if (isset($_REQUEST['referral']) and isset($_REQUEST['referral_key'])) {
    $valid_referrals=array('family','department','store');
    if (in_array($_REQUEST['referral'], $valid_referrals) and is_numeric($_REQUEST['referral_key'])) {
        switch ($_REQUEST['referral']) {
        case 'family':
            $referral_label=_('Family');
            break;
        case 'department':
            $referral_label=_('Department');
            break;
        case 'store':
            $referral_label=_('Store');
            break;
        case 'site':
            $referral_label=_('Site');
            break;
        default:
            $referral_label='';
            break;
        }
        
    $referral_data=array('url'=>$_REQUEST['referral'].'.php?id='.$_REQUEST['referral_key'],'label'=>$referral_label);
    $smarty->assign('referral_data',$referral_data);     
    }

   


}


$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');




if (isset($_REQUEST['view'])) {
    $valid_views=array('content','style','media','setup');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['page']['editing']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['page']['editing']);

  
$content_view=$_SESSION['state']['page']['editing_content_block'];
if (isset($_REQUEST['content_view'])) {
    $valid_views=array('header','content','footer','product_list','product_buttons','overview');
    if (in_array($_REQUEST['content_view'], $valid_views))
        $content_view=$_REQUEST['content_view'];

}
$smarty->assign('content_view',$content_view);




$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',

               $yui_path.'assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'assets/skins/sam/colorpicker.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'css/edit.css',
               'css/upload_files.css',
               'css/edit_page.css',
               'theme.css.php'

           );



$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',

              $yui_path.'editor/editor-min.js',
              $yui_path.'slider/slider-min.js',
              $yui_path.'colorpicker/colorpicker-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/search.js',
              'js/table_common.js',
              'js/edit_common.js',
              'js/editor_image_uploader.js',
              'edit_page.js.php?page_key='.$page->id.'&site_key='.$page->data['Page Site Key'],

          );

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$tipo_filter=$_SESSION['state']['page']['history']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['page']['history']['f_value']);
$filter_menu=array(
                 'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
                 'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),
                 'upto'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
                 'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)')),
                 'abstract'=>array('db_key'=>'abstract','menu_label'=>'Records with abstract','label'=>_('Abstract'))
             );
$smarty->assign('filter_name1',$filter_menu);
$smarty->assign('filter_menu1',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);


$tipo_filter=$_SESSION['state']['page']['edit_product_list']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['page']['edit_product_list']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Code like *<i>x</i>*','label'=>_('Code')),
                );
$smarty->assign('filter_name2',$filter_menu);
$smarty->assign('filter_menu2',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$tipo_filter=$_SESSION['state']['page']['edit_product_button']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['page']['edit_product_button']['f_value']);
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Code like *<i>x</i>*','label'=>_('Code')),
                );
$smarty->assign('filter_name3',$filter_menu);
$smarty->assign('filter_menu3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('filter7','code');
$smarty->assign('filter_value7','');
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Page code starting with  <i>x</i>'),'label'=>_('Code')),
	'title'=>array('db_key'=>'title','menu_label'=>_('Page title like  <i>x</i>'),'label'=>_('Title')),
);
$smarty->assign('filter_menu7',$filter_menu);
$smarty->assign('filter_name7',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu7',$paginator_menu);

$tipo_filter='name';
$smarty->assign('filter8','name');
$smarty->assign('filter_value8','');
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu8',$filter_menu);
$smarty->assign('filter_name8',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu8',$paginator_menu);

$tipo_filter='name';
$smarty->assign('filter9','name');
$smarty->assign('filter_value9','');
$filter_menu=array(
	'name'=>array('db_key'=>'name','menu_label'=>_('Name starting with <i>x</i>'),'label'=>_('Name')),
);
$smarty->assign('filter_menu9',$filter_menu);
$smarty->assign('filter_name9',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu9',$paginator_menu);


$order=$_SESSION['state']['site']['pages']['order'];
if ($order=='code') {
    $order='`Page Code`';
    $order_label=_('Code');
} else if ($order=='url') {
    $order='`Page URL`';
    $order_label=_('URL');
} else if ($order=='title') {
    $order='`Page Store Title`';
    $order_label=_('Title');
} else {
    $order='`Page Code`';
    $order_label=_('Code');
}

$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`   where  `Page Site Key`=%d  and %s < %s  order by %s desc  limit 1",
             $site->id,
             $order,
             prepare_mysql($page->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='edit_page.php?id='.$row['id'];
    $prev['title']=$row['name'];
$smarty->assign('prev',$prev);
}
mysql_free_result($result);

$sql=sprintf(" select `Page Key` as id , `Page Store Title` as name from `Page Store Dimension`    where  `Page Site Key`=%d  and  %s>%s  order by %s   ",
             $site->id,
             $order,
             prepare_mysql($page->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='edit_page.php?id='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);

}
mysql_free_result($result);

$smarty->assign('parent_url','site.php?id='.$site->id);
$parent_title=$site->data['Site Name'].' '._('Pages').' ('.$order_label.')';
$smarty->assign('parent_title',$parent_title);




$smarty->assign('parent','websites');
$smarty->assign('title',_('Editing Page').': '.$page->data['Page Code'].' ('.$site->data['Site Code'].')');

$smarty->display('edit_page.tpl');
?>