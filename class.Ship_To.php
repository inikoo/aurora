<?php
/*
 File: Ship_To.php

 This file contains the Ship To Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/

/* class: Ship_To
   Class to manage the *Company Dimension* table
*/
class Ship_To extends DB_Table {


    /*
         Constructor: Ship_To

         Initializes the class, Search/Load or Create for the data set




       */
    function Ship_To($arg1=false,$arg2=false) {

        $this->table_name='Ship To';
        $this->ignore_fields=array('Ship To Key');

        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return ;
        }
        if (preg_match('/^(create|new)/i',$arg1)) {
            $this->find($arg2,'create');
            return;
        }
        if (preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);
        return ;

    }


// function get_unknown(){
//   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
//   $result=mysql_query($sql);
//   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
//     $this->id=$this->data['Store Key'];
// }

    /*
        Function: get_data
        Obtiene los datos de la tabla Ship To Dimension de acuerdo al Id
    */
// JFA

    function get_data($tipo,$tag) {

        if ($tipo=='id')
            $sql=sprintf("select * from `Ship To Dimension` where `Ship To Key`=%d",$tag);
        else
            return;

        // print $sql;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Ship To Key'];


    }

    /*
       Method: find
       Find Company with similar data

       Returns:
     Key of the Shipping Addreses found, if create is found in the options string  returns the new key
      */
    function find($raw_data,$options) {

        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $data=$this->base_data();



        $_address=new Address();
        $address_data=$_address->prepare_3line($raw_data);



        foreach( $this->prepare_data($address_data) as $key=> $value) {
            $data[$key]=$value;

        }




        // Look for duplicates
        $fields=array('Ship To Country Code','Ship To Postal Code','Ship To Town','Ship To Line 1','Ship To Line 2','Ship To Line 3','Ship To Line 4');

        $sql=sprintf("select * from `Ship To Dimension` where true  ");
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }


        //    print "FUZZY $sql\n";
        $result=mysql_query($sql);
        $num_results=mysql_num_rows($result);
        if ($num_results==0) {
            // address not found
            $this->found=false;


        } else if ($num_results==1) {
            $row=mysql_fetch_array($result, MYSQL_ASSOC);

            $this->get_data('id',$row['Ship To Key']);
            $this->found=true;
            $this->found_key=$row['Ship To Key'];

        } else {// Found in mora than one
            print("Warning to shipping addresses $sql\n");
	    $row=mysql_fetch_array($result, MYSQL_ASSOC);

            $this->get_data('id',$row['Ship To Key']);
            $this->found=true;
            $this->found_key=$row['Ship To Key'];
	    

        }

        if (!$this->found and $create) {
            $this->create($data);

        }


    }




    /*
        Function: get
        Obtiene datos del producto de acuerdo al codigo de producto, al tipo de producto o la totalidad de productos (esto en base al criterio de seleccion)
    */
// JFA

    function get($key='') {

        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {




        }
        $_key=ucfirst($key);
        if (isset($this->data[$_key]))
            return $this->data[$_key];
        print "Error $key not found in get from Ship TO\n";
        return false;

    }

    /*
        Function: delete
        Elimina registros de la tabla Ship To Dimension en base al valor del campo store key, siempre y cuando no haya utilizado anteriormente
    */
// JFA

    function delete() {
        $this->deleted=false;
        //TODO (actualize Ship To Times Used  )

        if ($this->data['Ship To Times Used']==0) {
            $sql=sprintf("delete from `Ship To Dimension` where `Ship To Key`=%d",$this->id);
            if (mysql_query($sql)) {

                $this->deleted=true;

            } else {

                $this->msg=_('Error: can not delete shipping address');
                return;
            }

            $this->deleted=true;
        } else {
            $this->msg=_('Shipping address can not be deleted because it has some products');

        }
    }


    /*
        Method: load
        Obtiene registros de las tablas Product Dimension, Product Family Dimension, Product Department Dimension, y actualiza datos de Store Dimension, de acuerdo a la categoria indicada.
    */
// JFA


    function load($tipo,$args=false) {
        switch ($tipo) {





        }

    }



    function update_from_address($address_key) {
        $address=new Address($address_key);
        if ($address->id) {
            $lines=$address->display('3lines');
            $sql=sprintf("update `Ship To Dimension` set  `Ship To Line 1`=%s,`Ship To Line 2`=%s ,`Ship To Line 3`=%s , `Ship To Town`=%s,`Ship To Line 4`=%s  ,`Ship To Postal Code`=%s,`Ship To Country`=%s ,`Ship To XHTML Address`=%s  ,`Ship To Country Key`=%d,`Ship To Country Code`=%s,`Ship To Country 2 Alpha Code`=%s   where `Ship To Key`=%d"
                         ,prepare_mysql($lines[1],false)
                         ,prepare_mysql($lines[2],false)
                         ,prepare_mysql($lines[3],false)
                         ,prepare_mysql($address->data['Address Town'],false)
                         ,prepare_mysql($address->data['Address Country First Division'],false)

                         ,prepare_mysql($address->data['Address Postal Code'],false)
                         ,prepare_mysql($address->data['Address Country Name'],false)
                         ,prepare_mysql($address->display('xhtml'),false)
                         ,$address->data['Address Country Key']
                         ,prepare_mysql($address->data['Address Country Code'],false)
                         ,prepare_mysql($address->data['Address Country 2 Alpha Code'],false)

                         ,$this->id);

            mysql_query($sql);
            $this->get_data('id',$this->id);
            $this->update_parents();
        }



    }


    function update_parents() {
        $sql=sprintf("select  `Customer Key`   from  `Customer Dimension` where `Customer Main Ship To Key`=%d  ",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $sql=sprintf("update `Customer Dimension` set `Customer Main Ship To Town`=%s ,`Customer Main Ship To Postal Code`=%s,`Customer Main Ship To Country Region`=%s,`Customer Main Ship To Country`=%s,`Customer Main Ship To Country Code`=%s ,`Customer Main Ship To Country 2 Alpha Code`=%s ,`Customer Main Ship To Country Key`=%d where `Customer Key`=%d "
                         ,prepare_mysql($this->data['Ship To Town'],false)
                         ,prepare_mysql($this->data['Ship To Postal Code'],false)
                         ,prepare_mysql($this->data['Ship To Line 4'],false)
                         ,prepare_mysql($this->data['Ship To Country'])
                         ,prepare_mysql($this->data['Ship To Country Code'])
                         ,prepare_mysql($this->data['Ship To Country 2 Alpha Code'])
                         ,$this->data['Ship To Country Key']
                         ,$row['Customer Key']


                        );
            mysql_query($sql);
        }

        $sql=sprintf("select  `Order Key`   from  `Order Dimension` where  `Order Current Dispatch State` in ('In Process','Submited') and  `Order Ship To Keys`=%d  ",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $sql=sprintf("update `Order Dimension` set `Order XHTML Ship Tos`=%s  where `Order Key`=%d "
                        
                         ,prepare_mysql($this->data['Ship To XHTML Address'])
                         ,$row['Order Key']


                        );
            mysql_query($sql);
        }



    }



    function create($data) {

        $this->data=$data;

        $keys='';
        $values='';

        foreach($this->data as $key=>$value) {


            //  if(preg_match('/Address Data Creation/i',$key) ){
            //	$keys.=",`".$key."`";
            //	$values.=', Now()';
            //}else{
            $keys.=",`".$key."`";
            $values.=','.prepare_mysql($value,false);
            // }

        }
        $values=preg_replace('/^,/','',$values);
        $keys=preg_replace('/^,/','',$keys);

        $sql="insert into `Ship To Dimension` ($keys) values ($values)";
        //print $sql;
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->data['Address Key']= $this->id;
        } else {
            print "Error can not create address\n";
            exit;

        }
    }



    /*
      Function: prepare_data
      Transfrom Address Dimension data schema to Dhip To one
    */
    public static function prepare_data($raw_data) {

        if (preg_match('/ESP/i',$raw_data['Address Country Code']))
            $street=_trim($raw_data['Address Street Type'].' '.$raw_data['Address Street Name'].' '.$raw_data['Address Street Number']);
        else
            $street=_trim($raw_data['Address Street Number'].' '.$raw_data['Address Street Name'].' '.$raw_data['Address Street Type']);

        $internal=_trim($raw_data['Address Internal'].' '.$raw_data['Address Building']);
        $subtown_address=$raw_data['Address Town Second Division'];
        if ($raw_data['Address Town First Division'])
            $subtown_address.=' ,'.$raw_data['Address Town First Division'];
        $subtown_address=_trim($subtown_address);

        $subcountry_address=$raw_data['Address Country Second Division'];
        if ($raw_data['Address Country First Division'])
            $subcountry_address.=' ,'.$raw_data['Address Country First Division'];
        $subcountry_address=_trim($subcountry_address);


        $data['Ship To Line 1']=$internal;
        $data['Ship To Line 2']=$street;
        $data['Ship To Line 3']=$subtown_address;
        $data['Ship To Line 4']=$subcountry_address;
        $data['Ship To Town']=$raw_data['Address Town'];
        $data['Ship To Postal Code']=$raw_data['Address Postal Code'];
        $data['Ship To Country']=$raw_data['Address Country Name'];
        $data['Ship To Country Code']=$raw_data['Address Country Code'];
        $data['Ship To Country Key']=$raw_data['Address Country Key'];
        $data['Ship To Country 2 Alpha Code']=$raw_data['Address Country 2 Alpha Code'];
        $separator='<br/>';
        $data['Ship To XHTML Address']='';
        if ($internal!='')
            $data['Ship To XHTML Address'].=$internal.$separator;
        if ($street!='')
            $data['Ship To XHTML Address'].=$street.$separator;
        if ($subtown_address!='')
            $data['Ship To XHTML Address'].=$subtown_address.$separator;
        if ($raw_data['Address Town']!='')
            $data['Ship To XHTML Address'].=$raw_data['Address Town'].$separator;
        if ($raw_data['Address Postal Code']!='')
            $data['Ship To XHTML Address'].=$raw_data['Address Postal Code'].$separator;
        if ($subcountry_address!='')
            $data['Ship To XHTML Address'].=$subcountry_address.$separator;
        $data['Ship To XHTML Address'].=$raw_data['Address Country Name'].' ('.$raw_data['Address Country Code'].')';

        return $data;
    }

}
