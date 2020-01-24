<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2016 at 20:59:26 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$part = $state['_object'];

$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $part->get('Part Valid From'),
            'valid_to'     => ($part->get('Product Status') == 'Not In Use' ? $part->get('Part Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => 'part',
            'parent_key'   => $part->id,
            'title_value'  => _('Stock'),
            'title_volume' => '_xxx_'

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('part_stock.chart.tpl');


