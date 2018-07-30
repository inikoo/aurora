<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 14:17:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_invoice_showcase($data, $smarty, $user, $db) {
    require_once 'utils/geography_functions.php';

    global $account;

    if (!$data['_object']->id) {
        return "";
    }


    $data['_object']->update_payments_totals();


    $smarty->assign('invoice', $data['_object']);

    $smarty->assign('order', get_object('order', $data['_object']->get('Invoice Order Key')));


    $store = get_object('store', $data['_object']->get('Store Key'));

    $smarty->assign('store', $store);


    $customer = get_object('customer', $data['_object']->get('Customer Key'));

    $smarty->assign('customer', $customer);

    $smarty->assign('user', $user);

    $invoice = $data['_object'];

    $tax_data = array();
    $sql      = sprintf(
        "SELECT `Tax Category Name`,`Tax Category Rate`,`Tax Amount`  FROM  `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Tax Code`)  WHERE B.`Invoice Key`=%d  AND `Tax Category Country Code`=%s  ", $invoice->id,
        prepare_mysql($account->get('Account Country Code'))
    );

    if (($data['_object']->get('Invoice Type') == 'Refund')) {
        $factor = -1;
    } else {
        $factor = 1;
    }
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $tax_data[] = array(
                'name' => $row['Tax Category Name'],

                'amount' => money(
                    $factor * $row['Tax Amount'], $invoice->data['Invoice Currency']
                )
            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $smarty->assign('tax_data', $tax_data);



    if (in_array(
        $invoice->get('Invoice Address Country 2 Alpha Code'), get_countries_EC_Fiscal_VAT_area($db)
    )) {
        $pdf_with_commodity = false;
    } else {
        $pdf_with_commodity = true;
    }
    $smarty->assign('pdf_with_commodity', $pdf_with_commodity);

    if($store->get('Store Locale')!='en_GB'){
        $pdf_show_locale_option = true;
    }else{
        $pdf_show_locale_option = false;

    }
    $smarty->assign('pdf_show_locale_option', $pdf_show_locale_option);

    $pdf_with_rrp=true;
    $smarty->assign('pdf_with_rrp', $pdf_with_rrp);


    if ($data['_object']->get('Invoice Type') == 'Refund') {




        $smarty->assign(
            'object_data', base64_encode(
                             json_encode(
                                 array(
                                     'object' => $data['object'],
                                     'key'    => $data['key'],
                                     'symbol'=>currency_symbol($invoice->get('Order Currency')),
                                     'tax_rate'=>$invoice->get('Invoice Tax Rate'),
                                     'available_to_refund'=>$invoice->get('Invoice Total Amount'),
                                     'tab' => $data['tab'],
                                     'order_type'=>$invoice->get('Invoice Type'),
                                 )
                             )
                         )
        );


        return $smarty->fetch('showcase/refund.tpl');

    } else {
        return $smarty->fetch('showcase/invoice.tpl');

    }


}


?>
