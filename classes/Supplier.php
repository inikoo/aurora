<?
/*
 File: Supplier.php 

 This file contains the Supplier Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('DB_Table.php');

include_once('Company.php');
include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');
/* class: Supplier
 Class to manage the *Supplier Dimension* table
*/
class supplier extends DB_Table{


/*
    Constructor: Supplier
    
    Initializes the class, Search/Load or Create for the data set 
    
    Parameters:
    arg1 -    (optional) Could be the tag for the Search Options or the Company Key for a simple object key search
    arg2 -    (optional) Data used to search or create the object
    
       Returns:
       void
       


     */

  function Supplier($arg1=false,$arg2=false) {

    $this->table_name='Supplier';
    $this->ignore_fields=array('Supplier Key');


     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/^find/i',$arg1)){
     
       $this->find($arg2,$arg1);
       return;
     }   

     if(preg_match('/create|new/i',$arg1)){
     
       $this->find($arg2,'create');
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

    

    $this->data=$this->base_data();
    
 

    if($tipo=='id' or $tipo=='key')
      $sql=sprintf("select * from `Supplier Dimension` where `Supplier Key`=%d",$id);
    elseif ($tipo=='code'){
      if($id=='')
	$id=_('Unknown');
      
      $sql=sprintf("select * from `Supplier Dimension` where `Supplier Code`=%s ",prepare_mysql($id));
      

    }

    
    // print "$sql\n";
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Supplier Key'];
    
     
  }

 /*
    Method: find
    Find Supplier with similar data
   
   
   */  
 function find($raw_data,$options){
   // print "$options\n";
   //print_r($raw_data);
   
   if(isset($raw_data['editor'])){
     foreach($raw_data['editor'] as $key=>$value){
       
       if(array_key_exists($key,$this->editor))
	 $this->editor[$key]=$value;
       
      }
   }


   

   $create='';
   $update='';
   if(preg_match('/create/i',$options)){
     $create='create';
   }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    if(isset($raw_data['name']))
      $raw_data['Supplier Name']=$raw_data['name'];
    if(isset($raw_data['code']))
      $raw_data['Supplier Code']=$raw_data['code'];
    if(isset($raw_data['Supplier Code']) and $raw_data['Supplier Code']=='')
      $raw_data['Supplier Code']=_('Unknown');

    $data=$this->base_data();
     foreach($raw_data as $key=>$value){
       if(array_key_exists($key,$data)){
	 $data[$key]=_trim($value);
       }
    }

     if($data['Supplier Code']!=''){
       $sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Code`=%s ",prepare_mysql($data['Supplier Code']));
       $result=mysql_query($sql);
       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	 $this->found=true;
	 $this->found_key=$row['Supplier Key'];
	 
       }
     }
     
     if($this->found){
       $this->get_data('id',$this->found_key);
     }
     

     if($create){

       if($this->found)
	 $this->update($data);
       else
	 $this->create($data);
     }
    

    
 }

 /*
   Function: get
   Get data from the class
 */
 function get($key){



     if(array_key_exists($key,$this->data))
       return $this->data[$key];
     
     switch($key){
     case('Formated ID'):
     case("ID"):
        return $this->get_formated_id();
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


/*Method: create
   Creates a new supplier record

   The data should should be previously checked for duplicates 

   Parameter:
   array with the following items:


   code - (optional) Suppiler code
   supplier_id - (optional)
   contact_name - (optional) 

   if $data is empty Unknown supplier is created
   
   */
  function create($raw_data){

    //print_r($raw_data);

    $this->data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$this->data)){
	$this->data[$key]=_trim($value);
      }
    }

  

    if($this->data['Supplier Name']==''){
      $this->data['Supplier Name']=_('Unknown Supplier');
      $this->data['Supplier Code']=$this->create_code(_('Unknown Supplier'));
    }
    if($this->data['Supplier Code']==''){
      $this->data['Supplier Code']=$this->create_code($this->data['Supplier Code']);
    }

    
    $this->data['Supplier ID']=$this->new_id();
    $this->data['Supplier Code']=$this->check_repair_code($this->data['Supplier Code']);


    if(!$this->data['Supplier Company Key']){
      $raw_data['editor']=$this->editor;
      $company=new company('find in supplier create',$raw_data);
    
      $this->data['Supplier Company Key']=$company->id;

    }
    

    if($company->data['Company Main Email Key']){
      $this->data['Supplier Main Email Key']=$company->data['Company Main Email Key'];
      $this->data['Supplier Main XHTML Email']=$company->data['Company Main XHTML Email'];
      $this->data['Supplier Main Plain Email']=$company->data['Company Main Plain Email'];
    }
    if($company->data['Company Main Telephone Key']){
      $this->data['Supplier Main Telephone Key']=$company->data['Company Main Telephone Key'];
      $this->data['Supplier Main XHTML Telephone']=$company->data['Company Main Telephone'];
      $this->data['Supplier Main Plain Telephone']=$company->data['Company Main Plain Telephone'];
    }
    if($company->data['Company Main FAX Key']){
      $this->data['Supplier Main FAX Key']=$company->data['Company Main FAX Key'];
      $this->data['Supplier Main XHTML FAX']=$company->data['Company Main FAX'];
      $this->data['Supplier Main Plain FAX']=$company->data['Company Main Plain FAX'];
    }
    
    $this->data['Supplier Main Contact Key']=$company->data['Company Main Contact Key'];
    $this->data['Supplier Main Contact Name']=$company->data['Company Main Contact Name'];
    $this->data['Supplier Fiscal Name']=$company->data['Company Fiscal Name'];
    
    
    //    print_r( $this->data);

    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      $keys.=",`".$key."`";
      $values.=','.prepare_mysql($value,false);
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Supplier Dimension` ($keys) values ($values)";
    //print $sql;

    if(mysql_query($sql)){

      $this->id=mysql_insert_id();
      $this->get_data('id',$this->id);
      
      
    }else{
      // print "Error can not create supplier $sql\n";
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
   /*
    Function: create_code
    Create supplier code based in the supplier name


   */

  function create_code($name){
    $code=preg_replace('/[!a-z]/i','',$name);
    $code=preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    $code=preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    $code=preg_split('/\s*/',$name);
    $code=$code[0];
    $code=$this->check_repair_code($code);
    
    return $code;
  }
  /*
    Function: check_repair_code
    Check code for errors/duplicates and return a valid one if errors found


   */
  protected function check_repair_code($code){



    $code=_trim($code);
    if(!$this->is_valid_code($code)){
      if($code==''){
	$code='sup';
	if($this->is_valid_code($code))
	  return $code;
      }
      if(preg_match('/\d+$/',$code,$match[0]))
	$index=(int)$match[0]+1 ;
      else
	$index=2;
      $_code=$code;
      $ok=false;
      while($ok or $index<100){
	$code=$_code.$index;

	if($this->is_valid_code($code))
	  return $code;
	$index++;
      }
      exit("Error can no create code");
    }else
      return $code;
    
  }
  
 /*
    Function: is_valid_code
    Check code for duplicates

    Return:
    True,False
   */
  public static function is_valid_code($code){
    //  print "------------ $code\n";
    $code=_trim($code);
    if($code=='')
      return false;
    $sql=sprintf("select `Supplier Key`  from `Supplier Dimension` where `Supplier Code`=%s",prepare_mysql($code));

    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      return false;
    }else{
      return true;
    }  
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

 function update_field_switcher($field,$value,$options=''){
   switch($field){
   case('Supplier ID'):
   case('Supplier Main Contact Key'):
   case('Supplier Average Delivery Days'):
   case('Supplier Valid From'):
   case('Supplier Valid To'):
   case('Supplier Stock Value'):
   case('Supplier Company Key'):
   case('Supplier Accounts Payable Contact Key'):
   case('Supplier Sales Contact Key'):
   case('Supplier Main Email Key'):
   case('Supplier Main Telephone Key'):



     break;
   default:
     $this->update_field($field,$value,$options);
   }

   
 }

 /*function:get_formated_id
     Returns formated id
    */
   function get_formated_id(){
     global $myconf;
     
     $sql="select count(*) as num from `Supplier Dimension`";
     $res=mysql_query($sql);
     $min_number_zeros=4;
     if($row=mysql_fetch_array($res)){
       if(strlen($row['num'])-1>$min_number_zeros)
	 $min_number_zeros=strlen($row['num'])-01;
     }
     if(!is_numeric($min_number_zeros))
       $min_number_zeros=4;

     return sprintf("%s%0".$min_number_zeros."d",$myconf['supplier_id_prefix'], $this->data['Supplier ID']);

   }

}

?>