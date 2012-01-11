<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.CompanyArea.php');



if (!  ($user->can_view('orders') or $user->data['User Type']=='Warehouse'   ) ) {
    header('Location: index.php?cannot_view');
    exit;
}





$smarty->assign('view','warehouse_orders');


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
              'js/edit_common.js',
              'warehouse_orders.js.php',
              'js/edit_common.js',
              'js/csv_common.js'
          );




$smarty->assign('parent','parts');
$smarty->assign('title', _('Warehouse Orders'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$warehouse_area=new CompanyArea('code','WAH');
$pickers=$warehouse_area->get_current_staff_with_position_code('PICK');
$number_cols=5;
$row=0;
$pickers_data=array();
$contador=0;
foreach($pickers as $picker) {
    if (fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
    $tmp=array();
    foreach($picker as $key=>$value) {
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
foreach($packers as $packer) {
    if (fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
    $tmp=array();
    foreach($packer as $key=>$value) {
        $tmp[preg_replace('/\s/','',$key)]=$value;
    }
    $packers_data[$row][]=$tmp;
    $contador++;
}

$smarty->assign('packers',$packers_data);


//print_r($pickers_data);

$tipo_filter2=$_SESSION['state']['orders']['ready_to_pick_dn']['f_field'];
$smarty->assign('filter0',$tipo_filter2);
$smarty->assign('filter_value0',($_SESSION['state']['orders']['ready_to_pick_dn']['f_value']));
$filter_menu2=array(
                  'public_id'=>array('db_key'=>'public_id','menu_label'=>'Order Number starting with  <i>x</i>','label'=>'Order Number'),
              );
$smarty->assign('filter_menu0',$filter_menu2);
$smarty->assign('filter_name0',$filter_menu2[$tipo_filter2]['label']);
$paginator_menu0=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu0);

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
$smarty->assign('export_csv_table_cols',0);
$smarty->assign('csv_export_options',$csv_export_options0);

$elements_number=array('ReadytoPick'=>0,'ReadytoPack'=>0,'ReadytoShip'=>0,'Others'=>0,'Restock'=>0);
$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Ready to be Picked') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $elements_number['ReadytoPick']=$row['num'];
}
$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Approved') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $elements_number['ReadytoShip']=$row['num'];
}
$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Picked') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $elements_number['ReadytoPack']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Picking & Packing','Packer Assigned','Picker Assigned','Picking','Packing','Packed') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $elements_number['Others']=$row['num'];
}

$sql=sprintf("select count(*) as num from  `Delivery Note Dimension` where `Delivery Note State`  in ('Cancelled to Restock') ");
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    $elements_number['Restock']=$row['num'];
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['customer']['table']['elements']);



$smarty->display('warehouse_orders.tpl');
?>
