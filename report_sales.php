<?php

include_once 'common.php';
include_once 'report_functions.php';
include_once 'class.Store.php';

$report_name='report_sales';
$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
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

);

$root_title=_('Sales Report');
$title=_('Sales Report');

include_once 'reports_list.php';

$stores=join(',',$user->stores);
$smarty->assign('parent','reports');


if (isset($_REQUEST['tipo']) and preg_match('/y|m|d|q|w|f|all/',$_REQUEST['tipo'])) {
	$tipo=$_REQUEST['tipo'];
	$_SESSION['state']['reports_sales']['tipo']=$tipo;
}else {
	$tipo=$_SESSION['state']['reports_sales']['tipo'];
}


$root_title=_('Sales Report');
if (isset($_REQUEST['store_key']) and is_numeric($_REQUEST['store_key'])) {
	$store_keys=$_REQUEST['store_key'];
	$_SESSION['state']['report_sales']['store_keys']=$store_keys;
}else {
	if (is_numeric($_SESSION['state']['report_sales']['store_keys']))
		$store_keys=$_SESSION['state']['report_sales']['store_keys'];
	else {
		header('Location: report_sales_main.php?fe');
		exit();
	}

}

$report_name='report_sales';

include_once 'report_dates.php';

$_SESSION['state']['report_sales']['to']=$to;
$_SESSION['state']['report_sales']['from']=$from;
$_SESSION['state']['report_sales']['period']=$period;
$js_files[]='report_sales.js.php?to='.$mysql_to.'&from='.$mysql_from."&store_key=".$store_keys;
$js_files[]='reports_calendar.js.php';
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$store=new Store($store_keys);

$currency=$store->data['Store Currency Code'];
$home_name=$store->data['Store Home Country Name'];
$home_short_name=$store->data['Store Home Country Short Name'];


$interval_data=sales_in_interval($from,$to,$store_keys);
//print_r($interval_data);
$smarty->assign('novalue_invoices',$interval_data['errors']['novalue_invoices']);
$smarty->assign('f_novalue_invoices',number($interval_data['errors']['novalue_invoices']));

$smarty->assign('error_taxable',$interval_data['errors']['taxable']);
$smarty->assign('error_notaxable',$interval_data['errors']['notaxable']);
foreach ($interval_data['taxable'] as $key=>$value) {
	$interval_data['taxable'][$key]['invoices']=number($value['invoices']);
	$interval_data['taxable'][$key]['sales']=money($value['sales'],$currency);
	$interval_data['taxable'][$key]['tax']=money($value['tax'],$currency);
}
foreach ($interval_data['notaxable'] as $key=>$value) {
	$interval_data['notaxable'][$key]['invoices']=number($value['invoices']);
	$interval_data['notaxable'][$key]['sales']=money($value['sales'],$currency);
	$interval_data['notaxable'][$key]['tax']=money($value['tax'],$currency);
}
$smarty->assign('taxable',$interval_data['taxable']);
$smarty->assign('notaxable',$interval_data['notaxable']);
$smarty->assign('balance',$interval_data['balance']);




$formated_day_interval=get_time_interval(strtotime($from),(strtotime($to)))+1;
$day_interval=(strtotime($to)-strtotime($from))/3600/24;
if ($day_interval>=7) {
	$_from=$from;
	$_to=$to;
	preg_match('/^\d{4}/',$from,$match1);
	$last_year=$match1[0]-1;
	$_from=preg_replace('/^\d{4}/',$last_year,$_from);
	preg_match('/^\d{4}/',$to,$match2);
	$last_year=$match2[0]-1;
	$_to=preg_replace('/^\d{4}/',$last_year,$_to);


	$interval_data_last_year=sales_in_interval($_from,$_to,$store_keys);

	$invoices=$interval_data['invoices']['total_invoices'];
	$invoices_ly=$interval_data_last_year['invoices']['total_invoices'];
	$net=$interval_data['sales']['total_net'];
	$net_ly=$interval_data_last_year['sales']['total_net'];
	$net=$interval_data['sales']['total_net'];
	$net_ly=$interval_data_last_year['sales']['total_net'];
	$orders_received=$interval_data['orders']['orders_total'];
	$orders_received_ly=$interval_data_last_year['orders']['orders_total'];

	$diff_sales=$net-$net_ly;
	$diff_sales_change=($diff_sales>0?'+':'');
	$smarty->assign('diff_sales_change',$diff_sales_change);
	$smarty->assign('diff_sales',money($diff_sales,$currency));
	$smarty->assign('diff_sales_per',percentage($diff_sales,$net_ly,2));

	$diff_invoices=$invoices-$invoices_ly;
	$diff_invoices_change=($diff_invoices>0?'+':'');
	$smarty->assign('diff_invoices_change',$diff_invoices_change);
	$smarty->assign('diff_invoices',$diff_invoices_change.number($diff_invoices));
	$smarty->assign('diff_invoices_per',percentage($diff_invoices,$invoices_ly,2));

	$smarty->assign('orders_state',$interval_data['orders_state']);


	$diff_invoices_home=$interval_data['invoices']['invoices_home']-$interval_data_last_year['invoices']['invoices_home'];
	$diff_invoices_home_change=($diff_invoices_home>0?'+':'');
	$smarty->assign('diff_invoices_home_change',$diff_invoices_home_change);
	$smarty->assign('diff_invoices_home',$diff_invoices_home_change.number($diff_invoices_home));
	$smarty->assign('diff_invoices_home_per',percentage($diff_invoices_home,$interval_data_last_year['invoices']['invoices_home'],2));
	$diff_invoices_nohome=$interval_data['invoices']['invoices_nohome']-$interval_data_last_year['invoices']['invoices_nohome'];
	$diff_invoices_nohome_change=($diff_invoices_nohome>0?'+':'');
	$smarty->assign('diff_invoices_nohome_change',$diff_invoices_nohome_change);
	$smarty->assign('diff_invoices_nohome',$diff_invoices_nohome_change.number($diff_invoices_nohome));
	$smarty->assign('diff_invoices_nohome_per',percentage($diff_invoices_nohome,$interval_data_last_year['invoices']['invoices_nohome'],2));
	$diff_invoices_partners=$interval_data['invoices']['invoices_p']-$interval_data_last_year['invoices']['invoices_p'];
	$diff_invoices_partners_change=($diff_invoices_partners>0?'+':'');
	$smarty->assign('diff_invoices_partners_change',$diff_invoices_partners_change);
	$smarty->assign('diff_invoices_partners',$diff_invoices_partners_change.number($diff_invoices_partners));
	$smarty->assign('diff_invoices_partners_per',percentage($diff_invoices_partners,$interval_data_last_year['invoices']['invoices_p'],2));


	$diff_sales_home=$interval_data['sales']['net_home']-$interval_data_last_year['sales']['net_home'];
	$diff_sales_home_change=($diff_sales_home>0?'+':'');
	$smarty->assign('diff_sales_home_change',$diff_sales_home_change);
	$smarty->assign('diff_sales_home',money($diff_sales_home,$currency));
	$smarty->assign('diff_sales_home_per',percentage($diff_sales_home,$interval_data_last_year['sales']['net_home'],2));
	$diff_sales_nohome=$interval_data['sales']['net_nohome']-$interval_data_last_year['sales']['net_nohome'];
	$diff_sales_nohome_change=($diff_sales_nohome>0?'+':'');
	$smarty->assign('diff_sales_nohome_change',$diff_sales_nohome_change);
	$smarty->assign('diff_sales_nohome',money($diff_sales_nohome,$currency));
	$smarty->assign('diff_sales_nohome_per',percentage($diff_sales_nohome,$interval_data_last_year['sales']['net_nohome'],2));
	$diff_sales_partners=$interval_data['sales']['net_p']-$interval_data_last_year['sales']['net_p'];
	$diff_sales_partners_change=($diff_sales_partners>0?'+':'');
	$smarty->assign('diff_sales_partners_change',$diff_sales_partners_change);
	$smarty->assign('diff_sales_partners',money($diff_sales_partners,$currency));
	$smarty->assign('diff_sales_partners_per',percentage($diff_sales_partners,$interval_data_last_year['sales']['net_p'],2));



	$smarty->assign('diff_orders_received',$orders_received-$orders_received_ly);
	$smarty->assign('per_diff_orders_received',percentage($orders_received-$orders_received_ly,$orders_received_ly,2));

	$diff_sales=0;
	if ($net_ly>$net) {
		$diff_sales=-1;
		$smarty->assign('text_diff_sales',_('a decrese of').' '.percentage(($net_ly-$net),$net_ly));


	}elseif ($net_ly < $net) {
		$diff_sales+=1;
		$smarty->assign('text_diff_sales',_('an increse of').' '.percentage(($net-$net_ly),$net_ly));

	}else {
		$smarty->assign('text_diff_sales',_('no change'));

	}



	if ($invoices_ly>$invoices) {
		if ($diff_sales==1)
			$link=_('but');
		else
			$link=_('and');
		$smarty->assign('text_diff_invoices_link',$link.' ');
		$smarty->assign('text_diff_invoices',($invoices_ly-$invoices).' '._(' less'));

	}elseif ($invoices_ly<$invoices) {

		if ($diff_sales==-1)
			$link=_('but');
		else
			$link=_('and');
		$smarty->assign('text_diff_invoices_link',$link.' ');

		$smarty->assign('text_diff_invoices',$invoices-$invoices_ly.' '._(' more '));

	}else {
		$smarty->assign('text_diff_invoices','');
$smarty->assign('text_diff_invoices_link','');

	}
	
	
}
//end last year


if (isset($interval_data['exports']['top3'][0])) {
	$smarty->assign('export_country1',$interval_data['exports']['top3'][0]['country']);
	$smarty->assign('per_export_country1',percentage($interval_data['exports']['top3'][0]['net'],$interval_data['sales']['net_nohome']));
	if (isset($interval_data['exports']['top3'][1])) {
		$smarty->assign('export_country2',$interval_data['exports']['top3'][1]['country']);
		$smarty->assign('per_export_country2',percentage($interval_data['exports']['top3'][1]['net'],$interval_data['sales']['net_nohome']));
		if (isset($interval_data['exports']['top3'][2])) {
			$smarty->assign('export_country3',$interval_data['exports']['top3'][2]['country']);
			$smarty->assign('per_export_country3',percentage($interval_data['exports']['top3'][2]['net'],$interval_data['sales']['net_nohome']));
		}
	}
}
$smarty->assign('export_countries',$interval_data['exports']['countries']);

//   print_r($interval_data['exports']['top3']);
$smarty->assign('store',$store);

$smarty->assign('home',$home_name);
$smarty->assign('_home',$home_short_name);

$smarty->assign('extended_home',$myconf['extended_home']);
$smarty->assign('extended_home_nohome',$myconf['s_extended_home_nohome']);

$smarty->assign('region',$myconf['region']);

$smarty->assign('region',$myconf['region']);
$smarty->assign('region2',$myconf['continent']);
$smarty->assign('outside',$myconf['outside']);
$smarty->assign('org',$myconf['s_org']);


$smarty->assign('days',$day_interval.' '.ngettext('day','days',$day_interval));



//   $smarty->assign('total_net_sales', money($interval_data['sales']['total_net']));
// $smarty->assign('total_invoices', number($interval_data['invoices']['total_invoices']));

$smarty->assign('per_partner_sales', percentage($interval_data['sales']['net_p'],$interval_data['sales']['total_net']));
$smarty->assign('per_export', percentage($interval_data['sales']['total_net_nohome'],$interval_data['sales']['total_net']));
$smarty->assign('per_export_nop', percentage($interval_data['sales']['net_nohome'],$interval_data['sales']['total_net']));

foreach ($interval_data['refunds'] as $key=>$value) {
	$smarty->assign($key, money($value,$currency));
}

foreach ($interval_data['sales'] as $key=>$value) {
	$smarty->assign($key, money($value,$currency));
}
foreach ($interval_data['invoices'] as $key=>$value) {
	$smarty->assign($key, number($value));
}

$smarty->assign('dispatch_days', number($interval_data['other_data']['dispatch_days'],2));
$smarty->assign('dispatch_days_home', number($interval_data['other_data']['dispatch_days_home'],2));
$smarty->assign('dispatch_days_nohome', number($interval_data['other_data']['dispatch_days_nohome'],2));

$total_orders=$interval_data['orders']['orders_total'];
foreach ($interval_data['orders'] as $key=>$value) {
	if (preg_match('/_net$/',$key)) {
		$smarty->assign($key, money($value,$currency));
	}else {
		$smarty->assign($key, number($value));
		$smarty->assign('per_'.$key, percentage($value,$total_orders));
	}

}
$smarty->assign('dn',$interval_data['dn_data']);
$smarty->assign('dn_total',number($interval_data['dn']['dn_total']));
$smarty->assign('dn_total_weight',number($interval_data['dn']['dn_total_weight']).' Kg'  );

$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));
$smarty->assign('from',$from);
$smarty->assign('to',$to);


$smarty->assign('week',date('W'));
;
$smarty->assign('currency',$myconf['currency_symbol']);
$smarty->assign('invoices_data',$interval_data['invoices']);


$smarty->display('report_sales.tpl');

//  }else{

// $smarty->assign('parent','reports');
// $smarty->assign('title', _('Sales Reports'));
// $smarty->assign('css_files',$css_files);
// $smarty->assign('js_files',$js_files);
// $smarty->assign('year',date('Y'));
// $smarty->assign('month',date('m'));
// $smarty->assign('month_name',date('M'));

// $smarty->assign('week',date('W'));
// $smarty->assign('from',date('Y-m-d'));
// $smarty->assign('to',date('Y-m-d'));


// $smarty->display('report_sales.tpl');
//  }
?>
