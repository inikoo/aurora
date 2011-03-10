<?php
/*
 File: customer.php 

 UI customer page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('class.Customer.php');
include_once('class.Store.php');
if(!$user->can_view('customers')){
header('Location: index.php');
   exit;
 }

$modify=$user->can_edit('contacts');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['customer']['id']=$_REQUEST['id'];
  $customer_id=$_REQUEST['id'];
}else{
  $customer_id=$_SESSION['state']['customer']['id'];
}


$customer=new customer($customer_id);

if(!$customer->id){
 header('Location: customers.php?error='._('Customer not exists'));
  exit();

}

$_SESSION['state']['customer']['id']=$customer_id;
$_SESSION['state']['customers']['store']=$customer->data['Customer Store Key'];


if(isset($_REQUEST['view']) and preg_match('/^(history|products|orders)$/',$_REQUEST['view']) ){
 
  $view=$_REQUEST['view'];
}else{
  $view=$_SESSION['state']['customer']['view'];
}
if(!$customer->data['Customer Orders']){
$view='history';
}

$smarty->assign('view',$view);
 $_SESSION['state']['customer']['view']=$view;

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
		 'css/customer.css'

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
		'external_libs/ampie/ampie/swfobject.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'js/edit_common.js',
		'customer.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$customer->load('contacts');
$smarty->assign('customer',$customer);


$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



if(isset($_REQUEST['p'])){
  
  if($_REQUEST['p']=='cs'){

  $order=$_SESSION['state']['customers']['table']['order'];
  $order_label=$order;  
     if ($order=='name'){
        $order='`Customer File As`';
        $order_label=_('Name');
    }elseif($order=='id'){
    $order='`Customer Key`';
         $order_label=_('ID');
    }elseif($order=='location')
    $order='`Customer Main Location`';
    elseif($order=='orders'){
    $order='`Customer Orders`';
         $order_label='# '._('Orders');
    }elseif($order=='email')
    $order='`Customer Main Plain Email`';
    elseif($order=='telephone')
    $order='`Customer Main Plain Telephone`';
    elseif($order=='last_order')
    $order='`Customer Last Order Date`';
    elseif($order=='contact_name')
    $order='`Customer Main Contact Name`';
    elseif($order=='address')
    $order='`Customer Main Location`';
    elseif($order=='town')
    $order='`Customer Main Town`';
    elseif($order=='postcode')
    $order='`Customer Main Postal Code`';
    elseif($order=='region')
    $order='`Customer Main Country First Division`';
    elseif($order=='country')
    $order='`Customer Main Country`';
    //  elseif($order=='ship_address')
    //  $order='`customer main ship to header`';
    elseif($order=='ship_town')
    $order='`Customer Main Delivery Address Town`';
    elseif($order=='ship_postcode')
    $order='`Customer Main Delivery Address Postal Code`';
    elseif($order=='ship_region')
    $order='`Customer Main Delivery Address Country Region`';
    elseif($order=='ship_country')
    $order='`Customer Main Delivery Address Country`';
    elseif($order=='net_balance')
    $order='`Customer Net Balance`';
    elseif($order=='balance')
    $order='`Customer Outstanding Net Balance`';
    elseif($order=='total_profit')
    $order='`Customer Profit`';
    elseif($order=='total_payments')
    $order='`Customer Net Payments`';
    elseif($order=='top_profits')
    $order='`Customer Profits Top Percentage`';
    elseif($order=='top_balance')
    $order='`Customer Balance Top Percentage`';
    elseif($order=='top_orders')
    $order='``Customer Orders Top Percentage`';
    elseif($order=='top_invoices')
    $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
    $order='`Customer Total Refunds`';

    elseif($order=='activity')
    $order='`Customer Type by Activity`';
    else
        $order='`Customer File As`';

   $_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($customer->get($_order)),$order);

$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'name'=>'');
mysql_free_result($result);

$smarty->assign('prev',$prev);
$sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`     where  %s>%s  order by %s   ",$order,prepare_mysql($customer->get($_order)),$order);

$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'name'=>'');
mysql_free_result($result);
$smarty->assign('parent_info',"p=cs&");

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);
$store=new Store($customer->data['Customer Store Key']);
$smarty->assign('parent_url','customers.php?store='.$store->id);
$parent_title=$store->data['Store Code'].' '._('Customers').' ('.$order_label.')';
$smarty->assign('parent_title',$parent_title);

}



}


$show_details=$_SESSION['state']['customer']['details'];
$smarty->assign('show_details',$show_details);
$general_options_list=array();

 


if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_customer.php?id='.$customer->id,'label'=>_('Edit Customer'));

    $general_options_list[]=array('tipo'=>'js','id'=>'export_data','label'=>_('Export Customer (CSV)'));

//  $general_options_list[]=array('tipo'=>'url','url'=>'customer_csv.php?id='.$customer->id,'label'=>_('Export Data (CSV)'));

  $general_options_list[]=array('tipo'=>'url','url'=>'pdf_customer.php?id='.$customer->id,'label'=>_('Print Address Label'));


$smarty->assign('general_options_list',$general_options_list);




$smarty->assign('number_orders',$customer->get('Customer Orders'));
$smarty->assign('parent','customers');
$smarty->assign('title','Customer: '.$customer->get('customer name'));
$customer_home=_("Customers List");
$smarty->assign('id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));
$total_orders=$customer->get('Customer Orders');
$smarty->assign('orders',number($total_orders)  );
$total_net=$customer->get('Customer Total Net Payments');
$smarty->assign('total_net',money($total_net));
$total_invoices=$customer->get('Customer Orders Invoiced');
$smarty->assign('invoices',number($total_invoices)  );
if($total_invoices>0)
  $smarty->assign('total_net_average',money($total_net/$total_invoices));

$order_interval=$customer->get('Customer Order Interval');

if($order_interval>10){
  $order_interval=round($order_interval/7);
  if( $order_interval==1)
    $order_interval=_('week');
  else
    $order_interval=$order_interval.' '._('weeks');
  
 }else if($order_interval=='')
  $order_interval='';
else
  $order_interval=round($order_interval).' '._('days');
$smarty->assign('orders_interval',$order_interval);
$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['customer']['table']['f_field'];
$filter_value=$_SESSION['state']['customer']['table']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Code like','label'=>_('Code')),
		   );
$tipo_filter=$_SESSION['state']['customer']['assets']['f_field'];
$filter_value=$_SESSION['state']['customer']['assets']['f_value'];

$smarty->assign('filter_value1',$filter_value);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->display('customer.tpl');

?>
