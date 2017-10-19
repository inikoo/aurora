<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 22:40:52 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$website = get_object('Website', $object->get('Webpage Website Key'));


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


if ($new) {
    $can_update_code = true;

} else {
    $can_update_code = false;

}


$can_change_state = ($object->get('Webpage Scope') == 'System' ? false : true);
$can_delete       = false;


$content_data = $object->get('Content Data');


$object_fields = array();


if (in_array(
    $object->get('Webpage Scope'), array(
                                     'Category Categories',
                                     'Product',
                                     'Category Products'
                                 )
)) {

    $object_fields[] =

        array(
            'label' => _('Webpage state'),
            'class' => 'operations',

            'show_title' => true,
            'fields'     => array(


                array(
                    'id'        => 'launch_webpage',
                    'render'    => ($website->get('Website Status') == 'Active' and $object->get('Webpage State') == 'InProcess' ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc"  webpage_key="'.$object->id
                        .'" onClick="publish(this,\'publish_webpage\')" class="save changed valid">'._("Launch web page").' <i class="fa fa-rocket save changed valid"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(
                    'id'        => 'unpublish_webpage',
                    'render'    => ($website->get('Website Status') == 'Active' and $object->get('Webpage State') == 'Online' ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->id
                        .'" onClick="publish(this,\'unpublish_webpage\')" class="error button ">'._("Unpublish web page").' <i class="fa fa-rocket  fa-flip-vertical error button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),
                array(
                    'id'        => 'republish_webpage',
                    'render'    => ($website->get('Website Status') == 'Active' and $object->get('Webpage State') == 'Offline' ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->id.'" onClick="publish(this,\'publish_webpage\')" class=" button ">'._(
                            "Republish web page"
                        ).' <i class="fa fa-rocket   button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),



                array(
                    'id'        => 'set_as_ready_webpage',
                    'render'    => ($website->get('Website Status') == 'InProcess' and $object->get('Webpage State') != 'Ready' ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->id.'" onClick="publish(this,\'set_webpage_as_ready\')" class=" button ">'
                        ._(
                            "Set as ready"
                        ).' <i class="fa fa-check-circle padding_left_5  button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'


                ),


                array(
                    'id'        => 'launch_webpage',
                    'render'    => ($website->get('Website Status') == 'Active' and $object->get('Webpage State') == 'InProcess' ? true : false),
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc"  webpage_key="'.$object->id
                        .'" onClick="publish(this,\'publish_webpage\')" class="save changed valid">'._("Launch web page").' <i class="fa fa-rocket save changed valid"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(
                    'id'   => 'Webpage_Redirection_Code',
                    'edit' => ($edit ? 'string' : ''),
                    'render'    => ($website->get('Website Status') == 'Active' and $object->get('Webpage State') == 'Offline' ? true : false),

                'value'           => htmlspecialchars($object->get('Webpage Redirection Code')),
                'formatted_value' => $object->get('Webpage Redirection Code'),
                'label'           => ucfirst($object->get_field_label('Webpage Redirection Code')).' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, this is a 301 redirection, and misconfiguration will affect how google index this webpage').'" ></i>',
                'required'        => false,
                'type'            => 'value' ,
                'server_validation' => json_encode(array('tipo' => 'valid_redirection_webpage_code')),
                )


            )
        );

}

$object_fields[] = array(
    'label'      => _('Ids'),
    'show_title' => true,
    'fields'     => array(




        array(
            'id'     => 'Webpage_Code',
            'render' => ($can_update_code  ? true : false),
            'edit'   => ($edit ? 'string' : ''),
            'value'           => htmlspecialchars($object->get('Webpage Code')),
            'formatted_value' => $object->get('Code'),
            'label'           => ucfirst($object->get_field_label('Webpage Code')),
            'required'        => true,
            'type'            => 'value' ,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),



        ),
        array(
            'id'   => 'Webpage_Name',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($object->get('Webpage Name')),
            'formatted_value' => $object->get('Webpage Name'),
            'label'           => ucfirst($object->get_field_label('Webpage Name')),
            'required'        => true,
            'type'            => 'value' ,


        ),

        array(
            'id'   => 'Webpage_Browser_Title',
            'edit' => ($edit ? 'string' : ''),

            'value'           => htmlspecialchars($object->get('Webpage Browser Title')),
            'formatted_value' => $object->get('Webpage Browser Title'),
            'label'           => ucfirst($object->get_field_label('Webpage Browser Title')),
            'required'        => true,
            'type'            => 'value' ,


        ),

        array(
            'id'   => 'Webpage_Meta_Description',
            'edit' => ($edit ? 'textarea' : ''),

            'value'           => htmlspecialchars($object->get('Webpage Meta Description')),
            'formatted_value' => $object->get('Webpage Meta Description'),
            'label'           => ucfirst($object->get_field_label('Webpage Meta Description')),
            'required'        => false,
            'type'            => 'value' ,


        ),


    )
);


if (in_array(
    $object->get('Webpage Scope'), array(
                                     'HomepageToLaunch'
                                 )
)) {


    $object_fields[] = array(
        'label'      => _('Webpage settings'),
        'show_title' => true,
        'fields'     => array(
            array(
                'id'   => 'Webpage_Launching_Date',
                'edit' => 'date',

                'time'            => '00:00:00',
                'min_date'        => '1',
                'max_date'        => '100',
                'display_eraser'  => true,
                'value'           => $object->get('Webpage Launching Date'),
                'formatted_value' => $object->get('Launching Date'),

                'label'       => ucfirst($object->get_field_label('Launching date')),
                'invalid_msg' => get_invalid_message('string'),
            ),


        )
    );
}


if (in_array($object->get('Webpage Scope'), array('Contact'))) {

    $object_fields[] = array(
        'label'      => _('Contact'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_Company_Name',
                'value'       => $object->get('Store Company Name'),
                'label'       => _('Company Name'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_VAT_Number',
                'value'       => $object->get('Store VAT Number'),
                'label'       => _('VAT Number'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Store_Company_Number',
                'value'       => $object->get('Store Company Number'),
                'label'       => _('Company Number'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'        => ($edit ? 'email' : ''),
                'id'          => 'Store_Email',
                'value'       => $object->get('Store Email'),
                'label'       => _('Email'),
                'invalid_msg' => get_invalid_message('email'),
                'required'    => false,
                'type'        => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Store_Telephone',
                'value'           => $object->get('Store Telephone'),
                'formatted_value' => $object->get('Telephone'),
                'label'           => _('Telephone'),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Address',
                'value'           => $object->get('Store Address'),
                'formatted_value' => $object->get('Address'),
                'label'           => _('Address'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'textarea' : ''),
                'id'              => 'Store_Google_Map_URL',
                'value'           => $object->get('Store Google Map URL'),
                'formatted_value' => $object->get('Google Map URL'),
                'label'           => _('Google Map URL'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    );


}


if (in_array($object->get('Webpage Scope'), array('Category Categories'))) {

    $template_options = array(
        'categories_classic_showcase' => _('Responsive grid'),
        'categories_showcase'         => _('Fixed grid')
    );


    $object_fields[] = array(
        'label'      => _('Template'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit' => ($edit ? 'option' : ''),

                'id'              => 'Webpage_Template_Filename',
                'value'           => $object->get('Webpage Template Filename'),
                'formatted_value' => $object->get('Template Filename'),
                'options'         => $template_options,
                'label'           => _('Template'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),


        )
    );


}

if(!$new and $object->get('Webpage Scope')=='Category Products'){
    $export_operations      = array(
        'label'      => _('Export'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'export_webpage',
                'class'     => 'operation',
                'value'     => '',
                'label'     => sprintf('<span type="submit" class="button" file="/webpage_images.zip.php?parent=category&key=%d" onclick="window.open($(this).attr(\'file\'))"><i class="fa fa-file-archive-o" aria-hidden="true"></i> %s</span>',
                                       $object->get('Webpage Scope Key'),
                                       _('Images (including products)')),
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $export_operations;

}

if(!$new and $object->get('Webpage Scope')=='Product'){
$export_operations      = array(
    'label'      => _('Export'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(


        array(
            'id'        => 'export_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => sprintf('<span type="submit" class="button" file="/webpage_images.zip.php?parent=product&key=%d" onclick="window.open($(this).attr(\'file\'))"><i class="fa fa-file-archive-o" aria-hidden="true"></i> %s</span>',
                                   $object->get('Webpage Scope Key'),
                                   _('Images')),
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

$object_fields[] = $export_operations;

}


$operations      = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(


        array(
            'id'        => 'reset_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "webpage", "key":"'.$object->id
                .'"}\' onClick="reset_object(this)" class="delete_object disabled ">'._("Reset webpage").' <i class="fa fa-recycle  "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

if(!$new) {

    $object_fields[] = $operations;
}

/*

if (in_array(
    $object->get('Webpage Scope'), array(
                                     'Category Categories',
                                     'Product',
                                     'Category Products'
                                 )
)) {

    if (!$new and $can_delete) {
        $operations = array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'operations',
            'fields'     => array(

                array(
                    'id'        => 'delete_website',
                    'class'     => 'operation',
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'
                        .$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage")
                        .' <i class="fa fa-trash new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );

        $object_fields[] = $operations;
    }

} else {

    $operations      = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'reset_webpage',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "webpage", "key":"'
                    .$object->id.'"}\' onClick="reset_object(this)" class="delete_object disabled ">'._("Reset webpage").' <i class="fa fa-recycle  "></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );
    $object_fields[] = $operations;


}

*/

?>
