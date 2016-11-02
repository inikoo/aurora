<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 11:56:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$part = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $part->get('Part Valid From'),
            'valid_to'     => ($part->get('Part Status') == 'Not In Use' ? $part->get('Part Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => $state['object'],
            'parent_key'   => $state['key'],
            'title_value'  => _('Sale amount'),
            'title_volume' => _('SKOs')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
