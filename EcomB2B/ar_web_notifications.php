<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:45 am Thursday, 20 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

use Aws\Exception\AwsException;
use Aws\Sns\SnsClient;

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

    case 'notifications_control_panel':
        $data = prepare_values(
            $_REQUEST, array(

                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );
        notifications_control_panel($data, $customer);
        break;
    case 'subscribe':
        $data = prepare_values(
            $_REQUEST, array(

                         'channel'  => array(
                             'type' => 'string',
                         ),
                         'protocol' => array(
                             'type' => 'string',
                         ),
                         'endpoint' => array(
                             'type' => 'string',
                         ),
                         'sns_key'  => array(
                             'type' => 'string',
                         )
                     )
        );
        subscribe($data, $db, $customer, $website);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
}


/**
 * @param $data
 * @param $db       \PDO
 * @param $customer \Public_Customer
 * @param $website  \Public_Website
 */
function subscribe($data, $db, $customer, $website) {


    $SnSclient = new SnsClient(
        [
            'version'     => 'latest',
            'region'      => AWS_SNS_DS_NOTIFICATION['region'],
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        ]
    );

    $protocol = $data['protocol'];
    $endpoint = $data['endpoint'];
    switch ($data['channel']) {
        case 'ds_notifications':
            $topic = AWS_SNS_DS_NOTIFICATION['arn'];
            break;
        default:
            echo json_encode(
                array(
                    'state' => 400,
                    'msg'   => 'Unknown channel '.$data['channel']
                )
            );
            exit;
    }


    $subscription_data = [
        'Attributes'            => array(
            'FilterPolicy' => json_encode(
                array(
                    'account_code' => [DNS_ACCOUNT_CODE],
                    'customer_key' => [$customer->id],
                    'website_key'  => [$website->id],
                    'store_key'    => [$website->get('Website Store Key')],
                )
            )
        ),
        'Protocol'              => $protocol,
        'Endpoint'              => $endpoint,
        'ReturnSubscriptionArn' => true,
        'TopicArn'              => $topic,
    ];


    try {
        $result = $SnSclient->subscribe($subscription_data);

        $sql = "insert into `Customer SNS Fact` (`Customer SNS Created Date`,`Customer SNS Customer Key`,`Customer SNS Store Key`,`Customer SNS Subscription ARN`,`Customer SNS Subscription Protocol`,`Customer SNS Subscription Endpoint`,`Customer SNS Subscription Status`,`Customer SNS Settings`)
            values (?,?,?,?,?,?,?,?)   ON DUPLICATE KEY UPDATE `Customer SNS Key`=LAST_INSERT_ID(`Customer SNS Key`)
            ";


        $db->prepare($sql)->execute(
            array(
                gmdate('Y-m-d H:i:s'),
                $customer->id,
                $customer->get('Customer Store Key'),
                $result->get('SubscriptionArn'),
                $protocol,
                $endpoint,
                'Pending',
                '{}'

            )
        );

        $customer_sns_key = $db->lastInsertId();


        $sns_keys = $customer->metadata('sns_keys');
        if ($sns_keys == '') {
            $sns_keys = [];
        } else {
            $sns_keys = json_decode($sns_keys, true);
        }
        $sns_keys[] = $customer_sns_key;
        $customer->fast_update_json_field('Customer Metadata', 'sns_keys', json_encode($sns_keys));


        $smarty = new Smarty();
        $smarty->setTemplateDir('templates');
        $smarty->setCompileDir('server_files/smarty/templates_c');
        $smarty->setCacheDir('server_files/smarty/cache');
        $smarty->setConfigDir('server_files/smarty/configs');
        $smarty->addPluginsDir('./smarty_plugins');

        $smarty->assign('customer', $customer);

        $sql  = "select * from `Customer SNS Fact` where `Customer SNS Key`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $customer_sns_key
            )
        );
        if ($row = $stmt->fetch()) {
            $smarty->assign('subscription', $row);
            echo json_encode(
                array(
                    'state'     => 200,
                    'subs_html' => $smarty->fetch('theme_1/_notifications.'.$protocol.'.theme_1.EcomDS.tpl')

                )
            );
        } else {
            echo json_encode(
                array(
                    'state'     => 200,
                    'subs_html' => ''

                )
            );
        }


    } catch (AwsException $e) {
        // output error message if fails
        // print_r($e->getMessage());

        echo json_encode(
            array(
                'state' => 400,
                'msg'   => 'Error'

            )
        );

    }


    echo json_encode(
        array(
            'state' => 200,

        )
    );


}


/**
 * @param $data
 * @param $customer \Public_Customer
 */
function notifications_control_panel($data, $customer) {

    $theme  = 'theme_1';
    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $smarty->assign('customer', $customer);



    echo json_encode(
        array(
            'state' => 200,
            'html'  => $smarty->fetch('theme_1/_notifications.'.$theme.'.EcomDS'.($data['device_prefix'] == '' ? '' : '.'.$data['device_prefix']).'.tpl'),

        )
    );
    exit;


}
