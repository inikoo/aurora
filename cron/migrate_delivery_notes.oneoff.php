<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 January 2017 at 11:05:48 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

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

require_once 'class.DeliveryNote.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


$sql = sprintf('SELECT `Delivery Note Key` FROM `Delivery Note Dimension` order by `Delivery Note Key` desc ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $dn = new DeliveryNote($row['Delivery Note Key']);



       $sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Delivery Note Key`=%d and  `Order Key`>0", $dn->id);

		if ($result2=$db->query($sql)) {
		    if ($row2 = $result2->fetch()) {
		        print $dn->id."\r";

		        $dn->update(array('Delivery Note Order Key'=>$row2['Order Key']),'no_history');
			}
		}else {
			print_r($error_info=$db->errorInfo());
			print "$sql\n";
			exit;
		}
       
       
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}



?>
