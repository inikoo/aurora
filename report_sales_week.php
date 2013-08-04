<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2011, Inikoo 
 
 Version 2.0
*/
include_once('common.php');
include_once('class.Store.php');
if(!$user->can_view('reports')){
  header('Location: index.php');
  exit();
}


if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
  
 header('Location: reports.php?error_no_store');
    exit;
}

if (!in_array($store_id,$user->stores)    ) {
    header('Location: reports.php?forbidden');
    exit;
}

$store=new Store($store_id);
if(!$store->id){
header('Location: reports.php?error_no_store');
    exit;
}





$general_options_list=array();

$smarty->assign('general_options_list',$general_options_list);



if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']=='this_week'){

$date=date("Y-m-d");
}elseif(isset($_REQUEST['tipo']) and $_REQUEST['tipo']=='last_week'){
$date=date("Y-m-d",strtotime('now - 1 week'));
}elseif(isset($_REQUEST['date']) and preg_match('/\d{4}\-\d{2}\-\d{2}/',$_REQUEST['date'])){
$date=$_REQUEST['date'];
}else{
$date=date("Y-m-d");
}

$sql=sprintf("select `Year Week`,`First Day`,`Last Day` from kbase.`Week Dimension` where `First Day`<=%s and `Last Day`>=%s ",
prepare_mysql($date),
prepare_mysql($date)

);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){

$from=$row['First Day'];
$to=$row['Last Day'];
$yearweek=$row['Year Week'];
}else{
    exit("wrong date");
}


$_SESSION['state']['report_sales_week']['invoices']['from']=$from;
$_SESSION['state']['report_sales_week']['invoices']['to']=$to;
$_SESSION['state']['report_sales_week']['from']=$from;
$_SESSION['state']['report_sales_week']['to']=$to;
$_SESSION['state']['report_sales_week']['yearweek']=$yearweek;
$_SESSION['state']['report_sales_week']['store']=$store->id;
//print "$from $to";

$title=_('Weekly Sales Report').' '.$store->data['Store Code'].' '.$yearweek;

$subtitle1=_('Weekly Sales Report').' '.$store->data['Store Code'].' '.$yearweek;
$subtitle2=_('starting').' '.strftime("%x",strtotime($from));


$smarty->assign('title',$title);
$smarty->assign('subtitle1',$subtitle1);
$smarty->assign('subtitle2',$subtitle2);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'theme.css.php'
           );


$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/dropdown.js',
		   'js/csv_common.js',
		'report_sales_week.js.php'
		);


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','reports');
$smarty->assign('title', $title);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$data=array(
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),
array('sales'=>money(0),'invoices'=>0,'avg'=>''),

  );


$sql=sprintf('select WEEKDAY(`Invoice Date`) as day,sum(`Invoice Total Net Amount`) as sales,count(*) as invoices,AVG(`Invoice Total Amount`) promedio from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Date`>=%s and `Invoice Date`<=%s group by WEEKDAY(`Invoice Date`)',
$store->id,
prepare_mysql($from),
prepare_mysql($to)
);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$data[$row['day']]=array('sales'=>money($row['sales']),'invoices'=>number($row['invoices']),'avg'=>money($row['promedio']));
}

$sql=sprintf('select WEEKDAY(`Invoice Date`) as day,sum(`Invoice Total Net Amount`) as sales,count(*) as invoices,AVG(`Invoice Total Amount`) promedio from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Date`>=%s and `Invoice Date`<=%s',
$store->id,
prepare_mysql($from),
prepare_mysql($to)
);
$res=mysql_query($sql);
if($row=mysql_fetch_assoc($res)){
$data[7]=array('sales'=>money($row['sales']),'invoices'=>number($row['invoices']),'avg'=>money($row['promedio']));

}else{


}

$smarty->assign('sal',$data);


$smarty->assign('data',$data);
$smarty->assign('store',$store);



$tipo_filter=$_SESSION['state']['report_sales_week']['invoices']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['report_sales_week']['invoices']['f_value']);

$filter_menu=array(
		   'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Invoice Number'),
		   'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
		   'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
		   'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
		   'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')



		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->display('report_sales_week.tpl');






?>
