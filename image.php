<?php
require_once 'common.php';

if(!isset($_REQUEST['id']))
  $id=1;
 else
   $id=$_REQUEST['id'];


$sql=sprintf("select `Image Filename` as filename,`Image File Format` as format from `Image Dimension` where `Image Key`=%d",$id);

$result = mysql_query($sql);
//print $sql;
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $format=$row['format'];
  $filename=$row['filename'];
  //  $filename=$myconf['images_dir'].'original/'.$filename.'_orig.'.$format;
  //   print "$filename $format" ;
  //print "caca";
  if($format=='jpg'){
    //print "caca";
    $format='jpeg';
    header('Content-Type: image/'.$format);
    header('Content-Disposition: inline; filename='.$filename);
   
    $im = @imagecreatefromjpeg($filename);
    //print $im;
    imagejpeg($im); 
  }
 }

?>