<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 February 2018 at 12:06:28 GMT+8 Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$object_fields = array();

$object_fields[] = array(
    'label'      => _('Id'),
    'show_title' => true,
    'fields'     => array(
        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Email_Campaign_Name',
            'value'             => $object->get('Email Campaign Name'),
            'formatted_value'   => $object->get('Name'),
            'label'             => ucfirst($object->get_field_label('Email Campaign Name')),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => true,
            'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
            'type'              => 'value'
        )
    )
);

if ($object->get('State Index') < 50) {
    $object_fields[] = array(
        'label'      => _('Recipients'),
        'show_title' => true,


        'fields' => array(
            array(
                'id'    => 'Email_Campaign_Abandoned_Cart_Type',
                'edit'  => 'no_icon',
                'value' => $object->get('Email Campaign Abandoned Cart Type'),

                'formatted_value' => '<span id="Email_Campaign_Abandoned_Cart_Type_Inactive_field" class=" '.($object->get('Email Campaign Abandoned Cart Type') == 'Inactive' ? 'valid' : 'very_discreet_on_hover')
                    .'  button value valid  Email_Campaign_Abandoned_Cart_Type" onclick="toggle_email_campaign_abandoned_cart_type(this)" value="Inactive" field_type="radio_option" field="Email_Campaign_Abandoned_Cart_Type"  style="margin-right:40px"><i class=" radio far fa-fw  '
                    .($object->get('Email Campaign Abandoned Cart Type') == 'Inactive' ? 'fa-dot-circle' : 'fa-circle').'" aria-hidden="true"></i> <span >'
                    ._('Inactive form more than days').'  <i class="fa fa-arrow-from-right"></i></span></span>'


                    .'<span id="Email_Campaign_Abandoned_Cart_Type_Last_Updated_field" class=" '.($object->get('Email Campaign Abandoned Cart Type') == 'Last_Updated' ? 'valid' : 'very_discreet_on_hover')
                    .'  Email_Campaign_Abandoned_Cart_Type  value valid button" onclick="toggle_email_campaign_abandoned_cart_type(this)"  field_type="radio_option"  value="Last_Updated" field="Email_Campaign_Abandoned_Cart_Type"  style="margin-right:40px"><i class="radio far fa-fw '
                    .($object->get('Email Campaign Abandoned Cart Type') == 'Last_Updated' ? 'fa-dot-circle' : 'fa-circle').'" aria-hidden="true"></i> <span >'
                    ._('Order last updated with in').' <i class="fa fa-arrow-from-left"></i></span></span>',


                'label'    => _('Type'),
                'required' => false,
                'type'     => ''
            ),

            array(
                'edit'            => ($edit ? 'smallint_unsigned' : ''),
                'render'          => ($object->get('Email Campaign Abandoned Cart Type') == 'Inactive' ? true : false),
                'id'              => 'Email_Campaign_Abandoned_Cart_Days_Inactive_in_Basket',
                'value'           => $object->get('Email Campaign Abandoned Cart Days Inactive in Basket'),
                'formatted_value' => $object->get('Abandoned Cart Days Inactive in Basket'),

                'label'       => ucfirst($object->get_field_label('Email Campaign Abandoned Cart Days Inactive in Basket')),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => true,
                'type'        => 'value'
            ),

            array(
                'edit'   => ($edit ? 'smallint_unsigned' : ''),
                'render' => ($object->get('Email Campaign Abandoned Cart Type') == 'Last_Updated' ? true : false),

                'id'              => 'Email_Campaign_Abandoned_Cart_Days_Last_Updated',
                'value'           => $object->get('Email Campaign Abandoned Cart Days Last Updated'),
                'formatted_value' => $object->get('Abandoned Cart Days Last Updated'),

                'label'       => ucfirst($object->get_field_label('Email Campaign Abandoned Cart Days Last Updated')),
                'invalid_msg' => get_invalid_message('smallint_unsigned'),
                'required'    => true,
                'type'        => 'value'
            ),
        )

    );
}


?>
