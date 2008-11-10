<?
include_once('common.php');
include_once('report_functions.php');

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

		'js/report_pp.js.php'
		);



$smarty->assign('parent','reports.php');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$tipo=$_REQUEST['tipo'];
if($tipo=='f'){

  $from=$_REQUEST['from'];
  $to=$_REQUEST['to'];
   $title=_('Pickers & Packers Report');
 }elseif($tipo=='m'){
   $year=$_REQUEST['y'];
   $month=$_REQUEST['m'];
   
   $_time=mktime(0, 0, 0,$month ,1 , $year);
   $_time_n=mktime(0, 0, 0,$month+1 ,1 , $year);
   $_time_p=mktime(0, 0, 0,$month-1 ,1 , $year);

   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", mktime(0, 0, 0, $month+1, 0, $year));
   $period=date("F Y", $_time);
   $title="$period "._('Pickers & Packers Report');

   $smarty->assign('up',array('url'=>'tipo=y&y='.date("Y",$_time),'title'=>date("Y",$_time)));
   $smarty->assign('next',array('url'=>'tipo=m&m='.date("m",$_time_n).'&y='.date("Y",$_time_n),'title'=>date("F",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=m&m='.date("m",$_time_p).'&y='.date("Y",$_time_p),'title'=>date("F",$_time_p)));

 }elseif($tipo=='y'){
   $year=$_REQUEST['y'];

   
   $_time=mktime(0, 0, 0,1 ,1 , $year);
   $_time_n=mktime(0, 0, 0,1 ,1 , $year+1);
   $_time_p=mktime(0, 0, 0,1 ,1 , $year-1);

   $from=date("d-m-Y", $_time);
   $to=date("d-m-Y", mktime(0, 0, 0, 1, 0, $year+1));
   $period=date("Y", $_time);
   $title="$period "._('Pickers & Packers Report');

   $smarty->assign('tipo_title',_('Annual Report'));
   $smarty->assign('next',array('url'=>'tipo=y&y='.date("Y",$_time_n),'title'=>date("Y",$_time_n)));
   $smarty->assign('prev',array('url'=>'tipo=y&y='.date("Y",$_time_p),'title'=>date("Y",$_time_p)));
   $m=array();
   foreach( range(1,12) as $_m){
     
     $m[]=substr(strftime("%b", mktime(0, 0, 0, $_m, 1, 2000)),0,1);
     
   }

    $smarty->assign('m',$m);
  }
  



$interval_data=sales_in_interval($from,$to);
$day_interval=get_time_interval(strtotime($from),(strtotime($to)));


$smarty->assign('tipo',$tipo);
$smarty->assign('period',$period);

$smarty->assign('title',$title);
$smarty->assign('year',date('Y'));
$smarty->assign('month',date('m'));
$smarty->assign('month_name',date('M'));


$smarty->assign('week',date('W'));
$smarty->assign('from',date('d-m-Y'));
$smarty->assign('to',date('d-m-Y'));

$smarty->display('report_pp.tpl');


?>

