<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 March 2020  23:37::27  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

//print_r($parameters);

$group_by = '';

$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Product Code`';
} elseif ($order == 'name') {
    $order = '`Product Name`';
}   else {
    $order = '`Product ID`';
}


if($parameters['parent']=='store'){
    $where = sprintf(
        "where `Product Store Key`=%d and `Webpage State`='Online' ", $parameters['parent_key']
    );
}elseif($parameters['parent']=='department'){
    $where = sprintf(
        "where `Product Department Category Key`=%d and  `Product Store Key`=%d  and `Webpage State`='Online' ", $parameters['parent_key'],$parameters['store_key']
    );
}elseif($parameters['parent']=='family'){
    $where = sprintf(
        "where `Product Family Category Key`=%d and  `Product Store Key`=%d  and `Webpage State`='Online' ", $parameters['parent_key'],$parameters['store_key']
    );
    $order='`Product ID`';
    $order_direction='';
}



$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Product Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}






$fields
       = "`Store Currency Code`,`Product ID`, `Webpage URL`,`Product Webpage Key`,`Product Code`,`Product Name`,`Product Units per Case`,`Product Price`,`Product RRP`,`Image Key`,`Image File Format`,`Product Availability State`,`Product Status`
      

        ";
$table = '`Product Dimension` P left join `Page Store Dimension` W on (`Page Key`=`Product Webpage Key`) left join `Store Dimension` on (`Store Key`=`Product Store Key`) left join `Image Dimension` on (`Image Key`=`Product Main Image Key`) ';

$sql_totals = "select count(distinct `Product ID`) as num from $table $where";



