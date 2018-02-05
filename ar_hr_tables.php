<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 15:19:13 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('staff')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'employees':
        employees(get_table_parameters(), $db, $user, 'current');
        break;
    case 'exemployees':
        employees(get_table_parameters(), $db, $user, 'ex');
        break;
    case 'timesheets':
        timesheets(get_table_parameters(), $db, $user);
        break;
    case 'contractors':
        contractors(get_table_parameters(), $db, $user);
        break;
    case 'timesheet_records':
        timesheet_records(get_table_parameters(), $db, $user);
        break;
    case 'overtimes':
        overtimes(get_table_parameters(), $db, $user);
        break;
    case 'months':
        months(get_table_parameters(), $db, $user);
        break;
    case 'weeks':
        weeks(get_table_parameters(), $db, $user);
        break;
    case 'days':
        days(get_table_parameters(), $db, $user);
        break;
    case 'timesheets.employees':
        timesheets_employees(get_table_parameters(), $db, $user);
        break;

    case 'positions':
        positions(get_table_parameters(), $db, $user);
        break;
        case 'deleted.employees':
        deleted_employees(get_table_parameters(), $db, $user, 'current');
        break;
    case 'deleted.contractors':
        deleted_contractors(get_table_parameters(), $db, $user, 'current');
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function employees($_data, $db, $user, $type = '') {

    if ($type == 'current') {
        $extra_where = ' and `Staff Currently Working`="Yes"';
        $rtext_label = 'employee';

    } elseif ($type == 'ex') {
        $extra_where = ' and `Staff Currently Working`="No"';
        $rtext_label = 'ex employee';

    }

    include_once 'prepare_table/init.php';
    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
   // exit;


    $adata = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['User Active']) {
                case 'Yes':
                    $user_active = _('Active');
                    break;
                case 'No':
                    $user_active = _('Suspended');
                    break;
                case '':
                    $user_active = _("Don't set up");
                    break;
                default:
                    $user_active = $data['User Active'];
                    break;
            }

            switch ($data['Staff Type']) {
                case 'Employee':
                    $type = _('Employee');
                    break;
                case 'Volunteer':
                    $type = _('Volunteer');
                    break;
                case 'TemporalWorker':
                    $type = _("Temporal worker");
                    break;
                case 'WorkExperience':
                    $type = _("Work experience");
                    break;
                default:
                    $type = $data['Staff Type'];
                    break;
            }

            $adata[] = array(
                'id'           => (integer)$data['Staff Key'],
                'formatted_id' => sprintf("%04d", $data['Staff Key']),
                'payroll_id'   => $data['Staff ID'],
                'name'         => $data['Staff Name'],
                'code'         => sprintf('<span class="link" onCLick="change_view(\'employee/%d\')">%s</span>',$data['Staff Key'],$data['Staff Alias']),
                'code_link'    => $data['Staff Alias'],


                'birthday' => (($data['Staff Birthday'] == '' or $data['Staff Birthday'] == '0000-00-00 00:00:00')
                    ? ''
                    : strftime(
                        "%e %b %Y", strtotime($data['Staff Birthday'].' +0:00')
                    )),

                'official_id'  => $data['Staff Official ID'],
                'email'        => $data['Staff Email'],
                'telephone'    => $data['Staff Telephone Formatted'],
                'next_of_kind' => $data['Staff Next of Kind'],
                'from'         => (($data['Staff Valid From'] == '' or $data['Staff Valid From'] == '0000-00-00 00:00:00')
                    ? ''
                    : strftime(
                        "%e %b %Y", strtotime($data['Staff Valid From'].' +0:00')
                    )),

                'until' => (($data['Staff Valid To'] == '' or $data['Staff Valid To'] == '0000-00-00 00:00:00')
                    ? ''
                    : strftime(
                        "%e %b %Y", strtotime($data['Staff Valid To'].' +0:00')
                    )),
                'type'  => $type,


                'supervisors' => $data['supervisors'],

                'job_title'          => $data['Staff Job Title'],
                'user_login'         => $data['User Handle'],

                'user_login'         => sprintf('<span class="link" onCLick="change_view(\'account/user/%d\')">%s</span>',$data['User Key'],$data['User Handle']),
                'user_name_bis'         => sprintf('<span class="link" onCLick="change_view(\'account/user/%d\')">%s</span>',$data['User Key'],$data['User Handle']),




                'user_active'        => $user_active,
                'user_last_login'    => ($data['User Last Login'] == ''
                    ? ''
                    : strftime(
                        "%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00')
                    )),
                'user_number_logins' => ($data['User Active'] == '' ? '' : number($data['User Login Count'])),


                'roles' => $data['roles']
            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function contractors($_data, $db, $user) {


    $rtext_label = 'contractor';

    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();
    foreach ($db->query($sql) as $data) {


        switch ($data['User Active']) {
            case 'Yes':
                $user_active = _('Active');
                break;
            case 'No':
                $user_active = _('Suspended');
                break;
            case '':
                $user_active = _("Don't set up");
                break;
            default:
                $user_active = $data['User Active'];
                break;
        }

        switch ($data['Staff Type']) {
            case 'Employee':
                $type = _('Employee');
                break;
            case 'Volunteer':
                $type = _('Volunteer');
                break;
            case 'TemporalWorker':
                $type = _("Temporal worker");
                break;
            case 'WorkExperience':
                $type = _("Work experience");
                break;
            default:
                $type = $data['Staff Type'];
                break;
        }

        $adata[] = array(
            'id'           => (integer)$data['Staff Key'],
            'formatted_id' => sprintf("%04d", $data['Staff Key']),
            'payroll_id'   => $data['Staff ID'],
            'name'         => $data['Staff Name'],
            'code'         => $data['Staff Alias'],
            'code_link'    => $data['Staff Alias'],


            'birthday' => (($data['Staff Birthday'] == '' or $data['Staff Birthday'] == '0000-00-00 00:00:00')
                ? ''
                : strftime(
                    "%e %b %Y", strtotime($data['Staff Birthday'].' +0:00')
                )),

            'official_id'  => $data['Staff Official ID'],
            'email'        => $data['Staff Email'],
            'telephone'    => $data['Staff Telephone Formatted'],
            'next_of_kind' => $data['Staff Next of Kind'],
            'from'         => (($data['Staff Valid From'] == '' or $data['Staff Valid From'] == '0000-00-00 00:00:00')
                ? ''
                : strftime(
                    "%e %b %Y", strtotime($data['Staff Valid From'].' +0:00')
                )),

            'until' => (($data['Staff Valid To'] == '' or $data['Staff Valid To'] == '0000-00-00 00:00:00')
                ? ''
                : strftime(
                    "%e %b %Y", strtotime($data['Staff Valid To'].' +0:00')
                )),
            'type'  => $type,


            'supervisors' => $data['supervisors'],

            'job_title'          => $data['Staff Job Title'],
            'user_login'         => $data['User Handle'],
            'user_active'        => $user_active,
            'user_last_login'    => ($data['User Last Login'] == ''
                ? ''
                : strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00')
                )),
            'user_number_logins' => ($data['User Active'] == ''
                ? ''
                : number(
                    $data['User Login Count']
                )),


            'roles' => $data['roles']
        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function timesheets($_data, $db, $user) {

    $rtext_label = 'timesheet';
    include_once 'prepare_table/init.php';
    include_once 'utils/natural_language.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $date = strtotime($data['Timesheet Date']);
            /*

            $clocked_hours=$data['Timesheet Clocked Time']/3600;
            $breaks_hours=$data['Timesheet Breaks Time']/3600;
            $work_time_hours=$data['Timesheet Working Time']/3600;
            $unpaid_overtime=$data['Timesheet Unpaid Overtime']/3600;
            $paid_overtime=$data['Timesheet Paid Overtime']/3600;
            $worked_time=$data['worked_time']/3600;
        */
            $clocked_hours   = $data['clocked_time'] / 3600;
            $breaks_hours    = $data['breaks'] / 3600;
            $work_time_hours = $data['work_time'] / 3600;
            $unpaid_overtime = $data['unpaid_overtime'] / 3600;
            $paid_overtime   = $data['paid_overtime'] / 3600;
            $worked_time     = $data['worked_time'] / 3600;

            $alert = ($data['Timesheet Missing Clocking Records'] > 0 ? '<i class="fa fa-exclamation-circle warning"></i>' : '');

            $adata[] = array(
                'id'           => (integer)$data['Timesheet Key'],
                'staff_key'    => (integer)$data['Timesheet Staff Key'],
                'formatted_id' => '<i class="fa fa-calendar"></i>',
                'date_key'     => date('Ymd', $date),
                'alert'        => $alert,

                'staff_formatted_id' => sprintf(
                    "%04d", $data['Timesheet Staff Key']
                ),
                'alias'              => $data['Staff Alias'],
                'name'               => $data['Staff Name'],
                'payroll_id'         => $data['Staff ID'],
                'date'               => ($data['Timesheet Date'] != '' ? strftime("%a %e %b %Y", $date) : ''),
                //'clocked_time'=> ($clocked_hours!=0?sprintf("%s %s", number($clocked_hours, 2), _('h')):'<span class="disabled">-</span>'),
                //'breaks_time'=> ($breaks_hours!=0?sprintf("%s %s", number($breaks_hours, 2), _('h')):'<span class="disabled">-</span>'),
                //'work_time_hours'=> ($work_time_hours!=0?sprintf("%s %s", number($work_time_hours, 2), _('h')):'<span class="disabled">-</span>'),
                //'unpaid_overtime'=> ($unpaid_overtime!=0?sprintf("%s %s", number($unpaid_overtime, 2), _('h')):'<span class="disabled">-</span>'),
                //'paid_overtime'=> ($paid_overtime!=0?sprintf("%s %s", number($paid_overtime, 2), _('h')):'<span class="disabled">-</span>'),
                //'worked_time'=> ($worked_time!=0?sprintf("%s %s", number($worked_time, 2), _('h')):'<span class="disabled">-</span>'),
                'clocked_time'       => ($clocked_hours != 0 ? '<span title="'.sprintf("%s %s", number($clocked_hours, 3), _('h')).'">'.seconds_to_hourminutes($data['clocked_time']).'</span>'
                    : '<span class="disabled">-</span>'),
                'breaks_time'        => ($breaks_hours != 0 ? '<span title="'.sprintf("%s %s", number($breaks_hours, 3), _('h')).'">'.seconds_to_hourminutes($data['breaks']).'</span>'
                    : '<span class="disabled">-</span>'),
                'work_time_hours'    => ($work_time_hours != 0 ? '<span title="'.sprintf("%s %s", number($work_time_hours, 3), _('h')).'">'.seconds_to_hourminutes($data['work_time']).'</span>'
                    : '<span class="disabled">-</span>'),
                'unpaid_overtime'    => ($unpaid_overtime != 0 ? '<span title="'.sprintf("%s %s", number($unpaid_overtime, 3), _('h')).'">'.seconds_to_hourminutes($data['unpaid_overtime']).'</span>'
                    : '<span class="disabled">-</span>'),
                'paid_overtime'      => ($paid_overtime != 0 ? '<span title="'.sprintf("%s %s", number($paid_overtime, 3), _('h')).'">'.seconds_to_hourminutes($data['paid_overtime']).'</span>'
                    : '<span class="disabled">-</span>'),
                'worked_time'        => ($worked_time != 0 ? '<span title="'.sprintf("%s %s", number($worked_time, 3), _('h')).'">'.seconds_to_hourminutes($data['worked_time']).'</span>'
                    : '<span class="disabled">-</span>'),


                'clocking_records' => number(
                    $data['Timesheet Clocking Records']
                )

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function timesheet_records($_data, $db, $user) {

    $rtext_label = 'request';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Timesheet Record Source']) {
                case 'ClockingMachine':
                    $source = _('Clocking machine');
                    break;
                case 'Manual':
                    $source = _('Manual').' '.($data['authoriser'] != '' ? '('.$data['authoriser'].')' : '');
                    break;
                case 'API':
                    $source = 'API';
                    break;
                default:
                    $source = $data['Timesheet Record Source'];
                    break;
            }
            switch ($data['Timesheet Record Type']) {
                case 'WorkingHoursMark':
                    $type = _('Working hours mark');
                    break;
                case 'OvertimeMark':
                    $type = _('Overtime mark');
                    break;
                case 'BreakMark':
                    $type = _('Break mark');
                    break;
                case 'ClockingRecord':
                    $type = _('Clocking record');
                    break;


                default:
                    $type = $data['Timesheet Record Type'];
                    break;
            }


            switch ($data['Timesheet Record Action Type']) {
                case 'Start':

                    if ($data['Timesheet Record Ignored Due Missing End'] == 'Yes') {
                        $warning = ' <i title="'._('No associated clock out').'" class="fa fa-exclamation-circle warning"></i>';
                    } else {
                        $warning = '';
                    }
                    $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'"><span  class="success"><i class="fa fa-fw fa-sign-in"></i> '._('In').'</span> '.$warning.'</span>';

                    break;
                case 'End':
                    $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'" ><span class="error"><i class="fa fa-fw fa-sign-out"></i> '._('Out').'</span></span>';
                    break;
                case 'Unknown':
                    $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'"  ><span class="disabled"><i class="fa fa-fw fa-question"></i> '._('Unknown').'</span></span>';
                    break;
                case 'Ignored':
                    $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'"  ><span class="disabled"><i class="fa fa-fw fa-eye-slash"></i> '._('Ignored').' '.($data['ignorer'] != ''
                            ? '('.$data['ignorer'].')' : '').'</span></span>';
                    break;
                case 'MarkStart':
                    if ($data['Timesheet Record Type'] == 'WorkingHoursMark') {
                        $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'"><span  class="disabled"><i class="fa fa-fw fa-map-marker"></i> '._('Start').'</span></span>';
                    } else {
                        $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'"><span  class="disabled"><i class="fa fa-fw fa-cutlery"></i> '._('End').'</span></span>';
                    }

                    break;
                case 'MarkEnd':
                    if ($data['Timesheet Record Type'] == 'WorkingHoursMark') {
                        $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'" ><span class="disabled"><i class="fa fa-fw fa-map-marker"></i> '._('End').'</span></span>';
                    } else {
                        $action_type = '<span id="action_type_'.$data['Timesheet Record Key'].'" ><span class="disabled"><i class="fa fa-fw fa-cutlery"></i> '._('Start').'</span></span>';
                    }

                    break;
                default:
                    $action_type = $data['Timesheet Record Action Type'];
                    break;
            }

            if ($data['Timesheet Record Type'] == 'ClockingRecord') {

                switch ($data['Timesheet Record Ignored']) {
                    case 'Yes':
                        $ignored = _('Yes');
                        $used    = sprintf(
                            '<i id="used_%d" value="No" onClick="toggle_ignore_record(%d)" class="fa fa-fw fa-square-o checkbox"></i>', $data['Timesheet Record Key'], $data['Timesheet Record Key']
                        );
                        break;
                    case 'No':
                        $ignored = _('No');
                        $used    = sprintf(
                            '<i id="used_%d" value="Yes" onClick="toggle_ignore_record(%d)" class="fa fa-fw fa-check-square-o checkbox"></i>', $data['Timesheet Record Key'],
                            $data['Timesheet Record Key']
                        );
                        break;


                    default:
                        $ignored = $data['Timesheet Record Ignored'];
                        $used    = '';
                        break;
                }
            } else {
                $ignored = $data['Timesheet Record Ignored'];
                $used    = '';
            }

            $notes = sprintf(
                '<span class="button" key="%d" onclick="open_timesheet_record_notes(this)" id="notes_%d" >%s<span class="note">%s</span></span>', $data['Timesheet Record Key'],$data['Timesheet Record Key'],'<i class="fa fa-sticky-note-o very_discreet '.($data['Timesheet Record Note']!=''?'hide':'').'" aria-hidden="true"></i> ',($data['Timesheet Record Note'])
            );



            $adata[] = array(

                'id'                     => (integer)$data['Timesheet Record Key'],
                'staff_key'              => (integer)$data['Timesheet Record Staff Key'],
                'timesheet_key'          => (integer)$data['Timesheet Record Timesheet Key'],
                'staff_formatted_id'     => sprintf("%04d", $data['Timesheet Record Staff Key']),
                'formatted_id'           => sprintf("%06d", $data['Timesheet Record Key']),
                'formatted_timesheet_id' => sprintf("%06d", $data['Timesheet Record Timesheet Key']),
                'alias'                  => $data['Staff Alias'],
                'name'                   => $data['Staff Name'],
                'type'                   => $type,
                'action_type'            => $action_type,
                'source'                 => $source,
                'ignored'                => $ignored,
                'used'                   => $used,
                'notes'                  => $notes,
                'date'                   => ($data['Timesheet Record Date'] != '' ? strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Timesheet Record Date'])) : ''),
                'time'                   => ($data['Timesheet Record Date'] != '' ? strftime("%H:%M:%S", strtotime($data['Timesheet Record Date'])) : ''),



            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function overtimes($_data, $db, $user) {

    $rtext_label = 'overtime';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Overtime Status']) {
                case 'Active':
                    $status = sprintf(
                        '<i id="status_%d"  onClick="suspend_overtime(%d)" class="fa fa-fw fa-play checkbox"></i>', $data['Overtime Key'], $data['Overtime Key']
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i id="status_%d"  onClick="active_overtime(%d)" class="fa fa-fw fa-pause checkbox"></i>', $data['Overtime Key'], $data['Overtime Key']
                    );
                    break;
                case 'Pending':
                    $used = sprintf(
                        '<i  class="fa fa-fw fa-hourglass-start "></i>', $data['Timesheet Record Key'], $data['Timesheet Record Key']
                    );
                    break;
                case 'Finish':
                    $used = sprintf(
                        '<i  class="fa fa-fw fa-step-forward "></i>', $data['Timesheet Record Key'], $data['Timesheet Record Key']
                    );
                    break;

                default:
                    $ignored = $data['Timesheet Record Ignored'];
                    $used    = '';
                    break;
            }


            $adata[] = array(
                'id'            => (integer)$data['Overtime Key'],
                'formatted_key' => sprintf('%04d', $data['Overtime Key']),
                'reference'     => $data['Overtime Reference'],
                'status'        => $status,

                'start' => ($data['Overtime Start Date'] != '' ? strftime(
                    "%e %b %Y", strtotime($data['Overtime Start Date'])
                ) : ''),
                'end'   => ($data['Overtime End Date'] != '' ? strftime(
                    "%e %b %Y", strtotime($data['Overtime End Date'])
                ) : ''),


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function months($_data, $db, $user) {

    $rtext_label = 'month';
    include_once 'prepare_table/init.php';


    $sql
        = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";
    //print $sql;
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {
            $date    = strtotime($data['Timesheet Date']);
            $adata[] = array(
                'name'       => strftime("%B", $date),
                'key'        => date('Ym', $date),
                'timesheets' => number($data['timesheets']),
                'days'       => number($data['days']),
                'employees'  => number($data['employees'])

            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function weeks($_data, $db, $user) {

    $rtext_label = 'week';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";
    //print $sql;
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {
            $adata[] = array(
                'name'          => sprintf('%02d', $data['week']),
                'key'           => $data['yearweek'],
                'week_starting' => strftime(
                    "%e %b %Y", strtotime($data['week_starting'])
                ),
                'timesheets'    => number($data['timesheets']),
                'days'          => number($data['days']),
                'employees'     => number($data['employees'])
            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function days($_data, $db, $user) {

    $rtext_label = 'day';
    include_once 'prepare_table/init.php';


    $sql
        = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";

    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {
            $date    = strtotime($data['Timesheet Date']);
            $adata[] = array(
                'name' => strftime("%e %b %Y", $date),
                'key'  => date('Ymd', $date),

                'day_of_week' => strftime("%a", $date),
                'timesheets'  => number($data['timesheets']),
                'days'        => number($data['days']),
                'employees'   => number($data['employees'])


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function timesheets_employees($_data, $db, $user) {

    $rtext_label = 'employee';
    include_once 'prepare_table/init.php';
    include_once 'utils/natural_language.php';

    $sql
        = "select $fields from $table $where $wheref $group_by order by $order $order_direction ";
    //print $sql;
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $date = strtotime($data['Timesheet Date']);

            $clocked_hours   = $data['clocked_time'] / 3600;
            $breaks_hours    = $data['breaks'] / 3600;
            $work_time_hours = $data['work_time'] / 3600;
            $unpaid_overtime = $data['unpaid_overtime'] / 3600;
            $paid_overtime   = $data['paid_overtime'] / 3600;
            $worked_time     = $data['worked_time'] / 3600;

            $worked_time_monday    = $data['worked_time_monday'] / 3600;
            $worked_time_tuesday   = $data['worked_time_tuesday'] / 3600;
            $worked_time_wednesday = $data['worked_time_wednesday'] / 3600;
            $worked_time_thursday  = $data['worked_time_thursday'] / 3600;
            $worked_time_friday    = $data['worked_time_friday'] / 3600;
            $worked_time_saturday  = $data['worked_time_saturday'] / 3600;
            $worked_time_sunday    = $data['worked_time_sunday'] / 3600;
            $worked_time_workweek  = $data['worked_time_workweek'] / 3600;
            $worked_time_weekend   = $data['worked_time_weekend'] / 3600;

            $clocked_time_monday    = $data['clocked_time_monday'] / 3600;
            $clocked_time_tuesday   = $data['clocked_time_tuesday'] / 3600;
            $clocked_time_wednesday = $data['clocked_time_wednesday'] / 3600;
            $clocked_time_thursday  = $data['clocked_time_thursday'] / 3600;
            $clocked_time_friday    = $data['clocked_time_friday'] / 3600;
            $clocked_time_saturday  = $data['clocked_time_saturday'] / 3600;
            $clocked_time_sunday    = $data['clocked_time_sunday'] / 3600;
            $clocked_time_workweek  = $data['clocked_time_workweek'] / 3600;
            $clocked_time_weekend   = $data['clocked_time_weekend'] / 3600;

            $work_time_monday    = $data['work_time_monday'] / 3600;
            $work_time_tuesday   = $data['work_time_tuesday'] / 3600;
            $work_time_wednesday = $data['work_time_wednesday'] / 3600;
            $work_time_thursday  = $data['work_time_thursday'] / 3600;
            $work_time_friday    = $data['work_time_friday'] / 3600;
            $work_time_saturday  = $data['work_time_saturday'] / 3600;
            $work_time_sunday    = $data['work_time_sunday'] / 3600;
            $work_time_workweek  = $data['work_time_workweek'] / 3600;
            $work_time_weekend   = $data['work_time_weekend'] / 3600;

            $unpaid_overtime_monday    = $data['unpaid_overtime_monday'] / 3600;
            $unpaid_overtime_tuesday   = $data['unpaid_overtime_tuesday'] / 3600;
            $unpaid_overtime_wednesday = $data['unpaid_overtime_wednesday'] / 3600;
            $unpaid_overtime_thursday  = $data['unpaid_overtime_thursday'] / 3600;
            $unpaid_overtime_friday    = $data['unpaid_overtime_friday'] / 3600;
            $unpaid_overtime_saturday  = $data['unpaid_overtime_saturday'] / 3600;
            $unpaid_overtime_sunday    = $data['unpaid_overtime_sunday'] / 3600;
            $unpaid_overtime_workweek  = $data['unpaid_overtime_workweek'] / 3600;
            $unpaid_overtime_weekend   = $data['unpaid_overtime_weekend'] / 3600;

            $paid_overtime_monday    = $data['paid_overtime_monday'] / 3600;
            $paid_overtime_tuesday   = $data['paid_overtime_tuesday'] / 3600;
            $paid_overtime_wednesday = $data['paid_overtime_wednesday'] / 3600;
            $paid_overtime_thursday  = $data['paid_overtime_thursday'] / 3600;
            $paid_overtime_friday    = $data['paid_overtime_friday'] / 3600;
            $paid_overtime_saturday  = $data['paid_overtime_saturday'] / 3600;
            $paid_overtime_sunday    = $data['paid_overtime_sunday'] / 3600;
            $paid_overtime_workweek  = $data['paid_overtime_workweek'] / 3600;
            $paid_overtime_weekend   = $data['paid_overtime_weekend'] / 3600;

            $adata[] = array(
                'staff_key'        => $data['Timesheet Staff Key'],
                'name'             => $data['Staff Name'],
                'days'             => number($data['days']),
                'clocking_records' => number($data['clocking_records']),

                //'clocked_time'=> ($clocked_hours!=0?sprintf("%s %s", number($clocked_hours, 2, 2), _('h')):'<span class="disabled">-</span>'),
                //'breaks_time'=> ($breaks_hours!=0?sprintf("%s %s", number($breaks_hours, 2, 2), _('h')):'<span class="disabled">-</span>'),
                //'work_time_hours'=> ($work_time_hours!=0?sprintf("%s %s", number($work_time_hours, 2, 2), _('h')):'<span class="disabled">-</span>'),
                //'unpaid_overtime'=> ($unpaid_overtime!=0?sprintf("%s %s", number($unpaid_overtime, 2, 2), _('h')):'<span class="disabled">-</span>'),
                //'paid_overtime'=> ($paid_overtime!=0?sprintf("%s %s", number($paid_overtime, 2, 2), _('h')):'<span class="disabled">-</span>'),
                //'worked_time'=> ($worked_time!=0?sprintf("%s %s", number($worked_time, 2, 2), _('h')):'<span class="disabled">-</span>'),

                'clocked_time'    => ($clocked_hours != 0 ? '<span title="'.sprintf("%s %s", number($clocked_hours, 3), _('h')).'">'.seconds_to_hourminutes($data['clocked_time']).'</span>'
                    : '<span class="disabled">-</span>'),
                'breaks_time'     => ($breaks_hours != 0 ? '<span title="'.sprintf(
                        "%s %s", number($breaks_hours, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['breaks']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_hours' => ($work_time_hours != 0 ? '<span title="'.sprintf("%s %s", number($work_time_hours, 3), _('h')).'">'.seconds_to_hourminutes($data['work_time']).'</span>'
                    : '<span class="disabled">-</span>'),
                'unpaid_overtime' => ($unpaid_overtime != 0 ? '<span title="'.sprintf("%s %s", number($unpaid_overtime, 3), _('h')).'">'.seconds_to_hourminutes($data['unpaid_overtime']).'</span>'
                    : '<span class="disabled">-</span>'),
                'paid_overtime'   => ($paid_overtime != 0 ? '<span title="'.sprintf("%s %s", number($paid_overtime, 3), _('h')).'">'.seconds_to_hourminutes($data['paid_overtime']).'</span>'
                    : '<span class="disabled">-</span>'),
                'worked_time'     => ($worked_time != 0 ? '<span title="'.sprintf("%s %s", number($worked_time, 3), _('h')).'">'.seconds_to_hourminutes($data['worked_time']).'</span>'
                    : '<span class="disabled">-</span>'),

                'worked_time_monday'    => ($worked_time_monday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_monday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_monday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_tuesday'   => ($worked_time_tuesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_tuesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_tuesday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_wednesday' => ($worked_time_wednesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_wednesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['worked_time_wednesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_thursday'  => ($worked_time_thursday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_thursday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_thursday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_friday'    => ($worked_time_friday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_friday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_friday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_saturday'  => ($worked_time_saturday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_saturday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_saturday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_sunday'    => ($worked_time_sunday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_sunday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_sunday']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_workweek'  => ($worked_time_workweek != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_workweek, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_workweek']).'</span>' : '<span class="disabled">-</span>'),
                'worked_time_weekend'   => ($worked_time_weekend != 0 ? '<span title="'.sprintf(
                        "%s %s", number($worked_time_weekend, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['worked_time_weekend']).'</span>' : '<span class="disabled">-</span>'),

                'clocked_time_monday'    => ($clocked_time_monday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_monday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['clocked_time_monday']).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_tuesday'   => ($clocked_time_tuesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_tuesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['clocked_time_tuesday']).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_wednesday' => ($clocked_time_wednesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_wednesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['clocked_time_wednesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_thursday'  => ($clocked_time_thursday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_thursday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['clocked_time_thursday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_friday'    => ($clocked_time_friday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_friday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['clocked_time_friday']).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_saturday'  => ($clocked_time_saturday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_saturday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['clocked_time_saturday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_sunday'    => ($clocked_time_sunday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_sunday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['clocked_time_sunday']).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_workweek'  => ($clocked_time_workweek != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_workweek, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['clocked_time_workweek']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'clocked_time_weekend'   => ($clocked_time_weekend != 0 ? '<span title="'.sprintf(
                        "%s %s", number($clocked_time_weekend, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['clocked_time_weekend']).'</span>' : '<span class="disabled">-</span>'),

                'work_time_monday'    => ($work_time_monday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_monday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_monday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_tuesday'   => ($work_time_tuesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_tuesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_tuesday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_wednesday' => ($work_time_wednesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_wednesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_wednesday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_thursday'  => ($work_time_thursday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_thursday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_thursday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_friday'    => ($work_time_friday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_friday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_friday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_saturday'  => ($work_time_saturday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_saturday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_saturday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_sunday'    => ($work_time_sunday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_sunday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_sunday']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_workweek'  => ($work_time_workweek != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_workweek, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_workweek']).'</span>' : '<span class="disabled">-</span>'),
                'work_time_weekend'   => ($work_time_weekend != 0 ? '<span title="'.sprintf(
                        "%s %s", number($work_time_weekend, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['work_time_weekend']).'</span>' : '<span class="disabled">-</span>'),


                'unpaid_overtime_monday'    => ($unpaid_overtime_monday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_monday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_monday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_tuesday'   => ($unpaid_overtime_tuesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_tuesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_tuesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_wednesday' => ($unpaid_overtime_wednesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_wednesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_wednesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_thursday'  => ($unpaid_overtime_thursday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_thursday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_thursday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_friday'    => ($unpaid_overtime_friday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_friday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_friday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_saturday'  => ($unpaid_overtime_saturday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_saturday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_saturday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_sunday'    => ($unpaid_overtime_sunday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_sunday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_sunday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_workweek'  => ($unpaid_overtime_workweek != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_workweek, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_workweek']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'unpaid_overtime_weekend'   => ($unpaid_overtime_weekend != 0 ? '<span title="'.sprintf(
                        "%s %s", number($unpaid_overtime_weekend, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['unpaid_overtime_weekend']
                    ).'</span>' : '<span class="disabled">-</span>'),


                'paid_overtime_monday'    => ($paid_overtime_monday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_monday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['paid_overtime_monday']).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_tuesday'   => ($paid_overtime_tuesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_tuesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_tuesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_wednesday' => ($paid_overtime_wednesday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_wednesday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_wednesday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_thursday'  => ($paid_overtime_thursday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_thursday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_thursday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_friday'    => ($paid_overtime_friday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_friday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['paid_overtime_friday']).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_saturday'  => ($paid_overtime_saturday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_saturday, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_saturday']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_sunday'    => ($paid_overtime_sunday != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_sunday, 3), _('h')
                    ).'">'.seconds_to_hourminutes($data['paid_overtime_sunday']).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_workweek'  => ($paid_overtime_workweek != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_workweek, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_workweek']
                    ).'</span>' : '<span class="disabled">-</span>'),
                'paid_overtime_weekend'   => ($paid_overtime_weekend != 0 ? '<span title="'.sprintf(
                        "%s %s", number($paid_overtime_weekend, 3), _('h')
                    ).'">'.seconds_to_hourminutes(
                        $data['paid_overtime_weekend']
                    ).'</span>' : '<span class="disabled">-</span>'),


            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function deleted_employees($_data, $db, $user) {

    $rtext_label = 'deleted employee';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'          => (integer)$data['Staff Deleted Key'],
            'employee_id' => $data['Staff Deleted ID'],
            'alias'       => $data['Staff Deleted Alias'],
            'name'        => $data['Staff Deleted Name'],
            'date'        => ($data['Staff Deleted Date'] != '' ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Staff Deleted Date'])
            ) : ''),

        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function deleted_contractors($_data, $db, $user) {

    $rtext_label = 'deleted contractor';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'          => (integer)$data['Staff Deleted Key'],
            'employee_id' => $data['Staff Deleted ID'],
            'alias'       => $data['Staff Deleted Alias'],
            'name'        => $data['Staff Deleted Name'],
            'date'        => ($data['Staff Deleted Date'] != '' ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Staff Deleted Date'])
            ) : ''),

        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function positions($_data, $db, $user) {

   // $rtext_label = 'job position';
    include_once 'prepare_table/init.php';

    include 'conf/roles.php';

    $sql = "select $fields from $table $where $wheref $group_by ";

   // print $sql;

    $base_data = $roles;
    foreach ($db->query($sql) as $data) {

        if(isset($base_data[$data['Role Code']]))
        $base_data[$data['Role Code']] = array_merge($base_data[$data['Role Code']],$data);
    }

    foreach ($base_data as $key => $data) {

        $adata[] = array(
            'id'      => $key,
            '_position'         => $data['title'],
            'position'         => sprintf('<span class="button" onClick="change_view(\'hr/position/%s\')">%s</span>',$key,$data['title']),
            '_employees' => (isset($data['employees'])? $data['employees']:0),

            'employees' => (isset($data['employees'])? number($data['employees']):0)
        );

    }


    foreach ($adata as $key => $row) {
        $positions[$key]  = $row['_position'];
        $employees[$key] = $row['_employees'];
    }


    //print_r($positions);


  if($order=='position'){
      if($order_direction=='desc'){
          array_multisort($positions, SORT_DESC, $adata);

      }else{
          array_multisort($positions, SORT_ASC, $adata);

      }

  }elseif($order=='employees'){
        if($order_direction=='desc'){
            array_multisort($employees, SORT_DESC, $adata);

        }else{
            array_multisort($employees, SORT_ASC, $adata);

        }

    }


  //  print $order.' '.$order_direction;



    $total_records = count($roles);
    $rtext         = sprintf(ngettext('%s job position', '%s job positions', $total_records), number($total_records));

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $order,
            'sort_dir'      => $order_direction,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
