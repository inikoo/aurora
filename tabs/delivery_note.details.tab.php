<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 14:48:54 CET, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


$delivery_note = $state['_object'];

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(
            array(
                'class' => 'locked',
                'id'    => 'Delivery_Note_Key',
                'value' => $delivery_note->id,
                'label' => _('Id')
            ),
            array(
                'class' => 'locked',
                'id'    => 'Delivery_Note_ID',
                'value' => $delivery_note->get('Delivery Note ID'),
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
                'id'    => 'Cutomer',
                'value' => $delivery_note->get('Delivery Note Customer Name').' (<span class="id">'.sprintf(
                        "%05d", $delivery_note->get('Delivery Note Customer Key')
                    ).'</span>)',
                'label' => _('Customer')
            ),
            array(
                'id'    => 'Delivery_Note_Customer_Contact_Name',
                'value' => $delivery_note->get(
                    'Delivery Note Customer Contact Name'
                ),
                'label' => _('Contact name')
            ),
            array(
                'id'    => 'Delivery_Note_Telephone',
                'value' => $delivery_note->get('Delivery Note Telephone'),
                'label' => _('Contact telephone')
            ),
            array(
                'id'    => 'Delivery_Note_Email',
                'value' => $delivery_note->get('Delivery Note Email'),
                'label' => _('Contact email')
            ),


        )
    ),
    array(
        'label'      => _('Delivery'),
        'show_title' => true,
        'fields'     => array(

            array(
                'class' => 'address',
                'id'    => 'Delivery_Note_XHTML_Ship_To',
                'value' => $delivery_note->get('Delivery Note XHTML Ship To'),
                'label' => _('Delivery Address')
            ),


        )
    ),

);
$smarty->assign('object_fields', $object_fields);

$html = $smarty->fetch('edit_object.tpl');

?>