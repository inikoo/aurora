<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 November 2016 at 21:35:42 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$account = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $account->get('Account Valid From'),
            'valid_to'     => gmdate("Y-m-d H:i:s"),
            'parent'       => 'account',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Invoices')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
