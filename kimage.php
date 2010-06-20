<?php
require_once 'common.php';

if(!isset($_REQUEST['id']))
  $id=1;
 else
   $id=$_REQUEST['id'];


$sql=sprintf("select *  from kimage.`Image Data Dimension` where `Database Name`=%s and `Image Key`=%d"
,prepare_mysql($dns_db)
,$id);

$result = mysql_query($sql);
//print $sql;
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $type=ctype($row['Image Type']);
  $name=$row['Image Name'];
   $size=$row['Image Size'];
   $content=$row['Image Data'];

  
header("Content-length: $size");
header("Content-type: $type");
//header("Content-Disposition: attachment; filename=$name");
echo $content;
exit();  
}  
  function ctype($file_extension){
  switch( $file_extension ) {
          case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      case "mp3": $ctype="audio/mpeg"; break;
      case "wav": $ctype="audio/x-wav"; break;
      case "mpeg":
      case "mpg":
      case "mpe": $ctype="video/mpeg"; break;
      case "mov": $ctype="video/quicktime"; break;
      case "avi": $ctype="video/x-msvideo"; break;

      //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
      case "php":
      case "htm":
      case "html":
      case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

      default: $ctype="application/force-download";
    }
  return $ctype;
  }

?>