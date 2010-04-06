<?php
include_once('common.php');
include_once('class.Department.php');

$css_files=array(
		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/dropdown.css'
		 );
$js_files=array('js/dropdown.js');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['code'])){
  
  $family=new Family('code',$_REQUEST['code'],$store_key);
  if(!$family->id){
   
    header('Location: cataloge.php?wfc');
    exit;
  }

}else{
  header('Location: cataloge.php?nfc');
  exit;
}

$page_data=$family->get_page_data();



$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Abstract']);
$smarty->assign('contents',$page_data['Page Source Template']);
$smarty->assign('home_header_template',"pages/$store_code/home_header.tpl");
$smarty->assign('right_menu_template',"pages/$store_code/right_menu.tpl");
$smarty->assign('left_menu_template',"pages/$store_code/left_menu.tpl");

$order_by='`Product Code';

$sql=sprintf("select `Product Price`,`Product Current Key`,`Product Code`,`Product Web State`,`Product Name`,`Product Units Per Case`,`Product Main Image` from `Product Dimension` where `Product Family Key`=%d and `Product Sales Type`='Public Sale' and `Product Web State` not in ('Offline')  order by %s ",$family->id,$order_by);
$res=mysql_query($sql);
$products=array();
while($row=mysql_fetch_array($res)){
  $products[]=array('key'=>$row['Product Current Key'],'code'=>$row['Product Code'],'name'=>$row['Product Units Per Case'].'x '.$row['Product Name'],'image'=>$row['Product Main Image'],'price'=>money($row['Product Price']));
}

$smarty->assign('products',$products);


$smarty->assign('js_files',$js_files);
$smarty->display('pages/'.$store_code.'/family.tpl');




?>