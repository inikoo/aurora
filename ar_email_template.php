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


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function template_data($data) {

    include_once 'class.Email_Template.php';

    $email_template = new Email_Template($data['key']);

    switch ($data['field']){
        case 'json':


            print $email_template->get('Email Template Editing JSON');
            break;
    }





}

?>
