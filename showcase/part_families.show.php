<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 June 2016 at 18:40:38 BST, Sheffield UK

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_part_familes_showcase($data, $smarty) {

    $category = $data['_object'];
    if (!$category->id) {
        return "_";
    }

    $smarty->assign('category', $category);


    return $smarty->fetch('showcase/part_families.tpl');


}


?>
