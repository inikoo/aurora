<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 19:58:53 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;


require_once 'common.php';
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


                     )
        );
        send_test_email($data, $editor, $smarty, $db);
        break;
    case 'save_email_template_editing_json':

        $data = prepare_values(
            $_REQUEST, array(
                         'json'               => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        save_email_template_editing_json($data, $editor, $smarty, $db);
        break;
    case 'save_email_template_text':

        $data = prepare_values(
            $_REQUEST, array(
                         'text'               => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        save_email_template_text($data, $editor, $smarty, $db);
        break;
    case 'save_email_template_subject':

        $data = prepare_values(
            $_REQUEST, array(
                         'subject'            => array('type' => 'string'),
                         'email_template_key' => array('type' => 'key'),
                     )
        );
        save_email_template_subject($data, $editor, $smarty, $db);
        break;

    case 'publish_email_template':
        $data = prepare_values(
            $_REQUEST, array(
                         'json'               => array('type' => 'string'),
                         'html'               => array('type' => 'html'),
                         'email_template_key' => array('type' => 'key'),


                     )
        );
        publish_email_template($data, $editor, $smarty, $db);
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

    include_once('keyring/dns.php');
    include_once('external_libs/bee.io/BeeFree.php');
    $beefree = new BeeFree(BEE_IO_ID, BEE_IO_KEY);

    $response = array(
        'state' => 200,
        'token' => $beefree->getCredentials()
    );
    echo json_encode($response);

}


function send_test_email($data, $editor, $smarty, $db) {


    require 'external_libs/aws.phar';

    include_once 'class.Email_Template.php';

    $email_template = new Email_Template($data['email_template_key']);

    if ($email_template->get('Email Template Subject') == '') {
        $response = array(
            'state' => 400,
            'msg'   => _('Empty email subject')
        );
        echo json_encode($response);
        exit;
    }


    $scope_object = get_object($email_template->get('Email Template Scope'), $email_template->get('Email Template Scope Key'));

    $sender_email_address = $scope_object->get('Send Email Address');

    if ($sender_email_address == '') {
        $response = array(
            'state' => 400,
            'msg'   => _('Sender email address not configured')
        );
        echo json_encode($response);
        exit;
    }


    $client = SesClient::factory(
        array(
            'version'     => 'latest',
            'region'      => 'us-east-1',
            'credentials' => [
                'key'    => AWS_ACCESS_KEY_ID,
                'secret' => AWS_SECRET_ACCESS_KEY,
            ],
        )
    );



    $placeholders = array(
        '[Greetings]'=>'John Smith',
        '[Reset_Password_URL]'=>'http://my.website.com/reset/'.md5(date()),
        '[Signature]'=>$scope_object->get('Signature'),
    );


    $request                                    = array();
    $request['Source']                          = $sender_email_address;
    $request['Destination']['ToAddresses']      = array($data['email']);
    $request['Message']['Subject']['Data']      = $email_template->get('Email Template Subject');
    $request['Message']['Body']['Text']['Data'] = strtr($email_template->get('Email Template Text'), $placeholders);


    if ($email_template->get('Email Template Type') == 'HTML') {
        $request['Message']['Body']['Html']['Data'] = $data['html'];
    }


    //print_r($request);
    //exit;

    try {
        $result    = $client->sendEmail($request);
        $messageId = $result->get('MessageId');
        $response  = array(
            'state' => 200


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

function save_email_template_text($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


    if ($data['text'] != $email_template->get('Email Template Text')) {


        $checksum = md5(
            ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$data['text'].'|'.$email_template->get('Email Template Subject')
        );


        $update_data = array(
            'Email Template Text'             => $data['text'],
            'Email Template Last Text Edited' => gmdate('Y-m-d H:i:s'),
            'Email Template Editing Checksum' => $checksum,
            'Email Template Last Edited'      => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By'   => $editor['Author Key']

        );


        $email_template->update($update_data, 'no_history');
    }


    $smarty->assign('data', $email_template->get('Published Info'));

    $response = array(
        'state'               => 200,
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
        'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


    );
    echo json_encode($response);


}


function save_email_template_subject($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


    if ($data['subject'] != $email_template->get('Email Template Subject')) {


        $checksum = md5(
            ($email_template->get('Email Template Type') == 'Text' ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'.$data['subject']
        );


        $update_data = array(
            'Email Template Subject'          => $data['subject'],
            'Email Template Last Edited'      => gmdate('Y-m-d H:i:s'),
            'Email Template Editing Checksum' => $checksum,
            'Email Template Last Edited By'   => $editor['Author Key']

        );


        $email_template->update($update_data, 'no_history');
    }


    $smarty->assign('data', $email_template->get('Published Info'));

    $response = array(
        'state'               => 200,
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
        'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


    );
    echo json_encode($response);


}

function set_email_template_type($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
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
        ($email_template->get('Email Template Type') == $data['value'] ? '' : $email_template->get('Email Template Editing JSON')).'|'.$email_template->get('Email Template Text').'|'
        .$email_template->get('Email Template Subject')
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


function save_email_template_editing_json($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


    if ($data['json'] != $email_template->get('Email Template Editing JSON')) {

        $checksum = md5($data['json'].'|'.$email_template->get('Email Template Text').'|'.$email_template->get('Email Template Subject'));


        $update_data = array(
            'Email Template Editing JSON'     => $data['json'],
            'Email Template Editing Checksum' => $checksum,
            'Email Template Last Edited'      => gmdate('Y-m-d H:i:s'),
            'Email Template Last Edited By'   => $editor['Author Key']

        );


        $email_template->update($update_data, 'no_history');
    }


    $smarty->assign('data', $email_template->get('Published Info'));

    $response = array(
        'state'               => 200,
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
        'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


    );
    echo json_encode($response);

}


function publish_email_template($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;

    $publish_email_template_data = array(
        'Published Email Template JSON' => $data['json'],
        'Published Email Template HTML' => $data['html'],

    );

    $publish_email_template = $email_template->publish($publish_email_template_data);

    if ($publish_email_template->id) {


        $smarty->assign('data', $email_template->get('Published Info'));

        $response = array(
            'state'               => 200,
            'email_template_info' => $smarty->fetch('email_template.control.info.tpl'),
            'published'           => ($email_template->get('Email Template Editing Checksum') == $email_template->get('Email Template Published Checksum') ? true : false)


        );


        echo json_encode($response);
    }


}


function save_blueprint($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;

    $blueprint_data = array(
        'Email Blueprint JSON' => $data['json'],
        'Email Blueprint HTML' => $data['html'],
        'Email Blueprint Name' => $data['name'],

    );

    $blueprint = $email_template->create_blueprint($blueprint_data);

    if ($blueprint->id) {
        $response = array(
            'state' => 200,


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


    $metadata = $scope->get('Scope Metadata');


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


?>
