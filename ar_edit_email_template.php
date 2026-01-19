<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 19:58:53 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;


require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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

    case 'send_email':
        $data = prepare_values(
            $_REQUEST, array(
                         'recipient'     => array('type' => 'string'),
                         'recipient_key' => array('type' => 'key'),
                         'text'          => array('type' => 'string'),
                         'json'          => array('type' => 'string'),
                         'html'          => array('type' => 'html'),
                         'subject'       => array('type' => 'string'),
                     )
        );
        send_email($data, $editor, $smarty, $db);
        break;

    case 'set_email_template_type':
        $data = prepare_values(
            $_REQUEST, array(
                         'value'              => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        set_email_template_type($data, $editor, $smarty, $db);
        break;
    case 'bee_token':
        bee_token();

        break;
    case 'send_test_email':

        $data = prepare_values(
            $_REQUEST, array(
                         'html'               => array('type' => 'string'),
                         'email'              => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),
                         'options'            => array(
                             'type'     => 'string',
                             'optional' => true
                         ),


                     )
        );
        send_test_email($data, $smarty);
        break;


    case 'publish_email_template':
        $data = prepare_values(
            $_REQUEST, array(
                         'subject'            => array('type' => 'string'),
                         'json'               => array('type' => 'string'),
                         'text'               => array('type' => 'string'),
                         'html'               => array('type' => 'html'),
                         'email_template_key' => array('type' => 'key'),


                     )
        );
        publish_email_template($data, $editor, $smarty, $db);
        break;

    case 'auto_save_email_template':
        $data = prepare_values(
            $_REQUEST, array(
                         'subject'            => array('type' => 'string'),
                         'json'               => array('type' => 'string'),
                         'text'               => array('type' => 'string'),
                         'html'               => array('type' => 'html'),
                         'email_template_key' => array('type' => 'key'),


                     )
        );
        auto_save_email_template($data, $editor, $smarty, $db);
        break;

    case 'save_email_template_text_part':
        $data = prepare_values(
            $_REQUEST, array(
                         'subject'            => array('type' => 'string'),
                         'text'               => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),


                     )
        );
        save_email_template_text_part($data, $editor, $smarty, $db);
        break;

    case 'save_blueprint':
        $data = prepare_values(
            $_REQUEST, array(
                         'json'               => array('type' => 'string'),
                         'html'               => array('type' => 'html'),
                         'name'               => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),


                     )
        );
        save_blueprint($data, $editor, $smarty, $db);
        break;
    case 'select_blueprint':
        $data = prepare_values(
            $_REQUEST, array(
                         'role'      => array('type' => 'string'),
                         'blueprint' => array('type' => 'string'),
                         'scope'     => array('type' => 'string'),
                         'scope_key' => array('type' => 'key'),


                     )
        );
        select_blueprint($data, $editor, $smarty, $db);
        break;
    case 'use_template':
        $data = prepare_values(
            $_REQUEST, array(
                         'blueprint_key' => array('type' => 'string'),
                         'scope'         => array('type' => 'string'),
                         'scope_key'     => array('type' => 'key'),


                     )
        );
        use_template($data, $editor, $smarty, $db);
        break;
    case 'clone_sent_mailshot':
        $data = prepare_values(
            $_REQUEST, array(
                         'object'             => array('type' => 'string'),
                         'key'                => array('type' => 'key'),
                         'mailshot_clone_key' => array('type' => 'key'),


                     )
        );
        clone_sent_mailshot($data, $editor, $smarty, $db);
        break;
    case 'select_base_template':
        $data = prepare_values(
            $_REQUEST, array(

                         'template_email_key' => array('type' => 'key'),
                         'base_template_key'  => array('type' => 'key'),


                     )
        );
        select_base_template($data, $editor, $smarty, $db);
        break;
    case 'set_email_template_base':
        $data = prepare_values(
            $_REQUEST, array(
                         'blueprint' => array('type' => 'string'),
                         'key'       => array('type' => 'key'),


                     )
        );
        set_email_template_base($data, $editor, $smarty, $db);
        break;

    case 'create_text_only_email_template':
        $data = prepare_values(
            $_REQUEST, array(
                         'role'      => array('type' => 'string'),
                         'scope'     => array('type' => 'string'),
                         'scope_key' => array('type' => 'key'),


                     )
        );
        create_text_only_email_template($data, $editor, $smarty, $db);
        break;
    case 'set_email_template_as_selecting_blueprints':
        $data = prepare_values(
            $_REQUEST, array(
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        set_email_template_as_selecting_blueprints($data, $editor, $smarty, $db);
        break;
    case 'undo_email_template_as_selecting_blueprints':
        $data = prepare_values(
            $_REQUEST, array(
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        undo_email_template_as_selecting_blueprints($data, $editor, $smarty, $db);
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


function bee_token() {

    include_once 'keyring/dns.php';
    include_once 'keyring/au_deploy_conf.php';

    include_once('external_libs/bee.io/BeeFree.php');
    $beefree = new BeeFree(BEE_IO_ID, BEE_IO_KEY);


    $response = array(
        'state' => 200,
        'token' => $beefree->getCredentials()
    );
    echo json_encode($response);

}


function set_email_template_as_selecting_blueprints($data) {

    $email_template = get_object('Email_Template', $data['email_template_key']);


    $email_template->fast_update(array('Email Template Selecting Blueprints' => 'Yes'));


    if ($email_template->get('Email Template Scope') == 'EmailCampaign') {
        $mailshot = get_object('mailshot', $email_template->get('Email Template Scope Key'));
        $mailshot->fast_update(array('Email Campaign Selecting Blueprints' => 'Yes'));

    }

    $response = array(
        'state'           => 200,
        'update_metadata' => $email_template->get_update_metadata()


    );
    echo json_encode($response);


}

function undo_email_template_as_selecting_blueprints($data) {

    $email_template = get_object('Email_Template', $data['email_template_key']);


    $email_template->fast_update(array('Email Template Selecting Blueprints' => 'No'));


    if ($email_template->get('Email Template Scope') == 'EmailCampaign') {
        $mailshot = get_object('mailshot', $email_template->get('Email Template Scope Key'));
        $mailshot->fast_update(array('Email Campaign Selecting Blueprints' => 'No'));

    }

    $response = array(
        'state'           => 200,
        'update_metadata' => $email_template->get_update_metadata()


    );
    echo json_encode($response);


}

function send_email($data, $editor, $smarty, $db) {


    // print_r($data);

    include_once 'class.Published_Email_Template.php';


    $published_email_template_data['editor'] = $editor;


    $published_email_template_data['Published Email Template Checksum']           = md5($data['json'].'|'.$data['text'].'|'.$data['subject']);
    $published_email_template_data['Published Email Template Text']               = $data['text'];
    $published_email_template_data['Published Email Template Subject']            = $data['subject'];
    $published_email_template_data['Published Email Template Email Template Key'] = '';

    $published_email_template_data['Published Email Template JSON'] = $data['json'];
    $published_email_template_data['Published Email Template HTML'] = $data['html'];


    // print_r($data);

    $published_template = new Published_Email_Template('new', $published_email_template_data);


    $recipient         = get_object($data['recipient'], $data['recipient_key']);
    $recipient->editor = $editor;


    $recipient->send_personalized_invitation($published_template);


    $response = array(
        'state'    => 200,
        'redirect' => strtolower($data['recipient']).'s/'.$recipient->get('Store Key').'/'.$recipient->id
    );
    echo json_encode($response);


}


function send_test_email($data, $smarty) {


    $email_template = get_object('Email_Template', $data['email_template_key']);

    if ($email_template->get('Email Template Subject') == '') {
        $response = array(
            'state' => 400,
            'msg'   => _('Empty email subject')
        );
        echo json_encode($response);
        exit;
    }


    $email_template_type = get_object('EmailCampaignType', $email_template->get('Email Template Email Campaign Type Key'));


    $store   = get_object('Store', $email_template_type->get('Store Key'));
    $website = get_object('Website', $store->get('Store Website Key'));


    $sender_email_address = $store->get('Send Email Address');

    if ($sender_email_address == '') {
        $response = array(
            'state' => 400,
            'msg'   => _('Sender email address not configured')
        );
        echo json_encode($response);
        exit;
    }


    $ses_clients = array();

    $ses_clients[] = SesClient::factory(
        array(
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        )
    );
        /*
    $ses_clients[] = SesClient::factory(
        array(
            'version'     => 'latest',
            'region'      => 'eu-west-1',
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        )
    );
*/

    $placeholders = array(
        '[Greetings]'          => 'Dear John Smith',
        '[Name,Company]'       => 'John Smith, Acme Inc.',
        '[Customer Name]'      => 'Acme Inc.',
        '[Name]'               => 'John Smith',
        '[Customer Note]'      => 'Urgent order, please delivery ASAP',
        '[Invoice Address]'    => '44 Simpson Shores<br/>Marystad<br/>CF23 0EL<br/>UK',
        '[Delivery Address]'   => '6 Emma Locks<br/>Port Oscar<br/>GU1 2PB<br/>UK',
        '[Order Number]'       => '434534',
        '[Order Amount]'       => '£54.00',
        '[Order Date]'         => strftime("%a, %e %b %Y", strtotime('now -11 days')),
        '[Reset_Password_URL]' => 'http://my.website.com/reset/'.md5(date('U')),
        '[Signature]'          => $store->get('Signature'),
    );


    if ($email_template->get('Email Template Role') == 'Order Confirmation') {
        $placeholders['[Pay Info]'] = get_mock_pay_info(($email_template->get('Email Template Scope Key')), $smarty);
        $placeholders['[Order]']    = get_mock_order_info();
    } elseif ($email_template->get('Email Template Role') == 'GR Reminder') {


        $_date = date('Y-m-d', strtotime('now -11 days'));

        $placeholders['[Order Date + n days]']   = strftime("%a, %e %b %Y", strtotime($_date.' +30 days'));
        $placeholders['[Order Date + n weeks]']  = strftime("%a, %e %b %Y", strtotime($_date.' +1 week'));
        $placeholders['[Order Date + n months]'] = strftime("%a, %e %b %Y", strtotime($_date.' +1 month'));


    } elseif ($email_template->get('Email Template Role') == 'Delivery Confirmation') {


        $placeholders['[Tracking Number]'] = substr(md5(date('U')), 16);

        $placeholders['[Tracking URL]'] = 'https://example.com/';


    }


    $from_name = base64_encode($store->get('Name'));
    $_source   = "=?utf-8?B?$from_name?= <$sender_email_address>";


    $subject = strtr($email_template->get('Email Template Subject'), $placeholders);

    $request                               = array();
    $request['Source']                     = $_source;
    $request['Destination']['ToAddresses'] = array($data['email']);
    $request['Message']['Subject']['Data'] = '=?utf-8?B?'.base64_encode($subject).'?=';

    $request['Message']['Body']['Text']['Data'] = strtr($email_template->get('Email Template Text'), $placeholders);


    if ($email_template->get('Email Template Type') == 'HTML') {
        $request['Message']['Body']['Html']['Data'] = strtr($data['html'], $placeholders);
    }


    if ($email_template->get('Email Template Role') == 'GR Reminder') {

        $_date = date('Y-m-d', strtotime('now -11 days'));

        $request['Message']['Body']['Text']['Data'] = preg_replace_callback(
            '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {
            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
        }, $request['Message']['Body']['Text']['Data']
        );
        $request['Message']['Body']['Html']['Data'] = preg_replace_callback(
            '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {
            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
        }, $request['Message']['Body']['Html']['Data']
        );

        $request['Message']['Body']['Text']['Data'] = preg_replace_callback(
            '/\[Order Date \+\s*(\d+)\s*weeks\]/', function ($match_data) use ($_date) {
            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' weeks'));
        }, $request['Message']['Body']['Text']['Data']
        );
        $request['Message']['Body']['Html']['Data'] = preg_replace_callback(
            '/\[Order Date \+\s*(\d+)\s*months\]/', function ($match_data) use ($_date) {
            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' months'));
        }, $request['Message']['Body']['Html']['Data']
        );


    } elseif ($email_template->get('Email Template Role') == 'Delivery Confirmation') {

        if (isset($data['options']) and $data['options'] == 'with_tracking_code') {


            $request['Message']['Body']['Html']['Data'] = preg_replace('/\[Not Tracking START\].*\[END\]/i', '', $request['Message']['Body']['Html']['Data']);

            if (preg_match('/\[Tracking START\](.*)\[END\]/', $request['Message']['Body']['Html']['Data'], $matches)) {
                $request['Message']['Body']['Html']['Data'] = preg_replace('/\[Tracking START\].*\[END\]/', $matches[1], $request['Message']['Body']['Html']['Data']);

            }

        } else {
            $request['Message']['Body']['Html']['Data'] = preg_replace('/\[Tracking START\].*\[END\]/i', '', $request['Message']['Body']['Html']['Data']);

            if (preg_match('/\[Not Tracking START\](.*)\[END\]/', $request['Message']['Body']['Html']['Data'], $matches)) {
                $request['Message']['Body']['Html']['Data'] = preg_replace('/\[Not Tracking START\].*\[END\]/', $matches[1], $request['Message']['Body']['Html']['Data']);

            }
        }

    }

    $request['Message']['Body']['Html']['Data'] = preg_replace_callback('/\[Unsubscribe]/', function () use ($website, $smarty) {
        $smarty->assign('link', $website->get('Website URL').'/unsubscribe.php');
        return $smarty->fetch('unsubscribe_marketing_email.placeholder.tpl');
    }, $request['Message']['Body']['Html']['Data']
    );

    $request['Message']['Body']['Html']['Data'] = preg_replace_callback('/\[Stop_Junk_Mail]/', function () use ($website, $smarty) {
        $smarty->assign('link', $website->get('Website URL').'/unsubscribe.php');
        return $smarty->fetch('stop_junk_email.placeholder.tpl');
    }, $request['Message']['Body']['Html']['Data']
    );


    try {


        $ses_client = $ses_clients[0];

        $result    = $ses_client->sendEmail($request);
        $messageId = $result->get('MessageId');
        $response  = array(
            'state'           => 200,
            'update_metadata' => $email_template->get_update_metadata()


        );


    } catch (Exception $e) {
        // echo("The email was not sent. Error message: ");
        // echo($e->getMessage()."\n");
        $response = array(
            'state' => 400,
            'msg'   => "Error, email not send",
            'code'  => $e->getMessage()


        );
    }

    echo json_encode($response);


}


function set_email_template_type($data, $editor, $smarty, $db) {


    $email_template         = get_object('Email_Template', $data['email_template_key']);
    $email_template->editor = $editor;

    $valid_values = array(
        'HTML',
        'Text'
    );

    if (!in_array($data['value'], $valid_values)) {
        $response = array(
            'state' => 400,
            'msg'   => 'wrong value'
        );
        echo json_encode($response);
        exit;
    }

    $checksum = md5(
        ($email_template->get('Email Template Type') == $data['value'] ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject')
    );


    $email_template->update(
        array(
            'Email Template Type'             => $data['value'],
            'Email Template Editing Checksum' => $checksum,
        ), 'no_history'
    );

    $smarty->assign('data', $email_template->get('Published Info'));

    $response = array(
        'state'               => 200,
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
        'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false),
        'has_html_json'       => ($email_template->get('Email Template Editing JSON') == '' ? false : true)

    );


    echo json_encode($response);


}

function save_email_template_text_part($data, $editor, $smarty, $db) {

    $email_template         = get_object('Email_Template', $data['email_template_key']);
    $email_template->editor = $editor;


    $email_template->fast_update(
        array(
            'Email Template Subject'        => $data['subject'],
            'Email Template Text'           => $data['text'],
            'Email Template Last Edited'    => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By' => $editor['Author Key']
        )
    );

    $operations = array('delete_operations');

    if ($email_template->get('Email Template Published Email Key')) {
        $email_template->publish();

        $checksum = md5(($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject'));
        $email_template->fast_update(array('Email Template Editing Checksum' => $checksum));
    }


    $response = array(
        'state'           => 200,
        'update_metadata' => array(
            'operations' => $operations
        )

    );


    echo json_encode($response);


}


function auto_save_email_template($data, $editor, $smarty, $db) {


    $email_template         = get_object('Email_Template', $data['email_template_key']);
    $email_template->editor = $editor;


    $email_template->fast_update(
        array(
            'Email Template Editing JSON'   => $data['json'],
            'Email Template HTML'           => $data['html'],
            'Email Template Subject'        => $data['subject'],
            'Email Template Text'           => $data['text'],
            'Email Template Last Edited'    => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By' => $editor['Author Key']
        )
    );


    $checksum = md5(($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject'));
    $email_template->fast_update(array('Email Template Editing Checksum' => $checksum));


    $smarty->assign('data', $email_template->get('Published Info'));

    $response = array(
        'state'               => 200,
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
        'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


    );


    echo json_encode($response);


}


function publish_email_template($data, $editor, $smarty, $db) {


    $email_template         = get_object('Email_Template', $data['email_template_key']);
    $email_template->editor = $editor;


    $email_template->fast_update(
        array(
            'Email Template Editing JSON'   => $data['json'],
            'Email Template HTML'           => $data['html'],
            'Email Template Subject'        => $data['subject'],
            'Email Template Text'           => $data['text'],
            'Email Template Last Edited'    => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By' => $editor['Author Key']
        )
    );


    $checksum = md5(($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject'));
    $email_template->fast_update(array('Email Template Editing Checksum' => $checksum));


    $publish_email_template = $email_template->publish();

    if ($publish_email_template->id) {
        if ($email_template->get('Email Template Scope') == 'Mailshot' or $email_template->get('Email Template Scope') == 'EmailCampaign') {

            $mailshot         = get_object('Mailshot', $email_template->get('Email Template Scope Key'));
            $mailshot->editor = $editor;

            if ($mailshot->get('State Index') <= 20) {
                $mailshot->update_state('Ready');
            }


            $smarty->assign('data', $email_template->get('Published Info'));

            $response = array(
                'state'               => 200,
                'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
                'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false),
                'update_metadata'     => $mailshot->get_update_metadata()

            );


            echo json_encode($response);


        } elseif ($email_template->get('Email Template Scope') == 'EmailCampaignType') {

            $email_template_type         = get_object('EmailCampaignType', $email_template->get('Email Template Scope Key'));
            $email_template_type->editor = $editor;


            // print_r($email_template_type);


            if ($email_template_type->get('Email Campaign Type Status') == 'InProcess') {
                $email_template_type->update_status();
            }
            $smarty->assign('data', $email_template->get('Published Info'));


            //operations


            $response = array(
                'state'               => 200,
                'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
                'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false),
                'update_metadata'     => $email_template_type->get_update_metadata()

            );


            echo json_encode($response);


        } else {

            $smarty->assign('data', $email_template->get('Published Info'));

            $response = array(
                'state'               => 200,
                'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
                'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


            );


            echo json_encode($response);


        }

    }


}


function save_blueprint($data, $editor, $smarty, $db) {


    $email_template         = get_object('Email_Template', $data['email_template_key']);
    $email_template->editor = $editor;

    $blueprint_data = array(
        'Email Blueprint JSON' => $data['json'],
        'Email Blueprint HTML' => $data['html'],
        'Email Blueprint Name' => $data['name'],

    );

    $blueprint = $email_template->create_blueprint($blueprint_data);

    if ($blueprint->id) {
        $response = array(
            'state'         => 200,
            'blueprint_key' => $blueprint->id


        );

        echo json_encode($response);
    }


}


function select_blueprint($data, $editor, $db) {

    include_once 'class.Email_Template.php';
    include_once 'class.Email_Blueprint.php';


    if (is_numeric($data['blueprint'])) {

        $blueprint = new Email_Blueprint($data['blueprint']);


        if (!$blueprint->id) {
            $response = array(
                'state' => 400,
                'msg'   => _('Template not found')


            );

            echo json_encode($response);
            exit;
        }

        $blueprint_json = $blueprint->get('Email Blueprint JSON');


    } else {


        $blueprint = preg_replace('/(a-z0-9\_)/', '', $data['blueprint']);

        $filename = 'conf/etemplates/'.$blueprint.'.json';
        if (!file_exists($filename)) {
            $response = array(
                'state' => 400,
                'resp'  => 'Error no blueprint'
            );
            echo json_encode($response);
            exit;

        }


        $blueprint_json = file_get_contents($filename);
    }

    include 'conf/email_templates_data.php';

    $key = $email_templates_data[$data['role']]['key'];


    $scope = get_object($data['scope'], $data['scope_key']);


    //  print $scope->get_object_name();

    // $update_metadata = array();


    if ($data['scope'] == 'Mailshot') {


        $email_template = get_object('Email_Template', $scope->get('Email Campaign Email Template Key'));


        if ($email_template->id) {

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->fast_update(
                array(
                    'Email Template Selecting Blueprints' => 'No',
                    'Email Template Editing Checksum'     => $checksum,
                )
            );


        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'                    => $name,
                'Email Template Role Type'               => 'Marketing',
                'Email Template Role'                    => $data['role'],
                'Email Template Scope'                   => $data['scope'],
                'Email Template Scope Key'               => $data['scope_key'],
                'Email Template Text'                    => $text,
                'Email Template Subject'                 => $subject,
                'Email Template Email Campaign Type Key' => $scope->get('Email Campaign Email Template Type Key'),

                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');


            $scope->fast_update(
                array(
                    'Email Campaign Email Template Key'         => $email_template->id,
                    'Email Campaign Publish Email Template Key' => 0

                )
            );


        }


    } elseif ($scope->get_object_name() == 'Email Campaign Type') {


        if ($scope->get('Email Campaign Type Email Template Key')) {
            $email_template         = get_object('Email_Template', $scope->get('Email Campaign Type Email Template Key'));
            $email_template->editor = $editor;

            // print_r($email_template);

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON'            => $blueprint_json,
                    'Email Template Email Campaign Type Key' => $scope->id,
                    'Email Template Type'                    => 'HTML',
                    'Email Template Last Edited'             => gmdate('Y-m-d H:i:s'),
                    'Email Template Last Edited By'          => $editor['User Key']
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->fast_update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                )
            );

        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'                    => $name,
                'Email Template Email Campaign Type Key' => $scope->id,
                'Email Template Role Type'               => 'Transactional',
                'Email Template Role'                    => $data['role'],
                'Email Template Scope'                   => $data['scope'],
                'Email Template Scope Key'               => $data['scope_key'],
                'Email Template Text'                    => $text,
                'Email Template Subject'                 => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');

            $scope->fast_update(
                array(
                    'Email Campaign Type Email Template Key' => $email_template->id,

                )
            );

            $scope->update_status('');


        }


    } elseif ($scope->get_object_name() == 'Email Template') {


        if ($scope->id) {
            $email_template = $scope;
            $email_template->update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                ), 'no_history'
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );

        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'      => $name,
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => $data['role'],
                'Email Template Scope'     => $data['scope'],
                'Email Template Scope Key' => $data['scope_key'],
                'Email Template Text'      => $text,
                'Email Template Subject'   => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            new Email_Template('find', $email_template_data, 'create');


        }


    } else {


        if ($metadata['emails'][$key]['key'] > 0) {
            $email_template = new Email_Template($metadata['emails'][$key]['key']);
            $email_template->update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                ), 'no_history'
            );

            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );

        } else {


            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'      => $name,
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => $data['role'],
                'Email Template Scope'     => $data['scope'],
                'Email Template Scope Key' => $data['scope_key'],
                'Email Template Text'      => $text,
                'Email Template Subject'   => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');
        }

        $metadata['emails'][$key]['key'] = $email_template->id;

        $scope->update(array('Scope Metadata' => json_encode($metadata)), 'no_history');


    }


    //$metadata = $scope->get('Scope Metadata');


    $response = array(
        'state'           => 200,
        'update_metadata' => $scope->get_update_metadata()

    );

    echo json_encode($response);
    exit;

}


function select_base_template($data, $editor, $db) {


    $email_template         = get_object('Email_Template', $data['template_email_key']);
    $email_template->editor = $editor;
    $email_template_base    = get_object('Email_Template', $data['base_template_key']);


    $email_template->fast_update(
        array(
            'Email Template Editing JSON'       => $email_template_base->get('Email Template Editing JSON'),
            'Email Template Published Checksum' => $email_template_base->get('Email Template Published Checksum'),
            'Email Template Last Edited'        => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By'     => $email_template_base->get('Email Template Last Edited By'),

            'Email Template Subject'          => $email_template_base->get('Email Template Subject'),
            'Email Template Type'             => $email_template_base->get('Email Template Type'),
            'Email Template Editing Checksum' => $email_template_base->get('Email Template Editing Checksum'),

        )
    );


    $response = array(
        'state' => 200,


    );

    echo json_encode($response);

}


function set_email_template_base($data, $editor, $db) {


    $blueprint = preg_replace('/(a-z0-9\_)/', '', $data['blueprint']);

    $filename = 'conf/etemplates/'.$blueprint.'.json';
    if (!file_exists($filename)) {
        $response = array(
            'state' => 400,
            'resp'  => 'Error no blueprint'
        );
        echo json_encode($response);
        exit;

    }


    $blueprint_json = file_get_contents($filename);


    $email_template = get_object('Email_Template', $data['key']);
    $email_template->update(
        array(
            'Email Template Editing JSON' => $blueprint_json,
            'Email Template Type'         => 'HTML'
        ), 'no_history'
    );
    $checksum = md5(
        ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
            'Email Template Subject'
        )
    );


    $email_template->update(
        array(
            'Email Template Editing Checksum' => $checksum,
        ), 'no_history'
    );


    $response = array(
        'state' => 200,


    );

    echo json_encode($response);

}

function create_text_only_email_template($data, $editor, $db) {

    include_once 'class.Email_Template.php';
    include 'conf/email_templates_data.php';

    $key = $email_templates_data[$data['role']]['key'];


    $scope = get_object($data['scope'], $data['scope_key']);


    $metadata = $scope->get('Scope Metadata');


    if ($metadata['emails'][$key]['key'] > 0) {
        $email_template = new Email_Template($metadata['emails'][$key]['key']);
        $email_template->update(array('Email Template Type' => 'Text'), 'no_history');


        $checksum = md5(
            ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                'Email Template Subject'
            )
        );


        $email_template->update(
            array(
                'Email Template Editing Checksum' => $checksum,
            ), 'no_history'
        );


    } else {


        $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
        $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
        $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


        $email_template_data = array(
            'Email Template Name'         => $name,
            'Email Template Type'         => 'Text',
            'Email Template Role Type'    => 'Transactional',
            'Email Template Role'         => $data['role'],
            'Email Template Scope'        => $data['scope'],
            'Email Template Scope Key'    => $data['scope_key'],
            'Email Template Text'         => $text,
            'Email Template Subject'      => $subject,
            'Email Template Editing JSON' => ''
        );


        $email_template = new Email_Template('find', $email_template_data, 'create');
    }
    $metadata['emails'][$key]['key'] = $email_template->id;

    $scope->update(array('Scope Metadata' => json_encode($metadata)), 'no_history');


    $response = array(
        'state' => 200,


    );

    echo json_encode($response);

}

function get_mock_order_info() {


    $order_items_info = array(

        array(
            'code_plain'  => 'ABB1',
            'description' => 'Doe Spanner	',
            'quantity'    => '2',
            'to_charge'   => '£5.00',

        ),
        array(
            'code_plain'  => 'HHT-04',
            'description' => 'Moe Screwdriver',
            'quantity'    => '3',
            'to_charge'   => '£25.00',

        ),
        array(
            'code_plain'  => 'LLX-10a	',
            'description' => 'DDooley Hammer',
            'quantity'    => '1',
            'to_charge'   => '£20.00',

        ),


    );


    $order_info = '<table  cellpadding="0">';
    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	<td style="padding:5px;border-bottom:1px solid #777777">%s</td>
	</tr>', _('Code'), _('Description'), _('Quantity'), _('Amount')

    );

    foreach ($order_items_info as $data) {
        $order_info .= sprintf(
            '<tr style="border-bottom:1px solid #cccccc">
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', $data['code_plain'], $data['description'], $data['quantity'], $data['to_charge']

        );
    }


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Items Net'), '£50.00'


    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Credits'), '-£20'

    );


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Charges'), '£5.00'

    );


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Shipping'), '£10.00'

    );


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Net'), '£45.00'

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Tax'), '£9.00'

    );

    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Total'), '£54.00'

    );


    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('Paid'), '£54.00'

    );
    $order_info .= sprintf(
        '<tr style="border-bottom:1px solid #cccccc">
		<td colspan=2></td>
		<td style="text-align:right;padding:5px;vertical-align:top;border-bottom:1px solid #cccccc">%s</td>
		<td style="padding:5px;vertical-align:top;text-align:right;border-bottom:1px solid #cccccc">%s</td></tr>', _('To Pay Amount'), '£0.00'

    );


    $order_info .= '</table>';


    return $order_info;


}


function get_mock_pay_info($webpage_key, $smarty) {


    $webpage = get_object('Webpage', $webpage_key);
    $website = get_object('Website', $webpage->get('Webpage Website Key'));


    $bank_payment_account = get_object('Payment_Account', 'Bank', 'Block');


    $content = $webpage->get('Content Data');

    $placeholders = array(

        '[Order Number]' => '434534',
        '[Order Amount]' => '£54.00',

    );


    if (isset($content['_bank_header'])) {
        $content['_bank_header'] = strtr($content['_bank_header'], $placeholders);
    }
    if (isset($content['_bank_footer'])) {
        $content['_bank_footer'] = strtr($content['_bank_footer'], $placeholders);
    }


    $smarty->assign('content', $content);
    $smarty->assign('labels', $website->get('Localised Labels'));
    $smarty->assign('bank_payment_account', $bank_payment_account);


    return $smarty->fetch('payment_bank_details.inc.tpl');


}


function clone_sent_mailshot($data, $editor, $db) {


    if (!in_array($data['object'], array('Mailshot'))) {
        $response = array(
            'state' => 400,
            'msg'   => 'Invalid object'


        );

        echo json_encode($response);
        exit;
    }

    $scope = get_object($data['object'], $data['key']);

    $mailshot_to_be_cloned = get_object('Mailshot', $data['mailshot_clone_key']);


    if (!$scope->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Mailshot not found'


        );

        echo json_encode($response);
        exit;
    }

    if (!$mailshot_to_be_cloned->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Mailshot to be cloned not found'


        );

        echo json_encode($response);
        exit;
    }


    $email_template_to_be_cloned           = get_object('Email_Template', $mailshot_to_be_cloned->get('Email Campaign Email Template Key'));
    $email_published_template_to_be_cloned = get_object('published_email_template', $email_template_to_be_cloned->get('Email Template Published Email Key'));


    $email_json_to_be_cloned = $email_published_template_to_be_cloned->get('Published Email Template JSON');


    if ($data['object'] == 'Mailshot') {


        $email_template = get_object('Email_Template', $scope->get('Email Campaign Email Template Key'));


        if ($email_template->id) {

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON' => $email_json_to_be_cloned,
                    'Email Template Type'         => 'HTML'
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject')
            );


            $email_template->fast_update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                )
            );


        } else {

            $email_campaign_type = get_object('email_campaign_type', $scope->get('Email Campaign Email Template Type Key'));


            $email_template_data = array(
                'Email Template Name'                    => $email_campaign_type->get('Email Campaign Type Code'),
                'Email Template Role Type'               => 'Marketing',
                'Email Template Role'                    => $email_campaign_type->get('Email Campaign Type Code'),
                'Email Template Scope'                   => 'EmailCampaign',
                'Email Template Scope Key'               => $scope->id,
                'Email Template Text'                    => $email_published_template_to_be_cloned->get('Published Email Template Text'),
                'Email Template Subject'                 => $email_published_template_to_be_cloned->get('Published Email Template Subject'),
                'Email Template Email Campaign Type Key' => $scope->get('Email Campaign Email Template Type Key'),
                'Email Template Editing JSON'            => $email_json_to_be_cloned
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');


            $scope->fast_update(
                array(
                    'Email Campaign Email Template Key'         => $email_template->id,
                    'Email Campaign Publish Email Template Key' => 0

                )
            );


        }

        $email_template->fast_update(array('Email Template Selecting Blueprints' => 'No'));


        $scope->fast_update(array('Email Campaign Selecting Blueprints' => 'No'));


    } elseif ($scope->get_object_name() == 'Email Campaign Type') {

        exit('todo');


        if ($scope->get('Email Campaign Type Email Template Key')) {
            $email_template         = get_object('Email_Template', $scope->get('Email Campaign Type Email Template Key'));
            $email_template->editor = $editor;

            // print_r($email_template);

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON'            => $blueprint_json,
                    'Email Template Email Campaign Type Key' => $scope->id,
                    'Email Template Type'                    => 'HTML',
                    'Email Template Last Edited'             => gmdate('Y-m-d H:i:s'),
                    'Email Template Last Edited By'          => $editor['User Key']
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->fast_update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                )
            );

        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'                    => $name,
                'Email Template Email Campaign Type Key' => $scope->id,
                'Email Template Role Type'               => 'Transactional',
                'Email Template Role'                    => $data['role'],
                'Email Template Scope'                   => $data['scope'],
                'Email Template Scope Key'               => $data['scope_key'],
                'Email Template Text'                    => $text,
                'Email Template Subject'                 => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');

            $scope->fast_update(
                array(
                    'Email Campaign Type Email Template Key' => $email_template->id,

                )
            );

            $scope->update_status('');


        }


    } elseif ($scope->get_object_name() == 'Email Template') {


        if ($scope->id) {
            $email_template = $scope;
            $email_template->update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                ), 'no_history'
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );

        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'      => $name,
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => $data['role'],
                'Email Template Scope'     => $data['scope'],
                'Email Template Scope Key' => $data['scope_key'],
                'Email Template Text'      => $text,
                'Email Template Subject'   => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            new Email_Template('find', $email_template_data, 'create');


        }


    } else {


        if ($metadata['emails'][$key]['key'] > 0) {
            $email_template = new Email_Template($metadata['emails'][$key]['key']);
            $email_template->update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                ), 'no_history'
            );

            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );

        } else {


            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'      => $name,
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => $data['role'],
                'Email Template Scope'     => $data['scope'],
                'Email Template Scope Key' => $data['scope_key'],
                'Email Template Text'      => $text,
                'Email Template Subject'   => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');
        }

        $metadata['emails'][$key]['key'] = $email_template->id;

        $scope->update(array('Scope Metadata' => json_encode($metadata)), 'no_history');


    }


    //$metadata = $scope->get('Scope Metadata');


    $response = array(
        'state'           => 200,
        'update_metadata' => $scope->get_update_metadata()

    );

    echo json_encode($response);
    exit;

}


function use_template($data, $editor, $db) {

    include_once 'class.Email_Template.php';
    include_once 'class.Email_Blueprint.php';


    $blueprint = get_object('Email_Blueprint', $data['blueprint_key']);


    if (!$blueprint->id) {
        $response = array(
            'state' => 400,
            'msg'   => _('Template not found')


        );

        echo json_encode($response);
        exit;
    }

    $blueprint_json = $blueprint->get('Email Blueprint JSON');


    $scope = get_object($data['scope'], $data['scope_key']);


    //  print $scope->get_object_name();

    $update_metadata = array();

    if ($data['scope'] == 'Mailshot') {


        $email_template = get_object('Email_Template', $scope->get('Email Campaign Email Template Key'));


        if ($email_template->id) {

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->fast_update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                )
            );


        } else {


            $text    = '';
            $subject = $blueprint->get('Name');
            $name    = $blueprint->get('Role');


            $email_template_data = array(
                'Email Template Name'                    => $name,
                'Email Template Role Type'               => 'Marketing',
                'Email Template Role'                    => $blueprint->get('Role'),
                'Email Template Scope'                   => $data['scope'],
                'Email Template Scope Key'               => $data['scope_key'],
                'Email Template Text'                    => $text,
                'Email Template Subject'                 => $subject,
                'Email Template Email Campaign Type Key' => $scope->get('Email Campaign Email Template Type Key'),

                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');


            $scope->fast_update(
                array(
                    'Email Campaign Email Template Key'         => $email_template->id,
                    'Email Campaign Publish Email Template Key' => 0

                )
            );


        }


        $email_template->fast_update(array('Email Template Selecting Blueprints' => 'No'));


        $scope->fast_update(array('Email Campaign Selecting Blueprints' => 'No'));


    } elseif ($scope->get_object_name() == 'Email Campaign Type') {


        if ($scope->get('Email Campaign Type Email Template Key')) {
            $email_template         = get_object('Email_Template', $scope->get('Email Campaign Type Email Template Key'));
            $email_template->editor = $editor;

            // print_r($email_template);

            $email_template->fast_update(
                array(
                    'Email Template Editing JSON'            => $blueprint_json,
                    'Email Template Email Campaign Type Key' => $scope->id,
                    'Email Template Type'                    => 'HTML',
                    'Email Template Last Edited'             => gmdate('Y-m-d H:i:s'),
                    'Email Template Last Edited By'          => $editor['User Key']
                )
            );
            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->fast_update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                )
            );

        } else {

            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'                    => $name,
                'Email Template Email Campaign Type Key' => $scope->id,
                'Email Template Role Type'               => 'Transactional',
                'Email Template Role'                    => $data['role'],
                'Email Template Scope'                   => $data['scope'],
                'Email Template Scope Key'               => $data['scope_key'],
                'Email Template Text'                    => $text,
                'Email Template Subject'                 => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');

            $scope->fast_update(
                array(
                    'Email Campaign Type Email Template Key' => $email_template->id,

                )
            );

            $scope->update_status('');


        }


    } elseif ($scope->get_object_name() == 'Email Template') {
        /*

                if ($scope->id) {
                    $email_template = $scope;
                    $email_template->update(
                        array(
                            'Email Template Editing JSON' => $blueprint_json,
                            'Email Template Type'         => 'HTML'
                        ), 'no_history'
                    );
                    $checksum = md5(
                        ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                            'Email Template Subject'
                        )
                    );


                    $email_template->update(
                        array(
                            'Email Template Editing Checksum' => $checksum,
                        ), 'no_history'
                    );

                } else {

                    $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
                    $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
                    $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


                    $email_template_data = array(
                        'Email Template Name'      => $name,
                        'Email Template Role Type' => 'Transactional',
                        'Email Template Role'      => $data['role'],
                        'Email Template Scope'     => $data['scope'],
                        'Email Template Scope Key' => $data['scope_key'],
                        'Email Template Text'      => $text,
                        'Email Template Subject'   => $subject,


                        'Email Template Editing JSON' => $blueprint_json
                    );


                    new Email_Template('find', $email_template_data, 'create');


                }

        */
    } else {


        if ($metadata['emails'][$key]['key'] > 0) {
            $email_template = new Email_Template($metadata['emails'][$key]['key']);
            $email_template->update(
                array(
                    'Email Template Editing JSON' => $blueprint_json,
                    'Email Template Type'         => 'HTML'
                ), 'no_history'
            );

            $checksum = md5(
                ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$email_template->get(
                    'Email Template Subject'
                )
            );


            $email_template->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );

        } else {


            $text    = (isset($email_templates_data[$data['role']]['text']) ? $email_templates_data[$data['role']]['text'] : '');
            $subject = (isset($email_templates_data[$data['role']]['subject']) ? $email_templates_data[$data['role']]['subject'] : $data['role']);
            $name    = (isset($email_templates_data[$data['role']]['name']) ? $email_templates_data[$data['role']]['name'] : $data['role']);


            $email_template_data = array(
                'Email Template Name'      => $name,
                'Email Template Role Type' => 'Transactional',
                'Email Template Role'      => $data['role'],
                'Email Template Scope'     => $data['scope'],
                'Email Template Scope Key' => $data['scope_key'],
                'Email Template Text'      => $text,
                'Email Template Subject'   => $subject,


                'Email Template Editing JSON' => $blueprint_json
            );


            $email_template = new Email_Template('find', $email_template_data, 'create');
        }

        $metadata['emails'][$key]['key'] = $email_template->id;

        $scope->update(array('Scope Metadata' => json_encode($metadata)), 'no_history');


    }


    //$metadata = $scope->get('Scope Metadata');


    $response = array(
        'state'           => 200,
        'update_metadata' => $scope->get_update_metadata()

    );

    echo json_encode($response);
    exit;

}


?>
