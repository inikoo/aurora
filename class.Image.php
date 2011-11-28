<?php
/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/


/* class: image
   Class to manage the *Image Dimension* table
*/
// JFA


class Image  {

    var $id = false;
    var $im = "";
    var $resized_im = "";
    var $im_x = 0;
    var $im_y = 0;
    var $jpgCompression=90;
    var $msg='';
    var $new=false;
    var $deleted=false;
    var $found_key=0;
    var $delete_source_file=false;
    /*
       Constructor: Product
       Initializes the object.

       Parameters:
       a1 - Tag or Product Key
     */




    function Image($a1,$a2=false,$a3=false) {


        $this->tmp_path='app_files/pics/tmp/';
        $this->name='';
        $this->original_name='';
        $this->found=false;
        $this->error=false;
        $this->format='';
        $this->thumbnail_size=array(25,20);
        $this->small_size=array(320,280);
        $this->large_size=array(800,600);
        if (is_numeric($a1) and !$a2) {

            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_string($a2) ) {
            $this->find($a2,'create');
        }
        elseif($a1=='find') {
            $this->find($a2,$a3);

        }
        else
            $this->get_data($a1,$a2);
    }



    function get_data($tipo='id',$id) {
        $sql=sprintf("select `Image Key`,`Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` from `Image Dimension` where `Image Key`=%d ",$id);

        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Image Key'];
        $this->checksum=$this->data['Image File Checksum'];
    }

    function get_image_data() {
        $sql=sprintf("select `Image Data` from images.`Image Data Dimension` where `Image Key`=%d and  `Database Name`='dw'",$this->id);

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->im= imagecreatefromstring(base64_decode($row['Image Data']));
            $this->im_x     = imagesx($this->im);
            $this->im_y     = imagesy($this->im);
        }

    }




    function find($raw_data,$options) {
        if (!isset($raw_data['file'])) {
            $this->error=true;
            $this->msg='no file given';
            return;
        }


        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }




        $file=$raw_data['file'];

        if (preg_match('/\.\.\//',$file)) {
            $this->error=true;
            $this->msg=_('Invalid filename, return paths forbiden');
            return;
        }

        if (isset($raw_data['name'])) {
            $this->name=$raw_data['name'];

        }
        if (isset($raw_data['original_name'])) {
            $this->original_name=$raw_data['original_name'];

        }

        if (isset($raw_data['source_path'])) {
            $this->tmp_path=$raw_data['source_path'];

        }

        $this->mime_type='';
        if (isset($raw_data['type'])) {
            $this->mime_type=$raw_data['type'];
        }




        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }


        if (!is_file($this->tmp_path.$file)) {
            $this->error=true;
            $this->msg=_('No image file').' ('.$this->tmp_path.$file.')';
            return;
        }


        $this->checksum=md5_file($this->tmp_path.$file);




        $sql=sprintf("select `Image Key` from `Image Dimension` where `Image File Checksum`=%s"
                     ,prepare_mysql($this->checksum)

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Image Key'];
            $this->get_data('id',$this->found_key);
        }

        if (!$this->found and $create) {
            $this->create($file);

        }


    }




    function create($file,$args='') {

        $filename=$this->tmp_path.$file;
        $this->get_format($filename);
        $this->openImage($filename);
        if (!$this->im) {
            return;
        }



        //$news_imgfile = fread(fopen($filename, "r"), filesize($filename));
        $image_blob=$this->getImageData($this->im);

        $image_data=array(
                        'Image Width' => $this->im_x,
                        'Image Height' =>  $this->im_y,
                        'Image File Size'=>filesize($filename),
                        'Image File Checksum'=>$this->checksum,
                        'Image Filename'=>$this->name,
                        //    'Image URL'=>$name,
                        'Image Original Filename'=>$this->original_name,
                        'Image File Format'=>$this->format,
                        'Image Data'=>$image_blob
                    );






        //copy($filename, $name);



        $keys='(';
        $values='values(';
        foreach($image_data as $key=>$value) {
            $keys.="`$key`,";
            if ($key=='Image Data')
                $values.="'".addslashes($value)."',";
            elseif ($key=='Image Original Filename')
            $values.=prepare_mysql($value,false).",";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Image Dimension` %s %s",$keys,$values);
        // print "$sql\n";
        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->new=true;
            $this->get_data('id',$this->id);

            global $dns_db;

            $sql=sprintf("insert into kimage.`Image Data Dimension` values ('%s',%d,%s,%d,%s,'%s')
                         ON DUPLICATE KEY UPDATE `Image Name`=%s ,`Image Size`=%d,`Image Type`=%s,`Image Data`='%s'   "
                         ,addslashes($dns_db)
                         ,$this->id
                         ,prepare_mysql($this->name)
                         ,filesize($filename)
                         ,prepare_mysql($this->format)
                         ,$image_blob
                         ,prepare_mysql($this->name)
                         ,filesize($filename)
                         ,prepare_mysql($this->format)
                         ,$image_blob

                        );
            mysql_query($sql);
        } else {
            $this->error=true;
            $this->msg='Can not insert the image '.mysql_error();
            return;
        }



        if ($this->delete_source_file)
            unlink($filename);

        $this->create_thumbnail();
        $this->create_small();
        $this->create_large();


    }
// scale the image constraining proportions (maxX and maxY)

    function create_thumbnail() {



        $this->transformToFit($this->thumbnail_size[0],$this->thumbnail_size[1]);
        if ($this->error) {
            $this->msg=_('Can not resize image');
            return;
        }

        // if (!file_exists($this->path.'thumbnails'))
        //     mkdir($this->path.'thumbnails', 0700);


        // $name=$this->path.'thumbnails/'.$this->checksum.'.'.$this->format;
        //$this->saveImage($this->resized_im,$name);
        $image_blob=$this->getImageData($this->resized_im);
        $sql=sprintf("update `Image Dimension` set `Image Thumbnail Data`='%s' where `Image Key`=%d ",addslashes($image_blob),$this->id);

        mysql_query($sql);
        $this->data['Image Thumbnail Data']=$image_blob;
    }



    function get_format($filename) {



        $this->format=guess_file_format($filename);

    }


    /**
       *    @desc Open the image
       **/
    function openImage($srcImage) {


        if ($this->format=='jpeg') {
            $this->im = imagecreatefromjpeg($srcImage);
        }
        elseif($this->format=='png') {
            $this->im = imagecreatefrompng($srcImage);
            imagealphablending($this->im, true);
            imagesavealpha($this->im, true);
        }
        elseif($this->format=='gif') {
            $this->im = imagecreatefromgif($srcImage);
        }
        elseif($this->format=='wbmp') {
            $this->im = imagecreatefromwbmp($srcImage);
        }
        elseif($this->format=='psd') {
            include_once('class.PSD.php');
            $this->im = imagecreatefrompsd($srcImage);
        }
        else {
            $this->error=true;
            $this->msg=_('File format not supported')." ($this->format)";
            return;
        }

        if (!$this->im) {
            $this->error=true;
            $this->msg=_('Can not read image');;
            return;
        }


        $this->im_x     = imagesx($this->im);
        $this->im_y     = imagesy($this->im);
    }


    function saveImage($im,$destImage) {

        if ($this->format=='jpeg' or $this->format=='psd' ) {
            imagejpeg($im,$destImage ,$this->jpgCompression);

        }
        elseif($this->format=='png' or $this->format=='wbmp')
        imagepng($im,$destImage);
        elseif($this->format=='gif')
        imagegif($im,$destImage);
    }


    function getImageData($im) {
        ob_start();
        if ($this->format=='jpeg' or $this->format=='psd' ) {
            imagejpeg($im,NULL,$this->jpgCompression);

        }
        elseif($this->format=='png' or $this->format=='wbmp')
        imagepng($im);
        elseif($this->format=='gif')
        imagegif($im);

        $image_data = ob_get_contents();
        ob_end_clean();

        return $image_data;

    }



    function setCompression($val=70) {
        if ($val>0 && $val<10) {
            $val=10*$val;
        }
        elseif ($val>100) {
            $val=100;
        }
        elseif ($val<0) {
            $val=0;
        }
        $this->jpgCompression=$val;
    }
    function resizeImage($width,$height) {
        $dst_img    = imagecreatetruecolor($width,$height);
        imagecopyresampled($dst_img,$this->im,0,0,0,0,$width+1,$height+1,$this->im_x,$this->im_y);
        $this->resized_im = $dst_img;
    }




    // scale the image constraining proportions (maxX and maxY)

    function create_small() {

        if ($this->im_x<320 or $this->im_y<280) {
            $sql=sprintf("update `Image Dimension` set `Image Small Data`=NULL where `Image Key`=%d"

                         ,$this->id
                        );
            mysql_query($sql);
            return;
        }

        $this->transformToFit(320,280);
        if ($this->error) {
            $this->msg=_('Can not resize image');
            return;
        }



        $image_blob=$this->getImageData($this->resized_im);

        $sql=sprintf("update `Image Dimension` set `Image Small Data`='%s' where `Image Key`=%d"
                     ,addslashes($image_blob)
                     ,$this->id
                    );
        mysql_query($sql);
        $this->data['Image Small Data']=$image_blob;

    }




    function create_large() {


        if ($this->im_x<800 or $this->im_y<600) {
            $sql=sprintf("update `Image Dimension` set `Image Large Data`=NULL where `Image Key`=%d"

                         ,$this->id
                        );
            mysql_query($sql);
            return;
        }

        $this->transformToFit(800,600);
        if ($this->error) {
            $this->msg=_('Can not resize image');
            return;
        }




        $image_blob=$this->getImageData($this->resized_im);

        $sql=sprintf("update `Image Dimension` set `Image Large Data`='%s' where `Image Key`=%d"
                     ,addslashes($image_blob)
                     ,$this->id
                    );
        mysql_query($sql);
        $this->data['Image Large Data']=$image_blob;

    }


    // scale the image constraining proportions (maxX and maxY)
    function transformToFit($newX,$newY) {
        $x=$this->im_x;
        $y=$this->im_y;
        if ($x==0) {
            $this->error=true;
            return;
        }

        $mlt=$newX/$x;
        $nx=ceil($x * $mlt);
        $ny=ceil($y * $mlt);

        if ($ny>$newY) {
            $mlt=$newY/$ny;
            $nx=ceil($nx * $mlt);
            $ny=ceil($ny * $mlt);
        }

        $this->resizeImage($nx,$ny);
    }

// speaks for itself
    function strokeImage($strokeWidth, $strokeColor="000000") {
        $code = $this->colordecode($strokeColor);
        $width = imagesx($this->im);
        $height = imagesy($this->im);
        $color = ImageColorAllocate($this->im, $code[r], $code[g], $code[b]);
        if ($strokeWidth>1) {
            for ($i=0; $i<$strokeWidth; $i++) {
                ImageRectangle($this->im, $i, $i, $width-($i+1), $height-($i+1), $color);
            }
        } else {
            ImageRectangle($this->im, 0, 0, $width-1, $height-1, $color);
        }
    }


    function colordecode($hex) {
        $code[r] = hexdec(substr($hex, 0 ,2));
        $code[g] = hexdec(substr($hex, 2 ,2));
        $code[b] = hexdec(substr($hex, 4 ,2));
        return $code;
    }



    function load_subjects() {
        $sql=sprintf('select * from `Image Bridge` where `Image Key`=%d',$this->id);
        $res=mysql_query($sql);
        $this->subjects=array();
        while ($row=mysql_fetch_array($res)) {
            $this->subjects[]=array('Subject Type'=>$row['Subject Type'],'Subject Key'=>$row['Subject Key'],'Is Principal'=>$row['Is Principal']);
        }
    }


    function delete($force=false) {
        $this->load_subjects();
        $num_subjects=count($this->subjects);
        if ($num_subjects==0 or $force) {
            $sql=sprintf("delete from `Image Dimension` where `Image Key`=%d",$this->id);
            // print $sql;
            mysql_query($sql);
            $sql=sprintf("delete from `Image Bridge` where `Image Key`=%d",$this->id);
            mysql_query($sql);
            $this->deleted=true;
        }
    }


    function get_url() {
        return "image.php?id=".$this->id;
    }

}
