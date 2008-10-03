<?
require_once 'common.php';

$id=$_REQUEST['id'];


$sql=sprintf("select filename,format from image where id=%d",$id);
$res = $db->query($sql);
if($row=$res->fetchRow()) {
  $format=$row['format'];
  $filename=$row['filename'];
  $filename='images/original/'.$filename.'_orig.'.$format;
  
  if($format=='jpg'){
    $format='jpeg';
    header('Content-Type: image/'.$format);
    header('Content-Disposition: inline; filename='.$filename);
    $im = @imagecreatefromjpeg($filename);
    imagejpeg($im); 
  }
 }

?>