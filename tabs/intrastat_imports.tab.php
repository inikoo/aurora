<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:13-08-2019 13:26:22 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'intrastat_imports';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'intrastat_imports';



$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'commodity' => array('label' => _('Commodity')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



if(!isset($default['invoices_vat']))$default['invoices_vat']=1;
if(!isset($default['invoices_no_vat']))$default['invoices_no_vat']=1;
if(!isset($default['invoices_null']))$default['invoices_null']=1;


if(isset($_SESSION['table_state']['intrastat_imports'])){
    $smarty->assign('table_state',$_SESSION['table_state']['intrastat_imports']);

}else{
    $smarty->assign('table_state',$default);

}




$smarty->assign('table_top_template', 'control.intrastat_imports.tpl');

include 'utils/get_table_html.php';



