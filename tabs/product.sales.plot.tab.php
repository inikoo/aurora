<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 18:05:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$product = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $product->get('Product Valid From'),
            'valid_to'     => ($product->get('Product Status') == 'Discontinued' ? $product->get('Product Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => $state['object'],
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Invoices')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
