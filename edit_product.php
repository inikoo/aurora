<?php
include_once 'common.php';
include_once 'class.Product.php';
include_once 'class.Node.php';

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');

$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');

$smarty->assign('modify_stock',$modify_stock);

$view_suppliers=$user->can_view('suppliers');
$smarty->assign('view_suppliers',$view_suppliers);


$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);

$view_cust=$user->can_view('customers');
$smarty->assign('view_cust',$view_cust);

if (isset($_REQUEST['pid']) and is_numeric($_REQUEST['pid'])) {
	$product_id=$_REQUEST['pid'];
	$_SESSION['state']['product']['mode']='pid';
	$_SESSION['state']['product']['tag']=$product_id;
}else {
	header('Location: index.php?no_product_id');
	exit;
}

$product= new product('pid',$product_id);
if (!$product->id) {
	header('Location: index.php?product_not_found');
	exit;


}

$store=new Store($product->data['Product Store Key']);

//print_r($store);
$smarty->assign('product',$product);
$smarty->assign('store',$store);



//$product->load_images_slidesshow();
//$images=$product->images_slideshow;
$product->load_currency_data();

//$smarty->assign('images',$images);
//$smarty->assign('num_images',count($images));

$parts_info=$product->get_parts_info();

//print_r($parts_info);

$smarty->assign('parts',$parts_info);

$smarty->assign('num_parts',count($parts_info));
$units_tipo=array(
	'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
	'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
	'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
	'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
	'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
);
$units_tipo[$product->data['Product Unit Type']]['selected']=true;


$smarty->assign('units_tipo',$units_tipo);



$smarty->assign('box_layout','yui-t0');



$smarty->assign('sales_type',$product->data['Product Sales Type']);


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	$yui_path.'assets/skins/sam/editor.css',
	'css/container.css',
	'css/text_editor.css',

	'css/common.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'css/images.css',
	'css/edit_page.css',

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

	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/editor_image_uploader.js',
	'js/table_common.js',
	'js/upload_image.js',
	'js/edit_common.js'
);


$smarty->assign('parent','products');
$smarty->assign('title',$product->get('Product Code'));


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$product_home="Products Home";


$smarty->assign('store_key',$store->id);


$smarty->assign('date',date('Y-m-d'));
$smarty->assign('time',date('H:i'));

if (isset($_REQUEST['edit'])  ) {
	$_SESSION['state']['product']['edit']=$_REQUEST['edit'];

}
$smarty->assign('edit',$_SESSION['state']['product']['edit']);


$smarty->assign('edit_description_block',$_SESSION['state']['product']['edit_description_block']);



$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);

$smarty->assign('currency',$store->data['Store Currency Code']);



/* $sql=sprintf("select `Category Position`,`Category Code`,CD.`Category Key`, if((select PCB.`Subject Key` from `Category Bridge` PCB where  `Category Key`=CD.`Category Key` and `Subject Key`=%d  and `Subject`='Product') is null,0,1)as selected from `Category Dimension` CD where `Category Subject`='Product'  and `Category Deep`=1 order by `Category Order`",$product->pid); */

/* $res=mysql_query($sql); */
/* $cats=array(); */

//print $sql;
$nodes=new nodes('`Category Dimension`');
$nodes->sql_condition = "AND `Category Subject`='Product' " ;
$nodes->load_comb();
$comb=$nodes->comb;


$sql=sprintf("select PCB.`Category Key`,`Category Position` from `Category Bridge` PCB left join `Category Dimension` C on (C.`Category Key`=PCB.`Category Key`)   where  PCB.`Subject Key`=%d  and `Subject`='Product'    " ,$product->pid);
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$parents=preg_replace('/\d+>$/','',$row['Category Position']);
	$root=preg_replace('/>.*$/','',$row['Category Position']);
	// print "$root $parents ".$row['Category Key']."\n";
	$comb[$root]['teeth'][$parents]['elements'][$row['Category Key']]['selected']=true;



}
mysql_free_result($res);

$smarty->assign('categories',$comb);
$smarty->assign('number_categories',count($comb));
$_parts=array();
foreach ($parts_info as $key=>$value) {
	$_parts[$key]=$key;
}


$js_files[]=sprintf('edit_product.js.php?symbol=%s&pid=%d&cats=%s&parts=%s&store_key=%d',
	$store->data['Store Currency Code'],$product->pid,join(',',$nodes->root),join(',',$_parts), $store->id);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units_types=getEnumValues("Product Dimension","Product Unit Type" );
//print_r($units_types);
$unit_type_options=array();
foreach ($units_types as $units_type ) {
	$unit_type_options[$units_type]=$units_type;
}

$smarty->assign('unit_type_options',$unit_type_options);
$smarty->assign('unit_type',$product->data['Product Unit Type']);


$tipo_filter=$_SESSION['state']['product']['history']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['product']['history']['f_value']);
$filter_menu=array(
	'notes'=>array('db_key'=>'abstract','menu_label'=>_('Records with abstract *<i>x</i>*'),'label'=>_('Abstract')),
	'author'=>array('db_key'=>'author','menu_label'=>_('Done by <i>x</i>*'),'label'=>_('Notes')),
//	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
//	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)')),
);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$smarty->assign('filter_menu0',$filter_menu);



$tipo_filter2='code';
$filter_menu2=array(
	'code'=>array('db_key'=>'code','menu_label'=>_('Code'),'label'=>_('Code')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->assign('filter1','reference');
$smarty->assign('filter_value1','');
$filter_menu=array(
	'reference'=>array('db_key'=>'reference','menu_label'=>_('Part Reference'),'label'=>_('Reference')),

	'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU'),'label'=>_('SKU')),

	'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in'),'label'=>_('Used in')),

);
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu['used_in']['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$can_edit_code=true;
if ($product->data['Product Total Acc Quantity Ordered']>0) {
	$can_edit_code=false;
}

$smarty->assign('can_edit_code',$can_edit_code);

$link='edit_product.php';
include_once 'product_navigation_common.php';

$smarty->assign('store_currency_symbol',currency_symbol($store->data['Store Currency Code']));
$smarty->assign('show_history',$_SESSION['state']['product']['show_history']);

$lenght_units=array('cm'=>'cm','mm'=>'mm','m'=>'m','in'=>'in','yd'=>'yd','ft'=>'ft');
$weight_units=array('kg'=>'Kg','g'=>'g','oz'=>'oz','lb'=>'lb');

$smarty->assign('lenght_units',$lenght_units);
$smarty->assign('weight_units',$weight_units);

$smarty->display('edit_product.tpl');




?>
