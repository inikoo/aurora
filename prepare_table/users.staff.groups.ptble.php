<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2015 at 17:59:33 GMT, Train (Sheffield-Mansfield)
 Copyright (c) 2015, Inikoo

 Version 3

*/

$sql_totals='';

$_order=$order;
$_dir=$order_direction;

$wheref='';
$where='';

$fields='sum(if(`User Active`="Yes",1,0)) as active_users , sum(if(`User Active`="No",1,0)) as inactive_users , count(*) as users ,`User Group Key` ';
$table='`User Group User Bridge` B left join `User Dimension` U on (B.`User Key`=U.`User Key`) ';
$group_by='group by `User Group Key`';
?>