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

    

    if (is_numeric($a1) and !$a2) {
      $this->get_data('id',$a1);
    } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
      $this->msg=$this->create($a2);
    }
    elseif($a1=='find') {
      $this->find($a2,$a3);

    }
    else
      $this->get_data($a1,$a2,$a3);
  }

 function add_image($file,$args='') {
   $tmp_images_dir='app_files/pics/';


    $principal='No';
    if (preg_match('/principal/i',$args))
      $principal='Yes';
    if (!$this->images)
      $this->load('images');

    $checksum=md5_file($file);
    $same_as_other=false;
    



    foreach($this->images_original as $_key=>$_value) {
      if ($_value['Image File Checksum']==$checksum) {
	$same_as_other=true;
	$same_as=$_value['Image Filename'];
	return;
      }

    }

    if ($same_as_other) {
      //     $res[$key]['msg']=_('Image already uploaded')." (".$same_as.")";
      //$res[$key]['ok']=false;
      unlink($file);
      //     exit;
      //   continue;

    }

    $code=$this->get('Product Code');
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

     
      //print $tmp_images_dir.strtolower($this->data['Product Family Code']);
   
      if (!file_exists($tmp_images_dir.strtolower($this->data['Product Family Code'])))
	mkdir($tmp_images_dir.strtolower($this->data['Product Family Code']), 0700);
      $name=$tmp_images_dir.strtolower($this->data['Product Family Code']).'/'.strtolower('Original_'.$code.'_'.$this->id.'.'.$format);


      $news_imgfile = addslashes(fread(fopen($file, "r"), filesize($file)));


      $image_data=array(
			'Image Width' => imagesx($im),
			'Image Height' => imagesy($im),
			'Image File Size'=>$s=filesize($file),
			'Image File Checksum'=>$checksum,
			'Image Caption'=>$this->data['Product Name'],
			'Image Filename'=>$name,
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
	$image_key=mysql_insert_id();

	if ($principal=='Yes') {
	  $sql=sprintf("update `Image Bridge` set `Is Princial`='No' where   `Subject Type`='Product' and  `Subject Key`=%d",$this->pid);
	  mysql_query($sql);
	}

	if (count($this->images_original)==0)
	  $principal='Yes';

	$sql=sprintf("insert into `Image Bridge` values ('Product',%d,%d,%s)",$this->pid,$image_key,prepare_mysql($principal));
	//print $sql;
	mysql_query($sql);
	$url=sprintf('image.php?id=%d',$image_key);

	$sql=sprintf("update `Image Dimension` set `Image URL`=%s  where `Image Key`=%d",prepare_mysql($url),$image_key);
	//print $sql;
	mysql_query($sql);
      }


    }
    unlink($file);

  }



}