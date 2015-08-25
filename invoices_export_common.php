<?php

$table_key=3;

$user_maps=array();
$user_map_selected_key=0;
$sql=sprintf("select * from `Table User Export Fields` where `Table Key`=%d",$table_key,$user->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['Map State']=='Selected')
		$user_map_selected_key=$row['Table User Export Fields Key'];
	$user_maps[$row['Table User Export Fields Key']]=array('key'=>$row['Table User Export Fields Key'],'name'=>$row['Map Name'],'selected'=>($row['Map State']=='Selected'?1:0),'fields'=>preg_split('/,/',$row['Fields']));
}

$export_fields=array();
$sql=sprintf("select `Table Export Fields` from `Table Dimension` where `Table Key`=%d",$table_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$default_fields=preg_split('/,/',$row['Table Export Fields']);
	foreach ($default_fields as $default_field) {
		list($field,$checked)=preg_split('/\|/',$default_field);
		switch ($field) {

		case '`Invoice Title`':
			$field_label=_('Type');
			break;
		case '`Invoice Public ID`':
			$field_label=_('Number');
			break;
		case '`Invoice Customer Name`':
			$field_label=_('Customer');
			break;
		case '`Invoice Date`':
			$field_label=_('Date');
			break;
		case '`Invoice Currency`':
			$field_label=_('Currency');
			break;
		case '`Invoice Total Net Amount`':
			$field_label=_('Net');
			break;
		case '`Invoice Total Tax Amount`':
			$field_label=_('Tax');
			break;	
		
			$field_label=_('Main Payment Type');
			break;
		case '`Payment Account Name`':
			$field_label=_('Main Payment Account');
			break;
		default:
			$field_label=$field;
		}

		if ($user_map_selected_key) {
			if (in_array($field,$user_maps[$user_map_selected_key]['fields']))
				$checked=1;
			else
				$checked=0;
		}
		$export_fields[]=array('label'=>$field_label,'name'=>$field,'checked'=>$checked);
	}
}

$smarty->assign('number_export_invoices_fields',count($export_fields));
$smarty->assign('export_invoices_fields',$export_fields);
$smarty->assign('export_invoices_map','Default');
$smarty->assign('export_invoices_map_is_default',true);


?>
