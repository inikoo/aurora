<?php
require_once 'common.php';
if(!isset($_REQUEST['id'])){
  $id=-1;
}else
  $id=$_REQUEST['id'];


if(isset($_REQUEST['size']) and preg_match('/^large|small|thumbnail|tiny$/',$_REQUEST['size']))
$size=$_REQUEST['size'];
else
$size='original';




$sql=sprintf("select * from `Image Dimension` where `Image Key`=%d",$id);
$result = mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

//print_r($row);
 
 header('Content-type: image/jpeg');
  header('Content-Disposition: inline; filename='.$row['Image Original Filename']);
  //readfile($row['Attachment Filename']);
// echo  $row['Image Data'];  
 // var_dump(  $row) ;

//exit;

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
  
header("HTTP/1.0 404 Not Found");
	exit();
   
}

?>
