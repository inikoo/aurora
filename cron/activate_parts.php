<?php

require_once __DIR__.'/cron_common.php';
/** @var PDO $db */

require_once 'class.Part.php';
require_once 'class.Category.php';


$print_est = true;


update_parts_data($db);

function update_parts_data($db) {


    $sql = "SELECT `Part SKU` FROM `Part Dimension`  where `Part Status`='In Process'   ORDER BY `Part Reference`   ";

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);
            print $part->get('Reference')."\n";
            $part->activate();

        }

    }
}

