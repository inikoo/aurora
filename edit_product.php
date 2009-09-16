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

if(!isset($_REQUEST['key']) and is_numeric($_REQUEST['key']))
  $product_id=1;
else
  $product_id=$_REQUEST['key'];


$_SESSION['state']['product']['id']=$product_id;

if(!$product= new product($product_id))
  exit('Error product not found');

$smarty->assign('product',$product);


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



// $cat_parents=array();
// $v_cat='';
// foreach($product->categories['list'] as $key => $value){
//   $_cat_parents=split(',',$cat_list[$key]['parents']);
//   $cat_parents=array_merge($cat_parents,$_cat_parents);
//   $cat_parents[]=$key;
//   $v_cat.=','.$key;
// }
// $v_cat=preg_replace('/^,/','',$v_cat);
// $cat_parents=array_unique($cat_parents);
// foreach($cat_parents as $cat_parent){
//   $cat_list[$cat_parent]['show']=0;
// }

// $smarty->assign('cat_list',$cat_list);
// $smarty->assign('v_cat',$v_cat);

// $smarty->assign('num_cat_list',count($cat_list));

// $smarty->assign('cat',$product->categories['list']);
// $smarty->assign('num_cat',$product->categories['number']);

// $smarty->assign('suppliers',$product->get('number_of_suppliers'));
// $smarty->assign('supplier',$product->supplier);


//print_r($product->images);
//$smarty->assign('images',$product->images);


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
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'calendar/calendar-min.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'editor/editor-min.js',
		$yui_path.'json/json-min.js',

		'calendar_common.js.php',
		'common.js.php',
		'table_common.js.php',
		'js/md5.js',

	
	

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

$js_files[]=sprintf('edit_product.js.php?symbol=%s&product_id=%d',$product->data['Currency Symbol'],$product->id);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

// $sql=sprintf("select id,sname,tipo,name,description from cat where tipo=1 order by sname ");
// $res = mysql_query($sql);
// $num_cols=6;
// $cat=array();
// $i=1;
// while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
//   $cat[]=array('sname'=>$row['sname'],'name'=>$row['name'].": ".$row['description'],'id'=>$row['id'],'tipo'=>$row['tipo']);
//   $i++;
//  }
// list($cat,$num_cols)= array_transverse($cat,$num_cols);
// foreach($cat as $key=>$_cat){
//   $cat[$key]['mod']=fmod($key,$num_cols);
// }
// $smarty->assign('material_cat',$cat);
// $smarty->assign('cat_cols',$num_cols-1);

// $sql=sprintf("select id,sname,tipo,name,description from cat where tipo=2 order by sname ");
// $res = mysql_query($sql);
// $num_cols=6;
// $cat=array();
// $i=1;
// while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
//   $cat[]=array('sname'=>$row['sname'],'name'=>$row['name'].": ".$row['description'],'id'=>$row['id'],'tipo'=>$row['tipo']);
//   $i++;
//  }
// list($cat,$num_cols)= array_transverse($cat,$num_cols);
// foreach($cat as $key=>$_cat){
//   $cat[$key]['mod']=fmod($key,$num_cols);
// }
// $smarty->assign('use_cat',$cat);
// $smarty->assign('cat_cols',$num_cols-1);



// $sql=sprintf("select id,sname,tipo,name,description from cat where tipo=3 order by sname ");
// $res = mysql_query($sql);
// $num_cols=6;
// $cat=array();
// $i=1;
// while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
//   $cat[]=array('sname'=>$row['sname'],'name'=>$row['name'].": ".$row['description'],'id'=>$row['id'],'tipo'=>$row['tipo']);
//   $i++;
//  }
// list($cat,$num_cols)= array_transverse($cat,$num_cols);
// foreach($cat as $key=>$_cat){
//   $cat[$key]['mod']=fmod($key,$num_cols);
// }
// $smarty->assign('mods_cat',$cat);
// $smarty->assign('cat_cols',$num_cols-1);

// $sql=sprintf("select id,sname,tipo,name,description from cat where tipo=4 order by sname ");
// $res = mysql_query($sql);
// $num_cols=6;
// $cat=array();
// $i=1;
// while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
//   $cat[]=array('sname'=>$row['sname'],'name'=>$row['name'].": ".$row['description'],'id'=>$row['id'],'tipo'=>$row['tipo']);
//   $i++;
//  }
// list($cat,$num_cols)= array_transverse($cat,$num_cols);
// foreach($cat as $key=>$_cat){
//   $cat[$key]['mod']=fmod($key,$num_cols);
// }
// $smarty->assign('state_cat',$cat);
// $smarty->assign('cat_cols',$num_cols-1);
$units=$product->get('Product Units Per Case');
$smarty->assign('units',number($units));
$smarty->assign('factor_units',number_format($units,6));
$smarty->assign('factor_inv_units',number_format(1/$units,6)  );

// $smarty->assign('price_perunit',money($product->get('price')/$product->get('units')));
// if($product->data['rrp']=='')
//   $smarty->assign('rrp_perouter','');
// else
//   $smarty->assign('rrp_perouter',money($product->get('rrp')*$product->get('units')));
// $smarty->assign('decimal_point',$myconf['decimal_point']);
// $smarty->assign('thousand_sep',$myconf['thousand_sep']);

// foreach($_units_tipo as $key=>$value){
//   $units_tipo[$key]=array('id'=>$key,'name'=>$value,'sname'=>$_units_tipo_plural[$key]);
// }

//$smarty->assign('units_tipo_list',$units_tipo);
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