<?
include_once('common.php');
include_once('stock_functions.php');
include_once('classes/Product.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$view_orders=$LU->checkRight(ORDER_VIEW);

$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$modify_stock=$LU->checkRight(PROD_STK_MODIFY);
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$LU->checkRight(SUP_VIEW);
$smarty->assign('view_suppliers',$view_suppliers);


$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);

$view_cust=$LU->checkRight(CUST_VIEW);
$smarty->assign('view_cust',$view_cust);

if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $product_id=1;
else
  $product_id=$_REQUEST['id'];


$_SESSION['state']['product']['id']=$product_id;

$product= new product($product_id);
$product->read(array(
		     'product_info'
		     ,'categories'
		     ,'suppliers'
		     ,'product_tree'
		     ,'images'
		     )
	       );
//print_r( $product->get('images'));
//exit;

// $category_list=array();
// $sql="select name from cat where tipo=2 order by name";
// $result =& $db->query($sql);
// while($category_list=$result->fetchRow()){
//   $category_list[]=$row[]
// }

$fam_order=$_SESSION['state']['family']['table']['order'];
$sql=sprintf("select id,code from product where  %s<'%s' and  group_id=%d order by %s desc  ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
$result =& $db->query($sql);
if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,code from product where  %s>'%s' and group_id=%d order by %s   ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
$result =& $db->query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);


// $sql=sprintf("select filename,format,principal,caption,id from image where  product_id=%d ",$product_id);
// $result =& $db->query($sql);
// $image='';
// $num_images=0;
// $image='art/nopic.png';
// $set_principal=false;
// $other_images_src=array();
// $other_images_id=array();

// while($images=$result->fetchRow()){
//   if($images['principal']==1 and !$set_principal){
   
//     $image='images/med/'.$images['filename'].'_med.'.$images['format'];
//     $set_principal=true;
//     $smarty->assign('caption',$images['caption']);
//     $smarty->assign('image_id',$images['id']);

//   }
//   else{
//     $other_images_src[]='images/tb/'.$images['filename'].'_tb.'.$images['format'];
//     $other_images_id[]=$images['id'];
//   }
//   $num_images++;
//  }
//   $smarty->assign('other_images_src',$other_images_src);
//   $smarty->assign('other_images_id',$other_images_id);
//$smarty->assign('images',$product->get('images'));

// $sql=sprintf("select p2s.supplier_id, p2s.price,p2s.sup_code as code,s.name as name from product2supplier as p2s left join supplier as s on (p2s.supplier_id=s.id) where p2s.product_id=%d",$product_id);

// $result =& $db->query($sql);
// $supplier=array();
// $supplier_name=array();
// $supplier_price=array();
// $supplier_code=array();
// while($row=$result->fetchRow()){
//   $supplier_name[$row['supplier_id']]=$row['name'];
//   $supplier_price[$row['supplier_id']]=money($row['price']);
//   $supplier_code[$row['supplier_id']]=$row['code'];
//  }

// $suppliers=count($supplier_name);

list($cat_list,$deep)=get_cat_base(2,'sname');


$cat=$product->get('categories');
$cat_parents=array();
$v_cat='';
foreach($cat['list'] as $key => $value){
  $_cat_parents=split(',',$cat_list[$key]['parents']);
  $cat_parents=array_merge($cat_parents,$_cat_parents);
  $cat_parents[]=$key;
  $v_cat.=','.$key;
}
$v_cat=preg_replace('/^,/','',$v_cat);
$cat_parents=array_unique($cat_parents);
foreach($cat_parents as $cat_parent){
  $cat_list[$cat_parent]['show']=0;
}

$smarty->assign('cat_list',$cat_list);
$smarty->assign('v_cat',$v_cat);

$smarty->assign('num_cat_list',count($cat_list));

$smarty->assign('cat',$cat['list']);
$smarty->assign('num_cat',$cat['number']);

$smarty->assign('suppliers',$product->get('number_of_suppliers'));
$smarty->assign('suppliers_name',$product->get('supplier_name'));
$smarty->assign('suppliers_code',$product->get('supplier_code'));
$smarty->assign('suppliers_price',$product->get('supplier_price'));
$smarty->assign('suppliers_num_price',$product->get('supplier_num_price'));


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
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'calendar/calendar-min.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',

		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'editor/editor-beta-debug.js',
		$yui_path.'json/json-min.js',

		'js/calendar_common.js.php',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/edit_product.js.php',

		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title',$product->get('code'));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$product_home="Products Home";




$smarty->assign('date',date('d-m-Y'));
$smarty->assign('time',date('H:i'));



$smarty->assign('edit',$_SESSION['state']['product']['edit']);

$smarty->assign('shape_example',$_shape_example);
$smarty->assign('shapes',$_shape);
$_SESSION['state']['product']['shapes_example']=json_encode($_shape_example);
$_SESSION['state']['product']['shapes']=json_encode($_shape);



$smarty->assign('currency',$myconf['currency_symbol']);
$smarty->assign('data',$product->data);

$smarty->display('edit_product.tpl');
?>