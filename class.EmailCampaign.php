<?php
/*
 File: EmailCampaign.php

 This file contains the Email Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Kaktus

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Page.php');
include_once('class.PageStoreSection.php');

class EmailCampaign extends DB_Table {

    var $new=false;

    function EmailCampaign($arg1=false,$arg2=false) {
        $this->table_name='Email Campaign';
        $this->ignore_fields=array('Email Campaign Key','Email Campaign Maximum Emails');


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


        $this->get_data($arg1,$arg2);

    }


    function get_data($tipo,$tag) {


        $sql=sprintf("select * from `Email Campaign Dimension` where  `Email Campaign Key`=%d",$tag);

        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Email Campaign Key'];
       

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
        $sql=sprintf("insert into `Email Campaign Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);

           




        } else {
            $this->error=true;
            $this->msg='Can not insert Email Campaign Dimension';
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










    function update_field_switcher($field,$value,$options='') {


        switch ($field) {

        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {

                if ($value!=$this->data[$field]) {

                    $this->update_field($field,$value,$options);
                }
            }

        }



    }



}
?>
