<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  07 March 2020  12:16::09  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

$group_by = '';

if($parameters['parent']=='category'){
    $where = sprintf(
        "where `Category Parent Key`=%d and `Webpage State`='Online' and `Category Store Key`=%d and `Product Category Status`='Active' and `Product Category Public`='Yes'", $parameters['parent_key'], $parameters['store_key']
    );
    $table = '`Category Dimension` C   left join `Product Category Dimension` PCD on (C.`Category Key`=PCD.`Product Category Key`)  left join `Page Store Dimension` W on (`Page Key`=`Product Category Webpage Key`) ';

}elseif($parameters['parent']=='department'){
    $where = sprintf(
        "where B.`Category Key`=%d and `Webpage State`='Online' and `Category Store Key`=%d and `Product Category Status`='Active' and `Product Category Public`='Yes'", $parameters['parent_key'], $parameters['store_key']
    );
    $table = ' `Category Bridge` B left join    `Category Dimension` C   on (B.`Subject Key`=C.`Category Key` and `Subject`="Category")    left join `Product Category Dimension` PCD on (C.`Category Key`=PCD.`Product Category Key`)  left join `Page Store Dimension` W on (`Page Key`=`Product Category Webpage Key`) ';

}



$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Category Label` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}




$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = 'C.`Category Code`';
} elseif ($order == 'label') {
    $order = 'C.`Category Label`';
} elseif ($order == 'families') {
    $order = '`Category Number Subjects`';
} elseif ($order == 'products') {
    $order = '(`Product Category Active Products`+`Product Category Discontinuing Products`)';
}  else {
    $order = '`Category Label`';
}

$fields
       = " `Webpage URL`,`Category Number No Active Subjects`,`Category Number Active Subjects`,C.`Category Key`,`Category Branch Type`,`Category Children`,`Category Subject`,`Category Store Key`,`Category Warehouse Key`,`Category Code`,`Category Label`,`Category Number Subjects`,`Category Subjects Not Assigned`,
       (`Product Category Active Products`+`Product Category Discontinuing Products`) as products
      

        ";

$sql_totals = "select count(distinct C.`Category Key`) as num from $table $where";



