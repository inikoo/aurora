<?
include_once('common.php');
//include_once('stock_functions.php');
include_once('classes/Product.php');

$view_sales=$LU->checkRight(PROD_SALES_VIEW);
$view_stock=$LU->checkRight(PROD_STK_VIEW);
$view_orders=$LU->checkRight(ORDER_VIEW);

$create=$LU->checkRight(PROD_CREATE);
$modify=$LU->checkRight(PROD_MODIFY);
$modify_stock=$LU->checkRight(PROD_STK_MODIFY);
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$LU->checkRight(SUP_VIEW);
$view_cust=$LU->checkRight(CUST_VIEW);
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'connection/connection-min.js',
		$yui_path.'json/json-min.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		);





// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);

$smarty->assign('display',$_SESSION['state']['product']['display']);

// $smarty->assign('view_plot',$_SESSION['views']['product_plot']);

if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $product_id=1;
else
  $product_id=$_REQUEST['id'];
$_SESSION['state']['product']['id']=$product_id;


$product= new product($product_id);
$product->read(array(
		     'suppliers'
		     ,'product_tree'
		     ,'images'
		     ,'locations'
		     )
	       );

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


$locations=($product->get('locations'));

$smarty->assign('locations',$locations['data']);
$smarty->assign('suppliers',$product->get('number_of_suppliers'));
$smarty->assign('suppliers_name',$product->get('supplier_name'));
$smarty->assign('suppliers_code',$product->get('supplier_code'));
$smarty->assign('suppliers_price',$product->get('supplier_price'));



// $_SESSION['tables']['order_withprod'][4]=$product_id;
// $_SESSION['tables']['order_withcustprod'][4]=$product_id;
// $_SESSION['tables']['stock_history'][4]=$product_id;







$smarty->assign('parent','departments.php');
$smarty->assign('title',$product->get('group'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('department'));
$smarty->assign('department_id',$product->get('department_id'));
$smarty->assign('family',$product->get('group'));
$smarty->assign('family_id',$product->get('group_id'));
$smarty->assign('images',$product->get('images'));
$smarty->assign('image_dir',$myconf['images_dir']);

$smarty->assign('num_images',count($product->get('images')));
//print_r($product->get('images'));


$smarty->assign('product_id',$product_id);
$smarty->assign('code',$product->get('code'));
$smarty->assign('ncode',$product->get('ncode'));
$smarty->assign('id',$product->get('product_id'));
$smarty->assign('description',$product->get('description'));
$smarty->assign('units',number($product->get('units')));
$smarty->assign('unit_price',money($product->get('price')/$product->get('units')));
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
$weeks=$product->get('weeks_since');
$smarty->assign('weeks_since',number($weeks));

// assign plot tipo depending of the age of the product

$tipo_plot='sales';
if(preg_match('/outers/',$_SESSION['state']['product']['plot']))
  $tipo_plot='outers';


if($weeks>500){
  $time_plot='month';
 }elseif($weeks>52){
   $time_plot='month';
 }else{
   $time_plot='week';
 }

$plot_tipo='product_'.$time_plot.'_'.$tipo_plot;
$smarty->assign('plot_tipo',$plot_tipo);



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
$smarty->assign('table_title_orders',_('Orders'));
$smarty->assign('table_title_customers',_('Customers'));
$smarty->assign('table_title_stock',_('Stock History'));




//$smarty->assign('total_records',$product->get('numberof'));
//$smarty->assign('rpp',$_SESSION['tables']['product_list'][2]);

//$smarty->assign('records_perpage',$_SESSION['tables']['product_list'][2]);

$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);

$manage_stock_data=array();
$physical_locations=0;
foreach($locations['data'] as $location){
  $manage_stock_data['locations'][]=array('name'=>$location['name'],'id'=>$location['location_id'],'stock'=>$location['stock']);
  if($location['tipo']=='picking' or $location['tipo']=='storing')
    $physical_locations++;
}
$manage_stock_data['physical_locations']=$physical_locations;

$_SESSION['state']['product']['manage_stock_data']=json_encode($manage_stock_data);
$js_files[]= 'js/search_product.js';
$js_files[]='js/product.js.php?current_plot='.$plot_tipo;
$js_files[]='js/product_manage_stock.js.php';

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('product.tpl');
?>