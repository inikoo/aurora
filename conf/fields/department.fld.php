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

$store                = get_object('Store', $object->get('Store Key'));

$show_move=true;


if($store->get('Store Department Category Key')==$object->get('Category Parent Key')) {
    $show_move=false;

}

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

if($show_move){
    $category_product_fields[]=
        array(
            'label'      => _('Department'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                       => 'Subdepartment_Parent_Key',
                    'edit'                     => 'dropdown_select',
                    'scope'                    => 'departments',
                    'parent'                   => 'store',
                    'parent_key'               => ($new ? $options['store_key'] : $object->get('Category Store Key')),
                    'value'                    => htmlspecialchars($object->get('Category Parent Key')),
                    'formatted_value'          => $object->get('Parent Category Code'),
                    'stripped_formatted_value' => '',
                    'label'                    => _('Department'),
                    'required'                 => true,
                    'type'                     => 'value'


                ),


            )
        );
}



