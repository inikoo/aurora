<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2019 at 16:41:43 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'customer_notifications';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customer_notifications';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'type' => array('label' => _('Type'))

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');

$tmp_message='<div class="strong " style="padding:20px;border-bottom:1px solid #ccc"><i class="error fas fa-exclamation-triangle"></i> 
Newsletters and marketing mailshots moved to:
 <span class="link" style="padding-left: 20px" onclick="change_view(\'marketing/'.$state['parent_key'].'/emails\')">Stores/Products <span class="small italic">(left menu <i class="button far fa-store-alt fa-fw"></i>)</span>  <i style="padding-left:10px;padding-right:10px;" class="fa fa-angle-double-right separator"></i> Marketing <span class="small italic">(top menu <i class="button far fa-bullhorn fa-fw"></i>)</span> </span>
</div>';

$html=$tmp_message.$html;

?>
