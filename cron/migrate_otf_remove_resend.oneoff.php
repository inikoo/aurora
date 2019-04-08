<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2019 at 17:41:45 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();

$store_key = 5;

$sql = 'select count(*) as num  from `Order Transaction Fact` where `Store Key`=? and `Order Transaction Type`="Resend"  ';
$stmt = $db->prepare($sql);
if ($stmt->execute(
    array(
        $store_key
    )
)) {
    if ($row = $stmt->fetch()) {
        print $row['num']."\n";
    } else {

    }
} else {
    print_r($error_info = $db->errorInfo());
    exit();
}

$counter=0;

foreach (range(0, 500) as $number) {


    $sql = 'select * from `Order Transaction Fact` where `Store Key`=? and `Order Transaction Type`="Resend" LIMIT 10000  ';

    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $store_key
        )
    )) {
        while ($row = $stmt->fetch()) {
            $sql = sprintf(
                'insert into `Migration Data` (`Migration Data Scope`,`Migration Data Scope Key`,`Migration Data`)  values (%s,%d,%s)',
                prepare_mysql('OTF_Resend'), $row['Order Transaction Fact Key'], prepare_mysql(json_encode($row))
            );
            //print "$sql\n";
            $db->exec($sql);
            $sql = sprintf(
                'delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d ',
                $row['Order Transaction Fact Key']
            );
            //  print "$sql\n";
            $db->exec($sql);

            $counter++;

            print $counter."\r";

        }
    } else {
        print_r($error_info = $this->db->errorInfo());
        exit();
    }

}

?>
