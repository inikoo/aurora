<?
include_once('common.php');
include_once('_supplier.php');
include_once('staff.php');

include_once('classes/Order.php');

$_SESSION['views']['assets']='index';


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  exit(_('Error'));
$order_id=$_REQUEST['id'];

if(!$order=new Order('order',$order_id))
  exit(_('Error, order not found'));


$_SESSION['state']['order']['id']=$order_id;
switch($order->data['tipo']){
 case(2):
   
   break;
 default:
   $js_file='invp.js.php';
   $template='order.tpl';
 }

$js_file='invoice.js.php';
$template='invoice.tpl';


$smarty->assign('date_invoiced',strftime("%d %b %Y", strtotime('@'.$order->data['date_invoiced'])));
if($order->data['payment_method']=='')
  $payment_method=_('Unknown');
 else{
   if($order->data['payment_method']==2)
     $payment_method='<img title="'.$_pm_tipo[$order->data['payment_method']].'"  src="art/icons/creditcards.png" />';
   elseif($order->data['payment_method']==1)
     $payment_method='<img title="'.$_pm_tipo[$order->data['payment_method']].'"  src="art/icons/money.png" />';
 elseif($order->data['payment_method']==4)
     $payment_method='<img title="'.$_pm_tipo[$order->data['payment_method']].'"  src="art/icons/cheque.png" />';
elseif($order->data['payment_method']==5)
     $payment_method='<img title="'.$_pm_tipo[$order->data['payment_method']].'"  src="art/icons/database_table.png" />';
elseif($order->data['payment_method']==6)
     $payment_method='<img title="'.$_pm_tipo[$order->data['payment_method']].'"  src="art/icons/paypal.png" />';
   else
     $payment_method=$_pm_tipo[$order->data['payment_method']];
 }

$smarty->assign('payment_method',$payment_method);
$smarty->assign('deliver_by',$order->data['deliver_by']);
$smarty->assign('taken_by',mb_ucwords($order->data['taken_by']));
$smarty->assign('picked_by',mb_ucwords($order->data['picked_by']));
$smarty->assign('packed_by',mb_ucwords($order->data['packed_by']));

$d_time=round(get_time_interval($order->data['date_creation'],$order->data['date_invoiced']));
if($d_time<1){
  $d_time=_('the same day');
 }else{
  $d_time=_('in').' '.$d_time.' '.ngettext('day','days',$d_time);
 }
$smarty->assign('dispatch_time',$d_time);

$_weight=$order->data['weight'];
if($_weight>25)
  $weight=number($_weight,0)._('Kg');
elseif($_weight<1)
  $weight=number($_weight,3)._('Kg');
else
  $weight=number($_weight,1)._('Kg');

$smarty->assign('weight',$weight);

$smarty->assign('data',$order->data);

$smarty->assign('parcels',$order->data['parcels']);
$smarty->assign('w',$order->data['weight']);
$smarty->assign('order_hist',$order->data['order_hist']);

$smarty->assign('items_out_of_stock',$order->data['items_out_of_stock']);

$smarty->assign('customer_name',$order->data['customer_name']);
$smarty->assign('contact',$order->data['contact_name']);
$smarty->assign('shipping_vateable',money($order->data['shipping_vateable']));
$smarty->assign('shipping_no_vateable',money($order->data['shipping_no_vateable']));
if($order->data['charges_vateable']!=0)
  $smarty->assign('charges_vateable',money($order->data['charges_vateable']));
if($order->data['credits_vateable']!=0)
  $smarty->assign('credits_vateable',money($order->data['credits_vateable']));


if($order->data['charges_no_vateable']!=0)
  $smarty->assign('charges_no_vateable',money($order->data['charges_no_vateable']));
if($order->data['credits_no_vateable']!=0)
  $smarty->assign('credits_no_vateable',money($order->data['credits_no_vateable']));


//print_r($order);

$smarty->assign('items_vateable',money($order->data['items_vateable']));
$smarty->assign('items_no_vateable',money($order->data['items_no_vateable']));
$smarty->assign('net',money($order->data['net']));






//$smarty->assign('other_charges',money($order->data['charges']));
//$smarty->assign('fcredit',money(-$order->data['credits']));
//$smarty->assign('credit',$order->data['credits']);

//$smarty->assign('items_charge',money($order->data['items_charge']));

if($order->data['address_bill']==$order->data['address_del'])
  $smarty->assign('address_delbill',$order->data['address_bill']);
 else{
   $smarty->assign('address_bill',$order->data['address_bill']);
   $smarty->assign('address_del',$order->data['address_del']);
 }

$smarty->assign('tel',$order->data['tel']);


$smarty->assign('tax',money($order->data['tax']));
//$smarty->assign('vat2',money($order->data['vat2']));
$smarty->assign('total',money($order->data['total']));

$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
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
		'js/'.$js_file
		);




$smarty->assign('parent','order.php');
$smarty->assign('title',_('Order').' '.$order->data['public_id'] );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('tipo_f',$_order_tipo[$order->data['tipo']]);
$smarty->assign('public_id',$order->data['public_id']);



$smarty->display($template);
?>