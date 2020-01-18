<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 09-05-2019 14:41:39 CEST , Tranava, Sloavakia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


require_once 'common.php';

require_once 'utils/object_functions.php';


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
$smarty->assign('customer', $customer);


$smarty->assign('number_orders', $number_orders);
$smarty->assign('number_dns', $number_dns);


$r02 = array();


$sql = sprintf(
    "SELECT  `Product Barcode Number`,`Product Origin Country Code`,`Delivery Note Quantity` as Qty, `Order Transaction Amount` as Amount, `Product Package Weight`,`Order Transaction Amount`,`Delivery Note Quantity`,`Order Transaction Total Discount Amount`,`Order Transaction Out of Stock Amount`,`Order Currency Code`,`Order Transaction Gross Amount`,
`Product Currency`,`Product History Name`,`Product History Price`,`Product Units Per Case`,`Product Name`,`Product RRP`,`Product Tariff Code`,`Product Tariff Code`,P.`Product ID`,`Product History Code`
 FROM `Order Transaction Fact` O  LEFT JOIN `Product History Dimension` PH ON (O.`Product Key`=PH.`Product Key`) LEFT JOIN
  `Product Dimension` P ON (PH.`Product ID`=P.`Product ID`) WHERE `Invoice Key`=%d  and `Current Dispatching State`   and (`Order Transaction Amount`!=0 or `Delivery Note Quantity`!=0)  ORDER BY `Product History Code`", $invoice->id
);

//print $sql;exit;


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $units    = $row['Product Units Per Case'];
        $name     = $row['Product History Name'];
        $currency = $row['Product Currency'];

        $desc = '';
        if ($units > 1) {
            $desc = number($units).'x ';
        }
        $desc .= ' '.$name;
        $desc = trim($desc);

        $price = money($row['Product History Price'], $currency);


        $product = array(
            'name' => '',
        );


        $sku = $row['Product History Code'];

        $row   = array(
            'R02',
            'nazov polozky - name of item'                           => $desc,
            'mnozstvo - quantity of item'                            => $row['Qty'],
            'MJ - unit'                                              => 'ks',
            'jedn. cena bez DPH - unit price without VAT'            => number_format($row['Amount'], 2, '.', ''),
            'sadzba DPH -rate of VAT'                                => 'V',
            'skladova cena - price in-store'                         => 0,
            'cennikova cena - list price'                            => ($row['Qty'] == 0 ? '0.00' : number_format($row['Amount'] / $row['Qty'], 2, '.', '')),
            'percento zlava - percent discount'                      => 0,
            'typ polozky - type of item  '                           => 'K',
            'cudzi nazov - foreign name'                             => $sku,
            'EAN'                                                    => '',
            'PLU'                                                    => '',
            'S ucet - synthetic account'                             => '604',
            'A ucet - analytic account'                              => '000',
            'colny sadzobnik - tariff'                               => '',
            'JKPOV'                                                  => '',
            'cislo karty/sluzby - item/service number'               => $sku,
            'volna polozka - free item'                              => '',
            'nazov skladu - name of store'                           => '',
            'kod stredisko - code of center'                         => '',
            'nazov stredisko - name of center'                       => 'AW EU',
            'kod zakazka -code of order'                             => '',
            'nazov zakazka - name of order'                          => '',
            'kod cinnost - code of operation'                        => '',
            'nazov cinnost - name of operation'                      => '',
            'kod pracovnik - code of worker'                         => 1,
            'meno pracovnik - name of worker'                        => 'Tomas',
            'priezvisko pracovnik - surname of worker'               => 'Belan',
            'typ DPH - type of VAT'                                  => '03',
            'Pripravene - ready'                                     => 0,
            'Dodane - delivered'                                     => 0,
            'Vybavene - furnished'                                   => 0,
            'PripraveneMR - ready from last year'                    => 0,
            'DodaneMR - delivered in last year'                      => 0,
            'Rezervovane - reserved'                                 => 0,
            'RezervovaneMR - reserved from last year'                => 0,
            'MJ odvodena - derived unit'                             => 'ks',
            'Mnozstvo z odvodenej MJ -quantity of derived unit'      => $row['Qty'],
            'cislo stredisko - center number'                        => '',
            'cislo zakazka - order number'                           => '',
            'cislo cinnost - operation number'                       => '',
            'cislo pracovnik - worker number '                       => '',
            'ExtCisloPolozky - item Ext'                             => '',
            'Zaokruhlenie - round'                                   => -3,
            'Spôsob zaokruhlenia - round mode'                       => 3,
            'bola vybavena rucne - manually furnished'               => 0,
            'nazov zlavy - name of discount'                         => '',
            'cennikova cena s DPH - list price with VAT'             => number_format($row['Amount'], 2, '.', ''),
            'ceny boli zadavane s DPH - prices was entered with VAT' => 0,
            'jedn. cena s DPH - unit price with VAT'                 => ($row['Qty'] == 0 ? '0.00' : number_format($row['Amount'] / $row['Qty'], 2, '.', '')),
            'zlava v EUR bez DPH - discount in EUR without VAT'      => 0,
            'zlava v EUR s DPH - discount in EUR with VAT'           => 0,
            'Oddiel KVDPH'                                           => '',
            'Druh tovaru KVDPH'                                      => '',
            'Kod tovaru KVDPH'                                       => '',
            'MJ pre KVDH'                                            => '',
            'Mnozstvo KVDPH'                                         => 0,
        );
        $r02[] = implode("\t", $row);
    }
}



$payment       = 'Dobierka';
$shipping_type = 'Kurier';

$row = array(
    'R01',
    'cislo dokladu - receipt number'                             => '',
    // Leave blank to be autogenerated
    'meno partnera - partner name'                               => $invoice->get('Invoice Customer Name'),
    'ICO -  REG'                                                 => $invoice->get('Invoice Registration Number'),
    'datum vystavenia/datum prijatia'                            => date('d.m.Y', strtotime($invoice->get_date('Invoice Date'))),
    'datum splatnosti - due date'                                => date('d.m.Y', strtotime($invoice->get_date('Invoice Date'))),
    'DUZP'                                                       => date('d.m.Y', strtotime($invoice->get_date('Invoice Date'))),
    'Zaklad Nizsia - VAT basis in lower VAT'                     => 0,
    'Zaklad Vyssia - VAT basis in higher VAT'                    => number_format($invoice->get('Invoice Items Net Amount') + $invoice->get('Invoice Shipping Net Amount') + $invoice->get('Invoice Charges Net Amount') , 2, '.', ''),
    'Zaklad 0 - VAT basis in null VAT'                           => 0,
    'Zaklad Neobsahuje - basis in VAT free'                      => 0,
    'Sadzba Nizsia - TAX rate lower'                             => 10,
    'Sadzba Vyssia - TAX rate higher'                            => 20,
    'Suma DPH nizsia - Amount VAT lower'                         => 0,
    'Suma DPH vyssia - Amount VAT higher'                        => number_format($invoice->get('Invoice Total Tax Amount'), 2, '.', ''),
    'Halierove vyrovnanie - Price correction'                    => 0,
    'Suma spolu CM - Amount in all in foreign currency'          =>  number_format($invoice->get('Invoice Total  Amount'), 2, '.', ''),
    'typ dokladu - type of receipts'                             => 0,
    'kod Ev - tally code'                                        => 'OF',
    'kod CR - code of sequence'                                  => 'OF',
    'interne cislo partnera - internal partner number'           => '',
    'kod partnera - code of partner'                             => '',
    'stredisko - centre partner'                                 => '',
    'prevadzka -plent partner'                                   => '',
    'ulica - street'                                             => $invoice->get('	Invoice Address Line 1'),
    'PSC - postal code'                                          => $invoice->get('	Invoice Address Postal Code'),
    'mesto - city'                                               => $invoice->get('	Invoice Address Locality'),
    'DIC/DU - TAX partner'                                       => preg_replace('/^[^0-9]*/', '', $invoice->get('Invoice Tax Number')),
    'cas vystavenia - time of issue'                             => date('H:i:s'),
    'dod. Podmienky - terms of delivery and payments'            => '',
    'uvod - introduction'                                        => '',
    'zaver -completion, ending'                                  => 'Recyklačné poplatky sú zahrnuté v cene produktov.',
    'dod. List - bill of delivery'                               => '',
    'cislo objednavky - order number'                            => $order->get('Order Public ID'),
    'vystavil - signed by'                                       => 'Tomas Belan',
    'KS - constant symbol'                                       => '0008',
    'SS - specific symbol'                                       => '',
    'forma uhrady - payment'                                     => '',
    'sposob dopravy - shipment'                                  => '',
    'Mena - currency'                                            => $invoice->get('Invoice Currency'),
    'Mnozstvo jednotky - quantity of unit currency'              => 1,
    'Kurz - exchange rate'                                       => $invoice->get('Invoice Currency Exchange'),
    'Suma spolu TM - amount in all - domestic currency'          => $invoice->get('Invoice Total Amount'),
    'Zakazkovy list - bill of custom-made'                       => '',
    'poznamka -comment'                                          => '',
    'predmet fakturacie - subject of invoicing'                  => '',
    'partner stat - partner country'                             => $invoice->get('Invoice Address Country 2 Alpha Code'),
    'Kod IC DPH - code of VAT'                                   => '',
    'IC DPH - VAT'                                               => '',
    'Dodavatel cislo uctu - suppliers number of bank account'    => '',
    'Dodavatel banka - suppliers name of bank'                   => '',
    'Dodavatel pobocka - suppliers branch of bank'               => '',
    'partner stat - partner country2'                            => $invoice->get('Invoice Address Country 2 Alpha Code'),
    'Kod vystavil - code of signed by'                           => 1,
    'Partner meno skratka - short name of partner'               => substr($invoice->get('Invoice Customer Name'), 0, 15),
    'Dodavatel  SWIFT - SWIFT of suppliers'                      => '',
    'Dodavatel IBAN - IBAN of suppliers'                         => '',
    'Dodavatel kod statu DPH - code country in VAT of suppliers' => 'SK',
    'Dodavatel IC pre DPH - VAT of suppliers'                    => '',
    'Dodavatel stat - country of suppliers'                      => '',
    'Zaokruhlenie - round'                                       => -2,
    'Sposob zaokruhlenia - round mode'                           => 3,
    'IČO poradové číslo -  REG order number'                     => '',
    'Zaokruhlenie položky - round of item'                       => -4,
    'Sprievodny text k preddavku - Accompanying text to advance' => '',
    'Suma preddavku - amount of advance'                         => 0,
    'Spôsob výpočtu DPH - VAT calculation method'                => 0,
    'Starý spôsob výpočtu DPH'                                   => 0,
    'Datum vystavenia DF'                                        => '',
    'Úhradené cez ECR - paid via ECR'                            => 0,
    'VS '                                                        => '',
    'Poštová adresa - Kontaktná osoba'                           => '',
    'Poštová adresa - Firma'                                     => '',
    'Poštová adresa - Stredisko'                                 => '',
    'Poštová adresa - Prevádzka'                                 => '',
    'Poštová adresa - Ulica'                                     => '',
    'Poštová adresa - PSČ'                                       => '',
    'Poštová adresa - Mesto'                                     => '',
    ''                                                           => '',
    'Typ zľavy za doklad'                                        => '',
    'Zľava za doklad'                                            => '',
    'rezervované'                                                => '',
    'Kontaktná osoba'                                            => '',
    'Telefón'                                                    => '',
    'Uplatňovanie DPH podľa úhrad'                               => '',
);

$r01  = implode("\t", $row);
$r01  = iconv('UTF-8', 'WINDOWS-1250//IGNORE', $r01);
$r_02 = implode("\r\n", $r02);
$r_02 = iconv('UTF-8', 'WINDOWS-1250', $r_02);

$txt[] = "$r01\r\n$r_02";


$txt = "R00\tT01\r\n".implode("\r\n", $txt);
header('Content-Disposition: attachment; filename=tg-omega-faktury.txt');
echo $txt;

