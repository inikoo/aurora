<?php


include_once('app_files/db/dns.php');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}



if(!isset($_REQUEST['id'])){
  $id=-1;
}else
  $id=$_REQUEST['id'];


if(isset($_REQUEST['size']) and preg_match('/^large|small|thumbnail|tiny$/',$_REQUEST['size']))
$size=$_REQUEST['size'];
else
$size='original';




$sql=sprintf("select * from `Image Dimension` where `Image Public`='Yes' and  `Image Key`=%d",$id);
$result = mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 header('Content-type: image/jpeg');
  header('Content-Disposition: inline; filename='.$row['Image Original Filename']);
  //readfile($row['Attachment Filename']);
 // echo  $row['Image Data'];  
  //echo  $row['Image Small Data'] ;

if($size=='original'){
    echo $row['Image Data'];
}elseif($size=='large'){
    if(!$row['Image Large Data'])
         echo $row['Image Data'];
    else
        echo $row['Image Large Data'];
}elseif($size=='small'){
    if(!$row['Image Small Data'])
         echo $row['Image Data'];
    else
        echo  $row['Image Small Data'] ;
  }elseif($size=='thumbnail' or $size=='tiny'){
  echo  $row['Image Thumbnail Data'];  
  
  }else{
   echo $row['Image Data'];
  
  }

  
}else{
  
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 'css/common.css',
		 'css/container.css',
		 'css/button.css',
		 'css/table.css',
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
		'js/common.js',
		
		'js/dropdown.js'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
   $smarty->display('forbidden.tpl');
   
}

?>
