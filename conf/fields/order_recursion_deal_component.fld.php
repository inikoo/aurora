<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 14:16:33 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


$new = false;


$object_fields = array();



if($object->get('Deal Component Allowance Type')=='Percentage Off') {

    $object_fields[] = array(
        'label'      => _('Allowances'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'            => ($edit ? 'percentage' : ''),
                'id'              => 'Deal_Component_Allowance_Percentage',
                'value'           => $object->get('Deal Component Allowance Percentage'),
                'formatted_value' => $object->get('Allowance Percentage'),
                'label'           => _('Percentage off'),
                'invalid_msg'     => get_invalid_message('percentage'),
                'required'        => true,
                'type'            => 'value'
            ),

        )
    );
}

$object_fields[] =    array(
        'label'      => _('Public description'),
        'show_title' => true,
        'fields'     => array(



            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Deal_Component_Allowance_Label',
                'value'           => $object->get('Deal Component Allowance Label'),
                'formatted_value' => $object->get('Allowance Label'),
                'label'           => _('Allowance label'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),

        )


);

$operations = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'operations',
    'fields'     => array(

        array(
            'id'        => 'suspend_deal',
            'class'     => 'operation',
            'render'=>($object->get('Deal Component Status')=='Suspended'?false:true),
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._("Suspend offer").' <i class="fa fa-stop error new_button link"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),

        array(
            'id'        => 'activate_deal',
            'class'     => 'operation',
            'render'=>($object->get('Deal Component Status')!='Suspended'?false:true),

            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="activate_object(this)" class="button">'._("Activate offer").' <i class="fa fa-play success new_button"></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);

$object_fields[] = $operations;


?>
