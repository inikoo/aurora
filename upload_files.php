<?php
//print "xx";



print_r($_FILES);
foreach ($_FILES as $fieldName => $file) {

$filename="./app_files/uploads/". strip_tags(basename($file['name']));
move_uploaded_file($file['tmp_name'],$filename );

$fp      = fopen($filename, 'r');
$content = fread($fp, filesize($tmpName));
$content = addslashes($content);
fclose($fp);

//$query = sprintf("INSERT INTO `Attachment Dimension` ....";
//mysql_query($query) 


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
 
 