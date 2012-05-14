<?php
include_once('common.php');
include_once('class.Store.php');
if (!$user->can_view('orders'))
    exit();

if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['orders']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);
$smarty->assign('store',$store);

$_SESSION['state']['orders']['store']=$store_id;


$q='';

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'warehouse_orders.php','label'=>_('Warehouse Operations'));

$general_options_list[]=array('tipo'=>'url','url'=>'orders_lists.php?store='.$store->id,'label'=>_('Lists'));


//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Orders'));
$smarty->assign('search_scope','orders');


$sql="select count(*) as numberof from `Order Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC))
    $orders=$row['numberof'];
else
    exit('Internal Error');
mysql_free_result($result);

if (isset($_REQUEST['view']) and preg_match('/^orders|invoices|dn$/',$_REQUEST['view'])) {
    $_SESSION['state']['orders']['view']=$_REQUEST['view'];
}
if (isset($_REQUEST['invoice_type']) and preg_match('/^all|invoices|refunds|to_pay|paid$/',$_REQUEST['invoice_type'])) {
    $_SESSION['state']['orders']['invoices']['invoice_type']=$_REQUEST['invoice_type'];
}

if (isset($_REQUEST['dispatch']) and preg_match('/^all_orders|in_process|dispatched|unknown|cancelled|suspended$/',$_REQUEST['dispatch'])) {
    $_SESSION['state']['orders']['table']['dispatch']=$_REQUEST['dispatch'];
}

$block_view=$_SESSION['state']['orders']['view'];

$smarty->assign('block_view',$block_view);
$smarty->assign('dispatch',$_SESSION['state']['orders']['table']['dispatch']);
$smarty->assign('invoice_type',$_SESSION['state']['orders']['invoices']['invoice_type']);
$smarty->assign('dn_state_type',$_SESSION['state']['orders']['dn']['dn_state_type']);

$smarty->assign('dn_view',$_SESSION['state']['stores']['delivery_notes']['view']);



$smarty->assign('from',$_SESSION['state']['orders']['from']);
$smarty->assign('to',$_SESSION['state']['orders']['to']);

$smarty->assign('box_layout','yui-t0');


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
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
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'orders.js.php'
          );




$smarty->assign('parent','orders');
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
                  'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
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
                  'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
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
                  'country'=>array('db_key'=>'country','menu_label'=>'Orders from country code <i>xxx</i>','label'=>'Country Code')
              );
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu2=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu2);



// -----------------------------------------------export csv code starts here------------------------
$csv_export_options0=array(
                         'description'=>array(
                                           'title'=>_('Description'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'code'=>array('label'=>_('Order Id'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['code']),
                                                          'last_date'=>array('label'=>_('Last Updated'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['last_date']),

                                                          'customer'=>array('label'=>_('Customer'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['customer']),
                                                          'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['status']),


                                                      )
                                                  )
                                       ),

                         'total'=>array('title'=>_('Total'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'totalnet'=>array('label'=>_('Total Net'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['totalnet']),
                                                       'totaltax'=>array('label'=>_('Total Tax'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['totaltax']),
                                                       'total'=>array('label'=>_('Total'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['total']),

                                                   )
                                               )
                                       ),
                         'balance'=>array('title'=>_('Balance'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'balancenet'=>array('label'=>_('Balance Net'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['balancenet']),
                                                         'balancetax'=>array('label'=>_('Balance Tax'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['balancetax']),
                                                         'balancetotal'=>array('label'=>_('Balance Total'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['balancetotal']),

                                                     )
                                                 )
                                         ),
                         'outstanding'=>array('title'=>_('Outstanding Balance'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'outstandingbalancenet'=>array('label'=>_('Outstanding Balance Net'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['outstandingbalancenet']),
                                                             'outstandingbalancetax'=>array('label'=>_('Outstanding Balance Tax'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['outstandingbalancetax']),
                                                             'outstandingbalancetotal'=>array('label'=>_('Outstanding Balance Total'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['outstandingbalancetotal']),

                                                         )
                                                     )
                                             ),
                         'otherdetails'=>array('title'=>_('Other Details'),
                                               'rows'=>
                                                      array(
                                                          array(
                                                              'contactname'=>array('label'=>_('Customer Contact Name'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['contactname']),
                                                              'sourcetype'=>array('label'=>_('Source Type'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['sourcetype']),
                                                              'paymentstate'=>array('label'=>_('Payment State'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['paymentstate'])


                                                          )
                                                      )
                                              ),
                         'orderdetails'=>array('title'=>_('Order Details'),
                                               'rows'=>
                                                      array(
                                                          array(

                                                              'actiontaken'=>array('label'=>_('Actions Taken'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['actiontaken']),
                                                              'ordertype'=>array('label'=>_('Type'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['ordertype']),
                                                              'shippingmethod'=>array('label'=>_('Shipping Method'),'selected'=>$_SESSION['state']['orders']['table']['csv_export']['shippingmethod']),


                                                          )
                                                      )
                                              )
                     );
$smarty->assign('export_csv_table_cols',5);
$smarty->assign('csv_export_options',$csv_export_options0);
// -----------------------------------------------export csv code ends here------------------------

// ----------------------------------export csv array for invoice list starts here -----------------------------------------------
$csv_export_options1=array(
                         'description'=>array(
                                           'title'=>_('Description'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['code']),
                                                          'date'=>array('label'=>_('Date'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['date']),
                                                          'name'=>array('label'=>_('Customer'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['name']),


                                                      )
                                                  )
                                       ),

                         'payment_details'=>array('title'=>_('Payment Details'),
                                                  'rows'=>
                                                         array(
                                                             array(
                                                                 'paymentmethod'=>array('label'=>_('Pament Method'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['paymentmethod']),
                                                                 'invoicefor'=>array('label'=>_('Invoice For'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoicefor']),
                                                                 'invoicepaid'=>array('label'=>_('Invoice Paid'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoicepaid']),

                                                             )
                                                         )
                                                 ),
                         'other_invoice_details'=>array('title'=>_('Other Invoice Details'),
                                                        'rows'=>
                                                               array(
                                                                   array(
                                                                       'invoice_total_amount'=>array('label'=>_('Total Amount'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoice_total_amount']),
                                                                       'invoice_total_profit'=>array('label'=>_('Total Profit'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoice_total_profit']),
                                                                       'invoice_total_tax_amount'=>array('label'=>_('Total Tax Amount'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoice_total_tax_amount']),
                                                                       'invoice_total_tax_adjust_amount'=>array('label'=>_('Total Tax Adjust Amount'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoice_total_tax_adjust_amount']),
                                                                       'invoice_total_adjust_amount'=>array('label'=>_('Total Adjust Amount'),'selected'=>$_SESSION['state']['orders']['invoices']['csv_export']['invoice_total_adjust_amount']),

                                                                   )
                                                               )
                                                       )
                     );
$smarty->assign('export_csv_table_cols1',3);
$smarty->assign('csv_export_options1',$csv_export_options1);
// ----------------------------------export csv array for invoices list ends here -----------------------------------------------

// ----------------------------------export csv array for delivery notes list starts here ---------------------------------------
$csv_export_options2=array(
                         'description'=>array(
                                           'title'=>_('Description'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'id'=>array('label'=>_('Delivery Note ID'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['id']),
                                                          'date'=>array('label'=>_('Date'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['date']),
                                                          'type'=>array('label'=>_('Type'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['type']),
                                                          'customer_name'=>array('label'=>_('Customer'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['customer_name']),
                                                          'weight'=>array('label'=>_('Weight'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['weight']),
                                                          'parcels_no'=>array('label'=>_('Parcels'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['parcels_no'])

                                                      )
                                                  )
                                       ),

                         'picking'=>array('title'=>_('Picking'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'start_picking_date'=>array('label'=>_('Start Picking Date'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['start_picking_date']),
                                                         'finish_picking_date'=>array('label'=>_('Finish Picking Date'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['finish_picking_date'])
                                                     )
                                                 )
                                         ),
                         'packing'=>array('title'=>_('Packing'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'start_packing_date'=>array('label'=>_('Start Packing Date'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['start_packing_date']),
                                                         'finish_packing_date'=>array('label'=>_('Finish Packing Date'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['finish_packing_date'])
                                                     )
                                                 )
                                         ),
                         'other_details'=>array('title'=>_('Other Details'),
                                                'rows'=>
                                                       array(
                                                           array(
                                                               'state'=>array('label'=>_('State'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['state']),
                                                               'dispatched_method'=>array('label'=>_('Dispatch Method'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['dispatched_method']),
                                                               'parcel_type'=>array('label'=>_('Parcel Type'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['parcel_type']),
                                                               'boxes_no'=>array('label'=>_('Number Of Boxes'),'selected'=>$_SESSION['state']['orders']['dn']['csv_export']['boxes_no'])
                                                           )
                                                       )
                                               )

                     );
$smarty->assign('export_csv_table_cols2',4);
$smarty->assign('csv_export_options2',$csv_export_options2);
// ----------------------------------export csv array for delivery notes list ends here-----------------------------------------------

if($block_view=='invoices')
$smarty->assign('title', _('Invoices'));

elseif($block_view=='dn')
$smarty->assign('title', _('Delivery Notes'));

else
$smarty->assign('title', _('Orders'));


$smarty->display('orders.tpl');
?>
