<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:2 April 2019 at 13:43:19 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';


$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Migration from inikoo)',
    'Author Alias' => 'System (Migration from inikoo)',


);


$account = new Account();


$sql = "select `Store Code`,`Webpage Code`,`Page Key` from `Page Store Dimension`  left join `Category Dimension` on (`Webpage Scope Key`=`Category Key`)  
left join `Store Dimension` on (`Webpage Store Key`=`Store Key`)  
      where  `Webpage Scope`='Category Products' and `Category Code` is null and `Store Version`=2 ;";

$stmt = $db->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
        print_r($row);
        $page         = get_object('Webpage', $row['Page Key']);
        $page->editor = $editor;
        $page->delete();
    }
} else {
    print_r($error_info = $this->db->errorInfo());
    exit();
}


?>
