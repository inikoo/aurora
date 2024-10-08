<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


require_once 'common.php';
/** @var User $user */

if ($user->get('User View') != 'Staff') {
    exit;
}

require_once 'utils/omega_export_functions.php';

$id = $_REQUEST['id'] ?? '';
if (!$id) {
    exit;
}

$invoice = get_object('Invoice', $id);

if (!$invoice->id) {
    exit;
}


$text = "R00\tT00\r\n";

$text.=get_omega_export_text($invoice);

$encoded_text = iconv( mb_detect_encoding( $text ), 'ISO-8859-15//TRANSLIT', utf8_encode($text) );
header('Content-Type: text/plain');
header("Content-Disposition: attachment; filename=".$invoice->get('Invoice Public ID').".txt");


print $encoded_text;
