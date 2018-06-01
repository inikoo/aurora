<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:8 November 2015 at 13:37:41 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

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

    case 'valid_redirection_webpage_code':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'       => array('type' => 'string'),
                         'parent'       => array('type' => 'string'),
                         'parent_key'   => array('type' => 'string'),
                         'key'          => array('type' => 'string'),
                         'field'        => array('type' => 'string'),
                         'actual_field' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'value'        => array('type' => 'string'),
                         'metadata'     => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),
                     )
        );

        valid_redirection_webpage_code($data, $db, $user, $account);
        break;

    case 'check_for_duplicates':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'       => array('type' => 'string'),
                         'parent'       => array('type' => 'string'),
                         'parent_key'   => array('type' => 'string'),
                         'key'          => array('type' => 'string'),
                         'field'        => array('type' => 'string'),
                         'actual_field' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'value'        => array('type' => 'string'),
                         'metadata'     => array(
                             'type'     => 'json array',
                             'optional' => true
                         ),
                     )
        );

        check_for_duplicates($data, $db, $user, $account);
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


function valid_redirection_webpage_code($data, $db, $user, $account) {

    $invalid_msg = '';


    $sql = sprintf(
        "SELECT P.`Page Key` AS `key` ,`Webpage Code` ,`Webpage State`  FROM `Page Store Dimension` P WHERE  `Webpage Code`=%s  AND `Webpage Website Key`=%s  ", prepare_mysql($data['value']), $data['parent_key']

    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

            if ($row['key'] == $data['key']) {
                $invalid_msg = _("Webpage can't be redirected to itself");
            } elseif ($row['Webpage State'] != 'Online') {
                $invalid_msg = _("Redirection webpage is not online");
            }


        } else {
            $invalid_msg = _('Webpage not found');

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    if ($invalid_msg == '') {
        $validation = 'valid';
        $msg        = '';
    } else {
        $validation = 'invalid';
        $msg        = $invalid_msg;
    }

    $response = array(
        'state'      => 200,
        'validation' => $validation,
        'msg'        => $msg,
    );
    echo json_encode($response);

}

function check_for_duplicates($data, $db, $user, $account) {


    $field = preg_replace('/_/', ' ', $data['field']);


    $validation_sql_queries = array();

    $options_where = '';


    switch ($data['object']) {

        case 'Customers_List':
        case 'List':
            $invalid_msg = _('Another list has same name');
            $sql         = sprintf(
                "SELECT `List Key`AS `key` ,`List Name` AS field FROM `List Dimension` WHERE `List Parent Key`=%d  AND `List Name`=%s",
                $data['parent_key'],
                prepare_mysql($data['value'])
            );

            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );


            break;
        case 'Customer Poll Query Option':
            $invalid_msg = _('Another option has same code');
            $sql         = sprintf(
                "SELECT `Customer Poll Query Option Key`AS `key` ,`Customer Poll Query Option Name` AS field FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Store Key`=%d  AND `Customer Poll Query Option Name`=%s",
                $data['parent_key'],
                prepare_mysql($data['value'])
            );

            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );


            break;
        case 'User':
            switch ($field) {
                case 'Staff User Handle':
                    $invalid_msg = _('Another user is using this login');
                    $sql         = sprintf(
                        "SELECT `User Key`AS `key` ,`User Handle` AS field FROM `User Dimension` WHERE `User Type` IN ('Staff','Contractor','Administrator')  AND `User Handle`=%s", prepare_mysql($data['value'])
                    );

                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;
                case 'User Password Recovery Email':
                    $invalid_msg = _('Another user is using this email');
                    $sql         = sprintf(
                        "SELECT `User Key`AS `key` ,`User Handle` AS field FROM `User Dimension` WHERE `User Type` IN ('Staff','Contractor') AND `User Password Recovery Email`=%s", prepare_mysql($data['value'])
                    );

                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;

                default:

                    break;
            }
            break;

        case 'Deal':
            switch ($field) {
                case 'Deal Name':
                    $invalid_msg = _('Another offer has this name');
                    $sql         = sprintf(
                        "SELECT `Deal Key`AS `key` ,`Deal Name` AS field FROM `Deal Dimension` WHERE `Deal Name`=%s  and `Deal Store Key`=%d ", prepare_mysql($data['value']),$data['parent_key']
                    );



                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;
                case 'Deal Voucher Code':
                    $invalid_msg = _('Voucher code already used');
                    $sql         = sprintf(
                        "SELECT `Voucher Key`AS `key` ,`Voucher Code` AS field FROM `Voucher Dimension` WHERE `Voucher Code`=%s  and `Voucher Store Key`=%d ", prepare_mysql($data['value']),$data['parent_key']
                    );



                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;


                default:

                    break;
            }
            break;

        case 'Contractor':

            switch ($field) {
                case 'Staff ID':
                    $invalid_msg              = _(
                        'Another contractor is using this payroll Id'
                    );
                    $sql                      = sprintf(
                        "SELECT `Staff Key` AS `key` ,`Staff Alias` AS field FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' AND  `Staff ID`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff Alias':
                    $invalid_msg              = _(
                        'Another contractor is using this code'
                    );
                    $sql                      = sprintf(
                        "SELECT `Staff Key` AS `key` ,`Staff Alias` AS field FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' AND  `Staff Alias`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff Email':
                    $invalid_msg              = _(
                        'Another contractor is using this code'
                    );
                    $sql                      = sprintf(
                        "SELECT `Staff Key` AS `key` ,`Staff Alias` AS field FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' AND  `Staff Email`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff User Handle':
                    $invalid_msg              = _(
                        'Another user is using this login handle'
                    );
                    $sql                      = sprintf(
                        "SELECT `User Key`AS `key` ,`User Handle` AS field FROM `User Dimension` WHERE `User Type`!='Customer' AND `User Handle`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;
                default:

                    break;
            }


            break;

        case 'Staff':
        case 'ExStaff':


            if ($data['object'] == 'Staff') {
                $extra_where = "`Staff Currently Working`='Yes' and";
            } else {
                $data['object'] = 'Staff';
                $extra_where    = '';
            }


            switch ($field) {
                case 'Staff ID':
                    $invalid_msg              = _(
                        'Another employee is using this payroll Id'
                    );
                    $sql                      = sprintf(
                        "select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where  $extra_where `Staff ID`=%s  ", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff Alias':
                    $invalid_msg              = _(
                        'Another employee is using this code'
                    );
                    $sql                      = sprintf(
                        "select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where $extra_where  `Staff Alias`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff Email':
                    $invalid_msg              = _(
                        'Another employee is using this email'
                    );
                    $sql                      = sprintf(
                        "select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where $extra_where  `Staff Email`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff Official ID':
                    $invalid_msg              = _(
                        'Another employee is using this Id'
                    );
                    $sql                      = sprintf(
                        "select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where $extra_where `Staff Official ID`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    break;
                case 'Staff User Handle':
                    $invalid_msg              = _(
                        'Another user is using this login handle'
                    );
                    $sql                      = sprintf(
                        "SELECT `User Key`AS `key` ,`User Handle` AS field FROM `User Dimension` WHERE `User Type`!='Customer' AND `User Handle`=%s", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
                    break;
                default:

                    break;
            }


            break;
        case 'Store':

            switch ($field) {
                case 'Store Code':
                    $invalid_msg = _('Another store is using this code');
                    break;

                default:

                    break;
            }


            break;

        case 'Category':

            switch ($field) {
                case 'Category Code':
                    $invalid_msg = _('Another category is using this code');
                    break;

                default:

                    break;
            }


            break;
            
            
        case 'Prospect':

            $invalid_msg              = _('Prospect already inputted');
            $sql                      = sprintf(
                "SELECT `Prospect Key` AS `key` ,`Prospect Main Plain Email` AS field FROM `Prospect Dimension` WHERE `Prospect Main Plain Email`=%s AND `Prospect Store Key`=%d ", prepare_mysql($data['value']), $data['parent_key']
            );
            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );


            $invalid_msg              = _('Prospect already registered');
            $sql                      = sprintf(
                "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s AND `Customer Store Key`=%d ", prepare_mysql($data['value']), $data['parent_key']
            );
            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );

            $invalid_msg              = _('Prospect already registered');
            $sql                      = sprintf(
                "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND `Customer Other Email Store Key`=%d   ",
                prepare_mysql($data['value']), $data['parent_key']
            );
            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );


            break;

        case 'Customer':
            switch ($field) {
                case 'Customer Main Plain Email':
                    $invalid_msg              = _('Another customer have this email');
                    $sql                      = sprintf(
                        "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s AND `Customer Store Key`=%d ", prepare_mysql($data['value']), $data['parent_key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _('Another customer have this email');
                    $sql                      = sprintf(
                        "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND `Customer Other Email Store Key`=%d  AND `Customer Other Email Customer Key`!=%d ",
                        prepare_mysql($data['value']), $data['parent_key'], $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    $invalid_msg              = _('Email already set up in this customer');
                    $sql                      = sprintf(
                        "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND  `Customer Other Email Customer Key`=%d ", prepare_mysql($data['value']),
                        $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    break;
                case 'new email':
                    $invalid_msg              = _(
                        'Another customer have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s AND `Customer Store Key`=%d ", prepare_mysql($data['value']), $data['parent_key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _(
                        'Another customer have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND `Customer Other Email Store Key`=%d  AND `Customer Other Email Customer Key`!=%d ",
                        prepare_mysql($data['value']), $data['parent_key'], $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _(
                        'Email already set up in this customer'
                    );
                    $sql                      = sprintf(
                        "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND  `Customer Other Email Customer Key`=%d ", prepare_mysql($data['value']),
                        $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    $invalid_msg              = _(
                        'Email already set up in this customer'
                    );
                    $sql                      = sprintf(
                        "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Key`=%d ", prepare_mysql($data['value']), $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                default:

                    if (preg_match(
                        '/^Customer Other Email (\d+)/i', $field, $matches
                    )) {
                        $customer_email_key = $matches[1];


                        $invalid_msg              = _(
                            'Email already set up in this customer'
                        );
                        $sql                      = sprintf(
                            "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s  AND `Customer Key`=%d ", prepare_mysql($data['value']), $data['key']
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Email already set up in this customer'
                        );
                        $sql                      = sprintf(
                            "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND  `Customer Other Email Customer Key`=%d  AND  `Customer Other Email Key`!=%d  ",
                            prepare_mysql($data['value']), $data['key'], $customer_email_key
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Another customer have this email'
                        );
                        $sql                      = sprintf(
                            "SELECT `Customer Key` AS `key` ,`Customer Main Plain Email` AS field FROM `Customer Dimension` WHERE `Customer Main Plain Email`=%s AND `Customer Store Key`=%d ", prepare_mysql($data['value']), $data['parent_key']
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Another customer have this email'
                        );
                        $sql                      = sprintf(
                            "SELECT `Customer Other Email Customer Key` AS `key` ,`Customer Other Email Email` AS field FROM `Customer Other Email Dimension` WHERE `Customer Other Email Email`=%s AND `Customer Other Email Store Key`=%d  ", prepare_mysql($data['value']),
                            $data['parent_key']
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                    }
            }


            break;

        case 'Supplier':
            switch ($field) {
                case 'Supplier Main Plain Email':
                    $invalid_msg              = _(
                        'Another supplier have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Key` AS `key` ,`Supplier Main Plain Email` AS field FROM `Supplier Dimension` WHERE `Supplier Main Plain Email`=%s  ", prepare_mysql($data['value'])

                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _(
                        'Another supplier have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s   AND `Supplier Other Email Supplier Key`!=%d ",
                        prepare_mysql($data['value']), $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    $invalid_msg              = _(
                        'Email already set up in this supplier'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s AND  `Supplier Other Email Supplier Key`=%d ", prepare_mysql($data['value']),
                        $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    break;
                case 'new email':
                    $invalid_msg              = _(
                        'Another supplier have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Key` AS `key` ,`Supplier Main Plain Email` AS field FROM `Supplier Dimension` WHERE `Supplier Main Plain Email`=%s  ", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _(
                        'Another supplier have this email'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s  AND `Supplier Other Email Supplier Key`!=%d ", prepare_mysql($data['value']),
                        $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

                    $invalid_msg              = _(
                        'Email already set up in this supplier'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s AND  `Supplier Other Email Supplier Key`=%d ", prepare_mysql($data['value']),
                        $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    $invalid_msg              = _(
                        'Email already set up in this supplier'
                    );
                    $sql                      = sprintf(
                        "SELECT `Supplier Key` AS `key` ,`Supplier Main Plain Email` AS field FROM `Supplier Dimension` WHERE `Supplier Key`=%d ", prepare_mysql($data['value']), $data['key']
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                default:

                    if (preg_match(
                        '/^Supplier Other Email (\d+)/i', $field, $matches
                    )) {
                        $supplier_email_key = $matches[1];


                        $invalid_msg              = _(
                            'Email already set up in this supplier'
                        );
                        $sql                      = sprintf(
                            "SELECT `Supplier Key` AS `key` ,`Supplier Main Plain Email` AS field FROM `Supplier Dimension` WHERE `Supplier Main Plain Email`=%s  AND `Supplier Key`=%d ", prepare_mysql($data['value']), $data['key']
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Email already set up in this supplier'
                        );
                        $sql                      = sprintf(
                            "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s AND  `Supplier Other Email Supplier Key`=%d  AND  `Supplier Other Email Key`!=%d  ",
                            prepare_mysql($data['value']), $data['key'], $supplier_email_key
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Another supplier have this email'
                        );
                        $sql                      = sprintf(
                            "SELECT `Supplier Key` AS `key` ,`Supplier Main Plain Email` AS field FROM `Supplier Dimension` WHERE `Supplier Main Plain Email`=%s  ", prepare_mysql($data['value'])
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                        $invalid_msg              = _(
                            'Another supplier have this email'
                        );
                        $sql                      = sprintf(
                            "SELECT `Supplier Other Email Supplier Key` AS `key` ,`Supplier Other Email Email` AS field FROM `Supplier Other Email Dimension` WHERE `Supplier Other Email Email`=%s   ", prepare_mysql($data['value'])
                        );
                        $validation_sql_queries[] = array(
                            'sql'         => $sql,
                            'invalid_msg' => $invalid_msg
                        );


                    }
            }


            break;
        case 'Part':
            switch ($field) {
                case 'Part Barcode':
                case 'Part Barcode Number':
                case 'Part Part Barcode Number':


                    // $invalid_msg              =
                    $sql = sprintf(
                        "SELECT P.`Part SKU` AS `key` ,`Part Barcode Number` AS field  ,`Part Reference` AS name FROM `Part Dimension` P WHERE  `Part Barcode Number`=%s AND `Part SKU`!=%d  ", prepare_mysql($data['value']), $data['key']
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $response = array(
                                'state'      => 200,
                                'validation' => 'invalid',
                                'msg'        => sprintf(_('Unit barcode already used by %s'), '<span class="link error" style="color:red"  onclick="change_view(\'/part/'.$row['key'].'\')" >'.$row['name'].'</span>')
                            );

                        } else {
                            $response = array(
                                'state'      => 200,
                                'validation' => 'valid',
                                'msg'        => ''
                            );
                        }
                        echo json_encode($response);
                        exit;
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    break;
                case 'Part SKO Barcode':
                case 'Part Part SKO Barcode':

                    $invalid_msg              = _('SKO barcode already used');
                    $sql                      = sprintf(
                        "SELECT P.`Part SKU` AS `key` ,`Part Barcode Number` AS field FROM `Part Dimension` P WHERE  `Part SKO Barcode`=%s   ", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    break;

                case 'Part Carton Barcode':
                case 'Part Part Carton Barcode':

                    $invalid_msg              = _('Carton barcode already used');
                    $sql                      = sprintf(
                        "SELECT P.`Part SKU` AS `key` ,`Part Barcode Number` AS field FROM `Part Dimension` P WHERE  `Part Carton Barcode`=%s   ", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );


                    break;

                case 'Part Reference':
                case 'Part Part Reference':


                    $invalid_msg = _('Part reference already used');
                    //		$sql=sprintf("select P.`Part SKU` as `key` ,`Part Reference` as field from `Part Dimension` P where  `Part Reference`=%s  and `Part Status`='In Use' ",

                    $sql                      = sprintf(
                        "SELECT P.`Part SKU` AS `key` ,`Part Reference` AS field FROM `Part Dimension` P WHERE  `Part Reference`=%s   ", prepare_mysql($data['value'])
                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );
            }

            break;
        case 'Product':
            switch ($field) {
                case 'Product Code':


                    $invalid_msg              = _('Product code already used');
                    $sql                      = sprintf(
                        "SELECT P.`Product ID` AS `key` ,`Product Code` AS field FROM `Product Dimension` P WHERE  `Product Code`=%s  AND `Product Store Key`=%s AND `Product Status`!='Discontinued' ", prepare_mysql($data['value']), $data['parent_key']

                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

            }

            break;
        case 'Supplier Delivery':

            $options_where = '';
            if (isset($data['metadata']['option']) and $data['metadata']['option'] == 'creating_dn_from_po') {
                //'In Process','Send','Received','Checked','Placing','Done' TODO maybe he have to be more flaxivle with this

                //	$options_where=" and `Supplier Delivery State` in ('Done','Checked','Placing')";
            }

            if (isset($data['actual_field'])) {
                $_field = $data['actual_field'];
            } else {
                $_field = $field;
            }

            switch (strtolower($data['parent'])) {
                case 'agent':
                    $parent_where = sprintf(
                        ' and `%s Parent`="Agent"  and `%s Parent Key`=%d ', $data['object'], $data['object'], $data['parent_key']
                    );
                    break;

                case 'supplier':
                    $parent_where = sprintf(
                        ' and `%s Parent`="Supplier"  and `%s Parent Key`=%d ', $data['object'], $data['object'], $data['parent_key']
                    );
                    break;
                default:
                    $parent_where = '';
            }

            $invalid_msg = _('Delivery number already used');
            $sql         = sprintf(
                'SELECT `%s Key` AS `key` ,`%s` AS field FROM `%s Dimension` WHERE `%s`=%s %s %s', addslashes(preg_replace('/_/', ' ', $data['object'])), addslashes($_field),

                addslashes(preg_replace('/_/', ' ', $data['object'])), addslashes($_field), prepare_mysql($data['value']), $parent_where, $options_where

            );

            //print $sql;

            $validation_sql_queries[] = array(
                'sql'         => $sql,
                'invalid_msg' => $invalid_msg
            );

            break;
        case 'PurchaseOrder':
            $data['object'] = 'Purchase Order';
            break;
        case 'Page':
        case 'Webpage':

            switch ($field) {
                case 'Webpage Code':


                    $invalid_msg              = _('Webpage code already used');
                    $sql                      = sprintf(
                        "SELECT P.`Page Key` AS `key` ,`Webpage Code` AS field FROM `Page Store Dimension` P WHERE  `Webpage Code`=%s  AND `Webpage Website Key`=%s  ", prepare_mysql($data['value']), $data['parent_key']

                    );
                    $validation_sql_queries[] = array(
                        'sql'         => $sql,
                        'invalid_msg' => $invalid_msg
                    );

            }

            break;

        default:


            break;
    }


    if (count($validation_sql_queries) == 0) {
        switch (strtolower($data['parent'])) {
            case 'store':
                $parent_where = sprintf(
                    ' and `%s Store Key`=%d ', $data['object'], $data['parent_key']
                );
                break;
            case 'category':
                $parent_where = sprintf(
                    ' and `%s Parent Key`=%d ', $data['object'], $data['parent_key']
                );
                break;
            case 'supplier':
                if ($data['object'] == 'Purchase Order') {
                    $parent_where = sprintf(
                        ' and `Purchase Order Parent Key`=%d ', $data['parent_key']
                    );

                } else {
                    $parent_where = sprintf(
                        ' and `%s Supplier Key`=%d ', $data['object'], $data['parent_key']
                    );
                }
                break;
            default:
                $parent_where = '';
        }

        if (isset($data['actual_field'])) {
            $_field = $data['actual_field'];
        } else {
            $_field = $field;
        }


        $sql = sprintf(
            'SELECT `%s Key` AS `key` ,`%s` AS field FROM `%s Dimension` WHERE `%s`=%s %s %s', addslashes(preg_replace('/_/', ' ', $data['object'])), addslashes($_field), addslashes(preg_replace('/_/', ' ', $data['object'])), addslashes($_field),
            prepare_mysql($data['value']), $parent_where, $options_where

        );


        if (!isset($invalid_msg)) {
            $invalid_msg = _('There is another object with the same value');
        }
        $validation_sql_queries[] = array(
            'sql'         => $sql,
            'invalid_msg' => $invalid_msg
        );
    }

    $validation = 'valid';
    $msg        = '';


    foreach ($validation_sql_queries as $validation_query) {
        $sql         = $validation_query['sql'];
        $invalid_msg = $validation_query['invalid_msg'];

        //print "$sql\n";

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $validation = 'invalid';
                $msg        = $invalid_msg;
                break;
            } else {

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql";
            exit;
        }


    }

    $response = array(
        'state'      => 200,
        'validation' => $validation,
        'msg'        => $msg,
    );
    echo json_encode($response);


}


?>
