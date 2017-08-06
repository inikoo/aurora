<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 15:07:32 CET, Tessera, Italy
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
    case 'payment_service_providers':
        payment_service_providers(get_table_parameters(), $db, $user);
        break;
    case 'accounts':
        payment_accounts(get_table_parameters(), $db, $user);
        break;
    case 'stores':
        stores(get_table_parameters(), $db, $user);
        break;
    case 'payments':
        payments(get_table_parameters(), $db, $user);
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


function payment_service_providers($_data, $db, $user) {
    global $db, $account;
    $rtext_label = 'payment_service_provider';
    include_once 'prepare_table/init.php';

    $account_currency = $account->get('Account Currency');

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    foreach ($db->query($sql) as $data) {


        $other_currency = ($account_currency != $data['Payment Service Provider Currency']);

        $adata[] = array(
            'id'           => (integer)$data['Payment Service Provider Key'],
            'code'         => $data['Payment Service Provider Code'],
            'name'         => $data['Payment Service Provider Name'],
            'accounts'     => number(
                $data['Payment Service Provider Accounts']
            ),
            'transactions' => number(
                $data['Payment Service Provider Transactions']
            ),
            'payments'     => money(
                $data['Payment Service Provider Payments Amount'], $account_currency
            ),
            'refunds'      => money(
                $data['Payment Service Provider Refunds Amount'], $account_currency
            ),
            'balance'      => money(
                $data['Payment Service Provider Balance Amount'], $account_currency
            )
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


function payment_accounts($_data, $db, $user) {
    global $db, $account;
    $rtext_label = 'payment_account';
    include_once 'prepare_table/init.php';

    $account_currency = $account->get('Account Currency');

    $sql
           = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $other_currency = ($account_currency != $data['Payment Account Currency']);

            $adata[] = array(
                'id'           => (integer)$data['Payment Account Key'],
                'code'         => $data['Payment Account Code'],
                'name'         => $data['Payment Account Name'],
                'transactions' => number($data['Payment Account Transactions']),
                'payments'     => money(
                    $data['Payment Account Payments Amount'], $account_currency
                ),
                'refunds'      => money(
                    $data['Payment Account Refunds Amount'], $account_currency
                ),
                'balance'      => money(
                    $data['Payment Account Balance Amount'], $account_currency
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


function payments($_data, $db, $user) {
    global $db, $account;
    $rtext_label = 'transaction';
    include_once 'prepare_table/init.php';


    $sql
        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Payment Type']) {
                case 'Payment':
                    $type = _('Payment');
                    break;
                case 'Refund':
                    $type = _('Refund');
                    break;
                case 'Credit':
                    $type = _('Credit');
                    break;
                default:
                    $type = $data['Payment Type'];
                    break;
            }


            switch ($data['Payment Transaction Status']) {
                case 'Pending':
                    $status = _('Pending');
                    break;
                case 'Completed':
                    $status = _('Completed');
                    break;
                case 'Cancelled':
                    $status = _('Cancelled');
                    break;
                case 'Error':
                    $status = _('Error');
                    break;
                case 'Declined':
                    $status = _('Declined');
                    break;
                default:
                    $status = $data['Payment Transaction Status'];
                    break;
            }


            $notes = '';


            $amount= '<span class="'. ($data['Payment Transaction Status']!='Completed'?'strikethrough':'').'" >'.money($data['Payment Transaction Amount'], $data['Payment Currency Code']).'</span>';



            $adata[] = array(
                'id'           => (integer)$data['Payment Key'],
                'reference'    => $data['Payment Transaction ID'],
                'currency'     => $data['Payment Currency Code'],
                'amount'       =>$amount,
                'formatted_id' => sprintf("<span class='link' onclick='change_view(\"/%s/%d/payment/%d\")' >%05d", $parameters['parent'],$parameters['parent_key'],$data['Payment Key'],$data['Payment Key']),
                'type'         => $type,
                'status'       => $status,
                'notes'        => $notes,
                'date'         => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Payment Last Updated Date'].' +0:00')),

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

function stores($_data, $db, $user) {


    $rtext_label = 'store';

    include_once 'prepare_table/init.php';

    $sql         = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();


    $max_length=36;

    foreach ($db->query($sql) as $data) {



        if($data['payment_account_data']==''){
            $data['Payment Account Store Key']='';
            $data['Payment Account Store Status']='';
            $data['Payment Account Store Show In Cart']='';
        }else{
            list($data['Payment Account Store Key'],$data['Payment Account Store Status'],$data['Payment Account Store Show In Cart'])=preg_split('/,/',$data['payment_account_data']);

        }





        $name = (strlen($data['Store Name']) > $max_length ? substr($data['Store Name'],0,$max_length)."..." : $data['Store Name']);


        if($data['Payment Account Store Status']=='') {
            $accepted=sprintf('<span class="very_discreet ">%s</span>',_('No applicable'));
            $shown_in_website='';
        }else if($data['Payment Account Store Status']=='Active'){
            $accepted=sprintf('<span class="success button ">%s</span>',_('Yes'));

            if($data['Payment Account Store Show In Cart']=='Yes'){
                $shown_in_website=sprintf('<span class="success button ">%s</span>',_('Yes'));

            }else{
                $shown_in_website=sprintf('<span class="error discreet button ">%s</span>',_('No'));
            }

        }else{
            $accepted=sprintf('<span class="error discreet button ">%s</span>',_('No'));
            $shown_in_website='';
        }







        $record_data[] = array(
            'access' => (in_array($data['Store Key'], $user->stores) ? '' : '<i class="fa fa-lock "></i>'),

            'id'   => (integer)$data['Store Key'],
            'code' => sprintf('<span class="link" onClick="change_view(\'store/%d\')" >%s</span>',$data['Store Key'],$data['Store Code']),
            'name' => sprintf('<span class="link" onClick="change_view(\'store/%d\')" >%s</span>',$data['Store Key'],$name),
            'website' => sprintf('<span class="link" onClick="change_view(\'store/%d/website\')" title="%s" >%s</span>',$data['Store Key'],$data['Website Name'],$data['Website Code']),

           'accepted'=>$accepted,
            'shown_in_website'=>$shown_in_website



        );

    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $record_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
