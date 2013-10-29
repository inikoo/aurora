<?php

$table_key=9;

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
		case 'P.``Product ID``':
			$field_label=_('Id');
			break;
		case '`Product Code`':
			$field_label=_('Code');
			break;
		case '`Product Name`':
			$field_label=_('Name');
			break;
		case '`Product Price`':
			$field_label=_('Price');
			break;	
		case '`Product Tariff Code`':
			$field_label=_('Tariff Code');
			break;			
		case '`Product Duty Rate`':
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

$smarty->assign('number_export_products_fields',count($export_fields));

$smarty->assign('export_products_fields',$export_fields);
$smarty->assign('export_products_map','Default');
$smarty->assign('export_products_map_is_default',true);


?>