<?php
/*
 File: Deal.php

 This file contains the Deal Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('class.DB_Table.php');

class Deal extends DB_Table {




    function Deal($a1,$a2=false) {

        $this->table_name='Deal';
        $this->ignore_fields=array('Deal Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        } else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
           $this->find($a2,'create');

        } elseif(preg_match('/find/i',$a1))
            $this->find($a2,$a1);
        else
            $this->get_data($a1,$a2);

    }

    function get_data($tipo,$tag) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Deal Dimension` where `Deal Key`=%d",$tag);
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Deal Dimension` where `Deal Code`=%s",prepare_mysql($tag));
        // print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
            $this->calculate_deal=create_function('$transaction_data,$customer_id,$date', $this->get('Deal Metadata'));
            $this->id=$this->data['Deal Key'];
        }
    }

    function find($raw_data,$options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

        $this->candidate=array();
        $this->found=false;
        $this->found_key=0;
        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {

            if (array_key_exists($key,$data))
                $data[$key]=$value;

        }
        $fields=array();
        foreach($data as $key=>$value){
        if(!($key=='Deal Begin Date' or  !$key=='Deal Expiration Date'))
        $fields[]=$key;
        }
       
        $sql="select `Deal Key` from `Deal Dimension` where  true ";
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
       
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->get_data('id',$row['Deal Key']);
           
        }
        if($this->found){
            $this->get_data($this->found);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

       

        if ($data['Deal Allowance Type']=='Percentage Off' and preg_match('/Quantity Ordered/i',$data['Deal Terms Type'])) {
            //   print "***********";
            if (preg_match('/order \d+ or more/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]);
            else {
                print "ohh no a not founD in deal class ".$data['Deal Terms Description']."\n";
                print_r($data);
                exit;
            }

            if (preg_match('/^\d+\%/i',$data['Deal Allowance Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Deal Allowance Metadata']="$a,$b";
            //    print_r($match);
        }
        if ($data['Deal Allowance Type']=='Percentage Off' and preg_match('/Order Interval/i',$data['Deal Terms Type'])) {
            //   print "***********";
            if (preg_match('/last order within \d+ days/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' day';
            if (preg_match('/last order within \d+ .* month/i',$data['Deal Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' month';

            if (preg_match('/^\d+\%/i',$data['Deal Allowance Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Deal Allowance Metadata']="$a,$b";
            //    print_r($match);
        }
        elseif($data['Deal Allowance Type']=='Get Free' and preg_match('/Quantity Ordered^/i',$data['Deal Terms Type']) ) {

            $data['Deal Allowance Description']=preg_replace('/ one /',' 1 ',$data['Deal Allowance Description']);
            $data['Deal Allowance Description']=preg_replace('/ two /',' 2 ',$data['Deal Allowance Description']);
            $data['Deal Allowance Description']=preg_replace('/ three /',' 3 ',$data['Deal Allowance Description']);

            preg_match('/buy \d+/i',$data['Deal Allowance Description'],$match);
            $buy=_trim(preg_replace('/[^\d]/','',$match[0]));

            preg_match('/get \d+/i',$data['Deal Allowance Description'],$match);
            $get=_trim(preg_replace('/[^\d]/','',$match[0]));



            $data['Deal Allowance Metadata']=$buy.','.$get;
        }


        //print_r($data);

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Deal Dimension` %s %s",$keys,$values);
        // print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create deal  $sql\n";
            exit;

        }
    }

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {

        }

        return false;
    }

}