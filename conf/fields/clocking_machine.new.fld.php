<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 18:31:14 +0800 MYT,, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

include_once 'utils/timezones.php';




$_edit = true;


$object_fields = array(


    array(
        'label'      => _('Ids, Name'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Clocking_Machine_Serial_Number',
                'edit'              => 'string',
                'value'             => htmlspecialchars($object->get('Clocking Machine Serial Number')),
                'formatted_value'   => $object->get('Serial Number'),
                'label'             => '<span title="'._('Serial number displayed in the box').'">'._('Serial number').' <i class="far padding_left_5 fa-pager"></i>',
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_box_serial_number',

                    )
                ),
                'type'              => 'value'
            ),
            array(
                'id'                => 'Clocking_Machine_Code',
                'edit'              => 'string',
                'value'             => htmlspecialchars($object->get('Clocking Machine Code')),
                'formatted_value'   => $object->get('Code'),
                'label'             => ucfirst($object->get_field_label('Clocking Machine Code')),
                'required'          => true,
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => '',
                        'object'     => 'Clocking Machine',
                    )
                ),
                'type'              => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Timezone'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Clocking_Machine_Timezone',
                'edit'              => 'timezone',
                'value'             => htmlspecialchars($account->get('Account Timezone')),
                'formatted_value'   => $account->get('Timezone'),
                'label'             => ucfirst($object->get_field_label('Clocking Machine Timezone')),
                'required'          => true,
                'timezones'       => get_normalized_timezones(),

                'type'              => 'value'
            ),



        )
    ),
    array(
        'label'      => _('WiFi').' <i class="far fa-router"></i>',
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Clocking_Machine_WiFi_SSID',
                'edit'              => 'string',
                'value'             => htmlspecialchars($object->get('Clocking Machine WiFi SSID')),
                'formatted_value'   => $object->get('WiFi SSID'),
                'label'             => 'SSIS',
                'required'          => false,

                'type'              => 'value'
            ),
            array(
                'id'                => 'Clocking_Machine_WiFi_Password',
                'edit'              => 'string',
                'value'             => '',
                'formatted_value'   => '',
                'label'             => _('Password'),
                'required'          => false,

                'type'              => 'value'
            ),


        )
    ),
);







