<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 02 Aug 2021 01:39:10 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


use Aurora\Interfaces\TaxCategory\TaxCategoryProviderFactory;
use Aurora\Utilities\TaxNumber;
use Aurora\Utilities\Address;

include_once __DIR__.'/common.php';
/** @var PDO $db */


$settings = [
    'tax_authority' => 'EUR',
    'country'       => 'SK'
];

$order_parameters = [
    'invoice_country'   => 'DE',
    'delivery_country'  => 'SK',
    'invoice_postcode'  => '1234r5',
    'delivery_postcode' => '35000',
    'tax_number_valid'  => false
];


test_tax_category($db, $settings, $order_parameters);

function test_tax_category($db, $settings, $order_parameters)
{
    $address = new Address();

    $tax_number = new TaxNumber('123456', $order_parameters['tax_number_valid']);

    $provider     = TaxCategoryProviderFactory::createProvider($db, $settings['tax_authority'], ['RE' => false, 'base_country' => $settings['country']]);
    $tax_category = $provider->getTaxCategory(
        $address->setCountryCode($order_parameters['invoice_country'])->setPostalCode($order_parameters['invoice_postcode']),
        $address->setCountryCode($order_parameters['delivery_country'])->setPostalCode($order_parameters['delivery_postcode']),
        $tax_number
    );

    print_r($tax_category->get('Tax Category Code'));
}