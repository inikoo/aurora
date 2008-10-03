<?
include_once('common.php');
include_once('stock_functions.php');
include_once('classes/product.php');

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


if(isset($_REQUEST['vp']))
  $_SESSION['views']['product_plot']=$_REQUEST['vp'];

switch($_SESSION['views']['product_plot']){
 case(0):
   $smarty->assign('plot_title',_('Product sales value per week'));
   break;
 case(1):
   $smarty->assign('plot_title',_('Orders with this product per week'));
   break;
 case(2):
   $smarty->assign('plot_title',_('Sales value per order per week'));
   break;
case(3):
   $smarty->assign('plot_title',_('Product sales value per month'));
   break;
 case(4):
   $smarty->assign('plot_title',_('Orders with this product per month'));
   break;
 case(5):
   $smarty->assign('plot_title',_('Sales value per order per month'));
   break;


 }

$smarty->assign('plot_tipo',$_SESSION['views']['product_plot']);

$_SESSION['views']['product_blocks'][5]=0;
foreach($_SESSION['views']['product_blocks'] as $key=>$value){
  $hide[$key]=($value==1?0:1);
  
}
//print_r($hide);
$smarty->assign('hide',$hide);

$smarty->assign('view_plot',$_SESSION['views']['product_plot']);

if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $product_id=1;
else
  $product_id=$_REQUEST['id'];



$product= new product();
$product->read(array(
		     'product_info'=>$product_id
		     ,'suppliers'=>$product_id
		     ,'product_tree'=>$product_id
		     ,'images'=>$product_id
		     )
	       );
//print_r( $product->get('images'));
//exit;

//get previoues
$fam_order=$_SESSION['tables']['products_list'][0];

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
$smarty->assign('suppliers',$product->get('number_of_suppliers'));
$smarty->assign('suppliers_name',$product->get('supplier_name'));
$smarty->assign('suppliers_code',$product->get('supplier_code'));
$smarty->assign('suppliers_price',$product->get('supplier_price'));



$_SESSION['tables']['order_withprod'][4]=$product_id;
$_SESSION['tables']['order_withcustprod'][4]=$product_id;
$_SESSION['tables']['stock_history'][4]=$product_id;



$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		  $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		 
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
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'charts/charts-experimental-min.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'editor/editor-beta-min.js',

		$yui_path.'json/json-min.js',
		'js/calendar_common.js.php',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/assets_product.js.php'
		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title',$product->get('group'));

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('department'));
$smarty->assign('department_id',$product->get('department_id'));
$smarty->assign('family',$product->get('group'));
$smarty->assign('family_id',$product->get('group_id'));
$smarty->assign('images',$product->get('images'));
$smarty->assign('num_images',count($product->get('images')));
//print_r($product->get('images'));


$smarty->assign('product_id',$product_id);
$smarty->assign('code',$product->get('code'));
$smarty->assign('ncode',$product->get('ncode'));
$smarty->assign('id',$product->get('product_id'));
$smarty->assign('description',$product->get('description'));
$smarty->assign('units',number($product->get('units')));

$smarty->assign('units_tipo',$_units_tipo[$product->get('units_tipo')]);
$smarty->assign('stock',number($product->get('stock')));
$smarty->assign('available',number($product->get('available')));

$smarty->assign('n_price',$product->get('price'));
$smarty->assign('n_rrp',$product->get('rrp'));

$smarty->assign('price',money($product->get('price')));
$smarty->assign('rrp',money($product->get('rrp')));
$smarty->assign('units_carton',$product->get('units_carton'));


$smarty->assign('aunits_tipo',$_units_tipo);
$smarty->assign('ashape',$_shape);
$smarty->assign('ashape_example',$_shape_example);

$smarty->assign('cur_symbol',$myconf['currency_symbol']);

$smarty->assign('first_date',$product->get('first_date'));
$smarty->assign('weeks_since',number($product->get('weeks_since')));


$smarty->assign('outall',number($product->get('outall')));
$smarty->assign('awoutall',number($product->get('awoutall')));
$smarty->assign('awoutq',number($product->get('awoutq')));




$smarty->assign('w',$product->get('w'));

$smarty->assign('short_description',$product->get('description_med'));


$sql="select id,alias from staff where active=1 order by alias";
$result =& $db->query($sql);

$associates=array('0'=>_('Other'));
while($row=$result->fetchRow()){
  $associates[$row['id']]=$row['alias'];
  
 }

$smarty->assign('acheckedby',$associates);

$sql="select id,code from supplier  order by code";
$result =& $db->query($sql);

$asuppliers=array('0'=>_('Choose a supplier'));
while($row=$result->fetchRow()){
  $asuppliers[$row['id']]=$row['code'];
  
 }

$smarty->assign('asuppliers',$asuppliers);
$smarty->assign('date',date('d-m-Y'));
$smarty->assign('time',date('H:i'));

$smarty->assign('stock_table_options',array(_('Inv'),_('Pur'),_('Adj'),_('Sal'),_('P Sal')) );
$smarty->assign('stock_table_options_tipo', $_SESSION['views']['stockh_table_options'] );
$smarty->assign('t_title0',_('Orders'));
$smarty->assign('t_title1',_('Customers'));
$smarty->assign('t_title2',_('Stock History'));




//$smarty->assign('total_records',$product->get('numberof'));
//$smarty->assign('rpp',$_SESSION['tables']['product_list'][2]);

//$smarty->assign('records_perpage',$_SESSION['tables']['product_list'][2]);

$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);




$smarty->display('assets_product.tpl');
?>