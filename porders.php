<?php
include_once('common.php');
include_once('class.Store.php');
if(!$user->can_view('orders'))
  exit();

if(isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ){
  $store_id=$_REQUEST['store'];

}else{
  $store_id=$_SESSION['state']['orders']['store'];

}

//if(!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ){
//  header('Location: index.php');
//   exit;
//}

$store=new Store($store_id);
$smarty->assign('store',$store);

//$_SESSION['state']['orders']['store']=$store_id;


$q='';

$general_options_list=array();



$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders_store');


$sql="select count(*) as numberof from `Order Dimension`";
$result=mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC))
  $orders=$row['numberof'];
 else 
exit('Internal Error');
mysql_free_result($result);

if(isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])){
$_SESSION['state']['orders']['view']=$_REQUEST['view'];
}
if(isset($_REQUEST['invoice_type']) and preg_match('/^all|invoices|refunds|to_pay|paid$/',$_REQUEST['invoice_type'])){
$_SESSION['state']['orders']['invoices']['invoice_type']=$_REQUEST['invoice_type'];
}
        
if(isset($_REQUEST['dispatch']) and preg_match('/^all_orders|in_process|dispatched|unknown|cancelled|suspended$/',$_REQUEST['dispatch'])){
$_SESSION['state']['orders']['table']['dispatch']=$_REQUEST['dispatch'];
}

$smarty->assign('view',$_SESSION['state']['porder']['view']);
$smarty->assign('dispatch',$_SESSION['state']['porder']['table']['dispatch']);
$smarty->assign('invoice_type',$_SESSION['state']['porder']['porder_invoices']['invoice_type']);
$smarty->assign('dn_state_type',$_SESSION['state']['porder']['porder_dn']['dn_state_type']);

$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);



$smarty->assign('from',$_SESSION['state']['porder']['from']);
$smarty->assign('to',$_SESSION['state']['porder']['to']);

$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'css/edit.css',
		 'css/button.css',
		 'css/container.css'
		 );

$css_files[]='theme.css.php';



$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
		'js/search.js',
		'js/edit_common.js',
                
		'porders.js.php'
		);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Purchase Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$tipo_filter0=($q==''?$_SESSION['state']['orders']['table']['f_field']:'public_id');
$smarty->assign('filter0',$tipo_filter0);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['orders']['table']['f_value']:addslashes($q)));
$filter_menu0=array(
		   'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
		   'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
		   'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
		   'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
		   'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
		   );
$smarty->assign('filter_menu0',$filter_menu0);
$smarty->assign('filter_name0',$filter_menu0[$tipo_filter0]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

$tipo_filter1=$_SESSION['state']['orders']['invoices']['f_field'];
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',($_SESSION['state']['orders']['invoices']['f_value']));
$filter_menu1=array(
		   'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Invoice Number'),
		   'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
		   'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
		   'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
		   'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
		   );
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$paginator_menu1=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu1);

$tipo_filter2=$_SESSION['state']['orders']['dn']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['orders']['dn']['f_value']));
$filter_menu2=array(
		   'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'DN Number'),
		   'customer_name'=>array('db_key'=>'customer_name','menu_label'=>'Customer Name starting with <i>x</i>','label'=>'Customer'),
		   'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
		   'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
		   'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
		   );
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);
 $csv_export_options0=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'public_id'=>array('label'=>_('Order Id'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['public_id']),
                                                             'last_date'=>array('label'=>_('Last Updated'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['last_date']),
                                                              'supplier'=>array('label'=>_('Customer'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['supplier']),
							    'buyername'=>array('label'=>_('Customer'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['buyername']),
                                                             'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['status']),
                                                                                                                
                                                         )
                                                     )
                                          ),
                          
                            'total'=>array('title'=>_('Total'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'totalnet'=>array('label'=>_('Total Net'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['totalnet']),
                                                       'totaltax'=>array('label'=>_('Total Tax'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['totaltax']),
							'shippingmethod'=>array('label'=>_('Total'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['shippingmethod']),
							 'total'=>array('label'=>_('Total'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['total']),
                                                       
                                                   )
                            )
                            ),
			   'payments'=>array('title'=>_('Payments'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sourcetype'=>array('label'=>_('Balance Net'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['sourcetype']),
                                                       'paymentstate'=>array('label'=>_('Balance Tax'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['paymentstate']),
						       'currency_code'=>array('label'=>_('Balance Total'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['currency_code']),
                                                        'actiontaken'=>array('label'=>_('Action Taken'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['actiontaken']),
                                                        'items'=>array('label'=>_('Action Taken'),'selected'=>$_SESSION['state']['porder']['table']['csv_export']['items']),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',3);
$smarty->assign('csv_export_options',$csv_export_options0);

// ---------------------------------------------------------------------------------
 $csv_export_options1=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['code']),
                                                             'date'=>array('label'=>_('Date'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['date']),
                                                             'name'=>array('label'=>_('Supplier'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['name']),
                                                             'currency'=>array('label'=>_('Currency'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['currency']),
                                                             'items'=>array('label'=>_('Items'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['items']),
                                                         )
                                                     )
                                          ),
                            
                            'total'=>array('title'=>_('Total'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'invoice_total_tax'=>array('label'=>_('Tax'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['invoice_total_tax']),
                                                       'invoice_total_net_amount'=>array('label'=>_('Net Amount'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['invoice_total_net_amount']),
                                                       'invoice_total'=>array('label'=>_('Total Amount'),'selected'=>$_SESSION['state']['porder']['porder_invoices']['csv_export']['invoice_total']),
                                                   
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols1',2);
$smarty->assign('csv_export_options1',$csv_export_options1);


// -------------------------------------------------------------------------
 $csv_export_options2=array(
                             'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['code']),
                                                             'date'=>array('label'=>_('Date'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['date']),
                                                             'name'=>array('label'=>_('Supplier'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['name']),
                                                             'currency'=>array('label'=>_('Currency'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['currency']),
                                                             'items'=>array('label'=>_('Items'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['items']),
                                                         )
                                                     )
                                          ),
                            
                            'total'=>array('title'=>_('Total'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'invoice_total_tax'=>array('label'=>_('Tax'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['invoice_total_tax']),
                                                       'invoice_total_net_amount'=>array('label'=>_('Net Amount'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['invoice_total_net_amount']),
                                                       'invoice_total'=>array('label'=>_('Total Amount'),'selected'=>$_SESSION['state']['porder']['porder_dn']['csv_export']['invoice_total']),
                                                   
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols2',2);
$smarty->assign('csv_export_options2',$csv_export_options2);

$smarty->display('porders.tpl');
?>
