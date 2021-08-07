<?php

use Mpdf\Mpdf;

chdir('../');

require_once __DIR__.'/../vendor/autoload.php';
/** @var User $user */
/** @var Account $account */
/** @var Smarty $smarty */
/** @var PDO $db */


include_once 'common.php';
if ($user->get('User View') != 'Staff') {
    exit;
}

require_once 'utils/table_functions.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$purchase_order = get_object('Purchase_Order', $id);


if (!$purchase_order->id) {
    exit;
}


$mpdf = new Mpdf([
                     'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
                     'mode'          => 'utf-8',
                     'margin_left'   => 20,
                     'margin_right'  => 15,
                     'margin_top'    => 38,
                     'margin_bottom' => 25,
                     'margin_header' => 10,
                     'margin_footer' => 10
                 ]);


//$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(
    sprintf(_('Purchase order %s'), $purchase_order->get('Public ID'))
);
$mpdf->SetAuthor($account->get('Name'));


if (isset($_REQUEST['print'])) {
    $mpdf->SetJS('this.print();');
}

$smarty->assign('purchase_order', $purchase_order);


if ($purchase_order->get('Purchase Order State') == 'Cancelled') {
    $mpdf->SetWatermarkText(_('Cancelled'));
    $mpdf->showWatermarkText  = true;
    $mpdf->watermark_font     = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.06;
} else {
    if ($purchase_order->get('State Index') < 30) {
        $mpdf->SetWatermarkText(_('Preview'));
        $mpdf->showWatermarkText  = true;
        $mpdf->watermark_font     = 'DejaVuSansCondensed';
        $mpdf->watermarkTextAlpha = 0.06;
    }
}

$where = sprintf(
    ' where POTF.`Purchase Order Key`=%d',
    $purchase_order->id
);


$table = "
  `Purchase Order Transaction Fact` POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join `Supplier Dimension` S on (`Supplier Part Supplier Key`=S.`Supplier Key`)
";


$fields = "`Supplier Preferred Contact Number Formatted Number`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton`,`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted Units`,`Supplier Part Description`,
    `Note to Supplier`,`Purchase Order Submitted Unit Cost`,`Supplier Part Reference`,`Part Reference`,S.`Supplier Key`,`Supplier Name`

";


$sql = "select $fields from $table $where  order by `Supplier Code`,S.`Supplier Key`,`Supplier Part Reference` limit 10000";


$adata = array();

$supplier_key = 0;

if ($result = $db->query($sql)) {
    foreach ($result as $data) {
        if ($supplier_key != $data['Supplier Key']) {
            $supplier_key = $data['Supplier Key'];

            //  $supplier=get_object('Supplier',$supplier_key);

            $adata[] = array(
                'type'      => 'supplier',
                'name'      => $data['Supplier Name'],
                'telephone' => $data['Supplier Preferred Contact Number Formatted Number']


            );
        }


        if ($data['Purchase Order Submitted Units Per SKO'] == 0) {
            continue;
        }


        $units_per_carton = $data['Purchase Order Submitted Units Per SKO'] * $data['Purchase Order Submitted SKOs Per Carton'];


        $items_qty = $data['Purchase Order Submitted Units'].'<span class="small discreet">u.</span> | ';

        if ($data['Purchase Order Submitted Units Per SKO'] != 1) {
            if ($data['Purchase Order Submitted Units'] % $data['Purchase Order Submitted Units Per SKO'] != 0) {
                $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $data['Purchase Order Submitted Units Per SKO'], 3).'<span class="small discreet">sko.</span></span> | ';
            } else {
                $items_qty .= number($data['Purchase Order Submitted Units'] / $data['Purchase Order Submitted Units Per SKO'], 3).'<span class="small discreet">pks.</span> | ';
            }
        }
        if ($data['Purchase Order Submitted SKOs Per Carton'] != 1) {
            if ($data['Purchase Order Submitted Units'] % ($data['Purchase Order Submitted Units Per SKO'] * $data['Purchase Order Submitted SKOs Per Carton']) != 0) {
                $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $data['Purchase Order Submitted Units Per SKO'] / $data['Purchase Order Submitted SKOs Per Carton'], 3).'<span title="'._(
                        'Cartons'
                    ).'" class="small discreet">C.</span></span>';
            } else {
                $items_qty .= number($data['Purchase Order Submitted Units'] / $data['Purchase Order Submitted Units Per SKO'] / $data['Purchase Order Submitted SKOs Per Carton'], 3).'<span title="'._('Cartons').'" class="small discreet">C.</span>';
            }
        }

        $items_qty = preg_replace('/\|\s$/', '', $items_qty);


        $description = $data['Supplier Part Description'];
        $description .= '<span style="font-style: italic;font-size:90%">';


        if ($data['Purchase Order Submitted Units Per SKO'] > 1) {
            $description .= '<br>'.sprintf(
                    _("packed in %d's"),
                    $data['Purchase Order Submitted Units Per SKO']
                );
            $description .= ', '.sprintf(
                    ngettext(
                        '%s unit per carton',
                        '%s units per carton',
                        $units_per_carton
                    ),
                    number($units_per_carton),
                    number($units_per_carton)
                );
            $description .= ', '.sprintf(
                    ngettext(
                        '%s pack per carton',
                        '%s packs per carton',
                        $data['Purchase Order Submitted SKOs Per Carton']
                    ),
                    number($data['Purchase Order Submitted SKOs Per Carton']),
                    number($data['Purchase Order Submitted SKOs Per Carton'])
                );
        } elseif ($units_per_carton > 1) {
            $description .= '<br>'.sprintf(
                    ngettext(
                        '%s unit per carton',
                        '%s units per carton',
                        $units_per_carton
                    ),
                    number($units_per_carton),
                    number($units_per_carton)
                );
        }
        $description .= '</span>';


        if ($data['Note to Supplier'] != '') {
            $description .= '<div><b>'.$data['Note to Supplier'].'</b></div>';
        }

        $amount = '';
        if ($purchase_order->get('Purchase Order Currency Code') == 'IDR') {
            if (preg_match('/0000$/', $data['Purchase Order Submitted Unit Cost'])) {
                $unit_cost = money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'), false, 'NO_FRACTION_DIGITS');
            } elseif (preg_match('/00$/', $data['Purchase Order Submitted Unit Cost'])) {
                $unit_cost = money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));
            } else {
                $unit_cost = money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'), 'en_US', 'FOUR_FRACTION_DIGITS');
            }

            $_amount = $data['Purchase Order Submitted Units'] * $data['Purchase Order Submitted Unit Cost'];

            if (preg_match('/00$/', $_amount)) {
                $amount = money($_amount, $purchase_order->get('Purchase Order Currency Code'), false, 'NO_FRACTION_DIGITS');
            }
        } else {
            if (preg_match('/00$/', $data['Purchase Order Submitted Unit Cost'])) {
                $unit_cost = money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));
            } else {
                $unit_cost = money($data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'), 'en_US', 'FOUR_FRACTION_DIGITS');
            }
            $amount = money($data['Purchase Order Submitted Units'] * $data['Purchase Order Submitted Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));
        }


        $reference = $data['Supplier Part Reference'];
        if ($data['Supplier Part Reference'] != $data['Part Reference']) {
            $reference .= '<br>'.$data['Part Reference'];
        }

        $adata[] = array(
            'type' => 'item',

            'reference'   => $reference,
            'description' => $description,
            'ordered'     => $items_qty,
            'unit_cost'   => $unit_cost,
            'amount'      => $amount


        );
    }
}

if ($purchase_order->get('Purchase Order Currency Code') == 'IDR') {
    if (preg_match('/00$/', $purchase_order->get('Purchase Order Items Net Amount'))) {
        $smarty->assign('total_items_amount', money($purchase_order->get('Purchase Order Items Net Amount'), $purchase_order->get('Purchase Order Currency Code'), false, 'NO_FRACTION_DIGITS'));
    } else {
        $smarty->assign('total_items_amount', $purchase_order->get('Items Net Amount'));
    }
} else {
    $smarty->assign('total_items_amount', $purchase_order->get('Items Net Amount'));
}
$smarty->assign('transactions', $adata);


$html = $smarty->fetch('supplier.order.pdf.tpl');


$mpdf->WriteHTML($html);
$mpdf->Output($purchase_order->get('Public ID').'.pdf', 'I');

