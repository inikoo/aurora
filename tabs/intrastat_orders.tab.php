<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 12:06:06 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'class.Country.php';

$tab     = 'intrastat_orders';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array('label' => _('Customer')),
    'number'   => array('label' => _('Number')),

);

$_data  = preg_split('/\|/', $state['extra']);
$__data = preg_split('/\_/', $_data[1]);


$parameters = array(
    'parent'        => $state['parent'],
    'parent_key'    => $state['parent_key'],
    'country_code'       => $__data[0],
    'tariff_code'   => $__data[1],
    'parent_period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
    'parent_from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
    'parent_to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : ''),
    'invoices_no_vat'     => $_SESSION['table_state']['intrastat']['invoices_no_vat'],
    'invoices_vat'     => $_SESSION['table_state']['intrastat']['invoices_vat'],
    'invoices_null'     =>$_SESSION['table_state']['intrastat']['invoices_null'],


);

$country=new Country('2alpha',$__data[0]);

$smarty->assign('country',$country);

$smarty->assign('commodity_code',$__data[1]);

$smarty->assign('link_products', 'report/intrastat/products/'.$__data[0].'/'.$__data[1]);


$_SESSION['table_state']['intrastat_orders']['invoices_no_vat']=$parameters['invoices_no_vat'];
$_SESSION['table_state']['intrastat_orders']['invoices_vat']=$parameters['invoices_vat'];
$_SESSION['table_state']['intrastat_orders']['invoices_null']=$parameters['invoices_null'];
$_SESSION['table_state']['intrastat_orders']['tariff_code']=$parameters['tariff_code'];
$_SESSION['table_state']['intrastat_orders']['parent_period']=$parameters['parent_period'];
$_SESSION['table_state']['intrastat_orders']['parent_from']=$parameters['parent_from'];
$_SESSION['table_state']['intrastat_orders']['parent_to']=$parameters['parent_to'];
$_SESSION['table_state']['intrastat_orders']['parent']=$parameters['parent'];
$_SESSION['table_state']['intrastat_orders']['parent_key']=$parameters['parent_key'];

$smarty->assign('table_state',$_SESSION['table_state']['intrastat_orders']);

$smarty->assign('table_top_template', 'control.intrastat_orders.tpl');


include('utils/get_table_html.php');


?>
