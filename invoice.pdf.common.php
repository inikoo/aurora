<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 03 November 2019  21:19::14  +0800 Plane Bangkok-Oslo (Zadonk)

 Copyright (c) 2019, Inikoo

 Version 2.0
*/

/** @var Smarty $smarty */
/** @var Account $account */

/** @var PDO $db */


use CommerceGuys\Addressing\Country\CountryRepository;
use Mpdf\Mpdf;
use Aurora\Models\Utils\TaxCategory;

include_once 'class.Country.php';

$id = $_REQUEST['id'] ?? '';
if (!$id) {
    exit;
}

/**
 * @var $invoice Invoice
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

if (!empty($_REQUEST['hide_payment_status'])) {
    $hide_payment_status = true;
} else {
    $hide_payment_status = false;
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

$countryRepository = new CountryRepository();
if (!empty($_REQUEST['origin'])) {
    $print_origin = true;
} else {
    $print_origin = false;
}

if (!empty($_REQUEST['CPNP'])) {
    $print_CPNP = true;
} else {
    $print_CPNP = false;
}

if (!empty($_REQUEST['group_by_tariff_code'])) {
    $group_by_tariff_code = true;
} else {
    $group_by_tariff_code = false;
}

putenv('LC_ALL='.$_locale.'.UTF-8');
setlocale(LC_ALL, $_locale.'.UTF-8');
bindtextdomain("inikoo", "./locales");
textdomain("inikoo");


$number_orders = 0;
$number_dns    = 0;

$order = get_object('Order', $invoice->get('Invoice Order Key'));

$pastpay_notes=[];


$pastpay=false;
foreach($order->get_payments('objects') as $payment){
    if($payment->get('Payment Account Code')=='Pastpay'  and $payment->get('Payment Transaction Status')=='Completed' ){
        $pastpay=true;
    }

}

$pastpay_due_date=false;
if ($pastpay) {



    $pastpay_data = json_decode($order->get('Order Pastpay Data'), true);
    $term         = $pastpay_data['term'];

    $pastpay_due_date=date('Y-m-d', strtotime($invoice->get('Invoice Date')." + $term days"));


    $pastpay_due_date = strftime(
        "%e %b %Y",strtotime($pastpay_due_date));

    $ok=false;
    $__payments=$invoice->get_payments('objects');

    foreach($__payments as $_payment){
        if($_payment->get('Payment Account Code')=='Pastpay' and $_payment->get('Payment Transaction Status')=='Completed'  ){
            $ok=true;
        }
    }

    if($ok) {
        $_currency = $order->get('Currency');
        $locale    = $store->get('Store Locale');


        if (DNS_ACCOUNT_CODE == 'ES') {

            $_pastpay_notes = [


                'GBP' =>
                    [
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe Sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to PastPay’s account – Bank: Revolut Ltd -  Account number 79163777 ,  Sort code 04-00-75 , Recipient: PastPay Europe SP Z O O   including the invoice number in the payment reference. If you have any questions regarding the payment of the invoice, please contact us directly at payments@pastpay.com."


                    ],

                'EUR' =>
                    [
                        'en_GB' => 'Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe Sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to PastPay’s account – IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, including the invoice number in the payment reference. If you have any questions regarding the payment of the invoice, please contact us directly at payments@pastpay.com.',
                        'de_DE' => 'Wichtig! Abtretungsklausel: Gläubiger der Forderung aus dieser Rechnung ist PastPay Europe Sp. z o.o. (Anschrift: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), die die Forderung aufgrund des Abtretungsvertrags mit dem Webshop erworben hat. Bitte zahlen Sie den Rechnungsbetrag auf das Konto von PastPay – IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, und geben Sie im Verwendungszweck die Rechnungsnummer an. Wenn Sie Fragen zur Bezahlung der Rechnung haben, wenden Sie sich bitte direkt an uns unter',
                        'it_IT' => "Importante! Presta attenzione alla clausola di trasferimento: il beneficiario del pagamento indicato in questa fattura è PastPay Europe - Pentech Solutions sp. z o.o. (situata in ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP 5472223365). Questa azienda ha assunto il diritto al pagamento a seguito della cessione del contratto di crediti stipulato con il webshop. Gentilmente effettua il pagamento dell'importo specificato sulla fattura sul conto di Pentech Solutions IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'sk_SK' => "Dôležité! Prevodná doložka: veriteľ tejto pohľadávky v tejto faktúre je spoločnost PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), ktorá nadobudla túto pohľadávku z dôvodu postúpenia zmluvy o pohľadávke s internetovým obchodom. Prosíme o úhradu sumy faktúry na účet spoločnosti Pentech - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'nl_NL' => "Belangrijk! Overdrachtsclausule: De schuldeiser van de vordering op deze factuur is PastPay Europe - Pentech Solutions sp. z o.o. (adres: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) die de vordering heeft verkregen door een contract van overdracht van vorderingen met de webshop. Gelieve het bedrag van de factuur te betalen op de rekening van Pentech - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'es_ES' => '¡Importante! Cláusula de transferencia: El acreedor de la reclamación de esta factura es PastPay Europe Sp. z o.o.(dirección: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) que adquirió la reclamación debido a la transferencia de contrato de cuentas por cobrar con la tienda web. Por favor, abone el importe de la factura a la cuenta de PastPay - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, incluyendo el número de factura en la referencia del pago. Si tiene alguna pregunta sobre el pago de la factura, póngase en contacto con nosotros directamente en payments@pastpay.com.',
                        'pt_PT' => 'Importante! Cláusula de transferência: O credor do crédito nesta fatura é PastPay Europe Sp. z o.o.(endereço: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) que adquiriu o crédito devido ao contrato de transferência de recebíveis com a loja virtual. Por favor, pague o valor da fatura na conta PastPay – IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, incluindo o número da fatura na referência de pagamento. Se tiver alguma dúvida relativamente ao pagamento da fatura, contacte-nos diretamente em payment@pastpay.com.'
                    ],
                "RON" =>
                    [
                        'ro_Ro' => "Important! Clauză de transfer: Creditorul creanței din această factură este PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) care a dobândit creanța datorită transferului contractului de creanțe cu magazinul online. Vă rugăm să achitați valoarea facturii în contul Pentech - IBAN: BE76 9140 4490 9695 SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE76 9140 4490 9695 SWIFT: FXBBBEBBXXX",

                    ],
                "HUF" =>
                    [
                        "hu_HU" => "Fontos! Átruházási záradék: Jelen számlában megtestesülő követelés és járulékai jogosultja (átruházást követően) a PastPay Europe - Pentech Solutions sp. z o.o. (cím: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365). Kérjük, hogy a számla összegét mindenképpen a BE45 9140 4490 9089 (SWIFT: FXBBBEBBXXX) számú számlára szíveskedjenek kielégíteni.",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE45 9140 4490 9089 SWIFT: FXBBBEBBXXX"
                    ],
                "CZK" =>
                    [
                        "cs_CZ" => "Důležité! Doložka o postoupení pohledávky: věřitelem této pohledávky v této faktuře je společnost PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), která tuto pohledávku nabyla na základě smlouvy o postoupení pohledávky s internetovým obchodem. Fakturovanou částku prosím uhraďte na účet společnosti Pentech - IBAN: BE23 9140 4490 9291 SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE23 9140 4490 9291 SWIFT: FXBBBEBBXXX"
                    ],
                "PLN" =>
                    [
                        "pl_PL" => "WAŻNE! Klauzula cesji: Wierzycielem roszczenia objętego niniejszą fakturą jest Pentech Solutions Polska sp. z o.o. (adres: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), która uzyskała wierzytelność wskutek umowy cesji ze sklepem internetowym, zgodnie z zasadami płatności w systemie PastPay. Proszę uprzejmie zapłacić wartość faktury na rachunek bankowy Pentech – IBAN: BE54 9140 4490 9897, SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE54 9140 4490 9897, SWIFT: FXBBBEBBXXX"
                    ]

            ];
        }elseif(DNS_ACCOUNT_CODE=='AWEU'){
            $_pastpay_notes = [


                'GBP' =>
                    [
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe Sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to PastPay’s account – Bank: Revolut Ltd -  Account number 79163777 ,  Sort code 04-00-75 , Recipient: PastPay Europe SP Z O O   including the invoice number in the payment reference. If you have any questions regarding the payment of the invoice, please contact us directly at payments@pastpay.com."


                    ],

                'EUR' =>
                    [
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'en_XX' => 'Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe Sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to PastPay’s account – IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, including the invoice number in the payment reference. If you have any questions regarding the payment of the invoice, please contact us directly at payments@pastpay.com.',
                        'de_DE' => "Wichtig! Übertragungsklausel: Der Gläubiger der Forderung in dieser Rechnung ist PastPay Europe - Pentech Solutions sp. z o.o. (Adresse: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), der die Forderung aufgrund eines Forderungsübertragungsvertrages mit dem Händler erworben hat. Bitte zahlen Sie den Rechnungsbetrag auf das Konto von Pentech - IBAN: BE98 9140 4490 9493, SWIFT: FXBBBEBBXXX",
                        'it_IT' => "Importante! Presta attenzione alla clausola di trasferimento: il beneficiario del pagamento indicato in questa fattura è PastPay Europe - Pentech Solutions sp. z o.o. (situata in ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP 5472223365). Questa azienda ha assunto il diritto al pagamento a seguito della cessione del contratto di crediti stipulato con il webshop. Gentilmente effettua il pagamento dell'importo specificato sulla fattura sul conto di Pentech Solutions IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'sk_SK' => "Dôležité! Prevodná doložka: veriteľ tejto pohľadávky v tejto faktúre je spoločnost PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), ktorá nadobudla túto pohľadávku z dôvodu postúpenia zmluvy o pohľadávke s internetovým obchodom. Prosíme o úhradu sumy faktúry na účet spoločnosti Pentech - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'nl_NL' => "Belangrijk! Overdrachtsclausule: De schuldeiser van de vordering op deze factuur is PastPay Europe - Pentech Solutions sp. z o.o. (adres: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) die de vordering heeft verkregen door een contract van overdracht van vorderingen met de webshop. Gelieve het bedrag van de factuur te betalen op de rekening van Pentech - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX",
                        'es_ES' => '¡Importante! Cláusula de transferencia: El acreedor de la reclamación de esta factura es PastPay Europe Sp. z o.o.(dirección: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) que adquirió la reclamación debido a la transferencia de contrato de cuentas por cobrar con la tienda web. Por favor, abone el importe de la factura a la cuenta de PastPay - IBAN: BE98 9140 4490 9493 SWIFT: FXBBBEBBXXX, incluyendo el número de factura en la referencia del pago. Si tiene alguna pregunta sobre el pago de la factura, póngase en contacto con nosotros directamente en payments@pastpay.com.'
                    ],
                "RON" =>
                    [
                        'ro_Ro' => "Important! Clauză de transfer: Creditorul creanței din această factură este PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) care a dobândit creanța datorită transferului contractului de creanțe cu magazinul online. Vă rugăm să achitați valoarea facturii în contul Pentech - IBAN: BE76 9140 4490 9695 SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE76 9140 4490 9695 SWIFT: FXBBBEBBXXX",

                    ],
                "HUF" =>
                    [
                        "hu_HU" => "Fontos! Átruházási záradék: Jelen számlában megtestesülő követelés és járulékai jogosultja (átruházást követően) a PastPay Europe - Pentech Solutions sp. z o.o. (cím: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365). Kérjük, hogy a számla összegét mindenképpen a BE45 9140 4490 9089 (SWIFT: FXBBBEBBXXX) számú számlára szíveskedjenek kielégíteni.",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE45 9140 4490 9089 SWIFT: FXBBBEBBXXX"
                    ],
                "CZK" =>
                    [
                        "cs_CZ" => "Důležité! Doložka o postoupení pohledávky: věřitelem této pohledávky v této faktuře je společnost PastPay Europe - Pentech Solutions sp. z o.o. (adresa: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), která tuto pohledávku nabyla na základě smlouvy o postoupení pohledávky s internetovým obchodem. Fakturovanou částku prosím uhraďte na účet společnosti Pentech - IBAN: BE23 9140 4490 9291 SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE23 9140 4490 9291 SWIFT: FXBBBEBBXXX"
                    ],
                "PLN" =>
                    [
                        "pl_PL" => "WAŻNE! Klauzula cesji: Wierzycielem roszczenia objętego niniejszą fakturą jest Pentech Solutions Polska sp. z o.o. (adres: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365), która uzyskała wierzytelność wskutek umowy cesji ze sklepem internetowym, zgodnie z zasadami płatności w systemie PastPay. Proszę uprzejmie zapłacić wartość faktury na rachunek bankowy Pentech – IBAN: BE54 9140 4490 9897, SWIFT: FXBBBEBBXXX",
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe - Pentech Solutions sp. z o.o. (address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to Pentech's account – IBAN: BE54 9140 4490 9897, SWIFT: FXBBBEBBXXX"
                    ]

            ];
        }else{
            $_pastpay_notes = [
            'GBP' =>
                    [
                        'en_GB' => "Important! Transfer clause: The creditor of the claim in this invoice is PastPay Europe Sp. z o.o.(address: ul. Legionów 33/3, 43-300 Bielsko-Biała, NIP: 5472223365) which acquired the claim due to transfer of receivables contract with the webshop. Please pay the amount of the invoice to PastPay’s account – Bank: Revolut Ltd -  Account number 79163777 ,  Sort code 04-00-75 , Recipient: PastPay Europe SP Z O O   including the invoice number in the payment reference. If you have any questions regarding the payment of the invoice, please contact us directly at payments@pastpay.com."

                    ],
                ];

        }



        switch ($_currency) {
            case 'EUR':

                if (in_array($locale, ['de_DE', 'it_IT', 'sk_SK'])) {
                    $pastpay_notes[] = $_pastpay_notes[$_currency][$locale];
                }
                $pastpay_notes[] = $_pastpay_notes[$_currency]['en_GB'];

                break;
            default:
                $pastpay_notes = $_pastpay_notes[$_currency];
        }
    }

}



if ($order->id) {
    $smarty->assign('order', $order);
    $number_orders = 1;

    $delivery_note = get_object('Delivery_Note', $order->get('Order Delivery Note Key'));


    if ($delivery_note->id) {
        $smarty->assign('delivery_note', $delivery_note);
        $number_dns = 1;
    }
}

$smarty->assign('group_by_tariff_code', $group_by_tariff_code);



$smarty->assign('pastpay_due_date', $pastpay_due_date);

$smarty->assign('pro_mode', $pro_mode);
$smarty->assign('customer', $customer);
$smarty->assign('number_orders', $number_orders);
$smarty->assign('number_dns', $number_dns);

$smarty->assign('hide_payment_status', $hide_payment_status);

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


if ($invoice->data['Invoice Paid'] == 'Yes' and !$pastpay) {
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
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,`Product History Code` as `Product Code`,`Order Quantity`,`Order Transaction Product Type`
 FROM 
 `Order Transaction Fact` OTF  LEFT JOIN `Product History Dimension` PH ON (OTF.`Product Key`=PH.`Product Key`) LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) 
 
 WHERE `Invoice Key`=%d  and `Current Dispatching State`   and (`Order Transaction Amount`!=0 or `Delivery Note Quantity`!=0)  ORDER BY `Product History Code`",
    $invoice->id
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $currency = $row['Order Currency Code'];

        $amount        = $row['Amount'];
        $row['Amount'] = money($amount, $currency, $_locale);

        $discount = ($row['Order Transaction Total Discount Amount'] == 0
            ? ''
            : percentage(
                $row['Order Transaction Total Discount Amount'],
                $row['Order Transaction Gross Amount'],
                0
            ));


        $units = $row['Product Units Per Case'];
        $name  = $row['Product History Name'];
        $price = $row['Product History Price'];


        if ($row['Order Transaction Product Type'] == 'Service') {
            $row['Qty'] = $row['Order Quantity'];
        }

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

            $desc = number($units).'x ';

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
            /** @var Product $product */
            $product    = get_object('Product', $row['Product ID']);
            $parts_data = $product->get_parts_data();

            $parts = '';
            if (count($parts_data) > 0) {
                $description .= '<br>';

                foreach ($parts_data as $part_data) {
                    $parts .= ', '.$part_data['Units'].'x '.$part_data['Part Name'];
                }

                $description .= preg_replace('/, /', '', $parts);
            }
        }


        $row['Description'] = $description;

        $row['Discount'] = $discount;


        $transactions[] = $row;
    }
}

$transactions_no_products = array();


if ($invoice->data['Invoice Net Amount Off']) {
    $tax_category = new TaxCategory($db);
    $tax_category->loadWithKey($invoice->data['Invoice Tax Category Key']);


    if (!$tax_category->id) {
        throw new LogicException('Tax category not found '.$invoice->data['Invoice Tax Category Key']);
    }

    $net   = -1 * $invoice->data['Invoice Net Amount Off'];
    $tax   = $net * $tax_category->get('Tax Category Rate');
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
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d",
    $invoice->id
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
}


$sql = sprintf(
    "SELECT * FROM `Order No Product Transaction Fact` WHERE `Refund Key`=%d AND `Transaction Type`='Credit' ",
    $invoice->id
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
}

$transactions_out_of_stock = array();
$sql                       = sprintf(
    "SELECT (`No Shipped Due Out of Stock`) AS qty,`Product RRP`,`Product Barcode Number`,`Product History Code` as `Product Code`,
`Product Tariff Code`,`Product Tariff Code`,`Product Origin Country Code`,`Product Package Weight`,P.`Product ID`,`Product History Code` ,`Product Units Per Case`,`Product History Name`,`Product History Price`,`Product Currency`
FROM `Order Transaction Fact` O
 LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`)
 LEFT JOIN  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`)

  WHERE    `Invoice Key`=%d   and   (`No Shipped Due Out of Stock`>0   )  ORDER BY `Product History Code`",
    $invoice->id
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
}


$transactions_grouped_by_tariff_code = [];

$sql  = "select `Product Origin Country Code` origin, group_concat(`Product Code` SEPARATOR ', ') as codes,    sum(`Delivery Note Quantity` ) as Qty,  `Product Tariff Code` as Code ,sum(`Order Transaction Amount`) as Amount,`Product Name`, (select GROUP_CONCAT(`Commodity Name`) from kbase.`Commodity Code Dimension` where SUBSTRING(`Commodity Code`,1,8)=SUBSTRING(`Product Tariff Code`,1,8)         and `Commodity Name` IS NOT NULL ) as tc_name  from `Order Transaction Fact` OTF 
    left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) 
where `Invoice Key`=? group by `Product Tariff Code`,`Product Origin Country Code` ;";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $invoice->id
    ]
);
while ($row = $stmt->fetch()) {
    if ($row['Qty'] > 0) {
        $row['Description'] = $row['tc_name'];
        if ($row['Description'] == '') {
            $row['Description'] = $row['Product Name'].' *';
        }

        $row['Amount'] = money($row['Amount'], $invoice->get('Currency Code'));


        if (strlen($row['codes']) > 700) {
            $row['codes'] = $string = substr($row['codes'], 0, 700).'...';
        }


        $transactions_grouped_by_tariff_code[] = $row;
    }
}


$smarty->assign('transactions_grouped_by_tariff_code', $transactions_grouped_by_tariff_code);


$smarty->assign(
    'number_transactions_out_of_stock',
    count($transactions_out_of_stock)
);

$smarty->assign('transactions_out_of_stock', $transactions_out_of_stock);

$smarty->assign('transactions_no_products', $transactions_no_products);


if ($invoice->data['Invoice Type'] == 'CreditNote') {
    $sql = sprintf(
        "SELECT * FROM `Order No Product Transaction Fact` WHERE `Invoice Key`=%d ",
        $invoice->id
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

$number_tax_lines = 0;
$sql              = "select count(*) as num from `Invoice Tax Bridge` B  WHERE B.`Invoice Tax Invoice Key`=?";
$stmt             = $db->prepare($sql);
$stmt->execute(
    array(
        $invoice->id
    )
);
while ($row = $stmt->fetch()) {
    $number_tax_lines = $row['num'];
}


$tax_data = array();
$sql      = sprintf(
    "SELECT `Invoice Tax Amount`,`Invoice Tax Net`,`Invoice Tax Category Key` FROM  `Invoice Tax Bridge` B  WHERE B.`Invoice Tax Invoice Key`=%d  ",
    $invoice->id
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $tax_category = new TaxCategory($db);
        $tax_category->loadWithKey($row['Invoice Tax Category Key']);


        if ($number_tax_lines <= 1) {
            $base = '';
        } else {
            $base = money($row['Invoice Tax Net'], $invoice->get('Invoice Currency')).' @';
        }

        switch ($tax_category->get('Tax Category Type')) {
            case 'Outside':
                $tax_category_name = _('Tax').'<div style="font-size: x-small">'._('Outside the scope of tax').'</div>';
                break;
            case 'EU_VTC':
                $tax_category_name = _('Tax').'<div style="font-size: x-small">'.sprintf(_('Valid tax number %s'), $invoice->get('Invoice Tax Number')).'</div>';
                $exempt_tax        = true;
                break;
            default:
                $tax_category_name = _('Tax').'<div style="font-size: x-small"><small>'.$tax_category->get('Tax Category Code').'</small> '.$tax_category->get('Tax Category Name').'</div>';
        }


        $tax_data[] = array(
            'name'   => $tax_category_name,
            'base'   => $base,
            'amount' => money(
                $row['Invoice Tax Amount'],
                $invoice->data['Invoice Currency']
            )
        );
    }
}


$smarty->assign('tax_data', $tax_data);
$smarty->assign('account', $account);
$smarty->assign('pastpay_notes', $pastpay_notes);
$smarty->assign('pastpay', $pastpay);

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
if (!empty($save_to_file)) {
    $mpdf->Output($save_to_file, 'F');
} else {
    $mpdf->Output($invoice->get('Public ID').'.pdf', 'I');
}






