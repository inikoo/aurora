<?php
// $za = new ZipArchive();
// $za->open('app_files/web_pages/1046/Archive.zip');
 // $za->extractTo( 'app_files/web_pages/1046/');
//print_r($za);
//exit;

//switch($_REQUEST['type']){
//case("Family Page"):
//foreach ($_FILES as $fieldName => $file) {
    //$page_key=$_REQUEST['id'];
    //if(!is_numeric($page_key))exit;
    
    //mkdir("./app_files/web_pages/$page_key");
/*
    if(preg_match('/\.zip$/i',basename($file['name']))){
         //   move_uploaded_file($file['tmp_name'], "./app_files/web_pages/".$page_key."/" . strip_tags(basename($file['name'])));

    $za = new ZipArchive();
    $za->open($file['tmp_name']);
    
     $za->extractTo("./app_files/web_pages/".$page_key."/" );
    $za->close(); 
    
    }else{
	*/
	/*.$page_key."/" . strip_tags(basename($file['name']))*/
	//$uploads_dir = '/app_files/web_pages/';
        //move_uploaded_file($file['tmp_name'], "app_files/web_pages/".$file['name']);
		move_uploaded_file($_FILES["file"]["tmp_name"],
      "app_files/web_pages/" . $_FILES["file"]["name"]);
		//print $file['tmp_name'];

    //}
    
//}

//}

?>
 
 