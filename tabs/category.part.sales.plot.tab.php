<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 16:36:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$category = $state['_object'];


$data = base64_encode(
    json_encode(
        array(
            'valid_from'   => $category->get('Part Category Valid From'),
            'valid_to'     => ($category->get('Part Category Status') == 'NotInUse'
                ? $category->get('Part Category Valid To')
                : gmdate(
                    "Y-m-d H:i:s"
                )),
            'parent'       => 'part_category',
            'parent_key'   => $state['key'],
            'title_value'  => _('Sales'),
            'title_volume' => _('Deliveries')

        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('asset_sales.chart.tpl');


?>
