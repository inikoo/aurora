<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2017 at 15:43:40 GMT, Sheffield, UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($options_yn);

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Deal_Campaign_Name',
                'value'             => $object->get('Deal Campaign Name'),
                'label'             => ucfirst($object->get_field_label('Deal Campaign Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),

            array(
                'id'              => 'Deal_Campaign_Description',
                'edit'            => ($edit ? 'editor' : ''),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Deal_Campaign_Description',
                    'content' => $object->get('Deal Campaign Description'),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Deal_Campaign_Description',
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
                                    'object' => 'Deal_Campaign',
                                    'key'    => $object->id,
                                    'field'  => 'Deal Campaign Description',


                                )
                            )
                        )
                    )

                ),
                'value'             => $object->get('Deal Campaign Description'),
                'formatted_value'             => $object->get('Deal Campaign Description'),

                'label'             => ucfirst($object->get_field_label('Deal Campaign Description')),
                'required'          => false,
                'type'            => 'value'
            ),





        )
    ),

  

);


if (!$new ) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'suspend_campaign',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._("Suspend all offers")
                    .' <i class="fa fa-stop error new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}

?>
