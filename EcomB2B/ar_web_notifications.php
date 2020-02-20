<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1:45 am Thursday, 20 February 2020 (MYT), Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

use Aws\Sns\SnsClient;
use Aws\Exception\AwsException;

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

                         'channel' => array(
                             'type'     => 'string',
                         ),
                         'protocol' => array(
                             'type'     => 'string',
                         ),
                         'endpoint' => array(
                             'type'     => 'string',
                         )
                     )
        );
        subscribe($data, $customer);
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
 * @param $customer \Public_Customer
 */
function subscribe($data, $customer) {


    $SnSclient = new SnsClient([
                                 'version'     => 'latest',
                                 'region'      => AWS_SNS_DS_NOTIFICATION['region'],
                                 'credentials' => [
                                     'key'    => AWS_ACCESS_KEY_ID,
                                     'secret' => AWS_SECRET_ACCESS_KEY,
                                 ],
                             ]);

    $protocol = $data['protocol'];
    $endpoint = $data['endpoint'];
    switch ($data['channel']){
        case 'price_notification':
            $topic = AWS_SNS_DS_NOTIFICATION['arn'];
            break;
    }


    print_r([
                'Protocol' => $protocol,
                'Endpoint' => $endpoint,
                'ReturnSubscriptionArn' => true,
                'TopicArn' => $topic,
            ]);



    try {
        $result = $SnSclient->subscribe([
                                            'Protocol' => $protocol,
                                            'Endpoint' => $endpoint,
                                            'ReturnSubscriptionArn' => true,
                                            'TopicArn' => $topic,
                                        ]);
        var_dump($result);
    } catch (AwsException $e) {
        // output error message if fails
        print_r($e->getMessage());
    }



    echo json_encode(
        array(
            'state'               => 200,

        )
    );
    exit;


}


/**
 * @param $data
 * @param $customer \Public_Customer
 */
function notifications_control_panel($data, $customer) {

    $theme='theme_1';
    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $smarty->assign('customer',$customer);
    $smarty->assign('settings',$customer->metadata('notifications_settings'));

    echo json_encode(
        array(
            'state'               => 200,
            'html'=>$smarty->fetch('theme_1/_notifications.'.$theme.'.EcomDS'.($data['device_prefix']==''?'':'.'.$data['device_prefix']).'.tpl'),

        )
    );
    exit;


}
