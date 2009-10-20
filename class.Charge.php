<?php
/*
 File: Charge.php

 This file contains the Charge Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/
include_once('class.DB_Table.php');

class Charge extends DB_Table {




    function Charge($a1,$a2=false) {

        $this->table_name='Charge';
        $this->ignore_fields=array('Charge Key');

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
            $sql=sprintf("select * from `Charge Dimension` where `Charge Key`=%d",$tag);
        //    elseif($tipo=='code')
        //  $sql=sprintf("select * from `Charge Dimension` where `Charge Code`=%s",prepare_mysql($tag));
        // print $sql;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
	  //$this->calculate_charge=create_function('$transaction_data,$customer_id,$date', $this->get('Charge Metadata'));
            $this->id=$this->data['Charge Key'];
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
        if(!($key=='Charge Begin Date' or  !$key=='Charge Expiration Date'))
        $fields[]=$key;
        }
       
        $sql="select `Charge Key` from `Charge Dimension` where  true ";
        //print_r($fields);
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
       
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);
            $this->found=true;
            $this->get_data('id',$row['Charge Key']);
           
        }
        if($this->found){
            $this->get_data($this->found);
        }
        
        if($create and !$this->found){
        $this->create($data);
        
        }


    }



    function create($data) {

       

        if ($data['Charge Type']=='Percentage' and preg_match('/Quantity Ordered/i',$data['Charge Terms Type'])) {
            //   print "***********";
            if (preg_match('/order \d+ or more/i',$data['Charge Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]);
            else {
                print "ohh no a not founD in charge class ".$data['Charge Terms Description']."\n";
                print_r($data);
                exit;
            }

            if (preg_match('/^\d+\%/i',$data['Charge Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Charge Metadata']="$a,$b";
            //    print_r($match);
        }
        if ($data['Charge Type']=='Percentage' and preg_match('/Order Interval/i',$data['Charge Terms Type'])) {
            //   print "***********";
            if (preg_match('/last order within \d+ days/i',$data['Charge Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' day';
            if (preg_match('/last order within \d+ .* month/i',$data['Charge Terms Description'],$match))
                $a=preg_replace('/[^\d]/','',$match[0]).' month';

            if (preg_match('/^\d+\%/i',$data['Charge Description'],$match))
                $b=.01*preg_replace('/\%/','',$match[0]);
            $data['Charge Metadata']="$a,$b";
            //    print_r($match);
        }
        elseif($data['Charge Type']=='Amount' and preg_match('/Total Amount|Order Items Gross Amount/i',$data['Charge Terms Type']))   {


            preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Terms Description'],$match);
            $total_order=_trim(preg_replace('/[^\d^\.]/','',$match[0]));

            preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Description'],$match);
            $amount=_trim(preg_replace('/[^\d^\.]/','',$match[0]));



            $data['Charge Metadata']=$total_order.','.$amount;
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
        $sql=sprintf("insert into `Charge Dimension` %s %s",$keys,$values);
        // print "$sql\n";
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
        } else {
            print "Error can not create charge  $sql\n";
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