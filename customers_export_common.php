<?php

$table_key=1;

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

		case 'C.`Customer Key`':
			$field_label=_('ID');
			break;
		case '`Customer Name`':
			$field_label=_('Name');
			break;
		case '`Customer Main Contact Name`':
			$field_label=_('Contact');
			break;	
		case '`Customer Main Plain Email`':
			$field_label=_('Email');
			break;
			case '`Customer Tax Number`':
			$field_label=_('Tax Number');
			break;	
			case '`Customer Last Order Date`':
			$field_label=_('Last order date');
			break;		
		case '`Customer Address`':
			$field_label=_('Contact Address');
			break;	
		case 'Customer Address Elements':
			$field_label=_('Contact Address').' ('._('Elements').')';
			break;	
		case '`Customer Billing Address`':
			$field_label=_('Billing Address');
			break;	
		case 'Customer Billing Address Elements':
			$field_label=_('Billing Address').' ('._('Elements').')';
			break;	
		case '`Customer Delivery Address`':
			$field_label=_('Delivery Address');
			break;	
		case 'Customer Delivery Address Elements':
			$field_label=_('Delivery Address').' ('._('Elements').')';
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

$smarty->assign('number_export_customers_fields',count($export_fields));

$smarty->assign('export_customers_fields',$export_fields);
$smarty->assign('export_customers_map','Default');
$smarty->assign('export_customers_map_is_default',true);


?>