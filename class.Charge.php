<?php
/*
 File: Charge.php

 This file contains the Charge Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

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

        }
        elseif(preg_match('/find/i',$a1))
        $this->find($a2,$a1);
        else
            $this->get_data($a1,$a2);

    }

    function get_data($tipo,$tag) {


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
        foreach($data as $key=>$value) {
            if (!($key=='Charge Begin Date'
                    or $key=='Charge Expiration Date'
                    or $key=='Charge Terms Metadata'
                    or $key=='Charge Metadata'   ))
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
            $this->found_key=$row['Charge Key'];

        }
        if ($this->found) {
            $this->get_data('id',$this->found_key);
        }

        if ($create and !$this->found) {
            $this->create($data);

        }


    }



    function create($data) {


        if ($data['Charge Trigger Key']=='')
            $data['Charge Trigger Key']=0;

        $data['Charge Metadata']=Charge::parse_charge_metadata($data['Charge Type'],$data['Charge Description']);
        $data['Charge Terms Metadata']=Deal::parse_term_metadata($data['Charge Terms Type'],$data['Charge Terms Description']);



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



    public static function parse_charge_metadata($charge_type,$charge_description) {
        $conditions=preg_split('/\s+AND\s+/',$charge_type);
        $metadata='';

        foreach($conditions as $condition) {
            $metadata.=','.Charge::parse_individual_charge_metadata($condition,$charge_description);
        }
        $metadata=preg_replace('/^\,/','',$metadata);
        // print "** $charge_type,$charge_description ->$metadata  \n";
        return $metadata;
    }


    public static function parse_individual_charge_metadata($charge_type,$charge_description) {
// print "$charge_type,$charge_description\n";
        switch ($charge_type) {
        case('Percentage'):
            if (preg_match('/\d+((\.|\,)\d+)?\%/i',$charge_description,$match)) {
                $number=preg_replace('/\,/','.',$match[0]);
                $number=preg_replace('/\%/','',$number);
                return 0.01* (float) $number;
            }
            if (preg_match('/^(|.*\s+)free(\s+.*|)$/i',$charge_description,$match)) {
                return 1;
            }
            break;
        case('Amount'):
            $charge_description=translate_written_number($charge_description);
            if (preg_match('/\d+(\.\d+)?/i',$charge_description,$match)) {
//            print "** $charge_description \n";


                //preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Terms Description'],$match);
                //$total_order=_trim(preg_replace('/[^\d^\.]/','',$match[0]));
                //preg_match('/\d+\.?[\d]{0,2}/i',$data['Charge Description'],$match);
                //$amount=_trim(preg_replace('/[^\d^\.]/','',$match[0]));

                return _trim(preg_replace('/[^\d^.]/','',$match[0]));
            }
            break;
        }
    }





}