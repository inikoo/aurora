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


if (!$user->can_view('sites')) {
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
        websites(get_table_parameters(), $db, $user);
        break;
    case 'nodes':
        nodes(get_table_parameters(), $db, $user);
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
    case 'online_webpages':
        webpages_online(get_table_parameters(), $db, $user);
        break;
    case 'offline_webpages':
        webpages_offline(get_table_parameters(), $db, $user);
        break;
    case 'root_nodes':
        root_nodes(get_table_parameters(), $db, $user);
        break;
    case 'pages':
        pages(get_table_parameters(), $db, $user);
        break;
    case 'pageviews':
        pageviews(get_table_parameters(), $db, $user);
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

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;
    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'site_key'     => $data['User Site Key'],
            'id'           => $data['User Key'],
            'customer_key' => $data['User Parent Key'],
            'user'         => $data['User Handle'],
            'customer'     => $data['User Alias'],
            'sessions'     => number($data['User Sessions Count']),
            'last_login'   => ($data['User Last Login'] ? strftime(
                "%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00')
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
            'site_key' => $data['Website Key'],
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
            'site_key' => $data['Website Key'],
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


function websites($_data, $db, $user) {

    $rtext_label = 'website';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {


        $adata[] = array(
            'id'                            => (integer)$data['Website Key'],
            'code'                          => $data['Website Code'],
            'name'                          => $data['Website Name'],
            'url'                           => $data['Website URL'],
            'users'                         => number(
                $data['Website Total Acc Users']
            ),
            'visitors'                      => number(
                $data['Website Total Acc Visitors']
            ),
            'requests'                      => number(
                $data['Website Total Acc Requests']
            ),
            'sessions'                      => number(
                $data['Website Total Acc Sessions']
            ),
            'pages'                         => number(
                $data['Website Number WebPages']
            ),
            'pages_products'                => number(
                $data['Website Number WebPages with Products']
            ),
            'pages_out_of_stock'            => number(
                $data['Website Number WebPages with Out of Stock Products']
            ),
            'pages_out_of_stock_percentage' => percentage(
                $data['Website Number WebPages with Out of Stock Products'], $data['Website Number WebPages with Products']
            ),
            'products'                      => number(
                $data['Website Number Products']
            ),
            'out_of_stock'                  => number(
                $data['Website Number Out of Stock Products']
            ),
            'out_of_stock_percentage'       => percentage(
                $data['Website Number Out of Stock Products'], $data['Website Number Products']
            ),
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


function pages($_data, $db, $user) {

    $rtext_label = 'page';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    $interval_db = get_interval_db_name($parameters['f_period']);
    foreach ($db->query($sql) as $data) {
        /*
                $period_visitors=number($data["Page Store $interval_db Acc Visitors"]);
                $period_sessions=number($data["Page Store $interval_db Acc Sessions"]);
                $period_requests=number($data["Page Store $interval_db Acc Requests"]);
                $period_users=number($data["Page Store $interval_db Acc Users"]);

                $users=number($data["Page Store Total Acc Users"]);
                $requests=number($data["Page Store Total Acc Requests"]);






                switch ($data['Page State']) {
                case 'Online':
                    //$state='<img src="/art/icons/world.png" alt='._('Online').'/>';
                    $state=_('Online');
                    break;
                case 'Offline':
                    //$state='<img src="/art/icons/world_bw.png" alt='._('Offline').'/>';
                    $state=_('Offline');
                    break;
                default:
                    $state='';
                }


                $products=number($data['Page Store Number Products']);
                $products_out_of_stock=number($data['Page Store Number Out of Stock Products']);
                $products_sold_out=number($data['Page Store Number Sold Out Products']);
                $percentage_products_out_of_stock=percentage($data['Page Store Number Out of Stock Products'], $data['Page Store Number Products']);
                $list_products=number($data['Page Store Number List Products']);
                $button_products=number($data['Page Store Number Button Products']);

                if ($data['Page State']=='Offline') {
                    $products='<span style="color:#777;font-style:italic">'.$products.'</span>';
                    $products_out_of_stock='<span style="color:#777;font-style:italic">'.$products_out_of_stock.'</span>';
                    $products_sold_out='<span style="color:#777;font-style:italic">'.$products_sold_out.'</span>';
                    $percentage_products_out_of_stock='<span style="color:#777;font-style:italic">'.$percentage_products_out_of_stock.'</span>';
                    $list_products='<span style="color:#777;font-style:italic">'.$list_products.'</span>';
                    $button_products='<span style="color:#777;font-style:italic">'.$button_products.'</span>';

                }
        */
        $adata[] = array(
            'id'      => (integer)$data['Webpage Key'],
            'code'    => $data['Webpage Code'],
            'name'    => $data['Webpage Name'],
            'display' => percentage($data['Webpage Display Probability'], 1),
            //'type'=>$type,
            //'url'=>($data['Website SSL']=='Yes'?'https://':'http://').$data['Page URL'],
            //'title'=>$data['Page Store Title'],
            //'state'=>$state,
            //'users'=>$users,
            //'requests'=>$requests,
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


function pageviews($_data, $db, $user) {

    $rtext_label = 'pageview';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    $interval_db = get_interval_db_name($parameters['f_period']);
    foreach ($db->query($sql) as $data) {

        switch ($data['Page Store Section']) {
            case 'Department Catalogue':
                $type = sprintf(
                    "d(<span class=\"link\" onClick=\"change_view('department/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']
                );
                break;
            case 'Family Catalogue':
                $type = sprintf(
                    "f(<span class=\"link\" onClick=\"change_view('family/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']
                );
                break;
            case 'Product Description':
                $type = sprintf(
                    "p(<span class=\"link\" onClick=\"change_view('product/%d')\"  >%s</span>)", $data['Page Parent Key'], $data['Page Parent Code']
                );
                break;

            case 'Welcome':
                $type = _('Welcome');
                break;
            case 'Login':
                $type = _('Login');
                break;
            case 'Information':
                $type = _('Information');
                break;
            case 'Checkout':
                $type = _('Checkout');
                break;
            case 'Reset':
                $type = _('Reset');
                break;
            case 'Registration':
                $type = _('Registration');
                break;
            case 'Not Found':
                $type = _('Not Found');
                break;
            case 'Client Section':
                $type = _('Client Section');
                break;
            case 'Client Section':
                $type = _('Client Section');
                break;
            case 'Front Page Store':
                $type = _('Home');
                break;
            case 'Basket':
                $type = _('Basket');
                break;
            case 'Thanks':
                $type = _('Thanks');
                break;
            case 'Payment Limbo':
                $type = _('Payment Limbo');
                break;
            case 'Search':
                $type = _('Search');
                break;
            default:
                $type = _('Other').' '.$data['Page Store Section'];
                break;
        }

        $adata[] = array(
            'id'    => (integer)$data['User Request Key'],
            'page'  => $data['Page Code'],
            'title' => $data['Page Store Title'],
            'type'  => $type,

            'page_key' => $data['Page Key'],
            'site_key' => $data['Page Site Key'],
            'date'     => strftime(
                "%a %e %b %Y %H:%M:%S %Z", strtotime($data['Date'])
            ),

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


function root_nodes($_data, $db, $user) {

    $rtext_label = 'section';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="fa fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="fa fa-globe very_discreet" aria-hidden="true"></i>';

        }

        $adata[] = array(
            'id'    => (integer)$data['Website Node Key'],
            'code'  => $data['Webpage Code'],
            'name'  => $data['Webpage Name'],
            'state' => $state


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

function nodes($_data, $db, $user) {

    $rtext_label = 'section';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {

        $adata[] = array(
            'id'   => (integer)$data['Website Node Key'],
            'code' => $data['Webpage Code'],
            'name' => $data['Webpage Name'],


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
                '<span class="link" onclick="change_view(\'page/%d/version/%d\')">%s</span>', $data['Webpage Key'], $data['Webpage Version Key'],
                $data['Webpage Code'].'.'.$device_code.'.'.$data['Webpage Version Code']
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
                $scope_icon = sprintf('<i class="fa fa-file-o" aria-hidden="true" label="%s"></i>', _('Blank'));
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
            $state = '<i class="fa fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="fa fa-globe very_discreet" aria-hidden="true"></i>';

        }

        switch ($data['Webpage Scope']) {
            case 'Product':
                $type = sprintf('<i class="fa fa-leaf" aria-hidden="true" title="" ></i>', _('Product'));
                break;
            case 'Info':
                $type = sprintf('<i class="fa fa-info" aria-hidden="true" title="" ></i>', _('Info'));
                break;
            case 'Category Products':
                $type = sprintf('<i class="fa fa-pagelines" aria-hidden="true" title="" ></i>', _('Products'));

                break;
            case 'Category Categories':
                $type = sprintf('<i class="fa fa-tree" aria-hidden="true" title="" ></i>', _('Categories'));

                break;
            case 'Operations':
                $type = sprintf('<i class="fa fa-keyboard-o" aria-hidden="true" title="" ></i>', _('Operations'));

                break;
            default:
                $type = $data['Webpage State'];
                break;
        }

        $adata[] = array(
            'id'      => (integer)$data['Webpage Key'],
            'code'    => sprintf('<span class="link" onclick="change_view(\'website/%d/page/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'state'   => $state,
            'type'    => $type,
            'version' => number_format($data['Webpage Version'], 1),


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

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="fa fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="fa fa-globe very_discreet" aria-hidden="true"></i>';

        }

        $type_label=$webpage_types[$data['Webpage Type Code']]['title'];
        $type_icon=$webpage_types[$data['Webpage Type Code']]['icon'];
        $type=sprintf('<i class="fa fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>',$type_icon, $type_label);


        $adata[] = array(
            'id'      => (integer)$data['Webpage Key'],
            'code'    => sprintf('<span class="link" onclick="change_view(\'website/%d/in_process/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'state'   => $state,
            'type'    => $type,
            'name'    => $data['Webpage Name'],
            'template'    => $data['Webpage Template Filename'],


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

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

//print $sql;
    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="fa fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="fa fa-globe very_discreet" aria-hidden="true"></i>';

        }




        $type_label=$webpage_types[$data['Webpage Type Code']]['title'];
        $type_icon=$webpage_types[$data['Webpage Type Code']]['icon'];
        $type=sprintf('<i class="fa fa-fw %s padding_left_10" aria-hidden="true" title="%s" ></i>',$type_icon, $type_label);

        $adata[] = array(
            'id'      => (integer)$data['Webpage Key'],
            'code'    => sprintf('<span class="link" onclick="change_view(\'website/%d/online/webpage/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'name'      => $data['Webpage Name'],

            'state'   => $state,
            'type'    => $type,
            'version' => number_format($data['Webpage Version'], 1),


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

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();

    foreach ($db->query($sql) as $data) {

        if ($data['Webpage State'] == 'Online') {
            $state = '<i class="fa fa-globe" aria-hidden="true"></i>';
        } else {
            $state = '<i class="fa fa-globe very_discreet" aria-hidden="true"></i>';

        }

        switch ($data['Webpage Scope']) {
            case 'Product':
                $type = sprintf('<i class="fa fa-leaf" aria-hidden="true" title="" ></i>', _('Product'));
                break;
            case 'Info':
                $type = sprintf('<i class="fa fa-info" aria-hidden="true" title="" ></i>', _('Info'));
                break;
            case 'Category Products':
                $type = sprintf('<i class="fa fa-pagelines" aria-hidden="true" title="" ></i>', _('Products'));

                break;
            case 'Category Categories':
                $type = sprintf('<i class="fa fa-tree" aria-hidden="true" title="" ></i>', _('Categories'));

                break;
            case 'Operations':
                $type = sprintf('<i class="fa fa-keyboard-o" aria-hidden="true" title="" ></i>', _('Operations'));

                break;
            default:
                $type = $data['Webpage State'];
                break;
        }

        $adata[] = array(
            'id'      => (integer)$data['Webpage Key'],
            'code'    => sprintf('<span class="link" onclick="change_view(\'website/%d/page/%d\')">%s</span>', $data['Webpage Website Key'], $data['Webpage Key'], $data['Webpage Code']),
            'state'   => $state,
            'type'    => $type,
            'version' => number_format($data['Webpage Version'], 1),


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


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {



            $label=$webpage_types[$data['Webpage Type Code']]['title'];
            $icon=$webpage_types[$data['Webpage Type Code']]['icon'];

            $adata[] = array(
                'id'              => $data['Webpage Type Key'],
                'icon'           => '<i class="fa '.$icon.' fa-fw padding_left_5" aria-hidden="true"></i>',
                'label'           => sprintf('<span class="button" onClick="change_view(\'/webpages/%d/type/%d\')">%s</span>', $data['Webpage Type Website Key'], $data['Webpage Type Key'], $label),
                'in_process_webpages' => number($data['Webpage Type In Process Webpages']),
                'online_webpages' => number($data['Webpage Type Online Webpages']),
                'offline_webpages' => number($data['Webpage Type Offline Webpages'])
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


?>
