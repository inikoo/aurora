<?php
chdir('../');
require_once __DIR__.'/../vendor/autoload.php';


require_once 'utils/object_functions.php';

require_once 'common.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit("no id");
}
$delivery_note = get_object('DeliveryNote', $id);
if (!$delivery_note->id) {
    exit("no dn");
}
$store    = get_object('Store', $delivery_note->get('Delivery Note Store Key'));
$customer = get_object('Customer', $delivery_note->get('Delivery Note Customer Key'));
$order    = get_object('Order', $delivery_note->get('Delivery Note Order Key'));


$css = '<style>'.file_get_contents(getcwd().'/node_modules/@fortawesome/fontawesome-pro/css/all.css').'</style>';

$smarty->assign('css', $css);


if(isset($_REQUEST['with_labels'])) {


    $mpdf = new \Mpdf\Mpdf(
        [
            'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
            'mode'          => 'utf-8',
            'margin_left'   => 15,
            'margin_right'  => 15,
            'margin_top'    => 15,
            'margin_bottom' => 25,
            'margin_header' => 5,
            'margin_footer' => 10
        ]
    );

}else{
    $mpdf = new \Mpdf\Mpdf(
        [
            'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
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


//$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Picking Aid').' '.$delivery_note->data['Delivery Note ID']);
$mpdf->SetAuthor($store->data['Store Name']);


//$mpdf->SetDisplayMode('fullpage');
//$mpdf->SetJS('this.print();');    // set when we want to print....

$smarty->assign('store', $store);
$smarty->assign('order', $order);
$smarty->assign('customer', $customer);

$smarty->assign('delivery_note', $delivery_note);


$dangerous_goods=array();
$sql=sprintf(' select `Part UN Number` AS un_number ,`Part Packing Group`  AS part_packing_group,group_concat(`Part Reference`) as parts from  `Inventory Transaction Fact` ITF   LEFT JOIN `Part Dimension` Part ON  (Part.`Part SKU`=ITF.`Part SKU`) WHERE `Delivery Note Key`=?  and (`Part UN Number`!="" or `Part Packing Group`!="Noe" ) group by `Part UN Number`,`Part Packing Group`  ');

$stmt = $db->prepare($sql);
$stmt->execute(
    array($delivery_note->id)
);
while ($row = $stmt->fetch()) {
    if($row['un_number']>1 or $row['part_packing_group']!='None')
    $dangerous_goods[]=$row;
}
$smarty->assign('dangerous_goods', $dangerous_goods);



$transactions = array();


$sql = sprintf(
    "SELECT  Part.`Part Current On Hand Stock` AS total_stock, PLD.`Quantity On Hand` AS stock_in_picking,`Part Current Stock`,`Part Reference` AS reference,`Part Symbol` AS symbol,`Picking Note` AS notes,ITF.`Part SKU`,`Part Package Description` AS description,
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
        $stock_in_picking = $row['stock_in_picking'];
        $total_stock      = $row['total_stock'];

        $row['stock']     = sprintf("[<b>%d</b>,%d]", $stock_in_picking, $total_stock);
        $row['locations'] = array();


        // print $row['location_data'];

        if ($row['location_data'] == '') {

        } else {
            foreach (preg_split('/,/', $row['location_data']) as $location_data) {
                $row['locations'][] = preg_split('/\:/', $location_data);
            }

            $can_pick = array_column($row['locations'], 2);
            $stock    = array_column($row['locations'], 3);


            if (count($can_pick) > 0) {
                array_multisort($can_pick, SORT_DESC, $stock, SORT_DESC, $row['locations']);
            }




        }

        $qty=$row['qty']   ;
        $carton=$row['carton']   ;


        $row['qty']           = '<b>'.$qty.'</b>';

        if($carton>1 and fmod($qty,$carton)==0){


            $row['qty']  .='<div style="font-style: italic;">('.number($qty/$carton).'c)</div>';
        }


        $row['description_note'] = '';
        $row['images']           = '';

        if($row['symbol'] !=''){

            switch ($row['symbol']){
                case 'star':
                    $symbol= '&#9733;';
                    break;

                case 'skull':
                    $symbol=  '&#9760;';
                    break;
                case 'radioactive':
                    $symbol=  '&#9762;';
                    break;
                case 'peace':
                    $symbol=  '&#9774;';
                    break;
                case 'sad':
                    $symbol=  '&#9785;';
                    break;
                case 'gear':
                    $symbol=  '&#9881;';
                    break;
                case 'love':
                    $symbol=  '&#10084;';
                    break;
                default:
                    $symbol= '';

            }



            $row['reference']           .= ' '.$symbol ;

        }


        $transactions[]          = $row;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


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
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

//print $sql;

$formatted_number_of_items = '<b>'.number($number_of_items).'</b> '.ngettext(
        'item', 'items', $number_of_items
    );
$formatted_number_of_picks = '<i>'.number($number_of_picks).' '.ngettext(
        'SKO', 'SKOs', $number_of_picks
    ).'</i>';

$smarty->assign('formatted_number_of_items', $formatted_number_of_items);
$smarty->assign('formatted_number_of_picks', $formatted_number_of_picks);


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


$qr_data = sprintf('%s/dn/%d?d=%s', $account->get('Account System Public URL'), $delivery_note->id, gmdate('U'));


$smarty->assign(
    'qr_data', $qr_data
);

if(isset($_REQUEST['with_labels'])){
    $html = $smarty->fetch('order_pick_aid_with_labels.pdf.tpl');

}else{
    $html = $smarty->fetch('order_pick_aid.pdf.tpl');

}


$mpdf->WriteHTML($html);
$mpdf->Output($delivery_note->get('Delivery Note ID').'_picking.pdf', 'I');

