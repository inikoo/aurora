<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14-08-2019 13:04:37 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'class.Country.php';

$tab     = 'intrastat_deliveries';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat_deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number'   => array('label' => _('Number')),
    'supplier' => array('label' => _('Supplier')),

);

$_data  = preg_split('/\|/', $state['extra']);
$__data = preg_split('/\_/', $_data[1]);


$parameters = array(
    'parent'        => $state['parent'],
    'parent_key'    => $state['parent_key'],
    'country_code'       => $__data[0],
    'tariff_code'   => $__data[1],
    'parent_period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
    'parent_from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
    'parent_to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : ''),
   // 'invoices_no_vat'     => $_SESSION['table_state']['intrastat']['invoices_no_vat'],
   // 'invoices_vat'     => $_SESSION['table_state']['intrastat']['invoices_vat'],
   // 'invoices_null'     =>$_SESSION['table_state']['intrastat']['invoices_null'],


);

$country=new Country('2alpha',$__data[0]);

$smarty->assign('country',$country);

$smarty->assign('commodity_code',$__data[1]);

$smarty->assign('link_parts', 'report/intrastat/parts/'.$__data[0].'/'.$__data[1]);


//$_SESSION['table_state']['intrastat_deliveries']['invoices_no_vat']=$parameters['invoices_no_vat'];
//$_SESSION['table_state']['intrastat_deliveries']['invoices_vat']=$parameters['invoices_vat'];
//$_SESSION['table_state']['intrastat_deliveries']['invoices_null']=$parameters['invoices_null'];
$_SESSION['table_state']['intrastat_deliveries']['tariff_code']=$parameters['tariff_code'];
$_SESSION['table_state']['intrastat_deliveries']['parent_period']=$parameters['parent_period'];
$_SESSION['table_state']['intrastat_deliveries']['parent_from']=$parameters['parent_from'];
$_SESSION['table_state']['intrastat_deliveries']['parent_to']=$parameters['parent_to'];
$_SESSION['table_state']['intrastat_deliveries']['parent']=$parameters['parent'];
$_SESSION['table_state']['intrastat_deliveries']['parent_key']=$parameters['parent_key'];

$smarty->assign('table_state',$_SESSION['table_state']['intrastat_deliveries']);

$smarty->assign('table_top_template', 'control.intrastat_deliveries.tpl');


include('utils/get_table_html.php');


?>
