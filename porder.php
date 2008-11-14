<?
include_once('common.php');
include_once('classes/Order.php');
include_once('classes/Supplier.php');

$last_order_used=false;
if($_REQUEST['id']=='new'  ){
  if(!isset($_REQUEST['supplier_id']) or !is_numeric($_REQUEST['supplier_id']) )
    exit(_('Unknown supplier'));
  //Check id is any new order open!!!
  if(is_numeric($_SESSION['state']['po']['new']) and  (date('U')-$_SESSION['state']['po']['new_timestamp'])<86400  ){
    $last_order_used=true;
       $po=new Order('po',$_SESSION['state']['po']['new']);
    $_SESSION['state']['po']['new_timestamp']=date('U');
  }else{
     $po=new Order('po',array('supplier_id'=>$_REQUEST['supplier_id']));
    $_SESSION['state']['po']['new']=$po->id;
    $_SESSION['state']['po']['new_timestamp']=date('U');
  }
 }elseif(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
   
   $po=new Order('po',$_REQUEST['id']);

}else
   exit(_('Error the Purchese Order do not exist'));


$po_id = $po->id;
$_SESSION['state']['po']['id']=$po->id;

$_SESSION['state']['supplier']['id']=$po->data['supplier_id'];

$supplier=new Supplier($po->data['supplier_id']);


$smarty->assign('po',$po->data);
$smarty->assign('supplier',$supplier->data);


$all_products=false;
switch($po->data['status']){
 case('new'):
   $smarty->assign('title',_('New Purchase Order for').' '.$supplier->data['code']);
   $all_products=true;
 }

// $sql=sprintf("select s.id as id,code as code, s.name as name from supplier as s left join contact as c on (contact_id=c.id) where s.id=%d ",$supplier_id);
// //print "$sql";
// $result =& $db->query($sql);
// if(!$supplier=$result->fetchRow())
//    exit(_('Error the Supplier do not exist'));


// if($tipo==0){
//   $_SESSION['tables']['po_item'][4][2]=1;
//   $_SESSION['tables']['po_item'][3]=0;
//   $_SESSION['tables']['po_item'][2]=25;
//   $smarty->assign('filter1',$_SESSION['tables']['po_item'][6]);
//   $smarty->assign('filter_value1',$_SESSION['tables']['po_item'][7]);
//   $smarty->assign('expected_total_value',money(0));
//   $smarty->assign('expected_products',0);
//    $smarty->assign('po_date_creation',strftime("%e %b %Y", strtotime('now')));
//    switch($_SESSION['tables']['po_item'][6]){
//    case('p.code'):
//      $filter_text=_('Our Code');
//      break;
//    case('sup_code'):
//      $filter_text=_('Supplier Code');
//      break;
//    default:
//      $filter_text='?';
//    }
//    $smarty->assign('filter_name1',$filter_text);
//    $smarty->assign('t_title1',_('Products'));
//    $smarty->assign('title',_('New Purchase Order for').' '.$supplier['code']);

//  }else{
//    $_SESSION['tables']['po_item'][4][2]=0;
//    $_SESSION['tables']['po_item'][3]=0;
//    $_SESSION['tables']['po_item'][2]=500;
//    $smarty->assign('title',_('Deliver note for').' '.$supplier['code']);
   
//    $sql="select id,alias from staff where active=1";
//    $result =& $db->query($sql);
   
//    $staff_list=array('-1'=>'','0'=>_('Nobody'));
//    while($row=$result->fetchRow()){
//      $staff_list[$row['id']]=$row['alias'];
//    }
//    $smarty->assign('staff_list',$staff_list);
//    $smarty->assign('received_id',$porder['received_by']);
//    $smarty->assign('checked_id',$porder['checked_by']);


//  }


// $_SESSION['tables']['po_item'][4][3]=$tipo;
// $_SESSION['tables']['po_item'][4][0]=$po_id;
// $_SESSION['tables']['po_item'][4][1]=$supplier_id;
//  $smarty->assign('value_goods',money($porder['goods']));
//   $smarty->assign('value_shipping',money($porder['shipping']));
//   $smarty->assign('nm_value_shipping',number($porder['shipping'],0));
//   $smarty->assign('nc_value_shipping',money_cents($porder['shipping']));

//   $smarty->assign('value_vat',money($porder['vat']));
//   $smarty->assign('nm_value_vat',number($porder['vat'],0));
//   $smarty->assign('nc_value_vat',money_cents($porder['vat']));

//   $smarty->assign('value_total',money($porder['total']));
//   $smarty->assign('nm_value_total',number($porder['total'],0));
// $smarty->assign('nc_value_total',money_cents($porder['total']));

// $smarty->assign('value_other',money($porder['charges']));
// $smarty->assign('other',$porder['charges']);
// $smarty->assign('nm_value_other',number($porder['charges'],0));
// $smarty->assign('nc_value_other',money_cents($porder['charges']));
// $smarty->assign('n_value_other',$porder['charges']);



//   $smarty->assign('new',0);
//   $smarty->assign('value_dif',money($porder['diff']));
//   $smarty->assign('n_value_dif',$porder['diff']);

//   $smarty->assign('dn_number',$porder['public_id']);
//   $smarty->assign('po_date_invoice',strftime("%e %B %Y", $porder['date_invoice']));
//   $smarty->assign('v_po_date_invoice',strftime("%d-%m-%Y", $porder['date_invoice']));
//   $smarty->assign('v_po_date_received',strftime("%d-%m-%Y", $porder['date_received']));
//   $smarty->assign('v_po_time_received',strftime("%H:%M", $porder['date_received']));
//   $smarty->assign('po_datetime_received',strftime("%e %B %Y %H:%M", $porder['date_received']));



// $smarty->assign('po_date_creation',strftime("%e %B %Y", $porder['date_creation']));
// $smarty->assign('po_date_submited',($porder['date_submited']==''?'':strftime("%e %B %Y", $porder['date_submited'])));
// $smarty->assign('po_date_expected',($porder['date_expected']>0?strftime("%e %B %Y", $porder['date_expected']):'') );

//   $smarty->assign('dn_datetimereceived',$porder['date_received']);
//   $supplier_id=$porder['supplier_id'];
  

//   $js='js/newpo.js.php?po_id='.$po_id;
//   $smarty->assign('items',$porder['items']);

// $smarty->assign('t_title1',_('Products'));
// $smarty->assign('view_all', $_SESSION['tables']['po_item'][4][2]);

// $smarty->assign('box_layout','yui-t0');
// $smarty->assign('po_id',$po_id);

// $smarty->assign('po_number',sprintf("%05d",$po_id));


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
	       	'js/porder.js.php'
		);




$smarty->assign('parent','suppliers.php');
//$smarty->assign('supplier_id',$supplier_id);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('all_products',$all_products);


//$supplier_home=_("Suppliers List");
//$smarty->assign('home',$supplier_home);


//$smarty->assign('name',$supplier['name']);
//$smarty->assign('code',$supplier['code']);
//$smarty->assign('id',$supplier['id']);

//print $porder['items'];


//$smarty->assign('tipo',$tipo);
$smarty->assign('currency',$myconf['currency_symbol']);
$smarty->assign('decimal_point',$myconf['decimal_point']);



$smarty->display('po.tpl');
?>