<?php
include_once('common.php');

include_once('class.EmailCampaign.php');

if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}
//$modify=$user->can_edit('staff');
$general_options_list=array();
if (isset($_REQUEST['id']))
    $id=$_REQUEST['id'];
else {
    header('Location: index.php?error=no_id_in_customers_list');
    exit;

}



$email_campaign=new EmailCampaign($id);


if (!$email_campaign->id) {
    header('Location: index.php?error=email_campaign_not_found');
    exit;

}

$smarty->assign('email_campaign',$email_campaign);


$store=new Store($email_campaign->data['Email Campaign Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);

$smarty->assign('search_scope','marketing');
$smarty->assign('search_label',_('Search'));





$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'table.css',
               'button.css',
               'theme.css.php'
           );

$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',


              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'common_customers.js.php',
              'email_campaign_mailing_list.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','customers');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['customers']['table']['view']);

$smarty->assign('title', _('Customer Static List'));
$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','marketing');

$smarty->display('email_campaign_mailing_list.tpl');
?>
