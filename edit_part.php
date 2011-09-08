<?php
include_once('common.php');
include_once('class.Part.php');



$create=$user->can_create('parts');
$modify=$user->can_edit('parts');
$modify_stock=$user->can_edit('product stock');

$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$smarty->assign('view_suppliers',$view_suppliers);


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

$view_cust=$user->can_view('customers');
$smarty->assign('view_cust',$view_cust);

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {
    $part_id=$_REQUEST['id'];
    $_SESSION['state']['part']['id']=$part_id;
    $part= new part($part_id);
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
} else if (isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku'])) {
    $part= new part('sku',$_REQUEST['sku']);
    $part_id=$part->id;
    $_SESSION['state']['part']['id']=$part_id;
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
} else {
    $part_id=$_SESSION['state']['part']['id'];
    $_SESSION['state']['part']['id']=$part_id;
    $part= new part($part_id);
    $_SESSION['state']['part']['sku']=$part->data['Part SKU'];
}

if(!$part= new Part($part_id))
  exit('Error product not found');


$smarty->assign('part',$part);

$general_options_list=array();

$warehouse_key=0;




if($warehouse_key){
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse.php?id='.$warehouse->id,'label'=>_('Warehouse'));
$general_options_list[]=array('tipo'=>'url','url'=>'locations.php?warehouse_id='.$warehouse->id,'label'=>_('Locations'));
$general_options_list[]=array('tipo'=>'url','url'=>'parts.php?warehouse_id='.$warehouse->id,'label'=>_('Parts'));
}else{
$general_options_list[]=array('tipo'=>'url','url'=>'warehouses.php','label'=>_('Warehouse'));
$general_options_list[]=array('tipo'=>'url','url'=>'locations.php','label'=>_('Locations'));
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse_parts.php','label'=>_('Parts'));

}


$smarty->assign('general_options_list',$general_options_list);



//$product->load_images_slidesshow();
//$images=$product->images_slideshow;
//$product->load_currency_data();

//$smarty->assign('images',$images);
//$smarty->assign('num_images',count($images));

//$parts_info=$product->get_parts_info();

//print_r($parts_info);

//$smarty->assign('parts',$parts_info);

//$smarty->assign('num_parts',count($parts_info));
$units_tipo=array(
		  'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
		  'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
		  'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
		  'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
		  'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
);
//$units_tipo[$product->data['Product Unit Type']]['selected']=true;


//$smarty->assign('units_tipo',$units_tipo);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		// $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		  'text_editor.css',
		 'common.css',
		 'button.css',
		 'table.css',
		 'css/edit.css',
		  'css/dropdown.css',
		 );

include_once('Theme.php');


$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',	
		$yui_path.'datatable/datatable.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'editor/editor-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/search.js',
		 'js/dropdown.js',
		'js/table_common.js',
		'js/upload_image.js',
		'js/edit_common.js',
		'edit_part.js.php?sku='.$part->sku
		);


$smarty->assign('parent','warehouses');
$smarty->assign('title',$part->get('Product SKU'));





$smarty->assign('date',date('Y-m-d'));
$smarty->assign('time',date('H:i'));

if(isset($_REQUEST['edit'])  ){
  $_SESSION['state']['part']['edit']=$_REQUEST['edit'];

}
  $smarty->assign('edit',$_SESSION['state']['part']['edit']);

$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);

//$smarty->assign('currency',$product->data['Currency Symbol']);



/* $sql=sprintf("select `Category Position`,`Category Name`,CD.`Category Key`, if((select PCB.`Subject Key` from `Category Bridge` PCB where  `Category Key`=CD.`Category Key` and `Subject Key`=%d  and `Subject`='Product') is null,0,1)as selected from `Category Dimension` CD where `Category Subject`='Product'  and `Category Deep`=1 order by `Category Order`",$product->pid); */

/* $res=mysql_query($sql); */
/* $cats=array(); */

//print $sql;
/*

$nodes=new nodes('`Category Dimension`');
$nodes->sql_condition = "AND `Category Subject`='Product' " ;
$nodes->load_comb();
$comb=$nodes->comb;


$sql=sprintf("select PCB.`Category Key`,`Category Position` from `Category Bridge` PCB left join `Category Dimension` C on (C.`Category Key`=PCB.`Category Key`)   where  PCB.`Subject Key`=%d  and `Subject`='Product'    " ,$product->pid);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  $parents=preg_replace('/\d+>$/','',$row['Category Position']);
  $root=preg_replace('/>.*$/','',$row['Category Position']);
  // print "$root $parents ".$row['Category Key']."\n";
  $comb[$root]['teeth'][$parents]['elements'][$row['Category Key']]['selected']=true;
  
  

}
mysql_free_result($res);

$smarty->assign('categories',$comb);
$smarty->assign('number_categories',count($comb));
$_parts=array();
foreach($parts_info as $key=>$value){
$_parts[$key]=$key;
}
*/

//$js_files[]=sprintf('edit_product.js.php?symbol=%s&pid=%d&cats=%s&parts=%s',
 //   $product->data['Currency Symbol'],$product->pid,join(',',$nodes->root),join(',',$_parts));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Product Dimension","Product Unit Type" );
//print_r($units_types);
$unit_type_options=array();
foreach($units_types as $units_type ){
  $unit_type_options[$units_type]=$units_type;
}

$smarty->assign('unit_type_options',$unit_type_options
                                );
//$smarty->assign('unit_type',$product->data['Product Unit Type']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('title',_('Editing').' '.$part->formated_sku());


//while($row=mysql_fetch_array($res)){
  //$tree=preg_split('/>/',$row['Category Position']);
  //print $row['Category Key'];
  //print_r($nodes->fetch($row['Category Key']));
  //$cat_theme[$row['Category Key']]=array('name'=>$row['Category Name'],'selected'=>$row['selected']);
//}
//mysql_free_result($res);

//$smarty->assign('cat_use',$cat_use);
//$smarty->assign('cat_material',$cat_material);
//$smarty->assign('cat_theme',$cat_theme);


//show case 		
$custom_field=Array();
$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Table`='Part'");
$res = mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$custom_field[$row['Custom Field Key']]=$row['Custom Field Name'];
}


$show_case=Array();
$sql=sprintf("select * from `Part Custom Field Dimension` where `Part SKU`=%d", $part->id);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){

	foreach($custom_field as $key=>$value){
		$show_case[$value]=Array('value'=>$row[$key], 'lable'=>$key);
	}
}
//print_r($show_case);
$smarty->assign('show_case',$show_case);

$smarty->display('edit_part.tpl');




?>
