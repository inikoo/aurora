<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo




function fork_edit_parts($job) {

	if (!$_data=get_fork_data($job))
		return;

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];
	$inikoo_account_code=$_data['inikoo_account_code'];



	



	$data=prepare_values($fork_data,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'subject_source_checked_type'=>array('type'=>'string'),
			'subject_source_checked_subjects'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),
			'f_value'=>array('type'=>'string'),
			'f_field'=>array('type'=>'string'),




		));
	$data['fork_key']=$fork_key;

	$number_parts=0;
	$number_parts_updated=0;
	$number_parts_no_change=0;
	$number_parts_errors=0;

print_r($data);

	if ($data['subject_source_checked_type']=='unchecked') {

		$subject_source_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
		$estimated_number_parts=count($subject_source_checked_subjects);

		$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
			$estimated_number_parts,
			$data['fork_key']
		);
		mysql_query($sql);
		foreach ($subject_source_checked_subjects as $subject_key) {
			$part= new Part($subject_key);
			if ($part->sku) {
				$number_parts++;
				$part->update(array($data['key']=>$data['value']));
				if ($part->error) {
					$number_parts_errors++;
				}elseif ($part->updated) {
					$number_parts_updated++;
				}else {
					$number_parts_no_change++;
				}

				$sql=sprintf("update `Fork Dimension` set `Fork Operations Done=%d where `Fork Key`",
					$number_parts,
					$data['fork_key']

				);
				print "$sql\n";
				mysql_query($sql);
			}
		}
	}
	else {

		$f_value=$data['f_value'];
		$f_field=$data['f_field'];


		$wheref='';
		if ($f_field=='used_in' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='description' and $f_value!='')
			$wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='supplied_by' and $f_value!='')
			$wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
		elseif ($f_field=='sku' and $f_value!='')
			$wheref.=" and  P.`Part SKU` ='".addslashes($f_value)."'";


		switch ($data['parent']) {
		case 'category':

			$sql=sprintf("select `Subject Key` as `Part SKU` from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`)  where `Subject`='Part' and `Category Key`=%d %s",$data['parent_key'],$wheref);

			break;
		case 'warehouse':
			$sql=sprintf("select B.`Part SKU` from `Part Warehouse Bridge`  from `Category Bridge` B left join `Part Dimension` P on (`Part SKU`=`Subject Key`) where `Warehouse Key`=%d %s",$data['parent_key'],$wheref);
			break;
		}

		$res=mysql_query($sql);

		if ($data['subject_source_checked_subjects']=='') {
			$no_checked_subjects=array();
		}else {
			$no_checked_subjects=preg_split('/,/',$data['subject_source_checked_subjects']);
		}

		$estimated_number_parts = mysql_num_rows($res)-count($no_checked_subjects);
		$sql_fork=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW() where `Fork Key`=%d ",
			$estimated_number_parts,
			$data['fork_key']
		);
		mysql_query($sql_fork);

		while ($row=mysql_fetch_assoc($res)) {
			if (!in_array($row['Part SKU'],$no_checked_subjects)) {
				$part= new Part($row['Part SKU']);
				if ($part->sku) {


					$number_parts++;
					$part->update(array($data['key']=>$data['value']));
					if ($part->error) {
						$number_parts_errors++;
					}elseif ($part->updated) {
						$number_parts_updated++;
					}else {
						$number_parts_no_change++;
					}
					$sql=sprintf("update `Fork Dimension` set `Fork Operations Done`=%d,`Fork Operations No Changed`=%d,`Fork Operations Errors`=%d where `Fork Key`=%d",
						$number_parts_updated,
						$number_parts_no_change,
						$number_parts_errors,
						$data['fork_key']

					);
					//print $sql;
					mysql_query($sql);

				}
			}

		}
		$sql_fork=sprintf("update `Fork Dimension` set `Fork State`='Finished'  where `Fork Key`=%d ",
			$data['fork_key']
		);
		mysql_query($sql_fork);

	}


}

?>
