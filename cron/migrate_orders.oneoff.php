<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2017 at 12:19:26 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';
include_once 'class.Ship_To.php';


$editor = array(
    'Author Name' => '',
    'Author Alias' => '',
    'Author Type' => '',
    'Author Key' => '',
    'User Key' => 0,
    'Date' => gmdate('Y-m-d H:i:s')
);

$account = new Account();

$print_est = true;
$sql = sprintf("select count(*) as num FROM `Order Dimension` O left join `Store Dimension` on (`Store Key`=`Order Store Key`)  where `Store Version`=1 ");
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


$sql = sprintf('SELECT `Order Key` FROM `Order Dimension` O left join `Store Dimension` on (`Store Key`=`Order Store Key`)  where `Store Version`=1  order by O.`Order Key` desc ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $order = get_object('Order', $row['Order Key']);

        $data_to_update = array();

        $address_fields_to_update = array();

        if (!$order->get('Order Delivery Note Key')) {

            $sql = sprintf(
                "select DN.`Delivery Note Key` from `Order Transaction Fact` OTF  left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=OTF.`Delivery Note Key`) where `Delivery Note Type`='Order' and `Order Key`=%d and  OTF.`Delivery Note Key`>0", $order->id
            );


            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $dn = get_object('DeliveryNote', $row2['Delivery Note Key']);
                    if ($dn->id) {
                        $data_to_update['Order Delivery Note Key'] = $dn->id;

                    }

                }
            }
        }


        if (!$order->get('Order Invoice Key')) {
            $sql = sprintf("select DN.`Invoice Key` from `Order Transaction Fact` OTF  left join `Invoice Dimension` DN on (DN.`Invoice Key`=OTF.`Invoice Key`) where `Invoice Type`='Invoice' and `Order Key`=%d and  OTF.`Invoice Key`>0", $order->id);

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    $invoice = get_object('Invoice', $row2['Invoice Key']);
                    if ($dn->id) {
                        $data_to_update['Order Invoice Key'] = $invoice->id;

                    }

                }
            }
        }


        $recipient    = $order->get('Order Customer Contact Name');
        $organization = $order->get('Order Customer Name');


        if ($organization == $recipient) {
            $organization = '';
        }

        $store           = get_object('Store',$order->get('Store Key'));
        $default_country = $store->get('Store Home Country Code 2 Alpha');

        $address_billing_fields_to_update = parse_old_order_billing_address_fields($store, $order->get('Order Billing To Key To Bill'), $recipient, $organization, $default_country);


        $ship_to = new Ship_To($order->get('Order Ship To Key To Deliver'));

        $recipient    = $ship_to->get('Ship To Contact Name');
        $organization = $ship_to->get('Ship To Company Name');


        if ($organization == $recipient) {
            $organization = '';
        }

        $address_shipping_fields_to_update = parse_old_order_shipping_address_fields($store, $ship_to, $recipient, $organization, $default_country);





        //print_r($data_to_update);
        //print_r($address_billing_fields_to_update);
        //print_r($address_shipping_fields_to_update);

        $order->fast_update($data_to_update);
        $order->fast_update($address_billing_fields_to_update);
        $order->fast_update($address_shipping_fields_to_update);


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
    print $sql;
    exit;
}


function parse_old_order_billing_address_fields($store, $address_key, $recipient, $organization, $default_country) {


    $address = new Billing_To($address_key);
    if ($address->id > 0) {


        if($address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '' ){
            $address->data['Billing To Country 2 Alpha Code'] =$default_country;
        }


        $address_format = get_address_format(
            (  ( $address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '' )  ? $default_country : $address->data['Billing To Country 2 Alpha Code'])
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
        $_address_fields['Order Invoice '.$key] = $value;
    }


    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $_address_fields['Order Invoice Address Recipient'],
                'Address Organization'         => $_address_fields['Order Invoice Address Organization'],
                'Address Line 1'               => $_address_fields['Order Invoice Address Line 1'],
                'Address Line 2'               => $_address_fields['Order Invoice Address Line 2'],
                'Address Sorting Code'         => $_address_fields['Order Invoice Address Sorting Code'],
                'Address Postal Code'          => $_address_fields['Order Invoice Address Postal Code'],
                'Address Dependent Locality'   => $_address_fields['Order Invoice Address Dependent Locality'],
                'Address Locality'             => $_address_fields['Order Invoice Address Locality'],
                'Address Administrative Area'  => $_address_fields['Order Invoice Address Administrative Area'],
                'Address Country 2 Alpha Code' => $_address_fields['Order Invoice Address Country 2 Alpha Code'],
            )
        )
    );

    $_address_fields['Order Invoice Address Checksum'] = $new_checksum;


    $account = get_object('Account', 1);
    $country = $account->get('Account Country 2 Alpha Code');
    $locale  = $store->get('Store Locale');


    list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


    $address = $address->withFamilyName($_address_fields['Order Invoice Address Recipient'])->withOrganization($_address_fields['Order Invoice Address Organization'])->withAddressLine1($_address_fields['Order Invoice Address Line 1'])->withAddressLine2(
        $_address_fields['Order Invoice Address Line 2']
    )->withSortingCode(
        $_address_fields['Order Invoice Address Sorting Code']
    )->withPostalCode($_address_fields['Order Invoice Address Postal Code'])->withDependentLocality(
        $_address_fields['Order Invoice Address Dependent Locality']
    )->withLocality($_address_fields['Order Invoice Address Locality'])->withAdministrativeArea(
        $_address_fields['Order Invoice Address Administrative Area']
    )->withCountryCode(
        $_address_fields['Order Invoice Address Country 2 Alpha Code']
    );


    $xhtml_address = $formatter->format($address);


    $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
    $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


    $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);

    //  print $xhtml_address;

    $_address_fields['Order Invoice Address Formatted'] = $xhtml_address;
    /*
        $account=get_object('Account',1);
        $country = $account->get('Account Country 2 Alpha Code');
        $country = $store->get('Store Home Country Code 2 Alpha');

        $locale  = $store->get('Store Locale');



        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);
    */


    $_address_fields['Order Invoice Address Postal Label'] = $postal_label_formatter->format($address);


    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $_address_fields;
}




function parse_old_order_shipping_address_fields($store, $address, $recipient, $organization, $default_country) {



    if ($address->id > 0) {


        if($address->data['Ship To Country 2 Alpha Code'] == 'XX' or $address->data['Ship To Country 2 Alpha Code'] == '' ){
            $address->data['Ship To Country 2 Alpha Code'] =$default_country;
        }


        $address_format = get_address_format(
            ( ($address->data['Ship To Country 2 Alpha Code'] == 'XX' or  $address->data['Ship To Country 2 Alpha Code'] == '') ? $default_country : $address->data['Ship To Country 2 Alpha Code'])
        );

        $_tmp = preg_replace('/,/', '', $address_format->getFormat());

        $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => $address->get('Ship To Line 1'),
            'Address Line 2'               => $address->get('Ship To Line 2'),
            'Address Sorting Code'         => '',
            'Address Postal Code'          => $address->get('Ship To Postal Code'),
            'Address Dependent Locality'   => $address->get('Ship To Line 3'),
            'Address Locality'             => $address->get('Ship To Town'),
            'Address Administrative Area'  => $address->get('Ship To Line 4'),
            'Address Country 2 Alpha Code' => ($address->data['Ship To Country 2 Alpha Code'] == 'XX' ? $default_country : $address->data['Ship To Country 2 Alpha Code']),

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

        if (!in_array('administrativeArea', $used_fields) and $address->get(
                'Ship To Line 4'
            ) != '') {
            $address_fields['Address Administrative Area'] = '';
            //print_r($address->data);
            //print_r($address_fields);

            //print $address->display();


            //exit;

            //print_r($used_fields);
            //print_r($address->data);
            //exit('administrativeArea problem');

        }

        if (!in_array('postalCode', $used_fields) and $address->get(
                'Ship To Postal Code'
            ) != '') {

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
                    'Ship To Town'
                ) != '' or $address->get('Ship To Line 4') != '')) {


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
        $_address_fields['Order Delivery '.$key] = $value;
    }


    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $_address_fields['Order Delivery Address Recipient'],
                'Address Organization'         => $_address_fields['Order Delivery Address Organization'],
                'Address Line 1'               => $_address_fields['Order Delivery Address Line 1'],
                'Address Line 2'               => $_address_fields['Order Delivery Address Line 2'],
                'Address Sorting Code'         => $_address_fields['Order Delivery Address Sorting Code'],
                'Address Postal Code'          => $_address_fields['Order Delivery Address Postal Code'],
                'Address Dependent Locality'   => $_address_fields['Order Delivery Address Dependent Locality'],
                'Address Locality'             => $_address_fields['Order Delivery Address Locality'],
                'Address Administrative Area'  => $_address_fields['Order Delivery Address Administrative Area'],
                'Address Country 2 Alpha Code' => $_address_fields['Order Delivery Address Country 2 Alpha Code'],
            )
        )
    );

    $_address_fields['Order Delivery Address Checksum'] = $new_checksum;


    $account=get_object('Account',1);
    $country = $account->get('Account Country 2 Alpha Code');
    $locale  = $store->get('Store Locale');



    list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


    $address =
        $address->withFamilyName($_address_fields['Order Delivery Address Recipient'])->withOrganization($_address_fields['Order Delivery Address Organization'])->withAddressLine1($_address_fields['Order Delivery Address Line 1'])->withAddressLine2($_address_fields['Order Delivery Address Line 2'])->withSortingCode(
            $_address_fields['Order Delivery Address Sorting Code'])->withPostalCode($_address_fields['Order Delivery Address Postal Code'])->withDependentLocality(
            $_address_fields['Order Delivery Address Dependent Locality'])->withLocality($_address_fields['Order Delivery Address Locality'])->withAdministrativeArea(
            $_address_fields['Order Delivery Address Administrative Area'])->withCountryCode(
            $_address_fields['Order Delivery Address Country 2 Alpha Code']);


    $xhtml_address = $formatter->format($address);



    $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
    $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


    $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);

    //  print $xhtml_address;

    $_address_fields['Order Delivery Address Postal Label'] =$postal_label_formatter->format($address); ;




    $_address_fields['Order Delivery Address Formatted'] = $xhtml_address;



    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $_address_fields;
}


function trim_value(&$value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
}



?>
