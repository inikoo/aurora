<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.CompanyArea.php');



if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit();
}
if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];
} else {
    $store_id=$_SESSION['state']['customers']['store'];
}
if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}
$store=new Store($store_id);
$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');




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
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/edit_common.js',
              'customers_pending_orders.js.php',
              'js/edit_common.js',
              'js/csv_common.js',
              
          );




$smarty->assign('parent','customers');
$smarty->assign('title', _('Pending Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'

//print_r($pickers_data);

$tipo_filter2=$_SESSION['state']['customers']['pending_orders']['f_field'];
$smarty->assign('filter0',$tipo_filter2);
$smarty->assign('filter_value0',($_SESSION['state']['customers']['pending_orders']['f_value']));
$filter_menu2=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
              );
$smarty->assign('filter_menu0',$filter_menu2);
$smarty->assign('filter_name0',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Packed','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'

$elements_number=array('InProcessbyCustomer'=>0,'InProcess'=>0,'SubmittedbyCustomer'=>0,'InWarehouse'=>0,'Packed'=>0);
$sql=sprintf("select count(*) as num,`Order Current Dispatch State` from  `Order Dimension` where  `Order Store Key`=%d  group by `Order Current Dispatch State` ",$store_id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[preg_replace('/\s/','',$row['Order Current Dispatch State'])]=$row['num'];
}

$sql=sprintf("select count(*) as num  from  `Order Dimension` where  `Order Store Key`=%d  and `Order Current Dispatch State` in ('Ready to Pick','Picking & Packing','Ready to Ship') ",$store_id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number['InWarehouse']=$row['num'];
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['customers']['pending_orders']['elements']);

//print_r($_SESSION['state']['customers']['pending_orders']['elements']);

$smarty->display('customers_pending_orders.tpl');
?>
