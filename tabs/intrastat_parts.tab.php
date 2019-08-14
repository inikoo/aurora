<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 12:09:24 GMT, Sheffield UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'intrastat_products';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat_products';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Product name')
    ),

);

$_data  = preg_split('/\|/', $state['extra']);
$__data = preg_split('/\_/', $_data[1]);


$parameters = array(
    'parent'          => $state['parent'],
    'parent_key'      => $state['parent_key'],
    'country_code'    => $__data[0],
    'tariff_code'     => $__data[1],
    'parent_period'   => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
    'parent_from'     => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
    'parent_to'       => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : ''),
    'invoices_no_vat' => $_SESSION['table_state']['intrastat']['invoices_no_vat'],
    'invoices_vat'    => $_SESSION['table_state']['intrastat']['invoices_vat'],
    'invoices_null'   => $_SESSION['table_state']['intrastat']['invoices_null'],


);

include_once 'class.Country.php';
$country = new Country('2alpha', $__data[0]);

$smarty->assign('country', $country);

$smarty->assign('commodity_code', $__data[1]);


$smarty->assign('link_orders', 'report/intrastat/orders/'.$__data[0].'/'.$__data[1]);


$_SESSION['table_state']['intrastat_products']['invoices_no_vat'] = $parameters['invoices_no_vat'];
$_SESSION['table_state']['intrastat_products']['invoices_vat']    = $parameters['invoices_vat'];
$_SESSION['table_state']['intrastat_products']['invoices_null']   = $parameters['invoices_null'];
$_SESSION['table_state']['intrastat_products']['tariff_code']     = $parameters['tariff_code'];
$_SESSION['table_state']['intrastat_products']['parent_period']   = $parameters['parent_period'];
$_SESSION['table_state']['intrastat_products']['parent_from']     = $parameters['parent_from'];
$_SESSION['table_state']['intrastat_products']['parent_to']       = $parameters['parent_to'];
$_SESSION['table_state']['intrastat_products']['parent']          = $parameters['parent'];
$_SESSION['table_state']['intrastat_products']['parent_key']      = $parameters['parent_key'];

$smarty->assign('table_state', $_SESSION['table_state']['intrastat_products']);

$smarty->assign('table_top_template', 'control.intrastat_products.tpl');


include 'utils/get_table_html.php';


?>
