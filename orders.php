<?
include_once('common.php');

$q='';
if(isset($_REQUEST['search']) and $_REQUEST['search']!=''  ){
  // SEARCH!!!!!!!!!!!!
  $q=$_REQUEST['search'];
  //  print "$q";
  $sql=sprintf("select id from orden where public_id='%s' ",addslashes($q));
  $result =& $db->query($sql);
  if($found=$result->fetchRow()){
    header('Location: order.php?id='. $found['id']);
    exit;
  }
  
  $_SESSION['tables']['order_list'][5]='public_id';
  $_SESSION['tables']['order_list'][6]=addslashes($q);


 }




if(isset($_REQUEST['from']) or isset($_REQUEST['to'])){
  // ok limitite time period
  
  $from='';
  if($_REQUEST['from']!=''){
    $from=split('-',$_REQUEST['from']);
    if(count($from==3) and is_numeric($from[0]) and is_numeric($from[0]) and is_numeric($from[0]) ){
      $f_from=sprintf("%02d-%02d-%d",$from[0],$from[1],$from[2]);
      $from=join ('-',array_reverse($from));
    }
  }
 $to='';
  if($_REQUEST['to']!=''){
    $to=split('-',$_REQUEST['to']);
    if(count($to==3) and is_numeric($to[0]) and is_numeric($to[0]) and is_numeric($to[0]) ){
      $f_to=sprintf("%02d-%02d-%d",$to[0],$to[1],$to[2]);
      $to=join ('-',array_reverse($to));

    }
  }

  if($to=='' and $from=='' )
    $_SESSION['tables']['order_list'][4]="where true";
  if($to!='' and $from!='')
    $_SESSION['tables']['order_list'][4]="where date_index>='$from' and date_index<='$to'";
  else if($to!='')
    $_SESSION['tables']['order_list'][4]="where date_index<='$to'";
  else
    $_SESSION['tables']['order_list'][4]="where date_index>='$from' and date_index<='$to'";

  
$smarty->assign('from',$f_from);
$smarty->assign('to',$f_to);


 }
 else
   $_SESSION['tables']['order_list'][4]="where true";



//print_r($_SESSION['tables']['order_list']);

$sql="select count(*) as numberof from orden";
$result =& $db->query($sql);
if($row=$result->fetchRow())
  $orders=$row['numberof'];
 else 
   exit;


$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
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
		'js/calendar_common.js.php',

		'js/orders.js.php'
		);




$smarty->assign('parent','orders.php');
$smarty->assign('title', _('Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Order List'));










$tipo_filter=($q==''?$_SESSION['tables']['order_list'][5]:'public_id');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['tables']['order_list'][6]:addslashes($q)));




switch($tipo_filter){
 case('public_id'):
   $filter_text=_('Order Number');
   break;
 case('customer_name'):
   $filter_text=_('Customer Name');
   break;
 case('max'):
   $filter_text=_('Maximum Days');
   break;
 case('min'):
   $filter_text=_('Mininum Days');
   break;
 case('maxvalue'):
   $filter_text=_('Maximum Value');
   break;
 case('minvalue'):
   $filter_text=_('Mininum Value');
   break;
 default:
   $filter_text='?';
 }
$smarty->assign('filter_name',$filter_text);

$smarty->assign('table_info',$orders.'  '.ngettext('Order','Orders',$orders));


$smarty->display('orders.tpl');
?>