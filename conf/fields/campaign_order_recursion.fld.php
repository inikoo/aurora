<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 15:41:21 GMT+7, Bangkok, Thailand

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

/** @var \DealCampaign $object */
/** @var array $options */

$deals = $object->get_deals();
$deal  = array_pop($deals);
$store = $options['store'];

$edit = true;

$options_yn = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
asort($options_yn);

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array(
    array(
        'label'      => _('Public labels'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Deal_Campaign_Name',
                'value'             => $object->get('Deal Campaign Name'),
                'label'             => ucfirst($object->get_field_label('Deal Campaign Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),

                'type' => 'value'
            ),

            array(
                'edit'        => ($edit ? 'string' : ''),
                'id'          => 'Deal_Campaign_Deal_Term_Label',
                'value'       => $object->get('Deal Campaign Deal Term Label'),
                'label'       => _('Terms label'),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => true,

                'type' => 'value'
            ),




        )
    ),

    array(
        'label'      => _('Terms'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'        => ($edit ? 'smallint_unsigned' : ''),
                'id'          => 'Deal_Campaign_Order_Recursion_Days',
                'value'       => $deal->get('Deal Terms Days'),
                'formatted'   => $deal->get('Terms Days'),
                'label'       => _('Days after last order'),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => true,
                'type'        => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Reminders'),
        'show_title' => true,
        'fields'     => array(

            array(
                'render'          => true,
                'id'              => 'Deal_Campaign_Store_Send_Order_Recursion_Emails',
                'edit'            => 'option',
                'value'           => ($new ? 'No' : $store->get('Store Send Order Recursion Emails')),
                'formatted_value' => ($new ? _('Yes') : $store->get('Send Order Recursion Emails')),
                'options'         => $options_yn,
                'label'           => _('Send reminders'),
                'type'            => 'value'
            ),
            array(
                'edit'        => ($edit ? 'smallint_unsigned' : ''),
                'render'      => $store->get('Store Send Order Recursion Emails') == 'Yes',
                'id'          => 'Deal_Campaign_Order_Recursion_Reminder_Days',
                'value'       => $store->get('Store Order Recursion Emails Days'),
                'label'       => _('Days before last dead-end'),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => true,
                'type'        => 'value'
            ),

        )
    ),

);



