<?php
include_once('common.php');
include_once('class.Supplier.php');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'calendar/assets/skins/sam/calendar.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 //$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

		
		 'button.css',
		 'css/container.css'
		 );

$css_files[]='theme.css.php';


$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'connection/connection-debug.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'animation/animation-min.js',

		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'js/table_common.js',
	        'supplier_products.js.php',
                'js/edit_common.js',
                'js/csv_common.js'
		);




$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$sql="select count(*) as total_products  from `Supplier Product Dimension` ";
$result =mysql_query($sql);
if(!$products=mysql_fetch_array($result, MYSQL_ASSOC))
  exit("Internal Error DEPS");



$smarty->assign('products',$products['total_products']);

$smarty->assign('view',$_SESSION['state']['supplier_products']['view']);
$smarty->assign('percentage',$_SESSION['state']['supplier_products']['percentage']);
$smarty->assign('period',$_SESSION['state']['supplier_products']['period']);


$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','suppliers');
$smarty->assign('title','Supplier Products List');


$tipo_filter=$_SESSION['state']['supplier_products']['table']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['state']['supplier_products']['table']['f_value']);

$filter_menu=array( 
		   'code'=>array('db_key'=>'code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
		   'sup_code'=>array('db_key'=>'sup_code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
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
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['code']),
                                                             'supplier'=>array('label'=>_('Supplier'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['supplier']),
                                                             'product_name'=>array('label'=>_('Product Name'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['product_name']),
                                                             'product_description'=>array('label'=>_('Product Description'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['product_description']),                                                    
                                                         )
                                                     )
                                          ),
                            'other_details'=>array(
                                        'title'=>_('Other Details'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'unit_type'=>array('label'=>_('Product Unit Type'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['unit_type']),
                                                       'currency'=>array('label'=>_('Currency'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['currency']),
                                                       'valid_from'=>array('label'=>_('Product Valid From'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['valid_from']),
                                                       'valid_to'=>array('label'=>_('Product Valid To'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['valid_to']),
                                                       'buy_state'=>array('label'=>_('Buy State'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['buy_state']),
                                                
                                                   )
                                               )
                                    ),
                            'cost_all'=>array('title'=>_('Cost (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_all'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['cost_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'cost_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1y'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['cost_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'cost_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1q'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['cost_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'cost_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1m'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['cost_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'cost_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'cost_1w'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['cost_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['products']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',7);
$smarty->assign('csv_export_options',$csv_export_options);


$smarty->display('supplier_products.tpl');
?>
