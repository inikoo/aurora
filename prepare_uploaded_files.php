<?php

$files_data=array();
//print_r($_FILES);
foreach ($_FILES as $file) {
    $original_filename=strip_tags(basename($file['name']));
    $original_extension='';
    if(preg_match('/\.[a-z0-9]+$/i',$original_filename,$match)){
        $original_extension='.'.strtolower(preg_replace('/\./','',$match[0]));
    }
    
    $checksum= md5_file($file['tmp_name']);
    $filename_with_path="./server_files/tmp/".$checksum.$original_extension;
    move_uploaded_file($file['tmp_name'],$filename_with_path );
    $files_data[]=array(
                    'original_filename'=>$original_filename,
                    'filename_with_path'=>$filename_with_path,
                    'type'=>$file['type']
                    
                );
}

  $result=array(
                    'state'=>200,
                    'files_data'=>$files_data
                    
                );

    echo json_encode($result);

?>

