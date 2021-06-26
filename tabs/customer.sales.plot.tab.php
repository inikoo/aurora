<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 July 2018 at 12:14:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$customer = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $customer->get('Customer First Contacted Date'),
            'valid_to'     => gmdate("Y-m-d H:i:s"),
            'parent'       => 'customer',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Invoices')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


