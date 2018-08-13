<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2018 at 13:38:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';




require_once 'class.Store.php';
require_once 'class.Invoice.php';


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


$account = new Account();



$sql = sprintf("SELECT `Invoice Key` FROM `Invoice Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $invoice = new Invoice('id', $row['Invoice Key']);

        $invoice->update_billing_region();
        $invoice->categorize();
      //  exit;
    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

?>
