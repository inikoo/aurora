<?
/*
 File: customer.php 

 UI customer page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/

include_once('common.php');
include_once('classes/Customer.php');
if(!$LU->checkRight(CUST_VIEW))
  exit;


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		  $yui_path.'editor/assets/skins/sam/editor.css',
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
		'js/common.js.php',
		'js/table_common.js.php',
		'js/search.js',
		'js/customer.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  $customer_id=$_SESSION['state']['customer']['id'];
else
  $customer_id=$_REQUEST['id'];

$_SESSION['state']['customer']['id']=$customer_id;
$_SESSION['state']['customer']['table']['sf']=0;

$customer=new customer($customer_id);
$customer->load('contacts');
$smarty->assign('customer',$customer);

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

$sql=sprintf("select `Customer Name` as name from `Customer Dimension`   where  %s < %s  order by %s desc  limit 1",$order,prepare_mysql($customer->get($order)),$order);
//print $sql;
$result=mysql_query($sql);
if(!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$sql=sprintf("select  `Customer Name` as name from `Customer Dimension`     where  %s>%s  order by %s   ",$order,prepare_mysql($customer->get($order)),$order);
$result=mysql_query($sql);
if(!$next=mysql_fetch_array($result, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');
$smarty->assign('prev',$prev);
$smarty->assign('next',$next);



// $smarty->assign('data_contact',$customer->contact->data);
// $smarty->assign('data_telecoms',$customer->contact->data);
// $smarty->assign('data_emails',$customer->contact->data);



//$sql=sprintf("select cu.total,cu.num_orders as orders,cu.order_interval,co.tipo as tipo_customer, cu.id as id,cu.contact_id,cu.name as name from customer as cu  left join contact as co on (cu.contact_id=co.id ) where cu.id=%d ",$customer_id);
//print $sql;


//$result=mysql_query($sql);

//$customer=mysql_fetch_array($result, MYSQL_ASSOC);



//$sql=sprintf("select main_address from contact where id=%d  ",$customer['contact_id']);

//$result=mysql_query($sql);
//$address=mysql_fetch_array($result, MYSQL_ASSOC);
// include('string.php');
// include('_contact.php');
// include('telecom.php');
// include('email.php');
// include('address.php');
// include('_customer.php');


//$customer->data= get_customer_data($customer_id);


//$customer_contact_id=$customer->data['contact_id'];
//$contact_data= get_contact_data($customer->data['contact_id']);
//print_r($contact_data);
//$telecoms=get_telecoms($contact_data['id']);

// $num_children=count($contact_data['child']);
// if($num_children==1){
//   $smarty->assign('contact',$contact_data['child'][0]['name']);
//  }
// elseif($num_children==2){
//   $smarty->assign('contact',$contact_data['child'][1]['name'].' & '.$contact_data['child'][0]['name']);
//  }



// //$since='2004-06-14';
// //  update_customer($customer_id,$since);





// $tel0='';
// $fax0='';
// $email0='';

// $tel=array();
// $fax=array();
// $email=array();





// $sql=sprintf("select tipo,name,code,number,ext from telecom where contact_id=".$customer_contact_id);
// $result = mysql_query($sql);

// while($tmp=mysql_fetch_array($result, MYSQL_ASSOC)){
//   if($tmp['tipo']==1)
//       $tel[]=($tmp['name']!=''?$tmp['name'].' ':'').($tmp['code']!=''?'+'.$tmp['code'].' ':'').$tmp['number'].($tmp['ext']!=''?' '._('Ext').'. '.$tmp['ext']:'');
//   if($tmp['tipo']==4)
//     $fax[]=($tmp['name']!=''?$tmp['name'].' ':'').($tmp['code']!=''?'+'.$tmp['code'].' ':'').$tmp['number'];
//  }
// $sql=sprintf("select contact,email from email where contact_id=".$customer_contact_id);
// $result = mysql_query($sql);
// $k=0;
// while($tmp=mysql_fetch_array($result, MYSQL_ASSOC)){
//   if($tmp['contact']!='')
//       $email[]=sprintf('<a href="mailto:%s">%s</a>',$row['email'],$row['contact']);
//   else
//     $email[]=sprintf('<a href="mailto:%s">%s</a>',$row['email'],$row['email']);
//  }
// // Contactos
// $sql=sprintf("select child_id,contact.name from contact_relations left join contact on (child_id=contact.id)where parent_id=".$customer_contact_id);
// $result = mysql_query($sql);
// $contact_name=array();
// $contact_tel=array();
// $contact_fax=array();
// $contact_email=array();
// $contact_tels=array();
// $contact_faxes=array();
// $contact_emails=array();
// $contact_mobiles=array();

// $contact_mobile=array();



// while($tmp=mysql_fetch_array($result, MYSQL_ASSOC)){
//   $contact_id=$tmp['child_id'];
//   $contact_name[$contact_id]=$tmp['name'];
//   $contact_tel[$contact_id]=array();
//   $contact_fax[$contact_id]=array();
//   $contact_email[$contact_id]=array();
//   $contact_mobile[$contact_id]=array();


//   $sql=sprintf("select contact,email from email where contact_id=".$contact_id);
//   $resulte = mysql_query($sql);
//   while($rowe=$resulte->fetchRow()){
//   if($rowe['contact']!=$tmp['name'] and $rowe['contact']!='')
//     $contact_email[$contact_id][]=sprintf('<a href="mailto:%s">%s</a>',$rowe['email'],$rowe['contact']);
//   else
//     $contact_email[$contact_id][]=sprintf('<a href="mailto:%s">%s</a>',$rowe['email'],$rowe['email']);
//   }
//   $contact_emails[$contact_id]=count($contact_email[$contact_id]);
  
//   $sql=sprintf("select tipo,name,code,number,ext from telecom where (tipo=1 or tipo=0) and contact_id=".$contact_id);
//   $resultt = mysql_query($sql);
//   while($row=$resultt->fetchRow())
//       $contact_tel[$contact_id]=($row['name']!=''?$row['name'].' ':'').($row['code']!=''?'+'.$row['code'].' ':'').$row['number'].($row['ext']!=''?' '._('Ext').'. '.$row['ext']:'');
//   $contact_tels[$contact_id]=count($contact_tel[$contact_id]);
 
//   $sql=sprintf("select tipo,name,code,number,ext from telecom where tipo=4 and contact_id=".$contact_id);
//   $resultt = mysql_query($sql);
//   while($row=$resultt->fetchRow())
//     $contact_fax[$contact_id]=($row['code']!=''?'+'.$row['code'].' ':'').$row['number'];
//   $contact_faxes[$contact_id]=count($contact_fax[$contact_id]);
 
//   $sql=sprintf("select tipo,name,code,number,ext from telecom where tipo=2 and contact_id=".$contact_id);
//   $resultt = mysql_query($sql);
//   while($row=$resultt->fetchRow())
//     $contact_mobile[$contact_id]=($row['code']!=''?'+'.$row['code'].' ':'').$row['number'];
//   $contact_mobiles[$contact_id]=count($contact_mobile[$contact_id]);


//  //  $contact_faxes[$contact_id]=count($contact_fax[$contact_id]);
  


//   //   if($row['tipo']==4)
//   //   $contact_fax[$contact_id]=($row['name']!=''?$row['name'].' ':'').($row['code']!=''?'+'.$row['code'].' ':'').$row['number'];

//  }
//$contacts=count($contact_name);




$smarty->assign('box_layout','yui-t0');




$smarty->assign('parent','customers.php');
$smarty->assign('title','Customer: '.$customer->get('customer name'));



$customer_home=_("Customers List");
// $smarty->assign('home',$customer_home);
// $smarty->assign('name',$customer->data['name']);
$smarty->assign('id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));
// $smarty->assign('atel',$tel);
// $smarty->assign('afax',$fax);
// $smarty->assign('tels',count($tel));
// $smarty->assign('faxes',count($fax));
// $smarty->assign('emails',count($email));
// $smarty->assign('aemail',$email);
// $smarty->assign('tipo_customer',$_tipo_customer[$customer_detail_data['tipo']]);
// $smarty->assign('tipo_customer_id',$customer_detail_data['tipo']);
// $smarty->assign('principal_address',display_full_address($contact_data['main_address']) );

//$smarty->assign('contacts',$contacts);
//$smarty->assign('contact_name',$contact_name);
//$smarty->assign('contact_emails',$contact_emails);
//$smarty->assign('acontact_email',$contact_email);
//$smarty->assign('contact_tels',$contact_tels);
//$smarty->assign('acontact_tel',$contact_tel);
// $smarty->assign('contact_faxes',$contact_faxes);
// $smarty->assign('acontact_fax',$contact_fax);
// $smarty->assign('contact_mobiles',$contact_mobiles);
// $smarty->assign('acontact_mobile',$contact_mobile);
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
// $smarty->assign('table_title',_('History'));

// $smarty->assign('telecoms',$telecoms);


$filter_menu=array(
		   'notes'=>array('db_key'=>'notes','menu_label'=>'Records with  notes *<i>x</i>*','label'=>_('Notes')),
		   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Notes')),

		   'uptu'=>array('db_key'=>'upto','menu_label'=>'Records up to <i>n</i> days','label'=>_('Up to (days)')),
		   'older'=>array('db_key'=>'older','menu_label'=>'Records older than  <i>n</i> days','label'=>_('Older than (days)'))
		   );
$tipo_filter=$_SESSION['state']['customer']['table']['f_field'];
$filter_value=$_SESSION['state']['customer']['table']['f_value'];
//print_r($_SESSION['state']['customer']['table']);
$smarty->assign('filter_value',$filter_value);

$smarty->assign('filter_menu',$filter_menu);
$smarty->assign('filter_name',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);



$smarty->display('customer.tpl');
?>