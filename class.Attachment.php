<?php
/*
 File: Attachment.php

 This file contains the Attachment Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Warehouse.php');
include_once('class.Location.php');


class Attachment extends DB_Table {
    var $locations=false;
    var $compress=true;
    function Attachment($arg1=false,$arg2=false,$arg3=false) {

        $this->table_name='Attachment';
        $this->ignore_fields=array('Attachment Key');

        if (preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)) {
            $this->create($arg2);
            return;
        }

        if (preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg3);
            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);
    }

    /*
     Method: find
     Find Attachment with similar data
    */

    function find($raw_data,$options) {

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }


        $this->found=false;
        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }


        if (isset($raw_data['file']) and $raw_data['file']!='') {
            $file=$raw_data['file'];
            $checksum=md5_file($file);
            $mime=guess_file_mime($file);
            if ($mime=='unknown' and (isset($raw_data['Attachment MIME Type']) and $raw_data['Attachment MIME Type']!=''))
                $mime="unknown (".$raw_data['Attachment MIME Type'].")";
            $filesize=filesize($file);
            $extension= $this->find_extension($file);

            $raw_data['Attachment MIME Type']=$mime;
            $raw_data['Attachment File Checksum']=$checksum;
            $raw_data['Attachment File Size']=$filesize;

        }


        $data=$this->base_data();
        foreach($raw_data as $key=>$val) {
            $_key=$key;
            $data[$_key]=$val;
        }




        //look for areas with the same code in the same warehouse
        $sql=sprintf("select `Attachment Key` from `Attachment Dimension` where `Attachment File Checksum`=%s"
                     ,prepare_mysql($data['Attachment File Checksum'])
                    );

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Attachment Key'];
        }

        //what to do if found
        if ($this->found) {
            $this->get_data('id',$this->found_key);
        }


        if ($create) {
           // if ($this->found) {
            //    $this->update($raw_data,$options);
          //  } else {

                $this->create($data,$options);

        //    }


        }
    }


    function create ($data,$options='') {
        //    print_r($data);exit;
        $this->data=$this->base_data();
        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data))
                $this->data[$key]=_trim($value);
        }
        $tmp_compresed_file=$data['file'].'.gz';
        /*
           if($this->compress){
           $this->compress($data['file'],$tmp_compresed_file);
           $fp      = fopen($tmp_compresed_file, 'r');
           $this->data['Attachment Data'] = fread($fp, filesize($tmp_compresed_file));
           unlink($tmp_compresed_file);
           //$data['file']=$tmp_compresed_file;

           }
          */

        $filename= $data['file'];

        $this->data['Attachment Data'] = addslashes(fread(fopen($filename, "r"), filesize($filename)));
//print_r($this->data);

        $keys='(';
        $values='values(';
        foreach($this->data as $key=>$value) {

            $keys.="`$key`,";

            if ($key=='Attachment Data') {
                $values.="'".$value."',";
            } else {

                $_mode=true;
                if ($key=='Attachment Caption')
                    $_mode=false;
                $values.=prepare_mysql($value,$_mode).",";
            }



        }

        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);



        $sql=sprintf("insert into `Attachment Dimension` %s %s",$keys,$values);
        //print "$sql\n";
        // exit;
        if (mysql_query($sql)) {
            $this->id= mysql_insert_id();
            $this->new=true;
            $this->get_data('id',$this->id);
            $note=_('Attachment Created');
            $details=_('Attachment')." ".$this->data['Attachment File Original Name'];

          //  $attach_filename=sprintf("app_files/attachments/%016d.kfe",$this->id);
            $attach_url=sprintf("file.php?id=%d",$this->id);
//print "--->  ".$data['file'];


            //copy($data['file'], $attach_filename);


          //  unlink($data['file']);

            $sql=sprintf("update `Attachment Dimension` set `Attachment URL`=%s  where `Attachment Key`=%d"
                         ,prepare_mysql($attach_url)
                       //  ,prepare_mysql($attach_filename)
                         ,$this->id
                        );
            mysql_query($sql);
            $this->data['Attachment URL']=$attach_url;
           // $this->data['Attachment Filename']=$attach_filename;


        } else {
            $error=mysql_error();
            if (preg_match('/max_allowed_packet/i',$error)) {
                $this->msg="Got a packet bigger than 'max_allowed_packet' bytes ";
            } else {
                $this->msg='Unknown error';

            }
            $this->error=true;
        }
        
        }

        function get_data($key,$tag) {

            if ($key=='id')
                $sql=sprintf("select * from `Attachment Dimension` where `Attachment Key`=%d",$tag);
            else if ($key=='code')
                $sql=sprintf("select  *  from `Attachment Dimension` where `Attachment Code`=%s ",prepare_mysql($tag));
            else
                return;

            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->id=$this->data['Attachment Key'];
            }




        }



        function get_abstract() {
            $mime=$this->mime_type_icon($this->data['Attachment MIME Type']);
            return sprintf('%s <a href="%s">%s</a> (%s) %s'
                           ,$mime
                           ,$this->data['Attachment URL']
                           ,$this->data['Attachment File Original Name']

                           ,formatBytes($this->data['Attachment File Size'])
                           ,$this->data['Attachment Caption']
                          );
        }

        function get_details() {
            return '';
        }



        function load($key='') {
            switch ($key) {
            case('locations'):

                break;

            }


        }


        function get($key,$data=false) {
            switch ($key) {

            default:
                if (isset($this->data[$key]))
                    return $this->data[$key];
                else
                    return '';
            }
            return '';
        }





        function find_extension ($filename) {
            $filename = strtolower($filename) ;
            $exts = preg_split("/\.[a-z]$/i", $filename) ;
            $n = count($exts)-1;
            $exts = $exts[$n];
            return $exts;
        }


        function uncompress($srcName, $dstName) {
            $string = implode("", gzfile($srcName));
            $fp = fopen($dstName, "w");
            fwrite($fp, $string, strlen($string));
            fclose($fp);
        }

        function compress($srcName, $dstName) {
            $fp = fopen($srcName, "r");
            $data = fread ($fp, filesize($srcName));
            fclose($fp);

            $zp = gzopen($dstName, "w9");
            gzwrite($zp, $data);
            gzclose($zp);
        }


        function mime_type_icon($mime_type) {
            if (preg_match('/^image/',$mime_type)) {
                return '<img src="art/icons/image.png" alt="'.$mime_type.'"/>';
            }
            elseif(preg_match('/excel/',$mime_type)) {
                return '<img src="art/icons/page_excel.png" alt="'.$mime_type.'"/>';
            }
            elseif(preg_match('/msword/',$mime_type)) {
                return '<img src="art/icons/page_word.png" alt="'.$mime_type.'"/>';

            }
            else
                return $mime_type;
        }


        function delete(){
        
            $sql=sprintf("delete from `Attachment Dimension` where `Attachment Key`=%d",$this->id);
            mysql_query($sql);
            
            
            
            $sql=sprintf("delete from `Attachment Bridge` where `Attachment Key`=%d",$this->id);
            mysql_query($sql);
               
        
        }

    }

    ?>
