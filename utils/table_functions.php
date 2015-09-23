<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 13:54:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


function get_table_parameters() {

	return  prepare_values($_REQUEST,array(
			'parameters'=>array('type'=>'json array'),
			'nr'=>array('type'=>'number'),
			'page'=>array('type'=>'number'),
			'o'=>array('type'=>'string','optional'=>true),
			'od'=>array('type'=>'string','optional'=>true),
			'f_value'=>array('type'=>'string','optional'=>true),

		));
}


function get_table_totals($sql_totals,$wheref='',$record_label='') {


	global $db;


	$sql=trim($sql_totals." $wheref");

	if ($row = $db->query($sql)->fetch()) {
		$total=$row['num'];
	}


	if ($wheref!='') {
		$sql=$sql_totals;
		if ($row = $db->query($sql)->fetch()) {
			$total_records=$row['num'];
			$filtered=$row['num']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}





	if ($filtered==0) {
		$rtext=get_rtext($record_label,$total_records);
	}else {
		$rtext='<i class="fa fa-filter fa-fw"></i> '. get_rtext_with_filter($record_label,$total_records);
	}

	return array($rtext,$total);

}

function get_rtext($record_label,$total_records) {
	if ($record_label=='customer') {
		return sprintf(ngettext('%s customer', '%s customers', $total_records), number($total_records));
	}elseif ($record_label=='order') {
		return sprintf(ngettext('%s order', '%s orders', $total_records), number($total_records));
	}elseif ($record_label=='store') {
		return sprintf(ngettext('%s store', '%s stores', $total_records), number($total_records));
	}elseif ($record_label=='category') {
		return sprintf(ngettext('%s category', '%s categories', $total_records), number($total_records));
	}else {
		return sprintf(ngettext('%s record', '%s records', $total_records), number($total_records));
	}
}
function get_rtext_with_filter($record_label,$total_records) {
	if ($record_label=='customer') {
		return sprintf(ngettext('%s customer of %s', '%s customers of %s', $total_records), number($total_records));
	}elseif ($record_label=='order') {
		return sprintf(ngettext('%s order of %s', '%s orders of %s', $total_records), number($total_records));
	}elseif ($record_label=='store') {
		return sprintf(ngettext('%s store of %s', '%s stores of %s', $total_records), number($total_records));
	}elseif ($record_label=='category') {
		return sprintf(ngettext('%s category of %s', '%s categories of %s', $total_records), number($total_records));
	}else {
		return sprintf(ngettext('%s record of %s', '%s records of %s', $total_records), number($total_records));
	}
}


?>
