<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2019 at 12:47:06 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';

use CommerceGuys\Addressing\Address;
use CommerceGuys\Addressing\Formatter\PostalLabelFormatter;
use CommerceGuys\Addressing\AddressFormat\AddressFormatRepository;
use CommerceGuys\Addressing\Country\CountryRepository;
use CommerceGuys\Addressing\Subdivision\SubdivisionRepository;


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$store_key=3;

$print_est = false;

$sql = sprintf("select count(*) as num FROM `Invoice Dimension` O left join `Store Dimension` on (`Store Key`=`Invoice Store Key`)  where `Store key`=%d and `Invoice Type`='Refund' ",$store_key);
if ($result = $db->query($sql)) {
    if ($row = $result->fetch()) {
        $total = $row['num'];
    } else {
        $total = 0;
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

$lap_time0 = date('U');
$contador  = 0;


$sql = sprintf('SELECT `Invoice Key` FROM `Invoice Dimension`  where `Invoice Key`=1913501 order by `Invoice Key` desc ');
$sql = sprintf('SELECT `Invoice Key` FROM `Invoice Dimension` left join `Store Dimension` on (`Store Key`=`Invoice Store Key`)  where `Store key`=%d  and `Invoice Type`="Refund" order by `Invoice Key` desc ',$store_key);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $invoice                  = get_object('Invoice', $row['Invoice Key']);
        $data_to_update           = array();
        $address_fields_to_update = array();

        $sql = sprintf("update  `Order Transaction Fact` set  `Invoice Key`=`Refund Key` where `Refund Key`=%d  ", $invoice->id);
        $db->exec($sql);



        $sql = sprintf("select `Order Key` from `Order Transaction Fact` where `Refund Key`=%d ", $invoice->id);


        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {





                $order = get_object('Order', $row2['Order Key']);





                $data_to_update['Invoice Order Key'] = $row2['Order Key'];

                $recipient    = $invoice->get('Invoice Customer Contact Name');
                $organization = $invoice->get('Invoice Customer Name');


                if ($organization == $recipient) {
                    $organization = '';
                }
                $store           = new Store($invoice->get('Store Key'));
                $default_country = $store->get('Store Home Country Code 2 Alpha');

                $address_fields_to_update = parse_old_invoice_address_fields($store, $order->get('Order Billing To Key To Bill'), $recipient, $organization, $default_country);


                $sql = sprintf('select * from `Invoice Payment Bridge`  where `Invoice Key`=%d', $invoice->id);
                if ($result3 = $db->query($sql)) {
                    foreach ($result3 as $row3) {


                        $sql = sprintf(
                            'update `Order Payment Bridge` set `Invoice Key`=%d where `Order Key`=%d and `Payment Key`=%d ', $invoice->id, $order->id, $row3['Payment Key']


                        );
                        //print "$sql\n";
                        $db->exec($sql);

                        $sql = sprintf(
                            'Insert into `Order Payment Bridge` values  (%d,%d,%d,%d,%d,%.2f,%s) ', $order->id, $invoice->id, $row3['Payment Key'], $row3['Payment Account Key'], $row3['Payment Service Provider Key'], $row3['Amount'], prepare_mysql('No')


                        );
                       //print "$sql\n";

                        $db->exec($sql);

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $invoice->fast_update($data_to_update);
        $invoice->fast_update($address_fields_to_update);
        $invoice->update_payments_totals();

        //print_r($data_to_update);
        //print_r($address_fields_to_update);

        $contador++;
        $lap_time1 = date('U');

        if ($print_est) {
            print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.4f", ($lap_time1 - $lap_time0) / $contador)." EST  ".sprintf(
                    "%.4f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
                )."m  ($contador/$total) \r";
        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


function parse_old_invoice_address_fields($store, $address_key, $recipient, $organization, $default_country) {


    $address = new Billing_To($address_key);
    if ($address->id > 0) {


        if ($address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '') {
            $address->data['Billing To Country 2 Alpha Code'] = $default_country;
        }


        $address_format = get_address_format(
            (($address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '') ? $default_country : $address->data['Billing To Country 2 Alpha Code'])
        );


        $_tmp = preg_replace('/,/', '', $address_format->getFormat());

        $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => $address->get('Billing To Line 1'),
            'Address Line 2'               => $address->get('Billing To Line 2'),
            'Address Sorting Code'         => '',
            'Address Postal Code'          => $address->get('Billing To Postal Code'),
            'Address Dependent Locality'   => $address->get('Billing To Line 3'),
            'Address Locality'             => $address->get('Billing To Town'),
            'Address Administrative Area'  => $address->get('Billing To Line 4'),
            'Address Country 2 Alpha Code' => ($address->data['Billing To Country 2 Alpha Code'] == 'XX' ? $default_country : $address->data['Billing To Country 2 Alpha Code']),

        );
        //print_r($used_fields);

        //if (!in_array('recipient', $used_fields) or !in_array('organization', $used_fields) or !in_array('addressLine1', $used_fields)) {
        ////    print_r($used_fields);
        //    print_r($address->data);
        //    exit('no recipient or organization');
        // }

        if (!in_array('addressLine2', $used_fields)) {

            if ($address_fields['Address Line 2'] != '') {
                $address_fields['Address Line 1'] .= ', '.$address_fields['Address Line 2'];
            }
            $address_fields['Address Line 2'] = '';
        }

        if (!in_array('dependentLocality', $used_fields)) {

            if ($address_fields['Address Line 2'] == '') {
                $address_fields['Address Line 2'] = $address_fields['Address Dependent Locality'];
            } else {
                $address_fields['Address Line 2'] .= ', '.$address_fields['Address Dependent Locality'];
            }

            $address_fields['Address Dependent Locality'] = '';
        }

        if (!in_array('administrativeArea', $used_fields) and $address->get('Billing To Line 4') != '') {
            $address_fields['Address Administrative Area'] = '';
            //print_r($address->data);
            //print_r($address_fields);

            //print $address->display();


            //exit;

            //print_r($used_fields);
            //print_r($address->data);
            //exit('administrativeArea problem');

        }

        if (!in_array('postalCode', $used_fields) and $address->display('Billing To Postal Code') != '') {

            if (in_array('sortingCode', $used_fields)) {
                $address_fields['Address Sorting Code'] = $address_fields['Address Postal Code'];
                $address_fields['Address Postal Code']  = '';

            } else {
                if (in_array('addressLine2', $used_fields)) {
                    $address_fields['Address Line 2']      .= trim(
                        ' '.$address_fields['Address Postal Code']
                    );
                    $address_fields['Address Postal Code'] = '';
                }


                /*
                print_r($used_fields);
                print_r($address->data);
                print_r($address_fields);

                print $address->display();


                exit("\nError2\n");
                */
            }

        }


        if (!in_array('locality', $used_fields) and ($address->get(
                    'Billing To Town'
                ) != '' or $address->get('Billing To Line 4') != '')) {


            //$address_fields['Address Locality']='';
            //$address_fields['Address Dependent Locality']='';

            if (in_array('addressLine2', $used_fields)) {

                if ($address_fields['Address Line 1'] == '' and $address_fields['Address Line 2'] == '') {
                    $address_fields['Address Line 1'] .= $address_fields['Address Dependent Locality'];
                    $address_fields['Address Line 2'] .= $address_fields['Address Locality'];

                } elseif ($address_fields['Address Line 1'] != '' and $address_fields['Address Line 2'] == '') {
                    $address_fields['Address Line 2'] = preg_replace(
                        '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                    );

                } else {
                    $address_fields['Address Line 2'] = preg_replace(
                        '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                    );

                }
            } else {

                print_r($used_fields);
                print_r($address->data);
                print_r($address_fields);

                print $address->display();


                exit("Error3\n");

            }


        }


    } else {


        $address_format = get_address_format($default_country);


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );

    }

    array_walk($address_fields, 'trim_value');

    $_address_fields = array();
    foreach ($address_fields as $key => $value) {
        $_address_fields['Invoice '.$key] = $value;
    }


    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $_address_fields['Invoice Address Recipient'],
                'Address Organization'         => $_address_fields['Invoice Address Organization'],
                'Address Line 1'               => $_address_fields['Invoice Address Line 1'],
                'Address Line 2'               => $_address_fields['Invoice Address Line 2'],
                'Address Sorting Code'         => $_address_fields['Invoice Address Sorting Code'],
                'Address Postal Code'          => $_address_fields['Invoice Address Postal Code'],
                'Address Dependent Locality'   => $_address_fields['Invoice Address Dependent Locality'],
                'Address Locality'             => $_address_fields['Invoice Address Locality'],
                'Address Administrative Area'  => $_address_fields['Invoice Address Administrative Area'],
                'Address Country 2 Alpha Code' => $_address_fields['Invoice Address Country 2 Alpha Code'],
            )
        )
    );

    $_address_fields['Invoice Address Checksum'] = $new_checksum;


    $account = get_object('Account', 1);
    $country = $account->get('Account Country 2 Alpha Code');
    $locale  = $store->get('Store Locale');


    list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


    $address = $address->withFamilyName($_address_fields['Invoice Address Recipient'])->withOrganization($_address_fields['Invoice Address Organization'])->withAddressLine1($_address_fields['Invoice Address Line 1'])->withAddressLine2(
        $_address_fields['Invoice Address Line 2']
    )->withSortingCode(
        $_address_fields['Invoice Address Sorting Code']
    )->withPostalCode($_address_fields['Invoice Address Postal Code'])->withDependentLocality(
        $_address_fields['Invoice Address Dependent Locality']
    )->withLocality($_address_fields['Invoice Address Locality'])->withAdministrativeArea(
        $_address_fields['Invoice Address Administrative Area']
    )->withCountryCode(
        $_address_fields['Invoice Address Country 2 Alpha Code']

    );


    $xhtml_address = $formatter->format($address);


    $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
    $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


    $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);

    //  print $xhtml_address;

    $_address_fields['Invoice Address Formatted'] = $xhtml_address;
    /*
        $account=get_object('Account',1);
        $country = $account->get('Account Country 2 Alpha Code');
        $country = $store->get('Store Home Country Code 2 Alpha');

        $locale  = $store->get('Store Locale');



        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);
    */


    $_address_fields['Invoice Address Postal Label'] = $postal_label_formatter->format($address);


    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $_address_fields;
}


function trim_value(&$value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
}


?>
