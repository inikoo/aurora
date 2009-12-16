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
    $this->tmp_path='app_files/pics/tmp/';
    $this->name='';
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
   // print $sql;
   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Image Key'];
    
 
 }


 function find($raw_data,$options){
   if(!isset($raw_data['file'])){
     $this->error=true;
     $this->msg='no file given';
     return;
   }
   

    if(isset($raw_data['editor'])){
     foreach($raw_data['editor'] as $key=>$value){
       
       if(array_key_exists($key,$this->editor))
	 $this->editor[$key]=$value;
       
      }
   }




    $file=$raw_data['file'];

    if(preg_match('/\.\.\//',$file)){
      $this->error=true;
      $this->msg=_('Invalid filename, return paths forbiden');
      return;
    }

    if(isset($raw_data['name'])){
      $this->name=$raw_data['name'];
    }
   


    $this->caption='';
    if(isset($raw_data['caption'])){
      $this->caption=$raw_data['caption'];
    }



    if(isset($raw_data['path'])){
      if(preg_match('/\.\.\//',$raw_data['path'])){
      $this->error=true;
      $this->msg=_('Invalid destination path, return paths forbiden');
      return;
    }


      $this->path.=$raw_data['path'];
    }

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
     $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }


    $this->checksum=md5_file($this->tmp_path.$file);


    

    $sql=sprintf("select `Image Key` from `Image Dimension` where `Image File Checksum`=%s"
		 ,prepare_mysql($this->checksum)
		 
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
   

   
  
   $filename=$this->tmp_path.$file;
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
      $im = @imagecreatefromjpeg($filename);
    elseif($format=='png')    
         $im = @imagecreatefrompng($filename);
    else{
      $this->error=true;
      $this->msg=_('File format not supported');
    }
         
    // print "-----------------";
    if ($im) {

     
      if (!file_exists($this->path))
	mkdir($this->path, 0700);
      
      $name=$this->path.$this->checksum.'.'.$format;
	  
      
      if($this->name=='')
	$this->name=$file;
      else
	$this->name.=$format;
	  
	  $news_imgfile = addslashes(fread(fopen($filename, "r"), filesize($filename)));
	  
	  
	  $image_data=array(
			    'Image Width' => imagesx($im),
			    'Image Height' => imagesy($im),
			    'Image File Size'=>filesize($filename),
			    'Image File Checksum'=>$this->checksum,
			    'Image Caption'=>$this->caption,
			    'Image Filename'=>$this->name,
			'Image URL'=>$name,
			'Image File Format'=>$format,
			'Image Type'=>'Original'
                        );


      if($format=='jpeg')
	imagejpeg($im,$name );
      elseif($format=='png')    
	imagepng($im,$name );

      
      $image_data['Image Data']=$news_imgfile;

      $keys='(';
      $values='values(';
      foreach($image_data as $key=>$value) {
	$keys.="`$key`,";
	if (preg_match('/caption/i',$key))
	  $values.="'".addslashes($value)."',";
	else
	  $values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Image Dimension` %s %s",$keys,$values);

      if (mysql_query($sql)) {
	$this->id=mysql_insert_id();

	$this->get_data($this->id);
      }else{
	$this->error=true;
      }


    }
    unlink($filename);

  }



}