<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2019 at 21:10:03 GMT+8, Kuala Lumpur, Malaysia
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


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Migration from inikoo)',
    'Author Alias' => 'System (Migration from inikoo)',


);


$store_key = 8;

$print_est = false;



$sql = sprintf('SELECT `Customer Key`,`Customer Account Balance` FROM `Customer Dimension`  where `Customer Store Key`=%d and `Customer Account Balance`!=0  ', $store_key);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $customer = get_object('Customer', $row['Customer Key']);

       // print_r($row);
//exit;
        $customer->editor = $editor;
        $customer->set_account_balance_adjust($customer->get('Customer Account Balance'), _('Carry on balance'));
        $customer->update_account_balance();
        exit;

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
