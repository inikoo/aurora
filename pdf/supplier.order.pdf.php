<?php
chdir('../');

require_once __DIR__.'/../vendor/autoload.php';


include_once 'common.php';
include_once 'utils/natural_language.php';
include_once 'utils/object_functions.php';
require_once 'utils/table_functions.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$purchase_order = get_object('Purchase_Order', $id);
if (!$purchase_order->id) {
    exit;
}


$mpdf = new \Mpdf\Mpdf(
    [
        'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
        'mode'          => 'utf-8',
        'margin_left'   => 20,
        'margin_right'  => 15,
        'margin_top'    => 38,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]
);


//$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(
    sprintf(_('Purchase order %s'), $purchase_order->get('Public ID'))
);
$mpdf->SetAuthor($account->get('Name'));


if (isset($_REQUEST['print'])) {
    $mpdf->SetJS('this.print();');
}

$smarty->assign('purchase_order', $purchase_order);


//'InProcess','Submitted','Inputted','Dispatched','Received','Checked','Placed','Cancelled'

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

$_data = array(

    'parameters' => array(
        'tab'        => 'supplier.order.items',
        'parent'     => 'purchase_order',
        'parent_key' => $purchase_order->id,
        'f_field'    => 'code',
    ),
    'nr'         => 1000000,
    'page'       => 1
);

$dont_save_table_state = true;


$group_by = '';
include_once 'prepare_table/init.php';


$sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


$adata = array();



if ($result = $db->query($sql)) {
    foreach ($result as $data) {


        if($data['Part Units Per Package']==0){
            continue;
        }


        $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


        $items_qty = $data['Purchase Order Submitted Units'].'<span class="small discreet">u.</span> | ';

        if ($data['Part Units Per Package'] != 1   ) {

            if ($data['Purchase Order Submitted Units'] % $data['Part Units Per Package'] != 0) {
                $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $data['Part Units Per Package'], 3).'<span class="small discreet">sko.</span></span> | ';

            } else {
                $items_qty .= number($data['Purchase Order Submitted Units'] / $data['Part Units Per Package'], 3).'<span class="small discreet">pks.</span> | ';

            }
        }
        if ($data['Supplier Part Packages Per Carton'] != 1) {

            if ($data['Purchase Order Submitted Units'] % ($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton']) != 0) {
                $items_qty .= '<span class="error">'.number($data['Purchase Order Submitted Units'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton'], 3).'<span title="'._('Cartons').'" class="small discreet">C.</span></span>';

            } else {
                $items_qty .= number($data['Purchase Order Submitted Units'] / $data['Part Units Per Package'] / $data['Supplier Part Packages Per Carton'], 3).'<span title="'._('Cartons').'" class="small discreet">C.</span>';

            }
        }

        $items_qty=preg_replace('/\|\s$/','',$items_qty);


        $description = $data['Supplier Part Description'];
        $description .= '<span style="font-style: italic;font-size:90%">';



        if ($data['Part Units Per Package'] > 1) {
            $description .= '<br>'.sprintf(
                    _("packed in %d's"), $data['Part Units Per Package']
                );
            $description .= ', '.sprintf(
                    ngettext(
                        '%s unit per carton', '%s units per carton', $units_per_carton
                    ), number($units_per_carton), number($units_per_carton)
                );
            $description .= ', '.sprintf(
                    ngettext(
                        '%s pack per carton', '%s packs per carton', $data['Supplier Part Packages Per Carton']
                    ), number($data['Supplier Part Packages Per Carton']), number($data['Supplier Part Packages Per Carton'])
                );


        } elseif($units_per_carton>1) {

            $description .= '<br>'.sprintf(
                    ngettext(
                        '%s unit per carton', '%s units per carton', $units_per_carton
                    ), number($units_per_carton), number($units_per_carton)
                );

        }
        $description .= '</span>';


        if($data['Note to Supplier']!=''){

            $description.='<div><b>'.$data['Note to Supplier'].'</b></div>';
        }


        if(preg_match('/00$/',$data['Supplier Part Unit Cost'])){
            $unit_cost = money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));

        }else{
            $unit_cost = money($data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code'),'en_US','FOUR_FRACTION_DIGITS');

        }



        $amount = money($data['Purchase Order Submitted Units'] * $data['Supplier Part Unit Cost'], $purchase_order->get('Purchase Order Currency Code'));



        $reference=$data['Supplier Part Reference'];
        if($data['Supplier Part Reference']!=$data['Part Reference']){
            $reference.='<br>'.$data['Part Reference'];
        }

        $adata[] = array(

            'reference'   => $reference,
            'description' => $description,
            'ordered'     => $items_qty,
            'unit_cost'   => $unit_cost,
            'amount'      => $amount


        );


    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$smarty->assign('transactions', $adata);

$html = $smarty->fetch('supplier.order.pdf.tpl');



$mpdf->WriteHTML($html);
$mpdf->Output($purchase_order->get('Public ID').'.pdf', 'I');

