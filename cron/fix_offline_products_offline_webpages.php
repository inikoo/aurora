<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 220 March 2017 at 11:14:19 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);






$sql = sprintf(

    '
SELECT `Product ID`,`Page Key`,`Page Store Key` ,`Page Store Section`,`Page Parent Code`,`Product Status`,`Product Web State` FROM `Page Store Dimension` left join `Product Dimension` on (`Webpage Scope key`=`Product ID`)   WHERE `Webpage Scope`="Product" and `Product Status`="Active"  and  `Product Web Configuration`="Offline" and  `Webpage State`="Online" 

');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        print_r($row);
        print "Unpublish\n";
        $webpage=new Page($row['Page Key']);
        $webpage->unpublish();

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


$sql = sprintf(
    "
select  `Page Key`,`Product Web State`,`Product Web Configuration`,`Webpage State`,`Page State`,`Product Store Key`,`Product Code` from `Product Dimension` left join `Page Store Dimension` on (`Page Key`=`Product Webpage Key`) where `Product Web State`='For Sale' and `Product Web Configuration`='Online Auto' and `Webpage State`='Offline';
"

);



if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $webpage=new Page($row['Page Key']);
        print "publish  \n";
        print_r($row);
        $webpage->publish();

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

?>
