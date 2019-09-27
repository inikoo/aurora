<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 June 2018 at 19:48:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$metadata = $object->get('Metadata');

$smarty->assign('email_template', $object);

$object_fields[] = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'edit_field '.($object->get('Email Campaign Type Status') == 'InProcess' ? 'hide' : ''),
    'fields'     => array(

        array(

            'id'        => 'activate_email_template',
            'class'     => 'operation',
            'render'    => ($object->get('Email Campaign Type Status') == 'Suspended' ? true : false),
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="activate_object(this)" class="delete_object disabled">'._('Set as live').' <i class="fa fa-play success new_button "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),
        array(

            'id'     => 'suspend_email_template',
            'class'  => 'operation',
            'value'  => '',
            'render' => ($object->get('Email Campaign Type Status') == 'Active' ? true : false),

            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._('Suspend').' <i class="fa fa-stop error new_button "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);
