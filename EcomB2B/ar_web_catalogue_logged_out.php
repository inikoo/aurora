<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  07 March 2020  11:54::12  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_out.php';
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

    case 'departments':
        $_data = get_table_parameters();


        $sql  = "select `Store Department Category Key` from `Store Dimension` where `Store Key`=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $website->get('Website Store Key')
            )
        );
        if ($row = $stmt->fetch()) {
            $_data['parameters']['parent']     = 'category';
            $_data['parameters']['parent_key'] = $row['Store Department Category Key'];
            departments($_data, $db, $website);

        } else {
            $response = array(
                'state' => 400,
                'resp'  => 'departments not found'
            );
            echo json_encode($response);
            exit;
        }


        break;
    case 'families':
        $_data = get_table_parameters();

        if ($_data['parameters']['parent'] == 'store') {
            $sql  = "select `Store Family Category Key` from `Store Dimension` where `Store Key`=?";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $website->get('Website Store Key')
                )
            );
            if ($row = $stmt->fetch()) {
                $_data['parameters']['parent']     = 'category';
                $_data['parameters']['parent_key'] = $row['Store Family Category Key'];
                $_data['parameters']['store_key'] = $website->get('Website Store Key');

                families($_data, $db);

            } else {
                $response = array(
                    'state' => 400,
                    'resp'  => 'departments not found'
                );
                echo json_encode($response);
                exit;
            }
        }elseif ($_data['parameters']['parent'] == 'department') {
            $_data['parameters']['store_key'] = $website->get('Website Store Key');
            families($_data, $db);


        }

        break;


    case 'products':
        $_data = get_table_parameters();


        if ($_data['parameters']['parent'] == 'store') {

            $_data['parameters']['parent']     = 'store';
            $_data['parameters']['parent_key'] = $website->get('Website Store Key');
            products($_data, $db);


        } elseif ($_data['parameters']['parent'] == 'department') {

            $_data['parameters']['store_key'] = $website->get('Website Store Key');
            products($_data, $db);


        }elseif ($_data['parameters']['parent'] == 'family') {

            $_data['parameters']['store_key'] = $website->get('Website Store Key');
            products($_data, $db);


        }

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
 * @param $db \PDO
 * @param $website
 */
function families($_data, $db) {


    $rtext_label = 'family';


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

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $code = sprintf('<a href="%s">%s</a>', $data['Webpage URL'], $data['Category Code']);
            $name = sprintf('<a href="%s">%s</a>', $data['Webpage URL'], $data['Category Label']);

            $record_data[] = array(
                'id'       => $data['Category Key'],
                'code'     => $code,
                'name'     => $name,
                'products' => sprintf('<a href="catalogue.sys?scope=products&parent=family&parent_key=%d">%s</a>', $data['Category Key'], number($data['Category Number Subjects']))

            );
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


}

/**
 * @param $data
 * @param $db \PDO
 * @param $website
 */
function departments($_data, $db, $website) {

    $rtext_label = 'department';


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

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $code = sprintf('<a href="%s">%s</a>', $data['Webpage URL'], $data['Category Code']);
            $name = sprintf('<a href="%s">%s</a>', $data['Webpage URL'], $data['Category Label']);

            $record_data[] = array(
                'id'   => $data['Category Key'],
                'code' => $code,
                'name' => $name,

                'families' => sprintf('<a href="catalogue.sys?scope=families&parent=department&parent_key=%d">%s</a>', $data['Category Key'], number($data['Category Number Subjects'])),
                'products' => sprintf('<a href="catalogue.sys?scope=products&parent=department&parent_key=%d">%s</a>', $data['Category Key'], number($data['products']))

            );
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


}


/**
 * @param $data
 * @param $db \PDO
 * @param $website
 */

function products($_data, $db) {

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

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";


    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $code = sprintf('<a href="%s">%s</a>', $data['Webpage URL'], $data['Product Code']);
            $name = $data['Product Units per Case'].'x '.$data['Product Name'];

            $record_data[] = array(
                'id'   => $data['Product ID'],
                'code' => $code,
                'name' => $name,


            );
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


}
