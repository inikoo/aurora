<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 June 2018 at 13:37:22 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/object_functions.php';


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


$sql = sprintf("SELECT `Email Campaign Key` FROM `Email Campaign Dimension` ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $mailshot = get_object('Email Campaign', $row['Email Campaign Key']);

        $mailshot->update_sent_emails_totals();

    }

}


$sql = sprintf("SELECT `Email Campaign Type Key` FROM `Email Campaign Type Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $email_template_type = get_object('Email Campaign Type', $row['Email Campaign Type Key']);

        $email_template_type->update_sent_emails_totals();
        $email_template_type->update_number_subscribers();

    }

}

