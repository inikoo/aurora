<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 oct 2023 6;20pm KL Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$agent = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $agent->get('Agent Valid From'),
            'valid_to'     => ($agent->get('Agent Type') == 'Archived' ? $agent->get('Agent Valid To') : gmdate("Y-m-d H:i:s")),
            'parent'       => 'agent',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Deliveries')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');



