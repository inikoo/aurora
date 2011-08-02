<?php
/*
 File: Page Store Section.php

 This file contains the Page Store Section Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Page.php');

class PageStoreSection extends DB_Table {

    var $new=false;


    function PageStoreSection($arg1=false,$arg2=false,$arg3=false) {
        $this->table_name='Page Store Section';
        $this->ignore_fields=array('Page Store Section Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }



        if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
            $this->find($arg2,'create');
            return;
        }


        $this->get_data($arg1,$arg2,$arg3);

    }



    function get_data($tipo,$tag,$tag2) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Page Store Section Dimension` where  `Page Store Section Key`=%d",$tag);
        elseif($tipo=='code')
        $sql=sprintf("select * from `Page Store Section Dimension` where  `Page Store Section Code`=%s and `Site Key`=%d",
                     prepare_mysql($tag),
                     $tag2
                    );
        else {
            $this->error=true;
            $this->msg="invalid tag ($tipo)";
            return;
        }


        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Page Store Section Key'];


            if ($this->data['Page Store Section Logo Data']!='')
                $this->data['Page Store Section Logo Data']=unserialize($this->data['Page Store Section Logo Data']);
            if ($this->data['Page Store Section Header Data']!='')
                $this->data['Page Store Section Header Data']=unserialize($this->data['Page Store Section Header Data']);
            if ($this->data['Page Store Section Content Data']!='')
                $this->data['Page Store Section Content Data']=unserialize($this->data['Page Store Section Content Data']);
            if ($this->data['Page Store Section Footer Data']!='')
                $this->data['Page Store Section Footer Data']=unserialize($this->data['Page Store Section Footer Data']);
            if ($this->data['Page Store Section Layout Data']!='')
                $this->data['Page Store Section Layout Data']=unserialize($this->data['Page Store Section Layout Data']);

        } else {
            $this->error=true;
            $this->msg='page section not found';
        }



    }

    function find($raw_data,$options) {

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;
            }
        }

        $this->found=false;
        $this->found_key=false;

        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }





        $sql=sprintf("select `Page Store Section Key` from `Page Store Section Dimension` where `Page Store Section Code`=%s and `Site Key`=%d ",

                     prepare_mysql($raw_data['Page Store Section Code']),
                     $raw_data['Site Key']

                    );

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $this->found=true;
            $this->found_key=$row['Page Store Section Key'];
            $this->get_data('id',$this->found_key);
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }

    }


    function create($raw_data) {





        $data=$this->base_data();


        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))



                if (is_array($value))
                    $data[$key]=serialize($value);
                else
                    $data[$key]=_trim($value);


        }



        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";

            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Store Section Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);






        } else {
            $this->error=true;
            $this->msg='Can not insert Page Store Section Dimension';
            exit("$sql\n");
        }


    }








    function get($key) {



        switch ($key) {

        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
        }
        return false;
    }






    function update_data_field($field,$data) {
        
        foreach ($data as $key=>$items) {
            if($key=='style'){
            
            foreach($items as $style_key=>$style_value){
             if ($style_key=='background-image') {
                $file=$data['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->data['Image URL']);
                    
                    
                   
                    $this->data[$field]['style']['background-image']="url('$image_src')";
                }
            }else{
           $this->data[$field]['style'][$style_key]=$style_value;
                }
            }
            
            
            }else{
                $this->data[$field][$key]=$data;
            }
        }
        
        $sql=sprintf("update `Page Store Section Dimension` set `$field`=%s where `Page Store Section Key`=%d ",
        prepare_mysql(serialize($this->data[$field])),
        $this->id
        
        );
        mysql_query($sql);
        
    }



    function update_field_switcher($field,$value,$options='') {


        switch ($field) {
        case('Page Store Section Logo Data'):
        case('Page Store Section Header Data'):
        case('Page Store Section Footer Data'):
        case('Page Store Section Content Data'):
        case('Page Store Section Layout Data'):

            $this->update_data_field($field,$value);
            break;
        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {

                if ($value!=$this->data[$field]) {

                    $this->update_field($field,$value,$options);
                }
            }

        }



    }


    function base_data() {


        $data=array();
        $result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if (!in_array($row['Field'],$this->ignore_fields)) {
                    $data[$row['Field']]=$row['Default'];
                    if (preg_match('/ Data$/',$row['Field'])) {
                        $data[$row['Field']]='a:0:{}';
                    }

                }
            }
        }

        return $data;
    }


function get_data_for_smarty($data) {
//print_r($this->data);
    $header_style=$data['header_style'];
    if ($this->data['Page Store Section Header Data'] and array_key_exists('style',$this->data['Page Store Section Header Data']))
        foreach($this->data['Page Store Section Header Data']['style'] as $key=>$value) {
        $header_style.="$key:$value;";
    }
    $data['header_style']=$header_style;

    $footer_style=$data['footer_style'];
    if ($this->data['Page Store Section Footer Data'] and array_key_exists('style',$this->data['Page Store Section Footer Data']))
        foreach($this->data['Page Store Section Footer Data']['style'] as $key=>$value) {
        $footer_style.="$key:$value;";
    }
    $data['footer_style']=$footer_style;

    $content_style='';
    if ($this->data['Page Store Section Content Data'] ) {
//print "s";
        if (array_key_exists('style',$this->data['Page Store Section Content Data'])) {
            foreach($this->data['Page Store Section Content Data']['style'] as $key=>$value) {
           // print "$key";
                $content_style.="$key:$value;";
         //print "$key:$value; $content_style ";
         }
        }


    }
    $data['content_style']=$content_style;

    return $data;
}
  

}
?>
