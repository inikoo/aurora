<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 November 2017 at 20:56:27 GMT+8, Semijak , Bali, Indonesia
 Copyright (c) 2017, Inikoo

 Version 3

*/

chdir('../');
require_once 'common.php';
require_once 'class.Store.php';

require_once 'class.Order.php';
require_once 'utils/geography_functions.php';
require_once 'utils/object_functions.php';


$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}
$order = new Order($id);
if (!$order->id) {
    exit;
}



//print_r($order);
$store    = get_object('Store',$order->get('Order Store Key'));
$customer =  get_object('Customer',$order->get('Order Customer Key'));


putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
setlocale(LC_ALL, $store->data['Store Locale'].'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


$order_key = 0;
$dn_key    = 0;





include "external_libs/mpdf/mpdf.php";

$mpdf = new mPDF('win-1252', 'A4', '', '', 20, 15, 38, 25, 10, 10);

$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetTitle(_('Proforma').' '.$order->data['Order Public ID']);
$mpdf->SetAuthor($store->data['Store Name']);


if (isset($_REQUEST['print'])) {
    $mpdf->SetJS('this.print();');
}
$smarty->assign('store', $store);

$smarty->assign('order', $order);


    $smarty->assign('label_title', _('Proforma'));
    $smarty->assign('label_title_no', _('Proforma No.'));


if (in_array(
    $order->data['Order Delivery Country Code'], get_countries_EC_Fiscal_VAT_area($db)
)) {
    $print_tariff_code = false;
} else {
    $print_tariff_code = true;
}


$transactions = array();
$sql          = sprintf(
    "SELECT `Order Transaction Amount`,`Order Quantity`,`Order Transaction Total Discount Amount`,`Order Transaction Out of Stock Amount`,`Order Currency Code`,`Order Transaction Gross Amount`,
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product History XHTML Short Description`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code`
 FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN
  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Order Key`=%d ORDER BY `Product Code`", $order->id
);
//print $sql;exit;


if ($result=$db->query($sql)) {
		foreach ($result as $row) {



            $row['Amount'] = money(
                ($row['Order Transaction Amount']), $row['Order Currency Code']
            );


            $discount = ($row['Order Transaction Total Discount Amount'] == 0
                ? ''
                : percentage(
                    $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount']- floatval($row['Order Transaction Out of Stock Amount']), 0
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

           // if ($discount != '') {
          //      $description .= ' '._('Discount').':'.$discount;
          //  }

            if ($row['Product RRP'] != 0) {
                $description .= ' <br>'._('RRP').': '.money(
                        $row['Product RRP'], $row['Order Currency Code']
                    );
            }

            if ($print_tariff_code and $row['Product Tariff Code'] != '') {
                $description .= '<br>'._('Tariff Code').': '.$row['Product Tariff Code'];
            }

            $row['Product XHTML Short Description'] = $description;

            $row['Discount'] =$discount;


            $transactions[] = $row;
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}




$transactions_no_products = array();


if ($order->data['Order Net Amount Off']) {



    $tax_category = get_object('Tax_Category', $order->data['Order Tax Code']);

    $net   = -1 * $order->data['Order Net Amount Off'];
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
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d", $order->id
);

$total_gross    = 0;
$total_discount = 0;


if ($result=$db->query($sql)) {
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
                'Order Quantity'                => '',
                'Net'                             => money(
                    $row['Transaction Order Net Amount'], $row['Currency Code']
                ),
                'Tax'                             => money(
                    $row['Transaction Order Tax Amount'], $row['Currency Code']
                ),

                'Amount' => money(
                    $row['Transaction Order Net Amount'] + $row['Transaction Order Tax Amount'], $row['Currency Code']
                )
            );
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}




$sql    = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit' ", $order->id
);



if ($result=$db->query($sql)) {
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
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}



$transactions_out_of_stock = array();
$sql                       = sprintf(
    "SELECT `Product History XHTML Short Description`,(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`+`No Shipped Due Other`) AS qty,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Quantity`,`Order Currency Code`,`Product XHTML Short Description`,P.`Product ID`,O.`Product Code` FROM `Order Transaction Fact` O
 LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`)
 LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`)

  WHERE `Order Key`=%d AND (`No Shipped Due Out of Stock`>0  OR  `No Shipped Due No Authorized`>0 OR `No Shipped Due Not Found`>0 OR `No Shipped Due Other` )  ORDER BY `Product Code`", $order->id
);
//print $sql;exit;


if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $row['Amount']   = '';
            $row['Discount'] = '';

            if ($row['Product RRP'] != 0) {
                $row['Product XHTML Short Description']
                    = $row['Product History XHTML Short Description'].'<br>'._('RRP').': '.money($row['Product RRP'], $row['Order Currency Code']);
            }

            $row['Quantity']             = '<span >('.$row['qty'].')</span>';
            $transactions_out_of_stock[] = $row;

        }
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}





$smarty->assign(
    'number_transactions_out_of_stock', count($transactions_out_of_stock)
);

$smarty->assign('transactions_out_of_stock', $transactions_out_of_stock);

$smarty->assign('transactions_no_products', $transactions_no_products);


if ($order->data['Order Type'] == 'CreditNote') {

    $sql = sprintf(
        "SELECT * FROM `Order No Product Transaction Fact` WHERE `Order Key`=%d ", $order->id
    );
    //print $sql;exit;


    if ($result=$db->query($sql)) {
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
                $row['Product XHTML Short Description']
                                     = $row['Transaction Description'];
                $row['Amount']       = money(
                    ($row['Transaction Order Net Amount']), $row['Currency Code']
                );
                $row['Discount']     = '';
                $transactions[]      = $row;
    		}
    }else {
    		print_r($error_info=$db->errorInfo());
    		print "$sql\n";
    		exit;
    }


}


$smarty->assign('transactions', $transactions);



$smarty->assign('account', $account);

$extra_comments='';
if($account->get('Account Country Code')=='SVK'){

    if($exempt_tax){
        $extra_comments=_('Delivery is exempt from tax according to §43 of Act No. 222/2004 on VAT');

    }


}

$smarty->assign('extra_comments', $extra_comments);

//if ($account->data['Apply Tax Method'] == 'Per Item') {
//    $html = $smarty->fetch('order_tax_disaggregated.pdf.tpl');

//} else {
    $html = $smarty->fetch('proforma.pdf.tpl');

//}


$mpdf->WriteHTML($html);
//$mpdf->WriteHTML('<pagebreak resetpagenum="1" pagenumstyle="1" suppress="off" />');
$mpdf->Output();


?>
