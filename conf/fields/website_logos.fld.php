<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 April 2018 at 09:43:41 BST, Sheffield, UK

 Copyright (c) 2018, Inikoo

 Version 3.0
*/




$object_fields = array(
    array(
        'label'      => _('Favicon'),
        'show_title' => true,
        'fields'     => array(


                array(
                   'id'              => 'Website_Favicon',
                    'edit'            => 'no_icon',
                    'value'           => $object->get('Website Favicon'),
                    'formatted_value' => '0px"><i class=" fa fa-fw '.($object->get('Customer Send Newsletter')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Newsletter')=='No'?'discreet':'').'">'._('Newsletter').'</span></span>'.'<span onclick="toggle_subscription(this)"  field="Customer_Send_Email_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Send Email Marketing')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Email Marketing')=='No'?'discreet':'').'">'._('Marketing emails').'</span></span>'.'<span onclick="toggle_subscription(this)"  field="Customer_Send_Postal_Marketing" class="button" style="margin-right:40px"><i class=" fa fa-fw '.($object->get('Customer Send Postal Marketing')=='Yes'?'fa-toggle-on':'fa-toggle-off').'" aria-hidden="true"></i> <span class="'.($object->get('Customer Send Postal Marketing')=='No'?'discreet':'').'">'._('Postal marketing').'</span></span>',
                    'formatted_value' => '',
                    'label'           => _('Favicon').' png (310x310)',
                    'required'        => true,
                    'type'            => 'value'
                ),

        

        )
    ),








);




?>
