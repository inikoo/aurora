<?php

switch($_REQUEST['type']){
case("Family Page"):
foreach ($_FILES as $fieldName => $file) {
    $page_key=$_REQUEST['id'];
    if(!is_numeric($page_key))exit;
    
    mkdir("./app_files/web_pages/$page_key");

    move_uploaded_file($file['tmp_name'], "./app_files/web_pages/".$page_key."/" . strip_tags(basename($file['name'])));
    echo (" ");
}

}

?>
 
 