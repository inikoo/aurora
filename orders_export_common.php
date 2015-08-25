<?php

$table_key=2;

$user_maps=array();
$user_map_selected_key=0;
$sql=sprintf("select * from `Table User Export Fields` where `Table Key`=%d",$table_key,$user->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['Map State']=='Selected')
		$user_map_selected_key=$row['Table User Export Fields Key'];
	$user_maps[$row['Table User Export Fields Key']]=array('key'=>$row['Table User Export Fields Key'],'name'=>$row['Map Name'],'selected'=>($row['Map State']=='Selected'?1:0),'fields'=>preg_split('/,/',$row['Fields']));
}

//`Order Public ID`|1,`Order Customer Name`|1,`Order Date`|1,`Order Currency`|1,`Order Balance Total Amount`|1

$export_fields=array();
$sql=sprintf("select `Table Export Fields` from `Table Dimension` where `Table Key`=%d",$table_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$default_fields=preg_split('/,/',$row['Table Export Fields']);
	foreach ($default_fields as $default_field) {
		list($field,$checked)=preg_split('/\|/',$default_field);
		switch ($field) {

		case '`Order Public ID`':
			$field_label=_('Order Number');
			break;
		case '`Order Customer Name`':
			$field_label=_('Customer Name');
			break;
		case '`Order Date`':
			$field_label=_('Date');
			break;
		case '`Order Currency`':
			$field_label=_('Currency');
			break;
		case '`Order Balance Total Amount`':
			$field_label=_('Total Balance');
			break;
		case '`Payment Type`':
			$field_label=_('Payment Type');
			break;
		case '`Payment Account Name`':
			$field_label=_('Payment Account');
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

$smarty->assign('number_export_orders_fields',count($export_fields));
$smarty->assign('export_orders_fields',$export_fields);
$smarty->assign('export_orders_map','Default');
$smarty->assign('export_orders_map_is_default',true);


?>
