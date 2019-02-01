<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  30 January 2019 at 15:02:57 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

require_once 'common.php';

$sql = 'select `Supplier Part Key` from `Supplier Part Production Dimension`';

$stmt = $db->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        $production_part = get_object('production_part', $row['Supplier Part Key']);
        $production_part->update_available_to_make_up();
    }
} else {
    print_r($error_info = $db->errorInfo());
    exit();
}