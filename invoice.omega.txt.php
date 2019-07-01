<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:09-05-2019 09:49:20 CEST , Tranava, Sloavakia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


require_once 'common.php';

require_once 'utils/object_functions.php';
require_once 'utils/omega_export_functions.php';


$tab      = 'invoices';

if (isset($_SESSION['table_state'][$tab])) {
    $number_results  = $_SESSION['table_state'][$tab]['nr'];
    $start_from      = 0;
    $order           = $_SESSION['table_state'][$tab]['o'];
    $order_direction = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
    $f_value         = $_SESSION['table_state'][$tab]['f_value'];
    $parameters      = $_SESSION['table_state'][$tab];
} else {

    $default = $user->get_tab_defaults($tab);

    $number_results           = $default['rpp'];
    $start_from               = 0;
    $order                    = $default['sort_key'];
    $order_direction          = ($default['sort_order'] == 1 ? 'desc' : '');
    $f_value                  = '';
    $parameters               = $default;
    $parameters['parent']     = $data['parent'];
    $parameters['parent_key'] = $data['parent_key'];

}

include_once 'prepare_table/'.$tab.'.ptble.php';

$text = "R00\tT00\r\n";


$sql="select `Invoice Key` from  $table   $where $wheref ";

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    $invoice=get_object('Invoice',$row['Invoice Key']);
    $text.=get_omega_export_text($db,$account,$invoice);

}






$encoded_text = iconv( mb_detect_encoding( $text ), 'ISO-8859-15//TRANSLIT', utf8_encode($text) );
header('Content-Type: text/plain');
header("Content-Disposition: attachment; filename=invoices.txt");


print $encoded_text;