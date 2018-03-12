<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:27:49 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


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
    case 'reports':
        reports(get_table_parameters(), $db, $user);
        break;
    case 'ec_sales_list':
        ec_sales_list(get_table_parameters(), $db, $user, $account);
        break;
    case 'pickers':
        pickers(get_table_parameters(), $db, $user, $account);
        break;
    case 'packers':
        packers(get_table_parameters(), $db, $user, $account);
        break;
    case 'sales':
        sales(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders':
        dispatched_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery_notes':
        dispatched_delivery_notes(get_table_parameters(), $db, $user, $account);
        break;
    case 'intrastat':
        intrastat(get_table_parameters(), $db, $user, $account);
        break;
    case 'intrastat_orders':
        intrastat_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'intrastat_products':
        intrastat_products(get_table_parameters(), $db, $user, $account);
        break;
    case 'billingregion_taxcategory':
        billingregion_taxcategory(get_table_parameters(), $db, $user, $account);
        break;
    case 'billingregion_taxcategory.invoices':
    case 'billingregion_taxcategory.refunds':
        invoices_billingregion_taxcategory(
            get_table_parameters(), $db, $user, $account
        );
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


function reports($_data, $db, $user) {
    global $db;
    $rtext_label = 'report';
    //include_once 'prepare_table/init.php';
    include_once 'utils/available_reports.php';


    //$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


    $adata = array();


    foreach ($available_reports as $key => $data) {

        $adata[] = array(
            'name'            =>sprintf('<span class="link" onclick="change_view(\'/report/%s\')">%s</span>', $key,$data['Label']),
            'section'         => sprintf('<span class="link" onclick="change_view(\'/reports/%s\')">%s</span>', $data['Group'],$data['GroupLabel'])

        );

    }

    $_order = (isset($_data['o']) ? $_data['o'] : 'id');
    $_dir   = ((isset($_data['od']) and preg_match('/desc/i', $_data['od'])) ? 'desc' : '');


    $rtext = get_rtext('report', count($available_reports));

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => count($available_reports)

        )
    );
    echo json_encode($response);
}


function ec_sales_list($_data, $db, $user, $account) {

    $rtext_label = 'record';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Invoice Tax Number Valid']) {
                case 'Yes':
                    $tax_number_valid = '<i class="fa fa-check-circle success padding_right_5" aria-hidden="true"></i> ';
                    break;
                case 'No':
                    $tax_number_valid = '<i class="fa fa-exclamation-circle error padding_right_5" aria-hidden="true"></i> ';
                    break;
                case 'Unknown':
                    $tax_number_valid = '<i class="fa fa-question-circle very_discreet padding_right_5" aria-hidden="true"></i> ';
                    break;
                default:
                    $tax_number_valid = '';
                    break;
            }

            if ($data['Invoice Tax Number'] == '') {
                $tax_number_valid = '';
            }

            $tax_number          = $data['Invoice Tax Number'];
            $country_2alpha_code = $data['Invoice Billing Country 2 Alpha Code'];
            $tax_number          = preg_replace(
                '/^'.$country_2alpha_code.'/i', '', $tax_number
            );
            $tax_number          = preg_replace(
                '/[^a-z^0-9]/i', '', $tax_number
            );

            if (preg_match('/^gr$/i', $country_2alpha_code)) {
                $country_2alpha_code = 'EL';
            }

            $tax_number = preg_replace(
                '/^'.$country_2alpha_code.'/i', '', $tax_number
            );
            $tax_number = preg_replace('/[^a-z^0-9]/i', '', $tax_number);

            $adata[] = array(

                // 'tax_code'=> sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code']=='UNK'?_('Unknown tax code'):$data['Tax Category Name']), $data['Invoice Tax Code']),
                // 'request'=>$data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],
                'country_code' => $data['Invoice Billing Country 2 Alpha Code'],
                'invoices'     => number($data['invoices']),
                'refunds'      => number($data['refunds']),
                'customer'     => sprintf(
                    '<span class="link" onClick="change_view(\'customer/%d\')"   title="%s">%06d</span>', $data['Invoice Customer Key'], $data['Invoice Customer Name'], $data['Invoice Customer Key']
                ),
                'tax_number'   => $tax_number_valid.$tax_number,
                'tax'          => money(
                    $data['tax'], $account->get('Account Currency')
                ),
                'net'          => money(
                    $data['net'], $account->get('Account Currency')
                ),
                'total'        => money(
                    $data['total'], $account->get('Account Currency')
                ),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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


function billingregion_taxcategory($_data, $db, $user, $account) {

    $rtext_label = 'record';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Invoice Billing Region']) {
                case 'EU':
                    $billing_region = _('European Union');
                    break;
                case 'Unknown':
                    $billing_region = _('Unknown');
                    break;
                case 'NOEU':
                    $billing_region = _('Outside European Union');
                    break;
                case 'GBIM':
                    $billing_region = 'GB+IM';
                    break;
                default:
                    $billing_region = $data['Invoice Billing Region'];
                    break;
            }


            $adata[] = array(

                'billing_region' => $billing_region,
                'tax_code'       => sprintf(
                    '<span title="%s">%s</span>', ($data['Invoice Tax Code'] == 'UNK' ? _('Unknown tax code') : $data['Tax Category Name']), $data['Invoice Tax Code']
                ),
                'request'        => $data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],


                'invoices' => sprintf(
                    '<span class="link" onClick="change_view(\'report/billingregion_taxcategory/invoices/%s/%s\')" >%s</span>', $data['Invoice Billing Region'], $data['Invoice Tax Code'], number($data['invoices'])
                ),

                'refunds' => sprintf(
                    '<span class="link" onClick="change_view(\'report/billingregion_taxcategory/refunds/%s/%s\')" >%s</span>', $data['Invoice Billing Region'], $data['Invoice Tax Code'], number($data['refunds'])
                ),

                'customers' => number($data['customers']),
                'tax'       => money($data['tax'], $account->get('Account Currency')),
                'net'       => money($data['net'], $account->get('Account Currency')),
                'total'     => money($data['total'], $account->get('Account Currency')),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $rtext = preg_replace('/\(|\)/', '', $rtext);


    if (is_array($parameters['excluded_stores']) and count(
            $parameters['excluded_stores']
        ) > 0) {
        $excluded_stores = '';
        $sql             = sprintf(
            'SELECT `Store Key`,`Store Code`,`Store Name` FROM `Store Dimension` WHERE `Store Key` IN (%s)', join($parameters['excluded_stores'], ',')
        );

        if ($result = $db->query($sql)) {

            foreach ($result as $data) {
                $excluded_stores .= $data['Store Code'].', ';

            }

        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $excluded_stores = preg_replace('/, $/', '', $excluded_stores);


        $rtext .= ' ('._('Excluding').': '.$excluded_stores.')';
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


function invoices_billingregion_taxcategory($_data, $db, $user) {

    $rtext_label = 'invoice';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['Invoice Paid'] == 'Yes') {
                $state = _('Paid');
            } elseif ($data['Invoice Paid'] == 'Partially') {
                $state = _('Partially Paid');
            } else {
                $state = _('No Paid');
            }


            if ($data['Invoice Type'] == 'Invoice') {
                $type = _('Invoice');
            } elseif ($data['Invoice Type'] == 'CreditNote') {
                $type = _('Credit Note');
            } else {
                $type = _('Refund');
            }

            switch ($data['Invoice Main Payment Method']) {
                default:
                    $method = $data['Invoice Main Payment Method'];
            }

            $adata[] = array(
                'id' => (integer)$data['Invoice Key'],

                'number'               => sprintf('<span class="link" onclick="change_view(\'invoices/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Key'], $data['Invoice Public ID']),
                'customer'             => $data['Invoice Customer Name'],
                'store_code'           => sprintf('<span title="%s">%s</span>', $data['Store Name'], $data['Store Code']),
                'date'                 => strftime("%e %b %Y", strtotime($data['Invoice Date'].' +0:00')),
                'total_amount'         => money(
                    $data['Invoice Total Amount'], $data['Invoice Currency']
                ),
                'net'                  => money(
                    $data['Invoice Total Net Amount'], $data['Invoice Currency']
                ),
                'tax'                  => money(
                    $data['Invoice Total Tax Amount'], $data['Invoice Currency']
                ),
                'shipping'             => money(
                    $data['Invoice Shipping Net Amount'], $data['Invoice Currency']
                ),
                'items'                => money(
                    $data['Invoice Items Net Amount'], $data['Invoice Currency']
                ),
                'type'                 => $type,
                'payment_method'       => $method,
                'state'                => $state,
                'billing_country'      => $data['Invoice Billing Country 2 Alpha Code'],
                'billing_country_flag' => sprintf(
                    '<img title="%s" src="/art/flags/%s.gif">', $data['Country Name'], strtolower($data['Invoice Billing Country 2 Alpha Code'])
                )

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    if (is_array($parameters['excluded_stores']) and count(
            $parameters['excluded_stores']
        ) > 0) {
        $excluded_stores = '';
        $sql             = sprintf(
            'SELECT `Store Key`,`Store Code`,`Store Name` FROM `Store Dimension` WHERE `Store Key` IN (%s)', join($parameters['excluded_stores'], ',')
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $data) {
                $excluded_stores .= $data['Store Code'].', ';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $excluded_stores = preg_replace('/, $/', '', $excluded_stores);
        $rtext           .= ' ('._('Excluding').': '.$excluded_stores.')';
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


function intrastat($_data, $db, $user, $account) {

    $rtext_label = 'record';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'country_code' => $data['Delivery Note Address Country 2 Alpha Code'],
                // 'invoices'     => number($data['invoices']),

                'period'      => $data['monthyear'],
                'tariff_code' => $data['tariff_code'],
                'value'       => money($data['value'], $account->get('Account Currency')),
                'items'       => $data['items'],
                'products'    => sprintf(
                    '<span class="link" onClick="change_view(\'report/intrastat/products/%s/%s\')" >%s</span>', $data['Delivery Note Address Country 2 Alpha Code'], ($data['tariff_code'] == '' ? 'missing' : $data['tariff_code']), number($data['products'])
                ),
                'orders'      => sprintf(
                    '<span class="link" onClick="change_view(\'report/intrastat/orders/%s/%s\')" >%s</span>', $data['Delivery Note Address Country 2 Alpha Code'], ($data['tariff_code'] == '' ? 'missing' : $data['tariff_code']), number($data['orders'])
                ),

                'weight' => weight($data['weight'], 'Kg', 2, false, true),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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


function intrastat_orders($_data, $db, $user, $account) {

    $rtext_label = 'order';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(


                'number'       => sprintf('<span class="link" onClick="change_view(\'orders/%s/%s\')" >%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),
                'customer'     => sprintf('<span class="link" onClick="change_view(\'customers/%s/%s\')" >%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'date'         => strftime("%e %b %Y", strtotime($data['Delivery Note Date'].' +0:00')),
                'total_amount' => money($data['Order Total Amount'], $data['Order Currency Code'])


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s order', '%s orders', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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


function intrastat_products($_data, $db, $user, $account) {

    $rtext_label = 'product';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'store' => sprintf('<span class="link" onClick="change_view(\'products/%s\')" title="%s">%s</span>', $data['Product Store Key'], $data['Store Name'], $data['Store Code']),

                'code'       => sprintf('<span class="link" onClick="change_view(\'products/%s/%s\')" >%s</span>', $data['Product Store Key'], $data['Product ID'], $data['Product Code']),
                'name'       => $data['Product Name'],
                'units'      => number($data['Product Units Per Case']),
                'price'      => money($data['Product Price'] / $data['Product Units Per Case'], $data['Order Currency Code']),
                'weight'     => weight($data['Product Unit Weight'], 'Kg', 3, false, true),
                'units_send' => number($data['units_send']),

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s product', '%s products', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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


function pickers($_data, $db, $user, $account) {

    $rtext_label = 'picker';



    foreach ($_data['parameters'] as $parameter => $parameter_value) {
        $_SESSION['table_state']['packers'][$parameter] = $parameter_value;

    }





    include_once 'prepare_table/init.php';


    $total_dp=0;
    $sql=sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from  `Inventory Transaction Fact` $where group by `Picker Key` ");


    if ($result=$db->query($sql)) {
        foreach ($result as $row) {
            $total_dp+=($row['units']);

        }
    }else {
        print_r($error_info=$db->errorInfo());
        print "$sql\n";
        exit;
    }



    if($total_dp==0){
        $total_dp=1;
    }


    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {



            $adata[] = array(

                'name'          => $data['Staff Name'],
                'deliveries'    => number($data['deliveries']),
                'deliveries_with_errors'=> number($data['deliveries_with_errors']),
                'deliveries_with_errors_percentage'=>  percentage($data['deliveries_with_errors'], $data['deliveries']),
                'picks_with_errors'=> number($data['picks_with_errors']),
                'picks_with_errors_percentage'=>  percentage($data['picks_with_errors_percentage'], 1),
                'picked'        => number($data['picked'],0),
                'dp'            => number($data['dp']),
                'dp_percentage' => percentage($data['dp'], $total_dp),
                'hrs'           => number($data['hrs'], 1, true),
                'dp_per_hour'   => ($data['dp_per_hour'] == '' ? '' : number($data['dp_per_hour'], 1, true)),

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

function packers($_data, $db, $user, $account) {

    $rtext_label = 'packer';


    foreach ($_data['parameters'] as $parameter => $parameter_value) {
        $_SESSION['table_state']['pickers'][$parameter] = $parameter_value;

    }

    include_once 'prepare_table/init.php';


    $total_dp=0;
    $sql=sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from  `Inventory Transaction Fact` $where group by `Packer Key` ");


    if ($result=$db->query($sql)) {
        foreach ($result as $row) {
            $total_dp+=($row['units']);

        }
    }else {
        print_r($error_info=$db->errorInfo());
        print "$sql\n";
        exit;
    }



    if($total_dp==0){
        $total_dp=1;
    }


    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'name'          => $data['Staff Name'],
                'deliveries'    => number($data['deliveries']),
                'packed'        => number($data['packed'],0),
                'dp'            => number($data['dp']),
                'dp_percentage' => percentage($data['dp'], $total_dp),
                'hrs'           => number($data['hrs'], 1, true),
                'dp_per_hour'   => ($data['dp_per_hour'] == '' ? '' : number($data['dp_per_hour'], 1, true)),
                'deliveries_with_errors'=> number($data['deliveries_with_errors']),
                'deliveries_with_errors_percentage'=>  percentage($data['deliveries_with_errors'], $data['deliveries']),
                'picks_with_errors'=> number($data['picks_with_errors']),
                'picks_with_errors_percentage'=>  percentage($data['picks_with_errors_percentage'], 1),


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



function sales($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $refund_amount=money($data['refunds_amount_oc'],$account->get('Account Currency Code'));
            $revenue=money($data['revenue_oc'],$account->get('Account Currency Code'));
            $profit=money($data['profit_oc'],$account->get('Account Currency Code'));

            $adata[] = array(

                'store' =>$data['Store Code'],
                'invoices' =>number($data['invoices']),
                'refunds' =>number($data['refunds']),
                'customers' =>number($data['customers']),
                'refund_amount' =>$refund_amount,
                'revenue' =>$revenue,
                'profit' =>$profit
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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


function dispatched_orders($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $refund_amount=money($data['refunds_amount_oc'],$account->get('Account Currency Code'));
            $revenue=money($data['revenue_oc'],$account->get('Account Currency Code'));
            $profit=money($data['profit_oc'],$account->get('Account Currency Code'));

            $adata[] = array(

                'store' =>$data['Store Code'],
                'orders' =>number($data['orders']),
                'refunds' =>number($data['refunds']),
                'replacements' =>number($data['replacements']),
                'customers' =>number($data['customers']),
                'refund_amount' =>$refund_amount,
                'revenue' =>$revenue,
                'profit' =>$profit,
                'margin' =>percentage($data['profit_oc'],$data['revenue_oc'])
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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



function dispatched_delivery_notes($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


           // $refund_amount=money($data['refunds_amount_oc'],$account->get('Account Currency Code'));
           // $revenue=money($data['revenue_oc'],$account->get('Account Currency Code'));
          //  $profit=money($data['profit_oc'],$account->get('Account Currency Code'));

            $adata[] = array(

                'store' =>$data['Store Code'],
                'shipments' =>number($data['shipments']),

                'delivery_notes' =>number($data['shipments']-$data['replacements']),
                //'refunds' =>number($data['refunds']),
                'replacements' =>number($data['replacements']),
                'customers' =>number($data['customers']),
                //'refund_amount' =>$refund_amount,
                //'revenue' =>$revenue,
                //'profit' =>$profit,
                //'margin' =>percentage($data['profit_oc'],$data['revenue_oc'])
            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


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
