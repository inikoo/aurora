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

$show_details=$_SESSION['state']['customers']['details'];
$smarty->assign('details',$show_details);

$general_options_list=array();

if($modify){
  $general_options_list[]=array('tipo'=>'url','url'=>'edit_customers.php','label'=>_('Edit Customers'));
   $general_options_list[]=array('tipo'=>'js','id'=>'new_customer','label'=>_('Add Customer'));
}

$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));
   $general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php','label'=>_('Advanced Search'));
   $general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php','label'=>_('Customers Lists'));

$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');



 

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
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
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'js/edit_common.js',
                'js/csv_common.js',
		'customers.js.php'
		);


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('table_type',$_SESSION['state']['customers']['table']['type']);

$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['table']['f_value']);

$filter_menu=array(
		   'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
		   'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
		   'country'=>array('db_key'=>_('country'),'menu_label'=>_('Customer Country'),'label'=>_('Country')),

		   'min'=>array('db_key'=>_('min'),'menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
		   'max'=>array('db_key'=>_('min'),'menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
		   'last_more'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
		   'last_less'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
		   'maxvalue'=>array('db_key'=>_('maxvalue'),'menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
		   'minvalue'=>array('db_key'=>_('minvalue'),'menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
		   );
		   

$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

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



$plot_tipo=$_SESSION['state']['customers']['plot'];
$plot_data=$_SESSION['state']['customers']['plot_data'][$plot_tipo];
$plot_period=$plot_data['period'];
$plot_category=$plot_data['category'];




$plot_args='tipo='.$plot_tipo.'&category='.$plot_category.'&period='.$plot_period.'&keys='.$store_id.'&currency='.$store->data['Store Currency Code'];



if($plot_tipo=='pie'){
  $pie_forecast=$plot_data['forecast'];
  
  if($plot_data['date']=='today'){
    $plot_date=date('Y-m-d');
    $smarty->assign('plot_date',$plot_date);
    $smarty->assign('plot_formated_date',strftime("%b %Y",strtotime($plot_date)));

  }

  $plot_args=sprintf('tipo=children_share&item=store&category=%s&period=%s&keys=%d&date=%s&forecast=%s'
		     ,$plot_category
		     ,$plot_period
		     ,$store_id
		     ,$plot_date
		     ,$plot_data['forecast']);
}

$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_args',$plot_args);
$smarty->assign('plot_page',$plot_data['page']);
$smarty->assign('plot_period',$plot_period);
$smarty->assign('plot_category',$plot_period);
$smarty->assign('plot_data',$_SESSION['state']['customers']['plot_data']);


if($plot_tipo=='pie'){
  if($plot_period=='m')
    $plot_formated_period='Month';
  elseif($plot_period=='y')
    $plot_formated_period='Year';
    elseif($plot_period=='q')
      $plot_formated_period='Quarter';
    elseif($plot_period=='w')
      $plot_formated_period='Week';
  }else{
    if($plot_period=='m')
      $plot_formated_period='Monthly';
    elseif($plot_period=='y')
      $plot_formated_period='Yearly';
    elseif($plot_period=='q')
      $plot_formated_period='Quarterly';
    elseif($plot_period=='w')
      $plot_formated_period='Weekly';
  }
  
if($plot_category=='growth')
  $plot_formated_category=_('Growth');
else
  $plot_formated_category=_('Total');


$smarty->assign('plot_formated_category',$plot_formated_category);
$smarty->assign('plot_formated_period',$plot_formated_period);



$plot_period_menu=array(

		     array("period"=>'m','label'=>_('Montly'))
		     ,array("period"=>'q','label'=>_('Quarterly'))
		     ,array("period"=>'y','label'=>_('Yearly'))
		     );
$smarty->assign('plot_period_menu',$plot_period_menu);

$plot_category_menu=array(
		     array("category"=>'total','label'=>_('Total'))
		     ,array("category"=>'growth','label'=>_('Growth'))
		     );
$smarty->assign('plot_category_menu',$plot_category_menu);



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
 $smarty->assign('view',$_SESSION['state']['customers']['view']);


// $smarty->assign('export_text',$export_text);
// $smarty->assign('table_info',$total_customers.'  '.ngettext('identified customer','identified customers',$total_customers));



 $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['id']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['name']),
                                                             'location'=>array('label'=>_('Location'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['location']),
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                            'orders'=>array(
                                        'title'=>_('Orders'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'last_orders'=>array('label'=>_('Last Orders'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['last_orders']),
                                                       'orders'=>array('label'=>_('Orders'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['orders']),
                                                       'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['status'])

                                                   )
                                               )
                                    )/*,
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )*/
                        );
$smarty->assign('export_csv_table_cols',2);

                        
$smarty->assign('csv_export_options',$csv_export_options);
$smarty->display('customers.tpl');
?>
