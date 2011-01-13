<?php
include_once('class.Node.php');

include_once('common.php');
include_once('assets_header_functions.php');



if(!$user->can_view('stores')  ){
  header('Location: index.php');
   exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('staff');
get_header_info($user,$smarty);
$general_options_list=array();
if($modify){
//  $general_options_list[]=array('tipo'=>'url','url'=>'edit_product_categories.php?edit=1','label'=>_('Edit Categories'));
//$general_options_list[]=array('tipo'=>'js','state'=>$show_details,'id'=>'details','label'=>($show_details?_('Hide Details'):_('Show Details')));
  
  $general_options_list[]=array('tipo'=>'url','url'=>'new_category.php','label'=>_('Add Category'));
          }
$smarty->assign('general_options_list',$general_options_list);

$smarty->assign('view',$_SESSION['state']['product_categories']['view']);




$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'search.js','js/edit_common.js',
		'edit_product_categories.js.php',
		'js/dropdown.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


if(isset($_REQUEST['id'])){
$category_key=$_REQUEST['id'];
}else{
$category_key=$_SESSION['state']['product_categories']['category_key'];
}

$nodes=new Nodes('`Category Dimension`');

if($cat_data=$nodes->fetch($category_key)){


}else{
$category_key=0;
$main_title=_('Categories');
$subcategories_title=_('Category List');

}



$main_title=_('Categories');
$subcategories_title=_('Category List');

$_SESSION['state']['product_categories']['category_key']=$category_key;
$smarty->assign('main_title',$main_title);
$smarty->assign('subcategories_title',$subcategories_title);

/*

$sql="select `Category Name`,`Category Key` from `Category Dimension` where `Category Deep`=1 and `Category Subject`='Product' ";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
  if(!$category)
    $category=$row['Category Key'];
  $main_category_list[]=array('name'=>$row['Category Name'],'id'=>$row['Category Key'],'selected'=>( $category==$row['Category Key']?1:0));
}
//print_r($main_category_list);
mysql_free_result($res);
$smarty->assign('main_category_list',$main_category_list);
$_SESSION['state']['product_categories']['category']=$category;

global $myconf;

//first check





$sql=sprintf("select count(distinct `Store Currency Code` ) as distint_currencies, sum(IF(`Store Currency Code`=%s,1,0)) as default_currency    from `Store Dimension` where `Store Key` in (%s) "
	     ,prepare_mysql($myconf['currency_code']),join(',',$user->stores) );

$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
  $distinct_currencies=$row['distint_currencies'];
  $default_currency=$row['default_currency'];
}
$num_stores=count($user->stores);


if($num_stores==1){



}else{

  $display_stores=$_SESSION['state']['product_categories']['stores'];

 
  if(is_numeric($display_stores) and in_array($display_stores,$user->stores)){
    //todo: take this data from $user->store
    $sql=sprintf("select `Store Code` from `Store Dimesion` where `Store Key`=%d");
    $res=mysql_query($sql);
    if($row=mysql_query($res)){
      $display_stores_label==$row['Store Code'].' '._('Store Only');
    }else{
      $display_stores_label=_('All Stores');
    }
    
  }else{
    $display_stores_label=_('All Stores');
  }
    


  $smarty->assign('display_stores',$display_stores);
  $smarty->assign('display_stores_label',$display_stores_label);

  $display_stores_mode=$_SESSION['state']['product_categories']['stores_mode'];
  $display_stores_mode_label=array('grouped'=>_('Stores Grouped'),'ungrouped'=>'Showing each Store data');
  $smarty->assign('display_stores_mode',$display_stores_mode);
  $smarty->assign('display_stores_mode_label',$display_stores_mode_label[$display_stores_mode]);
  
  if($distinct_currencies==1){
  

    
  }else{
    if($distinct_currencies>1){
      $mode_options[]=array('mode'=>'value_default_d2d','label'=>_("Values in")." ".$myconf['currency_code']." ("._('d2d').")");
      
      if($_SESSION['state']['stores']['table']['show_default_currency']){
	$display_mode='value_default_d2d';
	$display_mode_label=_("Values in")." ".$myconf['currency_code']." ("._('d2d').")";
      }
    }
  
  }
}


$display_mode='value';
$display_mode_label=_('Values');
if($_SESSION['state']['product_categories']['percentages']){
  $display_mode='percentages';
  $display_mode_label=_('Percentages');
}




$smarty->assign('display_mode',$display_mode);
$smarty->assign('display_mode_label',$display_mode_label);
*/

$smarty->display('categories.tpl');

?>
