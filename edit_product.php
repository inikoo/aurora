<?php
include_once('common.php');
include_once('class.Product.php');

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

if(!isset($_REQUEST['pid']) and is_numeric($_REQUEST['pid']))
  $product_id=1;
else
  $product_id=$_REQUEST['pid'];


$_SESSION['state']['product']['pid']=$product_id;

if(!$product= new product('pid',$product_id))
  exit('Error product not found');


$store=new Store($product->data['Product Store Key']);

$smarty->assign('product',$product);
$smarty->assign('store',$store);


$product->load('images_slideshow');
$product->load_currency_data();
$images=$product->images_slideshow;
$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));
$product->load('part_list');
$smarty->assign('parts',$product->parts);
$smarty->assign('num_parts',count($product->parts));
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


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		  $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 'text_editor.css',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css',
		 'css/edit.css'
		 );

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		  'text_editor.css',
		 'common.css',
		 'button.css',
		 'table.css',
		 'css/edit.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'calendar/calendar-min.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'json/json-min.js',

		'calendar_common.js.php',
		'js/php.default.min.js',
		'common.js.php',
		'table_common.js.php',
		'js/edit_common.js'
	

		);



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
		'common.js.php',
		'js/search.js',
		'table_common.js.php',
		);


$smarty->assign('parent','assets_tree.php');
$smarty->assign('title',$product->get('Product Code'));




$product_home="Products Home";




$smarty->assign('date',date('d-m-Y'));
$smarty->assign('time',date('H:i'));



$smarty->assign('edit',$_SESSION['state']['product']['edit']);

$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('thousands_sep',$_SESSION['locale_info']['thousands_sep']);
$smarty->assign('decimal_point',$_SESSION['locale_info']['decimal_point']);

$smarty->assign('currency',$product->data['Currency Symbol']);

$js_files[]=sprintf('edit_product.js.php?symbol=%s&product_id=%d',$product->data['Currency Symbol'],$product->pid);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$sql=sprintf("select `Category Type`,`Category Name`,CD.`Category Key`, if((select PCB.`Product Key` from `Product Category Bridge` PCB where  `Category Key`=CD.`Category Key` and `Product Key`=%d ) is null,0,1)as selected from `Category Dimension` CD order by `Category Name`",$product->id);

$res=mysql_query($sql);
$cat_use=array();
$cat_theme=array();
$cat_meterial=array();

while($row=mysql_fetch_array($res)){
  if($row['Category Type']=='Use')
    $cat_use[$row['Category Key']]=array('name'=>$row['Category Name'],'selected'=>$row['selected']);
  if($row['Category Type']=='Material')
    $cat_material[$row['Category Key']]=array('name'=>$row['Category Name'],'selected'=>$row['selected']);
  if($row['Category Type']=='Theme')
    $cat_theme[$row['Category Key']]=array('name'=>$row['Category Name'],'selected'=>$row['selected']);
}
mysql_free_result($res);
$smarty->assign('cat_use',$cat_use);
$smarty->assign('cat_material',$cat_material);
$smarty->assign('cat_theme',$cat_theme);
$smarty->display('edit_product.tpl');
?>