<?
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

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/^(create|new)/i',$arg1)){
       $this->find($arg2,'create');
       return;
     } if(preg_match('/find/i',$arg1)){
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

  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Ship To Dimension` where `Ship To Key`=%d",$tag);
    else
      return;
    
    // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Ship To Key'];
    

  }

 /*
    Method: find
    Find Company with similar data
   
    Returns:
    Key of the Shipping Addreses found, if create is found in the options string  returns the new key
   */  
 function find($raw_data,$options){
   
   $create='';
   $update='';
   if(preg_match('/create/i',$options)){
     $create='create';
    }
   if(preg_match('/update/i',$options)){
     $update='update';
   }

   $data=$this->base_data();


   $address_data=Address::prepare_3line($raw_data);

   foreach( $this->prepare_data($address_data) as $key=> $value){
     $data[$key]=$value;
     
   }
   
  


   // Look for duplicates
   $fields=array('Ship To Country Code','Ship To Postal Code','Ship To Town','Ship To Line 1','Ship To Line 2','Ship To Line 3','Ship To Line 4');

   $sql=sprintf("select * from `Ship To Dimension` where true  ");
   foreach($fields as $field){
	$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
   }
   

   //    print "FUZZY $sql\n";
   $result=mysql_query($sql);
   $num_results=mysql_num_rows($result);
   if($num_results==0){
     // address not found
     $this->found=false;
     
     
   }else if($num_results==1){
     $row=mysql_fetch_array($result, MYSQL_ASSOC);
     
     $this->get_data('id',$row['Ship To Key']);
     $this->found=true;
     $this->found_key=$row['Ship To Key'];
     
   }else{// Found in mora than one
     exit("error to shipping addresses");
   }
 
   if(!$this->found and $create){
     $this->create($data);

   }


 }




/*
    Function: get
    Obtiene datos del producto de acuerdo al codigo de producto, al tipo de producto o la totalidad de productos (esto en base al criterio de seleccion)
*/
// JFA
 
  function get($key=''){

    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
  
    


    }
    $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from Ship TO\n";
    return false; 

  }

/*
    Function: delete
    Elimina registros de la tabla Ship To Dimension en base al valor del campo store key, siempre y cuando no haya utilizado anteriormente
*/
// JFA

 function delete(){
   $this->deleted=false;
   //TODO (actualize Ship To Times Used  )
   
   if($this->data['Ship To Times Used']==0){
     $sql=sprintf("delete from `Ship To Dimension` where `Ship To Key`=%d",$this->id);
     if(mysql_query($sql)){

       $this->deleted=true;
	  
     }else{

       $this->msg=_('Error: can not delete shipping address');
       return;
     }     

     $this->deleted=true;
   }else{
     $this->msg=_('Shipping address can not be deleted because it has some products');

   }
 }


/*
    Method: load
    Obtiene registros de las tablas Product Dimension, Product Family Dimension, Product Department Dimension, y actualiza datos de Store Dimension, de acuerdo a la categoria indicada.
*/
// JFA


 function load($tipo,$args=false){
   switch($tipo){
  
     

  

   }
   
 }

/*
    Function: update
    Funcion que permite actualizar el nombre o el codigo en la tabla store dimension, cuidando que no se duplique el valor del codigo o el nombre en dicha tabla
*/
// JFA

 function update($key,$a1=false,$a2=false){
   $this->updated=false;
   $this->msg='Nothing to change';
   
   switch($key){
   case('code'):

     if(_trim($a1)==$this->data['Store Code']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong code (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Store Dimension` where  `Store Code`=%s COLLATE utf8_general_ci  "
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: There is another store with the same code");
       return;
     }
     
      $sql=sprintf("update `Store Dimension` set `Store Code`=%s where `Store Key`=%d  "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Store code updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Store code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
   case('name'):
     
     if(_trim($a1)==$this->data['Store Name']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }
     
     if($a1==''){
       $this->msg=_('Error: Wrong name (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s COLLATE utf8_general_ci"
		,prepare_mysql($a1)
		);

     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another store with the same name");
       return;
     }
     
      $sql=sprintf("update `Store Dimension` set `Store Name`=%s where `Store Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Store name updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Store name could not be updated");

	$this->updated=false;
	
      }
      break;	


   }


 }

/*
    Function: create
    Funcion que permite grabar el nombre y codigo en la tabla store dimension, evitando duplicar el valor de codigo y el nombre en dicha tabla
*/
// JFA
 function create($data){
   
   $this->data=$data;

   $keys='';
    $values='';

    foreach($this->data as $key=>$value){
      

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
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->data['Address Key']= $this->id;
    }else{
      print "Error can not create address\n";exit;
	
    }
 }



/*
  Function: prepare_data
  Transfrom Address Dimension data schema to Dhip To one
*/
 public static function prepare_data($raw_data){
   
   if(preg_match('/ESP/i',$raw_data['Address Country Code']))
     $street=_trim($raw_data['Address Street Type'].' '.$raw_data['Address Street Name'].' '.$raw_data['Address Street Number']);
   else
     $street=_trim($raw_data['Address Street Number'].' '.$raw_data['Address Street Name'].' '.$raw_data['Address Street Type']);

   $internal=_trim($raw_data['Address Internal'].' '.$raw_data['Address Building']);
   $subtown_address=$raw_data['Address Town Secondary Division'];
   if($raw_data['Address Town Primary Division'])
     $subtown_address.=' ,'.$raw_data['Address Town Primary Division'];
   $subtown_address=_trim($subtown_address);
   
    $subcountry_address=$raw_data['Address Country Secondary Division'];
   if($raw_data['Address Country Primary Division'])
     $subcountry_address.=' ,'.$raw_data['Address Country Primary Division'];
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
   if($internal!='')
     $data['Ship To XHTML Address'].=$internal.$separator;
   if($street!='')
     $data['Ship To XHTML Address'].=$street.$separator;
   if($subtown_address!='')
     $data['Ship To XHTML Address'].=$subtown_address.$separator;
   if($raw_data['Address Town']!='')
     $data['Ship To XHTML Address'].=$raw_data['Address Town'].$separator;
   if($raw_data['Address Postal Code']!='')
     $data['Ship To XHTML Address'].=$raw_data['Address Postal Code'].$separator;
    if($subcountry_address!='')
     $data['Ship To XHTML Address'].=$subcountry_address.$separator;
     $data['Ship To XHTML Address'].=$raw_data['Address Country Name'].' ('.$raw_data['Address Country Code'].')';
  
   return $data;
 }

 }
