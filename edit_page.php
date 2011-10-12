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


$_SESSION['state']['page']['id']=$page->id;

$store=new Store($page->data['Page Store Key']);
$smarty->assign('store',$store);
$site=new Site($page->data['Page Site Key']);
$smarty->assign('site',$site);
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);


$smarty->assign('page',$page);




$general_options_list=array();

$general_options_list[]=array('class'=>'return','tipo'=>'url','url'=>'page.php?id='.$page->id,'label'=>_('Webpage').' &#8617;');

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
        default:
            $referral_label='';
            break;
        }
    }



    $general_options_list[]=array('class'=>'return','tipo'=>'url','url'=>$_REQUEST['referral'].'.php?id='.$_REQUEST['referral_key'],'label'=>$referral_label.' &#8617;');

}

$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');




if (isset($_REQUEST['view'])) {
    $valid_views=array('properties','page_header','page_footer','content','style','media','setup');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['page']['editing']=$_REQUEST['view'];

}
$smarty->assign('block_view',$_SESSION['state']['page']['editing']);




$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'container/assets/skins/sam/container.css',
               $yui_path.'editor/assets/skins/sam/editor.css',


               $yui_path.'assets/skins/sam/autocomplete.css',

               //	  'text_editor.css',
               'common.css',
               'button.css',
               'table.css',
               'css/edit.css'
           );


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
               'css/edit.css',
               'css/upload_files.css',
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
              'js/php.default.min.js',
              'js/common.js',
              'js/search.js',
              'js/table_common.js',
              'js/edit_common.js',
              'edit_page.js.php?page_id='.$page_key,

          );

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('parent','products');
$smarty->assign('title','Editing Page:'.$page->get('Page Short Title'));
$smarty->display('edit_page.tpl');
?>