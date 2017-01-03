<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 December 2016 at 13:54:31 CET, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$object->get_webpage();




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
                'id'                       => 'Product_Website_Node_Parent_Key',
                'edit'                     => 'dropdown_select',
                'scope'                    => 'web_node',
                'parent'                   => 'website',
                'parent_key'               => ($new ?: $object->webpage->get('Page Site Key')),
                'value'                    => htmlspecialchars($object->webpage->get('Page Found In Page Key')),
                'formatted_value'          => $object->webpage->get('Found In Page Key'),
                'stripped_formatted_value' => '',
                'label'                    => _('Found in'),
                'required'                 => true,
                'type'                     => ''


            ),

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
    $product_fields, $template_field,$template_fields
);







?>
