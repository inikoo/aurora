<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 12:26:02 GMT+8, Kuta, Bali Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/





$where=' where true ';


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Prospect First Contacted Date`');
    $where .= $where_interval['mysql'];

}







$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `User Alias` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


$order='';

$group_by
    = 'group by `Prospect Sales Representative Key`';

$table='  `Prospect Dimension` ';

$fields = "`Prospect Sales Representative Key`,count(Distinct `Prospect Key`) as new_prospects ,sum(`Prospect Calls Number`) calls,sum(`Prospect Emails Sent Number`) emails_sent,

sum(`Prospect Emails Open Number`)  emails_open,
sum(`Prospect Emails Clicked Number`)  emails_clicked,
sum(if(`Prospect Status`='Registered',1,0))  prospects_registered,
sum(if(`Prospect Status`='Invoiced',1,0))  prospects_invoiced


";








$sql_totals = "select count(Distinct `Prospect Sales Representative Key` )  as num from $table  $where  ";




?>
