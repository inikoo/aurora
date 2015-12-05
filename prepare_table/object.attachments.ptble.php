<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 4 December 2015 at 21:45:44 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
case 'employee':
	$where=sprintf(" where `Subject`='Staff' and `Subject Key`=%d ", $parameters['parent_key']);
	break;
default:
	$where=" where  false ";
	break;
}






$wheref='';
if ($parameters['f_field']=='caption' and $f_value!=''  ) {
	$wheref=sprintf('  and  Attachment Caption`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}


$_order=$order;
$_dir=$order_direction;



if ($order=='handle')
	$order='`User Handle`';
elseif ($order=='size')
	$order='`Attachment File Size`';
elseif ($order=='file')
	$order='`Attachment File Original Name`';
elseif ($order=='visibility')
	$order='`Attachment Public`';
elseif ($order=='caption')
	$order='`Attachment Caption`';
elseif ($order=='type')
	$order='`Attachment Subject Type`';
elseif ($order=='file_type')
	$order='`Attachment Type`,`Attachment MIME Type`';
elseif ($order=='file')
	$order='`Attachment File Original Name`';
else
	$order='B.`Attachment Key`';




$table=' `Attachment Bridge` B  left join `Attachment Dimension` A on (A.`Attachment Key`=B.`Attachment Key`) ';

$sql_totals="select count(Distinct B.`Attachment Key`) as num from $table  $where  ";


$fields="B.`Attachment Key`,`Attachment Subject Type`,`Attachment Caption`,`Attachment File Original Name`,`Attachment Public`,`Attachment MIME Type`,`Attachment Type`,`Attachment File Size`";


?>
