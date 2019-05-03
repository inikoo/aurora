<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2016 at 11:49:19 CEST, Mijas Costa, Spain

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
        'label'      => _('Department'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                       => 'Product_Category_Department_Category_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'departments',
                'parent'                   => 'store',
                'parent_key'               => ($new ? $options['store_key'] : $object->get('Category Store Key')),
                'value'                    => htmlspecialchars($object->get('Product Category Department Category Key')),
                'formatted_value'          => $object->get('Department Category Code'),
                'stripped_formatted_value' => '',
                'label'                    => _('Department'),
                'required'                 => true,
                'type'                     => 'value'


            ),


        )
    ),

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
                'label'           => _('Public'),
                'type'            => 'value'
            ),


        )
    ),



);


