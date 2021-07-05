<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 17:40:42 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



function get_location_object_fields($object, $user,$account,$db, $options) {

    $account->load_acc_data();

    $options_yes_no = array(
        'Yes' => _('Yes'),
        'No'  => _('No')
    );


    if ($user->can_supervisor('locations')) {
        $can_supervisor = true;
    } else {
        $can_supervisor = false;

    }

    if (isset($options['new']) and $options['new']) {
        $new = true;


    } else {
        $new = false;
    }

    $edit = true;

    $sql = sprintf(
        'select `Warehouse Flag Key`,`Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` where `Warehouse Flag Warehouse Key`=%d ', ($new ? $options['warehouse_key'] : $object->get('Location Warehouse Key'))
    );

    $flags = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $flags[$row['Warehouse Flag Key']] = sprintf('<i class="fa fa-flag %s padding_right_10 "  aria-hidden="true"></i> %s', strtolower($row['Warehouse Flag Color']), $row['Warehouse Flag Label']);
        }
    }


    $used_for_options = array(
        'Picking'    => _('Picking'),
        'Storing'    => _('Storing'),
        'Loading'    => _('Loading'),
        'Displaying' => _('Displaying')
    );
    asort($used_for_options);


    $object_fields = array(
        array(
            'label'      => _('Id'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit' => ($edit ? 'string' : ''),

                    'id'                => 'Location_Code',
                    'value'             => $object->get('Location Code'),
                    'formatted_value'   => $object->get('Code'),
                    'label'             => ucfirst($object->get_field_label('Location Code')),
                    'invalid_msg'       => get_invalid_message('string'),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array('tipo' => 'check_for_duplicates')
                    ),
                    'type'              => 'value'
                ),
                array(
                    'edit'            => ($edit ? 'option' : ''),
                    'render'          => false,
                    'id'              => 'Location_Mainly_Used_For',
                    'value'           => $object->get('Location Mainly Used For'),
                    'formatted_value' => $object->get('Mainly Used For'),
                    'options'         => $used_for_options,
                    'label'           => ucfirst($object->get_field_label('Location Mainly Used For')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(
                    'edit' => ($edit ? 'option' : ''),

                    'id'              => 'Location_Warehouse_Flag_Key',
                    'value'           => $object->get('Location Warehouse Flag Key'),
                    'formatted_value' => $object->get('Warehouse Flag Key'),
                    'options'         => $flags,
                    'label'           => ucfirst($object->get_field_label('Location Warehouse Flag Key')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),

            )
        ),

        array(
            'label'      => _('Capacity'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit' => ($edit ? 'numeric_unsigned' : ''),

                    'id'              => 'Location_Max_Weight',
                    'value'           => $object->get('Location Max Weight'),
                    'formatted_value' => $object->get('Max Weight'),
                    'label'           => ucfirst($object->get_field_label('Location Max Weight')),
                    'invalid_msg'     => get_invalid_message('numeric'),
                    'required'        => false,
                    'placeholder'     => 'Kg',
                    'type'            => 'value'
                ),

                array(
                    'edit' => ($edit ? 'numeric_unsigned' : ''),

                    'id'              => 'Location_Max_Volume',
                    'value'           => $object->get('Location Max Volume'),
                    'formatted_value' => $object->get('Max Volume'),
                    'label'           => ucfirst($object->get_field_label('Location Max Volume')),
                    'invalid_msg'     => get_invalid_message('numeric'),
                    'required'        => false,
                    'placeholder'     => _('Cubic meters'),
                    'type'            => 'value'
                ),


            )
        ),


    );


    if ($options['parent'] != 'warehouse_area') {
        $object_fields[] = array(
            'label'      => _('Warehouse area'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'                       => 'Location_Warehouse_Area_Key',
                    'edit'                     => 'dropdown_select',
                    'scope'                    => 'warehouse_areas',
                    'parent'                   => 'warehouse',
                    'parent_key'               => ($new ? $options['warehouse_key'] : $object->get('Location Warehouse Key')),
                    'value'                    => htmlspecialchars($object->get('Location Warehouse Area Key')),
                    'formatted_value'          => $object->get('Warehouse Area Key'),
                    'stripped_formatted_value' => '',
                    'label'                    => _('Area'),
                    'required'                 => true,
                    'type'                     => 'value'
                ),


            )
        );
    }

    if ($account->properties('fulfilment')) {
        $object_fields[] = array(
            'label'      => _('Fulfilment'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'              => 'Location_Fulfilment',
                    'edit'            => ($can_supervisor ? 'option' : ''),
                    'options'         => $options_yes_no,
                    'value'           => $object->get('Location Fulfilment'),
                    'formatted_value' => $object->get('Fulfilment'),
                    'label'           => ucfirst($object->get_field_label('Location Fulfilment')),
                    'type'            => ''
                ),


            )
        );
    }


    if (!$new) {
        $operations = array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'operations',
            'fields'     => array(

                array(
                    'id'        => 'delete_location',
                    'class'     => 'operation',
                    'value'     => '',
                    'render'    => true,
                    'label'     => '<i class="fa fa-fw fa-'.($can_supervisor ? 'lock-alt' : 'lock').'  button" 
                 data-labels=\'{ "text":"'._('Please ask an authorised user to delete this location').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($can_supervisor ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'LS\')').'"  
                style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete location")
                        .' <i class="far fa-trash-alt new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

             

            )

        );

        $object_fields[] = $operations;
    }

    return $object_fields;

}