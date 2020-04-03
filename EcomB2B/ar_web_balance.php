<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  02 April 2020  16:45::03  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
require_once 'utils/table_functions.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$account = get_object('Account', 1);

$tipo = $_REQUEST['tipo'];

switch ($tipo) {


    case 'balance':
        $_data                             = get_table_parameters();
        $_data['parameters']['parent_key'] = $customer->id;
        balance($_data, $db, $account);

        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
}


function balance($_data, $db, $account) {


    //'Payment','Adjust','Cancel','Return','PayReturn','AddFunds'

    $rtext_label = 'transaction';



    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql = "select  $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $table_data = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $note = '';
            switch ($data['Credit Transaction Type']) {
                case 'Payment':

                    if ($data['Credit Transaction Amount'] < 0) {
                        $type = _('Order payment');
                        $note = sprintf('<a href="client_order.sys?id=%d" >%s</a>', $data['Order Key'], $data['Order Public ID']);

                    } else {
                        $note = sprintf('<a href="client_order.sys?id=%d" >%s</a>', $data['Order Key'], $data['Order Public ID']);


                        if ($data['Invoice Key'] and $data['Invoice Type'] == 'Refund') {
                            $type = _('Credited from refund');

                            $note .= sprintf(
                                ' <span class="error padding_left_10"> <i class="fal fa-file-invoice-dollar error"></i> <span class=" error"  >%s</span></span>', $data['Invoice Public ID']
                            );

                        } else {

                            $icon = 'fa-sack-dollar';

                            $type = _('Credited from payment');
                            $note .= sprintf(
                                ' <span class=" padding_left_10"> <i class="fal %s "></i> <span class=" discreet" >%s</span></span>', $icon, $data['Payment Related Payment Transaction ID']
                            );

                        }

                    }


                    break;
                case 'Cancel':
                    $type = _('Cancelled');

                    break;
                case 'Return':
                    $type = _('Return');
                    $note = $data['History Abstract'];

                    break;
                case 'MoneyBack':
                case 'RemoveFundsOther':
                    $type = _('Withdraw');
                    $note = $data['History Abstract'];

                    break;
                case 'PayReturn':
                case 'Compensation':
                case 'AddFundsOther':
                    $type = _('Deposit');
                    $note = $data['History Abstract'];

                    break;

                case 'TransferOut':
                case 'TransferIn':
                    $type = _('Transfer');
                    $note = $data['History Abstract'];

                    break;

                case 'Adjust':
                    $type = _('Adjust');
                    $note = $data['History Abstract'];
                    break;
                case 'TopUp':
                    $type = _('Top up');

                    switch ($data['Payment Account Block']) {
                        case 'BTree':
                            $icon = '<i class="far fa-credit-card"></i>';
                            break;
                        case 'BTreePaypal':
                        case 'Paypal':
                            $icon = '<i class="fab fa-paypal"></i>';
                            break;
                        default:
                            $icon = '';
                            break;
                    }

                    $note = sprintf('%s <span class="discreet italic small">%s</span>', $icon ,$data['Payment Transaction ID']);
                    break;
                default:
                    $type = $data['Credit Transaction Type'];

            }


            $amount_ac = $data['Credit Transaction Amount'] * $data['Credit Transaction Currency Exchange Rate'];
            $date      = strftime(
                "%a %e %b %y %T %Z", strtotime($data['Credit Transaction Date'].' +0:00')
            );

            $table_data[] = array(
                'id'             => (integer)$data['Credit Transaction Key'],
                'transaction' => $type.' '.$note.'<br/>'.$date,

                'amount'         => money($data['Credit Transaction Amount'], $data['Credit Transaction Currency Code']),
                'running_amount' => money($data['Credit Transaction Running Amount'], $data['Credit Transaction Currency Code']),

                'type'  => $type,
                'notes' => $note,


                'amount_ac' => money($amount_ac, $account->get('Currency Code')),
                'date'      => $date


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
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}
