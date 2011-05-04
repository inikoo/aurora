<?php
/*
 File: customers.php 

 UI customers page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
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
$smarty->assign('store_id',$store->id);

$_SESSION['state']['customers']['store']=$store_id;
$modify=$user->can_edit('customers');
$general_options_list=array();

$general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));



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
include_once('Theme.php');
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
		'js/common.js',
		'js/table_common.js',
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
