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


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'                => 'Website_Code',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Code'),
                'label'             => ucfirst($object->get_field_label('Code')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value'
            ),
            array(
                'id'                => 'Website_Name',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Name'),
                'label'             => ucfirst($object->get_field_label('Name')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value',

            ),
            array(
                'id'                => 'Website_URL',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website URL'),
                'label'             => ucfirst($object->get_field_label('URL')),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value',
                'placeholder'              => 'www.exmaple.com',

            ),

        )
    ),

    array(
        'label'      => _('Look & feel'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'                => 'Website_Palette',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Palette'),
                'formatted_value'             => $object->get('Palette'),
                'label'             => ucfirst($object->get_field_label('Palette')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'type'              => 'value',

            ),

            array(
                'id'                => 'Website_Primary_Color',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Primary Color'),
                'label'             => ucfirst($object->get_field_label('Website Primary Color')),
                'invalid_msg'       => get_invalid_message('color'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Website_Secondary_Color',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Secondary Color'),
                'label'             => ucfirst($object->get_field_label('Website Secondary Color')),
                'invalid_msg'       => get_invalid_message('color'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Website_Accent_Color',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Accent Color'),
                'label'             => ucfirst($object->get_field_label('Website Accent Color')),
                'invalid_msg'       => get_invalid_message('color'),
                'required'          => true,
                'type'              => 'value'
            ),

            array(
                'id'                => 'Website_Title_Font',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Title Font'),
                'label'             => ucfirst($object->get_field_label('Title Font')),
                'required'          => true,
                'type'              => 'value',

            ),
            array(
                'id'                => 'Website_Text_Font',
                'edit'              => ($edit ? 'string' : ''),
                'value'             => $object->get('Website Text Font'),
                'label'             => ucfirst($object->get_field_label('Text Font')),
                'required'          => true,
                'type'              => 'value',

            ),

        )
    ),






);

if (!$new) {


/*
    $object_fields[] =  array(
        'label'      => _('Notify me'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'     => 'custom',
                'id'       => 'Browser_Notifications',
                'value'    =>'',
                'formatted_value'    => '<span class="marked_link">'._('Enable this browser notifications').'</span>',
                'label'    => _('Browser notifications'),
                'required' => false,



            ),

            array(
                'render'            => true,
                'id'                => 'User_Password_Recovery_Email',
                'edit'              => 'email',
                'value'             => $user->get('User Password Recovery Email'),
                'formatted_value'   => $user->get('User Password Recovery Email'),
                'label'             => ucfirst(
                    $user->get_field_label('User Password Recovery Email')
                ),
                'server_validation' => json_encode(
                    array(
                        'tipo'       => 'check_for_duplicates',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'object'     => 'User',
                        'key'        => $user->id
                    )
                ),
                'invalid_msg'       => get_invalid_message('email'),
                'type'              => 'value'

            ),

            array(
                'edit'     => 'custom',
                'id'       => 'Notify_Customer_Registration',
                'value'    =>$object->get('Notify_Customer_Registration',$user),
                'formatted_value'    =>'<span style="margin-right:40px" ><i class="button fa '.($object->get('Notify_Customer_Registration',$user)=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> '._('Browser notification').'</span> <i class="button fa '.($object->get('Notify_Customer_Registration',$user)=='Yes'?'fa-toggle-on':'fa-toggle-off').'"  aria-hidden="true"></i> '._('Email')  ,
                'label'    => _('when customer register'),
                'required' => false,



            ),
            array(
                'edit'     => 'custom',
                'id'       => 'Notify_Customer_Registration',
                'value'    => $object->get('Notify_Customer_Registration',$user),
                'label'    => _('when customer place order'),
                'required' => false,



            ),



        )
    );
*/

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
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete website & all webpages")
                    .' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


?>
