<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 June 2016 at 15:45:05 GMT+8 Kuta, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$account->load_acc_data();

include_once 'utils/available_locales.php';
$available_locales=get_available_locales();

include 'conf/user_groups.php';


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$options_User_Groups = array();

foreach ($user_groups as $key => $user_group) {
    $options_User_Groups[$user_group['Key']] = array(
        'label'    => $user_group['Name'],
        'selected' => false
    );
}



$options_locales = array();
foreach ($available_locales as $locale) {

    $options_locales[$locale['Locale']] = $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
}


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($options_yn);
asort($options_locales);
asort($options_User_Groups);


$object_fields = array(
    array(
        'label'      => _('System user'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(

            array(
                'render'          => is_array($options) and  in_array(
                    $options['parent'], array(
                                          'supplier',
                                          'agent',
                                          'contractor'
                                      )
                ),
                'id'              => 'User_Alias',
                'edit'            => 'string',
                'value'           => $object->get('User Alias'),
                'formatted_value' => $object->get('Alias'),
                'label'           => ucfirst(
                    $object->get_field_label('User Alias')
                ),
                'type'            => ((is_array($options) and in_array(
                    $options['parent'], array(
                                          'supplier',
                                          'agent',
                                          'contractor'
                                      )
                ) )? 'value' : '')

            ),


            array(
                'render'          => true,
                'id'              => 'User_Active',
                'edit'            => 'option',
                'value'           => ($new ? 'Yes' : $object->get('User Active')),
                'formatted_value' => ($new ? _('Yes') : $object->get('Active')),
                'options'         => $options_yn,
                'label'           => ucfirst($object->get_field_label('User Active')),
                'type'            => 'value'
            ),


            array(
                'render'            => true,
                'id'                => 'User_Password_Recovery_Email',
                'edit'              => 'email',
                'value'             => $object->get('User Password Recovery Email'),
                'formatted_value'   => $object->get('User Password Recovery Email'),
                'label'             => ucfirst(
                    $object->get_field_label('User Password Recovery Email')
                ),
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'object'     => 'User',
                        'key'        => $object->id
                    )
                ),
                'invalid_msg'       => get_invalid_message('email'),
                'type'              => 'value'

            ),


            array(
                'render'            => true,
                'id'                => 'User_Handle',
                'edit'              => 'handle',
                'value'             => $object->get('User Handle'),
                'formatted_value'   => $object->get('Handle'),
                'label'             => ucfirst(
                    $object->get_field_label('User Handle')
                ),
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'object'     => 'User',
                        'key'        => $object->id
                    )
                ),
                'invalid_msg'       => get_invalid_message('handle'),
                'type'              => 'value'

            ),

            array(
                'render' => true,

                'id'              => 'User_Password',
                'edit'            => 'password',
                'value'           => '',
                'formatted_value' => '******',
                'label'           => ucfirst(
                    $object->get_field_label('User Password')
                ),
                'invalid_msg'     => get_invalid_message('password'),
                'type'            => 'value'

            ),
            array(
                'render'          => ($new
                    ? ($options['parent'] == 'Staff' ? true : false)
                    : in_array(
                        $object->get('User Type'), array(
                                                     'Staff',
                                                     'Contractor'
                                                 )
                    )),
                'id'              => 'User_PIN',
                'edit'            => 'pin',
                'value'           => '',
                'formatted_value' => '****',
                'label'           => ucfirst(
                    $object->get_field_label('User PIN')
                ),
                'invalid_msg'     => get_invalid_message('pin'),
                'type'            => ($new
                    ? ($options['parent'] == 'Staff' ? 'value' : '')
                    : (in_array(
                        $object->get('User Type'), array(
                                                     'Staff',
                                                     'Contractor'
                                                 )
                    ) ? 'value' : ''))

            ),


        )
    ),


);


if (!$new) {

    if (in_array(
        $object->get('User Type'), array(
                                     'Staff',
                                     'Contractor'
                                 )
    )) {

        include_once 'utils/available_locales.php';
        $available_locales=get_available_locales();

        include 'conf/user_groups.php';


        $employee = get_object(
            $object->get('User Type'), ($object->get('User Parent Key'))
        );


        include_once 'conf/roles.php';
        $roles=get_roles();
        foreach ($roles as $_key => $_data) {
            if (in_array(
                $account->get('Setup Metadata')['size'], $_data['size']
            )) {

                foreach (
                    $account->get('Setup Metadata')['instances'] as $instance
                ) {
                    if (in_array($instance, $_data['instances'])) {

                        $options_Staff_Position[$_key] = array(
                            'label'    => $_data['title'],
                            'selected' => false
                        );
                        break;
                    }
                }
            }
        }


        foreach (
            preg_split('/,/', $employee->get('Staff Position')) as $current_position_key
        ) {
            if (array_key_exists(
                $current_position_key, $options_Staff_Position
            )) {

                $options_Staff_Position[$current_position_key]['selected'] = true;
            }
        }

        $options_yn = array(
            'Yes' => _('Yes'),
            'No'  => _('No')
        );

        $options_locales = array();
        foreach ($available_locales as $locale) {

            $options_locales[$locale['Locale']] = $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
        }


        $options_Groups = array();
        foreach ($user_groups as $key => $user_group) {
            $options_Groups[$key] = array(
                'label'    => $user_group['Name'],
                'selected' => false
            );
        }


        $options_Stores = array();
        $sql            = sprintf(
            'SELECT `Store Key` AS `key` ,`Store Name`,`Store Code`   FROM `Store Dimension`  '
        );
        foreach ($db->query($sql) as $row) {
            $options_Stores[$row['key']] = array(
                'label'    => $row['Store Code'],
                'selected' => false
            );
        }
        foreach (preg_split('/,/', $object->get('User Stores')) as $key) {
            if (array_key_exists($key, $options_Stores)) {
                $options_Stores[$key]['selected'] = true;
            }
        }

        $options_Websites = array();
        $sql              = sprintf(
            'SELECT `Website Key` AS `key` ,`Website Name`,`Website Code` FROM `Website Dimension`  '
        );
        foreach ($db->query($sql) as $row) {
            $options_Websites[$row['key']] = array(
                'label'    => $row['Website Code'],
                'selected' => false
            );
        }
        foreach (preg_split('/,/', $object->get('User Websites')) as $key) {
            if (array_key_exists($key, $options_Websites)) {
                $options_Websites[$key]['selected'] = true;
            }
        }

        $options_Warehouses = array();
        $sql                = sprintf(
            'SELECT `Warehouse Key` AS `key` ,`Warehouse Name`,`Warehouse Code` FROM `Warehouse Dimension`  '
        );
        foreach ($db->query($sql) as $row) {
            $options_Warehouses[$row['key']] = array(
                'label'    => $row['Warehouse Code'],
                'selected' => false
            );
        }
        foreach (preg_split('/,/', $object->get('User Warehouses')) as $key) {
            if (array_key_exists($key, $options_Warehouses)) {
                $options_Warehouses[$key]['selected'] = true;
            }
        }

        $options_Productions = array();
        $sql                 = sprintf(
            'SELECT `Supplier Production Supplier Key` AS `key`,`Supplier Name`,`Supplier Code` FROM `Supplier Production Dimension` SPD LEFT JOIN `Supplier Dimension` S ON (`Supplier Key`=`Supplier Production Supplier Key`)  '
        );


        foreach ($db->query($sql) as $row) {
            $options_Productions[$row['key']] = array(
                'label'    => $row['Supplier Code'],
                'selected' => false
            );
        }
        foreach (preg_split('/,/', $object->get('User Productions')) as $key) {
            if (array_key_exists($key, $options_Productions)) {
                $options_Productions[$key]['selected'] = true;
            }
        }

        asort($options_yn);
        asort($options_locales);
        asort($options_Groups);
        asort($options_Staff_Position);


        $object_fields[] = array(
            'label'      => _('Permissions'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(

                array(
                    'id'     => 'User_Permissions',
                    'edit'   => 'user_permissions',

                    'value'           => '',
                    'formatted_value' => '',
                    'label'           => _('Permissions'),
                    'required'        => false,
                    'type'            => 'user_value'
                ),

            )


        );


    }
}


$object_fields[] = array(
    'label'      => _('Preferences'),
    'show_title' => true,
    'class'      => 'edit_fields',
    'fields'     => array(
        array(
            'id'              => 'User_Preferred_Locale',
            'edit'            => 'option',
            'value'           => ($new
                ? $account->get('Account Locale')
                : $object->get(
                    'User Preferred Locale'
                )),
            'formatted_value' => ($new
                ? $account->get('Locale')
                : $object->get(
                    'Preferred Locale'
                )),
            'label'           => ucfirst(
                $object->get_field_label('Preferred Locale')
            ),
            'options'         => $options_locales,
            'type'            => 'value'

        )

    )
);

if (!$new) {

    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_user',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete user").' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;

}

