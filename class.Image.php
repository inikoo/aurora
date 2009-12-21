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


var $im = "";
var $resized_im = "";
    var $im_x = 0;
    var $im_y = 0;
    var $jpgCompression=90;
    var $msg='';
var $new=false;
var $deleted=false;

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
     $this->original_name='';
    $this->found=false;
$this->error=false;
$this->format='';

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
   $sql=sprintf("select `Image Key`,`Image Caption`,`Image URL`,`Image Thumbnail URL`,`Image Small URL`,`Image Large URL`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` from `Image Dimension` where `Image Key`=%d ",$id);
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
   if(isset($raw_data['original_name'])){
      $this->original_name=$raw_data['original_name'];
     
    }


    $this->caption='';
    if(isset($raw_data['caption'])){
      $this->caption=$raw_data['caption'];
    }
   $this->mime_type='';
    if(isset($raw_data['type'])){
      $this->mime_type=$raw_data['type'];
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

    if(!$this->found and $create){
      $this->create($file);

    }

    
  }




 function create($file,$args='') {
   
   $filename=$this->tmp_path.$file;
   $this->get_format($filename);
   $this->openImage($filename);
   if ($this->im) {

     
      if (!file_exists($this->path))
	mkdir($this->path, 0700);
      
      $name=$this->path.$this->checksum.'.'.$this->format;
	  
      
      if($this->name=='')
	$this->name=$file;
      else
	$this->name.='.'.$this->format;
	  
	  $news_imgfile = addslashes(fread(fopen($filename, "r"), filesize($filename)));
	  
	 
	  $image_data=array(
			    'Image Width' => $this->im_x,
			    'Image Height' =>  $this->im_y,
			    'Image File Size'=>filesize($filename),
			    'Image File Checksum'=>$this->checksum,
			    'Image Caption'=>$this->caption,
			    'Image Filename'=>$this->name,
			'Image URL'=>$name,
			'Image Original Filename'=>$this->original_name,
			'Image File Format'=>$this->format,
                        );


   


$this->saveImage($this->im,$name);

      
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
$this->new=true;
	$this->get_data($this->id);
      }else{
	$this->error=true;
      }


    }
    unlink($filename);

  }


function get_format($filename){

 $mimetype='Unknown';
 if($this->mime_type!='')
   $mimetype=$this->mime_type;
  
   
   ob_start();
   system("uname");
  
   
   
   $system='Unknown';
   $_system = ob_get_clean();
    
  // print "S:$system M:$mimetype\n";

   if(preg_match('/darwin/i',$_system)){
     ob_start();
      $system='Mac';
      system("file -I $filename");
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
     elseif(preg_match('/image.psd/i',$mimetype))
        $format='psd';
         elseif(preg_match('/gif$/i',$mimetype))
        $format='jpeg';
         elseif(preg_match('/wbmp$/i',$mimetype))
        $format='wbmp';
    else{
        $format='other';
    }
 //  print "S:$system M:$mimetype\n";
   // return;
    
    $this->format=$format;

}


 /**
    *    @desc Open the image
    **/
    function openImage($srcImage){
       
       
    if($this->format=='jpeg'){
      $this->im = imagecreatefromjpeg($srcImage);
    }elseif($this->format=='png'){    
         $this->im = imagecreatefrompng($srcImage);
    }elseif($this->format=='gif'){    
         $this->im = imagecreatefromgif($srcImage);
    }elseif($this->format=='wbmp'){    
         $this->im = imagecreatefromwbmp($srcImage);
    }elseif($this->format=='psd'){
    include_once('class.PSD.php');
         $this->im = imagecreatefrompsd($srcImage);
    }else{
      $this->error=true;
      $this->msg=_('File format not supported')." ($this->format)";
      return;
    }
       
     if(!$this->im){
     $this->error=true;
      $this->msg=_('Can not read image');;
      return;
     }
       
       
       $this->im_x     = imagesx($this->im);
        $this->im_y     = imagesy($this->im);
    } 


    function saveImage($im,$destImage){
    
       if($this->format=='jpeg' or $this->format=='psd' )
	imagejpeg($im,$destImage ,$this->jpgCompression);
      elseif($this->format=='png' or $this->format=='wbmp')    
	imagepng($im,$destImage);
    elseif($this->format=='gif')    
	imagegif($im,$destImage);
    
       
    } 

function setCompression($val=70){
        if ($val>0 && $val<10){
            $val=10*$val;
        }elseif ($val>100){
            $val=100;
        }elseif ($val<0){
            $val=0;
        }
        $this->jpgCompression=$val;
    } 
 function resizeImage($width,$height){
        $dst_img    = imagecreatetruecolor($width,$height);
        imagecopyresampled($dst_img,$this->im,0,0,0,0,$width+1,$height+1,$this->im_x,$this->im_y);
        $this->resized_im = $dst_img;
    } 

// scale the image constraining proportions (maxX and maxY)
 
    function create_thumbnail(){
      $this->transformToFit(25,20);
      if($this->error){
        $this->msg=_('Can not resize image');
        return;
        }
        
           $name=$this->path.'tb_'.$this->checksum.'.'.$this->format;

       $this->saveImage($this->resized_im,$name);
       $sql=sprintf("update `Image Dimension` set `Image Thumbnail URL`=%s where `Image Key`=%d"
            ,prepare_mysql($name)
            ,$this->id
        );
        mysql_query($sql);
        $this->data['Image Thumbnail URL']=$name;
      
    }
    
    
    // scale the image constraining proportions (maxX and maxY)
 
    function create_small(){
      $this->transformToFit(320,280);
      if($this->error){
        $this->msg=_('Can not resize image');
        return;
        }
        
           $name=$this->path.'sm_'.$this->checksum.'.'.$this->format;

       $this->saveImage($this->resized_im,$name);
       $sql=sprintf("update `Image Dimension` set `Image Small URL`=%s where `Image Key`=%d"
            ,prepare_mysql($name)
            ,$this->id
        );
        mysql_query($sql);
        $this->data['Image Small URL']=$name;
      
    }
    
 
    function create_large(){
      $this->transformToFit(800,600);
      if($this->error){
        $this->msg=_('Can not resize image');
        return;
        }
        
           $name=$this->path.'lg_'.$this->checksum.'.'.$this->format;

       $this->saveImage($this->resized_im,$name);
       $sql=sprintf("update `Image Dimension` set `Image Large URL`=%s where `Image Key`=%d"
            ,prepare_mysql($name)
            ,$this->id
        );
        mysql_query($sql);
        $this->data['Image Large URL']=$name;
      
    }
    
    
    // scale the image constraining proportions (maxX and maxY)
    function transformToFit($newX,$newY){
        $x=$this->im_x;
        $y=$this->im_y;
       if($x==0){
        $this->error=true;
        return;
       }
       
       $mlt=$newX/$x;
        $nx=ceil($x * $mlt);
        $ny=ceil($y * $mlt);
        
        if ($ny>$newY){
            $mlt=$newY/$ny;
            $nx=ceil($nx * $mlt);
            $ny=ceil($ny * $mlt);
        }
        
        $this->resizeImage($nx,$ny);
    } 
    
// speaks for itself
    function strokeImage($strokeWidth, $strokeColor="000000"){
        $code = $this->colordecode($strokeColor);
        $width = imagesx($this->im);
        $height = imagesy($this->im);
        $color = ImageColorAllocate($this->im, $code[r], $code[g], $code[b]);
        if ($strokeWidth>1){
            for ($i=0;$i<$strokeWidth;$i++){
                ImageRectangle($this->im, $i, $i, $width-($i+1), $height-($i+1), $color);
            }
        }else{
            ImageRectangle($this->im, 0, 0, $width-1, $height-1, $color);
        }
    } 


 function colordecode($hex){
        $code[r] = hexdec(substr($hex, 0 ,2));
        $code[g] = hexdec(substr($hex, 2 ,2));
        $code[b] = hexdec(substr($hex, 4 ,2));
        return $code;
    } 



function load_subjects(){
         $sql=sprintf('select * from `Image Bridge` where `Image Key`=%d',$this->id);
$res=mysql_query($sql);
    $this->subjects=array();
    while($row=mysql_fetch_array($res)){
    $this->subjects[]=array('Subject Type'=>$row['Subject Type'],'Subject Key'=>$row['Subject Key'],'Is Principal'=>$row['Is Principal']);
    }
    
    
}


function delete($force=false;){
$this->load_subjects();
    $num_subjects=count($this->subjects);
    
 if($num_subjects==0 or $force){
 unlink($this->data['Image URL']);
     if($this->data['Image Thumbnail URL']!='')   
     unlink($this->data['Image Thumbnail URL']);
      if($this->data['Image Small URL']!='')   
     unlink($this->data['Image Small URL']);
  if($this->data['Image Large URL']!='')   
     unlink($this->data['Image Large URL']);
     
   $sql=sprintf("delete from `Image Dimension` where `Image Key`=%d",$this->id);
   mysql_query($sql);
      $sql=sprintf("delete from `Image Bridge` where `Image Key`=%d",$this->id);
   mysql_query($sql);
    $this->deleted=true; 
 }
    
    
    
    

}




}