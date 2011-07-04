<?php

include_once('common.php');
//print "xx";



//print_r($_FILES);
foreach ($_FILES as $fieldName => $file) {
$original_filename=strip_tags(basename($file['name']));
$filename="./app_files/uploads/". $original_filename;
move_uploaded_file($file['tmp_name'],$filename );

$fp      = fopen($filename, 'r');
$filesize=filesize($filename);
$content = fread($fp,$filesize );
$content = addslashes($content);
fclose($fp);

$caption='hola';
$url='xxx';
$mime_type='mime';

$query = sprintf("INSERT INTO `Attachment Dimension` (`Attachment Caption`,`Attachment URL`,`Attachment Filename`,`Attachment MIME Type`,`Attachment Compressed Data`,`Attachment File Checksum`,`Attachment File Size`,`Attachment File Original Name`) values (%s,%s,%s,%s,'%s',%s,%d,%s) ",
prepare_mysql($caption),
prepare_mysql($url),
prepare_mysql($filename),
prepare_mysql($mime_type),
$content,
prepare_mysql(md5_file($filename)),
$filesize,
prepare_mysql($original_filename)
);
mysql_query($query); 


}


// $za = new ZipArchive();
// $za->open('app_files/web_pages/1046/Archive.zip');
 // $za->extractTo( 'app_files/web_pages/1046/');
//print_r($za);
//exit;
/*
switch($_REQUEST['type']){
case("Family Page"):
foreach ($_FILES as $fieldName => $file) {
    $page_key=$_REQUEST['id'];
    if(!is_numeric($page_key))exit;
    
    mkdir("./app_files/web_pages/$page_key");

    if(preg_match('/\.zip$/i',basename($file['name']))){
         //   move_uploaded_file($file['tmp_name'], "./app_files/web_pages/".$page_key."/" . strip_tags(basename($file['name'])));

    $za = new ZipArchive();
    $za->open($file['tmp_name']);
    
     $za->extractTo("./app_files/web_pages/".$page_key."/" );
    $za->close(); 
    
    }else{
        move_uploaded_file($file['tmp_name'], "./app_files/web_pages/".$page_key."/" . strip_tags(basename($file['name'])));

    }
    
}

}
*/
?>
 
 