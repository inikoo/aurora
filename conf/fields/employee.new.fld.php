<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2016 at 20:20:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include 'conf/user_groups.php';

if ($user->can_edit('users')) {
    $edit_users = true;
} else {
    $edit_users = false;
}

if (isset($options['new']) and $options['new']) {
    $new = true;
} else {
    $new = false;
}


$employee = $object;
$account  = new Account();

$employee->get_user();

$options_Staff_Payment_Terms = array(
    'Monthly' => _('Monthly (fixed)'),
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
include_once 'conf/roles.php';
$roles=get_roles();
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


foreach (preg_split('/,/', $employee->get('Staff Position')) as $current_position_key) {
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
$stores = array();
$sql    = sprintf(
    'SELECT `Store Code`,`Store Key`,`Store Name` FROM `Store Dimension` order by `Store Code` '
);
foreach ($db->query($sql) as $row) {
    $stores[$row['Store Key']] = $row;
}


$_options_User_Groups = array();
$options_User_Groups  = array();

foreach ($user_groups as $key => $user_group) {
    $_options_User_Groups['x'.$user_group['Key']] = array(
        'label'    => $user_group['Name'],
        'selected' => false,
        'key'      => $user_group['Key']
    );
}


foreach ($_options_User_Groups as $k => $d) {
    $_tmp[$k] = $d['label'];
}
array_multisort($_tmp, SORT_ASC, $_options_User_Groups);
foreach ($_options_User_Groups as $_option) {
    $options_User_Groups[(string)$_option['key']] = $_option;
}


$options_Stores = array();
$sql            = sprintf('SELECT `Store Key` AS `key` ,`Store Name`,`Store Code`   FROM `Store Dimension`  ');
foreach ($db->query($sql) as $row) {
    $options_Stores[$row['key']] = array(
        'label'    => $row['Store Code'],
        'selected' => false
    );
}


$options_Websites = array();
$sql              = sprintf('SELECT `Website Key` AS `key` ,`Website Name`,`Website Code` FROM `Website Dimension`  ');
foreach ($db->query($sql) as $row) {
    $options_Websites[$row['key']] = array(
        'label'    => $row['Website Code'],
        'selected' => false
    );
}


$options_Warehouses = array();
$sql                = sprintf('SELECT `Warehouse Key` AS `key` ,`Warehouse Name`,`Warehouse Code` FROM `Warehouse Dimension`  ');
foreach ($db->query($sql) as $row) {
    $options_Warehouses[$row['key']] = array(
        'label'    => $row['Warehouse Code'],
        'selected' => false
    );
}


$options_Productions = array();
$sql                 = sprintf(
    'SELECT `Supplier Production Supplier Key` AS `key`,`Supplier Name`,`Supplier Code` FROM `Supplier Production Dimension` SPD LEFT JOIN `Supplier Dimension` S ON (`Supplier Key`=`Supplier Production Supplier Key`)  '
);


foreach ($db->query($sql) as $row) {
    $options_Productions[$row['key']] = array(
        'label'    => $row['Supplier Code'],
        'selected' => false
    );
}


asort($options_Staff_Position);
asort($options_Staff_Supervisor);
asort($options_Staff_Type);
asort($options_Staff_Payment_Terms);

asort($options_yn);

$smarty->assign('stores', $stores);

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

                'id'              => 'Staff_Birthday',
                'edit'            => ($edit ? 'date' : ''),
                'time'            => '00:00:00',
                'value'           => $employee->get('Staff Birthday'),
                'formatted_value' => $employee->get('Birthday'),
                'label'           => ucfirst($employee->get_field_label('Staff Birthday')),
                'invalid_msg'     => get_invalid_message('date'),
                'required'        => false,
                'type'            => 'value'
            ),
            array(

                'id'   => 'Staff_Official_ID',
                'edit' => ($edit ? 'string' : ''),

                'value'             => $employee->get('Staff Official ID'),
                'label'             => ucfirst(
                    $employee->get_field_label('Staff Official ID')
                ),
                'invalid_msg'       => get_invalid_message('string'),
                'server_validation' => json_encode(
                    array(
                        'tipo'   => 'check_for_duplicates',
                        'object' => ($employee->get('Staff Currently Working') == 'Yes' ? 'Staff' : 'ExStaff')
                    )
                ),
                'required'          => false,
                'type'              => 'value'
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
            array(

                'id'   => 'Staff_Next_of_Kind',
                'edit' => ($edit ? 'string' : ''),

                'value'       => $employee->get('Staff Next of Kind'),
                'label'       => ucfirst(
                    $employee->get_field_label('Staff Next of Kind')
                ),
                'invalid_msg' => get_invalid_message('string'),
                'required'    => false,
                'type'        => 'value'

            ),

        )
    ),
    array(
        'label'      => _('Employment'),
        'show_title' => true,
        'class'      => 'edit_fields',
        'fields'     => array(
            array(

                'id'     => 'Staff_Type',
                'edit'   => ($edit ? 'option' : ''),
                'render' => true,


                'value'           => $employee->get('Staff Type'),
                'formatted_value' => $employee->get('Type'),
                'options'         => $options_Staff_Type,
                'label'           => ucfirst($employee->get_field_label('Staff Type')),
                'type'            => 'value',
                'required'        => false,
            ),

            array(
                'edit'   => ($edit ? 'option' : ''),
                'render' => true,

                'id'              => 'Staff_Currently_Working',
                'value'           => $employee->get('Staff Currently Working'),
                'formatted_value' => $employee->get('Currently Working'),
                'options'         => $options_yn,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Currently Working')
                ),
                'type'            => '',
                'required'        => false,
            ),
            array(
                'render' => true,
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
                'edit'   => ($edit ? 'date' : ''),
                'id'     => 'Staff_Valid_To',

                'time'            => '18:00:00',
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
                'id'              => 'Staff_Position',
                'edit'            => 'option_multiple_choices',
                'value'           => $employee->get('Staff Position'),
                'formatted_value' => $employee->get('Position'),
                'options'         => $options_Staff_Position,
                'label'           => ucfirst(
                    $employee->get_field_label('Staff Position')
                ),
            ),
            array(
                //   'render'=>($employee->get('Staff Currently Working')=='Yes'?true:false),
                'id'   => 'Staff_Supervisor',
                'edit' => ($edit ? 'option_multiple_choices' : ''),

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


$object_fields[] = array(
    'label'      => _('System user'),
    'show_title' => true,
    'class'      => 'edit_fields',
    'fields'     => array(


        array(

            'id'     => 'add_new_user',
            'render' => false,

            'class'    => '',
            'value'    => '',
            'label'    => _('Set up system user').' <i onClick="show_user_fields()" class="fa fa-plus new_button link"></i>',
            'required' => false,
            'type'     => 'util'
        ),

        array(
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
            'type'            => 'value',
            'hidden'          => true
        ),
        array(
            'render'            => true,
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
            'type'              => 'value',
            'required'          => false,

        ),

        array(
            'render' => true,

            'id'              => 'Staff_User_Password',
            'edit'            => 'password',
            'value'           => '',
            'formatted_value' => '******',
            'label'           => ucfirst(
                $employee->get_field_label('Staff User Password')
            ),
            'invalid_msg'     => get_invalid_message('password'),
            'type'            => 'value',
            'required'        => false,


        ),
        array(
            'render'          => true,
            'id'              => 'Staff_PIN',
            'edit'            => 'pin',
            'value'           => '',
            'formatted_value' => '****',
            'label'           => ucfirst($employee->get_field_label('Staff PIN')),
            'invalid_msg'     => get_invalid_message('pin'),
            'type'            => 'value',
            'required'        => false,

        ),


        array(
            'render' => ($edit_users ? true : false),

            'id'              => 'Staff_User_Permissions',
            'edit'            => 'user_permissions',
            'stores'          => $stores,
            'value'           => '',
            'formatted_value' => '',
            'label'           => _('Permissions'),
            'required'        => false,
            'type'            => 'value'
        ),


    )
);




