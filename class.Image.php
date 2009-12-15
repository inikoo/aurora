<?php
/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Kaktus

  Version 2.0
*/


/* class: image
   Class to manage the *Image Dimension* table
*/
// JFA


class Image  {

 /*
    Constructor: Product
    Initializes the object.

    Parameters:
    a1 - Tag or Product Key
  */
  function Image($a1,$a2=false,$a3=false) {

    $this->path='app_files/pics/';
    $this->found=false;


    if (is_numeric($a1) and !$a2) {
      $this->get_data('id',$a1);
    } else if (($a1=='new' or $a1=='create') and is_string($a2) ) {
      $this->find($a2,'create');
    }
    elseif($a1=='find') {
      $this->find($a2,$a3);

    }
    else
      $this->get_data($a1);
  }



 function get_data($id){
   $sql=sprintf("select `Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image Type`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` from `Image Dimension` where `Image Key`=%d ",$id);
   print $sql;
   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Image  Key'];
    
 
 }


 function find($raw_data,$options){

    if(isset($raw_data['editor'])){
     foreach($raw_data['editor'] as $key=>$value){
       
       if(array_key_exists($key,$this->editor))
	 $this->editor[$key]=$value;
       
      }
   }


    $file=$raw_data['file'];
   
   

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
     $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }


    $checksum=md5_file($this->path.$file);



    $sql=sprintf("select `Image Key` from `Image Dimension` where `Image File Checksum`=%s"
		 ,prepare_mysql($checksum)
		 
		 );
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
      $this->found=true;
      $this->found_key=$row['Image Key'];
      $this->get_data($this->found_key);
    }


    if(!$this->found and$create){
      $this->create($file);

    }

    
  }




 function create($file,$args='') {
   $tmp_images_dir=$this->path;

   
   $target_path = $tmp_images_dir;
   //print filesize($file)."-----Z\n";
   
   ob_start();
   system("uname");
   $mimetype='Unknown';
   $system='Unknown';
   $_system = ob_get_clean();
    
   if(preg_match('/darwin/i',$_system)){
     ob_start();
      $system='Mac';
      system("file -I $file");
      $mimetype=ob_get_clean();
      
    }elseif(preg_match('/linux/i',$_system)){
      $system='Linux'; 
      $mimetype = system("file -ib $filename");
    }else{
      $system='Other';  
    }
   
   
    if(preg_match('/png/i',$mimetype))
        $format='png';
    elseif(preg_match('/jpeg/i',$mimetype))
        $format='jpeg';
    else{
        $format='other';
    }
   // print "$system $mimetype";
   // return;
    
    
    if($format=='jpeg')
        $im = @imagecreatefromjpeg($file);
    elseif($format=='png')    
         $im = @imagecreatefrompng($file);
    else{
    $this->error=true;
    $this->msg=_('File format not supported');
    }
         
    // print "-----------------";
    if ($im) {

     
      
      


      $news_imgfile = addslashes(fread(fopen($file, "r"), filesize($file)));


      $image_data=array(
			'Image Width' => imagesx($im),
			'Image Height' => imagesy($im),
			'Image File Size'=>$s=filesize($file),
			'Image File Checksum'=>$checksum,
			'Image Caption'=>$this->data['Product Name'],
			'Image Filename'=>$file,
			'Image URL'=>'',
			'Image File Format'=>$format,
			'Image Type'=>'Original'
                        );
      //print_r($image_data);

      if($format=='jpeg')
	imagejpeg($im,$name );
      elseif($format=='png')    
	imagepng($im,$name );

      
      $image_data['Image Data']=$news_imgfile;

      $keys='(';
      $values='values(';
      foreach($image_data as $key=>$value) {
	$keys.="`$key`,";
	if (preg_match('/url/i',$key))
	  $values.="'".addslashes($value)."',";
	else
	  $values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Image Dimension` %s %s",$keys,$values);
      
      if (mysql_query($sql)) {
	$this->id=mysql_insert_id();
	$url=sprintf('image.php?id=%d',$image_key);
	$sql=sprintf("update `Image Dimension` set `Image URL`=%s  where `Image Key`=%d",prepare_mysql($url),$this->id);
	mysql_query($sql);
	$this->get_data($this->id);
      }


    }
    unlink($file);

  }



}