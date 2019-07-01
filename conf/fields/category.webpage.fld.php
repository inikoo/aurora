<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2016 at 13:23:45 GMT+8, Kuta, Bali

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$object->get_webpage();
$website = get_object('Website', $object->webpage->get('Webpage Website Key'));


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$can_delete=false;


if($object->get('Webpage Scope')=='Category Products'){
    $category = get_object('Category', $object->get('Webpage Scope Key'));

    if(!$category->id){
        $can_delete=true;
    }

}



$subject_render = false;

if ($object->get('Category Subject') == 'Category') {
    $template_options = array(
        'blank'               => _('White canvas'),
        'categories_showcase' => _('Categories showcase')
    );

} else {
    $template_options = array(
        'blank'             => _('White canvas'),
        'family_buttons'    => 'Old products showcase',
        'products_showcase' => _('Products showcase')
    );

}

asort($template_options);


$subject_options         = array(
    'Product'  => _('Products'),
    'Category' => _('Categories')
);
$subject_value           = 'Product';
$subject_formatted_value = _('Products');
$subject_render          = true;





$category_fields=array();


if($object->get('Product Category Public')=='Yes'){
    $category_fields[]=array(
        'label' => _('Webpage state'),
        'class' => 'operations',

        'show_title' => true,
        'fields'     => array(


            array(
                'id'        => 'launch_webpage',
                'render'    => ($website->get('Website Status') == 'Active' and $object->webpage->get('Webpage State') == 'InProcess' ? true : false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc"  webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'publish_webpage\')" class="save changed valid">'._("Launch web page")
                    .' <i class="fa fa-rocket save changed valid"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'unpublish_webpage',
                'render'    => ($website->get('Website Status') == 'Active' and $object->webpage->get('Webpage State') == 'Online' ? true : false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'unpublish_webpage\')" class="error button ">'._("Unpublish web page")
                    .' <i class="fa fa-rocket  fa-flip-vertical error button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            array(
                'id'        => 'republish_webpage',
                'render'    => ($website->get('Website Status') == 'Active' and $object->webpage->get('Webpage State') == 'Offline' ? true : false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'publish_webpage\')" class=" button ">'._("Republish web page")
                    .' <i class="fa fa-rocket   button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


            array(
                'id'        => 'set_as_not_ready_webpage',
                'render'    => ($website->get('Website Status') == 'InProcess' and $object->webpage->get('Webpage State') == 'Ready' ? true : false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'set_webpage_as_not_ready\')" class="discreet button ">'._("Set as not ready")
                    .' <i class="fa fa-child button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            array(
                'id'        => 'set_as_ready_webpage',
                'render'    => ($website->get('Website Status') == 'InProcess' and $object->webpage->get('Webpage State') != 'Ready' ? true : false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'set_webpage_as_ready\')" class=" button ">'._("Set as ready")
                    .' <i class="fa fa-check-circle padding_left_5  button"></i></span>',
                'reference' => '',
                'type'      => 'operation'


            )
        )
    );
}



    $category_fields[]=array(
        'label'      => _('Webpage'),
        'show_title' => true,
        'fields'     => array(

            /*
                        array(
                            'id'              => 'Webpage_URLs',
                            'edit'            => 'Webpage_URLs',
                            'value'           => '',
                            'formatted_value' => $object->get('Webpage URLs'),
                            'label'           => _('URLs'),
                            'required'        => false,
                            'type'            => ''
                        ),

            */


            array(
                'id'   => 'Category_Webpage_Name',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Category Webpage Name')),
                'formatted_value' => $object->get('Webpage Name'),
                'label'           => ucfirst($object->get_field_label('Category Webpage Name')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Category_Webpage_Browser_Title',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Category Webpage Browser Title')),
                'formatted_value' => $object->get('Webpage Browser Title'),
                'label'           => ucfirst($object->get_field_label('Category Webpage Browser Title')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Category_Webpage_Meta_Description',
                'edit' => ($edit ? 'textarea' : ''),

                'value'           => htmlspecialchars($object->get('Webpage Meta Description')),
                'formatted_value' => $object->get('Webpage Meta Description'),
                'label'           => ucfirst($object->get_field_label('Webpage Meta Description')),
                'required'        => true,
                'type'            => ''


            ),




        )
    );




$export_operations = array(
    'label'      => _('Export'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(


        array(
            'id'        => 'export_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => sprintf(
                '
<span type="submit" class="button" file="/webpage_images.zip.php?parent=category&key=%d" onclick="window.open($(this).attr(\'file\'))"><i class="fa fa-file-archive" aria-hidden="true"></i> %s</span>
<span type="submit" class="padding_left_30 button" file="/webpage_texts.txt.php?parent=category&key=%d" onclick="window.open($(this).attr(\'file\'))"><i class="fal fa-file-alt" aria-hidden="true"></i> %s</span>


', $object->id, _('Images (including products)'), $object->id, _('Text (including products)')
            ),
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

$category_fields[] = $export_operations;


$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(

        array(
            'id'        => 'reindex_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "old_page", "key":"'.$object->webpage->id
                .'"}\' onClick="reindex_object(this)" class="delete_object disabled ">'._("Reindex category & products webpages").' <i class="fa fa-indent  "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),
        array(
            'id'        => 'reset_webpage',
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "old_page", "key":"'.$object->webpage->id
                .'"}\' onClick="reset_object(this)" class="delete_object disabled ">'._("Reset webpage").' <i class="fa fa-recycle  "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

        array(
            'id'        => 'delete_website',
            'render'=>$can_delete,
            'class'     => 'operation',
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'
                .$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete webpage")
                .' <i class="far fa-trash-alt new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

    )

);

$category_fields[] = $operations;


?>
