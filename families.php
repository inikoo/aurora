<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
include_once('assets_header_functions.php');

if(!$user->can_view('product families'))
  exit();

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('product families');

$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);

$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');



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
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'common.js.php',
		'table_common.js.php',
		'js/search.js',
		'families.js.php',
		 'js/dropdown.js',
		 'js/edit_common.js',
	         'js/csv_common.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$_SESSION['state']['assets']['page']='families';
if(isset($_REQUEST['view'])){
  $valid_views=array('sales','general','stoke');
  if (in_array($_REQUEST['view'], $valid_views)) 
    $_SESSION['state']['families']['view']=$_REQUEST['view'];

 }


$smarty->assign('view',$_SESSION['state']['families']['view']);
$smarty->assign('show_details',$_SESSION['state']['families']['details']);
$smarty->assign('show_percentages',$_SESSION['state']['families']['percentages']);


//$sql="select id from product";
//$result=mysql_query($sql);

// include_once('class.product.php');
// while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
//   $product= new product($row['id']);
//   $product->set_stock();
// }


 $table_title=_('Families List');
  $sql="select count(*) as numberof ,sum(`Product Family Total Invoiced Gross Amount`-`Product Family Total Invoiced Discount Amount`) as total_sales  from `Product Family Dimension`  ";
$result =mysql_query($sql);
if(!$families=mysql_fetch_array($result, MYSQL_ASSOC))
  exit("Internal Error DEPS");



// //$smarty->assign('table_info',$families['numberof'].' '.ngettext('Family','Families',$families['numberof']));
// $sql="select count(*) as numberof from product_group";
// $result=mysql_query($sql);
// $families=mysql_fetch_array($result, MYSQL_ASSOC);
// $sql="select count(*) as numberof from product";
// $result=mysql_query($sql);
// $products=mysql_fetch_array($result, MYSQL_ASSOC);





// $smarty->assign('stock_value',money($families['stock_value']));
$smarty->assign('total_sales',money($families['total_sales']));
$smarty->assign('families',number($families['numberof']));
// $smarty->assign('families',number($families['numberof']));
// $smarty->assign('products',number($products['numberof']));

$smarty->assign('parent','products');
$smarty->assign('title', _('Product Families'));
//$smarty->assign('total_families',$families['numberof']);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['families']['table']['f_field']:'code');
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',($q==''?$_SESSION['state']['families']['table']['f_value']:addslashes($q)));
$filter_menu=array(
		   'code'=>array('db_key'=>'code','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
		   'description'=>array('db_key'=>'description','menu_label'=>'Family Description with <i>x</i>','label'=>'Description'),
		   );
$smarty->assign('filter_menu0',$filter_menu);

$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

 $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['name']),
                                                             'stores'=>array('label'=>_('Stores'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['stores']),
                                                             
                                                             'products'=>array('label'=>_('Products'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['products']),
                                                   
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                            'stock'=>array(
                                        'title'=>_('Stock'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'surplus'=>array('label'=>_('Surplus'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['surplus']),
                                                       'ok'=>array('label'=>_('Ok'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['ok']),
                                                       'low'=>array('label'=>_('Low'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['low']),
                                                       'critical'=>array('label'=>_('Critical'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['critical']),
                                                       'gone'=>array('label'=>_('Gone'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['gone']),
                                                
                                                       'unknown'=>array('label'=>_('Unknown'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['unknown']),
                                                             array('label'=>''),
                                                       

                                                   )
                                               )
                                    ),
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',7);

                        
$smarty->assign('csv_export_options',$csv_export_options);

//{include file='export_csv_menu_splinter.tpl' id=0  export_options=$csv_export_options }



$smarty->display('families.tpl');

?>
