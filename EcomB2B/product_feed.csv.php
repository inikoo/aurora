<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2018 at 00:55:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'common.php';



header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");

$sql=sprintf("select `Product Name`,`Product Code`,`Webpage URL`,`Product Main Image` ,`Website URL` from  `Product Dimension`  left join `Page Store Dimension`  on (`Product Webpage Key`=`Page Key`)  left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Webpage Website Key`=%d  ", $website->id);


if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            printf("%s,%s,%s,%s\n",
                   $row['Product Code'],
                   $row['Product Name'],
                   'https://'.$row['Website URL'].'/'.$row['Product Main Image'],
                   $row['Webpage URL']
            );
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


?>
