<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 25 May 2022 13:36:37 Tranava Slovakia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

include_once 'utils/static_data.php';

$options_yes_no = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);


$options_time = array();

foreach (range(0, 24) as $hour) {
    $options_time[sprintf('%02d:00:00', $hour)] = sprintf('%02d:00', $hour);
}


$metadata = $object->get('Metadata');

$smarty->assign('email_template', $object);



$object_fields = array(

    array(
        'label'      => _('Reminder'),
        'show_title' => true,
        'fields'     => array(

            array(
                'id'     => 'Email_Campaign_Type_Cool_Down_Hours',

                'edit'            => 'smallint_unsigned',
                'value'           => $metadata['Cool Down Hours'],
                'formatted_value' => sprintf(ngettext('%s hour', '%s hours', $metadata['Cool Down Hours']), number($metadata['Cool Down Hours'])),
                'label'           => _('Cool-down period'),
                'required'        => true,
                'type'            => 'value'
            ),

            array(
                'id'              => 'Email_Campaign_Type_Include_Critical',
                'edit'            =>'option',
                'options'         => $options_yes_no,
                'value'           => $object->get('Email Campaign Type Include Critical'),
                'formatted_value' => $object->get('Include Critical'),
                'label'           => _('Notify of critical stock as well'),
                'type'            => ''
            ),



        )
    ),





);




$object_fields[] = array(
    'label'      => _('Operations'),
    'show_title' => true,
    'class'      => 'edit_fields '.($object->get('Email Campaign Type Status') == 'InProcess' ? 'hide' : ''),
    'fields'     => array(

        array(

            'id'        => 'activate_email_template',
            'class'     => 'operation',
            'render'    => ($object->get('Email Campaign Type Status') == 'Suspended' ? true : false),
            'value'     => '',
            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="activate_object(this)" class="delete_object disabled">'._('Set as live').' <i class="fa fa-play success new_button "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),
        array(

            'id'     => 'suspend_email_template',
            'class'  => 'operation',
            'value'  => '',
            'render' => ($object->get('Email Campaign Type Status') == 'Active' ? true : false),

            'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id
                .'"}\' onClick="suspend_object(this)" class="delete_object disabled">'._('Suspend').' <i class="fa fa-stop error new_button "></i></span>',
            'reference' => '',
            'type'      => 'operation'
        ),


    )

);



