<?
include_once('common.php');
include_once('classes/Supplier.php');

if(!$LU->checkRight(SUP_VIEW))
  exit;


// include('_contact.php');
// include('telecom.php');
// include('email.php');
// include('address.php');
// include('_supplier.php');




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
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/supplier.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $supplier_id=1;
else
  $supplier_id=$_REQUEST['id'];


$_SESSION['state']['supplier']['id']=$supplier_id;
$smarty->assign('supplier_id',$supplier_id);

$supplier=new Supplier($supplier_id);
$supplier->load('contacts');


$smarty->assign('data',$supplier->data);

// $supplier_data= get_supplier_data($supplier_id);
// $contact_data= get_contact_data($supplier_data['contact_id']);
// $telecoms=get_telecoms($contact_data['id']);
// $num_children=count($contact_data['child']);
// if($num_children==1){
//   $smarty->assign('contact',$contact_data['child'][0]['name']);
//  }
// elseif($num_children==2){
//   $smarty->assign('contact',$contact_data['child'][1]['name'].' & '.$contact_data['child'][0]['name']);
//  }


//print_r($_SESSION['state']['supplier']);

$smarty->assign('display',$_SESSION['state']['supplier']['display']);
$smarty->assign('products_view',$_SESSION['state']['supplier']['products']['view']);
$smarty->assign('products_percentage',$_SESSION['state']['supplier']['products']['percentage']);
$smarty->assign('products_period',$_SESSION['state']['supplier']['products']['period']);


$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','suppliers.php');
$smarty->assign('title','Supplier: '.$supplier->get('Supplier Code'));

$smarty->assign('name',$supplier->get('Supplier Name'));

$smarty->assign('id',$myconf['supplier_id_prefix'].sprintf("%05d",$supplier->id));
//$smarty->assign('principal_address',display_full_address($contact_data['main_address']) );


$tipo_filter=$_SESSION['state']['supplier']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier']['products']['f_value']);

$filter_menu=array( 
		   'p.code'=>array('db_key'=>_('p.code'),'menu_label'=>'Our Product Code','label'=>'Code'),
		   'sup_code'=>array('db_key'=>_('sup_code'),'menu_label'=>'Supplier Product Code','label'=>'Supplier Code'),
		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$tipo_filter=$_SESSION['state']['supplier']['po']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier']['po']['f_value']);

$filter_menu=array( 
		   'id'=>array('db_key'=>_('p.code'),'menu_label'=>'Purchase order','label'=>'Id'),
		   'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
		   'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
		   'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
		   );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);



$smarty->display('supplier.tpl');
?>