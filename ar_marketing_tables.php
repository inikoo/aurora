<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:24:43 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!$user->can_view('stores')) {
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

switch ($tipo){

    case 'offers_group_by_store':
        offers_group_by_store(get_table_parameters(), $db, $user);
        break;
    case 'campaigns':
        campaigns(get_table_parameters(), $db, $user);
        break;
    case 'vouchers':
        deals(get_table_parameters(), $db, $user);
        break;
    case 'customer_deals':

    case 'deals':
        deals(get_table_parameters(), $db, $user);
        break;
    case 'deal_components':
        components(get_table_parameters(), $db, $user);
        break;
    case 'category_deal_components':
        category_components(get_table_parameters(), $db, $user);
        break;
    case 'reminders':
        reminders(get_table_parameters(), $db, $user);
        break;
    case 'campaign_bulk_deals':
        campaign_bulk_deals(get_table_parameters(), $db, $user);
        break;
    case 'components':
        components(get_table_parameters(), $db, $user);
        break;
    case 'campaign_order_recursion_components':
        campaign_order_recursion_components(get_table_parameters(), $db, $user);
        break;
    case 'newsletters':
        newsletters(get_table_parameters(), $db, $user);
        break;
    case 'email_template_types':
        email_template_types(get_table_parameters(), $db, $user);
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


function offers_group_by_store($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $total_campaigns=0;
    $total_deals=0;
    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            $total_campaigns+=$data['campaigns'];
            $total_deals+=$data['deals'];
            $adata[] = array(
                'id' => (integer)$data['Store Key'],

                'code'      => sprintf('<span class="link" onClick="change_view(\'offers/%d/categories\')">%s</span>', $data['Store Key'], $data['Store Code']),
                'name'      => sprintf('<span class="link" onClick="change_view(\'offers/%d/categories\')">%s</span>', $data['Store Key'], $data['Store Name']),
                'campaigns' => sprintf('<span class="" >%s</span>', $data['campaigns']),
                'deals'     => sprintf('<span class="link" onClick="change_view(\'offers/%d\')" >%s</span>',  $data['Store Key'] ,$data['deals']),
            );


        }
    }


    $adata[] = array(
        'store_key' => '',
        'name'      => '',
        'code'      => _('Total').($filtered > 0 ? ' '.'<i class="fa fa-filter fa-fw"></i>' : ''),


        'campaigns'      => number($total_campaigns),
        'deals' => number($total_deals),

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


function deals($_data, $db, $user) {

    $rtext_label = 'offer';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($_data['parameters']['parent'] == 'campaign') {
        $parent = get_object('DEalCampaign', $_data['parameters']['parent_key']);
    }


    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Status'];
            }

            /*
            $duration = '';
            if ($data['Deal Expiration Date'] == '' and $data['Deal Begin Date'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Begin Date'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Begin Date']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Expiration Date'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Expiration Date']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }
*/
            if ($data['Deal Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }


            if (strlen(strip_tags($data['Deal Term Allowances Label'])) > 75) {
                $description_class = 'super_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 60) {
                $description_class = 'very_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 50) {
                $description_class = 'small';
            } else {
                $description_class = '';
            }


            if ($_data['parameters']['parent'] == 'category') {
                $name = sprintf('<span class="link" onClick="change_view(\'products/%d/category/%d/deal/%d\')">%s</span>', $data['Deal Store Key'], $_data['parameters']['parent_key'], $data['Deal Key'], $data['Deal Name']);

            } elseif ($_data['parameters']['parent'] == 'store') {
                $name = sprintf('<span class="link" onClick="change_view(\'deals/%d/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Key'], $data['Deal Name']);

            } elseif ($_data['parameters']['parent'] == 'campaign') {
                $name = sprintf('<span class="link" onClick="change_view(\'offers/%d/%s/%d\')">%s</span>', $data['Deal Store Key'], strtolower($parent->get('Code')), $data['Deal Key'], $data['Deal Name']);

            } else {

                $name = $data['Deal Name'];
            }


            $description = sprintf('<span class="%s"  >%s</span>', $description_class, $data['Deal Term Allowances Label']);


            $adata[] = array(
                'id'                  => (integer)$data['Deal Key'],
                'current_deal_status' => $status,
                'name'                => $name,
                'description'         => $description,
                'from'                => $from,
                'to'                  => $to,
                'status'              => $status,
                'orders'              => number($data['Deal Total Acc Used Orders']),
                'customers'           => number($data['Deal Total Acc Used Customers'])

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

function campaigns($_data, $db, $user) {

    $rtext_label = 'categoey';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $adata[] = array(
                'id'   => (integer)$data['Deal Campaign Key'],
                'code' => sprintf('<span class="link" onclick="change_view(\'offers/%d/%s\')">%s</span>', $data['Deal Campaign Store Key'], strtolower($data['Deal Campaign Code']), $data['Deal Campaign Icon']),
                'name' => sprintf('<span class="link" onclick="change_view(\'offers/%d/%s\')">%s</span>', $data['Deal Campaign Store Key'], strtolower($data['Deal Campaign Code']), $data['Deal Campaign Name']),

                'active_deal_components'    => number($data['Deal Campaign Number Active Deal Components']),
                'suspended_deal_components' => number($data['Deal Campaign Number Suspended Deal Components']),
                'waiting_deal_components'   => number($data['Deal Campaign Number Waiting Deal Components']),
                'finish_deal_components'    => number($data['Deal Campaign Number Finish Deal Components']),

                'orders'    => number($data['Deal Campaign Total Acc Used Orders']),
                'customers' => number($data['Deal Campaign Total Acc Used Customers'])

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

function components($_data, $db, $user) {

    $rtext_label = 'allowance';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            /*
                        switch ($data['Deal Component Allowance Type']) {
                            case 'Percentage Off':
                                $allowance = percentage($data['Deal Component Allowance'], 1);
                                break;

                            default:
                                $allowance = $data['Deal Component Allowance Type'].' '.$data['Deal Component Allowance'];
                        }
            */

            //          $allowance=$data['Deal Component Allowance'];


            switch ($data['Deal Component Allowance Target']) {
                case 'Category':
                    $target = sprintf(
                        '<i class="fa fa-sitemap fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/products/%d/category/%d\')" >%s</span>', _('Category'), $data['Deal Component Store Key'],
                        $data['Deal Component Allowance Target Key'], $data['Deal Component Allowance Target Label']
                    );
                    break;
                case 'Charge':
                    $target = sprintf(
                        '<i class="fa fa-money fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/store/%d/charge/%d\')" >%s</span>', _('Charges'), $data['Deal Component Store Key'], $data['Deal Component Allowance Target Key'],
                        $data['Deal Component Allowance Target Label']
                    );
                    break;
                default:
                    $target = $data['Deal Component Allowance Target'];
            }


            switch ($data['Deal Component Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Component Status'];
            }

            $duration = '';
            if ($data['Deal Component Expiration Date'] == '' and $data['Deal Component Begin Date'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Component Begin Date'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Component Begin Date']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Component Expiration Date'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Component Expiration Date']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Component Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Component Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Component Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Component Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }

            $adata[] = array(
                'id'          => (integer)$data['Deal Component Key'],
                'status'      => $status,
                'description' => $data['Deal Component Term Allowances Label'],
                'from'        => $from,
                'to'          => $to,
                'target'      => $target,
                'duration'    => $duration,
                // 'allowance'   => $allowance,

                'orders'    => number($data['Deal Component Total Acc Used Orders']),
                'customers' => number($data['Deal Component Total Acc Used Customers'])

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

function campaign_order_recursion_components($_data, $db, $user) {

    $rtext_label = 'allowance';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Component Allowance Type']) {
                case 'Percentage Off':
                    $edit = '<span id="deal_component_allowance_'.$data['Deal Component Key'].'"><span class="button" key="'.$data['Deal Component Key'].'"  data-target="'.$data['Deal Component Allowance Target Label'].'" data-allowance="'.percentage(
                            $data['Deal Component Allowance'], 1
                        ).'" data-description="'.$data['Deal Component Allowance Label'].'"  onclick="edit_component_allowance(this)"   ><i class="  far fa-pencil"></i></span></span>';
                    break;

                default:
                    $edit = '';
            }

            switch ($data['Deal Component Allowance Target']) {
                case 'Category':
                    $target = sprintf(
                        '<i class="fa fa-sitemap fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/products/%d/category/%d\')" >%s</span>', _('Category'), $data['Deal Component Store Key'],
                        $data['Deal Component Allowance Target Key'], $data['Deal Component Allowance Target Label']
                    );
                    break;
                case 'Charge':
                    $target = sprintf(
                        '<i class="fa fa-money fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/store/%d/charge/%d\')" >%s</span>', _('Charges'), $data['Deal Component Store Key'], $data['Deal Component Allowance Target Key'],
                        $data['Deal Component Allowance Target Label']
                    );
                    break;
                default:
                    $target = $data['Deal Component Allowance Target'];
            }


            switch ($data['Deal Component Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Component Status'];
            }

            $status = '<span id="deal_component_status_'.$data['Deal Component Key'].'"><span status="'.$data['Deal Component Status'].'" target="'.$data['Deal Component Allowance Target Label'].'" key="'.$data['Deal Component Key']
                .'" class="button" onclick="edit_component_status(this)">'.$status.'</span></span>';


            if ($data['Deal Component Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Component Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }


            $allowance = $data['Deal Component Term Allowances Label'];
            if($allowance==''){
                $allowance=_('Unknown');
            }

            $allowance=sprintf('<span class="link" onclick="change_view(\'offers/%d/or/%d\')" >%s</span>',$data['Deal Component Store Key'],$data['Deal Component Key'],$allowance);

            $adata[] = array(
                'id'     => (integer)$data['Deal Component Key'],
                'status' => $status,
                'term_allowances' => $allowance,
                'from'            => $from,
                'target'          => $target,
                'orders'          => number($data['Deal Component Total Acc Used Orders']),
                'customers'       => number($data['Deal Component Total Acc Used Customers']),
                'edit'            => $edit

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


function campaign_bulk_deals($_data, $db, $user) {

    $rtext_label = 'offer';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Status'];
            }

            $duration = '';
            if ($data['Deal Expiration Date'] == '' and $data['Deal Begin Date'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Begin Date'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Begin Date']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Expiration Date'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Expiration Date']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }

            if (strlen(strip_tags($data['Deal Term Allowances Label'])) > 75) {
                $description_class = 'super_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 60) {
                $description_class = 'very_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 50) {
                $description_class = 'small';
            } else {
                $description_class = '';
            }


            $description = sprintf(
                '<span id="deal_component_description_%d"><span  class="%s button"  key="%d" target="%s" terms="%d"  allowance="%s" description_terms="%s" description_allowances="%s"  onclick="edit_volume_deal(this)"  title="%s">%s</span></span>',
                $data['Deal Component Key'], $description_class, $data['Deal Component Key'], $data['Deal Component Allowance Target Label'], $data['Deal Component Terms'], percentage($data['Deal Component Allowance'], 1), $data['Deal Term Label'],
                $data['Deal Component Allowance Label'], strip_tags($data['Deal Term Label'].' '.$data['Deal Component Allowance Label']), $data['Deal Term Allowances Label']
            );

            $name = sprintf('<span class="link" onClick="change_view(\'offers/%d/%s/%d\')">%s</span>', $data['Deal Store Key'], 'vl', $data['Deal Key'], $data['Deal Name']);


            $adata[] = array(
                'id'          => (integer)$data['Deal Key'],
                'status'      => $status,
                'name'        => $name,
                'target'      => sprintf('<span class="link" onClick="change_view(\'products/%d/category/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Component Allowance Target Key'], $data['Deal Component Allowance Target Label']),
                'description' => $description,

                'from'      => $from,
                'to'        => $to,
                'orders'    => number($data['Deal Total Acc Used Orders']),
                'customers' => number($data['Deal Total Acc Used Customers'])

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


function newsletters($_data, $db, $user) {

    $rtext_label = 'newsletter';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {


        $name = sprintf('<span class="link" onClick="change_view(\'newsletters/%d/%d\')">%s</span>', $data['Email Campaign Store Key'], $data['Email Campaign Key'], $data['Email Campaign Name']);


        switch ($data['Email Campaign State']) {
            case 'InProcess':
                $state = _('Setting up mailing list');
                break;
            case 'ComposingEmail':
                $state = _('Composing email');
                break;
            case 'Ready':
                $state = _('Ready to send');
                break;
            case 'Scheduled':
                $state = _('Scheduled to be send');
                break;
            case 'Sending':
                $state = _('Sending');

                break;
            case 'Cancelled':
                $state = _('Cancelled');
                break;
            case 'Send':
                $state = _('Send');
                break;


            default:
                $state = $data['Email Campaign State'];
                break;
        }


        $adata[] = array(
            'id'   => (integer)$data['Email Campaign Key'],
            'date' => strftime("%a %e %b %Y", strtotime($data['Email Campaign Last Updated Date'].' +0:00')),


            'name'   => $name,
            'state'  => $state,
            'emails' => number($data['Email Campaign Number Estimated Emails']),


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


function reminders($_data, $db, $user) {

    $rtext_label = 'scope';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {

        //'Newsletter','Marketing','GR Reminder','AbandonedCart','OOS Notification','Registration','Password Reminder','Order Confirmation','Delivery Confirmation'
        switch ($data['Email Campaign Type Code']) {
            case 'Newsletter':
                $name = _('Newsletter');
                break;
            case 'Marketing':
                $name = _('Mailshot');
                break;
            case 'AbandonedCart':
                $name = _('Orders in basket');
                break;
            case 'OOS Notification':
                $name = _('Out of stock notification');
                break;
            case 'Registration':
                $name = _('Welcome');
                break;
            case 'Password Reminder':
                $name = _('Password reset');
                break;
            case 'Order Confirmation':
                $name = _('order confirmation');
                break;
            case 'GR Reminder':
                $name = _('Reorder reminder');
                break;
            default:
                $name = $data['Email Campaign Type Code'];


        }


        $name = sprintf('<span class="link" onClick="change_view(\'customers/%d/email_campaign_type/%d/\')">%s</span>', $data['Email Campaign Type Store Key'], $data['Email Campaign Type Key'], $name);


        $adata[] = array(
            'id' => (integer)$data['Email Campaign Type Key'],


            'name'      => $name,
            'mailshots' => number($data['Email Campaign Type Mailshots']),


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


function email_template_types($_data, $db, $user) {

    $rtext_label = 'operational email';


    include_once 'prepare_table/init.php';


    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;

    $adata = array();


    foreach ($db->query($sql) as $data) {

        switch ($data['Email Campaign Type Status']) {
            case 'InProcess':
                $status = sprintf('<i class="far discreet fa-seedling" title="%s" ></i>', _('Composing email template'));
                break;
            case 'Active':
                $status = sprintf('<i class="far success fa-broadcast-tower" title="%s"></i>', _('Live'));
                break;
            case 'Suspended':
                $status = sprintf('<i class="fa error fa-stop" title="%s"></i>', _('Suspended'));
                break;

            default:
                $status = '';


        }

        $mailshots = '';
        switch ($data['Email Campaign Type Code']) {
            case 'Newsletter':
                $_type     = _('Newsletters');
                $status    = '';
                $mailshots = number($data['Email Campaign Type Mailshots']);
                break;
            case 'Marketing':
                $_type     = _('Marketing mailshots');
                $status    = '';
                $mailshots = number($data['Email Campaign Type Mailshots']);

                break;
            case 'AbandonedCart':
                $_type     = _('Orders in basket');
                $status    = '';
                $mailshots = number($data['Email Campaign Type Mailshots']);

                break;
            case 'OOS Notification':
                $_type     = _('Back in stock emails');
                $mailshots = number($data['Email Campaign Type Mailshots']);

                break;
            case 'Registration':
                $_type = _('Welcome emails');
                break;
            case 'Password Reminder':
                $_type = _('Password reset emails');
                break;
            case 'Order Confirmation':
                $_type = _('Order confirmations');
                break;
            case 'GR Reminder':
                $_type     = _('Reorder reminders');
                $mailshots = number($data['Email Campaign Type Mailshots']);

                break;
            case 'Invite Mailshot':
                $_type  = _('Invitation');
                $status = '';
                break;
            case 'Invite Full Mailshot':
                $_type  = _('Invitation mailshot');
                $status = '';
                break;
            case 'Invite':
                $_type  = _('Invitation (Personalized)');
                $status = '';
                break;
            default:
                $_type = $data['Email Campaign Type Code'];


        }


        $type = sprintf('<span class="link" onClick="change_view(\'email_campaign_type/%d/%d\')">%s</span>', $data['Email Campaign Type Store Key'], $data['Email Campaign Type Key'], $_type);


        $adata[] = array(
            'id'     => (integer)$data['Email Campaign Type Key'],
            'status' => $status,

            '_type'     => $_type,
            'type'      => $type,
            'mailshots' => $mailshots,

            'sent' => number($data['Email Campaign Type Sent']),

            'hard_bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Hard Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Hard Bounces']),
                percentage($data['Email Campaign Type Hard Bounces'], $data['Email Campaign Type Sent'])
            ),
            'soft_bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Soft Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Soft Bounces']),
                percentage($data['Email Campaign Type Soft Bounces'], $data['Email Campaign Type Sent'])
            ),

            'bounces' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Bounces'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Bounces']),
                percentage($data['Email Campaign Type Bounces'], $data['Email Campaign Type Sent'])
            ),

            'delivered' => ($data['Email Campaign Type Sent'] == 0 ? '<span class="super_discreet">'._('NA').'</span>' : number($data['Email Campaign Type Delivered'])),

            'open'    => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Type Open']), percentage($data['Email Campaign Type Open'], $data['Email Campaign Type Delivered'])
            ),
            'clicked' => sprintf(
                '<span class="%s" title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ''), number($data['Email Campaign Type Clicked']), percentage($data['Email Campaign Type Clicked'], $data['Email Campaign Type Delivered'])
            ),
            'spam'    => sprintf(
                '<span class="%s " title="%s">%s</span>', ($data['Email Campaign Type Delivered'] == 0 ? 'super_discreet' : ($data['Email Campaign Type Spams'] == 0 ? 'success super_discreet' : '')), number($data['Email Campaign Type Spams']),
                percentage($data['Email Campaign Type Spams'], $data['Email Campaign Type Delivered'])
            ),


        );

    }

    if ($_order == 'type') {


        $type = array();
        foreach ($adata as $key => $row) {
            $type[$key] = $row['_type'];
        }


        if ($_dir == 'desc') {
            array_multisort($type, SORT_DESC, $adata);

        } else {
            array_multisort($type, SORT_ASC, $adata);

        }


    }

    // print_r($_order);


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



function vouchers($_data, $db, $user) {

    $rtext_label = 'voucher';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Status'];
            }


            if ($data['Deal Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }


            if (strlen(strip_tags($data['Deal Term Allowances Label'])) > 75) {
                $description_class = 'super_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 60) {
                $description_class = 'very_small';
            } elseif (strlen(strip_tags($data['Deal Term Allowances Label'])) > 50) {
                $description_class = 'small';
            } else {
                $description_class = '';
            }


            if ($_data['parameters']['parent'] == 'category') {
                $name = sprintf('<span class="link" onClick="change_view(\'products/%d/category/%d/deal/%d\')">%s</span>', $data['Deal Store Key'], $_data['parameters']['parent_key'], $data['Deal Key'], $data['Deal Name']);

            } elseif ($_data['parameters']['parent'] == 'store') {
                $name = sprintf('<span class="link" onClick="change_view(\'deals/%d/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Key'], $data['Deal Name']);

            } else {
                $name = sprintf('<span class="link" onClick="change_view(\'offers/%d/%s/%d\')">%s</span>', $data['Deal Store Key'], 'vo', $data['Deal Key'], $data['Deal Name']);

            }


            $description = sprintf('<span class="%s"  >%s</span>', $description_class, $data['Deal Term Allowances Label']);


            $adata[] = array(
                'id'                  => (integer)$data['Deal Key'],
                'current_deal_status' => $status,
                'name'                => $name,
                'description'         => $description,
                'from'                => $from,
                'to'                  => $to,
                'orders'              => number($data['Deal Total Acc Used Orders']),
                'customers'           => number($data['Deal Total Acc Used Customers'])

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


function category_components($_data, $db, $user) {

    $rtext_label = 'offer';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Component Allowance Type']) {
                case 'Percentage Off':
                    $allowance = percentage($data['Deal Component Allowance'], 1);
                    break;

                default:
                    $allowance = $data['Deal Component Allowance Type'].' '.$data['Deal Component Allowance'];
            }

            switch ($data['Deal Component Allowance Target']) {
                case 'Category':
                    $target = sprintf(
                        '<i class="fa fa-sitemap fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/products/%d/category/%d\')" >%s</span>', _('Category'), $data['Deal Component Store Key'],
                        $data['Deal Component Allowance Target Key'], $data['Deal Component Allowance Target Label']
                    );
                    break;
                case 'Charge':
                    $target = sprintf(
                        '<i class="fa fa-money fa-fw" aria-hidden="true" title="%s" ></i> <span class="link" onclick="change_view(\'/store/%d/charge/%d\')" >%s</span>', _('Charges'), $data['Deal Component Store Key'], $data['Deal Component Allowance Target Key'],
                        $data['Deal Component Allowance Target Label']
                    );
                    break;
                default:
                    $target = $data['Deal Component Allowance Target'];
            }


            switch ($data['Deal Component Status']) {
                case 'Waiting':
                    $status = sprintf(
                        '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                    );
                    break;
                case 'Active':
                    $status = sprintf(
                        '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                    );
                    break;
                case 'Suspended':
                    $status = sprintf(
                        '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                    );
                    break;
                case 'Finish':
                    $status = sprintf(
                        '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                    );
                    break;
                default:
                    $status = $data['Deal Component Status'];
            }

            $duration = '';
            if ($data['Deal Component Expiration Date'] == '' and $data['Deal Component Begin Date'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Component Begin Date'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Component Begin Date']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Component Expiration Date'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Component Expiration Date']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Component Expiration Date'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Component Expiration Date']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Component Begin Date'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Component Begin Date']." +00:00")
                );
            } else {
                $from = '';
            }

            $adata[] = array(
                'id'     => (integer)$data['Deal Component Key'],
                'status' => $status,
                'name'   => sprintf(
                    '<span class="link" onclick="change_view(\'products/%d/category/%d/deal_component/%d\')">%s</span>', $data['Deal Component Store Key'], $data['Deal Component Allowance Target Key'], $data['Deal Component Key'], $data['Deal Name Label']
                ),


                'description' => $data['Deal Component Term Allowances Label'],
                'from'        => $from,
                'to'          => $to,
                'target'      => $target,
                'duration'    => $duration,
                'allowance'   => $allowance,

                'orders'    => number($data['Deal Component Total Acc Used Orders']),
                'customers' => number($data['Deal Component Total Acc Used Customers'])

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
