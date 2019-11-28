<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 20:14:17 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('websites')) {
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
    case 'templates':
        templates(get_table_parameters(), $db, $user);
        break;
    case 'blocks':
        blocks(get_table_parameters(), $db, $user);
        break;
    case 'blocks':
        blocks(get_table_parameters(), $db, $user);
        break;
    case 'websites':
        websites(get_table_parameters(), $db, $user, $account, $redis);
        break;

    case 'versions':
        versions(get_table_parameters(), $db, $user);
        break;
    case 'webpages':
        webpages(get_table_parameters(), $db, $user);
        break;
    case 'in_process_webpages':
        webpages_in_process(get_table_parameters(), $db, $user);
        break;
    case 'ready_webpages':
        webpages_ready(get_table_parameters(), $db, $user);
        break;
    case 'online_webpages':
        webpages_online(get_table_parameters(), $db, $user);
        break;
    case 'offline_webpages':
        webpages_offline(get_table_parameters(), $db, $user);
        break;

    case 'pages':
        pages(get_table_parameters(), $db, $user);
        break;

    case 'queries':
        queries(get_table_parameters(), $db, $user);
        break;
    case 'search_history':
        search_history(get_table_parameters(), $db, $user);
        break;
    case 'users':
        users(get_table_parameters(), $db, $user);
        break;
    case 'webpage_types':
        webpage_types(get_table_parameters(), $db, $user);
        break;
    case 'webpage_assets':
        webpage_assets(get_table_parameters(), $db, $user);
        break;
    case 'webpage_containers':
        webpage_containers(get_table_parameters(), $db, $user);
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

    $rtext_label = 'website user';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'id'           => $data['Website User Key'],
            'customer_key' => $data['Customer Key'],
            'user'         => $data['Website User Handle'],
            'customer'     => $data['Customer Name'],
            'sessions'     => number($data['Website User Sessions Count']),
            'last_login'   => ($data['Website User Last Login'] ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Website User Last Login'].' +0:00')
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


function queries($_data, $db, $user) {

    $rtext_label = 'query';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'website_key' => $data['Website Key'],
            'date'     => strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['date'].' +0:00')
            ),
            'query'    => $data['Query'],
            'number'   => number($data['number']),
            'users'    => number($data['users']),
            'results'  => number($data['results'], 1),
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


function search_history($_data, $db, $user) {

    $rtext_label = 'search';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {


        $user = $data['User Alias'];

        $adata[] = array(
            'website_key' => $data['Website Key'],
            'date'     => strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['Date'].' +0:00')
            ),
            'query'    => $data['Query'],
            'user_key' => $data['User Key'],
            'user'     => $user,
            'results'  => number($data['Number Results']),
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


function websites($_data, $db, $user, $account, $redis) {

    $rtext_label = 'website';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {


        switch ($data['Website Status']) {
            case 'Active':
                $status = sprintf('<a href="https://%s" target="_blank"><i title="%s" class="fal success fa-broadcast-tower"></i></a>', $data['Website URL'], _('Live'));
                break;
            case 'InProcess':
                $status = sprintf('<i title="%s" class="fal fa-drafting-compass"></i>', _('In construction'));
                break;
            case 'Closed':
                $status = sprintf('<i title="%s" class="fa error fa-do-not-enter"></i>', _('Closed'));
                break;
        }

        $adata[] = array(
            'id'     => (integer)$data['Website Key'],
            'access' => (in_array($data['Website Store Key'], $user->stores) ? '<i title="'._('Website worker access').'" class="fa fa-fw   fa-user-hard-hat "></i>' : '<i title="'._('View only').'" style="color:#603cb8" class="fa fa-fw fa-mask "></i>'),

            'status' => $status,
            'code'   => sprintf('<span class="link" title="%s" onclick="change_view(\'website/%d\')">%s</span>', $data['Website Name'], $data['Website Key'], $data['Website Code']),
            'name'   => sprintf('<span class="link" onclick="change_view(\'website/%d\')">%s</span>', $data['Website Key'], $data['Website Name']),
            'url'    => '<a href="https://'.$data['Website URL'].'" target="_blank"> <i class="fal fa-external-link-alt padding_right_10"></i> </a> '.$data['Website URL'],

            'online_users' => '<span class="website_rt_user_'.$data['Website Key'].'">'.($data['Website Status'] == 'Active' ? count($redis->ZREVRANGE('_WU'.$account->get('Code').'|'.$data['Website Key'], 0, 10000)) : '').'</span>',
            'users'        => number($data['Website Total Acc Users']),
            'visitors'     => number($data['Website Total Acc Visitors']),
            'requests'     => number($data['Website Total Acc Requests']),
            'sessions'     => number($data['Website Total Acc Sessions']),

            'pages'                         => number($data['Website Number Online Webpages']),
            'pages_products'                => number($data['Website Number WebPages with Products']),
            'pages_out_of_stock'            => number($data['Website Number WebPages with Out of Stock Products']),
            'pages_out_of_stock_percentage' => percentage($data['Website Number WebPages with Out of Stock Products'], $data['Website Number WebPages with Products']),
            'products'                      => number($data['Website Number Products']),
            'out_of_stock'                  => number($data['Website Number Out of Stock Products']),
            'out_of_stock_percentage'       => percentage($data['Website Number Out of Stock Products'], $data['Website Number Products']),


            'gsc_clicks'      => number($data['Website GSC Clicks']),
            'gsc_impressions' => number($data['Website GSC Impressions']),
            'gsc_ctr'         => percentage($data['Website GSC CTR'], 1, 2),
            'gsc_position'    => round($data['Website GSC Position']),


            //'email_reminders_customers'=>number($data['Website Number Back in Stock Reminder Customers']),
            //'email_reminders_products'=>number($data['Website Number Back in Stock Reminder Products']),
            //'email_reminders_waiting'=>number($data['Website Number Back in Stock Reminder Waiting']),
            //'email_reminders_ready'=>number($data['Website Number Back in Stock Reminder Ready']),
            //'email_reminders_sent'=>number($data['Website Number Back in Stock Reminder Sent']),
            //'email_reminders_cancelled'=>number($data['Website Number Back in Stock Reminder Cancelled'])

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



function blocks($_data, $db, $user) {

    $rtext_label = 'webpage block';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'id'       => (integer)$data['Webpage Block Key'],
            'template' => $data['Webpage Block Template']


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

function versions($_data, $db, $user) {

    $rtext_label = 'version';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        switch ($data['Webpage Version Device']) {
            case 'Desktop':
                $device      = _('Desktop');
                $device_code = 'desk';
                break;
            case 'Tablet':
                $device      = _('Tablet');
                $device_code = 'tab';
                break;
            case 'Mobile':
                $device      = _('Mobile');
                $device_code = 'mob';

                break;
            default:
                $device      = $data['device'];
                $device_code = $data['device'];
                break;
        }

        $adata[] = array(
            'id'          => (integer)$data['Webpage Version Key'],
            'code'        => sprintf(
                '<span class="link" onclick="change_view(\'page/%d/version/%d\')">%s</span>', $data['Webpage Key'], $data['Webpage Version Key'], $data['Webpage Code'].'.'.$device_code.'.'.$data['Webpage Version Code']
            ),
            'device'      => $device,
            'probability' => $data['Webpage Version Display Probability'],


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


function templates($_data, $db, $user) {

    $rtext_label = 'template';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {


        switch ($data['Template Scope']) {
            case 'Product':
                $scope_icon = sprintf('<i class="fa fa-cube" aria-hidden="true" label="%s"></i>', _('Product'));
                $scope      = _('Product');
                break;
            case 'Blank':
                $scope_icon = sprintf('<i class="fa fa-file" aria-hidden="true" label="%s"></i>', _('Blank'));
                $scope      = _('Blank');
                break;
            case 'Category':
                $scope_icon = sprintf('<i class="fa fa-cubes" aria-hidden="true" label="%s"></i>', _('Category'));
                $scope      = _('Category');
                break;
            case 'Categories':
                $scope_icon = sprintf('<i class="fa fa-table" aria-hidden="true" label="%s"></i>', _('Categories'));
                $scope      = _('Categories');
                break;
            case 'Basket':
                $scope_icon = sprintf('<i class="fa fa-shopping-basket" aria-hidden="true" label="%s"></i>', _('Basket'));
                $scope      = _('Basket');
                break;
            case 'Checkout':
                $scope_icon = sprintf('<i class="fa fa-credit-card" aria-hidden="true" label="%s"></i>', _('Checkout'));
                $scope      = _('Checkout');
                break;
            case 'Hub':
                $scope_icon = sprintf('<i class="fa fa-sitemap" aria-hidden="true" label="%s"></i>', _('Hub'));
                $scope      = _('Hub');
                break;
            case 'Header':
                $scope_icon = sprintf('<i class="fa fa-header" aria-hidden="true" label="%s"></i>', _('Header'));
                $scope      = _('Header');
                break;
            case 'Footer':
                $scope_icon = sprintf('<i class="fa fa-minus" aria-hidden="true" label="%s"></i>', _('Footer'));
                $scope      = _('Footer');
                break;
            case 'Home':
                $scope_icon = sprintf('<i class="fa fa-home" aria-hidden="true" label="%s"></i>', _('Home'));
                $scope      = _('Home');
                break;
            case 'Login':
                $scope_icon = sprintf('<i class="fa fa-sign-in" aria-hidden="true" label="%s"></i>', _('Login'));
                $scope      = _('Login');
                break;
            case 'Contact':
                $scope_icon = sprintf('<i class="fa fa-phone" aria-hidden="true" label="%s"></i>', _('Contact'));
                $scope      = _('Contact');
                break;
            case 'Register':
                $scope_icon = sprintf('<i class="fa fa-user-plus" aria-hidden="true" label="%s"></i>', _('Register'));
                $scope      = _('Register');
                break;
            case 'ResetPwd':
                $scope_icon = sprintf('<i class="fa fa-key" aria-hidden="true" label="%s"></i>', _('Reset password'));
                $scope      = _('Reset password');

                break;
            case 'Profile':
                $scope_icon = sprintf('<i class="fa fa-user" aria-hidden="true" label="%s"></i>', _('Profile'));
                $scope      = _('Product');
                break;
            case 'Orders':
                $scope_icon = sprintf('<i class="fa fa-shopping-cart" aria-hidden="true" label="%s"></i>', _('Orders'));
                $scope      = _('Orders');
                break;
            default:
                $scope_icon = $data['Template Scope'];
                $scope      = $data['Template Scope'];
                break;
        }


        switch ($data['Template Device']) {
            case 'Desktop':
                $device = sprintf('<i class="fa fa-desktop" aria-hidden="true" label="%s"></i>', _('Desktop'));
                break;
            case 'Tablet':
                $device = sprintf('<i class="fa fa-tablet" aria-hidden="true" label="%s"></i>', _('Tablet'));
                break;
            case 'Mobile':
                $device = sprintf('<i class="fa fa-mobile" aria-hidden="true" label="%s"></i>', _('Mobile'));

                break;
            default:
                $device = $data['Template Device'];
                break;
        }

        switch ($data['Template Base']) {
            case 'Yes':
                $type = _('System');
                break;
            case 'No':
                $type = _('Custome');

                break;
            default:
                $type = $data['Template Base'];
                break;
        }


        $adata[] = array(
            'id'         => (integer)$data['Template Key'],
            'code'       => sprintf('<span class="link" onclick="change_view(\'website/%d/template/%d\')">%s</span>', $data['Template Website Key'], $data['Template Key'], $data['Template Code']),
            'device'     => $device,
            'type'       => $type,
            'scope_icon' => $scope_icon,
            'scope'      => $scope,
            'web_pages'  => number($data['Template Number Webpages']),
            'versions'   => number($data['Template Number Webpage Versions']),


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


function webpages($_data, $db, $user) {

    $rtext_label = 'webpage';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="far fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="far fa-globe very_discreet" aria-hidden="true"></i>';

        }

        switch ($data['Webpage Scope']) {
            case 'Product':
                $type = sprintf('<i class="fa fa-leaf" aria-hidden="true" title="" ></i>', _('Product'));
                break;
            case 'Info':
                $type = sprintf('<i class="fa fa-info" aria-hidden="true" title="" ></i>', _('Info'));
                break;
            case 'Category Products':
                $type = sprintf('<i class="fab fa-pagelines" aria-hidden="true" title="" ></i>', _('Products'));

                break;
            case 'Category Categories':
                $type = sprintf('<i class="fa fa-tree" aria-hidden="true" title="" ></i>', _('Categories'));

                break;
            case 'Operations':
                $type = sprintf('<i class="fa fa-keyboard" aria-hidden="true" title="" ></i>', _('Operations'));

                break;
            default:
                $type = $data['Webpage State'];
                break;
        }

        $adata[] = array(
            'id'    => (integer)$data['Webpage Key'],
            'code'  => sprintf('<span class="link" onclick="change_view(\'website/%d/page/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'state' => $state,
            'type'  => $type,


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


function webpages_in_process($_data, $db, $user) {

    $rtext_label = 'webpage in_process';
    include_once 'prepare_table/init.php';
    include_once 'conf/webpage_types.php';
    $webpage_types=get_webpage_types();

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="far fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="far fa-globe very_discreet" aria-hidden="true"></i>';

        }


        if (isset($webpage_types[$data['Webpage Type Code']])) {
            $type_label = $webpage_types[$data['Webpage Type Code']]['title'];
            $type_icon  = $webpage_types[$data['Webpage Type Code']]['icon'];
        } else {
            $type_label = '';
            $type_icon  = '';
        }


        $type = sprintf('<i class="fa fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>', $type_icon, $type_label);


        $adata[] = array(
            'id'    => (integer)$data['Webpage Key'],
            'code'  => sprintf('<span class="link" onclick="change_view(\'website/%d/in_process/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'state' => $state,
            'type'  => $type,
            'name'  => $data['Webpage Name']


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


function webpages_ready($_data, $db, $user) {

    $rtext_label = 'webpage ready';
    include_once 'prepare_table/init.php';
    include_once 'conf/webpage_types.php';
    $webpage_types=get_webpage_types();

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {


        $type_label = $webpage_types[$data['Webpage Type Code']]['title'];
        $type_icon  = $webpage_types[$data['Webpage Type Code']]['icon'];
        $type       = sprintf('<i class="fa fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>', $type_icon, $type_label);


        $adata[] = array(
            'id'   => (integer)$data['Webpage Key'],
            'code' => sprintf('<span class="link" onclick="change_view(\'website/%d/ready/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'type' => $type,
            'name' => $data['Webpage Name']


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


function webpages_online($_data, $db, $user) {

    $rtext_label = 'webpage online';


    include_once 'prepare_table/init.php';
    include_once 'conf/webpage_types.php';
    $webpage_types=get_webpage_types();


    //print_r($_data);

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
    $adata = array();

    foreach ($db->query($sql) as $data) {


        $type_label = $webpage_types[$data['Webpage Type Code']]['title'];
        $type_icon  = $webpage_types[$data['Webpage Type Code']]['icon'];
        $type       = sprintf('<i class="fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>', $type_icon, $type_label);

        if ($_data['parameters']['parent'] == 'webpage_type') {

            $code = sprintf(
                '<span class="link" onclick="change_view(\'webpages/%d/type/%d/online/%d\')">%s</span>', $data['Webpage Website Key'], $_data['parameters']['parent_key'], $data['Webpage Key'], strtolower($data['Webpage Code'])
            );

        } else {
            $code = sprintf('<span class="link" onclick="change_view(\'website/%d/online/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], strtolower($data['Webpage Code']));

        }

        $adata[] = array(
            'id'   => (integer)$data['Webpage Key'],
            'code' => $code,
            'name' => $data['Webpage Name'],

            // 'state'   => $state,
            'type' => $type,


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


function webpages_offline($_data, $db, $user) {


    $rtext_label = 'webpage offline';
    include_once 'prepare_table/init.php';
    include_once 'conf/webpage_types.php';
    $webpage_types=get_webpage_types();

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {


        $type_label = $webpage_types[$data['Webpage Type Code']]['title'];
        $type_icon  = $webpage_types[$data['Webpage Type Code']]['icon'];
        $type       = sprintf('<i class="fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>', $type_icon, $type_label);


        if ($_data['parameters']['parent'] == 'webpage_type') {

            $code = sprintf(
                '<span class="link" onclick="change_view(\'webpages/%d/type/%d/offline/%d\')">%s</span>', $data['Webpage Website Key'], $_data['parameters']['parent_key'], $data['Webpage Key'], strtolower($data['Webpage Code'])
            );

        } else {
            $code = sprintf('<span class="link" onclick="change_view(\'website/%d/offline/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], strtolower($data['Webpage Code']));

        }


        $adata[] = array(
            'id'   => (integer)$data['Webpage Key'],
            'code' => $code,
            'name' => $data['Webpage Name'],

            'type' => $type,


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


function webpage_types($_data, $db, $user) {

    // $rtext_label = 'job position';
    include_once 'prepare_table/init.php';
    include_once 'conf/webpage_types.php';
    $webpage_types=get_webpage_types();


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $label = $webpage_types[$data['Webpage Type Code']]['title'];
            $icon  = $webpage_types[$data['Webpage Type Code']]['icon'];

            $adata[] = array(
                'id'    => $data['Webpage Type Key'],
                'icon'  => '<i class="fa '.$icon.' fa-fw padding_left_5" aria-hidden="true"></i>',
                'label' => sprintf('<span class="link" onClick="change_view(\'/webpages/%d/type/%d/\')">%s</span>', $data['Webpage Type Website Key'], $data['Webpage Type Key'], $label),

                'in_process_webpages' => sprintf(
                    '<span class="link" onClick="change_view(\'/webpages/%d/type/%d\',{tab:\'webpage_type.in_process_webpages\'})">%s</span>', $data['Webpage Type Website Key'], $data['Webpage Type Key'], number($data['Webpage Type In Process Webpages'])
                ),
                'online_webpages'     => sprintf(
                    '<span class="link" onClick="change_view(\'/webpages/%d/type/%d\',{tab:\'webpage_type.online_webpages\'})">%s</span>', $data['Webpage Type Website Key'], $data['Webpage Type Key'], number($data['Webpage Type Online Webpages'])
                ),
                'offline_webpages'    => sprintf(
                    '<span class="link" onClick="change_view(\'/webpages/%d/type/%d\',{tab:\'webpage_type.offline_webpages\'})">%s</span>', $data['Webpage Type Website Key'], $data['Webpage Type Key'], number($data['Webpage Type Offline Webpages'])
                ),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
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


function webpage_assets($_data, $db, $user) {

    $rtext_label = 'webpage';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="far fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="far fa-globe very_discreet" aria-hidden="true"></i>';

        }

        switch ($data['Webpage Scope']) {
            case 'Product':
                $scope = sprintf('<i class="fal fa-cube" title="%s" ></i>', _('Product'));
                break;
            case 'Category Products':
                $scope = sprintf('<i class="fal fa-folder-open" aria-hidden="true" title="" ></i>', _('Family'));
                break;

            case 'Category Categories':
                $scope = sprintf('<i class="fal fa-folder-tree" aria-hidden="true" title="" ></i>', _('Categories'));

                break;

            default:
                $scope = '';
                break;
        }

        switch ($data['Website Webpage Scope Type']) {
            case 'Guest':
                $type = _('Guest');
                break;
            case 'Category_Products_Item':
                $type = _("Family's product").' <i class="fal padding_left_5 fa-cart-plus"></i>';
                break;
            case 'Products_Item':
                $type = _('Related product').' <i class="fal padding_left_5 fa-cart-plus"></i>';
                break;
            case 'See_Also_Category_Auto':
            case 'See_Also_Product_Auto':
                $type = _('See also').' <i class="fal padding_left_5 fa-robot"></i>';
                break;
            case 'See_Also_Category_Manual':
            case 'See_Also_Product_Manual':
                $type = _('See also').' <i class="fal padding_left_5 fa-brain"></i>';
                break;
            case 'Product_Main_Webpage':
                $type = _('Product webpage').' <i class="padding_left_5 fal fa-browser"></i>';
                break;

            default:
                $type = $data['Website Webpage Scope Type'];
                break;
        }

        $adata[] = array(
            'id'   => (integer)$data['Webpage Key'],
            'code' => sprintf('<span class="link url" onclick="change_view(\'website/%d/webpage/%d/asset/%d\')">%s</span>', $data['Webpage Website Key'], $parameters['parent_key'], $data['Webpage Key'], strtolower($data['Webpage Code'])),
            'name' => $data['Webpage Name'],

            'state' => $state,
            'type'  => $type,
            'scope' => $scope,


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


function webpage_containers($_data, $db, $user) {

    $rtext_label = 'webpage';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="far fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="far fa-globe very_discreet" aria-hidden="true"></i>';

        }

        switch ($data['Webpage Scope']) {
            case 'Product':
                $scope = sprintf('<i class="fal fa-cube" title="%s" ></i>', _('Product'));
                break;
            case 'Category Products':
                $scope = sprintf('<i class="fal fa-folder-open" aria-hidden="true" title="" ></i>', _('Family'));
                break;

            case 'Category Categories':
                $scope = sprintf('<i class="fal fa-folder-tree" aria-hidden="true" title="" ></i>', _('Categories'));

                break;

            default:
                $scope = '';
                break;
        }

        switch ($data['Website Webpage Scope Type']) {
            case 'Guest':
                $type = _('Guest');
                break;
            case 'Category_Products_Item':
                $type = _("Family's product").' <i class="fal padding_left_5 fa-cart-plus"></i>';
                break;
            case 'Products_Item':
                $type = _('Related product').' <i class="fal padding_left_5 fa-cart-plus"></i>';
                break;
            case 'See_Also_Category_Auto':
            case 'See_Also_Product_Auto':
                $type = _('See also').' <i class="fal padding_left_5 fa-robot"></i>';
                break;
            case 'See_Also_Category_Manual':
            case 'See_Also_Product_Manual':
                $type = _('See also').' <i class="fal padding_left_5 fa-brain"></i>';
                break;
            case 'Product_Main_Webpage':
                $type = _('Product webpage').' <i class="padding_left_5 fal fa-browser"></i>';
                break;

            default:
                $type = $data['Website Webpage Scope Type'];
                break;
        }

        $adata[] = array(
            'id'   => (integer)$data['Webpage Key'],
            'code' => sprintf('<span class="link url" onclick="change_view(\'website/%d/webpage/%d/asset/%d\')">%s</span>', $data['Webpage Website Key'], $parameters['parent_key'], $data['Webpage Key'], strtolower($data['Webpage Code'])),
            'name' => $data['Webpage Name'],

            'state' => $state,
            'type'  => $type,
            'scope' => $scope,


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

