<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13:39:30 MYT Tuesday, 7 July 2020, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


function get_raw_material_edit_fields($raw_material, $user) {

    if ($user->can_edit('production')) {
        $edit = true;
    } else {
        $edit = false;
    }

    /*
    if ($user->can_supervisor('production')) {
        $super_edit = true;
    } else {
        $super_edit = false;

    }
*/


    $object_fields = [];


    $object_fields[] = array(
        'label'      => _('Status/Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'                => 'Raw_Material_Code',
                'edit'              => ($edit ? 'string' : ''),
                'right_code'        => 'FE',
                'value'             => htmlspecialchars($raw_material->get('Raw Material Code')),
                'formatted_value'   => $raw_material->get('Code'),
                'label'             => ucfirst($raw_material->get_field_label('Raw Material Code')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'object'     => 'Raw_Material',
                        'key'        => $raw_material->id
                    )
                ),
                'type'              => 'value'
            ),


        )
    );


    return $object_fields;
}