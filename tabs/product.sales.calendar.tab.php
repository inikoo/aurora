<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2016 at 15:55:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$product = $state['_object'];

$sales_max_sample_domain = 1;

if ($product->get('Product Max Day Sales') > 0) {
    $top_range = $product->get('Product Avg with Sale Day Sales') + (3 * $product->get('Product STD with Sale Day Sales'));
    if ($product->get('Product Max Day Sales') < $top_range) {
        $sales_max_sample_domain = $product->get('Product Max Day Sales');
    } else {
        $sales_max_sample_domain = $top_range;
    }

}

$data = base64_encode(
    json_encode(
        array(
            'valid_from'              => $product->get('Product Valid From'),
            'valid_to'                => ($product->get('Product Status') == 'Discontinued'
                ? $product->get('Product Valid To')
                : gmdate(
                    "Y-m-d H:i:s"
                )),
            'sales_max_sample_domain' => $sales_max_sample_domain,
            'parent'                  => $state['object'],
            'parent_key'              => $state['key']
        )
    )
);

$smarty->assign('data', $data);
$html = $smarty->fetch('calendar.tpl');


?>
