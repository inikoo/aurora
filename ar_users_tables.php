<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 11:45:16 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('users')) {
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
    case 'users':
        users(get_table_parameters(), $db, $user);
        break;
    case 'user_categories':
        user_categories(get_table_parameters(), $db, $user);
        break;
    case 'staff':
        staff(get_table_parameters(), $db, $user);
        break;
    case 'contractors':
        contractors(get_table_parameters(), $db, $user);
        break;
    case 'suppliers':
        suppliers(get_table_parameters(), $db, $user);
        break;
    case 'agents':
        agents(get_table_parameters(), $db, $user);
        break;

    case 'login_history':
        login_history(get_table_parameters(), $db, $user);
        break;
    case 'api_keys':
        api_keys(get_table_parameters(), $db, $user);
        break;
    case 'api_requests':
        api_requests(get_table_parameters(), $db, $user);
        break;
    case 'deleted_users':
        deleted_users(get_table_parameters(), $db, $user);
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


function users($_data, $db, $user) {

    $rtext_label = 'user';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $adata = array();

    foreach ($db->query($sql) as $data) {
        if ($data['User Active'] == 'Yes') {
            $is_active = _('Yes');
        } else {
            $is_active = _('No');
        }

        $groups     = preg_split('/,/', $data['Groups']);
        $stores     = preg_split('/,/', $data['Stores']);
        $warehouses = preg_split('/,/', $data['Warehouses']);
        $sites      = preg_split('/,/', $data['Sites']);

        $adata[] = array(
            'id'              => (integer)$data['User Key'],
            'handle'          => $data['User Handle'],
            'name'            => $data['User Alias'],
            'active'          => $is_active,
            'logins'          => number($data['User Login Count']),
            'last_login'      => ($data ['User Last Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Login']." +00:00")
                )),
            'fail_logins'     => number($data['User Failed Login Count']),
            'fail_last_login' => ($data ['User Last Failed Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Failed Login']." +00:00")
                )),

            'groups'     => $data['Groups'],
            'stores'     => $stores,
            'warehouses' => $warehouses,
            'websites'   => $data['Sites'],
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


function contractors($_data, $db, $user) {

    $rtext_label = 'user';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {
        if ($data['User Active'] == 'Yes') {
            $is_active = _('Yes');
        } else {
            $is_active = _('No');
        }

        $groups     = preg_split('/,/', $data['Groups']);
        $stores     = preg_split('/,/', $data['Stores']);
        $warehouses = preg_split('/,/', $data['Warehouses']);
        $sites      = preg_split('/,/', $data['Sites']);

        $adata[] = array(
            'id'              => (integer)$data['User Key'],
            'handle'          => $data['User Handle'],
            'name'            => $data['User Alias'],
            'active'          => $is_active,
            'logins'          => number($data['User Login Count']),
            'last_login'      => ($data ['User Last Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Login']." +00:00")
                )),
            'fail_logins'     => number($data['User Failed Login Count']),
            'fail_last_login' => ($data ['User Last Failed Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Failed Login']." +00:00")
                )),

            'groups'     => $data['Groups'],
            'stores'     => $stores,
            'warehouses' => $warehouses,
            'websites'   => $data['Sites'],
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


function agents($_data, $db, $user) {

    $rtext_label = 'user';
    include_once 'prepare_table/init.php';

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {
        if ($data['User Active'] == 'Yes') {
            $is_active = _('Yes');
        } else {
            $is_active = _('No');
        }

        $groups     = preg_split('/,/', $data['Groups']);
        $stores     = preg_split('/,/', $data['Stores']);
        $warehouses = preg_split('/,/', $data['Warehouses']);
        $sites      = preg_split('/,/', $data['Sites']);

        $adata[] = array(
            'id'              => (integer)$data['User Key'],
            'handle'          => $data['User Handle'],
            'name'            => $data['User Alias'],
            'active'          => $is_active,
            'logins'          => number($data['User Login Count']),
            'last_login'      => ($data ['User Last Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Login']." +00:00")
                )),
            'fail_logins'     => number($data['User Failed Login Count']),
            'fail_last_login' => ($data ['User Last Failed Login'] == ''
                ? ''
                : strftime(
                    "%e %b %Y %H:%M %Z", strtotime($data ['User Last Failed Login']." +00:00")
                )),

            'groups'     => $data['Groups'],
            'stores'     => $stores,
            'warehouses' => $warehouses,
            'websites'   => $data['Sites'],
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


function login_history($_data, $db, $user) {

    $rtext_label = 'session';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'id'          => (integer)$data['User Log Key'],
            'user_key'    => (integer)$data['User Key'],
            'handle'      => $data['User Handle'],
            'user'        => $data['User Alias'],
            'parent_key'  => $data['User Parent Key'],
            'ip'          => $data['IP'],
            'login_date'  => strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Start Date'])
            ),
            'logout_date' => ($data['Logout Date'] != '' ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Logout Date'])
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


function user_categories($_data, $db, $user) {

    $rtext_label = 'user category';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $base_data = array(
        'Staff'         => array(
            'User Type'    => 'Staff',
            'active_users' => 0
        ),
        'Contractor'    => array(
            'User Type'    => 'Contractor',
            'active_users' => 0
        ),
        'Warehouse'     => array(
            'User Type'    => 'Warehouse',
            'active_users' => 0
        ),
        'Administrator' => array(
            'User Type'    => 'Administrator',
            'active_users' => 0
        ),
        'Supplier'      => array(
            'User Type'    => 'Supplier',
            'active_users' => 0
        ),
        'Agent'         => array(
            'User Type'    => 'Agent',
            'active_users' => 0
        ),

    );

    foreach ($db->query($sql) as $data) {

        $base_data[$data['User Type']] = $data;
    }

    foreach ($base_data as $key => $data) {

        switch ($data['User Type']) {
            case 'Staff':
                $type    = _('Employees');
                $request = 'account/users/staff';
                break;
            case 'Contractor':
                $type    = _('Contractors');
                $request = 'account/users/contractors';
                break;
            case 'Warehouse':
                $type    = _('Warehouse');
                $request = 'account/users/warehouse';
                break;
            case 'Administrator':
                $type    = _('Adminstrator');
                $request = 'account/users/root';
                break;
            case 'Supplier':
                $type    = _('Suppliers');
                $request = 'account/users/suppliers';
                break;
            case 'Agent':
                $type    = _('Agents');
                $request = 'account/users/agents';
                break;
            default:
                $type = '**'.$data['User Type'];
                break;
        }

        $adata[] = array(
            'request'      => $request,
            'type'         => $type,
            'active_users' => number($data['active_users']),
        );

    }
    $total_records = 6;
    $rtext         = sprintf(
        ngettext('%s user category', '%s user categories', $total_records), number($total_records)
    );

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

function deleted_users($_data, $db, $user) {

    $rtext_label = 'users';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    foreach ($db->query($sql) as $data) {

        switch ($data['User Deleted Type']) {
            case 'Staff':
                $type = _('Employee');
                break;
            case 'Supplier':
                $type = _('Supplier');
                break;
            case 'Contractor':
                $type = _('Contractor');
                break;
            case 'Agent':
                $type = _('Agent');
                break;
            default:
                $type = $data['User Deleted Type'];
                break;
        }


        $adata[] = array(
            'id'     => (integer)$data['User Deleted Key'],
            'handle' => $data['User Deleted Handle'],
            'alias'  => $data['User Deleted Alias'],
            'type'   => $type,
            'date'   => ($data['User Deleted Date'] != '' ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['User Deleted Date'])
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


function api_keys($_data, $db, $user) {

    $rtext_label = 'session';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'id'          => (integer)$data['User Log Key'],
            'user_key'    => (integer)$data['User Key'],
            'handle'      => $data['User Handle'],
            'user'        => $data['User Alias'],
            'parent_key'  => $data['User Parent Key'],
            'ip'          => $data['IP'],
            'login_date'  => strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Start Date'])
            ),
            'logout_date' => ($data['Logout Date'] != '' ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Logout Date'])
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


function api_requests($_data, $db, $user) {

    $rtext_label = 'request';
    include_once 'prepare_table/init.php';

    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['API Key Scope']) {
                case 'Timesheet':
                    $scope = _('Timesheet');
                    break;
                default:
                    $scope = $data['API Key Scope'];
                    break;
            }

            switch ($data['Response']) {
                case 'OK':
                    $response = _('Success');
                    break;
                case 'Fail_Attempt':
                    $response = _('Fail attempt');
                    break;
                case 'Fail_Attempt':
                    $response = _('Fail attempt');
                    break;
                case 'Fail_TimeLimit':
                    $response = _('Fail to many requests');
                    break;
                case 'Fail_Access':
                    $response = _('Fail access');
                    break;
                case 'Fail_Operation':
                    $response = _('Fail operation');
                    break;
                case 'Fail_IP':
                    $response = _('Unauthorized IP');
                    break;
                default:
                    $response = $data['Response'];
                    break;
            }


            $response_code = $data['Response Code'];

            $adata[] = array(

                'user_key' => (integer)$data['API Key User Key'],
                'handle'   => $data['User Handle'],
                'scope'    => $scope,

                'formatted_id' => sprintf('%04d', $data['API Key Key']),
                'user'         => $data['User Alias'],

                'date'          => ($data['Date'] != '' ? strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($data['Date'])
                ) : ''),
                'ip'            => $data['IP'],
                'method'        => $data['HTTP Method'],
                'response'      => $response,
                'response_code' => $response_code,

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


?>
