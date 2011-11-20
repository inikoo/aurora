<?php
include_once('common.php');
include_once('class.Store.php');
/*if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}*/


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
$smarty->assign('store_id',$store_id);
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
              'new_products_list.js.php',
              'js/edit_common.js',
          );



$_SESSION['state']['products']['list']['where']='';
$smarty->assign('parent','products');
$smarty->assign('title', _('Lists'));
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

$condition=array(
                       'less'=>array('name'=>_('Less than')),
                       'equal'=>array('name'=>_('Equal')),
                       'more'=>array('name'=>_('More than')),
					   'between'=>array('name'=>_('Between'))
                   );
$smarty->assign('condition',$condition);

$web_state=array(
                       'online_force_out_of_stock'=>array('name'=>_('Online Force Out of Stock')),
                       'online_auto'=>array('name'=>_('Online Auto')),
                       'offline'=>array('name'=>_('Offline')),
					   'unknown'=>array('name'=>_('Unknown')),
					   'online_force_for_sale'=>array('name'=>_('Online Force For Sale'))			   
                   );
$smarty->assign('web_state',$web_state);

$availability_state=array(
                       'optimal'=>array('name'=>_('Optimal')),
                       'low'=>array('name'=>_('Low')),
                       'critical'=>array('name'=>_('Critical')),
					   'surplus'=>array('name'=>_('Surplus')),
					   'out_of_stock'=>array('name'=>_('Out of Stock')),
					   'unknown'=>array('name'=>_('Unknown')),		
					   'no_applicable'=>array('name'=>_('No applicable'))		   
                   );
$smarty->assign('availability_state',$availability_state);


$smarty->assign('view',$_SESSION['state']['customers']['table']['view']);
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


$smarty->display('new_products_list.tpl');
?>
