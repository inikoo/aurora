<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.CompanyArea.php');



if(!($user->can_view('orders')    ) ){
  header('Location: index.php?cannot_view');
   exit;
}



$q='';
if(isset($_REQUEST['search']) and $_REQUEST['search']!=''  ){
  // SEARCH!!!!!!!!!!!!
  $q=$_REQUEST['search'];
  //  print "$q";
  $sql=sprintf("select `Order Key` as id from `Order Dimension` where `Order Public ID`='%s' ",addslashes($q));
  $result=mysql_query($sql);
  if($found=mysql_fetch_array($result, MYSQL_ASSOC)){
    header('Location: order.php?id='. $found['id']);
    exit;
  }
  mysql_free_result($result);
  $_SESSION['tables']['order_list'][5]='public_id';
  $_SESSION['tables']['order_list'][6]=addslashes($q);


 }



$sql="select count(*) as numberof from `Order Dimension`";
$result=mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC))
  $orders=$row['numberof'];
 else 
exit('Internal Error');
mysql_free_result($result);



$smarty->assign('view','warehouse_orders');
$smarty->assign('from',$_SESSION['state']['orders']['from']);
$smarty->assign('to',$_SESSION['state']['orders']['to']);

$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 
		 'button.css',
		 'container.css'
		 
		 );
$theme="";
if($theme)
{
array_push($css_files, 'themes_css/'.$Themecss1);   
array_push($css_files, 'themes_css/'.$Themecss2);
array_push($css_files, 'themes_css/'.$Themecss3);
}    
 

else{
array_push($css_files, 'common.css'); 

array_push($css_files, 'table.css');
}


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
		'js/edit_common.js',
		'warehouse_orders.js.php',
		 'js/edit_common.js',
	         'js/csv_common.js'
		);




$smarty->assign('parent','orders');
$smarty->assign('title', _('Warehouse Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$warehouse_area=new CompanyArea('code','WAH');
$pickers=$warehouse_area->get_current_staff_with_position_code('PICK');
$number_cols=5;
$row=0;
 $pickers_data=array();
    $contador=0;
    foreach($pickers as $picker){
       if(fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
        $tmp=array();
     foreach($picker as $key=>$value){
       $tmp[preg_replace('/\s/','',$key)]=$value;
     }
      $pickers_data[$row][]=$tmp;
 $contador++;
   }

$smarty->assign('pickers',$pickers_data);

$packers=$warehouse_area->get_current_staff_with_position_code('PACK');
$number_cols=5;
$row=0;
 $packers_data=array();
    $contador=0;
    foreach($packers as $packer){
       if(fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
        $tmp=array();
     foreach($packer as $key=>$value){
       $tmp[preg_replace('/\s/','',$key)]=$value;
     }
      $packers_data[$row][]=$tmp;
 $contador++;
   }

$smarty->assign('packers',$packers_data);


//print_r($pickers_data);

$tipo_filter2=$_SESSION['state']['orders']['ready_to_pick_dn']['f_field'];
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',($_SESSION['state']['orders']['ready_to_pick_dn']['f_value']));
$filter_menu2=array(
		   'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
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
                                                             'id'=>array('label'=>_('Order Id'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['id']),
                                                             'date'=>array('label'=>_('Last Updated'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['date']),
                                                            
                                                             'type'=>array('label'=>_('Type'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['type']),
                                                             'customer_name'=>array('label'=>_('Customer Name'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['customer_name']),
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                          
                            'details'=>array('title'=>_('Other Details'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'weight'=>array('label'=>_('Weight'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['weight']),
                                                       'picks'=>array('label'=>_('Picks'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['picks']),
							 'parcel_type'=>array('label'=>_('Parcel Type'),'selected'=>$_SESSION['state']['orders']['ready_to_pick_dn']['csv_export']['parcel_type'])
                                                       
                                                 
                                                       
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',2);
$smarty->assign('csv_export_options',$csv_export_options0);


$smarty->display('warehouse_orders.tpl');
?>
