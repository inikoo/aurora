<?php
/*
 File: Country.php

 This file contains the Country Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

/*
 Constructor: Country
 Initializes the class, trigger  Search/Load for the data set

 If first argument is find it will try to match the data or create if not found

 Parameters:
 arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
 arg2 -    (optional) Data used to search or create the object

 Returns:
 void

 Example:
 (start example)
 $country_unknown=new Country('code','UNK');


 (end example)

*/


class Country {

    var $data=array();
    var $id=false;



    function __construct($arg1=false,$arg2=false) {



        if ($arg1=='id' and is_numeric($arg2)) {
            $this->get_data('id',$arg2);
            return;
        }
        elseif($arg1=='code') {
            $this->get_data('code',$arg2);
            return;
        }
        elseif($arg1=='find') {
            $this->get_data('find',$arg2);
            return;
        }
        elseif(preg_match('/^(minicode|2alpha|2 alpha code)$/i',$arg1)) {
            $this->get_data('2 alpha code',$arg2);
            return;
        }
        elseif($arg1=='name' and $arg2!='') {
            $name=$arg2;
            $this->get_data('name',$name);
            return;
        }
        elseif($arg1=='new' and is_array($arg2)) {
            $this->create('name',$name);
            return;
        }

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id',$arg1);
        }



    }


    function get_data($key,$id) {

        if ($key=='find') {
            $sql=sprintf("select `Country Key`  from kbase.`Country Dimension`where  `Country Name`=%s  "
                         ,prepare_mysql($id)

                        );
            //print $sql;
            $result=mysql_query($sql);

            if ($row=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->get_data('id',$row['Country Key']);
            else {


                $sql=sprintf("select `Country Alias Code`  from kbase.`Country Alias Dimension` where `Country Alias`=%s  "
                             ,prepare_mysql($id)

                            );
                //print $sql;
                $result_alias=mysql_query($sql);

                if ($row_alias=mysql_fetch_array($result_alias, MYSQL_ASSOC))
                    $this->get_data('code',$row_alias['Country Alias Code']);
                else
                    $this->get_data('code','UNK');
                mysql_free_result($result_alias);

            }
            mysql_free_result($result);
            return;
        }
        if ($key=='id') {
            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Key`=%d",$id);
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            mysql_free_result($result);
            return;
        }
        if ($key=='2 alpha code') {
            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country 2 Alpha Code`=%s",prepare_mysql($id));
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            mysql_free_result($result);
            return;
        }
        if ($key=='code') {
            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Code`=%s",prepare_mysql($id));
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            mysql_free_result($result);
            return;
        }

        if ($key=='name') {
            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Name`=%s",prepare_mysql($id));
            print $sql;
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            return;

            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Official Name`=%s",prepare_mysql($id));
             print $sql;
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            mysql_free_result($result);
            return;
            $sql=sprintf("SELECT * FROM kbase.`Country Dimension` C where `Country Native Name`=%s",prepare_mysql($id));
             print $sql;
            $result=mysql_query($sql);
            if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
                $this->id=$this->data['Country Key'];
            mysql_free_result($result);
            return;
        }


    }


    function get($key) {

        if (isset($this->data[$key]))
            return $this->data[$key];

if($key=='Population'){
return number($this->data['Country Population']);
}
if($key=='GNP'){
return money($this->data['Country GNP']);
}
        return false;

    }


function get_formated_exchange_reverse($currency_code,$date=false,$display=''){
switch($display){
case('tr'):

    return '<tr><td>'.money(1,$currency_code).'</td><td>=</td><td>'.money($this->exchange($currency_code,$date),$this->data['Country Currency Code'])."</td></tr>";

break;
default:
    return money(1,$currency_code).'='.money($this->exchange($currency_code,$date),$this->data['Country Currency Code']);
}

}
function get_formated_exchange($currency_code,$date=false,$display=''){
switch($display){
case('tr'):

    return '<tr><td>'.money(1,$this->data['Country Currency Code']).'</td><td>=</td><td>'.money(1/$this->exchange($currency_code,$date),$currency_code)."</td></tr>";

break;
default:

return money(1,$this->data['Country Currency Code']).'='.money(1/$this->exchange($currency_code,$date),$currency_code);
}
}
function exchange($currency_code,$date=false){
include_once('class.CurrencyExchange.php');

 $currency_exchange = new CurrencyExchange($currency_code.$this->data['Country Currency Code'],$date);
      $exchange= $currency_exchange->get_current_exchange();
      return $exchange;

}


}


?>