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
        'label'      => _('Id'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(

                'id'    => 'API_Key_Key',
                'value' => $apy_key->get('API Key Key'),
                'label' => ucfirst($apy_key->get_field_label('API Key Key')),
            ),
            array(

                'id'    => 'API_Key_Code',
                'value' => $apy_key->get('API Key Code'),
                'label' => ucfirst($apy_key->get_field_label('API Key Code')),

            ),


        )
    ),

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
                'label'           => ucfirst(
                    $apy_key->get_field_label('API Key Active')
                ),
                'required'        => true

            ),

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


        )
    ),


);


$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>
