<?php
include_once('common.php');

if(!isset($_REQUEST['tipo']))
  $_REQUEST['tipo']='m';

$tipo=$_REQUEST['tipo'];
switch($_REQUEST['tipo']){
 case('m'):
   if(!isset($_REQUEST['m']) or !is_numeric($_REQUEST['m']))
     $_REQUEST['m']=date('m');
   if(!isset($_REQUEST['y']) or !is_numeric($_REQUEST['y']))
     $_REQUEST['y']=date('Y');
   $m=$_REQUEST['m'];
   $y=$_REQUEST['y'];
  $smarty->assign('m', $m);
  $smarty->assign('y', $y);
  $smarty->assign('tipo', 'm');

  
  $smarty->assign('title', _('Monthly Sales Report'));

  $time_interval=sprintf(" year(date_index)=%d and month(date_index)=%d ",$y,$m);
  $time_interval_last_year=sprintf(" year(date_index)=%d and month(date_index)=%d ",$y-1,$m);
  $smarty->assign('f2_interval', strftime("%B %Y", mktime(0, 0, 0, $m, 1, $y)));
  break;
 case('w'):
   if(!isset($_REQUEST['w']) or !is_numeric($_REQUEST['w']))
     $_REQUEST['w']=date('W');
   if(!isset($_REQUEST['y']) or !is_numeric($_REQUEST['y']))
     $_REQUEST['y']=date('Y');
   $w=$_REQUEST['w'];
   $y=$_REQUEST['y'];
  $smarty->assign('w', $w);
  $smarty->assign('y', $y);
  $smarty->assign('tipo', 'w');

  
  $smarty->assign('title', _('Weekly Sales Report'));

  $time_interval=sprintf(" year(date_index)=%d and week(date_index)=%d ",$y,$w);
  $time_interval_last_year=sprintf(" year(date_index)=%d and week(date_index)=%d ",$y-1,$w);
  $smarty->assign('f2_interval', $w.' '.strftime("%Y", mktime(0, 0, 0, 1, 1, $y)));
  break;
 case('y'):
   if(!isset($_REQUEST['y']) or !is_numeric($_REQUEST['y']))
     $_REQUEST['y']=date('Y');
   $y=$_REQUEST['y'];
   $smarty->assign('y', $y);
   $smarty->assign('tipo', 'y');

  
  $smarty->assign('title', _('Yearly Sales Report'));
  
  $time_interval=sprintf(" year(date_index)=%d",$y);
  $time_interval_last_year=sprintf(" year(date_index)=%d  ",$y-1);
  $smarty->assign('f2_interval', strftime("%Y", mktime(0, 0, 0, 1, 1, $y)));
  break;
 case('f'):
   if(!isset($_REQUEST['from']))
     $_REQUEST['from']='';
   if(!isset($_REQUEST['to']))
     $_REQUEST['to']='';


   $from='';
   if($_REQUEST['from']!=''){
     $from=split('-',$_REQUEST['from']);
     if(count($from==3) and is_numeric($from[0]) and is_numeric($from[0]) and is_numeric($from[0]) ){
      $f_from=sprintf("%02d-%02d-%d",$from[0],$from[1],$from[2]);
      $d1=$from[0];
      $m1=$from[1];
      $y1=$from[2];
      $fromf=join ('-',$from);
      $from=join ('-',array_reverse($from));
     }
   }
   $to='';
   if($_REQUEST['to']!=''){
     $to=split('-',$_REQUEST['to']);
     if(count($to==3) and is_numeric($to[0]) and is_numeric($to[0]) and is_numeric($to[0]) ){
       $f_to=sprintf("%02d-%02d-%d",$to[0],$to[1],$to[2]);
       $d2=$to[0];
       $m2=$to[1];
       $y2=$to[2];
       $tof=join ('-',$to);
       $to=join ('-',array_reverse($to));
       
     }
   }




  $smarty->assign('title', _('Sales Report'));
  

  

  if($to=='' and $from=='' ){
    $time_interval=sprintf(" ");
    $smarty->assign('f2_interval',_('All orders'));
  }
  if($to!='' and $from!=''){
     $time_interval=" date_index>='$from 00:00:00' and date_index<='$to 23:59:59'";
     $smarty->assign('f2_interval', strftime("%e %B %Y", mktime(0, 0, 0, $m1, $d1, $y1)).' '._('to').' '.strftime("%e %B %Y", mktime(0, 0, 0, $m2, $d2, $y2))      );
  }else if($from!=''){
    $time_interval="  date_index>='$from 00:00:00''";
    $smarty->assign('f2_interval', _('Since').' '.strftime("%A %e %B %Y", mktime(0, 0, 0, $m1, $d1, $y1)));
  }
  else{
   $time_interval=" date_index<='$to 23:59:59'";
   $smarty->assign('f2_interval', _('Up to').' '.strftime("%A %e %B %Y", mktime(0, 0, 0, $m2, $d2, $y2)));
       
  }

  //  print $time_interval;
 $smarty->assign('tipo', 'f');
  $smarty->assign('from', $fromf);
 $smarty->assign('to', $tof);

  break;

 }   

$_SESSION['tables']['order_list'][4]="where  $time_interval ";

   // Invoices first get all the info from them ok;
   // tols
   
$sql=sprintf(" select count(*) as orders ,sum(total)as total,sum(vat) as vat,sum(vat2) as vat2,sum(net) as net ,sum(shipping) as shipping,sum(credits) as credits from orden where tipo=2 and  $time_interval  ");
$result=mysql_query($sql);
if(!$invoice_totals=mysql_fetch_array($result, MYSQL_ASSOC))
  exit(_('Error'));

if($tipo!='f'){
  $sql=sprintf(" select count(*) as orders ,sum(total)as total,sum(vat) as vat,sum(vat2) as vat2,sum(net) as net ,sum(shipping) as shipping,sum(credits) as credits from orden where tipo=2 and $time_interval_last_year ");
  $result=mysql_query($sql);
  if(!$invoice_totals_lastyear=mysql_fetch_array($result, MYSQL_ASSOC))
    exit(_('Error'));
  $smarty->assign('orders_ly', $invoice_totals_lastyear['orders']);
$smarty->assign('total_ly', money($invoice_totals_lastyear['total']));
$smarty->assign('vat_ly', money($invoice_totals_lastyear['vat']));
$smarty->assign('vat2_ly', money($invoice_totals_lastyear['vat2']));

 }

$sql=sprintf(" select count(*) as orders ,sum(total)as total from orden where tipo=1 and $time_interval ");
$result=mysql_query($sql);
if(!$dn_totals=mysql_fetch_array($result, MYSQL_ASSOC))
  exit(_('Error'));
$sql=sprintf(" select count(*) as orders ,sum(total)as total from orden where tipo=4 and $time_interval ");
   $result=mysql_query($sql);
if(!$cancelled_totals=mysql_fetch_array($result, MYSQL_ASSOC))
  exit(_('Error'));

$sql=sprintf(" select count(*) as orders ,sum(total)as total from orden where tipo=7 and $time_interval ");
$result=mysql_query($sql);
if(!$sample_totals=mysql_fetch_array($result, MYSQL_ASSOC))
  exit(_('Error'));



$smarty->assign('orders', $invoice_totals['orders']);
$smarty->assign('total', money($invoice_totals['total']));
$smarty->assign('vat', money($invoice_totals['vat']));
$smarty->assign('vat2', money($invoice_totals['vat2']));



$smarty->assign('dns', $dn_totals['orders']);
$smarty->assign('dn_total', money($dn_totals['total']));

$smarty->assign('cancelled', $cancelled_totals['orders']);
$smarty->assign('cancelled_total', money($cancelled_totals['total']));

$smarty->assign('samples', $sample_totals['orders']);
$smarty->assign('sample_total', money($sample_totals['total']));



$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		
		 'css/container.css'
		 );

$css_files[]='theme.css.php';

$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/js/common.js',
		'js/js/table_common.js',
		'js/report_sales.js.php'
		);




$smarty->assign('parent','reports');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//$smarty->assign('table_title',_('Orders'));



$smarty->assign('filter','customer_name');
$smarty->assign('filter_name',_('Customer Name'));
$smarty->assign('filter_value','');



$smarty->display('report_sales.tpl');
?>
