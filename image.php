<?
require_once 'common.php';

$id=$_REQUEST['id'];


$sql=sprintf("select filename,format from image where id=%d",$id);
$res = mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $format=$row['format'];
  $filename=$row['filename'];
  $filename=$myconf['images_dir'].'original/'.$filename.'_orig.'.$format;
  
  if($format=='jpg'){
    $format='jpeg';
    header('Content-Type: image/'.$format);
    header('Content-Disposition: inline; filename='.$filename);
    $im = @imagecreatefromjpeg($filename);
    imagejpeg($im); 
  }
 }

?>