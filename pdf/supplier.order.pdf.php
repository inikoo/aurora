<?php
chdir('../');


include_once 'common.php';
include_once 'utils/natural_language.php';
include_once 'utils/object_functions.php';
require_once 'utils/table_functions.php';

include "external_libs/mpdf/mpdf.php";

$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$purchase_order = get_object('Purchase_Order', $id);
if (!$purchase_order->id) {
    exit;
}


$mpdf = new mPDF('win-1252', 'A4', '', '', 20, 15, 38, 25, 10, 10);

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

        $description = $data['Part Unit Description'].' @'.money(
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
                '<i key="%d" class="invisible fa fa-fw fa-square-o button" aria-hidden="true"></i>', $data['Purchase Order Transaction Fact Key']
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


require_once 'class.Store.php';

require_once 'class.Invoice.php';
include_once 'class.Payment.php';
include_once 'class.Payment_Account.php';
include_once 'class.Payment_Service_Provider.php';

require_once 'utils/geography_functions.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$invoice = new Invoice($id);
if (!$invoice->id) {
    exit;
}


//$invoice->update_tax();


//print_r($invoice);
$store    = new Store($invoice->data['Invoice Store Key']);
$customer = new Customer($invoice->data['Invoice Customer Key']);


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL, $store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


$order_key = 0;
$dn_key    = 0;


$number_orders = $invoice->get_number_orders();


if ($number_orders == 1) {
    $orders = $invoice->get_orders_objects();
    $order  = array_pop($orders);
    $smarty->assign('order', $order);
}
$number_dns = $invoice->get_number_delivery_notes();
if ($number_dns == 1) {
    $delivery_notes = $invoice->get_delivery_notes_objects();
    $delivery_note  = array_pop($delivery_notes);
    $smarty->assign('delivery_note', $delivery_note);
}


$smarty->assign('number_orders', $number_orders);
$smarty->assign('number_dns', $number_dns);


include "external_libs/mpdf/mpdf.php";
$mpdf = new mPDF('win-1252', 'A4', '', '', 20, 15, 38, 25, 10, 10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Invoice').' '.$invoice->data['Invoice Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);


if ($invoice->data['Invoice Paid'] == 'Yes') {
    $mpdf->SetWatermarkText(_('Paid'));
    $mpdf->showWatermarkText  = true;
    $mpdf->watermark_font     = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.03;
}

//$mpdf->SetDisplayMode('fullpage');


if (isset($_REQUEST['print'])) {
    $mpdf->SetJS('this.print();');
}
$smarty->assign('store', $store);

$smarty->assign('invoice', $invoice);


if ($invoice->data['Invoice Type'] == 'Invoice') {
    $smarty->assign('label_title', _('Invoice'));
    $smarty->assign('label_title_no', _('Invoice No.'));

} elseif ($invoice->data['Invoice Type'] == 'CreditNote') {
    $smarty->assign('label_title', _('Credit Note'));
    $smarty->assign('label_title_no', _('Credit Note No.'));

} else {
    $smarty->assign('label_title', _('Refund'));
    $smarty->assign('label_title_no', _('Refund No.'));
}


if (in_array(
    $invoice->data['Invoice Delivery Country Code'], get_countries_EC_Fiscal_VAT_area()
)) {
    $print_tariff_code = false;
} else {
    $print_tariff_code = true;
}


$transactions = array();
$sql          = sprintf(
    "SELECT
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product History XHTML Short Description`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code`
 FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN
  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=%d ORDER BY `Product Code`", $invoice->id
);
//print $sql;exit;
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {


    $row['Net'] = money(
        ($row['Invoice Transaction Gross Amount'] - $row['Invoice Transaction Total Discount Amount']), $row['Invoice Currency Code']
    );

    $row['Tax']    = money(
        ($row['Invoice Transaction Item Tax Amount']), $row['Invoice Currency Code']
    );
    $row['Amount'] = money(
        ($row['Invoice Transaction Gross Amount'] - $row['Invoice Transaction Total Discount Amount'] + $row['Invoice Transaction Item Tax Amount']), $row['Invoice Currency Code']
    );


    $discount = ($row['Invoice Transaction Total Discount Amount'] == 0
        ? ''
        : percentage(
            $row['Invoice Transaction Total Discount Amount'], $row['Invoice Transaction Gross Amount'], 0
        ));

    $units    = $row['Product Units Per Case'];
    $name     = $row['Product History Name'];
    $price    = $row['Product History Price'];
    $currency = $row['Product Currency'];

    $desc = '';
    if ($units > 1) {
        $desc = number($units).'x ';
    }
    $desc .= ' '.$name;
    if ($price > 0) {
        $desc .= ' ('.money($price, $currency, $_locale).')';
    }

    $description = $desc;

    if ($discount != '') {
        $description .= ' '._('Discount').':'.$discount;
    }

    if ($row['Product RRP'] != 0) {
        $description .= ' <br>'._('RRP').': '.money(
                $row['Product RRP'], $row['Invoice Currency Code']
            );
    }

    if ($print_tariff_code and $row['Product Tariff Code'] != '') {
        $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
    }

    $row['Product XHTML Short Description'] = $description;


    $transactions[] = $row;

}


$transactions_no_products = array();


if ($invoice->data['Invoice Net Amount Off']) {

    $tax_category = new TaxCategory($invoice->data['Invoice Tax Code']);

    $net   = -1 * $invoice->data['Invoice Net Amount Off'];
    $tax   = $net * $tax_category->data['Tax Category Rate'];
    $total = $net + $tax;


    $row['Product Code']                    = _('Amount Off');
    $row['Product XHTML Short Description'] = '';
    $row['Net']                             = money(
        $net, $row['Currency Code']
    );
    $row['Tax']                             = money(
        $tax, $row['Currency Code']
    );
    $row['Amount']                          = money(
        $total, $row['Currency Code']
    );

    $row['Discount']            = '';
    $transactions_no_products[] = $row;

}


$sql            = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d", $invoice->id
);
$result         = mysql_query($sql);
$total_gross    = 0;
$total_discount = 0;
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    switch ($row['Transaction Type']) {
        case('Credit'):
            $code = _('Credit');
            break;
        case('Refund'):
            $code = _('Refund');
            break;
        case('Shipping'):
            $code = _('Shipping');
            break;
        case('Charges'):
            $code = _('Charges');
            break;
        case('Adjust'):
            $code = _('Adjust');
            break;
        case('Other'):
            $code = _('Other');
            break;
        case('Deal'):
            $code = _('Deal');
            break;
        case('Insurance'):
            $code = _('Insurance');
            break;
        default:
            $code = $row['Transaction Type'];


    }
    $transactions_no_products[] = array(

        'Product Code'                    => $code,
        'Product XHTML Short Description' => $row['Transaction Description'],
        'Invoice Quantity'                => '',
        'Net'                             => money(
            $row['Transaction Invoice Net Amount'], $row['Currency Code']
        ),
        'Tax'                             => money(
            $row['Transaction Invoice Tax Amount'], $row['Currency Code']
        ),

        'Amount' => money(
            $row['Transaction Invoice Net Amount'] + $row['Transaction Invoice Tax Amount'], $row['Currency Code']
        )
    );
}


$sql = sprintf(
    "SELECT `Product History XHTML Short Description`,`Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Refund Quantity`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Refund Key`=%d ORDER BY `Product Code`",
    $invoice->id
);
//print $sql;exit;
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $row['Amount'] = money(
        ($row['Invoice Transaction Net Refund Items']), $row['Invoice Currency Code']
    );
    if ($row['Invoice Transaction Net Refund Items'] == 0) {
        $row['Amount'] .= '<br><span style="font-size:80%">'._('Tax').': '.money(
                ($row['Invoice Transaction Tax Refund Items']), $row['Invoice Currency Code']
            ).'</span>';
    }


    $row['Discount']         = '';
    $row['Invoice Quantity'] = $row['Refund Quantity'];
    if ($row['Product RRP'] != 0) {
        $row['Product XHTML Short Description']
            = $row['Product History XHTML Short Description'].'<br>'._('RRP').': '.money($row['Product RRP'], $row['Invoice Currency Code']);
    }

    if ($print_tariff_code and $row['Product Tariff Code'] != '') {
        $row['Product XHTML Short Description']
            = $row['Product History XHTML Short Description'].'<br>'._(
                'Tariff Code'
            ).': '.$row['Product Tariff Code'];
    }

    $transactions[] = $row;

}

$sql    = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit' ", $invoice->id
);
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

    $row['Product Code']                    = _('Credit');
    $row['Product XHTML Short Description'] = $row['Transaction Description'];
    $row['Net']                             = money(
        ($row['Transaction Refund Net Amount']), $row['Currency Code']
    );
    $row['Tax']                             = money(
        ($row['Transaction Refund Tax Amount']), $row['Currency Code']
    );
    $row['Amount']                          = money(
        ($row['Transaction Refund Net Amount'] + $row['Transaction Refund Tax Amount']), $row['Currency Code']
    );

    $row['Discount'] = '';
    $transactions[]  = $row;


}


$transactions_out_of_stock = array();
$sql                       = sprintf(
    "SELECT `Product History XHTML Short Description`,(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) AS qty,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` FROM `Order Transaction Fact` O
 LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`)
 LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`)

  WHERE `Invoice Key`=%d AND (`No Shipped Due Out of Stock`>0  OR  `No Shipped Due No Authorized`>0 OR `No Shipped Due Not Found`>0 OR `No Shipped Due Other` )  ORDER BY `Product Code`", $invoice->id
);
//print $sql;exit;
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $row['Amount']   = '';
    $row['Discount'] = '';

    if ($row['Product RRP'] != 0) {
        $row['Product XHTML Short Description']
            = $row['Product History XHTML Short Description'].'<br>'._('RRP').': '.money($row['Product RRP'], $row['Invoice Currency Code']);
    }

    $row['Quantity']             = '<span >('.$row['qty'].')</span>';
    $transactions_out_of_stock[] = $row;

}


$smarty->assign(
    'number_transactions_out_of_stock', count($transactions_out_of_stock)
);

$smarty->assign('transactions_out_of_stock', $transactions_out_of_stock);

$smarty->assign('transactions_no_products', $transactions_no_products);


if ($invoice->data['Invoice Type'] == 'CreditNote') {

    $sql = sprintf(
        "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d ", $invoice->id
    );
    //print $sql;exit;
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        switch ($row['Transaction Type']) {
            case('Credit'):
                $code = _('Credit');
                break;
            case('Refund'):
                $code = _('Refund');
                break;
            case('Shipping'):
                $code = _('Shipping');
                break;
            case('Charges'):
                $code = _('Charges');
                break;
            case('Adjust'):
                $code = _('Adjust');
                break;
            case('Other'):
                $code = _('Other');
                break;
            case('Deal'):
                $code = _('Deal');
                break;
            case('Insurance'):
                $code = _('Insurance');
                break;
            default:
                $code = $row['Transaction Type'];


        }
        $row['Product Code'] = $code;
        $row['Product XHTML Short Description']
                             = $row['Transaction Description'];
        $row['Amount']       = money(
            ($row['Transaction Invoice Net Amount']), $row['Currency Code']
        );
        $row['Discount']     = '';
        $transactions[]      = $row;


    }
}


$smarty->assign('transactions', $transactions);


$tax_data = array();
$sql      = sprintf(
    "SELECT `Tax Category Name`,`Tax Category Rate`,`Tax Amount` FROM  `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Tax Code`)  WHERE B.`Invoice Key`=%d ",
    $invoice->id
);

$res = mysql_query($sql);
while ($row = mysql_fetch_assoc($res)) {

    if ($row['Tax Amount'] == 0) {
        continue;
    }

    switch ($row['Tax Category Name']) {
        case 'Outside the scope of VAT':
            $tax_category_name = _('Outside the scope of VAT');
            break;
        case 'VAT 17.5%':
            $tax_category_name = _('VAT').' 17.5%';
            break;
        case 'VAT 20%':
            $tax_category_name = _('VAT').' 20%';
            break;
        case 'VAT 15%':
            $tax_category_name = _('VAT').' 15%';
            break;
        case 'No Tax':
            $tax_category_name = _('No Tax');
            break;
        case 'RE (5,2%)':
            $tax_category_name = 'RE 5,2%';
            break;
        case 'Exempt from VAT':
            $tax_category_name = _('Exempt from VAT');
            break;


        default:
            $tax_category_name = $row['Tax Category Name'];
    }


    $tax_data[] = array(
        'name'   => $tax_category_name,
        'amount' => money(
            $row['Tax Amount'], $invoice->data['Invoice Currency']
        )
    );
}


$smarty->assign('tax_data', $tax_data);


if ($account->data['Apply Tax Method'] == 'Per Item') {
    $html = $smarty->fetch('invoice_tax_disaggregated.pdf.tpl');

} else {
    $html = $smarty->fetch('invoice.pdf.tpl');

}


$mpdf->WriteHTML($html);
//$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
$mpdf->Output();


?>
