<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 24 June 2016 at 09:13:01 BST, Plane (London-Jakarta) , Indian Ocean
 Copyright (c) 2016, Inikoo

 Version 3

*/


if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}

$employee = $object;
$account  = new Account();

$options_Staff_Payment_Terms = array(
    'Monthly'  => _('Monthly (fixed)'),
    'PerHour' => _('Per hour (prorata)')
);


$options_Staff_Type = array(
    'Contractor'     => _('Contractor'),
    'Employee'       => _('Employee'),
    'Volunteer'      => _('Volunteer'),
    'TemporalWorker' => _('Temporal Worker'),
    'WorkExperience' => _('Work Experience')
);
$options_yn         = array(
    'Yes' => _('Yes'),
    'No'  => _('No')
);
include 'conf/roles.php';
foreach ($roles as $_key => $_data) {
    if (in_array($account->get('Setup Metadata')['size'], $_data['size'])) {

        foreach ($account->get('Setup Metadata')['instances'] as $instance) {
            if (in_array($instance, $_data['instances'])) {

                $options_Staff_Position[$_key] = array(
                    'label'    => $_data['title'],
                    'selected' => false
                );
                break;
            }
        }
    }
}

foreach (
    preg_split('/,/', $employee->get('Staff Position')) as $current_position_key
) {
    if (array_key_exists($current_position_key, $options_Staff_Position)) {

        $options_Staff_Position[$current_position_key]['selected'] = true;
    }
}

$options_Staff_Supervisor = array();
$sql                      = sprintf(
    'SELECT `Staff Name`,`Staff Key`,`Staff Alias` FROM `Staff Dimension` WHERE `Staff Currently Working`="Yes" '
);
foreach ($db->query($sql) as $row) {
    $options_Staff_Supervisor[$row['Staff Key']] = array(
        'label' => $row['Staff Alias'],

        'label2'   => $row['Staff Name'].' ('.sprintf('%03d', $row['Staff Key']).')',
        'selected' => false
    );
}
asort($options_Staff_Position);
asort($options_Staff_Supervisor);
asort($options_Staff_Type);
asort($options_Staff_Payment_Terms);

asort($options_yn);

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(

                'id'   => 'Staff_ID',
                'edit' => ($edit ? 'string' : ''),

                'value'             => $employee->get('Staff ID'),
                'label'             => ucfirst(
                    $employee->get_field_label('Staff ID')
                ),
                'invalid_msg'       => get_invalid_message('smallint_unsigned'),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'required'          => false,
                'type'              => 'value'
            ),
            array(

                'id'   => 'Staff_Alias',
                'edit' => ($edit ? 'string' : ''),

                'value'             => $employee->get('Staff Alias'),
                'label'             => ucfirst(
                    $employee->get_field_label('Staff Alias')
                ),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'type'              => 'value'
            ),


        )
    ),

    array(
        'label'      => _('Personal information'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(

            array(

                'id'   => 'Staff_Name',
                'edit' => ($edit ? 'string' : ''),

                'value'       => $employee->get('Staff Name'),
                'label'       => ucfirst(
                    $employee->get_field_label('Staff Name')
                ),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => true,
                'type'        => 'value'

            ),

            array(

                'id'   => 'Staff_Email',
                'edit' => ($edit ? 'email' : ''),

                'value'             => $employee->get('Staff Email'),
                'formatted_value'   => $employee->get('Email'),
                'label'             => ucfirst(
                    $employee->get_field_label('Staff Email')
                ),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'invalid_msg'       => get_invalid_message('email'),
                'required'          => false,
                'type'              => 'value'
            ),
            array(

                'id'   => 'Staff_Telephone',
                'edit' => ($edit ? 'telephone' : ''),

                'value'           => $employee->get('Staff Telephone'),
                'formatted_value' => $employee->get('Telephone'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Telephone')
                ),
                'invalid_msg'     => get_invalid_message('telephone'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(

                'id'   => 'Staff_Address',
                'edit' => ($edit ? 'textarea' : ''),

                'value'           => $employee->get('Staff Address'),
                'formatted_value' => $employee->get('Staff Address'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Address')
                ),
                'invalid_msg'     => get_invalid_message('string'),
                'required'        => false,
                'type'            => 'value'
            ),


        )
    ),
    array(
        'label'      => _('Contract'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(
            array(

                'id'   => 'Staff_Type',
                'edit' => ($new ? 'hidden' : ($edit ? 'option' : '')),

                'value'           => ($new
                    ? 'Contractor'
                    : $employee->get(
                        'Staff Type'
                    )),
                'formatted_value' => ($new
                    ? _('Contractor')
                    : $employee->get(
                        'Type'
                    )),
                'options'         => $options_Staff_Type,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Type')
                ),
                'type'            => 'value',
                'required'        => false,
            ),

            array(
                'render' => false,
                'edit'   => ($edit ? 'option' : ''),

                'id'              => 'Staff_Currently_Working',
                'value'           => ($new
                    ? 'Yes'
                    : $employee->get(
                        'Staff Currently Working'
                    )),
                'formatted_value' => ($new
                    ? _('Yes')
                    : $employee->get(
                        'Staff Currently Working'
                    )),
                'options'         => $options_yn,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Currently Working')
                ),
                'type'            => 'value',
                'required'        => false,
            ),
            array(
                'render' => ($new ? false : true),
                'edit'   => ($edit ? 'date' : ''),
                'id'     => 'Staff_Valid_From',

                'time'            => '09:00:00',
                'value'           => $employee->get('Staff Valid From'),
                'formatted_value' => $employee->get('Valid From'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Valid From')
                ),
                'invalid_msg'     => get_invalid_message('date'),
                'type'            => 'value',
                'required'        => false,
            ),
            array(
                'render' => ($new
                    ? false
                    : ($employee->get(
                        'Staff Currently Working'
                    ) == 'Yes' ? false : true)),
                'edit'   => 'hidden',
                'id'     => 'Staff_Valid_To',

                'time'            => '09:00:00',
                'value'           => $employee->get('Staff Valid To'),
                'formatted_value' => $employee->get('Valid To'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Valid To')
                ),
                'invalid_msg'     => get_invalid_message('date'),
                'type'            => 'value',
                'required'        => false,
            ),

            array(

                'id'   => 'Staff_Job_Title',
                'edit' => ($edit ? 'string' : ''),

                'value'    => $employee->get('Staff Job Title'),
                'label'    => ucfirst(
                    $employee->get_field_label('Staff Job Title')
                ),
                'required' => false,
                'type'     => 'value'
            ),
            array(
                //   'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
                'id'   => 'Staff_Supervisor',
                'edit' => ($edit ? 'radio_option' : ''),

                'value'           => $employee->get('Staff Supervisor'),
                'formatted_value' => $employee->get('Supervisor'),
                'options'         => $options_Staff_Supervisor,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Supervisor')
                ),
                'required'        => false,
                'type'            => 'value'

            ),

        )
    ),


);

if (!$new) {
    $object_fields[] = array(
        'label'      => _('Working hours & cost'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(
            array(

                'id'              => 'Staff_Working_Hours',
                'edit'            => 'working_hours',
                'value'           => $employee->get('Staff Working Hours'),
                'formatted_value' => $employee->get('Working Hours'),
                'options'         => $options_Staff_Type,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Working Hours')
                ),
                'invalid_msg'     => get_invalid_message('working_hours'),
            ),

            array(

                'id'              => 'Staff_Salary',
                'edit'            => 'salary',
                'value'           => $employee->get('Staff Salary'),
                'formatted_value' => $employee->get('Salary'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Salary')
                ),
                'invalid_msg'     => get_invalid_message('salary'),
            )


        )
    );

    if ($employee->get('Staff User Key')) {


        $object_fields[] = array(
            'label'      => _('System user').' <i  onClick="change_view(\'account/user/'.$employee->get(
                    'Staff User Key'
                ).'\')" class="fa fa-terminal link"></i>',
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(

                array(

                    'id'              => 'Staff_User_Active',
                    'edit'            => 'option',
                    'value'           => $employee->get('Staff User Active'),
                    'formatted_value' => $employee->get('User Active'),
                    'options'         => $options_yn,
                    'label'           => ucfirst(
                        $employee->get_field_label('Staff Active')
                    ),
                ),
                array(
                    'id'              => 'Staff_Position',
                    'edit'            => 'radio_option',
                    'value'           => $employee->get('Staff Position'),
                    'formatted_value' => $employee->get('Position'),
                    'options'         => $options_Staff_Position,
                    'label'           => ucfirst(
                        $employee->get_field_label('Staff Position')
                    ),
                ),
                array(

                    'id'                => 'Staff_User_Handle',
                    'edit'              => 'handle',
                    'value'             => $employee->get('Staff User Handle'),
                    'formatted_value'   => $employee->get('User Handle'),
                    'label'             => ucfirst(
                        $employee->get_field_label('Staff User Handle')
                    ),
                    'server_validation' => json_encode(
                        array('tipo' => 'check_for_duplicates')
                    ),
                    'invalid_msg'       => get_invalid_message('handle'),
                ),

                array(
                    'render' => ($employee->get('Staff User Active') == 'Yes' ? true : false),

                    'id'              => 'Staff_User_Password',
                    'edit'            => 'password',
                    'value'           => '',
                    'formatted_value' => '******',
                    'label'           => ucfirst(
                        $employee->get_field_label('Staff User Password')
                    ),
                    'invalid_msg'     => get_invalid_message('password'),
                ),
                array(
                    'render' => ($employee->get('Staff User Active') == 'Yes' ? true : false),

                    'id'              => 'Staff_User_PIN',
                    'edit'            => 'pin',
                    'value'           => '',
                    'formatted_value' => '****',
                    'label'           => ucfirst(
                        $employee->get_field_label('Staff User PIN')
                    ),
                    'invalid_msg'     => get_invalid_message('pin'),
                ),


            )
        );

    } else {
        $object_fields[] = array(
            'label'      => _('System user'),
            'show_title' => true,
            'class'      => 'edit_fields',
            'fields'     => array(
                array(

                    'id'        => 'new_user',
                    'class'     => 'new',
                    'value'     => '',
                    'label'     => _('Set up system user').' <i class="fa fa-plus new_button link"></i>',
                    'reference' => 'contractor/'.$employee->id.'/user/new'
                ),

            )
        );

    }


    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(

            array(
                'id'        => 'delete_contractor',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$object->get_object_name(
                    ).'", "key":"'.$object->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete contractor").' <i class="fa fa-trash new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;

} else {


    $object_fields[] = array(
        'label'      => _('System user'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(


            array(

                'id'       => 'add_new_user',
                'class'    => '',
                'value'    => '',
                'label'    => _('Set up system user').' <i onClick="show_user_fields()" class="fa fa-plus new_button link"></i>',
                'required' => false,
                'type'     => 'util'
            ),

            array(
                'render'   => false,
                'id'       => 'dont_add_new_user',
                'class'    => '',
                'value'    => '',
                'label'    => _("Don't set up system user").' <i onClick="hide_user_fields()" class="fa fa-minus new_button link"></i>',
                'required' => false,
                'type'     => 'util'
            ),


            array(
                'render' => false,
                'id'     => 'Staff_User_Active',
                'edit'   => ($edit ? 'option' : ''),

                'options'         => $options_yn,
                'value'           => 'Yes',
                'formatted_value' => _('Yes'),
                'label'           => ucfirst(
                    $employee->get_field_label('Staff User Active')
                ),
                'type'            => 'user_value',
                'hidden'          => true
            ),
            array(
                'render'            => false,
                'id'                => 'Staff_User_Handle',
                'edit'              => 'handle',
                'value'             => $employee->get('Staff User Handle'),
                'formatted_value'   => $employee->get('User Handle'),
                'label'             => ucfirst(
                    $employee->get_field_label('Staff User Handle')
                ),
                'server_validation' => json_encode(
                    array('tipo' => 'check_for_duplicates')
                ),
                'invalid_msg'       => get_invalid_message('handle'),
                'type'              => 'user_value',
                'required'          => false,

            ),
            array(
                'render' => false,
                'id'     => 'Staff_Position',
                'edit'   => ($edit ? 'radio_option' : ''),

                'value'           => '',
                'formatted_value' => '',
                'options'         => $options_Staff_Position,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Position')
                ),
                'required'        => false,
                'type'            => 'user_value'
            ),


            array(
                'render' => false,

                'id'              => 'Staff_User_Password',
                'edit'            => 'password',
                'value'           => '',
                'formatted_value' => '******',
                'label'           => ucfirst(
                    $employee->get_field_label('Staff User Password')
                ),
                'invalid_msg'     => get_invalid_message('password'),
                'type'            => 'user_value',
                'required'        => false,


            ),
            array(
                'render'          => false,
                'id'              => 'Staff_PIN',
                'edit'            => 'pin',
                'value'           => '',
                'formatted_value' => '****',
                'label'           => ucfirst(
                    $employee->get_field_label('Staff PIN')
                ),
                'invalid_msg'     => get_invalid_message('pin'),
                'type'            => 'user_value',
                'required'        => false,

            ),


        )
    );

}

?>
