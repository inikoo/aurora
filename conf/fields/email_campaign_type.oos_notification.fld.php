<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 June 2018 at 16:53:43 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';


$options_time = array();

foreach (range(0, 24) as $hour) {
    $options_time[sprintf('%02d:00:00', $hour)] = sprintf('%02d:00', $hour);
}


$metadata = $object->get('Metadata');

$smarty->assign('email_template', $object);


$object_fields = array(
    array(
        'label'      => _('Schedule'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Email_Campaign_Type_Schedule_Days',
                'edit'            => 'no_icon',
                'value'           => '',
                'formatted_value' => '<span id="Email_Campaign_Type_Schedule_Days_Monday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Monday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Monday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week monday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Tuesday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Tuesday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Tuesday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week tuesday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Wednesday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Wednesday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Wednesday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week wednesday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Thursday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Thursday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Thursday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week thursday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Friday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Friday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Friday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week friday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Saturday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Saturday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Saturday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week saturday')).'</span></span>'
                    .'<span id="Email_Campaign_Type_Schedule_Days_Sunday_field" class="button value valid unselectable"  onclick="toggle_schedule_days(this)" field_type="subscription" field="Email_Campaign_Type_Schedule_Days_Sunday"  style="margin-right:20px"><i class=" fa fa-fw '
                    .($metadata['Schedule']['Days']['Sunday'] == 'Yes' ? 'fa-toggle-on' : 'fa-toggle-off').'" aria-hidden="true"></i> <span >'.strftime('%a', strtotime('this week sunday')).'</span></span>',


                'label'    => _('Days'),
                'required' => true,


                'type' => ''
            ),

            array(
                'id'      => 'Email_Campaign_Type_Schedule_Time',
                'edit'    => 'option',
                'options' => $options_time,

                'value'           => $object->get('Email Campaign Type Schedule Time'),
                'formatted_value' => $object->get('Schedule Time'),
                'label'           => _('Time'),
                'required'        => true,


                'type' => 'value'
            ),


        )
    )


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


?>
