<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2017 at 09:34:36 CET, MIjas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
include_once 'utils/get_addressing.php';

require_once 'class.Customer.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$where = '  ';

$sql = sprintf("select count(*) as num from `Customer Dimension` left join `Store Dimension` on (`Store Key`=`Customer Store Key`) $where");
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
$account = get_object('Account', '');
$locale  = $account->get('Account Locale');

$sql = sprintf(
    "select `Customer Key` from `Customer Dimension`  left join `Store Dimension` on (`Store Key`=`Customer Store Key`)  $where order by `Customer Key` desc "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $customer = new Customer('id', $row['Customer Key']);




        $store   = get_object('Store', $customer->get('Store Key'));
        $country = $store->get('Store Home Country Code 2 Alpha');

        $type = 'Contact';

        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        if($customer->get($type.' Address Country 2 Alpha Code')!='') {

            $address =
                $address->withFamilyName($customer->get($type.' Address Recipient'))->withOrganization($customer->get($type.' Address Organization'))->withAddressLine1($customer->get($type.' Address Line 1'))->withAddressLine2($customer->get($type.' Address Line 2'))
                    ->withSortingCode(
                        $customer->get($type.' Address Sorting Code')
                    )->withPostalCode($customer->get($type.' Address Postal Code'))->withDependentLocality(
                        $customer->get($type.' Address Dependent Locality')
                    )->withLocality($customer->get($type.' Address Locality'))->withAdministrativeArea(
                        $customer->get($type.' Address Administrative Area')
                    )->withCountryCode(
                        $customer->get($type.' Address Country 2 Alpha Code')
                    );


            $xhtml_address = $formatter->format($address);


            if ($customer->get($type.' Address Recipient') == $customer->get('Main Contact Name')) {
                $xhtml_address = preg_replace('/(class="family-name">.+<\/span>)<br>/', '$1', $xhtml_address);
            }

            if ($customer->get($type.' Address Organization') == $customer->get('Company Name')) {
                $xhtml_address = preg_replace('/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address);
            }

            $xhtml_address = preg_replace(
                '/class="family-name"/', 'class="recipient fn '.(($customer->get($type.' Address Recipient') == $customer->get('Main Contact Name') and $type == 'Contact') ? 'hide' : '').'"', $xhtml_address
            );


            $xhtml_address = preg_replace(
                '/class="organization"/', 'class="organization org '.(($customer->get($type.' Address Organization') == $customer->get('Company Name') and $type == 'Contact') ? 'hide' : '').'"', $xhtml_address
            );
            $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
            $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


            //$xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


            $customer->fast_update(
                array(
                    'Customer '.$type.' Address Formatted'    => $xhtml_address,
                    'Customer '.$type.' Address Postal Label' => $postal_label_formatter->format($address),

                )
            );
        }
        $type = 'Delivery';

        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        if($customer->get($type.' Address Country 2 Alpha Code')!='') {

            $address =
                $address->withFamilyName($customer->get($type.' Address Recipient'))->withOrganization($customer->get($type.' Address Organization'))->withAddressLine1($customer->get($type.' Address Line 1'))->withAddressLine2($customer->get($type.' Address Line 2'))
                    ->withSortingCode(
                        $customer->get($type.' Address Sorting Code')
                    )->withPostalCode($customer->get($type.' Address Postal Code'))->withDependentLocality(
                        $customer->get($type.' Address Dependent Locality')
                    )->withLocality($customer->get($type.' Address Locality'))->withAdministrativeArea(
                        $customer->get($type.' Address Administrative Area')
                    )->withCountryCode(
                        $customer->get($type.' Address Country 2 Alpha Code')
                    );


            $xhtml_address = $formatter->format($address);


            $xhtml_address = preg_replace(
                '/class="family-name"/', 'class="recipient fn '.($customer->get($type.' Address Recipient') == $customer->get('Main Contact Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
            );


            $xhtml_address = preg_replace(
                '/class="organization"/', 'class="organization org '.($customer->get($type.' Address Organization') == $customer->get('Company Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
            );
            $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
            $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


            //$xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


            $customer->fast_update(
                array(
                    'Customer '.$type.' Address Formatted'    => $xhtml_address,
                    'Customer '.$type.' Address Postal Label' => $postal_label_formatter->format($address),

                )
            );
        }

        $type = 'Invoice';

        if($customer->get($type.' Address Country 2 Alpha Code')!='') {


            list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


            $address =
                $address->withFamilyName($customer->get($type.' Address Recipient'))->withOrganization($customer->get($type.' Address Organization'))->withAddressLine1($customer->get($type.' Address Line 1'))->withAddressLine2($customer->get($type.' Address Line 2'))
                    ->withSortingCode(
                        $customer->get($type.' Address Sorting Code')
                    )->withPostalCode($customer->get($type.' Address Postal Code'))->withDependentLocality(
                        $customer->get($type.' Address Dependent Locality')
                    )->withLocality($customer->get($type.' Address Locality'))->withAdministrativeArea(
                        $customer->get($type.' Address Administrative Area')
                    )->withCountryCode(
                        $customer->get($type.' Address Country 2 Alpha Code')
                    );


            $xhtml_address = $formatter->format($address);


            $xhtml_address = preg_replace(
                '/class="family-name"/', 'class="recipient fn '.($customer->get($type.' Address Recipient') == $customer->get('Main Contact Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
            );


            $xhtml_address = preg_replace(
                '/class="organization"/', 'class="organization org '.($customer->get($type.' Address Organization') == $customer->get('Company Name') and $type == 'Contact' ? 'hide' : '').'"', $xhtml_address
            );
            $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
            $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
            $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


            //$xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


            $customer->fast_update(
                array(
                    'Customer '.$type.' Address Formatted'    => $xhtml_address,
                    'Customer '.$type.' Address Postal Label' => $postal_label_formatter->format($address),

                )
            );

        }
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
    exit;
}