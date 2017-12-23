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


$table_views = array(



);

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
    'parent'        => $state['parent'],
    'parent_key'    => $state['parent_key'],
    'country_code'       => $__data[0],
    'tariff_code'   => $__data[1],
    'parent_period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
    'parent_from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
    'parent_to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : '')


);



include 'utils/get_table_html.php';


?>
