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




$mpdf->useOnlyCoreFonts = true;    // false is default
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
        'f_field'       => 'code',
    ),
    'nr'         => 1000000,
    'page'       => 1
);

$dont_save_table_state = true;


$group_by = '';
include_once 'prepare_table/init.php';


$sql
    = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


$adata = array();

$exchange = -1;

$adata = array();

if ($result = $db->query($sql)) {
    foreach ($result as $data) {


        $quantity = number($data['Purchase Order Quantity']);


        $units_per_carton = $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'];


        $subtotals = sprintf('<span  class="subtotals" >');
        if ($data['Purchase Order Quantity'] > 0) {


            $amount = money(
                $data['Purchase Order Quantity'] * $units_per_carton * $data['Supplier Part Historic Unit Cost'], $purchase_order->get('Purchase Order Currency Code')
            );


            $subtotals .= sprintf(
                _("%d units"), ($data['Purchase Order Quantity'] * $data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'])
            );

            if ($data['Part Package Weight'] > 0) {
                $subtotals .= ' '.weight(
                        $data['Part Package Weight'] * $data['Purchase Order Quantity'] * $data['Supplier Part Packages Per Carton']
                    );
            }
            if ($data['Supplier Part Carton CBM'] > 0) {
                $subtotals .= ' '.number(
                        $data['Purchase Order Quantity'] * $data['Supplier Part Carton CBM']
                    ).' m³';
            }
        }
        $subtotals .= '</span>';


        if (!$data['Supplier Delivery Key']) {

            $delivery_qty = $data['Purchase Order Quantity'];

            $delivery_quantity = sprintf(
                '<span class="delivery_quantity" id="delivery_quantity_%d" key="%d" item_key="%d" item_historic_key=%d on="1" ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-minus fa-fw button" aria-hidden="true"></i></span>',
                $data['Purchase Order Transaction Fact Key'], $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $delivery_qty + 0,
                $delivery_qty + 0
            );
        } else {
            $delivery_quantity = number($data['Supplier Delivery Quantity']);

        }

        $description = $data['Supplier Part Description'].' @'.money(
                $data['Supplier Part Historic Unit Cost'], $purchase_order->get('Purchase Order Currency Code')
            );
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


        } else {

            $description .= '<br>'.sprintf(
                    ngettext(
                        '%s unit per carton', '%s units per carton', $units_per_carton
                    ), number($units_per_carton), number($units_per_carton)
                );

        }
        $description .= '</span>';


        $adata[] = array(

            'id'                => (integer)$data['Purchase Order Transaction Fact Key'],
            'item_index'        => $data['Purchase Order Item Index'],
            'parent_key'        => $purchase_order->get(
                'Purchase Order Parent Key'
            ),
            'parent_type'       => strtolower(
                $purchase_order->get('Purchase Order Parent')
            ),
            'supplier_part_key' => (integer)$data['Supplier Part Key'],
            'supplier_key'      => (integer)$data['Supplier Key'],
            'checkbox'          => sprintf(
                '<i key="%d" class="invisible far fa-square fa-fw button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']
            ),
            'operations'        => sprintf(
                '<i key="%d" class="fa fa-fw fa-truck fa-flip-horizontal button" aria-hidden="true" onClick="change_on_delivery(this)"></i>', $data['Purchase Order Transaction Fact Key']
            ),
            'reference'         => $data['Supplier Part Reference'],
            'description'       => $description,
            'quantity'          => sprintf(
                '<span    data-settings=\'{"field": "Purchase Order Quantity", "transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_qty width_50" value="%s" ovalue="%s"> <i onClick="save_item_qty_change(this)" class="fa  fa-plus fa-fw button" aria-hidden="true"></i></span>',
                $data['Purchase Order Transaction Fact Key'], $data['Supplier Part Key'], $data['Supplier Part Historic Key'], $data['Purchase Order Quantity'] + 0,
                $data['Purchase Order Quantity'] + 0
            ),
            'delivery_quantity' => $delivery_quantity,
            'subtotals'         => $subtotals,
            'ordered'           => number($data['Purchase Order Quantity']),
            'supplier_key'      => $data['Supplier Key'],
            'supplier'          => $data['Supplier Code'],
            'amount'            => $amount


        );


    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$smarty->assign('transactions', $adata);

$html = $smarty->fetch('supplier.order.pdf.tpl');
$mpdf->WriteHTML($html);
$mpdf->Output();



?>
