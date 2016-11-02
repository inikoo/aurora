<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2016 at 14:48:23 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$public_options = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($public_options);


$category_product_fields = array(
    array(
        'label'      => _('Visibility'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'            => 'option',
                'id'              => 'Product_Category_Public',
                'options'         => $public_options,
                'value'           => $object->get('Product Category Public'),
                'formatted_value' => $object->get('Public'),
                'label'           => _('Show in website'),
                'type'            => 'value'
            ),


        )
    ),


    array(
        'label'      => _('Webpage').' <span class="no_title">'.$object->webpage->get('Code').'</span>',
        'show_title' => true,
        'class'      => ($object->get('Product Category Public') == 'Yes' ? '' : 'hide'),
        'fields'     => array(


            array(
                'id'                       => 'Category_Website_Node_Parent_Key',
                'render'                   => ($object->get(
                    'Product Category Public'
                ) == 'Yes' ? true : false),
                'edit'                     => 'dropdown_select',
                'scope'                    => 'web_node',
                'parent'                   => 'website',
                'parent_key'               => ($new
                    ?: $object->webpage->get(
                        'Page Site Key'
                    )),
                'value'                    => htmlspecialchars(
                    $object->webpage->get('Page Found In Page Key')
                ),
                'formatted_value'          => $object->webpage->get(
                    'Found In Page Key'
                ),
                'stripped_formatted_value' => '',
                'label'                    => _('Found in'),
                'required'                 => true,
                'type'                     => ''


            ),

            array(
                'id'     => 'Category_Webpage_Name',
                'render' => ($object->get('Product Category Public') == 'Yes' ? true : false),

                'edit' => ($edit ? 'string' : ''),

                'value'           => htmlspecialchars(
                    $object->get('Category Webpage Name')
                ),
                'formatted_value' => $object->get('Webpage Name'),
                'label'           => ucfirst(
                    $object->get_field_label('Category Webpage Name')
                ),
                'required'        => true,
                'type'            => ''


            ),

            array(
                'id'     => 'Product_Category_Description',
                'render' => ($object->get('Product Category Public') == 'Yes' ? true : false),

                'edit'            => ($edit ? 'editor' : ''),
                'class'           => 'editor',
                'editor_data'     => array(
                    'id'      => 'Product_Category_Description',
                    'content' => $object->get('Product Category Description'),

                    'data' => base64_encode(
                        json_encode(
                            array(
                                'mode'     => 'edit_object',
                                'field'    => 'Product_Category_Description',
                                'plugins'  => array(
                                    'paragraphStyle',
                                    'paragraphFormat',
                                    'fontFamily',
                                    'fontSize',
                                    'colors',
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
                                    'object' => 'Category',
                                    'key'    => $object->id,
                                    'field'  => 'Product Category Description',


                                )
                            )
                        )
                    )

                ),
                'value'           => $object->get(
                    'Product Category Description'
                ),
                'formatted_value' => $object->get(
                    'Product Category Description'
                ),
                'label'           => ucfirst(
                    $object->get_field_label('Product Category Description')
                ),
                'required'        => false,
                'type'            => 'value'
            ),


            array(
                'id'              => 'Webpage_Related_Products',
                'edit'            => 'webpage_related_products',
                'value'           => '',
                'formatted_value' => $object->get('Webpage Related Products'),
                'label'           => _('Related products links'),
                'required'        => false,
                'type'            => ''
            ),
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

?>
