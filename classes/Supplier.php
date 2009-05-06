<?
/*
 File: Supplier.php 

 This file contains the Supplier Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('Company.php');
include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');
/* class: Supplier
 Class to manage the *Supplier Dimension* table
*/
class supplier{
  var $db;
  var $data=array();
  var $items=array();

  var $id=false;


  function Supplier($arg1=false,$arg2=false) {
     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/create|new/i',$arg1)){
       $this->create($arg2);
       return;
     }       
     $this->get_data($arg1,$arg2);
     
 }
 /*
   Method: get_data
   Load the data from the database
   
   $tipo can be key,id,code
  
   */

  function get_data($tipo,$id){
    if($tipo=='id' or $tipo=='key')
      $sql=sprintf("select * from `Supplier Dimension` where `Supplier Key`=%d",$id);
    elseif ($tipo=='code'){
      if($id=='')
	$id=_('Unknown');
      
      $sql=sprintf("select * from `Supplier Dimension` where `Supplier Code`=%s  and `Supplier Most Recent`='Yes'",prepare_mysql($id));
      
    }

    
    // print "$sql\n";
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Supplier Key'];
    
     
  }

   function get($key){

     if(array_key_exists($key,$this->data))
       return $this->data[$key];
     
     switch($key){
     case('Total Parts Sold Amount'):
       return money($this->data['Supplier Total Parts Sold Amount']);
       break;
     case('Total Parts Profit'):
       return money($this->data['Supplier Total Parts Profit After Storing']);
       break;
     case('Stock Value'):

       if(!is_numeric($this->data['Supplier Stock Value']))
	 return _('Unknown');
       else
       return money($this->data['Supplier Stock Value']);
       break;

     }




     

     print "Error $key not found in get from supplier\n";
     return false;

  }
 /*
   Function: base_data
   Inizializate $data array with the default field values
   */
  function base_data(){
    $base_data=array(
		     'Supplier Code'=>''
		     ,'Supplier Name'=>''
		     ,'Supplier Fiscal Name'=>''
		     ,'Supplier Company Key'=>''
		     ,'Supplier Main Plain Telephone'=>''
		     ,'Supplier Main Telephone'=>''
		     ,'Supplier Main Contact Name'=>''
		     ,'Supplier Main Contact Key'=>''
		     ,'Supplier Accounts Payable Contact Key'=>''
		     ,'Supplier Sales Contact Key'=>''
		     ,'Supplier Valid From'=>''
		     ,'Supplier Valid To'=>''
		     ,'Supplier Active'=>'Yes'
		     ,'Supplier ID'=>''
		     ,'Supplier Location'=>''
		     ,'Supplier Main XHTML Email'=>''
		     ,'Supplier Main Plain Email'=>''
		       );

  }

/*Method: create
   Creates a new supplier record

   Parameter:
   array with the following items:


   code - (optional) Suppiler code
   supplier_id - (optional)
   contact_name - (optional) 

   if $data is empty Unknown supplier is created
   
   */
  function create($data){
    // print_r($data);
    
   $this->base_data();
    foreach($data as $key=>$value){
      if(isset($this->data[strtolower($key)]))
	$this->data[strtolower($key)]=_trim($value);
    }

    if(!$this->data['Supplier Name']){
      $this->data['Supplier Name']=_('Unknown Supplier');
      $this->data['Supplier Code']=$this->create_code(_('Unknown Supplier'));
    }
    if(!$this->data['Supplier Code']){
      $this->data['Supplier Code']=$this->create_code($this->data['Supplier Code']);
    }
    $this->data['Supplier ID']=$this->new_id();
    $this->data['Supplier Code']=$this->check_code($this->data['Supplier Code']);


    $contact=new contact('new',$data);

    if(isset($data['contact_name']))
      $data_contact=array('name'=>$data['contact_name']);
    elseif(isset($data['contact_name_data']))
      $data_contact=array('name_data'=>$data['contact_name_data']);
    else
      $data_contact=array();


    if(isset($data['address_data']))
      $data_contact['address_data']=$data['address_data'];
    if(isset($data['email']))
      $data_contact['email']=$data['email'];
    if(isset($data['www']))
      $data_contact['www']=$data['www'];


    $contact=new contact('new',$data_contact);
   
    $company=new company('new',
			 array('name'=>$name,'contact key'=>$contact->id)
			 );


    $most_recent='Yes';
    $from=date("Y-m-d H:i:s");
    $to=date("Y-m-d H:i:s");
    $most_recent_key='';

    if(isset($data['most_recent']) and preg_match('/no/i',$data['most_recent']))
      $most_recent='No';
    
    if(isset($data['from']))
      $from=$data['from'];
    if(isset($data['to']))
      $to=$data['to'];
    
    if(isset($data['most_recent_key']) and is_numeric($data['most_recent_key'])  and $data['most_recent_key']>0  )
      $most_recent_key=$data['most_recent_key'];

    $sql=sprintf("insert into `Supplier Dimension` (`Supplier Code`,`Supplier Name`,`Supplier Company Key`,`Supplier Main Contact Key`,`Supplier Accounts Payable Contact Key`,`Supplier Sales Contact Key`,`Supplier Valid From`,`Supplier Valid To`,`Supplier Most Recent`,`Supplier Most Recent Key`,`Supplier ID`,`Supplier Location`,`Supplier Main XHTML Email`) values (%s,%s,%d,%d,%d,%d,%s,%s,%s,%s,%s,%s,%s)",
		 prepare_mysql($code),
		 prepare_mysql($name),
		 $company->id,
		 $contact->id,
		 $contact->id,
		 $contact->id,
		 prepare_mysql($from),
		 prepare_mysql($to),
		 prepare_mysql($most_recent),
		 prepare_mysql($most_recent_key),
		 prepare_mysql($data['supplier id']),
		 prepare_mysql($contact->data['Contact Main Location']),
		 prepare_mysql($contact->data['Contact Main XHTML Email'])
		 );
    // print "$sql\n";
    //print_r($contact->data);
    //exit;

    if(mysql_query($sql)){

      $this->id=mysql_insert_id();
      $this->get_data('id',$this->id);
      
      if($most_recent=='Yes'){
	$sql=sprintf('update `Supplier Dimension` set `Supplier Most Recent Key`=%d where `Supplier Key`=%d',$this->id,$this->id);
	mysql_query($sql);
      }
    }else{
      print "Error can not create supplier\n";exit;
    }






  }

  function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      $this->contact=new Contact($this->data['Supplier Main Contact Key']);
      if($this->contact->id){
	//$this->contact->load('telecoms');
	//$this->contact->load('contacts');
      }
      
    case('products_info'):
      $this->data['Supplier Active Supplier Products']=0;
      $this->data['Supplier Discontinued Supplier Products']=0;
      $sql=sprintf("select sum(if(`Supplier Product Buy State`='Ok',1,0)) as buy_ok, sum(if(`Supplier Product Buy State`='Discontinued',1,0)) as discontinued from `Supplier Product Dimension` where  `Supplier Product Supplier Key`=%d",$this->id);
      
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->data['Supplier Active Supplier Products']=$row['buy_ok'];
	$this->data['Supplier Discontinued Supplier Products']=$row['discontinued'];
	
	$sql=sprintf("update `Supplier Dimension` set `Supplier Active Supplier Products`=%d ,`Supplier Discontinued Supplier Products`=%d where `Supplier Key`=%d  ",
		     $row['buy_ok'],
		     $row['discontinued'],
		     $this->id
		     );
	mysql_query($sql);
      }
      
      $sql=sprintf("select  sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where `Supplier Product Supplier Key`=%d ;",$this->id);
      // print "$sql\n";
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

       $sql=sprintf("update `Supplier Dimension` set `Supplier For Sale Products`=%d ,`Supplier Discontinued Products`=%d ,`Supplier Not For Sale Products`=%d ,`Supplier Unknown Sales State Products`=%d, `Supplier Optimal Availability Products`=%d , `Supplier Low Availability Products`=%d ,`Supplier Critical Availability Products`=%d ,`Supplier Out Of Stock Products`=%d,`Supplier Unknown Stock Products`=%d ,`Supplier Surplus Availability Products`=%d where `Supplier Key`=%d  ",
		    $row['for_sale'],
		    $row['discontinued'],
		    $row['not_for_sale'],
		    $row['sale_unknown'],
		    $row['availability_optimal'],
		    $row['availability_low'],
		    $row['availability_critical'],
		    $row['availability_outofstock'],
		    $row['availability_unknown'],
		    $row['availability_surplus'],
		    $this->id
	    );
       //print "$sql\n";exit;
       mysql_query($sql);
  }
  $this->get_data('id',$this->id);
  
     break;
  
  case('sales'):
    $sql=sprintf("select sum(`Supplier Product Total Sold Amount`) as sold,sum(`Supplier Product Total Parts Profit`) as profit,sum(`Supplier Product Total Parts Profit After Storing`) as profit_astoring,sum(`Supplier Product Total Cost`) as cost  from `Supplier Product Dimension`  where `Supplier Product Supplier Key`=%d",$this->id);
    //    print $sql;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->data['Supplier Total Parts Profit']=$row['profit'];
      $this->data['Supplier Total Parts Profit After Storing']=$row['profit_astoring'];
      $this->data['Supplier Total Cost']=$row['cost'];
      $this->data['Supplier Total Parts Sold Amount']=$row['sold'];

     $sql=sprintf("update `Supplier Dimension` set  `Supplier Total Parts Profit`=%.2f,`Supplier Total Parts Profit After Storing`=%.2f,`Supplier Total Cost`=%.2f ,`Supplier Total Parts Sold Amount`=%.2f  where `Supplier Key`=%d "
		  ,$this->data['Supplier Total Parts Profit']
		  ,$this->data['Supplier Total Parts Profit After Storing']
		  ,$this->data['Supplier Total Cost']
		  ,$this->data['Supplier Total Parts Sold Amount']
		  ,$this->id
		  );
     //      print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update sup\n");
    }
      
    
    

    break;
  }
    
  }
  

  function create_code($name){
    preg_replace('/[!a-z]/i','',$name);
    preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    preg_split('/\s*/',$name);
    return $name;
  }

  function check_code($name){
    return $name;
  }
  
function new_id(){
  $sql="select max(`Supplier ID`) as id from `Supplier Dimension`";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id']+1;
  }else{
    $id=1;
  }  
  return $id;
}

function valid_id($id){
  if(is_numeric($id) and $id>0 and $id<9223372036854775807)
    return true;
  else
    return false;
}


}

?>