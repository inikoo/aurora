<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 14:04:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


$deal_component = $object;

$deal = get_object('Deal', $deal_component->get('Deal Key'));

$number_components=count($deal->get_deal_components('keys','All'));


$new = false;


$object_fields = array();





$object_fields[] = array(
    'label'      => _('Public labels'),
    'show_title' => true,
    'fields'     => array(


        array(
            'edit'              => ($edit ? 'string' : ''),
            'id'                => 'Deal_Name_Label',
            'value'             => $object->get('Deal Name Label'),
            'formatted_value'   => $object->get('Name Label'),
            'label'             => _('Name').($number_components>1?' <span class="warning small"><i class="warning fa fa-exclamation-triangle"></i> '._('Will affect other allowances').'</span>':''),
            'invalid_msg'       => get_invalid_message('string'),
            'required'          => false,
            'type'              => 'value'
        ),

        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Deal_Term_Label',
            'value'           => $object->get('Deal Term Label'),
            'formatted_value' => $object->get('Term Label'),
            'label'           => _('Terms info').($number_components>1?' <span class="warning small"><i class="warning fa fa-exclamation-triangle"></i> '._('Will affect other allowances').'</span>':''),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),

        array(
            'edit'            => ($edit ? 'string' : ''),
            'id'              => 'Deal_Component_Allowance_Label',
            'value'           => $object->get('Deal Component Allowance Label'),
            'formatted_value' => $object->get('Allowance Label'),
            'label'           => _('Allowance info'),
            'invalid_msg'     => get_invalid_message('string'),
            'required'        => false,
            'type'            => 'value'
        ),


    )

);





if ($deal_component->get('Deal Component Status') != 'Finish') {


    $object_fields[] = array(

        'label'      => _('Dates'),
        'show_title' => true,
        'fields'     => array(


            array(
                'edit'   => ($edit ? 'date' : ''),
                'time'   => '00:00:00',
                'render' => ($deal_component->get('Deal Component Status') != 'Waiting' ? false : true),

                'id'              => 'Deal_Component_Begin_Date',
                'value'           => $deal_component->get('Deal Component Begin Date'),
                'formatted_value' => $deal_component->get('Begin Date'),
                'label'           => ucfirst($deal_component->get_field_label('Begin Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => true
            ),

            array(
                'edit' => ($edit ? 'date' : ''),
                'time' => '23:59:59',

                'id'              => 'Deal_Component_Expiration_Date',
                'value'           => $deal_component->get('Deal Component Expiration Date'),
                'formatted_value' => $deal_component->get('Expiration Date'),
                'label'           => ucfirst($deal_component->get_field_label('Expiration Date')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => true
            ),
        )

    );


}

if( $object->get('Deal Component Status')!='Finish') {


    if ($number_components == 1) {


        if ($deal->get('Deal Status') != 'Finish') {
            $operations = array(
                'label'      => _('Operations'),
                'show_title' => true,
                'class'      => 'operations',
                'fields'     => array(

                    array(
                        'id'        => 'suspend_deal',
                        'class'     => 'operation',
                        'render'    => (($deal->get('Deal Status') == 'Suspended' or $deal->get('Deal Status') == 'Finish') ? false : true),
                        'value'     => '',
                        'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal->get_object_name().'", "key":"'.$deal->id
                            .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._("Suspend offer").' <i class="fa fa-stop error new_button link"></i></span>',
                        'reference' => '',
                        'type'      => 'operation'
                    ),

                    array(
                        'id'     => 'activate_deal',
                        'class'  => 'operation',
                        'render' => ($deal->get('Deal Status') != 'Suspended' ? false : true),

                        'value'     => '',
                        'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal->get_object_name().'", "key":"'.$deal->id
                            .'"}\' onClick="activate_object(this)" class="button">'._("Activate offer").' <i class="fa fa-play success new_button"></i></span>',
                        'reference' => '',
                        'type'      => 'operation'
                    ),


                    array(
                        'id'     => 'finish_deal',
                        'class'  => 'operation',
                        'render' => (($deal->get('Deal Status') == 'Active' or $deal->get('Deal Status') == 'Suspended') ? true : false),

                        'value'     => '',
                        'label'     => '<i class="fa fa-fw fa-lock  button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal->get_object_name().'", "key":"'.$deal->id
                            .'"}\' onClick="finish_object(this)" class="delete_object disabled">'._("End offer now").' <i class="fa fa-stop discreet new_button"></i></span>',
                        'reference' => '',
                        'type'      => 'operation'
                    ),

                    array(
                        'id'     => 'finish_deal',
                        'class'  => 'operation',
                        'render' => (($deal->get('Deal Status') == 'Waiting') ? true : false),

                        'value'     => '',
                        'label'     => '<i class="fa fa-fw fa-lock  button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal->get_object_name().'", "key":"'.$deal->id
                            .'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete").' <i class="far fa-trash-alt discreet new_button"></i></span>',
                        'reference' => '',
                        'type'      => 'operation'
                    ),

                )

            );

        }


    } else {


        $operations = array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'operations',
            'fields'     => array(

                array(
                    'id'        => 'suspend_deal',
                    'class'     => 'operation',
                    'render'    => ($deal_component->get('Deal Component Status') == 'Suspended' ? false : true),
                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal_component->get_object_name().'", "key":"'.$deal_component->id
                        .'"}\' onClick="suspend__parent_object(this)" class="delete_object disabled">'._("Suspend offer").' <i class="fa fa-stop error new_button link"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),

                array(
                    'id'     => 'activate_deal',
                    'class'  => 'operation',
                    'render' => ($deal_component->get('Deal Component Status') != 'Suspended' ? false : true),

                    'value'     => '',
                    'label'     => '<i class="fa fa-fw fa-lock hide button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$deal_component->get_object_name().'", "key":"'.$deal_component->id
                        .'"}\' onClick="activate_parent_object(this)" class="button">'._("Activate offer").' <i class="fa fa-play success new_button"></i></span>',
                    'reference' => '',
                    'type'      => 'operation'
                ),


            )

        );

    }

    if (isset($operations)) {
        $object_fields[] = $operations;

    }

}