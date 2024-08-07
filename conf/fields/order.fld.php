<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

$customer = get_object('Customer', $object->get('Order Customer Key'));
$store    = get_object('Store', $object->get('Order Store Key'));

$options_valid_tax_number = array(
    'Yes'     => _('Valid'),
    'No'      => _('Not Valid'),
    'Unknown' => _('Unknown'),
    'Auto'    => _('Check online'),
);

$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);

$countries = get_countries($db);


if ($object->get('State Index') >= 30) {
    $render_invoicing_field = true;
    $invoicing_fields_class = '';
} else {
    $render_invoicing_field = false;
    $invoicing_fields_class = 'hide';

}

$order_source_options=[];

$sql     = "select * from `Order Source Dimension` where   `Order Source Store Key` is null or `Order Source Store Key`=? ";
$stmt    = $db->prepare($sql);
$stmt->execute(
    [
        $store->id
    ]
);
while ($row = $stmt->fetch()) {
    $order_source_options[$row['Order Source Key']]=$row['Order Source Name'];
}

$other_delivery_addresses_fields = array();


$other_delivery_addresses = $customer->get_addresses_data();

$smarty->assign('other_delivery_addresses', $other_delivery_addresses);

$number_other_delivery_address = count($other_delivery_addresses);


if ($store->get('Store Can Collect') == 'Yes') {
    $number_other_delivery_address++;
}

$smarty->assign('store',$store);
$smarty->assign('order', $object);


try {
    $other_delivery_addresses_fields_directory = $smarty->fetch('order_delivery_addresses_directory.tpl');
} catch (Exception $e) {
    $other_delivery_addresses_fields_directory = '';
}

$object_fields = array(

    array(
        'label'      => _('Header'),
        'show_title' => true,
        'fields'     => array(

            /*
                        array(
                            'id'    => 'Order_Customer_Name',
                            'edit'            => ($edit ? 'string' : ''),
                            'value' => $object->get('Order Customer Name'),
                            'label' => _('Customer name'),
                            'required'        => false
                        ),

                        array(
                            'id'    => 'Order_Telephone',
                            'edit'            => ($edit ? 'string' : ''),
                            'value' => $object->get('Order Telephone'),
                            'label' => _('Contact telephone'),
                            'required'        => false
                        ),



                        array(
                            'id'                => 'Order_Email',
                            'edit'              => ($edit ? 'email' : ''),
                            'value'             => $object->get('Order Customer Purchase Order ID'),
                            'formatted_value'   => $object->get('Customer Purchase Order ID'),
                            'label' => _('Email'),
                            'invalid_msg'       => get_invalid_message('email'),
                            'required'          => false,
                        ),

            */


            array(
                'id'              => 'Order_Customer_Purchase_Order_ID',
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Order Customer Purchase Order ID'),
                'formatted_value' => $object->get('Customer Purchase Order ID'),
                'label'           => ucfirst($object->get_field_label('Order Customer Purchase Order ID')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
            ),

        )
    ),

    array(
        'label'      => _('Sell channel'),
        'show_title' => true,
        'fields'     => array(


            array(
                'right_code'      => 'IS',
                'id'              => 'Order_Source_Key',
                'edit'            => ($edit ? 'option' : ''),
                'value'           => $object->get('Order Source Key'),
                'options'         => $order_source_options,

                'formatted_value' => $object->get('Order Source'),
                'label'           => ucfirst($object->get_field_label('Order Source Key')),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
            ),

        )
    ),


    array(

        'label' => _('Invoicing').(($object->get('State Index') >= 90 and $object->get('Order Invoice Key')) ? ' <span  style="font-weight: normal" class="padding_left_10 small">'._('Applied only to new refunds').'</span>' : ''),

        'show_title' => false,
        'class'      => $invoicing_fields_class,
        'fields'     => array(


            array(
                'id'              => 'Order_Recargo_Equivalencia',
                'edit'            => ($edit ? 'option' : ''),
                'render'          => ($account->get('Account Country Code')=='ESP'?true:false),
                'options'         => $options_yes_no,
                'value'           => $object->get('Order Recargo Equivalencia'),
                'formatted_value' => $object->get('Recargo Equivalencia'),
                'label'           => _('Recargo de equivalencia').' <i class="fa fa-registered recargo_equivalencia"></i>',
                'type'            => ''
            ),

            array(
                'id'              => 'Order_Tax_Number',
                'render'          => $render_invoicing_field,
                'edit'            => ($edit ? 'string' : ''),
                'value'           => $object->get('Order Tax Number'),
                'formatted_value' => $object->get('Tax Number'),
                'label'           => ucfirst($object->get_field_label('Order Tax Number')),
                'required'        => false,
                'type'            => 'value'

            ),
            array(
                'render'          => ($object->get('Order Tax Number') == '' ? false : $render_invoicing_field),
                'id'              => 'Order_Tax_Number_Valid',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_valid_tax_number,
                'value'           => $object->get('Order Tax Number Valid'),
                'formatted_value' => $object->get('Tax Number Valid'),
                'label'           => ucfirst($object->get_field_label('Order Tax Number Valid')),
            ),

            array(
                'id'     => 'Order_Invoice_Address',
                'render' => $render_invoicing_field,

                'edit'            => ($edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Order Invoice Address')),
                'formatted_value' => $object->get('Invoice Address'),
                'label'           => ucfirst($object->get_field_label('Order Invoice Address')),
                'required'        => false
            ),


        )
    ),

    array(
        'label'      => _('Delivering').($object->get('State Index') >= 100 ? ' <span  style="font-weight: normal" class="padding_left_10 small">'._('Applied only to new replacements/refunds').'</span>' : ''),
        'show_title' => false,
        'class'      => ($object->get('State Index') < 30 ? 'hide' : ''),
        'fields'     => array(


            array(
                'id'              => 'Order_Delivery_Address',
                'render'          => (($object->get('State Index') < 30 or $object->get('Order For Collection') == 'Yes') ? false : true),
                'edit'            => ($edit ? 'address' : ''),
                'countries'       => $countries,
                'value'           => htmlspecialchars($object->get('Order Delivery Address')),
                'formatted_value' => $object->get('Delivery Address'),
                'label'           => ucfirst($object->get_field_label('Order Delivery Address')),
                'invalid_msg'     => get_invalid_message('address'),
                'required'        => false
            ),

            array(
                'id'     => 'other_delivery_addresses',
                'render' => ($number_other_delivery_address > 1 ? ($object->get('State Index') < 30 ? false : true) : false),
                'class'  => 'directory',

                'value'           => '',
                'label'           => _('Delivery options'),
                'formatted_value' => $other_delivery_addresses_fields_directory,
                'reference'       => ''
            )


        )
    ),


);

