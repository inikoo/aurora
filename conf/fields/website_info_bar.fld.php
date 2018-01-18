<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2018 at 13:34:29 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/



$options_basket_amount = array(
    'total' => _('Total'),
    'items_net' => _('Items net'),

);


$object_fields = array(
    array(
        'label'      => _('Basket button'),
        'show_title' => true,
        'fields'     => array(


                array(
                    'id'              => 'Website_Settings_Info_Bar_Basket_Amount_Type',
                    'edit'            => ($edit ? 'option' : ''),
                    'options'         => $options_basket_amount,
                    'value'           => ($object->get('Website Settings Info Bar Basket Amount Type')==''?'total':$object->get('Website Settings Info Bar Basket Amount Type')),
                    'formatted_value' => ($object->get('Website Settings Info Bar Basket Amount Type')==''?_('Total'):$object->get('Settings Info Bar Basket Amount Type')),
                    'label'           => _('Displayed amount'),
                    'type'            => 'value'
                ),

        

        )
    ),








);




?>
