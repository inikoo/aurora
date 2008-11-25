<?

include_once('common.php');
include_once('classes/Product.php');
include_once('classes/Order.php');


// $sql=sprintf("select id,public_id from orden where pick_factor is null or weight is null order by public_id desc limit 10000");
//   $result =& $db->query($sql);
// print "<table border=1>";
// $pack=0;
// $pick=0;
// $i=0;
//   while($row=$result->fetchRow()){
// if(!$order=new Order('order_public_id',$row['public_id']))
//   print "Errror";
//  $_pick=$order->data['pick_factor'];
//  $_pack=$order->data['pack_factor'];
 
//  $pick+=$_pick;
//  $pack+=$_pack;
//  // printf("<tr><td>%s</td><td>%s</td><td>%.1f</td><td>%.1f</td><td>%.1f</td></tr>",$i++, $order->data['public_id'],$order->data['weight'],$_pick,$_pack   );
//  $sql=sprintf("update orden set  weight=%.3f ,weight_estimated=1  where id=%d and weight is null ",$order->data['weight'],$row['id']);

//  mysql_query($sql);
//  $sql=sprintf("update orden set pick_factor=%d ,pack_factor=%d  where id=%d  ",$_pick,$_pack,$row['id']);

//  mysql_query($sql);
//   }
//  printf("<tr><td></td><td></td><td></td><td>%.1f</td><td>%.1f</td></tr>",$pick,$pack   );
// print "</table>";
// exit;

//  $sql=sprintf("select id,order_id from todo_users where tipo='taken' ");
//  $result =& $db->query($sql);
//  while($row=$result->fetchRow()){
// //   $product=new Product($row['id']);
// //   $product->read('first_date');
// //   $product->read('sales_metadata');
// //   $product->read('stock_forecast');

//    $order_id=$row['order_id'];
//    //   $name=$row['name'];
//    //$tipo=$row['tipo'];
//    $id=$row['id'];
//    $sql="select id from orden where public_id='$order_id'";
//    $resultx =& $db->query($sql);
//    if($rowx=$resultx->fetchRow()){
//      $sql="update todo_users set order_id=".$rowx['id']." where id=$id";
//      mysql_query($sql);
//     }

//   }
$view_orders=$LU->checkRight(ORDER_VIEW);
$smarty->assign('view_orders',$view_orders);



$week=date("W");
$sql='select sum(total) as total,count(*) from orden where tipo=2 and week(date_index)='.$week;


$smarty->assign('box_layout','yui-t4');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'button.css',
		 'container.css',
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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/index.js.php'
		);




$smarty->assign('parent','index.php');
$smarty->assign('title', _('Home'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//set_stock_value_all();
//update_department_all();
//update_family_all();


//fix_todotransaction_all();

$smarty->assign('filter',$_SESSION['tables']['proinvoice_list'][5]);
$smarty->assign('filter_value',$_SESSION['tables']['proinvoice_list'][6]);

switch($_SESSION['tables']['proinvoice_list'][5]){
 case('max'):
   $filter_text=_('Maximun Day Interval');
   break;
 case('min'):
   $filter_text=_('Minimun Day Interval');
   break;
 case('public_id'):
   $filter_text=_('Order Number');
   break;
 case('customer_name'):
   $filter_text=_('Customer Name');
   break;
 default:
   $filter_text='?';
 }



$smarty->assign('filter_name',$filter_text);
$smarty->assign('f_date',_('Week').strftime(" %W %Y" ));

$smarty->assign('t_title0',_('Outstanding Orders'));

$smarty->display('index.tpl');





?>

