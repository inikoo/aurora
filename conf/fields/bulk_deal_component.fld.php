<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 14:38:27 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

$deal_component = $object;


$deal = get_object('Deal',$deal_component->get('Deal Component Deal Key'));


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
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'              => ($edit ? 'string' : ''),
                'id'                => 'Deal_Name',
                'value'             => $deal->get('Deal Name'),
                'label'             => ucfirst($deal->get_field_label('Deal Name')),
                'invalid_msg'       => get_invalid_message('string'),
                'required'          => true,
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),

                'type' => 'value'
            ),




        )
    ),

    array(
        'label'      => _('Terms & allowances'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'            => ($edit ? 'smallint_unsigned' : ''),
                'id'              => 'Deal_Terms',
                'value'           => $deal->get('Deal Terms'),
                'formatted_value' => $deal->get('Terms'),
                'label'           => sprintf(_('Apply when buy %s or more'), '<i>n</i>'),
                'invalid_msg'     => get_invalid_message('smallint_unsigned'),
                'required'        => true,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'percentage' : ''),
                'id'              => 'Deal_Component_Allowance_Percentage',
                'value'           => $deal->get('Deal Component Allowance Percentage'),
                'formatted_value' => $deal->get('Component Allowance Percentage'),
                'label'           => _('Percentage off'),
                'invalid_msg'     => get_invalid_message('percentage'),
                'required'        => true,
                'type'            => 'value'
            ),

        )
    ),

    array(
        'label'      => _('Public description'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Deal_Campaign_Name',
                'value'           => $deal->get('Deal Campaign Name'),
                'formatted_value' => $deal->get('Campaign Name'),
                'label'           => _('Name').' <i class="warning fa fa-exclamation-triangle padding_left_10" title="'._('This label will be present in all other families').'"></i>',
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),
            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Deal_Term_Label',
                'value'           => $deal->get('Deal Term Label'),
                'formatted_value' => $deal->get('Term Label'),
                'label'           => _('Terms label'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),

            array(
                'edit'            => ($edit ? 'string' : ''),
                'id'              => 'Deal_Component_Allowance_Label',
                'value'           => $deal->get('Deal Component Allowance Label'),
                'formatted_value' => $deal->get('Component Allowance Label'),
                'label'           => _('Allowance label'),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => true,
                'type'            => 'value'
            ),

        )
    ),


);


if (!$new) {



    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'suspend_deal',
                'class'     => 'operation',
                'render'=>($deal_component->get('Deal Component Status')=='Suspended'?false:true),
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="suspend_parent_object(this)" class="delete_object disabled">'._("Suspend offer").' <i class="fa fa-stop error new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'activate_deal',
                'class'     => 'operation',
                'render'=>($deal_component->get('Deal Component Status')!='Suspended'?false:true),

                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                    .'"}\' onClick="activate_parent_object(this)" class="button">'._("Activate offer").' <i class="fa fa-play success new_button"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;
}


