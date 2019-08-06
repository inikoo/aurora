<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 March 2018 at 16:51:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';


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
    case 'estimate_number_list_items':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'      => array('type' => 'string'),
                         'parent_key'  => array('type' => 'string'),
                         'fields_data' => array('type' => 'json array')
                     )
        );

        estimate_number_list_items($data, $db, $user, $smarty);
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


function estimate_number_list_items($data, $db, $user, $smarty) {


  // print_r($data);
  // exit;
    $number_items = 0;

    switch ($data['object']) {
        case 'Email_Campaign':
            include_once 'utils/parse_customer_list.php';


            $email_campaign=get_object('Email_Campaign_Type',$data['parent_key']);
            $data['fields_data']['store_key']=$email_campaign->get('Store Key');

            list($table, $where,$group_by) = parse_customer_list($data['fields_data'],$db);

            $where = sprintf(' where `Customer Store Key`=%d ', $email_campaign->get('Store Key')).$where.' and `Customer Send Email Marketing`="Yes" and `Customer Main Plain Email`!="" ';

            $sql = "select count(Distinct C.`Customer Key`) as num from $table  $where ";




            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_items = $row['num'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

            $text=sprintf(
                ngettext('The mailing list will have %s recipient', 'The mailing list will have %s recipients', $number_items), number($number_items)
            );

            break;
        case 'Customers_List':
            include_once 'utils/parse_customer_list.php';

            $data['fields_data']['store_key']=$data['parent_key'];
            list($table, $where,$group_by) = parse_customer_list($data['fields_data'],$db);

            $where = sprintf(' where `Customer Store Key`=%d ', $data['parent_key']).$where;

            $sql = "select count(Distinct C.`Customer Key`) as num from $table  $where ";



            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_items = $row['num'];
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

            $text=sprintf(
                ngettext('The list will have %s customer', 'The list will have %s customers', $number_items), number($number_items)
            );

            break;
        default:
            break;
    }


    $response = array(
        'state' => 200,

        'number_items' => $number_items,
        'text' =>$text
    );
    echo json_encode($response);

}



