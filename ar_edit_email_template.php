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

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


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

    $request                                    = array();
    $request['Source']                          = $email_template->get('Email Template Sender');
    $request['Destination']['ToAddresses']      = array($data['email']);
    $request['Message']['Subject']['Data']      = $email_template->get('Email Template Subject');
    $request['Message']['Body']['Html']['Data'] =$data['html'];
    $request['Message']['Body']['Text']['Data'] =$email_template->get('Email Template Text');



    try {
        $result    = $client->sendEmail($request);
        $messageId = $result->get('MessageId');
        $response = array(
            'state' => 200


        );


    } catch (Exception $e) {
       // echo("The email was not sent. Error message: ");
       // echo($e->getMessage()."\n");
        $response = array(
            'state' => 400,
            'msg'=>"Error, email not send"


        );
    }



    echo json_encode($response);


}

function save_email_template_text($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


    if ($data['text'] != $email_template->get('Email Template Text')) {


        $checksum = md5($email_template->get('Email Template Editing JSON').'|'.$data['text'].'|'.$email_template->get('Email Template Subject'));


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
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl')


    );
    echo json_encode($response);


}


function save_email_template_subject($data, $editor, $smarty, $db) {


    include_once 'class.Email_Template.php';

    $email_template         = new Email_Template($data['email_template_key']);
    $email_template->editor = $editor;


    if ($data['subject'] != $email_template->get('Email Template Subject')) {

        $checksum = md5($email_template->get('Email Template Editing JSON').'|'.$email_template->get('Email Template Text').'|'.$data['subject']);


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
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl')


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
        'email_template_info' => $smarty->fetch('email_template.control.info.tpl')


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
            'email_template_info' => $smarty->fetch('email_template.control.info.tpl')


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

    switch ($data['role']) {
        case 'Welcome':
            include_once 'class.Page.php';
            $webpage  = new Page($data['scope_key']);
            $metadata = $webpage->get('Scope Metadata');


            // print_r($metadata);


            if ($metadata['welcome_email']['key'] > 0) {

                $email_template = new Email_Template($metadata['welcome_email']['key']);


                $email_template_data = array(
                    'Email Template JSON' => $blueprint_json
                );

                $email_template->update($email_template_data, 'no_history');


            } else {


                $email_template_data = array(

                    'Email Template Name'      => _('Welcome'),
                    'Email Template Role Type' => 'Transactional',
                    'Email Template Role'      => 'Welcome',
                    'Email Template Scope'     => $data['scope'],
                    'Email Template Scope Key' => $data['scope_key'],
                    'Email Template JSON'      => $blueprint_json
                );

                //  print_r($email_template_data);

                $email_template = new Email_Template('find', $email_template_data, 'create');


            }
            $metadata['welcome_email']['key'] = $email_template->id;


            $webpage->update(
                array(
                    'Webpage Scope Metadata' => json_encode($metadata)
                ), 'no_history'
            );
            break;
        default:
            break;
    }

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish')


    );

    echo json_encode($response);

}


?>
