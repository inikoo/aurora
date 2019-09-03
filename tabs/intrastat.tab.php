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


if(!isset($default['invoices_vat']))$default['invoices_vat']=1;
if(!isset($default['invoices_no_vat']))$default['invoices_no_vat']=1;
if(!isset($default['invoices_null']))$default['invoices_null']=1;


if(isset($_SESSION['table_state']['intrastat'])){
    $smarty->assign('table_state',$_SESSION['table_state']['intrastat']);

}else{
    $smarty->assign('table_state',$default);

}




$smarty->assign('table_top_template', 'control.intrastat.tpl');


//print_r($default);


include 'utils/get_table_html.php';



