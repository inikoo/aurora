<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2016 at 18:28:05 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'utils/country_functions.php';



$root_user = $state['_object'];

$account = new Account();

$object_fields = array(


    array(
        'label'      => _('Recovery information'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(

                'id'              => 'User_Password_Recovery_Email',
                'edit'            => 'email',
                'value'           => $root_user->get(
                    'User Password Recovery Email'
                ),
                'formatted_value' => $root_user->get('Password Recovery Email'),
                'label'           => ucfirst(
                    $root_user->get_field_label('User Password Recovery Email')
                ),
                'invalid_msg'     => get_invalid_message('email'),
                'required'        => true,
                'type'            => 'value'

            ),
            array(

                'id'              => 'User_Password_Recovery_Mobile',
                'edit'            => 'telephone',
                'value'           => $root_user->get('User Password Recovery Mobile'),
                'formatted_value' => $root_user->get('Password Recovery Mobile'),
                'label'           => ucfirst($root_user->get_field_label('User Password Recovery Mobile')),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => true,
                'type'            => 'value'

            ),


        )
    ),
    array(
        'label'      => _('Root user'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(


                'id'              => 'User_Password',
                'edit'            => 'password',
                'value'           => '',
                'formatted_value' => '******',
                'label'           => _('Password')


                ,
                'invalid_msg'     => get_invalid_message('password'),
                'type'            => 'value'

            ),


        )
    )


);


$smarty->assign('state', $state);
$smarty->assign('object', $root_user);
$smarty->assign('account', $account);

$smarty->assign('form_type', 'setup');

$smarty->assign('object_name', $root_user->get_object_name());
$smarty->assign('step', 'root_user');

$country='GB';

$smarty->assign(
    'default_country', $country
);
$smarty->assign(
    'preferred_countries', '"'.join(
                             '", "', preferred_countries($country)
                         ).'"'
);

$default_country = $country;
$smarty->assign(
    'default_telephone_data', base64_encode(
                                json_encode(
                                    array(
                                        'default_country'     => strtolower($default_country),
                                        'preferred_countries' => array_map(
                                            'strtolower', preferred_countries($default_country)
                                        ),
                                    )
                                )
                            )
);


$smarty->assign('object_fields', $object_fields);
$html = $smarty->fetch('new_object.tpl');

?>
