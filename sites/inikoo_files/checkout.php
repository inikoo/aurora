<?php

include_once('common_splinter.php');
include_once('class.LightCustomer.php');
include_once('class.Customer.php');
include_once('class.Store.php');
include_once('class.Product.php');
//include_once('header.php');


$data=array('type'=>'parent', 'width'=>1000, 'customer_profile'=>1);

set_parameters($data);


include_once('top_navigation.php');
include_once('footer.php');
$smarty->assign('footer',$footer_);
$customer=new Customer($user->get('User Parent Key'));

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               'text_editor.css',
               'common.css',
               'button.css',
               'container.css',
               'table.css',
               'css/profile.css',
               'css/upload.css'
           );
//include_once('Theme.php');
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'editor/editor-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              $yui_path.'uploader/uploader-min.js',
              
              'external_libs/ampie/ampie/swfobject.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_common.js',
			  'checkout.js.php?order_key='.$customer->get_order_key()
          );
          
//print_r($user);

$smarty->assign('order_key',$customer->get_order_key());
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$items=array();
$sql=sprintf("select * from `Order Transaction Fact` where `Order Key`=%d and `Current Dispatching State`='In Process'", $customer->get_order_key() );
//$sql="select * from `Order Transaction Fact` where `Order Key`=222377";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$sql=sprintf("select * from `Product Dimension` where `Product ID`=%d", $row['Product ID']);
	$result1=mysql_query($sql);
	if($row1=mysql_fetch_array($result1, MYSQL_ASSOC)){
		$name=$row1['Product Code']." ".$row1['Product Name'];
		$price=$row1['Product Price'];
	}
	
	
	$items[]=array('product_name'=>$name
					,'total'=>$row['Order Transaction Gross Amount']
					,'price'=>$price
					,'qty'=>$row['Order Quantity']);
}

//print_r($items);

$smarty->assign('items',$items);



$smarty->display('checkout.tpl');

?>