<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2015 at 23:11:30 GMT Sheffield UL
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';

$apy_key = $state['_object'];

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


asort($options_yn);

$object_fields = array(


    array(
        'label'      => _('Access'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(

            array(

                'id'              => 'API_Key_Active',
                'edit'            => 'option',
                'value'           => $apy_key->get('API Key Active'),
                'formatted_value' => $apy_key->get('Active'),
                'options'         => $options_yn,
                'label'           => ucfirst($apy_key->get_field_label('API Key Active')),
                'required'        => true

            ),
/*
            array(

                'id'          => 'API_Key_Allowed IP',
                'edit'        => 'string',
                'value'       => $apy_key->get('API Key Allowed IP'),
                'label'       => ucfirst(
                    $apy_key->get_field_label('API Key Allowed IP')
                ),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false
            ),
            array(

                'id'              => 'API_Key_Allowed_Requests_per_Hour',
                'edit'            => 'mediumint_unsigned',
                'value'           => $apy_key->get(
                    'API Key Allowed Requests per Hour'
                ),
                'formatted_value' => $apy_key->get('Allowed Requests per Hour'),
                'label'           => ucfirst(
                    $apy_key->get_field_label(
                        'API Key Allowed Requests per Hour'
                    )
                ),
                'invalid_msg'     => get_invalid_message('mediumint_unsigned'),
                'required'        => true

            ),

*/


        )
    ),


);


$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(

        array(
            'id'        => 'delete_api_key',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$apy_key->get_object_name().'", "key":"'.$apy_key->id
                .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete API key").' <i class="far fa-trash-alt new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);
$object_fields[] = $operations;

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>
