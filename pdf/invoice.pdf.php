<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2017 at 15:25:31 CEST , Tranava, Sloavakia

 Copyright (c) 2014, Inikoo

 Version 2.0
*/


chdir('../');


require_once __DIR__.'/../vendor/autoload.php';


require_once 'common.php';

require_once 'utils/object_functions.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$invoice = get_object('Invoice', $id);
if (!$invoice->id) {
    exit;
}

$store    = get_object('Store', $invoice->get('Invoice Store Key'));
$customer = get_object('Customer', $invoice->get('Invoice Customer Key'));


if (!empty($_REQUEST['locale'])) {
    $_locale = $_REQUEST['locale'];
} else {
    $_locale = $store->get('Store Locale');

}

if (!empty($_REQUEST['commodity'])) {
    $print_tariff_code = true;
} else {
    $print_tariff_code = false;
}

if (!empty($_REQUEST['rrp'])) {
    $print_rrp = true;
} else {
    $print_rrp = false;
}

if (!empty($_REQUEST['weight'])) {
    $print_weight = true;
} else {
    $print_weight = false;
}


putenv('LC_ALL='.$_locale.'.UTF-8');
setlocale(LC_ALL, $_locale.'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


$order_key = 0;
$dn_key    = 0;


$number_orders = 1;

if ($number_orders == 1) {
    $order = get_object('Order', $invoice->get('Invoice Order Key'));
    $smarty->assign('order', $order);
}


$delivery_note = get_object('Delivery_Note', $order->get('Order Delivery Note Key'));
$smarty->assign('delivery_note', $delivery_note);

$number_dns=1;

$smarty->assign('number_orders', $number_orders);
$smarty->assign('number_dns', $number_dns);


$mpdf = new \Mpdf\Mpdf(
    [
        'tempDir'       => __DIR__.'/../server_files/pdf_tmp',
        'mode'          => 'utf-8',
        'margin_left'   => 10,
        'margin_right'  => 10,
        'margin_top'    => 38,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]
);


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


$transactions = array();

if ($invoice->get('Invoice Version') == 2) {
    $sql = sprintf(
        "SELECT  `Delivery Note Quantity` as Qty, `Order Transaction Amount` as Amount, `Product Unit Weight`,`Invoice Currency Code`,`Order Transaction Amount`,`Delivery Note Quantity`,`Order Transaction Total Discount Amount`,`Order Transaction Out of Stock Amount`,`Order Currency Code`,`Order Transaction Gross Amount`,
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product History XHTML Short Description`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code`
 FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN
  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=%d  and `Current Dispatching State`   and (`Order Transaction Amount`!=0 or `Delivery Note Quantity`!=0)  ORDER BY `Product Code`", $invoice->id
    );
} else {

    $sql = sprintf(
        "SELECT `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Quantity` as Qty, (`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Item Tax Amount`) as Amount, `Product Unit Weight`,`Invoice Currency Code`,`Order Transaction Amount`,`Delivery Note Quantity`,`Order Transaction Total Discount Amount`,`Order Transaction Out of Stock Amount`,`Order Currency Code`,`Order Transaction Gross Amount`,
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product History XHTML Short Description`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code`
 FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN
  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=%d  and `Current Dispatching State` not in ('Out of Stock in Basket')  and ((`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Item Tax Amount`)!=0 or `Invoice Quantity`!=0)  ORDER BY `Product Code`", $invoice->id
    );
}

//print $sql;exit;




if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $row['Amount'] = money(
            ($row['Amount']), $row['Order Currency Code']
        );

        if ($invoice->get('Invoice Version') == 2) {
            $discount = ($row['Order Transaction Total Discount Amount'] == 0
                ? ''
                : percentage(
                    $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'] - floatval($row['Order Transaction Out of Stock Amount']), 0
                ));

        } else {
            $discount = ($row['Invoice Transaction Total Discount Amount'] == 0 ? '' : percentage($row['Invoice Transaction Total Discount Amount'], $row['Invoice Transaction Gross Amount'], 0));

        }


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


        if ($row['Product RRP'] != 0 and $print_rrp) {
            $description .= ' <br>'._('RRP').': '.money($row['Product RRP'], $row['Order Currency Code']);
        }

        if ($row['Product Unit Weight'] != 0 and $print_weight) {
            $description .= ' <br>'._('Weight').': '.weight($row['Product Unit Weight'] * $row['Product Units Per Case']);
        }

        if ($print_tariff_code and $row['Product Tariff Code'] != '') {
            $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
        }


        $row['Product XHTML Short Description'] = $description;

        $row['Discount'] = $discount;


        $transactions[] = $row;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$transactions_no_products = array();


if ($invoice->data['Invoice Net Amount Off']) {


    $tax_category = get_object('Tax_Category', $invoice->data['Invoice Tax Code']);

    $net   = -1 * $invoice->data['Invoice Net Amount Off'];
    $tax   = $net * $tax_category->data['Tax Category Rate'];
    $total = $net + $tax;


    $row['Product Code']                    = _('Amount Off');
    $row['Product XHTML Short Description'] = '';
    $row['Net']                             = money($net, $invoice->get('Currency Code'));
    $row['Tax']                             = money($tax, $invoice->get('Currency Code'));
    $row['Amount']                          = money($total, $invoice->get('Currency Code'));

    $row['Discount']            = '';
    $transactions_no_products[] = $row;

}


$sql = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d", $invoice->id
);

$total_gross    = 0;
$total_discount = 0;


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
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
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf(
    "SELECT `Product History XHTML Short Description`,`Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Refund Quantity`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Refund Key`=%d ORDER BY `Product Code`",
    $invoice->id
);
//print $sql;exit;

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

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
            $row['Product XHTML Short Description'] = $row['Product History XHTML Short Description'].'<br>'._('RRP').': '.money($row['Product RRP'], $row['Invoice Currency Code']);
        }

        if ($print_tariff_code and $row['Product Tariff Code'] != '') {
            $row['Product XHTML Short Description'] = $row['Product History XHTML Short Description'].'<br>'._(
                    'Tariff Code'
                ).': '.$row['Product Tariff Code'];
        }

        $transactions[] = $row;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit' ", $invoice->id
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
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
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$transactions_out_of_stock = array();
$sql                       = sprintf(
    "SELECT `Product History XHTML Short Description`,(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) AS qty,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Item Tax Amount`,`Invoice Quantity`,`Invoice Transaction Tax Refund Amount`,`Invoice Currency Code`,`Invoice Transaction Net Refund Amount`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` FROM `Order Transaction Fact` O
 LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`)
 LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`)

  WHERE `Invoice Key`=%d AND (`No Shipped Due Out of Stock`>0  OR  `No Shipped Due No Authorized`>0 OR `No Shipped Due Not Found`>0 OR `No Shipped Due Other` )  ORDER BY `Product Code`", $invoice->id
);
//print $sql;exit;


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $row['Amount']   = '';
        $row['Discount'] = '';

        if ($row['Product RRP'] != 0) {
            $row['Product XHTML Short Description'] = $row['Product History XHTML Short Description'].'<br>'._('RRP').': '.money($row['Product RRP'], $row['Invoice Currency Code']);
        }

        $row['Quantity']             = '<span >('.$row['qty'].')</span>';
        $transactions_out_of_stock[] = $row;

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
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


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
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
            $row['Product Code']                    = $code;
            $row['Product XHTML Short Description'] = $row['Transaction Description'];
            $row['Amount']                          = money(
                ($row['Transaction Invoice Net Amount']), $row['Currency Code']
            );
            $row['Discount']                        = '';
            $transactions[]                         = $row;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


$smarty->assign('transactions', $transactions);


$exempt_tax = false;

$tax_data = array();
$sql      = sprintf(
    "SELECT `Tax Category Name`,`Tax Category Rate`,`Tax Amount`,`Tax Category Type` FROM  `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Tax Code`)  WHERE B.`Invoice Key`=%d  and `Tax Category Country Code`=%s ",
    $invoice->id, prepare_mysql($account->get('Account Country Code'))
);


//print $sql;
//  exit;

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        if ($row['Tax Category Type'] == 'Exempt') {
            $exempt_tax = true;

        }

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
                $exempt_tax        = true;

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
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$smarty->assign('tax_data', $tax_data);
$smarty->assign('account', $account);

$extra_comments = '';
if ($account->get('Account Country Code') == 'SVK') {

    if ($exempt_tax) {
        $extra_comments = _('Delivery is exempt from tax according to §43 of Act No. 222/2004 on VAT');

    }


}

$smarty->assign('extra_comments', $extra_comments);

//if ($account->data['Apply Tax Method'] == 'Per Item') {
//    $html = $smarty->fetch('invoice_tax_disaggregated.pdf.tpl');

//} else {
$html = $smarty->fetch('invoice.pdf.tpl');

//}


$mpdf->WriteHTML($html);
//$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
$mpdf->Output();


?>
