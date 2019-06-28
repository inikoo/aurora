<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:09-05-2019 09:49:20 CEST , Tranava, Sloavakia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


require_once 'common.php';

require_once 'utils/object_functions.php';


$european_union_2alpha = array(
    'NL',
    'BE',
    'GB',
    'BG',
    'ES',
    'IE',
    'IT',
    'AT',
    'GR',
    'CY',
    'LV',
    'LT',
    'LU',
    'MT',
    'PT',
    'PL',
    'FR',
    'RO',
    'SE',
    'DE',
    'SK',
    'SI',
    'FI',
    'DK',
    'CZ',
    'HU',
    'EE'
);

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


if($invoice->get('Invoice Currency')=='EUR'){
    $exchange_rate=1;
}else{
    $sql='select *,`ECB Currency Exchange Rate` from kbase.`ECB Currency Exchange Dimension` where `ECB Currency Exchange Date`<? and `ECB Currency Exchange Currency Pair`=? order by `ECB Currency Exchange Date` desc';


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            gmdate('Y-m-d',strtotime($invoice->get('Invoice Date').' +0:00')),
            $invoice->get('Invoice Currency').'EUR'
        )
    );
    if ($row = $stmt->fetch()) {

        $exchange_rate=$row['ECB Currency Exchange Rate'];

    }else{
        $exchange_rate=$invoice->get('Invoice Currency Exchange');
    }





}




if ($account->get('Account Country 2 Alpha Code') == $invoice->get('Invoice Address Country 2 Alpha Code')) {
    $invoice_numeric_code          = 100;
    $invoice_alpha_code            = 'OF';
    $invoice_numeric_code_total    = 100;
    $invoice_numeric_code_shipping = 101;
    $invoice_numeric_code_charges  = 102;

} else {
    $invoice_numeric_code          = 300;
    $invoice_numeric_code_total    = 200;
    $invoice_numeric_code_shipping = 201;
    $invoice_numeric_code_charges  = 202;

    $invoice_alpha_code = 'zOF';
}

if ($invoice->get('Invoice Address Country 2 Alpha Code') == 'SK') {

    if($invoice->get('Order Registration Number')!=''   or $invoice->get('Invoice Tax Number')!=''       ){
        $code_tax = 'A1';

    }else{
        $code_tax = 'D2';

    }


    $code_sum = '03';
} elseif (in_array($invoice->get('Invoice Address Country 2 Alpha Code'), $european_union_2alpha)) {

    if ($invoice->get('Invoice Tax Code') != 'S1') {
        $code_sum = '16';
        $code_tax = 'X';
    } else {
        $code_sum = '03';
        $code_tax = 'A1';
    }


} else {

    if ($invoice->get('Invoice Tax Code') != 'S1') {
        $code_sum = '17t';
        $code_tax = 'X';
    } else {
        $code_sum = '03';
        $code_tax = 'A1';
    }


}

$_total_amount_exchange=round($invoice->get('Invoice Items Net Amount') * $exchange_rate, 2)+round($invoice->get('Invoice Shipping Net Amount') * $exchange_rate, 2)+round($invoice->get('Invoice Charges Net Amount') * $exchange_rate, 2)+round($invoice->get('Invoice Total Tax Amount') * $exchange_rate, 2);


$text = "R00\tT00\r\n";

$invoice_header_data = array(
    'R01',
    $invoice_numeric_code,
    $invoice_alpha_code,
    $invoice_alpha_code,
    $invoice->get('Invoice Public ID'),
    $order->get('Order Public ID'),
    $invoice->get('Invoice Customer Name'),
    $invoice->get('Invoice Registration Number'),
    $invoice->get('Invoice Tax Number'),
    date('d.m.Y', strtotime($invoice->get_date('Invoice Date'))),
    '',
    date('d.m.Y', strtotime($invoice->get_date('Invoice Tax Liability Date'))),
    date('d.m.Y', strtotime($order->get_date('Order Date'))),
    date('d.m.Y', strtotime($order->get_date('Order Date'))),
    $invoice->get('Invoice Currency'),
    1,
    1/$exchange_rate,
    0,
    $invoice->get('Invoice Total Amount'),
    $_total_amount_exchange,
    10,
    20,
    '0.000',
    $invoice->get('Invoice Items Net Amount') + $invoice->get('Invoice Shipping Net Amount') + $invoice->get('Invoice Charges Net Amount'),
    '0.000',
    '0.000',
    '0.000',
    ($invoice->get('Invoice Total Tax Amount') == 0 ? '' : $invoice->get('Invoice Total Tax Amount')),
    ($invoice->get('Invoice Total Tax Amount') == 0 ? '' : '0.000'),
    'Tomášková Andrea',
    '',
    '',
    '',
    '',
    '',
    '',
    '1374',
    '',
    date('H:i:s'),
    '',
    'Total '.$store->get('Code').' '.$invoice->get('Invoice Tax Code'),
    0,
    '',
    '',
    '',
    0,
    0,
    'EJA',
    'José António Erika',
    $store->get('Code'),
    0,
    $store->get('Code'),
    'Tomášková Andrea',
    $invoice->get('Invoice Public ID'),
    '',
    '',
    '/',
    0,
    '',
    '',
    0

);

$invoice_header = "";
foreach ($invoice_header_data as $header_item) {
    $invoice_header .= $header_item."\t";
}
$invoice_header .= "\r\n";


$text .= $invoice_header;





$row_data = array(
    'R02',
    0,
    311,
    200,
    '',
    '',
    $_total_amount_exchange,
    $invoice->get('Invoice Total Amount'),
    $invoice->get('Invoice Customer Name'),
    'S',
    '',
		     '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      '',
		      'X',
		  


);

$invoice_row = "";
foreach ($row_data as $column) {
    $invoice_row .= $column."\t";
}
$invoice_row .= "\r\n";
$text        .= $invoice_row;

$row_data = array(
    'R02',
    0,
    '',
    '',
    604,
    $invoice_numeric_code_total,
    round($invoice->get('Invoice Items Net Amount') * $exchange_rate, 2),
    $invoice->get('Invoice Items Net Amount'),
    'Items '.$store->get('Code').' '.$invoice->get('Invoice Tax Code'),
    $code_sum,
    '',
    '',
    '(Nedefinované)',
    'X',
    '(Nedefinované)',
    'X',
    '(Nedefinované)',
    'X',
    '(Nedefinované)',
    '',
    '',
    '',
    '',
    '',
    '',
    $code_tax,
    '',
    '',
    '',
    0,
    0
);


$invoice_row = "";
foreach ($row_data as $column) {
    $invoice_row .= $column."\t";
}
$invoice_row .= "\r\n";
$text        .= $invoice_row;




if ($invoice->get('Invoice Shipping Net Amount') != 0) {





    $row_data = array(
        'R02',
        0,
        '',
        '',
        604,
        $invoice_numeric_code_shipping,
        round($invoice->get('Invoice Shipping Net Amount') * $exchange_rate, 2),
        $invoice->get('Invoice Shipping Net Amount'),
        'Shipping '.$store->get('Code').' '.$invoice->get('Invoice Tax Code'),
        $code_sum,
        '',
        '',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        '',
        '',
        '',
        '',
        '',
        '',
        $code_tax,
        '',
        '',
        '',
        0,
        0
    );

    $invoice_row = "";
    foreach ($row_data as $column) {
        $invoice_row .= $column."\t";
    }
    $invoice_row .= "\r\n";
    $text        .= $invoice_row;

}


if ($invoice->get('Invoice Charges Net Amount') != 0) {





    $row_data = array(
        'R02',
        0,
        '',
        '',
        604,
        $invoice_numeric_code_charges,
        round($invoice->get('Invoice Charges Net Amount') * $exchange_rate, 2),
        $invoice->get('Invoice Charges Net Amount'),
        'Charges '.$store->get('Code').' '.$invoice->get('Invoice Tax Code'),
        $code_sum,
        '',
        '',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        '',
        '',
        '',
        '',
        '',
        '',
        $code_tax,
        '',
        '',
        '',
        0,
        0
    );

    $invoice_row = "";
    foreach ($row_data as $column) {
        $invoice_row .= $column."\t";
    }
    $invoice_row .= "\r\n";
    $text        .= $invoice_row;

}


if ($invoice->get('Invoice Total Tax Amount') != 0) {
    $row_data = array(
        'R02',
        0,
        '',
        '',
        343,
        220,
        round($invoice->get('Invoice Total Tax Amount') * $exchange_rate, 2),
        $invoice->get('Invoice Total Tax Amount'),
        'Tax '.$store->get('Code').' '.$invoice->get('Invoice Tax Code'),
        '04',
        '',
        '',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        'X',
        '(Nedefinované)',
        '',
        '',
        '',
        '',
        '',
        '',
        $code_tax,
        '',
        '',
        '',
        0,
        0
    );

    $invoice_row = "";
    foreach ($row_data as $column) {
        $invoice_row .= $column."\t";
    }
    $invoice_row .= "\r\n";
    $text        .= $invoice_row;

}


//$text = iconv('UTF-8', 'WINDOWS-1250', $text);
$encoded_text = iconv( mb_detect_encoding( $text ), 'Windows-1252//TRANSLIT', $text );


header('Content-Type: text/plain; charset=Windows-1252');

header("Content-Disposition: attachment; filename=".$invoice->get('Invoice Public ID').".txt");


print $encoded_text;