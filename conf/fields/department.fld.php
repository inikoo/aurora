<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2016 at 16:59:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$public_options = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($public_options);



$category_product_fields = array(
    array(
        'label'      => _('Visibility'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'            => 'option',
                'id'              => 'Product_Category_Public',
                'options'         => $public_options,
                'value'           => $object->get('Product Category Public'),
                'formatted_value' => $object->get('Public'),
                'label'           => _('Show in website'),
                'type'            => 'value'
            ),


        )
    ),


);

?>
