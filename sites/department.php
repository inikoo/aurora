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
  
  $department=new Department('code',$_REQUEST['code'],$store_key);
  if(!$department->id){
   
    header('Location: cataloge.php?wdc');
    exit;
  }

}else{
  header('Location: cataloge.php?ndc');
  exit;
}


$page=$site->get_page_object('department',$department->id);
if(!$page->id){
  header('Location: index.php?page_not_found');
  exit;
}

$smarty->assign('page',$page);
$page_data=$page->get_data_for_smarty($page_data);
$smarty->assign('page_data',$page_data);


$smarty->assign('contents',$page->data['Page Source Template']);
$smarty->assign('home_header_template',"../templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"../templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"../templates/left_menu.".$store->data['Store Locale'].".tpl");

$order_by='`Product Family Code`';

$sql=sprintf("select `Product Family Key`,`Product Family Code`,`Product Family Name`,`Product Family Main Image` from `Product Family Dimension` where `Product Family Main Department Key`=%d and `Product Family Sales Type`='Public Sale' order by %s ",$department->id,$order_by);

$res=mysql_query($sql);
$families=array();
while($row=mysql_fetch_array($res)){
  $families[]=array('key'=>$row['Product Family Key'],'code'=>$row['Product Family Code'],'name'=>$row['Product Family Name'],'image'=>$row['Product Family Main Image']);
}

$smarty->assign('families',$families);


$smarty->assign('js_files',$js_files);
$smarty->display("../templates/department.".$store->data['Store Locale'].".tpl");


update_page_key_visit_log($page->id);
$_SESSION['prev_page_key']=$page->id;


?>
