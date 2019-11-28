<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 21:49:55 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

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


    case 'template_data':
        $data = prepare_values(
            $_REQUEST, array(
                         'field' => array('type' => 'string'),
                         'key'   => array('type' => 'key')
                     )
        );
        template_data($data);
        break;

    case 'email_text':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        email_text($data,$db);
        break;
    case 'template_text':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        template_text($data);
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

/**
 * @param $data
 * @param $db \PDO
 */
function email_text($data, $db) {

    $text = '';

    $sql = sprintf('select `Email Tracking Email Copy Subject`, `Email Tracking Email Copy Compressed Body` from `Email Tracking Email Copy` where `Email Tracking Email Copy Key`=%d ', $data['key']);
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $text = gzuncompress($row['Email Tracking Email Copy Compressed Body']);
        }

    }

    echo $text;

}


function template_text($data) {



    $published_email_template = get_object('Published_Email_Template',$data['key']);

    echo $published_email_template->get('Published Email Template HTML');


}

function template_data($data) {

    include_once 'class.Email_Template.php';

    $email_template = new Email_Template($data['key']);

    switch ($data['field']) {
        case 'json':
            print $email_template->get('Email Template Editing JSON');
            break;
    }

}

