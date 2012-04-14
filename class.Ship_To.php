<?php
/*
 File: Ship_To.php

 This file contains the Ship To Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

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



        foreach( $raw_data as $key=> $value) {
        if(array_key_exists($key,$data))
            $data[$key]=$value;

        }
        
        // print_r($raw_data);
       //  print_r($data);
      //  exit("s");
        

        $fields=array('Ship To Email','Ship To Telephone','Ship To Company Name','Ship To Contact Name','Ship To Country Code','Ship To Postal Code','Ship To Town','Ship To Line 1','Ship To Line 2','Ship To Line 3','Ship To Line 4');

        $sql=sprintf("select * from `Ship To Dimension` where true  ");
        foreach($fields as $field) {
            $sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
        }
//print $sql;

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


function get_xhtml_address() {
    $address='';
    $separator="<br/>";
    if ($this->data['Ship To Line 1']!='')
        $address=_trim($this->data['Ship To Line 1']).$separator;
    if ($this->data['Ship To Line 2']!='')
        $address.=_trim($this->data['Ship To Line 2']).$separator;
    if ($this->data['Ship To Line 3']!='')
        $address.=_trim($this->data['Ship To Line 3']).$separator;
    if ($this->data['Ship To Town']!='')
        $address.=_trim($this->data['Ship To Town']).$separator;
    if ($this->data['Ship To Line 4']!='')
        $address.=_trim($this->data['Ship To Line 4']).$separator;
    if ($this->data['Ship To Postal Code']!='')
        $address.=_trim($this->data['Ship To Postal Code']).$separator;




    $address.=$this->data['Ship To Country Name'];

return _trim($address);

}

    function get($key='') {


        if($key=='World Region Code'){
        
        
        if($this->data['Ship To Country Code']=='')return 'UNKN';
        
        $sql=sprintf("select `World Region Code` from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($this->data['Ship To Country Code']));
		$result=mysql_query($sql);
		if($row=mysql_fetch_array($result))
			return ($row['World Region Code']==''?'UNKN':$row['World Region Code']);
		else
		    return 'UNKN';
        
        }


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

   

    function create($data) {

        $this->data=$data;

        $keys='';
        $values='';

        foreach($this->data as $key=>$value) {
    if($key=='Ship To XHTML Address')
            continue;
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
            $this->new=true;
            $this->get_data('id',$this->id);
            $this->data['Ship To XHTML Address']=$this->get_xhtml_address();
            $sql=sprintf("update `Ship To Dimension` set `Ship To XHTML Address`=%s where `Ship To Key`=%d",prepare_mysql($this->data['Ship To XHTML Address']),$this->id);
            mysql_query($sql);
        } else {
            print "Error can not create address\n";
            exit;

        }
    }

function display($tipo){
$separator='\n';
switch ($tipo) {
    case 'xhtml':
   $separator='<br>';

   default:
   $address='';
             if ($this->data['Ship To Line 1']!='')
                    $address=_trim($this->data['Ship To Line 1']).$separator;
                if ($this->data['Ship To Line 2']!='')
                    $address.=_trim($this->data['Ship To Line 2']).$separator;

  if ($this->data['Ship To Line 3']!='')
                    $address.=_trim($this->data['Ship To Line 3']).$separator;
            $town_address=_trim($this->data['Ship To Town']);
                 if ($town_address!='')
                    $address.=$town_address.$separator;
                    
                    if ($this->data['Ship To Line 4']!='')
                    $address.=_trim($this->data['Ship To Line 3']).$separator;
                $ps_address=_trim($this->data['Ship To Postal Code']);
                if ($ps_address!='')
                    $address.=$ps_address.$separator;

                $address.=$this->data['Ship To Country Name'];
        
    
        
        break;
}


}

    function delete_old() {
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
    function update_parents_old() {
        $sql=sprintf("select  `Customer Key`   from  `Customer Dimension` where `Customer Main Delivery Address Key`=%d  ",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $sql=sprintf("update `Customer Dimension` set `Customer Main Delivery Address Town`=%s ,`Customer Main Delivery Address Postal Code`=%s,`Customer Main Delivery Address Country Region`=%s,`Customer Main Delivery Address Country`=%s,`Customer Main Delivery Address Country Code`=%s ,`Customer Main Delivery Address Country 2 Alpha Code`=%s ,`Customer Main Delivery Address Country Key`=%d where `Customer Key`=%d "
                         ,prepare_mysql($this->data['Ship To Town'],false)
                         ,prepare_mysql($this->data['Ship To Postal Code'],false)
                         ,prepare_mysql($this->data['Ship To Line 4'],false)
                         ,prepare_mysql($this->data['Ship To Country Name'])
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
    public static function prepare_data_old($raw_data) {

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
        $data['Ship To Country Name']=$raw_data['Address Country Name'];
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
