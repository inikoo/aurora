<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2018 at 20:23:56 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$customer = $object;

$customer_fields = array();

$sql = "SELECT `Customer Poll Query Key` FROM `Customer Poll Query Dimension` WHERE `Customer Poll Query Store Key`=?";

$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $customer->get('Store Key')
    )
);
while ($row = $stmt->fetch()) {
    $poll_query = get_object('Customer_Poll_Query', $row['Customer Poll Query Key']);
    if ($poll_query->get('Customer Poll Query Type') == 'Open') {




        $customer_fields[] = array(
            'label'      => $poll_query->get('Customer Poll Query Label'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'edit'        => ($edit ? 'textarea' : ''),
                    'id'          => 'Customer_Poll_Query_'.$poll_query->id,
                    'value'       => $poll_query->get_answer($customer->id)[1],
                    'label'           => sprintf(
                        '<span class="link" onclick="change_view(\'customers/%d/poll_query/%d\')" >%s</span>', $poll_query->get('Store Key'), $poll_query->id, $poll_query->get('Customer Poll Query Name')
                    ),
                    'invalid_msg' => get_invalid_message('string'),
                    'required'    => false,
                    'type'        => 'value'
                )
            )
        );


    }
    else {


        $options = array();


        $sql = sprintf('SELECT `Customer Poll Query Option Key`,`Customer Poll Query Option Name`,`Customer Poll Query Option Label`  FROM `Customer Poll Query Option Dimension` WHERE `Customer Poll Query Option Query Key`=%d ', $poll_query->id);

        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {

                $options[$row2['Customer Poll Query Option Key']] = $row2['Customer Poll Query Option Label'];

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        list($answer_code, $answer_label, $answer_key) = $poll_query->get_answer($customer->id);


        $customer_fields[] = array(
            'label'      => $poll_query->get('Customer Poll Query Label'),
            'show_title' => true,
            'fields'     => array(

                array(
                    'id'              => 'Customer_Poll_Query_'.$poll_query->id,
                    'edit'            => ($edit ? 'option' : ''),
                    'options'         => $options,
                    'value'           => $answer_key,
                    'formatted_value' => $answer_label,
                    'label'           => sprintf(
                        '<span class="link" onclick="change_view(\'customers/%d/poll_query/%d\')" >%s</span>', $poll_query->get('Store Key'), $poll_query->id, $poll_query->get('Customer Poll Query Name')
                    ),
                    'type'            => 'value'
                )
            )
        );


    }
    }

