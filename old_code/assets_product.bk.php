<?
include_once('common.php');
include_once('stock_functions.php');
include_once('class.product.php');

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


if(isset($_REQUEST['vp']) and is_numeric($_REQUEST['vp']) and $_REQUEST['vp']>=0 and $_REQUEST['vp']<6)
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

$smarty->assign('view_plot',$_SESSION['views']['product_plot']);

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



$product= new product($product_id);
print $product->get('code');
exit;

//ok get net and prrvious product;







$sql=sprintf("select 


p.rrp,
p.units_carton,
d.name as department, 
department_id,
group_id as fam_id,
g.name as fam,
p.description as description,
p.id as product_id,
p.code as code,
p.ncode as ncode,
p.units as units,
p.units_tipo as units_tipo,
p.weight as w,
p.stock  as stock,
p.available,
p.price as price,
p.awtsq as awtsq,
p.awtsall as awtsall,
p.tsall as tsall,
p.tsw as tsw,
p.stock_value as stock_value,
p.outall,
p.outw,
p.tsm,
p.tsy,
p.tsq,
p.awtsq,
p.awoutq,

 (TO_DAYS(NOW())-TO_DAYS(first_date))/7 as weeks_since, 


UNIX_TIMESTAMP(first_date) as first_date,
p.description_med as description_med
from product as p  left join product_group as g on (g.id=group_id) left join product_department as d on (d.id=department_id)  
where p.id=%d ",$product_id);

$result=mysql_query($sql);
if(!$product=$result->fetchRow())
  exit;

//get previoues
$fam_order=$_SESSION['tables']['products_list'][0];

$sql=sprintf("select id,code from product where  %s<'%s' and  group_id=%d order by %s desc  ",$fam_order,$product[$fam_order],$product['fam_id'],$fam_order);
$result=mysql_query($sql);

if(!$prev=$result->fetchRow())
  $prev=array('id'=>0,'code'=>'');
$sql=sprintf("select id,code from product where  %s>'%s' and group_id=%d order by %s   ",$fam_order,$product[$fam_order],$product['fam_id'],$fam_order);
$result=mysql_query($sql);
if(!$next=$result->fetchRow())
  $next=array('id'=>0,'code'=>'');

$smarty->assign('prev',$prev);
$smarty->assign('next',$next);

$sql=sprintf("select filename,format,principal,caption,id from image where  product_id=%d ",$product_id);
$result=mysql_query($sql);
$image='';
$num_images=0;
$image='art/nopic.png';
$set_principal=false;
$other_images_src=array();
$other_images_id=array();

while($images=$result->fetchRow()){
  if($images['principal']==1 and !$set_principal){
   
    $image='images/med/'.$images['filename'].'_med.'.$images['format'];
    $set_principal=true;
    $smarty->assign('caption',$images['caption']);
    $smarty->assign('image_id',$images['id']);

  }
  else{
    $other_images_src[]='images/tb/'.$images['filename'].'_tb.'.$images['format'];
    $other_images_id[]=$images['id'];
  }
  $num_images++;
 }
   $smarty->assign('other_images_src',$other_images_src);
   $smarty->assign('other_images_id',$other_images_id);
   $smarty->assign('images',$num_images);

$sql=sprintf("select p2s.supplier_id, p2s.price,p2s.sup_code as code,s.name as name from product2supplier as p2s left join supplier as s on (p2s.supplier_id=s.id) where p2s.product_id=%d",$product_id);

$result=mysql_query($sql);
$supplier=array();
$supplier_name=array();
$supplier_price=array();
$supplier_code=array();
while($row=$result->fetchRow()){
  $supplier_name[$row['supplier_id']]=$row['name'];
  $supplier_price[$row['supplier_id']]=money($row['price']);
  $supplier_code[$row['supplier_id']]=$row['code'];
 }

$suppliers=count($supplier_name);
$smarty->assign('suppliers',$suppliers);
$smarty->assign('suppliers_name',$supplier_name);
$smarty->assign('suppliers_code',$supplier_code);
$smarty->assign('suppliers_price',$supplier_price);



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
		 'css/container.css',
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
		'js/calendar_js/common.js',
		'js/js/common.js',
		'js/js/table_common.js',
		'js/assets_product.js.php'
		);




$smarty->assign('parent','products');
$smarty->assign('title',$product['fam']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product['department']);
$smarty->assign('department_id',$product['department_id']);
$smarty->assign('family',$product['fam']);
$smarty->assign('family_id',$product['fam_id']);

$smarty->assign('code',$product['code']);
$smarty->assign('ncode',$product['ncode']);
$smarty->assign('id',$product['product_id']);
$smarty->assign('description',$product['description']);
$smarty->assign('units',number($product['units']));
$smarty->assign('unitstipo',$_units_tipo[$product['units_tipo']]);
$smarty->assign('stock',number($product['stock']));
$smarty->assign('available',number($product['available']));

$smarty->assign('n_price',$product['price']);
$smarty->assign('n_rrp',$product['rrp']);

$smarty->assign('price',money($product['price']));
$smarty->assign('rrp',money($product['rrp']));
$smarty->assign('units_carton',$product['units_carton']);

$smarty->assign('units_tipo',$product['units_tipo']);
$smarty->assign('aunits_tipo',$_units_tipo);
$smarty->assign('cur_symbol',$myconf['currency_symbol']);

$smarty->assign('first_date',strftime("%e %B %Y", strtotime('@'.$product['first_date'])));
$smarty->assign('weeks_since',number($product['weeks_since']));

$smarty->assign('awtsq',money($product['awtsq']));
$smarty->assign('awtsall',money($product['awtsall']));
$smarty->assign('tsall',money($product['tsall']));
$smarty->assign('tsw',money($product['tsw']));
$smarty->assign('awtsq',number($product['awtsq']));
$smarty->assign('awtsall',number($product['awtsall']));
$smarty->assign('outall',number($product['outall']));
$smarty->assign('outw',number($product['outw']));



$smarty->assign('w',$product['w']);

$smarty->assign('short_description',$product['description_med']);
$smarty->assign('image',$image);
$smarty->assign('num_images',$num_images);

$sql="select id,alias from staff where active=1 order by alias";
$result=mysql_query($sql);

$associates=array('0'=>_('Other'));
while($row=$result->fetchRow()){
  $associates[$row['id']]=$row['alias'];
  
 }

$smarty->assign('acheckedby',$associates);

$sql="select id,code from supplier  order by code";
$result=mysql_query($sql);

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



//$smarty->assign('total_records',$product['numberof']);
//$smarty->assign('rpp',$_SESSION['tables']['product_list'][2]);

//$smarty->assign('records_perpage',$_SESSION['tables']['product_list'][2]);



$smarty->display('assets_product.tpl');
?>