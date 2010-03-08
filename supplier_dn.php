<?php
include_once('common.php');
include_once('class.Supplier.php');
include_once('class.PurchaseOrder.php');
include_once('class.SupplierDeliveryNote.php');



///print_r($_REQUEST);


if(isset($_REQUEST['id'])){

  $supplier_delivery_note=new SupplierDeliveryNote($_REQUEST['id']);
  if(!$supplier_delivery_note->id)
    exit("Error supplier deliver note can no be found");
  $supplier=new Supplier('id',$supplier_delivery_note->data['Supplier Delivery Note Supplier Key']);
}else if(isset($_REQUEST['new']) ){

  $supplier_key=false;

  
  if(isset($_REQUEST['supplier_key']) and is_numeric($_REQUEST['supplier_key'])){
    $supplier_key=$_REQUEST['supplier_key'];
  }


if(isset($_REQUEST['po'])){


if(!isset($_REQUEST['number']) or $_REQUEST['number']==''){
  exit('No Supplier Delivery Note Public ID');
  }


$supplier_dn_public_id=$_REQUEST['number'];

  $po_keys=preg_split('/,/',$_REQUEST['po']);
  $po_objects=array();
  $supplier_key=false;
  foreach($po_keys as $po_key){
    if(!is_numeric($po_key))
      continue;
    $po=new PurchaseOrder($po_key);
    if(!$po->id)
      continue;
    if(!$supplier_key)
      $supplier_key=$po->data['Purchase Order Supplier Key'];
    else{
      if($supplier_key!=$po->data['Purchase Order Supplier Key'])
	continue;
    }
    
    if($po->data['Purchase Order Current Dispatch State']=='Submitted' or $po->data['Purchase Order Current Dispatch State']=='In Process' ){
      $po_objects[]=array('object'=>$po);
    }
    
  }
}


$supplier=new Supplier($supplier_key);
if(!$supplier->id){
  exit("error supplier not found/supplier incorrect");
}





  $editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
		);

  $data=array(
	      'Supplier Delivery Note Supplier Key'=>$supplier->id
	      ,'Supplier Delivery Note Public ID'=>$supplier_dn_public_id
	      ,'editor'=>$editor
	      );

  $supplier_delivery_note=new SupplierDeliveryNote('find',$data,'create');
  if($supplier_delivery_note->error or !$supplier_delivery_note->id){
    print_r($supplier_delivery_note);
    exit('error when creating the supplier deliver note');
  }




  header('Location: supplier_dn.php?id='.$supplier_delivery_note->id);
  exit;
  

}else{

  exit("error");
}
   

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
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'common.js.php',
		'table_common.js.php',
		);





$supplier_delivery_note_id = $supplier_delivery_note->id;
$_SESSION['state']['supplier_dn']['id']=$supplier_delivery_note->id;
$_SESSION['state']['supplier_dn']['supplier_key']=$supplier->id;
$_SESSION['state']['supplier']['id']=$supplier->id;
//print_r($supplier_delivery_note->data);
//print_r($supplier_delivery_note);
$smarty->assign('supplier_dn',$supplier_delivery_note);
$smarty->assign('supplier',$supplier);
$smarty->assign('title',_('Supplier Delivery Note').': '.$supplier_delivery_note->data['Supplier Delivery Note Public ID']);
$smarty->assign('view',$_SESSION['state']['supplier_dn']['view']);

$tipo_filter=$_SESSION['state']['supplier_dn']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier_dn']['products']['f_value']);
$filter_menu=array( 
		   'p.code'=>array('db_key'=>_('p.code'),'menu_label'=>'Our Product Code','label'=>'Code'),
		   'code'=>array('db_key'=>_('code'),'menu_label'=>'Supplier Product Code','label'=>'Supplier Code'),
		    );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu',$paginator_menu);


switch($supplier_delivery_note->data['Supplier Delivery Note Current State']){
case('Received'):



if($_SESSION['state']['supplier_dn']['show_all'])
  $smarty->assign('show_all',1);
else
  $smarty->assign('show_all',0);


//create user list
$sql=sprintf("select `Staff Key`id,`Staff Alias` as alias ,`Staff Position Key` as position_id from `Staff Dimension` where `Staff Currently Working`='Yes' order by alias ");
$res = mysql_query($sql);
$num_cols=5;
$staff=array();
while($row=mysql_fetch_array($res, MYSQL_ASSOC)){
  $staff[]=array('alias'=>$row['alias'],'id'=>$row['id'],'position_id'=>$row['position_id']);
}

//$staff= array_transverse($staff,$num_cols);
//print_r($staff);
foreach($staff as $key=>$_staff){
  $staff[$key]['mod']=fmod($key,$num_cols);
}
$smarty->assign('staff',$staff);
$smarty->assign('staff_cols',$num_cols);



$submit_method=array(
		     'Internet'=>array('fname'=>_('Internet'))
		     ,'Telephone'=>array('fname'=>_('Telephone'))
		     ,'Fax'=>array('fname'=>_('Fax'))
		     ,'In Person'=>array('fname'=>_('In Person'))
		     ,'Email'=>array('fname'=>_('Email'))
		     ,'Post'=>array('fname'=>_('Post'))
		     ,'Other'=>array('fname'=>_('Other'),'selected'=>true)
	
		     );
$smarty->assign('default_submit_method','Other');
$smarty->assign('submit_method',$submit_method);

$smarty->assign('user',$user->data['User Alias']);
$smarty->assign('user_staff_key',$user->data['User Parent Key']);





$js_files[]='supplier_dn_received.js.php';
$js_files[]='js/edit_common.js';
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('supplier_dn_received.tpl');
break;
case('Submitted'):

  
  $js_files[]='supplier_dn_submitted.js.php';
  $js_files[]='js/edit_common.js';
  $smarty->assign('css_files',$css_files);
  $smarty->assign('js_files',$js_files);
  $smarty->display('supplier_dn_submitted.tpl');


  break;
break;
case('Cancelled'):
  $js_files[]='supplier_dn_cancelled.js.php';
  $smarty->assign('css_files',$css_files);
  $smarty->assign('js_files',$js_files);
  $smarty->display('supplier_dn_cancelled.tpl');


  break;
}


?>