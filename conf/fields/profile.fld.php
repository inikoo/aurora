<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2016 at 13:48:52 GMT+8, Ubud (Bali), Indonesia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/available_locales.php';
$available_locales=get_available_locales();

include 'conf/user_groups.php';




//print_r($object);

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$options_theme = array(
    'app_theme_default' => _('Black'),
    'app_theme_red' => _('Red'),
    'app_theme_green' => _('Green'),
    'app_theme_blue'  => _('Blue')
);

$options_timezone = array(
    'Account' => _('Organization').' <small class="discreet small">('.$account->get('Timezone').')</small>',
    'Local' => _('Local time').' <small class="discreet small">('.$_SESSION['local_timezone_label'].')</small>',
    'UTC' => 'UTC',
);


$options_locales = array();
foreach ($available_locales as $locale) {

    $options_locales[$locale['Locale']] = $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
}



$object_fields = array(


    array(
        'label'      => _('Email'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(





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



        )
    ),


    array(
        'label'      => _('Access'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(




            array(
                'render'          => ($object->get('User Active') == 'Yes' ? true : false),
                'id'              => 'User_Password',
                'edit'            => 'password',
                'value'           => $object->get('User Password'),
                'formatted_value' => $object->get('Password'),
                'label'           => _('Password'),
                'invalid_msg'     => get_invalid_message('password'),
            ),
            array(
                'render'          => ( ($object->get('User Active') == 'No' or $object->get('User Type')=='Administrator' ) ? false : true),
                'id'              => 'User_PIN',
                'edit'            => 'pin',
                'value'           => $object->get('User PIN'),
                'formatted_value' => $object->get('PIN'),
                'label'           => _('PIN'),
                'invalid_msg'     => get_invalid_message('pin'),
            ),

        )
    ),


    array(
        'label'      => _('Notifications'),
        'show_title' => true,
        'class'      => 'edit_fields hide',
        'fields'     => array(




            array(
                'id'     => 'User_Browser_Notifications',
                'render' => false,
                'edit'            => 'user_browser_notifications',
                'class'=>'operation_with_field',
                'value'           => '',
                'formatted_value' => $object->get('User Browser Notifications'),
                'label'           => '',
                'required'        => false,
                'type'            => ''
            ),


        )
    ),


    array(
        'label'      => _('Preferences'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(
            array(
                'id'              => 'User_Preferred_Locale',
                'edit'            => 'option',
                'value'           => $object->get('User Preferred Locale'),
                'formatted_value' => $object->get('Preferred Locale'),
                'label'           => ucfirst(
                    $object->get_field_label('Preferred Locale')
                ),
                'options'         => $options_locales,


            ),
            array(
                'id'              => 'User_Display_Timezone',
                'edit'            => 'option',
                'value'           => $object->get('User Display Timezone'),
                'formatted_value' => $object->get('Display Timezone'),
                'label'           => ucfirst($object->get_field_label('User Display Timezone')),
                'options'         => $options_timezone,


            ),
            array(
                'render'          => (  $object->get('User Type')=='Administrator'  ? false : true),
                'id'              => 'theme',
                'edit'            => 'option',
                'value'           => $user->get('User Theme'),
                'formatted_value' => $user->get('Theme'),
                'label'           => ucfirst($object->get_field_label('User Theme')),
                'options'         => $options_theme,


            )

        )
    ),
);



