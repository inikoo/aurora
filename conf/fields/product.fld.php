<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2016 at 15:01:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_product_variant_fields(Product $product, User $user, PDO $db, $options): array
{

    // THIS ONLY USE FOR NEW
    include_once 'utils/static_data.php';
    include_once 'utils/country_functions.php';


    $options['store_key'] = $options['parent_product']->get('Product Store Key');


    $edit = $user->can_edit('stores');


    if ($user->can_edit('parts')) {
        $part_edit = true;
    } else {
        $part_edit = false;
    }

    $options_Packing_Group = array(
        'None' => _('None'),
        'I'    => 'I',
        'II'   => 'II',
        'III'  => 'III'
    );

    $options_status            = array(
        'Active'       => _('Active'),
        'Suspended'    => _('Suspended'),
        'Discontinued' => _('Discontinued')
    );
    $options_web_configuration = array(
        'Online Auto'               => _('Automatic'),
        //	'Online Force For Sale'=>_('For sale').' <i class="fa fa-thumbtack" aria-hidden="true"></i>' ,
        'Online Force Out of Stock' => _('Out of stock').' <i class="fa fa-thumbtack" aria-hidden="true"></i>',
        'Offline'                   => _('Offline')
    );


    //$parts_data    = $product->get_parts_data();
    //$number_parts  = count($parts_data);
    /*
    $linked_fields = array();
    if ($number_parts == 1 and isset($parts_data[0]['Linked Fields']) and is_array($parts_data[0]['Linked Fields'])) {
        $linked_fields = array_flip($parts_data[0]['Linked Fields']);
    }
*/
    if (count($product->get_parts()) == 1) {
        $fields_linked = true;
    } else {
        $fields_linked = false;
    }

    if (isset($options['new']) and $options['new']) {
        $new = true;
    } else {
        $new = false;
    }


    $options_Unit_Type = array(
        'Piece' => _('Piece'),
        'Gram'  => _('Gram'),
        'Liter' => _('Liter')
    );
    asort($options_Unit_Type);

    /*
        if ($product->get('Product Family Category Key')) {
            include_once 'class.Category.php';
            $family       = new Category($product->get('Product Family Category Key'));
            $family_label = $family->get('Code').', '.$family->get('Label');
        } else {
            $family_label = '';
        }
    */

    //  $linked_fields = $product->get_linked_fields_data();
    $product_fields = array(


        array(
            'label'      => _('Status'),
            'show_title' => true,
            'class'      => ($new ? 'hide' : ''),
            'fields'     => array(
                array(
                    'render' => !$new,
                    'id'     => 'Product_Status',
                    'edit'   => ($edit ? 'option' : ''),

                    'options'         => $options_status,
                    'value'           => htmlspecialchars($product->get('Product Status')),
                    'formatted_value' => $product->get('Status'),
                    'label'           => ucfirst($product->get_field_label('Product Status')),
                    'required'        => !$new,
                    'type'            => 'skip'
                ),
                array(
                    'render' => (!$new && $product->get('Product Status') == 'Active'),
                    'id'     => 'Product_Web_Configuration',
                    'edit'   => ($edit ? 'option' : ''),

                    'options'         => $options_web_configuration,
                    'value'           => htmlspecialchars($product->get('Product Web Configuration')),
                    'formatted_value' => $product->get('Web Configuration'),
                    'label'           => ucfirst($product->get_field_label('Web Configuration')),
                    'required'        => !$new,
                    'type'            => 'skip'
                ),
            )
        ),


        array(
            'label'      => _('Id'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(

                array(
                    'id'                => 'Product_Code',
                    'class'             => 'product_field  '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($product->get('Product Code')),
                    'formatted_value'   => $product->get('Code'),
                    'label'             => _('Variant suffix code'),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'parent'     => 'store',
                            'parent_key' => ($new ? $options['store_key'] : $product->get('Product Store Key')),
                            'object'     => 'Product',
                            'key'        => $product->id
                        )
                    ),
                    'type'              => 'value'
                ),

                array(
                    'id'              => 'Product_Name',
                    'class'           => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($options['parent_product']->get('Product Name')),
                    'formatted_value' => $product->get('Name'),
                    'label'           => _('Variant full name'),
                    'required'        => true,
                    'type'            => 'value'


                ),
                array(
                    'id'              => 'Product_Variant_Short_Name',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('has_variants') == 'Yes'   or $product->get('is_variant') == 'No' ) )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($product->get('Product Variant Short Name')),
                    'formatted_value' => $product->get('Variant Short Name'),
                    'label'           => ucfirst($product->get_field_label('Product Variant Short Name')),
                    'required'        => true,
                    'type'            => 'value'


                ),


            )
        ),
        array(
            'label'      => _('Parts'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'              => 'Product_Parts',
                    'class'           => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'            => 'parts_list',
                    'value'           => $product->get('Product Parts'),
                    'formatted_value' => $product->get('Parts'),
                    'label'           => ucfirst($product->get_field_label('Product Parts')),
                    'required'        => false,
                    'type'            => 'value'
                )

            )
        ),


        array(
            'label'      => _('Outer'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(

                array(
                    'render' => true,
                    'id'     => 'Product_Units_Per_Case',
                    'class'  => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'edit'            => ($edit ? 'numeric' : ''),
                    'value'           => $product->get('Product Units Per Case'),
                    'formatted_value' => $product->get('Units Per Case'),
                    'label'           => ucfirst($product->get_field_label('Product Units Per Case')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => true,
                    'type'            => 'value'
                ),


                array(
                    'id'    => 'Product_Price',
                    'class' => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'edit'            => ($edit ? 'amount' : ''),
                    'value'           => $product->get('Product Price'),
                    'formatted_value' => $product->get('Price'),
                    'label'           => ucfirst($product->get_field_label('Product Price')),
                    'invalid_msg'     => get_invalid_message('amount'),
                    'required'        => true,
                    'type'            => 'value'
                ),


            )
        ),


    );


    return $product_fields;
}


function get_product_fields(Product $product, User $user, PDO $db, $options): array
{
    include_once 'utils/static_data.php';
    include_once 'utils/country_functions.php';


    $edit = $user->can_edit('stores');


    if ($user->can_edit('parts')) {
        $part_edit = true;
    } else {
        $part_edit = false;
    }

    $options_Packing_Group = array(
        'None' => _('None'),
        'I'    => 'I',
        'II'   => 'II',
        'III'  => 'III'
    );

    $options_status            = array(
        'Active'       => _('Active'),
        'Suspended'    => _('Suspended'),
        'Discontinued' => _('Discontinued')
    );
    $options_web_configuration = array(
        'Online Auto'               => _('Automatic'),
        //	'Online Force For Sale'=>_('For sale').' <i class="fa fa-thumbtack" aria-hidden="true"></i>' ,
        'Online Force Out of Stock' => _('Out of stock').' <i class="fa fa-thumbtack" aria-hidden="true"></i>',
        'Offline'                   => _('Offline')
    );


    //$parts_data    = $product->get_parts_data();
    //$number_parts  = count($parts_data);
    /*
    $linked_fields = array();
    if ($number_parts == 1 and isset($parts_data[0]['Linked Fields']) and is_array($parts_data[0]['Linked Fields'])) {
        $linked_fields = array_flip($parts_data[0]['Linked Fields']);
    }
*/


    $options_pricing_policy=[
        0=>_('No policy')
    ];

    $sql="select `Product Pricing Policy Key`,`Product Pricing Policy Label` from `Product Pricing Policy Dimension`";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [

        ]
    );
    while ($row = $stmt->fetch()) {
        $options_pricing_policy[$row['Product Pricing Policy Key']]=$row['Product Pricing Policy Label'];
    }



    if (count($product->get_parts()) == 1) {
        $fields_linked = true;
    } else {
        $fields_linked = false;
    }

    if (isset($options['new']) and $options['new']) {
        $new = true;
    } else {
        $new = false;
    }


    $options_Unit_Type = array(
        'Piece' => _('Piece'),
        'Gram'  => _('Gram'),
        'Liter' => _('Liter')
    );
    asort($options_Unit_Type);

    /*
        if ($product->get('Product Family Category Key')) {
            include_once 'class.Category.php';
            $family       = new Category($product->get('Product Family Category Key'));
            $family_label = $family->get('Code').', '.$family->get('Label');
        } else {
            $family_label = '';
        }
    */

    //  $linked_fields = $product->get_linked_fields_data();
    $product_fields = array(

        array(
            'label'      => _('Type'),
            'show_title' => true,
            'class'      => ($new ? '' : 'hide'),
            'fields'     => array(
                array(
                    'render' => $new,
                    'id'     => 'Product_Type',
                    'edit'   => ($edit ? 'no_icon' : ''),

                    'value'           => 'Product',
                    'formatted_value' => '<input id="Product_Type" value="Product" type="hidden"/><div id="product_type_options"><span data-value="Product" class="product_type_option padding_right_20"><i class="far fa-dot-circle"></i> '._('Product').'</span>
                                                <span data-value="Service" class="product_type_option discreet_on_hover button"><i class="far fa-circle"></i> '._('Service').'</span></div>',
                    'label'           => ucfirst($product->get_field_label('Product Type')),
                    'required'        => false,
                    'type'            => 'value'

                ),

            )
        ),

        array(
            'label'      => _('Status'),
            'show_title' => true,
            'class'      => ($new ? 'hide' : ''),
            'fields'     => array(
                array(
                    'render' => !$new,
                    'id'     => 'Product_Status',
                    'edit'   => ($edit ? 'option' : ''),

                    'options'         => $options_status,
                    'value'           => htmlspecialchars($product->get('Product Status')),
                    'formatted_value' => $product->get('Status'),
                    'label'           => ucfirst($product->get_field_label('Product Status')),
                    'required'        => !$new,
                    'type'            => 'skip'
                ),
                array(
                    'render' => (!$new && $product->get('Product Status') == 'Active'),
                    'id'     => 'Product_Web_Configuration',
                    'edit'   => ($edit ? 'option' : ''),

                    'options'         => $options_web_configuration,
                    'value'           => htmlspecialchars($product->get('Product Web Configuration')),
                    'formatted_value' => $product->get('Web Configuration'),
                    'label'           => ucfirst($product->get_field_label('Web Configuration')),
                    'required'        => !$new,
                    'type'            => 'skip'
                ),
            )
        ),


        array(
            'label'      => _('Service'),
            'show_title' => true,
            'class'      => 'service_field '.(($new or $product->get('Product Type') == 'Product') ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'                => 'Service_Code',
                    'edit'              => ($edit ? 'string' : ''),
                    'class'             => 'service_field '.(($new or $product->get('Product Type') == 'Product') ? 'hide' : ''),
                    'value'             => htmlspecialchars($product->get('Product Code')),
                    'formatted_value'   => $product->get('Code'),
                    'label'             => ucfirst($product->get_field_label('Product Code')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'field'      => 'Product_Code',
                            'parent'     => 'store',
                            'parent_key' => ($new ? $options['store_key'] : $product->get('Product Store Key')),
                            'object'     => 'Product',
                            'key'        => $product->id
                        )
                    ),
                    'type'              => 'value skip'
                ),
                array(
                    'id'              => 'Service_Name',
                    'edit'            => ($edit ? 'string' : ''),
                    'class'           => 'service_field '.(($new or $product->get('Product Type') == 'Product') ? 'hide' : ''),
                    'value'           => htmlspecialchars($product->get('Product Name')),
                    'formatted_value' => $product->get('Name'),
                    'label'           => _('Description'),
                    'required'        => true,
                    'type'            => 'value skip'


                ),

                array(
                    'id'              => 'Service_Price',
                    'edit'            => ($edit ? 'amount' : ''),
                    'class'           => 'service_field '.(($new or $product->get('Product Type') == 'Product') ? 'hide' : ''),
                    'value'           => $product->get('Product Price'),
                    'formatted_value' => $product->get('Price'),
                    'label'           => _('Unit price'),
                    'invalid_msg'     => get_invalid_message('amount'),
                    'required'        => true,
                    'type'            => 'value skip'
                ),
                array(
                    'id'              => 'Service_Unit_Label',
                    'class'           => 'service_field '.(($new or $product->get('Product Type') == 'Product') ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => ($new ? '' : $product->get('Product Unit Label')),
                    'formatted_value' => ($new ? '' : $product->get('Unit Label')),
                    'label'           => ucfirst($product->get_field_label('Product Unit Label')),
                    'required'        => true,
                    'type'            => 'value skip'
                ),
            )
        ),


        array(
            'label'      => _('Id'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(

                array(
                    'id'                => 'Product_Code',
                    'class'             => 'product_field  '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($product->get('Product Code')),
                    'formatted_value'   => $product->get('Code'),
                    'label'             => ucfirst($product->get_field_label('Product Code')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'parent'     => 'store',
                            'parent_key' => ($new ? $options['store_key'] : $product->get('Product Store Key')),
                            'object'     => 'Product',
                            'key'        => $product->id
                        )
                    ),
                    'type'              => 'value'
                ),

                array(
                    'id'              => 'Product_CPNP_Number',
                    'edit'            => ($part_edit ? 'string' : ''),
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('is_variant') == 'Yes' )  )    ? 'hide' : ''),
                    'render'          => !$new,
                    'value'           => $product->get('Product CPNP Number'),
                    'formatted_value' => $product->get('CPNP Number'),
                    'label'           => ucfirst($product->get_field_label('Product CPNP Number')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_UFI',
                    'edit'            => ($part_edit ? 'string' : ''),
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('is_variant') == 'Yes' )  )    ? 'hide' : ''),
                    'render'          => !$new,
                    'value'           => $product->get('Product UFI'),
                    'formatted_value' => $product->get('UFI'),
                    'label'           => ucfirst(
                            $product->get_field_label('Product UFI')
                        ).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._(
                                'Linked to part value'
                            ).'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),


            )
        ),

        array(
            'label'      => _('Video (vimeo)'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'              => 'Product_Video',
                    'class'           => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'            => 'string',
                    'value'           => $product->get('Product Video'),
                    'formatted_value' => $product->get('Video'),
                    'label'           => ucfirst($product->get_field_label('Vimeo video link')),
                    'required'        => false,
                    'type'            => 'value'
                )

            )
        ),


        array(
            'label'      => _('Parts'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'              => 'Product_Parts',
                    'class'           => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
                    'edit'            => 'parts_list',
                    'value'           => $product->get('Product Parts'),
                    'formatted_value' => $product->get('Parts'),
                    'label'           => ucfirst($product->get_field_label('Product Parts')),
                    'required'        => false,
                    'type'            => 'value'
                )

            )
        ),

        array(
            'label'      => _('Family'),
            'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                       => 'Product_Family_Category_Key',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'                     => 'dropdown_select',
                    'scope'                    => 'families',
                    'parent'                   => 'store',
                    'parent_key'               => ($new ? $options['store_key'] : $product->get('Product Store Key')),
                    'value'                    => htmlspecialchars($product->get('Product Family Category Key')),
                    'formatted_value'          => $product->get('Family Category Key'),
                    'stripped_formatted_value' => '',
                    'label'                    => _('Family'),
                    'required'                 => true,
                    'type'                     => 'value'


                ),

                array(
                    'id'              => 'Product_Label_in_Family',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($product->get('Product Label in Family')),
                    'formatted_value' => $product->get('Label in Family'),
                    'label'           => ucfirst($product->get_field_label('Product Label in Family')),
                    'required'        => false,
                    'type'            => 'value'

                ),

            )
        ),


        array(
            'label'      => _('Outer'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(

                array(
                    'render' => true,
                    'id'     => 'Product_Units_Per_Case',
                    'class'  => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'edit'            => ($edit ? 'numeric' : ''),
                    'value'           => $product->get('Product Units Per Case'),
                    'formatted_value' => $product->get('Units Per Case'),
                    'label'           => ucfirst($product->get_field_label('Product Units Per Case')),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => true,
                    'type'            => 'value'
                ),

                array(
                    'render' => !$new  and count($options_pricing_policy)>1 ,
                    'id'     => 'Product_Pricing_Policy_Key',
                    'edit'   => ($edit ? 'option' : ''),

                    'options'         => $options_pricing_policy,
                    'value'           => htmlspecialchars($product->get('Product Pricing Policy Key')),
                    'formatted_value' => $product->get('Pricing Policy Key'),
                    'label'           => ucfirst($product->get_field_label('Product Pricing Policy Key')),
                    'required'        => !$new,
                    'type'            => 'skip'
                ),

                array(
                    'id'    => 'Product_Price',
                    'class' => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'edit'            => ($edit ? 'amount' : ''),
                    'value'           => $product->get('Product Price'),
                    'formatted_value' => $product->get('Price'),
                    'label'           => ucfirst($product->get_field_label('Product Price')),
                    'invalid_msg'     => get_invalid_message('amount'),
                    'required'        => true,
                    'type'            => 'value'
                ),


            )
        ),

        array(
            'label'      => _('Retail unit'),
            'show_title' => true,
            'class'      => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'              => 'Product_Unit_Label',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('is_variant') == 'Yes' )  )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => ($new ? _('piece') : $product->get('Product Unit Label')),
                    'formatted_value' => ($new ? _('piece') : $product->get('Unit Label')),
                    'label'           => ucfirst($product->get_field_label('Product Unit Label')),
                    'required'        => true,
                    'type'            => 'value'

                ),

                array(
                    'id'              => 'Product_Name',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' )  )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($product->get('Product Name')),
                    'formatted_value' => $product->get('Name'),
                    'label'           => ucfirst($product->get_field_label('Product Name')),
                    'required'        => true,
                    'type'            => 'value'


                ),
                array(
                    'id'              => 'Product_Variant_Short_Name',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('has_variants') == 'Yes'   or $product->get('is_variant') == 'No' ) )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($product->get('Product Variant Short Name')),
                    'formatted_value' => $product->get('Variant Short Name'),
                    'label'           => ucfirst($product->get_field_label('Product Variant Short Name')),
                    'required'        => true,
                    'type'            => 'value'


                ),

                array(
                    'id'              => 'Product_Brand',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' )  )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'string' : ''),
                    'value'           => htmlspecialchars($product->get('Product Brand')),
                    'formatted_value' => $product->get('Brand'),
                    'label'           => ucfirst($product->get_field_label('Product Brand')),
                    'required'        => false,
                    'type'            => 'value'


                ),

                array(
                    'id'              => 'Product_Unit_RRP',
                    'class'           => 'product_field  '.((!$new and  ( $product->get('Product Type') == 'Service' or $product->get('is_variant') == 'Yes' )  )    ? 'hide' : ''),
                    'edit'            => ($edit ? 'amount' : ''),
                    'value'           => $product->get('Product Unit RRP'),
                    'formatted_value' => $product->get('Unit RRP'),
                    'label'           => ucfirst($product->get_field_label('Product Unit RRP')),
                    'invalid_msg'     => get_invalid_message('amount'),
                    'required'        => false,
                    'type'            => 'value'
                ),

                array(
                    'id'    => 'Product_Unit_Weight',
                    'edit'  => ($part_edit ? 'numeric' : ''),
                    'class' => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'render'          => !$new,
                    'value'           => $product->get('Product Unit Weight'),
                    'formatted_value' => $product->get('Unit Weight'),
                    'label'           => ucfirst($product->get_field_label('Product Unit Weight')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('numeric'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'    => 'Product_Unit_Dimensions',
                    'edit'  => ($part_edit ? 'dimensions' : ''),
                    'class' => 'product_field '.((!$new and $product->get('Product Type') == 'Service') ? 'hide' : ''),

                    'render'          => !$new,
                    'value'           => $product->get('Unit Dimensions'),
                    'formatted_value' => $product->get('Unit Dimensions'),
                    'label'           => ucfirst($product->get_field_label('Product Unit Dimensions')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('numeric'),
                    'required'        => false,
                    'type'            => 'value'
                ),


            )
        ),

        array(
            'label'      => _('Customer'),
            'show_title' => true,
            'class'      => (($new  or $product->get('is_variant') == 'Yes')  ? 'hide' : ''),
            'fields'     => array(
                array(
                    'id'                       => 'Product_Customer_Key',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'                     => ($new ? '' : 'dropdown_select'),
                    'render'                   => !$new,
                    'scope'                    => 'customers',
                    'parent'                   => 'store',
                    'parent_key'               => ($new ? $options['store_key'] : $product->get('Product Store Key')),
                    'value'                    => $product->get('Product Customer Key'),
                    'formatted_value'          => $product->get('Customer Key'),
                    'stripped_formatted_value' => '',
                    'label'                    => _('Customer'),
                    'required'                 => true,
                    'type'                     => ''


                ),


            )
        ),


    );

    if ($product->get('Product Type') == 'Product') {
        $product_fields[] = array(
            'label'      => _('Properties'),
            'show_title' => true,
            'class'      => (($new  or $product->get('is_variant') == 'Yes')  ? 'hide' : ''),
            'fields'     => array(

                array(
                    'id'   => 'Product_Materials',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    //'linked'=>$fields_linked,
                    'edit' => ($part_edit ? 'textarea' : ''),

                    'render' => !$new,

                    'value'           => htmlspecialchars($product->get('Product Materials')),
                    'formatted_value' => $product->get('Materials'),
                    'label'           => ucfirst($product->get_field_label('Product Materials')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'                       => 'Product_Origin_Country_Code',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'                     => ($part_edit ? 'country_select' : ''),
                    'render'                   => !$new,
                    'options'                  => get_countries($db),
                    'scope'                    => 'countries',
                    'value'                    => strtolower(country_3alpha_to_2alpha($product->get('Product Origin Country Code'))),
                    'formatted_value'          => $product->get('Origin Country Code'),
                    'stripped_formatted_value' => ($product->get('Product Origin Country Code') != '' ? $product->get('Origin Country').' ('.$product->get('Product Origin Country Code').')' : ''),
                    'label'                    => ucfirst($product->get_field_label('Product Origin Country Code')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'                 => false,
                    //'type'=>'value'
                ),
                array(
                    'id'              => 'Product_Tariff_Code',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'numeric' : ''),
                    'render'          => !$new,
                    'value'           => $product->get('Product Tariff Code'),
                    'formatted_value' => $product->get('Tariff Code'),
                    'label'           => ucfirst($product->get_field_label('Product Tariff Code')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'

                ),
                array(
                    'id'              => 'Product_Duty_Rate',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'string' : ''),
                    'render'          => !$new,
                    'value'           => $product->get('Product Duty Rate'),
                    'formatted_value' => $product->get('Duty Rate'),
                    'label'           => ucfirst($product->get_field_label('Product Duty Rate')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_HTSUS_Code',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'numeric' : ''),
                    'render'          => !$new,
                    'value'           => $product->get('Product HTSUS Code'),
                    'formatted_value' => $product->get('HTSUS Code'),
                    'label'           => '<span title="Harmonized Tariff Schedule of the United States Code ">HTS US <img alt="us" src="/art/flags/us.png"/></span> '.($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'invalid_msg'     => get_invalid_message('string'),
                    'required'        => false,
                    'type'            => 'value'

                ),

            )
        );
        $product_fields[] = array(
            'label'      => _('Health & Safety'),
            'class'      => (($new  or $product->get('is_variant') == 'Yes')  ? 'hide' : ''),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'              => 'Product_UN_Number',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'string' : ''),
                    'render'          => !$new,
                    'value'           => htmlspecialchars($product->get('Product UN Number')),
                    'formatted_value' => $product->get('UN Number'),
                    'label'           => ucfirst($product->get_field_label('Product UN Number')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_UN_Class',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'string' : ''),
                    'render'          => !$new,
                    'value'           => htmlspecialchars($product->get('Product UN Class')),
                    'formatted_value' => $product->get('UN Class'),
                    'label'           => ucfirst($product->get_field_label('Product UN Class')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_Packing_Group',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'option' : ''),
                    'render'          => !$new,
                    'options'         => $options_Packing_Group,
                    'value'           => htmlspecialchars($product->get('Product Packing Group')),
                    'formatted_value' => $product->get('Packing Group'),
                    'label'           => ucfirst($product->get_field_label('Product Packing Group')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_Proper_Shipping_Name',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'string' : ''),
                    'render'          => !$new,
                    'value'           => htmlspecialchars($product->get('Product Proper Shipping Name')),
                    'formatted_value' => $product->get('Proper Shipping Name'),
                    'label'           => ucfirst($product->get_field_label('Product Proper Shipping Name')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_Hazard_Identification_Number',
                    'class'=>  $product->get('is_variant') == 'Yes' ?'hide':'',

                    'edit'            => ($part_edit ? 'string' : ''),
                    'render'          => !$new,
                    'value'           => htmlspecialchars($product->get('Product Hazard Identification Number')),
                    'formatted_value' => $product->get('Hazard Identification Number'),
                    'label'           => ucfirst($product->get_field_label('Product Hazard Identification Number')).($fields_linked ? ' <i  class="discreet fa fa-link"  title="'._('Linked to part value').'"></i>' : ''),
                    'required'        => false,
                    'type'            => 'value'
                )
            )


        );

        $product_fields[] = array(
            'label'      => 'GPSR (if empty will use Part GPSR)',
            'class'      => (($new  or $product->get('is_variant') == 'Yes')  ? 'hide' : ''),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Product_GPSR_Manufacturer',
                    'edit'            => ($edit ? 'textarea' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR Manufacturer')
                    ),
                    'formatted_value' => $product->get('GPSR Manufacturer'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR Manufacturer')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_GPSR_EU_Responsable',
                    'edit'            => ($edit ? 'textarea' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR EU Responsable')
                    ),
                    'formatted_value' => $product->get('GPSR EU Responsable'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR EU Responsable')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_GPSR_Warnings',
                    'edit'            => ($edit ? 'textarea' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR Warnings')
                    ),
                    'formatted_value' => $product->get('GPSR Warnings'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR Warnings')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_GPSR_Manual',
                    'edit'            => ($edit ? 'textarea' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR Manual')
                    ),
                    'formatted_value' => $product->get('GPSR Manual'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR Manual')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_GPSR_Class_Category_Danger',
                    'edit'            => ($edit ? 'textarea' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR Class Category Danger')
                    ),
                    'formatted_value' => $product->get('GPSR Class Category Danger'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR Class Category Danger')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'              => 'Product_GPSR_Languages',
                    'edit'            => ($edit ? 'string' : ''),
                    'right_code'      => 'PE',
                    'value'           => htmlspecialchars(
                        $product->get('Product GPSR Languages')
                    ),
                    'formatted_value' => $product->get('GPSR Languages'),
                    'label'           => ucfirst(
                        $product->get_field_label('Product GPSR Languages')
                    ),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Toxic',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Toxic'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Toxic"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Toxic') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Toxic') == 'Yes' ? 'discreet' : '').'">'._('Acute Toxicity').' <img src="https://aw.aurora.systems/art/pictograms/Toxic.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Acute Toxicity'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Corrosive',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Corrosive'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Corrosive"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Corrosive') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Corrosive') == 'Yes' ? 'discreet' : '').'">'._('Corrosive').' <img src="https://aw.aurora.systems/art/pictograms/Corrosive.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Corrosive'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Explosive',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Explosive'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Explosive"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Explosive') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Explosive') == 'Yes' ? 'discreet' : '').'">'._('Explosive').' <img src="https://aw.aurora.systems/art/pictograms/Explosive.jpg" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Explosive'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Flammable',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Flammable'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Flammable"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Flammable') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Flammable') == 'Yes' ? 'discreet' : '').'">'._('Flammable').' <img src="https://aw.aurora.systems/art/pictograms/Flammable.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Flammable'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Gas',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Gas'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Gas"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Gas') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Gas') == 'Yes' ? 'discreet' : '').'">'._('Gas under pressure').' <img src="https://aw.aurora.systems/art/pictograms/Gas.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Gas under pressure'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Environment',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Environment'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Environment"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Environment') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Environment') == 'Yes' ? 'discreet' : '').'">'._('Hazards to the environment').' <img src="https://aw.aurora.systems/art/pictograms/Environment.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Hazards to the environment'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Health',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Health'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Health"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Health') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Health') == 'Yes' ? 'discreet' : '').'">'._('Health hazard').' <img src="https://aw.aurora.systems/art/pictograms/Health.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Health hazard'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Oxidising',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Oxidising'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Oxidising"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Oxidising') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Oxidising') == 'Yes' ? 'discreet' : '').'">'._('Oxidising agent').' <img src="https://aw.aurora.systems/art/pictograms/Oxidising.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Oxidising'),
                    'required'        => false,
                    'type'            => 'value'
                ),
                array(
                    'id'     => 'Product_Pictogram_Danger',
                    'edit'   => 'no_icon',
                    'render' => true,
                    'value'           => $product->get('Product Pictogram Danger'),
                    'formatted_value' => '<span class="button" onclick="save_toggle_switch_product(this)"  field="Product_Pictogram_Danger"  style="margin-right:40px"><i class=" fa fa-fw '.($product->get('Product Pictogram Danger') == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off')
                        .'" aria-hidden="true"></i> <span class="'.($product->get('Product Pictogram Danger') == 'Yes' ? 'discreet' : '').'">'._('Serious health hazard').' <img src="https://aw.aurora.systems/art/pictograms/Danger.png" style="position:relative;top:5px;height: 24px"/></span></span>',
                    'label'           => _('Health hazard'),
                    'required'        => false,
                    'type'            => 'value'
                ),

            )


        );



    }

    return $product_fields;
}
