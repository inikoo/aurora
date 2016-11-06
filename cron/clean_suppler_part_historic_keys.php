<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:19 April 2016 at 11:32:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.SupplierPart.php';


redo_supplier_part_historic_keys($db);

function redo_supplier_part_historic_keys($db) {

    $sql = 'truncate `Supplier Part Historic Dimension`';
    $db->exec($sql);


    $sql = sprintf(
        'SELECT `Supplier Part Key` FROM `Supplier Part Dimension`   '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $supplier_part = new SupplierPart($row['Supplier Part Key']);

            $supplier_part->update_historic_object();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


?>
