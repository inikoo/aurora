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
$product->group_by('code');
$product->load('part_location_list');


$smarty->assign('product',$product);
$num_links=$product->get('num_links');
$smarty->assign('num_links',$num_links);
$smarty->assign('fnum_links',number($num_links).' '.ngettext($num_links,'link','links'));
//print_r($product->data);
$smarty->assign('data',$product->data);

//$smarty->assign('web_status',$_web_status[$product->get('web_status')]);

$web_status_error=false;
$web_status_error_title='';
 if($product->get('Product Web State')=='Online For Sale'){
   if(!($product->get('Product Availability')>0)){
     $web_status_error=true;
     $web_status_error_title=_('This product is out of stock');
   }
  }else{
   if($product->get('Product Availability')>0){
       $web_status_error=true;
       $web_status_error_title=_('This product is not for sale on the webpage');
   }
 }

$smarty->assign('web_status_error',$web_status_error);
$smarty->assign('web_status_error_title',$web_status_error_title);


// $fam_order=$_SESSION['state']['family']['table']['order'];
// $sql=sprintf("select id,code from product where  %s<'%s' and  group_id=%d order by %s desc  ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
// $result =& $db->query($sql);
// if(!$prev=$result->fetchRow())
//   $prev=array('id'=>0,'code'=>'');
// $sql=sprintf("select id,code from product where  %s>'%s' and group_id=%d order by %s   ",$fam_order,$product->get($fam_order),$product->get('group_id'),$fam_order);
// $result =& $db->query($sql);
// if(!$next=$result->fetchRow())
//   $next=array('id'=>0,'code'=>'');

// $smarty->assign('prev',$prev);
// $smarty->assign('next',$next);


//$locations=($product->get('locations'));

//$smarty->assign('locations',$locations['data']);
//$smarty->assign('num_suppliers',$product->get('number_of_suppliers'));
//$smarty->assign('suppliers',$product->supplier);



$smarty->assign('parent','departments.php');
$smarty->assign('title',$product->get('Product Code'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('Product Main Department Name'));
$smarty->assign('department_id',$product->get('Product Main Department Key'));
$smarty->assign('family',$product->get('Product Family Code'));
$smarty->assign('family_id',$product->get('Product Family Key'));
//$smarty->assign('images',$product->get('images'));
//$smarty->assign('image_dir',$myconf['images_dir']);
//$smarty->assign('num_images',$product->get('num_images'));



//$weeks=$product->get('weeks_since');


// assign plot tipo depending of the age of the product

// $tipo_plot='sales';
// if(preg_match('/outers/',$_SESSION['state']['product']['plot']))
//   $tipo_plot='outers';


// if($weeks>500){
//   $time_plot='month';
//  }elseif($weeks>52){
//    $time_plot='month';
//  }else{
//    $time_plot='week';
//  }

//$plot_tipo='product_'.$time_plot.'_'.$tipo_plot;
$plot_tipo=$_SESSION['state']['product']['plot'];
$plot_data=$_SESSION['state']['product']['plot_data'];

//print print_r($_SESSION['state']['product']);
$smarty->assign('plot_tipo',$plot_tipo);
$smarty->assign('plot_data',$plot_data);




$smarty->assign('stock_table_options',array(_('Inv'),_('Pur'),_('Adj'),_('Sal'),_('P Sal')) );
$smarty->assign('stock_table_options_tipo', $_SESSION['views']['stockh_table_options'] );
$smarty->assign('table_title_orders',_('Orders'));
$smarty->assign('table_title_customers',_('Customers'));
$smarty->assign('table_title_stock',_('Stock History'));



$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);


$js_files[]= 'js/search.js';
$js_files[]='js/product.js.php';

// $smarty->assign('tsoall',number($product->get('tsoall')));
// $smarty->assign('awtsoall',number($product->get('awtsoall')));
// $smarty->assign('awtsoq',number($product->get('awtsoq')));
// $smarty->assign('units',number($product->gr['units']));
// $smarty->assign('price',money($product->data['price']));
// $smarty->assign('rrp',money($product->data['rrp']));
// $smarty->assign('weeks_since',number($product->get('weeks')));

// $smarty->assign('unit_price',money($product->data['price']/$product->data['units']));


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('web_status_menu',$_web_status);


$smarty->display('product.tpl');
?>