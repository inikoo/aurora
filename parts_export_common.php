<?php

$table_key=5;

$user_maps=array();
$user_map_selected_key=0;
$sql=sprintf("select * from `Table User Export Fields` where `Table Key`=%d",$table_key,$user->id);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	if($row['Map State']=='Selected')
	$user_map_selected_key=$row['Table User Export Fields Key'];
	$user_maps[$row['Table User Export Fields Key']]=array('key'=>$row['Table User Export Fields Key'],'name'=>$row['Map Name'],'selected'=>($row['Map State']=='Selected'?1:0),'fields'=>preg_split('/,/',$row['Fields']));
}


$export_fields=array();
$sql=sprintf("select `Table Export Fields` from `Table Dimension` where `Table Key`=%d",$table_key);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
	$default_fields=preg_split('/,/',$row['Table Export Fields']);
	foreach($default_fields as $default_field){
		list($field,$checked)=preg_split('/\|/',$default_field);
		switch($field){

		case 'P.`Part SKU`':
			$field_label=_('SKU');
			break;
		case '`Part Reference`':
			$field_label=_('Reference');
			break;
		case '`Part Unit Description`':
			$field_label=_('Unit Description');
			break;	
		case '`Part Current Stock`':
			$field_label=_('Stock');
			break;
		case '`Part Tariff Code`':
			$field_label=_('Tariff Code');
			break;			
		case '`Part Duty Rate`':
			$field_label=_('Duty Rate');
			break;			
			
			
			
		default:
			$field_label=$field;
		}
		
		if($user_map_selected_key){
			if(in_array($field,$user_maps[$user_map_selected_key]['fields']))
			$checked=1;
			else
			$checked=0;
		}
		$export_fields[]=array('label'=>$field_label,'name'=>$field,'checked'=>$checked);
	}
}

$smarty->assign('number_export_parts_fields',count($export_fields));

$smarty->assign('export_parts_fields',$export_fields);
$smarty->assign('export_parts_map','Default');
$smarty->assign('export_parts_map_is_default',true);


?>