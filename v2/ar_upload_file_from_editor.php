<?php
include_once('common.php');
include_once('class.Image.php');


if (isset($_FILES['image']['tmp_name'])) {

   

    $image_data=array(
                    'file'=>$_FILES['image']['tmp_name'],
                    'source_path'=>'',
                    'name'=>$_FILES['image']['name'],
                    'caption'=>''
                );

    $image=new Image('find',$image_data,'create');
   
    if ($image->id) {
        echo "{status:'UPLOADED', image_url:'image.php?id=".$image->id."'}";
    } else {
        echo "{status:'Error:  ".addslashes($image->msg)."'}";
    }



} else {
    echo "{status:'No file was submitted'}";
}
?>