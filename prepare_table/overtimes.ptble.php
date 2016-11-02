<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 14:54:54 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$where = 'where true';


$wheref = '';

if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Overtime Reference`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'reference') {
    $order = '`Overtime Reference`';
} elseif ($order == 'start') {
    $order = '`Overtime Start Date`';
} elseif ($order == 'end') {
    $order = '`Overtime End Date`';
} else {
    $order = '`Overtime Key`';
}


$table = '  `Overtime Dimension` as O  ';

$sql_totals = "select count(*) as num from $table  $where  ";

$fields
    = "`Overtime Key`,`Overtime Reference`,`Overtime Start Date`,`Overtime End Date`,`Overtime Status`";

?>
