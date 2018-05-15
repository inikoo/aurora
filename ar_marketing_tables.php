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

switch ($tipo) {
    case 'marketing_server':
        marketing_server(get_table_parameters(), $db, $user);
        break;
    case 'campaigns':
        campaigns(get_table_parameters(), $db, $user);
        break;
    case 'deals':
        deals(get_table_parameters(), $db, $user);
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
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function marketing_server($_data, $db, $user) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            $adata[] = array(
                'id' => (integer)$data['Store Key'],

                'code'      => sprintf('<span class="link" onClick="change_view(\'marketing/%d\')">%s</span>', $data['Store Key'], $data['Store Code']),
                'name'      => $data['Store Name'],
                'campaigns' => sprintf('<span class="link" onClick="change_view(\'campaigns/%d\')">%s</span>', $data['Store Key'], $data['campaigns']),
                'deals'     => sprintf('<span class="link" onClick="change_view(\'deals/%d\')">%s</span>', $data['Store Key'], $data['deals']),
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


function deals($_data, $db, $user) {

    $rtext_label = 'offer';
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

            if ($data['Deal Term Allowances Label'] == '' and isset($data['Deal Component Allowance Description'])) {
                $data['Deal Term Allowances Label'] = $data['Deal Component Terms Description'].' &rArr; '.$data['Deal Component Allowance Description'];
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

            } else {
                $name = sprintf('<span class="link" onClick="change_view(\'campaigns/%d/%d/deal/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Campaign Key'], $data['Deal Key'], $data['Deal Name']);

            }


            $description = sprintf('<span class="%s"  >%s</span>', $description_class, $data['Deal Term Allowances Label']);


            $adata[] = array(
                'id'          => (integer)$data['Deal Key'],
                'status'      => $status,
                'name'        => $name,
                'description' => $description,
                'from'        => $from,
                'to'          => $to,
                'orders'      => number($data['Deal Total Acc Used Orders']),
                'customers'   => number($data['Deal Total Acc Used Customers'])

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

    $rtext_label = 'campaign';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Deal Campaign Status']) {
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
                    $status = $data['Deal Campaign Status'];
            }

            $duration = '';
            if ($data['Deal Campaign Valid To'] == '' and $data['Deal Campaign Valid From'] == '') {
                $duration = _('Permanent');
            } else {

                if ($data['Deal Campaign Valid From'] != '') {
                    $duration = strftime(
                        "%x", strtotime($data['Deal Campaign Valid From']." +00:00")
                    );

                }
                $duration .= ' - ';
                if ($data['Deal Campaign Valid To'] != '') {
                    $duration .= strftime(
                        "%x", strtotime($data['Deal Campaign Valid To']." +00:00")
                    );

                } else {
                    $duration .= _('Present');
                }

            }

            if ($data['Deal Campaign Valid To'] != '') {
                $to = strftime(
                    "%x", strtotime($data['Deal Campaign Valid To']." +00:00")
                );
            } else {
                $to = _('Permanent');
            }


            if ($data['Deal Campaign Valid From'] != '') {
                $from = strftime(
                    "%x", strtotime($data['Deal Campaign Valid From']." +00:00")
                );
            } else {
                $from = '';
            }

            $adata[] = array(
                'id'        => (integer)$data['Deal Campaign Key'],
                'store_key' => (integer)$data['Deal Campaign Store Key'],
                'status'    => $status,
                'name'      => $data['Deal Campaign Name'],
                'from'      => $from,
                'to'        => $to,
                'deals'     => number(
                    $data['Deal Campaign Number Current Deals']
                ),

                'orders'    => number(
                    $data['Deal Campaign Total Acc Used Orders']
                ),
                'customers' => number(
                    $data['Deal Campaign Total Acc Used Customers']
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

function components($_data, $db, $user) {

    $rtext_label = 'allowance';
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
                'id'          => (integer)$data['Deal Component Key'],
                'status'      => $status,
                'name'        => $data['Deal Component Name Label'],
                'description' => $data['Deal Component Term Label'].' <i class="fa fa-arrow-right"></i> '.$data['Deal Component Allowance Label'],
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
                    $allowance = '<span id="deal_component_allowance_'.$data['Deal Component Key'].'"><span class="button" key="'.$data['Deal Component Key'].'" target="'.$data['Deal Component Allowance Target Label'].'" allowance="'.percentage(
                            $data['Deal Component Allowance'], 1
                        ).'" description="'.$data['Deal Component Allowance Label'].'"  onclick="edit_component_allowance(this)"   >'.percentage($data['Deal Component Allowance'], 1).'</span></span>';
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

            $status = '<span id="deal_component_status_'.$data['Deal Component Key'].'"><span status="'.$data['Deal Component Status'].'" target="'.$data['Deal Component Allowance Target Label'].'" key="'.$data['Deal Component Key']
                .'" class="button" onclick="edit_component_status(this)">'.$status.'</span></span>';

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
                'name'        => $data['Deal Component Name Label'],
                'description' => '<span id="deal_component_description_'.$data['Deal Component Key'].'">'.$data['Deal Component Allowance Label'].'</span>',
                'allowance'   => $allowance,
                'from'        => $from,
                'to'          => $to,
                'target'      => $target,
                'duration'    => $duration,
                'orders'      => number($data['Deal Component Total Acc Used Orders']),
                'customers'   => number($data['Deal Component Total Acc Used Customers'])

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


            $adata[] = array(
                'id'          => (integer)$data['Deal Key'],
                'status'      => $status,
                'name'        => sprintf('<span class="link" onClick="change_view(\'campaigns/%d/%d/deal/%d\')">%s</span>', $data['Deal Store Key'], $data['Deal Campaign Key'], $data['Deal Key'], $data['Deal Name']),
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

?>
