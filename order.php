<?
include_once('common.php');
include_once('_order.php');
include_once('_supplier.php');
include_once('staff.php');
include_once('string.php');

$_SESSION['views']['assets']='index';


if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  exit(_('Error'));
$order_id=$_REQUEST['id'];

if(!$order=get_order_data($order_id))
  exit(_('Error, order not found'));


$_SESSION['order_id']=$order_id;
switch($order['tipo']){
 case(2):
   
   break;
 default:
   $js_file='invp.js.php';
   $template='order.tpl';
 }

   $js_file='invoice.js.php';
   $template='invoice.tpl';

if(isset($_REQUEST['from']) or isset($_REQUEST['to'])){
  // ok limitite time period
  
  $from='';
  if($_REQUEST['from']!=''){
    $form=$_REQUEST['from'];
    $from=split('-',$_REQUEST['from']);
    if(count($from==3) and is_numeric($from[0]) and is_numeric($from[0]) and is_numeric($from[0]) ){
      $from=join ('-',array_reverse($from));
    }
  }
 $to='';
  if($_REQUEST['to']!=''){
    $form=$_REQUEST['to'];
    $to=split('-',$_REQUEST['to']);
    if(count($to==3) and is_numeric($to[0]) and is_numeric($to[0]) and is_numeric($to[0]) ){
      $to=join ('-',array_reverse($to));
    }
  }

  if($to=='' and $from=='' )
    $_SESSION['tables']['orders_list'][4]="where true";
    if($to!='' and $from!='')
      $_SESSION['tables']['orders_list'][4]="where date_index>=$from and date_index<=$to";
  else if($to!='')
    $_SESSION['tables']['orders_list'][4]="where date_index<=$to";
  else
    $_SESSION['tables']['orders_list'][4]="where date_index>=$from and date_index<=$to";

 }

//print_r($order);
$smarty->assign('date_invoiced',strftime("%d %b %Y", strtotime('@'.$order['date_invoiced'])));
if($order['payment_method']=='')
  $payment_method=_('Unknown');
 else{
   if($order['payment_method']==2)
     $payment_method='<img title="'.$_pm_tipo[$order['payment_method']].'"  src="art/icons/creditcards.png" />';
   elseif($order['payment_method']==1)
     $payment_method='<img title="'.$_pm_tipo[$order['payment_method']].'"  src="art/icons/money.png" />';
 elseif($order['payment_method']==4)
     $payment_method='<img title="'.$_pm_tipo[$order['payment_method']].'"  src="art/icons/cheque.png" />';
elseif($order['payment_method']==5)
     $payment_method='<img title="'.$_pm_tipo[$order['payment_method']].'"  src="art/icons/database_table.png" />';
elseif($order['payment_method']==6)
     $payment_method='<img title="'.$_pm_tipo[$order['payment_method']].'"  src="art/icons/paypal.png" />';
   else
     $payment_method=$_pm_tipo[$order['payment_method']];
 }

$smarty->assign('payment_method',$payment_method);
$smarty->assign('deliver_by',$order['deliver_by']);
$smarty->assign('taken_by',mb_ucwords($order['taken_by']));
$smarty->assign('picked_by',mb_ucwords($order['picked_by']));
$smarty->assign('packed_by',mb_ucwords($order['packed_by']));

$d_time=round(get_time_interval($order['date_creation'],$order['date_invoiced']));
if($d_time<1){
  $d_time=_('the same day');
 }else{
  $d_time=_('in').' '.$d_time.' '.ngettext('day','days',$d_time);
 }
$smarty->assign('dispatch_time',$d_time);


$smarty->assign('parcels',$order['parcels']);
$smarty->assign('w',$order['weight']);
$smarty->assign('order_hist',$order['order_hist']);

$smarty->assign('items_out_of_stock',$order['items_out_of_stock']);

$smarty->assign('customer_name',$order['customer_name']);
$smarty->assign('contact',$order['contact_name']);
$smarty->assign('shipping_vateable',money($order['shipping_vateable']));
$smarty->assign('shipping_no_vateable',money($order['shipping_no_vateable']));
if($order['charges_vateable']!=0)
  $smarty->assign('charges_vateable',money($order['charges_vateable']));
if($order['credits_vateable']!=0)
  $smarty->assign('credits_vateable',money($order['credits_vateable']));


if($order['charges_no_vateable']!=0)
  $smarty->assign('charges_no_vateable',money($order['charges_no_vateable']));
if($order['credits_no_vateable']!=0)
  $smarty->assign('credits_no_vateable',money($order['credits_no_vateable']));


//print_r($order);

$smarty->assign('items_vateable',money($order['items_vateable']));
$smarty->assign('items_no_vateable',money($order['items_no_vateable']));
$smarty->assign('net',money($order['net']));






//$smarty->assign('other_charges',money($order['charges']));
//$smarty->assign('fcredit',money(-$order['credits']));
//$smarty->assign('credit',$order['credits']);

//$smarty->assign('items_charge',money($order['items_charge']));

if($order['address_bill']==$order['address_del'])
  $smarty->assign('address_delbill',$order['address_bill']);
 else{
   $smarty->assign('address_bill',$order['address_bill']);
   $smarty->assign('address_del',$order['address_del']);
 }

$smarty->assign('tel',$order['tel']);


$smarty->assign('tax',money($order['tax']));
//$smarty->assign('vat2',money($order['vat2']));
$smarty->assign('total',money($order['total']));

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
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/'.$js_file
		);




$smarty->assign('parent','order.php');
$smarty->assign('title',_('Order').' '.$order['public_id'] );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('tipo_f',$_order_tipo[$order['tipo']]);
$smarty->assign('public_id',$order['public_id']);



$smarty->display($template);
?>