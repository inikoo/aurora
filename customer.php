<?php
/*
 File: customer.php

 UI customer page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'common.php';
include_once 'class.Customer.php';
include_once 'class.Store.php';
include_once 'duplicate_warning.php';

if (!$user->can_view('customers')) {
	header('Location: index.php');
	exit;
}

$modify=$user->can_edit('contacts');
$smarty->assign('modify',$modify);

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {

	$customer_id=$_REQUEST['id'];
} else {
	$customer_id=$_SESSION['state']['customer']['id'];
}


$customer=new customer($customer_id);
if (!$customer->id) {
	header('Location: customer_deleted.php?id='.$customer_id);
	exit;
}

//print $customer->get('Customer Type');
$smarty->assign('customer_type',$customer->get('Customer Type'));
//print_r($customer);

$smarty->assign('other_email_count',count($customer->get_other_emails_data()));
if (!in_array($customer->data['Customer Store Key'],$user->stores)) {
	header('Location: customers.php?msg=forbidden');
	exit;
}



$_SESSION['state']['customer']['id']=$customer_id;
$_SESSION['state']['customers']['store']=$customer->data['Customer Store Key'];


if (isset($_REQUEST['view']) and preg_match('/^(history|products|orders|details)$/',$_REQUEST['view']) ) {

	$view=$_REQUEST['view'];
} else {
	$view=$_SESSION['state']['customer']['view'];
}
if (!$customer->data['Customer Orders'] and ($view=='products' or $view=='orders')) {
	//   $view='history';
}
//print $view;
$smarty->assign('view',$view);
$_SESSION['state']['customer']['view']=$view;

$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',

	'css/text_editor.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/customer.css',
	'css/upload.css',
	'css/edit.css',

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
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
//	$yui_path.'uploader/uploader-min.js',

	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	'js/php.default.min.js',
	'js/table_common.js',
	'js/search.js',
	'js/edit_common.js',
	'edit_address.js.php',
	'edit_delivery_address_common.js.php',
//	'upload_common.js.php',
	'customer.js.php?customer_key='.$customer->id.'&customer_type='.$customer->get('Customer Type'),
	'js/validate_telecom.js',
	'js/aes.js',
	'js/sha256.js',
	//printf('edit_company.js.php?id=%d&scope=Customer&scope_key=%d',$company->id,$customer->id),
	//printf('edit_contact.js.php?id=%d&scope=Customer&scope_key=%d',$contact->id,$customer->id),
	'address_data.js.php?tipo=customer&id='.$customer->id,
	'edit_contact_from_parent.js.php',
	'edit_contact_telecom.js.php',
	'edit_contact_name.js.php',
	'edit_contact_email.js.php',
	'js/notes.js',
);



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$customer->load('contacts');
$smarty->assign('customer',$customer);

$smarty->assign('all_warning',get_all_warnings($customer));


list($customer_type, $login_stat)=$customer->is_user_customer($customer_id);
$_login_stat=array('UserHandle'=>false);

if ($customer_type) {

	foreach ($login_stat as $key=>$value) {

		if ($key=='User Last Login' || $key=='User Last Failed Login') {
			$value=strftime("%a %e %b %y %H:%M", strtotime($value." +00:00"));
		}

		$_login_stat[preg_replace('/\s/','',$key)]=$value;
	}
}

//$smarty->assign('login_stat',$_login_stat);
//$smarty->assign('customer_type',$customer_type);

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');

$store=new Store($customer->data['Customer Store Key']);
$smarty->assign('store', $store);

if (isset($_REQUEST['p'])) {
	$smarty->assign('parent_list',$_REQUEST['p']);
	if ($_REQUEST['p']=='cs') {

		$order=$_SESSION['state']['customers']['customers']['order'];
		$order_label=$order;
		
		
		if ($order=='name')
		$order='`Customer File As`';
	elseif ($order=='id')
		$order='`Customer Key`';
	elseif ($order=='location')
		$order='`Customer Main Location`';
	elseif ($order=='orders')
		$order='`Customer Orders`';
	elseif ($order=='email')
		$order='`Customer Main Plain Email`';
	elseif ($order=='telephone')
		$order='`Customer Main Plain Telephone`';
	elseif ($order=='last_order')
		$order='`Customer Last Order Date`';
	elseif ($order=='contact_name')
		$order='`Customer Main Contact Name`';
	elseif ($order=='address')
		$order='`Customer Main Plain Address`';
	elseif ($order=='town')
		$order='`Customer Main Town`';
	elseif ($order=='postcode')
		$order='`Customer Main Postal Code`';
	elseif ($order=='region')
		$order='`Customer Main Country First Division`';
	elseif ($order=='country')
		$order='`Customer Main Country`';
	//  elseif($order=='ship_address')
	//  $order='`customer main ship to header`';
	elseif ($order=='ship_town')
		$order='`Customer Main Delivery Address Town`';
	elseif ($order=='ship_postcode')
		$order='`Customer Main Delivery Address Postal Code`';
	elseif ($order=='ship_region')
		$order='`Customer Main Delivery Address Country Region`';
	elseif ($order=='ship_country')
		$order='`Customer Main Delivery Address Country`';
	elseif ($order=='net_balance')
		$order='`Customer Net Balance`';
	elseif ($order=='balance')
		$order='`Customer Outstanding Net Balance`';
	elseif ($order=='total_profit')
		$order='`Customer Profit`';
	elseif ($order=='total_payments')
		$order='`Customer Net Payments`';
	elseif ($order=='top_profits')
		$order='`Customer Profits Top Percentage`';
	elseif ($order=='top_balance')
		$order='`Customer Balance Top Percentage`';
	elseif ($order=='top_orders')
		$order='``Customer Orders Top Percentage`';
	elseif ($order=='top_invoices')
		$order='``Customer Invoices Top Percentage`';
	elseif ($order=='total_refunds')
		$order='`Customer Total Refunds`';
	elseif ($order=='contact_since')
		$order='`Customer First Contacted Date`';
	elseif ($order=='activity')
		$order='`Customer Type by Activity`';
	elseif ($order=='logins')
		$order='`Customer Number Web Logins`';
	elseif ($order=='failed_logins')
		$order='`Customer Number Web Failed Logins`';
	elseif ($order=='requests')
		$order='`Customer Number Web Requests`';
	else
		$order='`Customer File As`';
		
		
		$wheref='';

$conf=$_SESSION['state']['customers']['customers'];

	$f_field=$conf['f_field'];

	
		$f_value=$conf['f_value'];

	if (($f_field=='customer name'     )  and $f_value!='') {
		$wheref=sprintf('  and  `Customer Name`  REGEXP "[[:<:]]%s" ',addslashes($f_value));
		
		
	}
	elseif (($f_field=='postcode'     )  and $f_value!='') {
		$wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
	}
	elseif ($f_field=='id'  )
		$wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
	elseif ($f_field=='last_more' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
	elseif ($f_field=='last_less' and is_numeric($f_value) )
		$wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
	elseif ($f_field=='max' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`<=".$f_value."    ";
	elseif ($f_field=='min' and is_numeric($f_value) )
		$wheref.=" and  `Customer Orders`>=".$f_value."    ";
	elseif ($f_field=='maxvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
	elseif ($f_field=='minvalue' and is_numeric($f_value) )
		$wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
	elseif ($f_field=='country' and  $f_value!='') {
		if ($f_value=='UNK') {
			$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
			$find_data=' '._('a unknown country');
		} else {

			$f_value=Address::parse_country($f_value);
			if ($f_value!='UNK') {
				$wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
				$country=new Country('code',$f_value);
				$find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
			}

		}
	}



		$_order=preg_replace('/`/','',$order);
		$sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`   where `Customer Key`!=%d and `Customer Store Key`=%d  and %s <= %s $wheref  order by %s desc  limit 1",
		$customer->id,
		$store->id,$order,prepare_mysql($customer->get($_order)),$order);
		

		$result=mysql_query($sql);
		if (!$prev=mysql_fetch_array($result, MYSQL_ASSOC))
			$prev=array('id'=>0,'name'=>'','link'=>'');
		mysql_free_result($result);

		$smarty->assign('prev',$prev);
		$sql=sprintf("select `Customer Key` as id , `Customer Name` as name from `Customer Dimension`     where `Customer Key`!=%d and  `Customer Store Key`=%d and  %s>=%s  $wheref order by %s   ",
		$customer->id,
		$store->id,$order,prepare_mysql($customer->get($_order)),$order);
//print $sql;
		$result=mysql_query($sql);
		if (!$next=mysql_fetch_array($result, MYSQL_ASSOC))
			$next=array('id'=>0,'name'=>'','link'=>'');
			
	//	print_r($next);	
			
		mysql_free_result($result);
		$smarty->assign('parent_info',"p=cs&");

		$smarty->assign('prev',$prev);
		$smarty->assign('next',$next);

		$smarty->assign('parent_url','customers.php?store='.$store->id);
		$parent_title=$store->data['Store Code'].' '._('Customers').' ('.$order_label.')';
		$smarty->assign('parent_title',$parent_title);

	}



}


$smarty->assign('store_key',$customer->data['Customer Store Key']);


$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));
$new_customer=false;
if ($modify) {
	if (isset($_REQUEST['r']) and $_REQUEST['r']=='nc') {
		$new_customer=true;
	}


	$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'new_customer.php','label'=>_('Add Other Customer'));
	$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_customer.php?id='.$customer->id,'label'=>_('Edit Customer'));

}
$general_options_list=array();
//$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('new_customer',$new_customer);



$smarty->assign('number_orders',$customer->get('Customer Orders'));
$smarty->assign('parent','customers');
$smarty->assign('title',_('Customer').': '.$customer->get('customer name'));
$customer_home=_("Customers List");

$total_orders=$customer->get('Customer Orders');
$smarty->assign('orders',number($total_orders)  );
$total_net=$customer->get('Customer Total Net Payments');
$smarty->assign('total_net',money($total_net));
$total_invoices=$customer->get('Customer Orders Invoiced');
$smarty->assign('invoices',number($total_invoices)  );
if ($total_invoices>0)
	$smarty->assign('total_net_average',money($total_net/$total_invoices));

$order_interval=$customer->get('Customer Order Interval');

if ($order_interval>10) {
	$order_interval=round($order_interval/7);
	if ( $order_interval==1)
		$order_interval=_('week');
	else
		$order_interval=$order_interval.' '._('weeks');

} else if ($order_interval=='')
		$order_interval='';
	else
		$order_interval=round($order_interval).' '._('days');
	$smarty->assign('orders_interval',$order_interval);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['customer']['history']['f_field'];
$filter_value=$_SESSION['state']['customer']['history']['f_value'];

$smarty->assign('filter_value0',$filter_value);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code like'),'label'=>_('Code')),
);
$tipo_filter=$_SESSION['state']['customer']['assets']['f_field'];
$filter_value=$_SESSION['state']['customer']['assets']['f_value'];

$smarty->assign('filter_value1',$filter_value);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$filter_menu=array(
	'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Invoice Number'),
	'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
	'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Min Value ('.$corporate_currency_symbol.')'),
	'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$corporate_currency_symbol.'n</i>','label'=>'Max Value ('.$corporate_currency_symbol.')'),
	'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
);
$tipo_filter=$_SESSION['state']['customer']['orders']['f_field'];
$filter_value=$_SESSION['state']['customer']['orders']['f_value'];

$smarty->assign('filter_value2',$filter_value);
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$filter_menu=array(
	'ip'=>array('db_key'=>'ip','menu_label'=>'IP','label'=>'IP'),
);
$tipo_filter=$_SESSION['state']['staff_user']['login_history']['f_field'];
$filter_value=$_SESSION['state']['staff_user']['login_history']['f_value'];

$smarty->assign('filter_value3',$filter_value);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);




$elements_number=array('Notes'=>0,'Orders'=>0,'Changes'=>0,'Attachments'=>0,'Emails'=>0,'WebLog'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Customer History Bridge` where `Customer Key`=%d group by `Type`",$customer->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['customer']['history']['elements']);


$gold_reward=0;
//print_r($customer->data);
if ($customer->data['Customer Last Order Date']    ) {
	$last_order_date=$customer->data['Customer Last Order Date'];
	$last_order_date='2011-01-15';
	$last_order_time=strtotime( $last_order_date);
	// print $last_order_time;
	if ( (date('U')-$last_order_time)<2592000 )
		$gold_reward='Gold Reward Member';

}
$correlation_msg='';
$msg='';
$sql=sprintf("select * from `Customer Correlation` where `Customer A Key`=%d and `Correlation`>200",$customer->id);
$res2=mysql_query($sql);
while ($row2=mysql_fetch_assoc($res2)) {
	$msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>",$customer->id,$row2['Customer B Key'],$myconf['customer_id_prefix'].sprintf("%05d",$row2['Customer B Key']));
}
$sql=sprintf("select * from `Customer Correlation` where `Customer B Key`=%d and `Correlation`>200",$customer->id);
$res2=mysql_query($sql);
while ($row2=mysql_fetch_assoc($res2)) {
	$msg.=','.sprintf("<a style='color:SteelBlue' href='customer_split_view.php?id_a=%d&id_b=%d'>%s</a>",$customer->id,$row2['Customer A Key'],$myconf['customer_id_prefix'].sprintf("%05d",$row2['Customer A Key']));
}

$msg=preg_replace('/^,/','',$msg);
if ($msg!='') {
	$correlation_msg='<p>'._('Potential duplicated').': '.$msg.'</p>';

}




//show case
$custom_field=array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field In Showcase`='Yes' and `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$show_case=array();
$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);
$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {

	foreach ($custom_field as $key=>$value) {
		$show_case[$value]=$row[$key];
	}
}



$custom_field=array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Customer'");
$res = mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}

$customer_custom_fields=array();
$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);
$res=mysql_query($sql);
if ($row=mysql_fetch_array($res)) {

	foreach ($custom_field as $key=>$value) {
		$customer_custom_fields[$value]=$row[$key];
	}
}

$smarty->assign('show_case',$show_case);
$smarty->assign('customer_custom_fields',$customer_custom_fields);
$smarty->assign('correlation_msg',$correlation_msg);
$smarty->assign('hq_country',$corporate_country_code);

$smarty->assign('gold_reward',$gold_reward);

$smarty->assign('options_box_width','550px');
$smarty->assign('id',$myconf['customer_id_prefix'].sprintf("%05d",$customer->id));

$smarty->assign('default_country_2alpha',$store->get('Store Home Country Code 2 Alpha'));
$smarty->assign('other_email_login_handle',$customer->get_other_email_login_handle());

$tipo_filter100='code';
$filter_menu100=array(
	'code'=>array('db_key'=>_('code'),'menu_label'=>_('Country Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>_('name'),'menu_label'=>_('Country Name'),'label'=>_('Name')),
	'wregion'=>array('db_key'=>_('wregion'),'menu_label'=>_('World Region Name'),'label'=>_('Region')),
);
$smarty->assign('filter_name100',$filter_menu100[$tipo_filter100]['label']);
$smarty->assign('filter_menu100',$filter_menu100);
$smarty->assign('filter100',$tipo_filter100);
$smarty->assign('filter_value100','');

$categories_data=$customer->get_category_data();
$number_categories_data=count($categories_data);
$smarty->assign('categories_data',$categories_data);
$smarty->assign('number_categories_data',$number_categories_data);

;
$smarty->assign('sticky_note',$customer->data['Customer Sticky Note']);

$smarty->display('customer.tpl');

?>
