<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:21 December 2017 at 11:16:12 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'intrastat';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['intrastat']['to'])) {
    $default['to'] = $_SESSION['table_state']['intrastat']['to'];
}
if (isset($_SESSION['table_state']['intrastat']['from'])) {
    $default['from'] = $_SESSION['table_state']['intrastat']['from'];
}
if (isset($_SESSION['table_state']['intrastat']['period'])) {
    $default['period'] = $_SESSION['table_state']['intrastat']['period'];
}
if (isset($_SESSION['table_state']['intrastat']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['intrastat']['excluded_stores'];
}
$table_views = array();

$table_filters = array(
    //	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
    'commodity' => array('label' => _('Comodity')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


//$smarty->assign('hide_period',true);

include 'utils/get_table_html.php';


?>
