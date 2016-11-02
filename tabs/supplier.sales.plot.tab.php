<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 September 2016 at 15:02:15 GMT+8, Kuta, Bali , Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$supplier = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $supplier->get('Supplier Valid From'),
            'valid_to'     => ($supplier->get('Supplier Type') == 'Archived' ? $supplier->get('Supplier Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => 'supplier',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Deliveries')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
