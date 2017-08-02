<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 15:16:01 GMT+8, Puchong, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$labels=$object->get('Localised Labels');

//print_r($labels);


$object_fields = array(
    array(
        'label'      => _('Registration'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Localised_Labels_Register',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_Register'])  ?_('Register'):$labels['_Register']),
                'label'             => _('Register'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Localised_Labels_Login',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_Login'])  ?_('Login'):$labels['_Login']),
                'label'             => _('Login'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Localised_Labels_login_to_see',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => ( empty($labels['_login_to_see'])  ?_('For prices, please login or register'):$labels['_login_to_see']),
                'label'             => _('Login to see message'),
                'required'          => true,
                'type'              => 'value'
            ),

        )
    ),



);



?>
