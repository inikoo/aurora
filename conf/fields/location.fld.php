<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 17:40:42 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$sql=sprintf('select `Warehouse Flag Key`,`Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` where `Warehouse Flag Warehouse Key`=%d ',
    ($new ? $options['warehouse_key']:$object->get('Location Warehouse Key'))
    );

$flags=array();
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $flags[$row['Warehouse Flag Key']]=sprintf('<i class="fa fa-flag %s padding_right_10 "  aria-hidden="true"></i> %s', strtolower($row['Warehouse Flag Color']),$row['Warehouse Flag Label']);
		}
}else {
		print_r($error_info=$this->db->errorInfo());
		print "$sql\n";
		exit;
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
                'edit' => ($edit ? 'option' : ''),
            'render'=>false,
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

                'id'                => 'Location_Max_Weight',
                'value'             => $object->get('Location Max Weight'),
                'formatted_value'   => $object->get('Max Weight'),
                'label'             => ucfirst($object->get_field_label('Location Max Weight')),
                'invalid_msg'       => get_invalid_message('numeric'),
                'required'          => false,
               'placeholder'=>'Kg',
                'type'              => 'value'
            ),

            array(
                'edit' => ($edit ? 'numeric_unsigned' : ''),

                'id'                => 'Location_Max_Volume',
                'value'             => $object->get('Location Max Volume'),
                'formatted_value'   => $object->get('Max Volume'),
                'label'             => ucfirst($object->get_field_label('Location Max Volume')),
                'invalid_msg'       => get_invalid_message('numeric'),
                'required'          => false,
                'placeholder'=>_('Cubic meters'),
                'type'              => 'value'
            ),


        )
    ),



);


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
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete location").' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
