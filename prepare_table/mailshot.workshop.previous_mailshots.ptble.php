<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 February 2019 at 00:59:43 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

$group='';


$where=sprintf(' where `Email Campaign Email Template Type Key`=%d and  `Email Campaign State`="Sent" and  `Email Campaign Type`=%s',$parameters['email_campaign_type_key'],
               prepare_mysql($mailshot->get('Email Campaign Type'))
    );


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Email Campaign Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Email Campaign Name`';
} elseif ($order == 'date') {
    $order = '`Email Campaign Last Updated Date`';
}elseif ($order == 'subject') {
    $order = '`Email Campaign Last Updated Date`';
}  elseif ($order == 'open') {
    $order = '`Email Campaign Open`/`Email Campaign Delivered`';
}elseif ($order == 'clicked') {
    $order = '`Email Campaign Clicked`/`Email Campaign Delivered`';
} elseif ($order == 'spam') {
    $order = '`Email Campaign Spams`/`Email Campaign Delivered`';
}else {
    $order = '`Email Campaign Key`';
}
$table  = '`Email Campaign Dimension` C 

left join `Email Template Dimension` T on (T.`Email Template Key`=C.`Email Campaign Email Template Key`) 

left join `Store Dimension` S on (S.`Store Key`=C.`Email Campaign Store Key`) 


';
$fields = "`Email Campaign Key`,`Email Campaign Name`,`Email Campaign Store Key`,S.`Store Code`,`Store Name`,`Email Campaign Last Updated Date`,`Email Campaign Store Key`,
`Email Campaign Sent`,`Email Campaign Delivered`,`Email Campaign Hard Bounces`,`Email Campaign Soft Bounces`,`Email Campaign Open`,`Email Campaign Clicked`,`Email Campaign Spams`,
`Email Template Subject`
";


$sql_totals = "select count(*) as num from $table $where ";


?>
