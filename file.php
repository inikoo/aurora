<?php
require_once 'common.php';
if(!isset($_REQUEST['id'])){
  $id=-1;
}else
  $id=$_REQUEST['id'];

$sql=sprintf("select * from `Attachment Dimension` where `Attachment Key`=%d",$id);
$result = mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  header('Content-Type: '.$row['Attachment MIME Type']);
  header('Content-Disposition: inline; filename='.$row['Attachment File Original Name']);
  readfile($row['Attachment Filename']);
}else{
  
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
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
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		'js/php.default.min.js',
		'common.js.php',
		
		'js/dropdown.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
   $smarty->display('forbidden.tpl');
   
}

?>