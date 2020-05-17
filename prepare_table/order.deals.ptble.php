<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2018 at 10:56:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$group_by=' group by DB.`Deal Key`,`Deal Info`';

$where = sprintf(
    ' where  DB.`Order Key`=%d', $parameters['parent_key']
);


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Deal Name`';
}else if ($order == 'description') {
    $order = '`Deal Info`';
}else if ($order == 'current_deal_status') {
    $order = '`Deal Status`';
}else if ($order == 'items') {
    $order = 'items';
}else if ($order == 'discount_percentage') {
    $order = 'discount_percentage';
}else if ($order == 'amount_discounted') {
    $order = 'amount_discounted';
} else if ($order == 'bonus') {
    $order = 'bonus';
} else {
    $order = 'DB.`Deal Key`';
}



$table = '`Order Transaction Deal Bridge` DB   left join  `Deal Dimension` D  on (D.`Deal Key`=DB.`Deal Key`)  left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) left join `Order Dimension` O on (DB.`Order Key`=O.`Order Key`) left join `Store Dimension` on (`Order Store Key`=`Store Key`) left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=DB.`Order Transaction Fact Key`)   ';


$fields = "DB.`Deal Key`,`Deal Name`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,`Store Currency Code`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`,`Store Bulk Discounts Campaign Key`,
`Deal Description`, DB.`Order Transaction Fact Key`,`Deal Info`,`Order Transaction Deal Metadata`,`Order Transaction Deal Pinned`,
count(*) as items,avg(`Fraction Discount`) as discount_percentage,sum(`Amount Discount`) amount_discounted,sum(`Bonus Quantity`) bonus, `Deal Term Allowances Label`,D.`Deal Key`
";


$sql_totals = "select count(*) as num from $table $where ";



?>
