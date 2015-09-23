<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 21:07:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
if (!isset($rtext_label)) {
	$rtext_label='';
}

global $user;

$parameters=$_data['parameters'];
$number_results=$_data['nr'];
$start_from=($_data['page']-1)*$number_results;
$order=(isset($_data['o'])  ?$_data['o']:'id');
$order_direction=((isset($_data['od']) and  preg_match('/desc/i',$_data['od']) ) ?'desc':'');

if (isset($_data['f_value']) and $_data['f_value']!='') {
	$f_value=$_data['f_value'];
}else {
	$f_value='';
}

foreach ( $parameters as $parameter=>$parameter_value) {
	$_SESSION['table_state'][$_data['parameters']['tab']][$parameter]=$parameter_value;

}


$_SESSION['table_state'][$_data['parameters']['tab']]['o']=$order;
$_SESSION['table_state'][$_data['parameters']['tab']]['od']=($order_direction==''?-1:1);
$_SESSION['table_state'][$_data['parameters']['tab']]['nr']=$number_results;
$_SESSION['table_state'][$_data['parameters']['tab']]['f_value']=$f_value;



include_once 'prepare_table/'.$_data['parameters']['tab'].'.ptble.php';
list($rtext,$total)=get_table_totals($sql_totals,$wheref,$rtext_label);



?>
