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
if(!$user->can_view('customers')){

header('Location: customers.php');
   exit;
 

}

$modify=$user->can_edit('contacts');

$edit=false;
if(isset($_REQUEST['edit']) and $_REQUEST['edit']){
  $edit=true;
  $_REQUEST['id']=$_REQUEST['edit'];
 }

if(!$modify)
  $edit=false;

if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ){
  $_SESSION['state']['customer']['id']=$_REQUEST['id'];
  $customer_id=$_REQUEST['id'];
}else{
  $customer_id=$_SESSION['state']['customer']['id'];
}


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 //$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

		 'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
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
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'customer.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$_SESSION['state']['customer']['id']=$customer_id;
$_SESSION['state']['customer']['table']['sf']=0;

$customer=new customer($customer_id);
$customer->load('contacts');
$smarty->assign('customer',$customer);


if($edit ){
  $smarty->assign('customer_type',$customer->data['Customer Type']);
   $css_files[]=$yui_path.'assets/skins/sam/autocomplete.css';
    $css_files[]='css/edit_address.css';
    $css_files[]='css/edit.css';
    $js_files[]='js/edit_common.js';
    $js_files[]='js/validate_telecom.js';
  
  if($customer->data['Customer Type']=='Company'){
    $company=new Company($customer->data['Customer Company Key']);
    if(!$company->id){
      print "error no company found".print_r($customer);
    }
    $smarty->assign('company',$company);
    
    $offset=1;// 0 is reserved to new address
    $addresses=$company->get_addresses($offset);
    $smarty->assign('addresses',$addresses);
    $number_of_addresses=count($addresses);
    $smarty->assign('number_of_addresses',$number_of_addresses);
    
    $contacts=$company->get_contacts($offset);
    $smarty->assign('contacts',$contacts);
    $number_of_contacts=count($contacts);
    $smarty->assign('number_of_contacts',$number_of_contacts);
    $js_files[]=sprintf('edit_company.js.php?id=%d&scope=Customer&scope_key=%d',$company->id,$customer->id);
    
  }else{

    $contact=new Contact($customer->data['Customer Main Contact Key']);
    $smarty->assign('contact',$contact);
    


  }    
  

    $smarty->assign('scope','customer');
    $smarty->assign('scope_key',$customer->id);
    
    
    
    
    $sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Code`=L.`Language ISO 639-1 Code`  where `Language Code`=%s limit 1000",prepare_mysql($myconf['lang']));
    $result=mysql_query($sql);
    $salutations=array();
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
    }
    mysql_free_result($result);
    
    $smarty->assign('prefix',$salutations);
    
    $editing_block=$_SESSION['state']['customer']['edit'];
    $smarty->assign('edit',$editing_block);
    
   
    $js_files[]='edit_address.js.php';
    $js_files[]='edit_contact_from_parent.js.php';
	
    $js_files[]='edit_contact_telecom.js.php';
    $js_files[]='edit_contact_name.js.php';
    $js_files[]='edit_contact_email.js.php';
    $js_files[]=sprintf('edit_customer.js.php');
    

  $smarty->assign('css_files',$css_files);
  $smarty->assign('js_files',$js_files);
  $smarty->display('edit_customer.tpl');
  exit();
  

}else{

  
  $order=$_SESSION['state']['customers']['table']['order'];
  if($order=='name')
    $order='`Customer File As`';
  elseif($order=='id')
    $order='`Customer ID`';
  elseif($order=='location')
     $order='`Customer Main Location`';
   elseif($order=='orders')
     $order='`Customer Orders`';
   elseif($order=='email')
     $order='`Customer Email`';
   elseif($order=='telephone')
     $order='`Customer Main Telehone`';
   elseif($order=='last_order')
     $order='`Customer Last Order Date`';
   elseif($order=='contact_name')
     $order='`Customer Main Contact Name`';
   elseif($order=='address')
     $order='`Customer Main Location`';
   elseif($order=='town')
     $order='`Customer Main Address Town`';
   elseif($order=='postcode')
     $order='`Customer Main Address Postal Code`';
   elseif($order=='region')
     $order='`Customer Main Address Country Primary Division`';
   elseif($order=='country')
     $order='`Customer Main Address Country`';
   //  elseif($order=='ship_address')
   //  $order='`customer main ship to header`';
   elseif($order=='ship_town')
     $order='`Customer Main Ship To Town`';
   elseif($order=='ship_postcode')
     $order='`Customer Main Ship To Postal Code`';
   elseif($order=='ship_region')
     $order='`Customer Main Ship To Country Region`';
   elseif($order=='ship_country')
     $order='`Customer Main Ship To Country`';
   elseif($order=='net_balance')
     $order='`Customer Net Balance`';
   elseif($order=='balance')
     $order='`Customer Outstanding Net Balance`';
   elseif($order=='total_profit')
     $order='`Customer Profit`';
   elseif($order=='total_payments')
     $order='`Customer Total Payments`';
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
else
   $order='`Customer File As`';

   $_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($customer->get($_order)),$order);
//print $sql;
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

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



$show_details=$_SESSION['state']['customer']['details'];
$smarty->assign('show_details',$show_details);
$general_options_list=array();
if($modify)
  $general_options_list[]=array('tipo'=>'url','url'=>'customer.php?edit='.$customer->id,'label'=>_('Edit Customer'));
$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));

$smarty->assign('general_options_list',$general_options_list);





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
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),

		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['customer']['table']['f_field'];
$filter_value=$_SESSION['state']['customer']['table']['f_value'];
$smarty->assign('filter_value',$filter_value);
$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);
$smarty->display('customer.tpl');
}
?>