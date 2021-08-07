<?php
/*
About:
Author: Raul Perusquia <raul@inikoo.com>
Created:09-05-2019 09:49:20 CEST , Tranava, Slovakia

Copyright (c) 2018, Inikoo

Version 2.0
*/
/** @var User $user */
/** @var Smarty $smarty */
/** @var PDO $db */
/** @var Account $account */

require_once 'common.php';
if ($user->get('User View') != 'Staff') {
    exit;
}

require_once 'utils/omega_export_functions.php';

include_once 'prepare_table/invoices.ptc.php';
$table = new prepare_table_invoices($db, $account, $user);
$table->initialize_from_session('invoices');
$table->prepare_table();

$text = "R00\tT00\r\n";


$sql = " `Invoice Key` from  $table->table $table->where $table->wheref";
$stmt = $db->prepare('select '.$sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $invoice = get_object('Invoice', $row['Invoice Key']);
    $text    .= get_omega_export_text($invoice);

}

$encoded_text = iconv(mb_detect_encoding($text), 'ISO-8859-15//TRANSLIT', utf8_encode($text));
header('Content-Type: text/plain');
header("Content-Disposition: attachment; filename=invoices.txt");

print $encoded_text;