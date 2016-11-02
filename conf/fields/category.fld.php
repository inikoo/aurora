<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 May 2016 at 14:29:11 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/
if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$subject_render = false;
switch ($options['Category Scope']) {
    case 'Product':
        $subject_options         = array(
            'Product'  => _('Products'),
            'Category' => _('Categories')
        );
        $subject_value           = 'Product';
        $subject_formatted_value = _('Products');
        $subject_render          = true;

        break;
    case 'Part':
        $subject_options         = array('Part' => _('Parts'));
        $subject_value           = 'Part';
        $subject_formatted_value = _('Parts');
        break;
    case 'Location':
        $subject_options         = array('Location' => _('Locations'));
        $subject_value           = 'Location';
        $subject_formatted_value = _('Locations');
        break;
    default:
        $subject_options         = array();
        $subject_value           = '';
        $subject_formatted_value = '';
        break;
}


$category_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(
            array(
                'edit'            => 'hidden',
                'render'          => false,
                'required'        => false,
                'id'              => 'Category_Scope',
                'value'           => $options['Category Scope'],
                'formatted_value' => $options['Category Scope'],
                'label'           => 'Scope',
                'type'            => 'value'
            ),

            array(
                'edit'            => 'option',
                'render'          => (($new and $subject_render) ? true : false),
                'required'        => (($new and $subject_render) ? true : false),
                'id'              => 'Category_Subject',
                'options'         => $subject_options,
                'value'           => $subject_value,
                'formatted_value' => $subject_formatted_value,
                'label'           => _('Subject type'),
                'type'            => 'value'
            ),

            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Category_Code',
                'value'             => $object->get('Category Code'),
                'formatted_value'   => $object->get('Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Category Code')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Category_Label',
                'value'           => $object->get('Category Label'),
                'formatted_value' => $object->get('Label'),
                'label'           => ucfirst(
                    $object->get_field_label('Category Label')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'type'            => 'value'
            ),

        )
    ),


);


switch ($options['Category Scope']) {
    case 'Product':


        $store = new Store($object->get('Store Key'));


        $object->get_webpage();


        if ($store->get('Store Family Category Key') == $object->get(
                'Category Root Key'
            )
        ) {

            include 'family.fld.php';
            $category_fields = array_merge(
                $category_fields, $category_product_fields
            );

        } elseif ($store->get('Store Department Category Key') == $object->get(
                'Category Root Key'
            )
        ) {

            include 'department.fld.php';
            $category_fields = array_merge(
                $category_fields, $category_product_fields
            );

        } else {
            include 'category.product.fld.php';
            $category_fields = array_merge(
                $category_fields, $category_product_fields
            );

        }


        break;
    default:

        break;
}


if (!$new) {
    $operations        = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(
            array(
                'id'        => 'delete_category',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete category').' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

        )

    );
    $category_fields[] = $operations;

}


?>
