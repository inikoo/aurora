<?php
/*
 File: customers.php 

 UI customers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');

if(!$user->can_view('customers')){
  header('Location: index.php');
  exit();
}

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
  $store_id=$_SESSION['state']['customers']['store'];

}

if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
  header('Location: index.php');
   exit;
}

$store=new Store($store_id);
$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);

$_SESSION['state']['customers']['store']=$store_id;

$modify=$user->can_edit('customers');



$general_options_list=array();


   $general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Exit Customer Stats'));


$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



 


		 
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
		//'external_libs/ampie/ampie/swfobject.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'js/edit_common.js',
        'js/csv_common.js',
		'customers_stats.js.php',
		 'external_libs/ammap/ammap/swfobject.js'
		);


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers Stats'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$new_window=30;

$sigma_factor=3.2906;//99.9% value assuming normal distribution
$sql=sprintf("select sum(if(`Customer Type by Activity`='New',1,0)) as new from `Customer Dimension` where DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= `Customer First Order Date` and `Customer Store Key`=%d"
,$store_id
);;
$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   
   $new_customers_1m=$row['new'];
 }
mysql_free_result($result);

 $sql=sprintf("select count(distinct `Customer Key`) as customers,sum(if(`Customer Type by Activity`='New',1,0)) as new,sum(if(`Customer Type by Activity`='Active',1,0)) as active ,sum(if(`Customer Type by Activity`='Inactive',1,0)) as inactive from `Customer Dimension` where `Customer Store Key`=%d"
 ,$store_id
 );
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
 if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
   $total_customers=$row['customers'];
   $active_customers=$row['active'];
   $new_customers=$row['new'];
   $inactive_customers=$row['inactive'];
  }
mysql_free_result($result);



 /* $now="NOW()"; */
/*  $sql="select count(*) as active_customers from `Customer Dimension` where  (`Customer Order Interval`)>DATEDIFF($now,`Customer Last Order Date`)"; */
/*  $result = mysql_query($sql) or die('Query failed: ' . mysql_error()); */
/*  $active_customers=0; */
/*  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) { */
/*    $active_customers=$row['active_customers']; */
/*   } */

/*  $sql="select count(*) as new_customers from `Customer Dimension` where   (91.25)>DATEDIFF($now,`Customer First Order Date`)"; */
/*  $result = mysql_query($sql) or die('Query failed: ' . mysql_error()); */
/*  $new_customers=0; */
/*  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) { */
/*    $new_customers=$row['new_customers']; */
/*   } */


$overview_text=translate("We have had  %1\$s  customers so far, %2\$s of them still active (%3\$s%\). Over the last month we acquired  %4\$s new customers representing  %5\$s of the total active customer base."
			 ,number($total_customers)
			 ,number($active_customers+$new_customers)
			 ,percentage($active_customers,$total_customers)

			 ,$new_customers_1m
			 ,percentage($new_customers_1m,$active_customers+$new_customers));
 $smarty->assign('overview_text',$overview_text);
//$smarty->assign('plot_tipo',$_SESSION['state']['customers']['plot']);




// $home_country='United Kingdom';
// $home_informal_name=_('the UK');


// $sql="select sum(total_net+total_net_nd) as total_net from customer    ";
// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//   $total_net=$row['total_net'];
//  }
// $sql="select sum(total_net+total_net_nd) as total_net from customer  left join contact on (contact_id=contact.id) left join address on (main_address=address.id) where country!='$home_country'";
// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//   $export_total_net=$row['total_net'];
//  }

// //print "$total_net $export_total_net";


// $total_net_80p=.8*$total_net;

// $sql="select (total_net+total_net_nd) as total_net from customer order by (total_net+total_net_nd) desc";
// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());

// $top_customers=1;$_total_net=0;

// while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//   $_total_net+=$row['total_net'];
//   if($_total_net>$total_net_80p){
//     break;
//   }
//   $top_customers++;

//  }

// $overview_text=translate("%1\$s customers (%2\$s%\) are responsable for 80%% of the sales.",$top_customers,percentage($top_customers,$total_customers));
// $smarty->assign('top_text',$overview_text);



// $export_customers=0;
// $sql="select count(*) as export_customers from customer left join contact on (contact_id=contact.id) left join address on (main_address=address.id) where   contact_id>0 and (num_invoices+num_invoices_nd)>0 and country!='$home_country'";
// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// $new_customers=0;
// if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//   $export_customers=$row['export_customers'];
//  }
// $domestic_customers=$total_customers-$export_customers;

// $percentage_domestic=percentage($domestic_customers,$total_customers);
// $countries=0;
// $sql="select count(*) as countries from customer left join contact on (contact_id=contact.id) left join address on (main_address=address.id) where country!='$home_country' group by country";
// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// $countries=mysql_num_rows($result);

// $continents=0;
// 		      $sql="select country,continent from customer left join contact on (contact_id=contact.id) left join address on (main_address=address.id) left join list_country on (list_country.name=country) where country!='United Kingdom' group by continent";

// $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
// $continents=mysql_num_rows($result);

// $export_text=translate("%1\$s are based in $home_informal_name, the other %2\$s customers (%3\$s%\ of sales)  are distributed over %4\$s countries and %5\$s continents.",$percentage_domestic,$export_customers,percentage($export_total_net,$total_net),$countries,$continents);
 $smarty->assign('view',$_SESSION['state']['customers']['stats_view']);




$smarty->display('customers_stats.tpl');
?>
