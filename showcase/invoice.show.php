<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 14:17:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_invoice_showcase($data, $smarty, $user, $db, $account): string
{
    require_once 'utils/geography_functions.php';


    if (!$data['_object']->id) {
        return "";
    }
    //$data['_object']->update_tax_data();



    $order = get_object('order', $data['_object']->get('Invoice Order Key'));

    $smarty->assign('invoice', $data['_object']);

    $smarty->assign('order', $order);


    $store = get_object('store', $data['_object']->get('Store Key'));

    $smarty->assign('store', $store);


    $customer = get_object('customer', $data['_object']->get('Customer Key'));

    $smarty->assign('customer', $customer);

    $smarty->assign('user', $user);

    $invoice = $data['_object'];


    $export_omega = false;

    if ($account->get('Account Country 2 Alpha Code') == 'SK') {
        $export_omega = true;
    }


    if ($invoice->get('Invoice External Invoicer Key')) {
        $external_invoicer = get_object('External_Invoicer', $invoice->get('Invoice External Invoicer Key'));
        if ($external_invoicer->metadata('country') == 'SK') {
            $export_omega = true;
        }
    }

    $smarty->assign('export_omega', $export_omega);

    if (($data['_object']->get('Invoice Type') == 'Refund')) {
        $factor = -1;
    } else {
        $factor = 1;
    }

    $tax_data = [];
    $sql      = "SELECT `Tax Category Code`,`Tax Category Rate`,`Tax Category Name`,`Invoice Tax Amount`,`Invoice Tax Net`  FROM  
        `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Key`=B.`Invoice Tax Category Key`)  WHERE B.`Invoice Tax Invoice Key`=?";

    $stmt = $db->prepare($sql);
    $stmt->execute(array(
                       $invoice->id
                   ));
    $number_tax_lines = 0;
    while ($row = $stmt->fetch()) {

        switch ($row['Tax Category Code']) {
            case 'OUT':
                $tax_description = _('Outside the scope of tax');
                break;
            case 'EU':
                $tax_description = sprintf(_('EU with %s'), $invoice->get('Tax Number Formatted'));
                break;
            default:
                $tax_description = $row['Tax Category Name'];
        }


        $tax_data[] = array(
            'name'   => $tax_description,
            'base'   => money(
                $factor * $row['Invoice Tax Net'],
                $invoice->data['Invoice Currency']
            ),
            'amount' => money(
                $factor * $row['Invoice Tax Amount'],
                $invoice->data['Invoice Currency']
            )
        );
        $number_tax_lines++;
    }
    $smarty->assign('number_tax_lines', $number_tax_lines);

    $smarty->assign('tax_data', $tax_data);


    if ($store->settings('invoice_show_tariff_codes') == 'Yes' or !in_array($invoice->get('Invoice Address Country 2 Alpha Code'), get_countries_EC_Fiscal_VAT_area($db))) {
        $pdf_with_commodity = true;
    } else {
        $pdf_with_commodity = false;
    }
    $smarty->assign('pdf_with_commodity', $pdf_with_commodity);

    if ($store->settings('invoice_show_pro_mode') == 'Yes') {
        $pdf_pro_mode = true;
    } else {
        $pdf_pro_mode = false;
    }
    $smarty->assign('pdf_pro_mode', $pdf_pro_mode);

    if ($store->get('Store Locale') != 'en_GB') {
        $pdf_show_locale_option = true;
    } else {
        $pdf_show_locale_option = false;
    }
    $smarty->assign('pdf_show_locale_option', $pdf_show_locale_option);


    if ($store->settings('invoice_show_rrp') == 'Yes') {
        $pdf_with_rrp = true;
    } else {
        $pdf_with_rrp = false;
    }
    $smarty->assign('pdf_with_rrp', $pdf_with_rrp);

    if ($store->settings('invoice_show_parts') == 'Yes') {
        $pdf_with_parts = true;
    } else {
        $pdf_with_parts = false;
    }
    $smarty->assign('pdf_with_parts', $pdf_with_parts);

    if ($store->settings('invoice_show_barcode') == 'Yes') {
        $pdf_with_barcode = true;
    } else {
        $pdf_with_barcode = false;
    }
    $smarty->assign('pdf_with_barcode', $pdf_with_barcode);

    if ($store->settings('invoice_show_weight') == 'Yes') {
        $pdf_with_weight = true;
    } else {
        $pdf_with_weight = false;
    }
    $smarty->assign('pdf_with_weight', $pdf_with_weight);

    if ($store->settings('invoice_show_origin') == 'Yes') {
        $pdf_with_origin = true;
    } else {
        $pdf_with_origin = false;
    }
    $smarty->assign('pdf_with_origin', $pdf_with_origin);

    if ($store->settings('invoice_show_CPNP') == 'Yes') {
        $pdf_with_CPNP = true;
    } else {
        $pdf_with_CPNP = false;
    }
    $smarty->assign('pdf_with_CPNP', $pdf_with_CPNP);


    if ($invoice->deleted) {
        if ($data['_object']->get('Invoice Type') == 'Refund') {
            return $smarty->fetch('showcase/deleted_refund.tpl');
        } else {
            return $smarty->fetch('showcase/deleted_invoice.tpl');
        }
    } else {
        if ($data['_object']->get('Invoice Type') == 'Refund') {
            $smarty->assign(
                'object_data',
                json_encode(array(
                                'object'              => $data['object'],
                                'key'                 => $data['key'],
                                'symbol'              => currency_symbol($invoice->get('Order Currency')),
                                'tax_rate'            => $invoice->get('Invoice Tax Rate'),
                                'available_to_refund' => $invoice->get('Invoice Total Amount'),
                                'tab'                 => $data['tab'],
                                'order_type'          => $invoice->get('Invoice Type'),
                            ))

            );


            return $smarty->fetch('showcase/refund.tpl');
        } else {
            return $smarty->fetch('showcase/invoice.tpl');
        }
    }
}



