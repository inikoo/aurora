<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 September 2017 at 02:33:57 GMT+8, Kuala Lumpur,, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


require_once 'utils/object_functions.php';
require_once 'utils/parse_natural_language.php';



    $sql = sprintf('SELECT `Webpage Scope Key` FROM `Page Store Dimension` WHERE `Webpage Scope`="Category Categories" ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $category = get_object('Category',$row['Webpage Scope Key']);

          //  print_r($category);

            $category->create_stack_index($force_reindex = false);



        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }



?>
