<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2017 at 20:33:42 GMT+8, Cyberjaya. Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$options_status = array(
    'In Use'        => _('Active'),
    'Discontinuing' => _('Discontinued')
);

asort($options_status);


$category_product_fields = array();

if ($object->get('Category Branch Type') == 'Head') {




    $category_product_fields[] = array(
        'label'      => _('Status'),
        'show_title' => true,
        'fields'     => array(
            array(
                'render' => ($new ? false : true),
                'id'     => 'Part_Category_Status_Including_Parts',
                'edit'   => ($edit ? 'option' : ''),
                'options'         => $options_status,
                'value'           => htmlspecialchars($object->get('Part Category Status')),
                'formatted_value' => $object->get('Status'),
                'label'           => ucfirst($object->get_field_label('Part Category Status')),
                'required'        => ($new ? false : true),
                'type'            => 'skip'
            ),
        )
    );


}


?>
