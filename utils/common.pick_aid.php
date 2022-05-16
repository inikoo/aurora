<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   21 May 2020  14:04::34  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

use Mpdf\Mpdf;

include_once 'utils/general_functions.php';

function get_pick_aid_mpdf($type) {


    if ($type == 'with_labels') {

        $mpdf = new Mpdf(
            [
                'tempDir'       => 'server_files/pdf_tmp',
                'mode'          => 'utf-8',
                'margin_left'   => 15,
                'margin_right'  => 15,
                'margin_top'    => 15,
                'margin_bottom' => 25,
                'margin_header' => 5,
                'margin_footer' => 10
            ]
        );

    } else {
        $mpdf = new Mpdf(
            [
                'tempDir'       => 'server_files/pdf_tmp',
                'mode'          => 'utf-8',
                'margin_left'   => 15,
                'margin_right'  => 15,
                'margin_top'    => 22,
                'margin_bottom' => 25,
                'margin_header' => 10,
                'margin_footer' => 10
            ]
        );
    }

    return $mpdf;
}


/**
 * @param $type
 * @param $delivery_note \DeliveryNote
 * @param $db            \PDO
 * @param $smarty        \Smarty
 *
 * @return mixed
 * @throws \SmartyException
 */
function get_picking_aid_html_for_pdf($type, $delivery_note, $db, $smarty, $account) {


    $store    = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
    $customer = get_object('Customer', $delivery_note->get('Delivery Note Customer Key'));
    $order    = get_object('Order', $delivery_note->get('Delivery Note Order Key'));


    $smarty->assign('store', $store);
    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('account', $account);

    $smarty->assign('delivery_note', $delivery_note);


    $dangerous_goods = array();
    $sql             =
        "select `Part UN Number` AS un_number ,`Part Packing Group`  AS part_packing_group,group_concat(`Part Reference`) as parts from  `Inventory Transaction Fact` ITF   LEFT JOIN `Part Dimension` Part ON  (Part.`Part SKU`=ITF.`Part SKU`) WHERE `Delivery Note Key`=?  and (`Part UN Number`!='' or `Part Packing Group`!='No' ) group by `Part UN Number`,`Part Packing Group`";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($delivery_note->id)
    );
    while ($row = $stmt->fetch()) {
        if ($row['un_number'] > 1 or $row['part_packing_group'] != 'None') {
            $dangerous_goods[] = $row;
        }
    }
    $smarty->assign('dangerous_goods', $dangerous_goods);


    $transactions = array();


    $sql = sprintf(
        "SELECT  `Part Units Per Package`, Part.`Part Current On Hand Stock` AS total_stock, PLD.`Quantity On Hand` AS stock_in_picking,`Part Current Stock`,`Part Reference` AS reference,`Part Symbol` AS symbol,`Picking Note` AS notes,ITF.`Part SKU`,`Part Package Description` AS description,
(`Required`+`Given`) AS qty,`Location Code` AS location ,
        IFNULL((select GROUP_CONCAT(LD.`Location Key`,':',LD.`Location Code`,':',`Can Pick`,':',`Quantity On Hand` SEPARATOR ',') 
        from `Part Location Dimension` PLD  left join `Location Dimension` LD on (LD.`Location Key`=PLD.`Location Key`) 
        where PLD.`Part SKU`=Part.`Part SKU`  and PLD.`Location Key`!=L.`Location Key` 
        )   ,'') as location_data,
`Part SKOs per Carton` as carton,
`Part UN Number` AS un_number,
`Part Packing Group` AS part_packing_group
FROM 
`Inventory Transaction Fact` ITF   LEFT JOIN  
`Part Dimension` Part ON  (Part.`Part SKU`=ITF.`Part SKU`) LEFT JOIN  
`Location Dimension` L ON  (L.`Location Key`=ITF.`Location Key`)  LEFT JOIN 
`Part Location Dimension` PLD ON (ITF.`Location Key`=PLD.`Location Key` AND ITF.`Part SKU`=PLD.`Part SKU`) 
WHERE `Delivery Note Key`=%d ORDER BY `Location File As`,`Part Reference` ", $delivery_note->id
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            // print_r($row);

            $stock_in_picking = $row['stock_in_picking'];
            $total_stock      = $row['total_stock'];
            $row['stock']     = sprintf("[<b>%d</b>,%d]", $stock_in_picking, $total_stock);
            $row['locations'] = array();
            if ($row['location_data'] != '') {

                foreach (preg_split('/,/', $row['location_data']) as $location_data) {
                    $row['locations'][] = preg_split('/:/', $location_data);
                }

                $can_pick = array_column($row['locations'], 2);
                $stock    = array_column($row['locations'], 3);


                if (count($can_pick) > 0) {
                    array_multisort($can_pick, SORT_DESC, $stock, SORT_DESC, $row['locations']);
                }


            }

            $_qty = $row['qty'];


            if (is_whole_number($row['qty']) or $row['Part Units Per Package'] == 1) {
                $qty = '<b>'.number($row['qty']).'</b>';
            } else {

                $hole = floor($row['qty']);

                $reminder = $row['qty'] - $hole;


                $qty = '<b>'.$hole.'</b> ';

                $residuo = round($reminder * $row['Part Units Per Package'], 2);

                if ($residuo == 1 and $row['Part Units Per Package'] == 2) {
                    $qty .= '&#xBD;';
                } elseif ($residuo == 1 and $row['Part Units Per Package'] == 3) {
                    $qty .= '&#8531;';
                } elseif ($residuo == 2 and $row['Part Units Per Package'] == 3) {
                    $qty .= '&#8532;';
                } elseif ($residuo == 1 and $row['Part Units Per Package'] == 4) {
                    $qty .= '&#188;';
                } elseif ($residuo == 3 and $row['Part Units Per Package'] == 4) {
                    $qty .= '&#190;';
                } elseif ($residuo == 1 and $row['Part Units Per Package'] == 6) {
                    $qty .= '&#8537;';
                } elseif ($residuo == 5 and $row['Part Units Per Package'] == 6) {
                    $qty .= '&#8538;';
                } elseif ($residuo == 1 and $row['Part Units Per Package'] == 8) {
                    $qty .= '&#8539;';
                } elseif ($residuo == 3 and $row['Part Units Per Package'] == 8) {
                    $qty .= '&#8540;';
                } elseif ($residuo == 5 and $row['Part Units Per Package'] == 8) {
                    $qty .= '&#8541;';
                } elseif ($residuo == 7 and $row['Part Units Per Package'] == 8) {
                    $qty .= '&#8542;';
                } else {
                    $qty .= '<sup style="">'.$residuo.'</sup>&#8260;<sub style="">'.$row['Part Units Per Package'].'</sub>';

                }


            }


            $carton = $row['carton'];


            $row['qty'] = $qty;

           
            if ($_qty > 0 and $carton > 1 and fmod($_qty, $carton) == 0) {
                $row['qty'] .= '<div style="font-style: italic;">('.number($_qty / $carton).'c)</div>';
            }


            $row['description_note'] = '';
            $row['images']           = '';

            if ($row['symbol'] != '') {

                switch ($row['symbol']) {
                    case 'star':
                        $symbol = '&#9733;';
                        break;

                    case 'skull':
                        $symbol = '&#9760;';
                        break;
                    case 'radioactive':
                        $symbol = '&#9762;';
                        break;
                    case 'peace':
                        $symbol = '&#9774;';
                        break;
                    case 'sad':
                        $symbol = '&#9785;';
                        break;
                    case 'gear':
                        $symbol = '&#9881;';
                        break;
                    case 'love':
                        $symbol = '&#10084;';
                        break;
                    default:
                        $symbol = '';

                }


                $row['reference'] .= ' '.$symbol;

            }


            $transactions[] = $row;
        }
    }

    //print_r($transactions);

    $smarty->assign('transactions', $transactions);

    $number_of_items = 0;
    $number_of_picks = 0;
    $sql             = sprintf(
        "SELECT count(*) AS items,sum(`Required`+`Given`) AS picks FROM `Inventory Transaction Fact`  WHERE `Delivery Note Key`=%d ", $delivery_note->id
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $number_of_items = $row['items'];
            $number_of_picks = $row['picks'];
        }
    }

    $formatted_number_of_items = '<b>'.number($number_of_items).'</b> '.ngettext('item', 'items', $number_of_items);
    $formatted_number_of_picks = '<i>'.number($number_of_picks).' '.ngettext('SKO', 'SKOs', $number_of_picks).'</i>';

    $smarty->assign('formatted_number_of_items', $formatted_number_of_items);
    $smarty->assign('formatted_number_of_picks', $formatted_number_of_picks);

    /*

    $qr_data = sprintf(
        '%s/delivery_notes/%d?d=%s', $account->get('Account System Public URL'), $delivery_note->id, base64_url_encode(
                                       (json_encode(
                                           array(
                                               'a' => $account->get('Code'),
                                               'k' => $delivery_note->id,
                                               'c' => gmdate('u')
                                           )
                                       ))
                                   )
    );
*/

    $qr_data = sprintf('%s/dn/%d?d=%s', $account->get('Account System Public URL'), $delivery_note->id, gmdate('U'));


    $smarty->assign(
        'qr_data', $qr_data
    );


    if ($delivery_note->get('Delivery Note Type') == 'Order' and $order->get('Order Priority Level') != 'Normal') {
        $urgent = true;
    } else {
        $urgent = false;
    }

    if ($delivery_note->get('Delivery Note Type') == 'Order' and $order->get('Order Care Level') != 'Normal') {
        $fragile = true;
    } else {
        $fragile = false;
    }

    if ($delivery_note->get('Delivery Note Type') == 'Order' and $order->get('Order Shipping Level') != 'Normal') {
        $use_tracking = true;
    } else {
        $use_tracking = false;
    }


    $smarty->assign('urgent', $urgent);
    $smarty->assign('fragile', $fragile);
    $smarty->assign('use_tracking', $use_tracking);

    //exit;


    if ($type == 'with_labels') {
        $html = $smarty->fetch('order_pick_aid_with_labels.pdf.tpl');

    } else {

        $html = $smarty->fetch('order_pick_aid.pdf.tpl');

    }

    return $html;


}
