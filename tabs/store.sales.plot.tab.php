<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2016 at 13:53:37 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$store = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $store->get('Store Valid From'),
            'valid_to'     => ($store->get('Store State') == 'Closed' ? $store->get('Store Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => 'store',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Invoices')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
