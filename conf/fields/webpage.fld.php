<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 22:40:52 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$can_update_code=false;

$can_change_state=($object->get('Webpage Scope')=='System'?false:true);
$can_delete=($object->get('Webpage Scope')=='System'?false:true);

$object_fields = array(






        array(
            'label' => _('Webpage state').' <span class="padding_left_10 Webpage_State_Edit_Label"><i class="fa fa-globe '.($object->get('Webpage State') == 'Online' ? 'success' : 'super_discreet')
                .'" aria-hidden="true"></i></span>',
            'class' => 'operations '.(!$can_change_state?'hide':''),
            'show_title' => true,
            'fields'     => array(


                array(
                    'id'        => 'launch_webpage',
                    'render'    => ( ($object->get('Webpage Launch Date') != '' or !$can_change_state ) ? false : true),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span webpage_key="'.$object->id.'" onClick="publish(this,\'publish_webpage\')" class="save changed valid">'._("Launch web page")
                        .' <i class="fa fa-rocket save changed valid"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(
                    'id'        => 'unpublish_webpage',
                    'render'    => (($object->get('Webpage Launch Date') == '' or $object->get('Webpage State') == 'Offline'  or !$can_change_state ) ? false : true),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span webpage_key="'.$object->id.'" onClick="publish(this,\'unpublish_webpage\')" class="error button ">'._("Unpublish web page")
                        .' <i class="fa fa-rocket  fa-flip-vertical error button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),
                array(
                    'id'        => 'republish_webpage',
                    'render'    => (($object->get('Webpage Launch Date') != '' and $object->get('Webpage State') == 'Offline') and $can_change_state ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span webpage_key="'.$object->id.'" onClick="publish(this,\'publish_webpage\')" class=" button ">'._("Republish web page")
                        .' <i class="fa fa-rocket   button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )
        ),



    array(
        'label'      => _('Ids'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'                => 'Webpage_Code',
                'render'=>($can_update_code?true:false),
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Webpage Code'),
                'label'             => ucfirst($object->get_field_label('Webpage Code')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
            ),

            array(
                'id'   => 'Webpage_Name',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Webpage Name')),
                'formatted_value' => $object->get('Webpage Name'),
                'label'           => ucfirst($object->get_field_label('Webpage Name')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Webpage_Browser_Title',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Webpage Browser Title')),
                'formatted_value' => $object->get('Webpage Browser Title'),
                'label'           => ucfirst($object->get_field_label('Webpage Browser Title')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Webpage_Meta_Description',
                'edit' => ($edit ? 'textarea' : ''),

                'value'           => htmlspecialchars($object->get('Webpage Meta Description')),
                'formatted_value' => $object->get('Webpage Meta Description'),
                'label'           => ucfirst($object->get_field_label('Webpage Meta Description')),
                'required'        => true,
                'type'            => ''


            ),




        )
    ),


);

if (!$new   and $can_delete) {
    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_website',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage version").' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
