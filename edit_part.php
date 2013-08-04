<?php
include_once 'common.php';
include_once 'class.Part.php';
include_once 'class.Warehouse.php';

$view_parts=$user->can_view('parts');

if (!$view_parts) {
	header('Location: index.php');
	exit();
}

$create=$user->can_create('parts');
$modify=$user->can_edit('parts');

$modify_stock=$user->can_edit('product stock');

$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$smarty->assign('view_suppliers',$view_suppliers);


$smarty->assign('create',$create);
$smarty->assign('modify',$modify);



if (isset($_REQUEST['sku']) and is_numeric($_REQUEST['sku'])) {
	$part= new part('sku',$_REQUEST['sku']);
	$part_id=$part->id;
	$_SESSION['state']['part']['id']=$part_id;
	$_SESSION['state']['part']['sku']=$part->data['Part SKU'];
} else {
	header('Location: warehouse.php?msg=part_not_given');
	exit;
}

$part= new Part($part_id);

if (!$part->id) {
	header('Location: warehouse.php?msg=part_not_found');
	exit;
}


$smarty->assign('part',$part);

$general_options_list=array();



$warehouse_keys=$part->get_warehouse_keys();
foreach ($warehouse_keys as $warehouse_key) {
	if (in_array($warehouse_key,$user->warehouses)) {
		$warehouse=new Warehouse($warehouse_key);
		break;
	}
	header('Location: index.php?forbidden');
	exit;
}

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');


$general_options_list[]=array('tipo'=>'url','url'=>'part.php?sku='.$part->sku,'label'=>_('Exit Edit'));
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
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

	$yui_path.'editor/assets/skins/sam/editor.css',
	'css/container.css',
	'text_editor.css',
	'common.css',
	'button.css',
	'table.css',
	'css/edit.css',

);

$css_files[]='theme.css.php';


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
	$yui_path.'uploader/uploader-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/editor_image_uploader.js',
	'js/table_common.js',
	'js/upload_image.js',
	
	'js/edit_common.js',
	'edit_part.js.php?sku='.$part->sku
);

$smarty->assign('parent','parts');





$smarty->assign('date',date('Y-m-d'));
$smarty->assign('time',date('H:i'));

if (isset($_REQUEST['edit'])  and in_array($_REQUEST['edit'],array('description', 'products', 'suppliers','transactions'))) {
	$_SESSION['state']['part']['edit']=$_REQUEST['edit'];
}



if (isset($_REQUEST['edit_description_block'])  and in_array($_REQUEST['edit_description_block'],array('status','description','properties','pictures','info','health_and_safety'))) {
	$_SESSION['state']['part']['edit_description_block']=$_REQUEST['edit_description_block'];
}

$smarty->assign('edit',$_SESSION['state']['part']['edit']);
$smarty->assign('description_block',$_SESSION['state']['part']['edit_description_block']);





$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);

//$smarty->assign('currency',$product->data['Currency Symbol']);



/* $sql=sprintf("select `Category Position`,`Category Code`,CD.`Category Key`, if((select PCB.`Subject Key` from `Category Bridge` PCB where  `Category Key`=CD.`Category Key` and `Subject Key`=%d  and `Subject`='Product') is null,0,1)as selected from `Category Dimension` CD where `Category Subject`='Product'  and `Category Deep`=1 order by `Category Order`",$product->pid); */

/* $res=mysql_query($sql); */
/* $cats=array(); */

//print $sql;


//$js_files[]=sprintf('edit_product.js.php?symbol=%s&pid=%d&cats=%s&parts=%s',
//   $product->data['Currency Symbol'],$product->pid,join(',',$nodes->root),join(',',$_parts));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Part Dimension","Part Unit" );
//print_r($units_types);
$unit_type_options=array();
foreach ($units_types as $units_type ) {
	$unit_type_options[$units_type]=$units_type;
}

$smarty->assign('unit_type_options',$unit_type_options

);
$smarty->assign('unit_type',$part->data['Part Unit']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('title',_('Editing').' '.$part->formated_sku());



/*
 Do this inside class.Part.php
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

$smarty->assign('show_case',$show_case);
*/

$tipo_filter=$_SESSION['state']['part']['history']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['part']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with notes *<i>x</i>*'),'label'=>_('Notes')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
	'abstract'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract'),'label'=>_('Abstract'))
);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu0',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['part']['products']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part']['products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Products with code *<i>x</i>*'),'label'=>_('Code')),
);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu1',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$tipo_filter=$_SESSION['state']['part']['supplier_products']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['part']['supplier_products']['f_value']);
$filter_menu=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Products with code *<i>x</i>*'),'label'=>_('Code'))
);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu2',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$tipo_filter=$_SESSION['state']['part']['transactions']['f_field'];
$smarty->assign('filter_show3',$_SESSION['state']['part']['transactions']['f_show']);
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['part']['transactions']['f_value']);
$filter_menu=array(
	'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
	'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);

$transactions=array(
	'all_transactions'=>$part->data['Part Transactions'],
	'in_transactions'=>$part->data['Part Transactions In'],
	'out_transactions'=>$part->data['Part Transactions Out'],
	'audit_transactions'=>$part->data['Part Transactions Audit'],
	'oip_transactions'=>$part->data['Part Transactions OIP'],
	'move_transactions'=>$part->data['Part Transactions Move'],
);


$smarty->assign('transactions',$transactions);
$smarty->assign('transaction_type',$_SESSION['state']['part']['transactions']['view']);


$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);

$order=$_SESSION['state']['warehouse']['parts']['order'];
if ($order=='sku') {
	$_order='Part SKU';
	$order='P.`Part SKU`';
	$order_label=_('SKU');;

} else {
	$_order='Part SKU';
	$order='P.`Part SKU`';
	$order_label=_('SKU');
}
//$_order=preg_replace('/`/','',$order);
$sql=sprintf("select  P.`Part SKU` as id , `Part Unit Description` as name from `Part Dimension` P left join  `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`)  where  `Warehouse Key`=%d  and %s < %s  order by %s desc  limit 1",
	$warehouse->id,
	$order,
	prepare_mysql($part->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$prev['link']='edit_part.php?sku='.$row['id'];
	$prev['title']=$row['name'];
	$smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select P.`Part SKU` as id , `Part Unit Description` as name from `Part Dimension` P  left join  `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`) where  `Warehouse Key`=%d    and  %s>%s  order by %s   ",
	$warehouse->id,
	$order,
	prepare_mysql($part->get($_order)),
	$order
);

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	$next['link']='edit_part.php?sku='.$row['id'];
	$next['title']=$row['name'];
	$smarty->assign('next',$next);
}
mysql_free_result($result);

$smarty->assign('show_history',$_SESSION['state']['part']['show_history']);
$smarty->assign('products_view',$_SESSION['state']['part']['products']['view']);



$smarty->display('edit_part.tpl');




?>
