<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 June 2018 at 14:54:51 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$smarty->assign('email_template', $object);


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


if ($new) {

    $object_fields = array(
        array(
            'label'      => _('Name'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                => 'Email_Template_Name',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => '',
                    'formatted_value'   => '',
                    'label'             => ucfirst($object->get_field_label('Email Template Name')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'     => 'check_for_duplicates',
                            'metadata' => array('role' => 'Invite Mailshot')
                        )
                    ),

                    'type' => 'value'
                ),

                array(
                    'id'     => 'Email_Template_Role',
                    'render' => false,
                    'edit'   => 'string',
                    'value'  => 'Invite Mailshot',
                    'label'  => '',
                    'type'   => 'value'
                ),


            )
        ),

        array(
            'label'      => _("Email's content"),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'                => 'Email_Template_Subject',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => $object->get('Email Template Subject'),
                    'formatted_value'   => $object->get('Subject'),
                    'label'             => _("Subject"),
                    'required'          => true,


                    'type' => 'value'
                ),




            )
        ),


    );


} else {
    $object_fields = array(
        array(
            'label'      => _('Name'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                => 'Email_Template_Name',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => $object->get('Email Template Name'),
                    'formatted_value'   => $object->get('Name'),
                    'label'             => ucfirst($object->get_field_label('Email Template Name')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'     => 'check_for_duplicates',
                            'metadata' => array('role' => 'Invite Mailshot')
                        )
                    ),

                    'type' => 'value'
                ),




            )
        ),

        array(
            'label'      => _("Email's content"),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'                => 'Email_Template_Subject',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => $object->get('Email Template Subject'),
                    'formatted_value'   => $object->get('Subject'),
                    'label'             => _("Subject"),
                    'required'          => true,


                    'type' => 'value'
                ),



            )
        ),



    );



    if($object->get('Email Template Sent')==0 ){
        $object_fields[]= array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(

                array(

                    'id'        => 'activate_email_template',
                    'class'     => 'operation',
                    'render'=>($object->get('Email Template State')=='Active'?false:true),
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="activate_object(this)" class="delete_object disabled">'._('Reactivate').' <i class="fa fa-play success new_button "></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),
                array(

                    'id'        => 'suspend_email_template',
                    'class'     => 'operation',
                    'value'     => '',
                    'render'=>($object->get('Email Template State')=='Active'?true:false),

                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._('Suspend').' <i class="fa fa-stop error new_button "></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(

                    'id'        => 'delete_email_template',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                        .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._('Delete').' <i class="fa fa-trash-alt new_button "></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

            )

        );
    }



}


?>
