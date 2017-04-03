<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:27:49 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$object->get_flags_data();


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit' => ($edit ? 'string' : ''),

                'id'                => 'Warehouse_Code',
                'value'             => $object->get('Warehouse Code'),
                'formatted_value'   => $object->get('Code'),
                'label'             => ucfirst(
                    $object->get_field_label('Warehouse Code')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'
            ),
            array(
                'edit' => ($edit ? 'string' : ''),

                'id'                => 'Warehouse_Name',
                'value'             => $object->get('Warehouse Name'),
                'formatted_value'   => $object->get('Name'),
                'label'             => ucfirst(
                    $object->get_field_label('Warehouse Name')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'type'              => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Address'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit' => ($edit ? 'textarea' : ''),

                'id'              => 'Warehouse_Address',
                'value'           => $object->get('Warehouse Address'),
                'formatted_value' => $object->get('Address'),
                'label'           => ucfirst(
                    $object->get_field_label('Warehouse Address')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),
        )
    ),


);


if(!$new) {

    $flags = array();
    $sql   = sprintf("SELECT * FROM `Warehouse Flag Dimension`  ");

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $flags[] = array(
                'edit' => ($edit ? 'string' : ''),

                'id'                => 'Warehouse_Flag_Label_'.$row['Warehouse Flag Key'],
                'value'             => $object->get(
                    'Warehouse Flag Label '.$row['Warehouse Flag Key']
                ),
                'formatted_value'   => $object->get(
                    'Flag Label '.$row['Warehouse Flag Key']
                ),
                'label'             => ucfirst(
                    $object->get_field_label(
                        'Warehouse Flag Label '.$row['Warehouse Flag Color']
                    )
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'         => 'check_for_duplicates',
                        'object'       => 'Warehouse Flag',
                        'parent'       => 'warehouse',
                        'parent_key'   => $object->id,
                        'actual_field' => 'Warehouse Flag Label'
                    )
                ),
                'type'              => 'value'
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $object_fields[] = array(
        'label'      => _("Location's flag labels"),
        'show_title' => true,
        'fields'     => $flags
    );
}

?>
