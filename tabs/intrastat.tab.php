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
$tipo    = 'intrastat';$smarty->assign('table_top_template', 'prospects.base_blueprints.tpl');


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

if (isset($_SESSION['table_state']['intrastat']['invoices_vat'])) {
    $default['invoices_vat'] = $_SESSION['table_state']['intrastat']['invoices_vat'];
}else{
    $default['invoices_vat'] = 1;
}
if (isset($_SESSION['table_state']['intrastat']['invoices_no_vat'])) {
    $default['invoices_no_vat'] = $_SESSION['table_state']['intrastat']['invoices_no_vat'];
}else{
    $default['invoices_no_vat'] = 1;
}
if (isset($_SESSION['table_state']['intrastat']['invoices_null'])) {
    $default['invoices_null'] = $_SESSION['table_state']['intrastat']['invoices_null'];
}else{
    $default['invoices_null'] = 1;
}






$table_views = array();

$table_filters = array(
    //	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
    'commodity' => array('label' => _('Commodity')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);

//print_r($_SESSION['table_state']['intrastat']);

if(!isset($_SESSION['table_state']['intrastat'])){
    $smarty->assign('table_state',$default);
   // print 'c1';

   // print_r($default);


}else{
    $smarty->assign('table_state',$_SESSION['table_state']['intrastat']);
  //  print 'c2';
  //  print_r($_SESSION['table_state']['intrastat']);
}


$smarty->assign('table_top_template', 'control.intrastat.tpl');



include 'utils/get_table_html.php';


?>
