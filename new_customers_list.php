<?php
include_once('common.php');

if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}


if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    header('Location: customers.php?error');
    exit;
}

if (! ($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: customers.php?error_store='.$store_id);
    exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'table.css'
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
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'common_customers.js.php',
              'new_customers_list.js.php',
              'js/edit_common.js',
          );



$_SESSION['state']['customers']['list']['where']='';
$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$have_options=array(
                  'email'=>array('name'=>_('Email')),
                  'tel'=>array('name'=>_('Telephone')),
                  'fax'=>array('name'=>_('Fax')),
                  'address'=>array('name'=>_('Address')),
              );
$smarty->assign('have_options',$have_options);

$dont_have_options=array(
                       'email'=>array('name'=>_('Email')),
                       'tel'=>array('name'=>_('Telephone')),
                       'fax'=>array('name'=>_('Fax')),
                       'address'=>array('name'=>_('Address')),
                   );
$smarty->assign('dont_have_options',$dont_have_options);



$allow_options=array(
                      
                       'newsletter'=>array('name'=>_('Newsletter')),
                       'marketing_email'=>array('name'=>_('Marketing Email')),
                       'marketing_post'=>array('name'=>_('Marketing Post')),
                        'all'=>array('name'=>'No restrictions','selected'=>true), 
                   );
$smarty->assign('allow_options',$allow_options);


$smarty->assign('business_type',true);


$smarty->assign('view',$_SESSION['state']['customers']['view']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter1=$_SESSION['state']['world']['wregions']['f_field'];
$filter_menu1=array(
                  'wregion_code'=>array('db_key'=>_('wregion_code'),'menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
                  'continent_code'=>array('db_key'=>_('continent_code'),'menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['world']['wregions']['f_value']);



$tipo_filter2=$_SESSION['state']['world']['countries']['f_field'];
$filter_menu2=array(
                  'country_code'=>array('db_key'=>_('country_code'),'menu_label'=>_('Country Code'),'label'=>_('Code')),
                  'wregion_code'=>array('db_key'=>_('wregion_code'),'menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
                  'continent_code'=>array('db_key'=>_('continent_code'),'menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',$_SESSION['state']['world']['countries']['f_value']);


$smarty->display('new_customers_lists.tpl');
?>
