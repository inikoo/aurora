<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 03 November 2019  21:19::14  +0800 Plane Bangkok-Oslo (Zadonk)

 Copyright (c) 2019, Inikoo

 Version 2.0
*/

/** @var \Smarty $smarty */
/** @var \Account $account */
/** @var PDO $db */


use CommerceGuys\Addressing\Country\CountryRepository;
use Mpdf\Mpdf;


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}

/**
 * @var $invoice \Invoice
 */
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


if (!empty($_REQUEST['pro_mode'])) {
    $pro_mode = true;
} else {
    $pro_mode = false;
}


if (!empty($_REQUEST['commodity'])) {
    $print_tariff_code = true;
} else {
    $print_tariff_code = false;
}


if (!empty($_REQUEST['barcode'])) {
    $print_barcode = true;
} else {
    $print_barcode = false;
}


if (!empty($_REQUEST['parts'])) {
    $parts = true;
} else {
    $parts = false;
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


if (!empty($_REQUEST['origin'])) {
    $print_origin = true;
    include_once 'class.Country.php';
    $countryRepository = new CountryRepository();
} else {
    $print_origin = false;
}

if (!empty($_REQUEST['CPNP'])) {
    $print_CPNP = true;
} else {
    $print_CPNP = false;
}


putenv('LC_ALL='.$_locale.'.UTF-8');
setlocale(LC_ALL, $_locale.'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


$number_orders = 0;
$number_dns    = 0;

$order = get_object('Order', $invoice->get('Invoice Order Key'));

if ($order->id) {
    $smarty->assign('order', $order);
    $number_orders = 1;

    $delivery_note = get_object('Delivery_Note', $order->get('Order Delivery Note Key'));


    if ($delivery_note->id) {
        $smarty->assign('delivery_note', $delivery_note);
        $number_dns = 1;

    }

}

$smarty->assign('pro_mode', $pro_mode);
$smarty->assign('customer', $customer);
$smarty->assign('number_orders', $number_orders);
$smarty->assign('number_dns', $number_dns);


$mpdf = new Mpdf(
    [
        'tempDir'       => 'server_files/pdf_tmp',
        'mode'          => 'utf-8',
        'margin_left'   => 10,
        'margin_right'  => 10,
        'margin_top'    => 38,
        'margin_bottom' => 25,
        'margin_header' => 10,
        'margin_footer' => 10
    ]
);


$mpdf->SetTitle(_('Invoice').' '.$invoice->data['Invoice Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);


if ($invoice->data['Invoice Paid'] == 'Yes') {
    $mpdf->SetWatermarkText(_('Paid'));
    $mpdf->showWatermarkText  = true;
    $mpdf->watermark_font     = 'DejaVuSansCondensed';
    $mpdf->watermarkTextAlpha = 0.03;
}


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
    $original_invoice = get_object('Invoice', $order->get('Order Invoice Key'));
    $smarty->assign('original_invoice', $original_invoice);

} else {


    $original_invoice = get_object('Invoice', $order->get('Order Invoice Key'));
    $smarty->assign('original_invoice', $original_invoice);


    if ($invoice->get('Invoice Tax Type') == 'Tax_Only') {
        $smarty->assign('label_title', _('Tax Refund'));
        $smarty->assign('label_title_no', _('Tax Refund No.'));
    } else {
        $smarty->assign('label_title', _('Refund'));
        $smarty->assign('label_title_no', _('Refund No.'));
    }


}


$transactions = array();


$sql = sprintf(
    "SELECT   `Product CPNP Number`,`Product Barcode Number`,`Product Origin Country Code`,`Delivery Note Quantity` as Qty, `Order Transaction Amount` as Amount, `Product Package Weight`,`Order Transaction Amount`,`Delivery Note Quantity`,`Order Transaction Total Discount Amount`,`Order Transaction Out of Stock Amount`,`Order Currency Code`,`Order Transaction Gross Amount`,
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,`Product History Code` as `Product Code`
 FROM 
 `Order Transaction Fact` OTF  LEFT JOIN `Product History Dimension` PH ON (OTF.`Product Key`=PH.`Product Key`) LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) 
 
 WHERE `Invoice Key`=%d  and `Current Dispatching State`   and (`Order Transaction Amount`!=0 or `Delivery Note Quantity`!=0)  ORDER BY `Product History Code`", $invoice->id
);


//print $sql;exit;


if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $currency = $row['Order Currency Code'];

        $amount        = $row['Amount'];
        $row['Amount'] = money($amount, $currency, $_locale);

        $discount = ($row['Order Transaction Total Discount Amount'] == 0
            ? ''
            : percentage(
                $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'], 0
            ));


        $units = $row['Product Units Per Case'];
        $name  = $row['Product History Name'];
        $price = $row['Product History Price'];


        if ($pro_mode) {

            $desc             = $name;
            $row['Qty_Units'] = $units * $row['Qty'];

            $unit_cost = $amount / $row['Qty_Units'];
            if (preg_match('/0000$/', $unit_cost)) {
                $unit_cost = money($unit_cost, $currency, $_locale, 'NO_FRACTION_DIGITS');

            } elseif (preg_match('/00$/', $unit_cost)) {
                $unit_cost = money($unit_cost, $currency, $_locale);

            } else {
                $unit_cost = money($unit_cost, $currency, $_locale, 'FOUR_FRACTION_DIGITS');

            }


            $row['Unit_Price'] = $unit_cost;

        } else {

            $desc = '';
            if ($units > 1) {
                $desc = number($units).'x ';
            }
            $desc .= ' '.$name;
            if ($price > 0) {
                $desc .= ' ('.money($price, $currency, $_locale).')';
            }
        }


        $description = $desc;


        if ($row['Product RRP'] != 0 and $print_rrp) {
            $description .= ' <br>'._('RRP').': '.money($row['Product RRP'], $row['Order Currency Code']);
        }

        if ($row['Product Package Weight'] != 0 and $print_weight) {
            $description .= ' <br>'._('Weight').': '.weight($row['Product Package Weight']);
        }

        if ($row['Product Origin Country Code'] != '' and $print_origin) {

            $_country = new Country('code', $row['Product Origin Country Code']);


            if ($_country->id and $_country->get('Country 2 Alpha Code') != 'XX') {
                try {
                    $country     = $countryRepository->get($_country->get('Country 2 Alpha Code'));
                    $description .= ' <br>'._('Origin').': '.$country->getName().' ('.$country->getThreeLetterCode().')';
                } catch (Exception $e) {
                    $description .= ' <br>'._('Origin').': '.$_country->get('Country 2 Alpha Code');
                }


            }


        }

        if ($print_tariff_code and $row['Product Tariff Code'] != '') {
            $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
        }

        if ($print_barcode and $row['Product Barcode Number'] != '') {
            $description .= '<br>'._('Barcode').': '.$row['Product Barcode Number'];
        }

        if ($print_CPNP and $row['Product CPNP Number'] != '') {
            $description .= '<br>'._('CPNP').': '.$row['Product CPNP Number'];
        }

        if ($parts) {
            $product = get_object('Product', $row['Product ID']);


            $parts_data = $product->get_parts_data();

            $parts = '';
            if (count($parts_data) > 0) {
                $description .= '<br>';

                foreach ($parts_data as $part_data) {
                    $parts .= ', '.$part_data['Units'].'x '.$part_data['Part Name'];
                }

                $description .= preg_replace('/\, /', '', $parts);
            }


        }


        $row['Description'] = $description;

        $row['Discount'] = $discount;


        $transactions[] = $row;
    }
}

$transactions_no_products = array();


if ($invoice->data['Invoice Net Amount Off']) {


    $tax_category = get_object('Tax_Category', $invoice->data['Invoice Tax Code']);

    $net   = -1 * $invoice->data['Invoice Net Amount Off'];
    $tax   = $net * $tax_category->data['Tax Category Rate'];
    $total = $net + $tax;


    $row['Product Code'] = _('Amount Off');
    $row['Description']  = '';
    $row['Net']          = money($net, $invoice->get('Currency Code'));
    $row['Tax']          = money($tax, $invoice->get('Currency Code'));
    $row['Amount']       = money($total, $invoice->get('Currency Code'));

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

            'Product Code' => $code,
            'Description'  => $row['Transaction Description'],
            'Net'          => money($row['Transaction Invoice Net Amount'], $row['Currency Code']),
            'Tax'          => money($row['Transaction Invoice Tax Amount'], $row['Currency Code']),

            'Amount' => money($row['Transaction Invoice Net Amount'] + $row['Transaction Invoice Tax Amount'], $row['Currency Code'])
        );
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
        $row['Product Code'] = _('Credit');
        $row['Description']  = $row['Transaction Description'];
        $row['Net']          = money($row['Transaction Refund Net Amount'], $row['Currency Code']);
        $row['Tax']          = money($row['Transaction Refund Tax Amount'], $row['Currency Code']);
        $row['Amount']       = money(($row['Transaction Refund Net Amount'] + $row['Transaction Refund Tax Amount']), $row['Currency Code']);

        $row['Discount'] = '';
        $row['Qty']      = '';
        $transactions[]  = $row;
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$transactions_out_of_stock = array();
$sql                       = sprintf(
    "SELECT (`No Shipped Due Out of Stock`) AS qty,`Product RRP`,`Product Barcode Number`,`Product History Code` as `Product Code`,
`Product Tariff Code`,`Product Tariff Code`,`Product Origin Country Code`,`Product Package Weight`,P.`Product ID`,`Product History Code` ,`Product Units Per Case`,`Product History Name`,`Product History Price`,`Product Currency`
FROM `Order Transaction Fact` O
 LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`)
 LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`)

  WHERE    `Invoice Key`=%d   and   (`No Shipped Due Out of Stock`>0   )  ORDER BY `Product History Code`", $invoice->id
);
//print $sql;exit;


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $row['Amount']   = '';
        $row['Discount'] = '';


        $units    = $row['Product Units Per Case'];
        $name     = $row['Product History Name'];
        $price    = $row['Product History Price'];
        $currency = $row['Product Currency'];


        if ($pro_mode) {

            $desc = $name;
            if ($price > 0) {

                $unit_cost = $price / $units;


                if (preg_match('/0000$/', $unit_cost)) {
                    $unit_cost = money($unit_cost, $currency, $_locale, 'NO_FRACTION_DIGITS');

                } elseif (preg_match('/00$/', $unit_cost)) {
                    $unit_cost = money($unit_cost, $currency, $_locale);

                } else {
                    $unit_cost = money($unit_cost, $currency, $_locale, 'FOUR_FRACTION_DIGITS');

                }


                $desc .= ' ('.$unit_cost.')';
            }
            $row['Quantity'] = '<span >('.number($row['qty'] * $units, 3).' '.ngettext('unit', 'units', $row['qty'] * $units).')</span>';

        } else {

            $desc = '';
            if ($units > 1) {
                $desc = number($units).'x ';
            }
            $desc .= ' '.$name;
            if ($price > 0) {
                $desc .= ' ('.money($price, $currency, $_locale).')';
            }
            $row['Quantity'] = '<span >('.number($row['qty'], 3).')</span>';

        }


        $description = $desc;


        if ($row['Product RRP'] != 0 and $print_rrp) {
            $description .= ' <br>'._('RRP').': '.money($row['Product RRP'], $row['Product Currency']);
        }

        if ($row['Product Package Weight'] != 0 and $print_weight) {
            $description .= ' <br>'._('Weight').': '.weight($row['Product Package Weight']);
        }

        if ($row['Product Origin Country Code'] != '' and $print_origin) {

            $_country = new Country('code', $row['Product Origin Country Code']);


            if ($_country->id and $_country->get('Country 2 Alpha Code') != 'XX') {
                try {
                    $country     = $countryRepository->get($_country->get('Country 2 Alpha Code'));
                    $description .= ' <br>'._('Origin').': '.$country->getName().' ('.$country->getThreeLetterCode().')';
                } catch (Exception $e) {
                    $description .= ' <br>'._('Origin').': '.$_country->get('Country 2 Alpha Code');
                }


            }


        }

        if ($print_tariff_code and $row['Product Tariff Code'] != '') {
            $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
        }

        if ($print_barcode and $row['Product Barcode Number'] != '') {
            $description .= '<br>'._('Barcode').': '.$row['Product Barcode Number'];
        }


        $row['Description'] = $description;


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
            $row['Product Code'] = $code;
            $row['Description']  = $row['Transaction Description'];
            $row['Amount']       = money($row['Transaction Invoice Net Amount'], $row['Currency Code']);

            $row['Discount'] = '';
            $row['Qty']      = '';
            $transactions[]  = $row;
        }
    }


}


$smarty->assign('transactions', $transactions);


$exempt_tax = false;

$tax_data = array();
$sql      = sprintf(
    "SELECT `Tax Category Name`,`Tax Category Rate`,`Invoice Tax Amount`,`Tax Category Type` FROM  `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Invoice Tax Code`)  WHERE B.`Invoice Tax Invoice Key`=%d  and `Tax Category Country Code`=%s ",
    $invoice->id, prepare_mysql($account->get('Account Country Code'))
);


//print $sql;
//  exit;

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        if ($row['Tax Category Type'] == 'Exempt') {
            $exempt_tax = true;

        }

        if ($row['Invoice Tax Amount'] == 0) {
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
                $row['Invoice Tax Amount'], $invoice->data['Invoice Currency']
            )
        );
    }
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
$html = $smarty->fetch('invoice.pdf.tpl');
$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
$mpdf->WriteHTML($html);
$mpdf->Output($invoice->get('Public ID').'.pdf', 'I');




