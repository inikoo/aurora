<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 August 2018 at 23:33:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
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


//print_r($_REQUEST);

switch ($tipo) {


    case 'get_order_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'order_key' => array(
                             'type' => 'kry',
                         ),

                         'device_prefix' => array(
                             'type' => 'string',

                         )
                     )
        );

        get_order_html($data, $customer, $db);


        break;
    case 'order_items':
        $_data = get_table_parameters();


        $order = get_object('Order', $_data['parameters']['parent_key']);

        if ($order->get('Order Customer Key') != $customer->id) {
            $response = array(
                'state' => 400,
                'resp'  => 'Error'
            );
            echo json_encode($response);
            exit;
        }

        orders_items($_data, $db);

        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}


function get_order_html($data, $customer, $db) {


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $order = get_object('Order', $data['order_key']);

    if (!$order->id) {
        $response = array(
            'state' => 200,
            'html'  => _('Order not found')
        );
        echo json_encode($response);
    }

    if ($customer->id != $order->get('Order Customer Key')) {
        $response = array(
            'state' => 200,
            'html'  => _('Wrong order id')
        );
        echo json_encode($response);
    }

    $website = get_object('Website', $_SESSION['website_key']);
    $store   = get_object('Store', $website->get('Website Store Key'));


    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('labels', $website->get('Localised Labels'));
    $smarty->assign('logged_in', true);

    $smarty->assign('items_data', $order->get_items());


    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.order.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


/**
 * @param $_data
 * @param $db \PDO
 */
function orders_items($_data, $db) {


    include_once 'utils/currency_functions.php';

    $rtext_label = 'product';


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

    $sql = "select $fields from $table $where $wheref  order by $order $order_direction limit $start_from,$number_results";




    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product History Name'].'</span>';

            if ($data['Webpage URL'] == '') {
                $code = sprintf('<span title="%s">%s</span>', $name, $data['Product History Code']);

            } else {
                $code = sprintf('<a class="link" href="%s" title="%s">%s</a>', $data['Webpage URL'], $name, $data['Product History Code']);

            }


            $reference = $data['Customer Portfolio Reference'];


            $ordered = sprintf('<span>%s</span>', number($data['Order Quantity']));

            if($data['Order Bonus Quantity']>0){
                $ordered.=sprintf(' (<i class="fa fa-fw fa-gift" title="%s">%s</i>)',_('Free gift'),number($data['Order Bonus Quantity']));
            }
            if($data['No Shipped Due Out of Stock']>0){
                $ordered.=sprintf('<br/><span class="small error"><i class="fa fa-fw error fa-exclamation-circle" title="%s"></i> %s',_('Not dispatched'),number(-1*$data['No Shipped Due Out of Stock']));
            }

            if($data['Current Dispatching State']=='Dispatched'){
                $qty     = sprintf('<span>%s</span>', number($data['Delivery Note Quantity']));

            }else{
                $qty     = '';

            }


            $record_data[] = array(

                'id'        => (integer)$data['Product ID'],
                'code'      => $code,
                'name'      => $name,
                'reference' => $reference,
                'net'       => sprintf('<span>%s</span>', money($data['Order Transaction Amount'], $data['Order Currency Code'])),
                'ordered'   => $ordered,
                'qty'       => $qty


            );


        }

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

