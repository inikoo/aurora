<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(
            array(
                'class' => 'locked',
                'id'    => 'Order_Key',
                'value' => $object->id,
                'label' => _('Id')
            ),
            array(
                'class' => 'locked',
                'id'    => 'Order_Public_ID',
                'value' => $object->get('Order Public ID'),
                'label' => _('Number')
            ),


        )
    ),
    array(
        'label'      => _('Customer'),
        'show_title' => true,
        'fields'     => array(

            array(
                'class' => 'locked',
                'id'    => 'Customer',
                'value' => $object->get('Order Customer Name').' (<span class="id">'.sprintf(
                        "%05d", $object->get('Order Customer Key')
                    ).'</span>)',
                'label' => _('Customer')
            ),
            array(
                'id'    => 'Order_Customer_Fiscal_Name',
                'value' => $object->get('Order Customer Fiscal Name'),
                'label' => _('Fiscal name')
            ),
            array(
                'id'    => 'Order_Customer_Contact_Name',
                'value' => $object->get('Order Customer Contact Name'),
                'label' => _('Contact name')
            ),
            array(
                'id'    => 'Order_Telephone',
                'value' => $object->get('Order Telephone'),
                'label' => _('Contact telephone')
            ),
            array(
                'id'    => 'Order_Email',
                'value' => $object->get('Order Email'),
                'label' => _('Contact email')
            ),


        )
    ),
    array(
        'label'      => _('Billing'),
        'show_title' => true,
        'fields'     => array(

            array(
                'class' => 'address',
                'id'    => 'Order_Billing_Address',
                'value' => $object->get('Order XHTML Billing Tos'),
                'label' => _('Billing Address')
            ),


        )
    ),
    array(
        'label'      => _('Delivery'),
        'show_title' => true,
        'fields'     => array(

            array(
                'class' => 'address',
                'id'    => 'Order_Ship_To_Address',
                'value' => $object->get('Order XHTML Ship Tos'),
                'label' => _('Delivery Address')
            ),


        )
    ),

);


?>
