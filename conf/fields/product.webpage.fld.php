<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 13:54:31 CET, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$object->get_webpage();


$website=get_object('Website',$object->webpage->get('Webpage Website Key'));

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}






$subject_render = false;

 $template_options = array(
        'product'    => _('Product')
    );

asort($template_options);





$product_fields = array(

    array(
        'label'      => _('Webpage state'),
        'class'      => 'operations',

        'show_title' => true,
        'fields'     => array(


            array(
                'id'        => 'launch_webpage',
                'render'=>( $website->get('Website Status')=='Active' and $object->webpage->get('Webpage State')=='InProcess' ?true:false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc"  webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'publish_webpage\')" class="save changed valid">'._("Launch web page").' <i class="fa fa-rocket save changed valid"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'unpublish_webpage',
                'render'=>(  $website->get('Website Status')=='Active' and   $object->webpage->get('Webpage State')=='Online'  ?true:false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'unpublish_webpage\')" class="error button ">'._("Unpublish web page").' <i class="fa fa-rocket  fa-flip-vertical error button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            array(
                'id'        => 'republish_webpage',
                'render'=>(  $website->get('Website Status')=='Active' and   $object->webpage->get('Webpage State')=='Offline'  ?true:false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'publish_webpage\')" class=" button ">'._("Republish web page").' <i class="fa fa-rocket   button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


            array(
                'id'        => 'set_as_not_ready_webpage',
                'render'=>(  $website->get('Website Status')=='InProcess' and  $object->webpage->get('Webpage State')=='Ready'  ?true:false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => ' <span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'set_webpage_as_not_ready\')" class="discreet button ">'._("Set as not ready").' <i class="fa fa-child button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),
            array(
                'id'        => 'set_as_ready_webpage',
                'render'=>(  $website->get('Website Status')=='InProcess' and  $object->webpage->get('Webpage State')!='Ready'  ?true:false),
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<span style="margin:10px 0px;padding:10px;border:1px solid #ccc" webpage_key="'.$object->webpage->id.'" onClick="publish(this,\'set_webpage_as_ready\')" class=" button ">'._("Set as ready").' <i class="fa fa-check-circle padding_left_5  button"></i></span>',
                'reference' => '',
                'type'      => 'operation'



        )
        )
    ),


    array(
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
                'id'   => 'Product_Webpage_Name',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Product Webpage Name')),
                'formatted_value' => $object->get('Webpage Name'),
                'label'           => ucfirst($object->get_field_label('Product Webpage Name')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Product_Webpage_Browser_Title',
                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars($object->get('Product Webpage Browser Title')),
                'formatted_value' => $object->get('Webpage Browser Title'),
                'label'           => ucfirst($object->get_field_label('Product Webpage Browser Title')),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'   => 'Product_Webpage_Meta_Description',
                'edit' => ($edit ? 'textarea' : ''),

                'value'           => htmlspecialchars($object->get('Product Webpage Meta Description')),
                'formatted_value' => $object->get('Webpage Meta Description'),
                'label'           => ucfirst($object->get_field_label('Product Webpage Meta Description')),
                'required'        => true,
                'type'            => ''


            ),



            /*
                        array(
                            'id'              => 'Webpage_Versions',
                            'edit'            => 'Webpage_Versions',
                            'value'           => '',
                            'formatted_value' => $object->get('Webpage Versions'),
                            'label'           => _('Versions'),
                            'required'        => false,
                            'type'            => ''
                        ),
            */

        )
    ),


);
/*
$template_field = array(





    array(
        'label'      => _('Template'),
        'show_title' => true,
        'fields'     => array(



            array(
                'edit' => ($edit ? 'option' : ''),

                'id'              => 'Webpage_Template',
                'value'           => $object->get('Product Webpage Template'),
                'formatted_value' => $object->get('Webpage Template'),
                'options'         => $template_options,
                'label'           => _('Template'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),






        )
    ),


);
*/
$template_fields = array(






    array(
        'label'      => _('Template settings'),
        'show_title' => true,
        'fields'     => array(







            array(
                'id'              => 'Webpage_See_Also',
                'edit'            => 'webpage_see_also',
                'value'           => '',
                'formatted_value' => $object->get('Webpage See Also'),
                'label'           => _('See also links'),
                'required'        => false,
                'type'            => ''
            ),


        )
    ),


);

$product_fields = array_merge(
    $product_fields,
    //$template_field,
    $template_fields
);







?>
