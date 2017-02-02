<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 18 October 2015 at 18:14:31 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where  = " where  `Staff Currently Working`='Yes'  ";
$wheref = '';


$group_by = ' group by `Role Code`';

$table = '`Staff Role Bridge` B left join `Staff Dimension` S on (B.`Staff Key`=S.`Staff Key`)  ';

$sql_totals = false;

$fields = "`Role Code`,count(*) as employees";

?>
