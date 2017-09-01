<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 September 2017 at 20:01:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
require_once 'class.Agent.php';


$sql = sprintf('SELECT `Agent Key` FROM `Agent Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $agent = new Agent($row['Agent Key']);
        $code  = $agent->get('Code');

        $line_number = 1;
        $sql         = sprintf(
            "SELECT `Purchase Order Public ID` FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`='Agent' AND `Purchase Order Parent Key`=%d ORDER BY REPLACE(`Purchase Order Public ID`,%s,'') DESC LIMIT 1",
            $agent->id, prepare_mysql($code)
        );

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $line_number = (int)preg_replace('/[^\d]/', '', preg_replace('/^'.$code.'/', '', $row2['Purchase Order Public ID'])) + 1;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $agent->update(
            array(
                'Agent Order Public ID Format' => $agent->get('Code').'%03d',
                'Agent Order Last Order ID'    => $line_number,

            ), 'no_history'
        );

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


$sql = sprintf('SELECT `Supplier Key` FROM `Supplier Dimension`  ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $supplier = new Supplier($row['Supplier Key']);


        $code = $supplier->get('Code');
        $code=str_replace("(","",$code);
        $code=str_replace(")","",$code);

        $line_number = 1;
        $sql         = sprintf(
            "SELECT `Purchase Order Public ID` FROM `Purchase Order Dimension` WHERE `Purchase Order Parent`='Supplier' AND `Purchase Order Parent Key`=%d ORDER BY REPLACE(`Purchase Order Public ID`,%s,'') DESC LIMIT 1",
            $supplier->id,
            prepare_mysql($code)
        );

        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                print '-->'.$code."<-\n";
                print '-->'.preg_replace('/^'.$code.'/', '', $row2['Purchase Order Public ID'])."<-\n";

                $line_number = (int)preg_replace('/[^\d]/', '', preg_replace('/^'.$code.'/', '', $row2['Purchase Order Public ID'])) + 1;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $supplier->update(
            array(
                'Supplier Order Public ID Format' => $supplier->get('Code').'%03d',
                'Supplier Order Last Order ID'    => $line_number,

            ), 'no_history'
        );

    }


} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


?>
