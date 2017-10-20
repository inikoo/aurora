<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 October 2017 at 00:07:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'utils/aes.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';
require_once 'class.Material.php';
require_once 'class.Page.php';

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
include_once 'utils/object_functions.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();





  $sql = sprintf("select `Part SKU` from `Part Dimension` where `Part Status`='Discontinuing' and `Part Current On Hand Stock`<=0 and `Part Current Stock In Process`=0; ");

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->discontinue_trigger();

        }

    }
    
  $sql = sprintf("select `Part SKU` from `Part Dimension` where `Part Status`='Not In Use' and `Part Current On Hand Stock`>0 and `Part Current Stock In Process`>0");

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->discontinue_trigger();

        }

    }
    
  $sql = sprintf("select `Part SKU` from `Part Dimension` where `Part Status`='Not In Use' and `Part Current On Hand Stock`<0  ");

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->discontinue_trigger();

        }

    }



?>
