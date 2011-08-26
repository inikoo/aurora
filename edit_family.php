<?php
/*
 File: family.php 

 UI family page

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/


include_once('common.php');
include_once('class.Family.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
  $family_id=$_SESSION['state']['family']['id'];
 else
   $family_id=$_REQUEST['id'];
$_SESSION['state']['family']['id']=$family_id;

$family=new Family($family_id);
$tmp_page_data=$family->get_page_data();
$page_data=array();
foreach($tmp_page_data as $key=>$value){
  $page_data[preg_replace('/\s/','',$key)]=$value;
  
}

$smarty->assign('page_data',$page_data);



//print_r($page_data);

$_SESSION['state']['department']['id']=$family->data['Product Family Main Department Key'];
$_SESSION['state']['store']['id']=$family->data['Product Family Store Key'];



if(!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores))){
 header('Location: index.php');
  exit();
  }

$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');

if(!$modify){
 header('Location: family.php?id='.$family_id);
  exit();
}


if(isset($_REQUEST['edit_tab'])){
  $edit=$_REQUEST['edit_tab'];
$_SESSION['state']['family']['editing']=$edit;
}else{
  $edit=$_SESSION['state']['family']['editing'];
}

$smarty->assign('edit',$edit);


$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);




$general_options_list=array();


  $general_options_list[]=array('tipo'=>'url','url'=>'family.php','label'=>_('Exit Edit'));


  $smarty->assign('general_options_list',$general_options_list);


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		  
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'uploader/uploader.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/edit_common.js',
                'js/csv_common.js',
		 'js/dropdown.js'
		);



 
  $css_files[]='css/edit.css';
  $css_files[]='css/upload_files.css';
  $js_files[]='js/edit_common.js';
  $js_files[]='country_select.js.php';
   $js_files[]='js/upload_image.js';
 
    // $js_files[]='upload_files.js.php';

  $js_files[]='edit_family.js.php';
  $smarty->assign('yui_path',$yui_path);

  
  
$family->load_images_slidesshow();
$images=$family->images_slideshow;
$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






$_SESSION['state']['assets']['page']='department';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['product']['view']=$_REQUEST['view'];

 }

$department_order=$_SESSION['state']['department']['families']['order'];
$department_period=$_SESSION['state']['department']['period'];
$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
  

$smarty->assign('department_period',$department_period);
$smarty->assign('department_period_title',$department_period_title[$department_period]);




if(isset($_REQUEST['department_id']) and $_REQUEST['department_id']>0){
  $department_id=$_REQUEST['department_id'];
  $order=$_SESSION['state']['department']['families']['order'];
  if($order=='per_tsall' or $order=='tsall')
    $order='total_sales';
  if($order=='per_tsm' or $order=='tms')
    $order='month_sales';
  if($order=='code')
    $order='Product Family Code';
  if($order=='name')
    $order='Product Family Name';
  if($order=='active')
    $order='Product Family For Sale Products';
  if($order=='outofstock')
    $order='Product Family Out Of Stock Products';
  if($order=='stockerror')
    $order='Product Family Unknown Stock Products';
  




$sql=sprintf("select  F.`Product Family Key` as id, `Product Family Code` as code  from `Product Family Dimension`   F left join `Product Family Department Bridge` FD on (FD.`Product Family Key`=F.`Product Family Key`) where  `%s`<'%s' and `Product Department Key`=%d  order by `%s` desc  ",$order,$family->get($order),$department_id,$order);


$res = mysql_query($sql);
if(!$prev=mysql_fetch_array($res, MYSQL_ASSOC))
  $prev=array('id'=>0,'code'=>'');

$sql=sprintf("select F.`Product Family Key` as id, `Product Family Code` as code   from `Product Family Dimension`   F left join `Product Family Department Bridge`  FD on (FD.`Product Family Key`=F.`Product Family Key`)  where  `%s`>'%s' and `Product Department Key`=%d order by `%s`   ",$order,$family->get($order),$department_id,$order);

$res = mysql_query($sql);

if(!$next=mysql_fetch_array($res, MYSQL_ASSOC))
  $next=array('id'=>0,'code'=>'');



 


$smarty->assign('prev',$prev);
$smarty->assign('next',$next);

 }







$smarty->assign('parent','products');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);
// $smarty->assign('department',$family->get('department'));
// $smarty->assign('department_id',$family->data['department_id']);
// $smarty->assign('products',$family->get('product_numbers'));
// $smarty->assign('data',$family->data);




 $smarty->assign('family',$family);
 $smarty->assign('store',$store);
 $smarty->assign('department',$department);





$q='';
  $tipo_filter=($q==''?$_SESSION['state']['family']['products']['f_field']:'code');
  $smarty->assign('filter_name0',$tipo_filter);
  $smarty->assign('filter_value0',($q==''?$_SESSION['state']['family']['products']['f_value']:addslashes($q)));
  $filter_menu=array(
		    

		     );
  $smarty->assign('filter_menu0',$filter_menu);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$info_period_menu=array(
			array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
		     ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
		     ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
		     ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
		     ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
		     );
$smarty->assign('info_period_menu',$info_period_menu);


//print show_currency_conversion('USD','GBP');



$units_tipo=array(
		  'Piece'=>array('fname'=>_('Piece'),'name'=>'Piece','selected'=>false),
		  'Grams'=>array('fname'=>_('Grams'),'name'=>'Grams','selected'=>false),
		  'Liters'=>array('fname'=>_('Liters'),'name'=>'Liters','selected'=>false),
		  'Meters'=>array('fname'=>_('Meters'),'name'=>'Meters','selected'=>false),
		  'Other'=>array('fname'=>_('Other'),'name'=>'Other','selected'=>false),
);
 $units_tipo['Piece']['selected']=true;

$smarty->assign('units_tipo',$units_tipo);
  $smarty->assign('title', _('Editing Family').': '.$family->get('Product Family Code'));


$smarty->assign('view',$_SESSION['state']['family']['products']['edit_view']);


  $smarty->display('edit_family.tpl');

?>
