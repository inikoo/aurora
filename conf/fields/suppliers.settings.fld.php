<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2016 at 22:45:13 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array(
    array(
        'label'      => _('Purchase orders'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Account_Suppliers_Terms_and_Conditions',
                'edit'            => ($edit ? 'editor' : ''),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Account_Suppliers_Terms_and_Conditions',
                    'content' => $object->get(
                        'Account Suppliers Terms and Conditions'
                    ),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Account_Suppliers_Terms_and_Conditions',
                                'plugins'  => array(
                                    'align',
                                    'draggable',
                                    'image',
                                    'link',
                                    'save',
                                    'entities',
                                    'emoticons',
                                    'fullscreen',
                                    'lineBreaker',
                                    'table',
                                    'codeView',
                                    'codeBeautifier'
                                ),
                                'metadata' => array(
                                    'tipo'   => 'edit_field',
                                    'object' => 'Account',
                                    'key'    => $object->id,
                                    'field'  => 'Account Suppliers Terms and Conditions',


                                )
                            )
                        )
                    )

                ),
                'value'           => $object->get(
                    'Account Suppliers Terms and Conditions'
                ),
                'formatted_value' => $object->get(
                    'Account Suppliers Terms and Conditions'
                ),
                'label'           => _('Terms and Conditions'),
                'required'        => false,
                'type'            => 'value'
            ),

        )
    ),


);


?>
