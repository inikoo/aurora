<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 October 2016 at 12:26:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';


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
//$store = new Store('id',1);
//$store->update_sales_from_invoices('Week To Day');
//exit;


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);

        try {
            $sql = "insert into `Store Emails Data`  (`Store Emails Store Key`)  values (?) ";
            $db->prepare($sql)->execute(
                [
                    $store->id
                ]
            );
        } catch (Exception $e) {
            //
        }


        $intervals = array(
            'Total',
            'Year To Date',
            'Quarter To Date',
            'Week To Date',
            'Month To Date',
            '1 Year',
            '1 Month',
            '1 Week',
            '1 Quarter',
            'Last Month',
            'Last Week',
            'Yesterday',
            'Today',
        );

        //enum('Newsletter','Marketing','GR Reminder','AbandonedCart','Invite Mailshot','OOS Notification','Invite Full Mailshot')

        $types     = ['Newsletter','Marketing','AbandonedCart','All'];


        foreach($intervals as $interval){
            foreach($types as $type){
                $store->update_emails_data($type, $interval, $this_year = true, $last_year = true);

            }
        }

    }
}



