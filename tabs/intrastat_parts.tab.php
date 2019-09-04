<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 12:09:24 GMT, Sheffield UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'intrastat_parts';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Reference'),
        'title' => _('Part Reference')
    ),
  

);

$_data  = preg_split('/\|/', $state['extra']);
$__data = preg_split('/\_/', $_data[1]);


$parameters = array(
    'parent'          => $state['parent'],
    'parent_key'      => $state['parent_key'],
    'country_code'    => $__data[0],
    'tariff_code'     => $__data[1],
    'parent_period'   => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
    'parent_from'     => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
    'parent_to'       => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : ''),
 

);

include_once 'class.Country.php';
$country = new Country('2alpha', $__data[0]);

$smarty->assign('country', $country);

$smarty->assign('commodity_code', $__data[1]);


$smarty->assign('link_deliveries', 'report/intrastat/deliveries/'.$__data[0].'/'.$__data[1]);

$_SESSION['table_state']['intrastat_parts']['tariff_code']     = $parameters['tariff_code'];
$_SESSION['table_state']['intrastat_parts']['parent_period']   = $parameters['parent_period'];
$_SESSION['table_state']['intrastat_parts']['parent_from']     = $parameters['parent_from'];
$_SESSION['table_state']['intrastat_parts']['parent_to']       = $parameters['parent_to'];
$_SESSION['table_state']['intrastat_parts']['parent']          = $parameters['parent'];
$_SESSION['table_state']['intrastat_parts']['parent_key']      = $parameters['parent_key'];

$smarty->assign('table_state', $_SESSION['table_state']['intrastat_parts']);

$smarty->assign('table_top_template', 'control.intrastat_parts.tpl');


include 'utils/get_table_html.php';


?>
