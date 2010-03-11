<?php
/*
 File: Supplier.php 

 This file contains the Supplier Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');

include_once('class.Company.php');
include_once('class.Contact.php');
include_once('class.Telecom.php');
include_once('class.Email.php');
include_once('class.Address.php');
//include_once('Name.php');
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
  var $new=false;
  
  function Supplier($arg1=false,$arg2=false,$arg3=false) {

    $this->table_name='Supplier';
    $this->ignore_fields=array('Supplier Key','Supplier 1 Year Acc Parts Profit'
			       ,'Supplier 1 Year Acc Parts Profit After Storing'
			       ,'Supplier 1 Year Acc Cost'
			       ,'Supplier 1 Year Acc Parts Sold Amount'
			       ,'Supplier 1 Quarter Acc Parts Profit'
			       ,'Supplier Total Parts Profit'
			       ,'Supplier Total Parts Profit After Storing'
			       ,'Supplier Total Cost'
			       ,'Supplier Total Parts Sold Amount'
			       ,'Supplier 1 Quarter Acc Parts Profit After Storing'
			       ,'Supplier 1 Quarter Acc Cost'
			       ,'Supplier 1 Quarter Acc Parts Sold Amount'
			       ,'Supplier 1 Month Acc Parts Profit'
			       ,'Supplier 1 Month Acc Parts Profit After Storing'
			       ,'Supplier 1 Month Acc Cost'
			       ,'Supplier 1 Month Acc Parts Sold Amount'
			       ,'Supplier 1 Month Acc Parts Broken'
			       ,'Supplier 1 Week Acc Parts Profit'
			       ,'Supplier 1 Week Acc Parts Profit After Storing'
			       ,'Supplier 1 Week Acc Cost'
			       ,'Supplier 1 Week Acc Parts Sold Amount'
			       ,'Supplier Stock Value' 
			       ,'Supplier Active Company Products'
			       ,'Supplier Discontinued Company Products'
			       ,'Supplier Surplus Availability Products'
			       ,'Supplier Optimal Availability Products'
			       ,'Supplier Low Availability Products'
			       ,'Supplier Critical Availability Products'
			       ,'Supplier Out Of Stock Products'
			       ,'Supplier For Sale Products'
			       ,'Supplier Not For Sale Products'
			       ,'Supplier To Be Discontinued Products'
			       ,'Supplier Discontinued Products'
			       
			       );


     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/^find/i',$arg1)){
     
       $this->find($arg2,$arg3);
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
    if(isset($raw_data['Supplier Code']) and $raw_data['Supplier Code']==''){
      $this->get_data('id',1);
      return;
    }
     

    $data=$this->base_data();
     foreach($raw_data as $key=>$value){
       if(array_key_exists($key,$data)){
	 $data[$key]=_trim($value);
       }elseif(preg_match('/^Supplier Address/',$key)){
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

       if(!$this->found)
	 $this->create($data);
     }
    
     
     if($update){
       
       if($this->found)
	 $this->update($data);
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

    $main_email_key=false;
    $main_telephone_key=false;
    $main_fax_key=false;
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
      $main_email_key=$company->data['Company Main Email Key'];
      //$this->data['Supplier Main Email Key']=$company->data['Company Main Email Key'];
      //$this->data['Supplier Main XHTML Email']=$company->data['Company Main XHTML Email'];
      //$this->data['Supplier Main Plain Email']=$company->data['Company Main Plain Email'];
    }
    if($company->data['Company Main Telephone Key']){
      $main_telephone_key=$company->data['Company Main Telephone Key'];
      //$this->data['Supplier Main Telephone Key']=$company->data['Company Main Telephone Key'];
      //$this->data['Supplier Main XHTML Telephone']=$company->data['Company Main Telephone'];
      //$this->data['Supplier Main Plain Telephone']=$company->data['Company Main Plain Telephone'];
    }
    if($company->data['Company Main FAX Key']){
      $main_fax_key=$company->data['Company Main FAX Key'];
      //$this->data['Supplier Main FAX Key']=$company->data['Company Main FAX Key'];
      //$this->data['Supplier Main XHTML FAX']=$company->data['Company Main FAX'];
      //$this->data['Supplier Main Plain FAX']=$company->data['Company Main Plain FAX'];
    }
     if($company->data['Company Main Web Site']){
       $this->data['Company Main Web Site']=$company->data['Company Main Web Site'];
	     
     }
    
    $this->data['Supplier Main Contact Key']=$company->data['Company Main Contact Key'];
    //$this->data['Supplier Main Contact Name']=$company->data['Company Main Contact Name'];
    //$this->data['Supplier Fiscal Name']=$company->data['Company Fiscal Name'];
    
    
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
      
      $this->update_company($company->id,true);
       $history_data=array(
			  'note'=>_('Supplier Created')
			  ,'details'=>_trim(_('New supplier')." \"".$this->data['Supplier Name']."\"  "._('added'))
			  ,'action'=>'created'
			  );
      $this->add_history($history_data);
      $this->new=true;

      
       if($main_email_key){
	$this->update_email($main_email_key);
      }

      if($main_telephone_key){

	$this->add_tel(array(
			     'Telecom Key'=>$main_telephone_key
			     ,'Telecom Type'=>'Contact Telephone'
			     ));
	
      }
      if($main_fax_key){
	$this->add_tel(array(
			     'Telecom Key'=>$main_fax_key
			     ,'Telecom Type'=>'Contact Fax'
			     ));
      }




      
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
      $sql=sprintf("select sum(if(`Supplier Product Buy State`='Ok',1,0)) as buy_ok, sum(if(`Supplier Product Buy State`='Discontinued',1,0)) as discontinued from `Supplier Product Dimension` where  `supplier key`=%d",$this->id);
      
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
      
      $sql=sprintf("select  sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For Sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Key`=SPPL.`Supplier Key` and SPD.`Supplier Product Code`=SPPL.`Supplier Product Code`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where SPD.`Supplier Key`=%d ;",$this->id);
      //   print "$sql\n";
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
    $sql=sprintf("select sum(`Supplier Product Total Sold Amount`) as sold,sum(`Supplier Product Total Parts Profit`) as profit,sum(`Supplier Product Total Parts Profit After Storing`) as profit_astoring,sum(`Supplier Product Total Cost`) as cost  from `Supplier Product Dimension`  where `supplier key`=%d",$this->id);
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
   case('Supplier Company Name'):
     $this->update_company_name($value,$options);
     break;

   default:
     $this->update_field($field,$value,$options);
   }

   
 }

 function update_company_name($value,$options){
  
     $company=new Company($this->data['Supplier Company Key']);
     $company->editor=$this->editor;
     $company->update(array('Company Name'=>$value));

     if($company->updated){
      
       $this->updated=true;
       $this->new_value=$company->new_value;
     }

 }

 /*function:get_formated_id_link
     Returns formated id_link
    */
   function get_formated_id_link(){
     return sprintf('<a href="supplier.php?id=%d">%s</a>',$this->id, $this->get_formated_id());

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



/*
  function:update_email
  */
 function update_email($email_key=false){
   if(!$email_key)
     return;
   $email=new Email($email_key);
   if(!$email->id){
     $this->msg='Email not found';
     return;

   }


$old_value=$this->data['Supplier Main Email Key'];
   if($old_value  and $old_value!=$email_key   ){
     $this->remove_email();
     }

   $sql=sprintf("insert into `Email Bridge` values (%d,'Supplier',%d,%s,'Yes','Yes')",
                $email->id,
                $this->id,
                prepare_mysql(_('Supplier Main Email'))
                );
   mysql_query($sql);

   $old_plain_email=$this->data['Supplier Main Plain Email'];
   $this->data['Supplier Main Email Key']=$email->id;
   $this->data['Supplier Main Plain Email']=$email->display('plain');
   $this->data['Supplier Main XHTML Email']=$email->display('xhtml');
   $sql=sprintf("update `Supplier Dimension` set `Supplier Main Email Key`=%d,`Supplier Main Plain Email`=%s,`Supplier Main XHTML Email`=%s where `Supplier Key`=%d"

                ,$this->data['Supplier Main Email Key']
                ,prepare_mysql($this->data['Supplier Main Plain Email'])
                ,prepare_mysql($this->data['Supplier Main XHTML Email'])
                ,$this->id
                );
   if(mysql_query($sql)){
if($old_plain_email!=$this->data['Supplier Main Plain Email']){
       $this->updated=true;
       $note=_('Email changed');
       if($old_value){
        
         $details=_('Supplier email changed from')." \"".$old_plain_email."\" "._('to')." \"".$this->data['Supplier Main Plain Email']."\"";
       }else{
         $details=_('Supplier email set to')." \"".$this->data['Supplier Main Plain Email']."\"";
       }

       $history_data=array(
                           'indirect_object'=>'Email'
                           ,'indirect_object'=>$email->id
                           ,'details'=>$details
                           ,'note'=>$note
                           );
       $this->add_history($history_data);
     }



   }else{
     $this->error=true;

   }


 }

/* Method: remove_email
  Delete the email from Supplier
  
  Delete telecom record  this record to the Supplier


  Parameter:
  $args -     string  options
 */
 function remove_email($email_key=false){

   
    if(!$email_key){
     $email_key=$this->data['Supplier Main Email Key'];
   }
   
   
   $email=new email($email_key);
   if(!$email->id){
     $this->error=true;
     $this->msg='Wrong email key when trying to remove it';
     $this->msg_updated='Wrong email key when trying to remove it';
   }

   $email->set_scope('Supplier',$this->id);
   if( $email->associated_with_scope){
     
     $sql=sprintf("delete `Email Bridge`  where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Email Key`=%d",
		  $this->id
		  
		  ,$this->data['Supplier Main Email Key']
		  );
     mysql_query($sql);
     
     if($email->id==$this->data['Supplier Main Email Key']){
       $sql=sprintf("update `Supplier Dimension` set `Supplier Main XHTML Email`='', `Supplier Main Plain Email`='' , `Supplier Main Email Key`=''  where `Supplier Key`=%d"
		    ,$this->id
		    );
       
       mysql_query($sql);
     }
   }
   

       

 }


function update_company($company_key=false) {
$this->associated=false;
    if (!$company_key)
        return;
    $company=new company($company_key);
    if (!$company->id) {
        $this->msg='company not found';
        return;

    }


    $old_company_key=$this->data['Supplier Company Key'];

    if ($old_company_key  and $old_company_key!=$company_key   ) {
        $this->remove_company();
    }
    if($old_company_key!=$company_key){
    $sql=sprintf("insert into `Company Bridge` values (%d,'Supplier',%d,'Yes','Yes')",
                 $company->id,
                 $this->id
                );
    mysql_query($sql);
    if(mysql_affected_rows()){
    $this->associated=true;
    
    }
    }
    

    $old_name=$this->data['Supplier Name'];
    if ($old_name!=$company->data['Company Name']) {


        if ($this->data['Supplier Name']!=$company->data['Company Name']) {
            $old_supplier_name=$this->data['Supplier Name'];
            $this->data['Supplier Name']=$company->data['Company Name'];
            $this->data['Supplier File As']=$company->data['Company File As'];
            $sql=sprintf("update `Supplier Dimension` set `Supplier Name`=%d,`Supplier File As`=%s where `Supplier Key`=%d"
                         ,prepare_mysql($this->data['Supplier Name'])
                         ,prepare_mysql($this->data['Supplier File As'])
                         ,$this->id
                        );
            mysql_query($sql);
            $note=_('Company name changed');
            $details=_('Supplier Name changed from')." \"".$old_supplier_name."\" "._('to')." \"".$this->data['Supplier Name']."\"";
            $history_data=array(
                              'indirect_object'=>'Supplier Name'
                                                ,'details'=>$details
                                                           ,'note'=>$note
                                                                   ,'action'=>'edited'
                          );
            $this->add_history($history_data);

        }

        $this->data['Supplier Company Key']=$company->id;
        $this->data['Supplier Company Name']=$company->data['Company Name'];
        $sql=sprintf("update `Supplier Dimension` set `Supplier Company Key`=%d,`Supplier Fiscal Name`=%s where `Supplier Key`=%d"

                     ,$this->data['Supplier Company Key']
                     ,prepare_mysql($company->data['Company Fiscal Name'])
                     ,$this->id
                    );
        mysql_query($sql);



        $this->updated=true;






        $note=_('Supplier company name changed');
        if ($old_company_key) {
            $details=_('Supplier company name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Supplier Company Name']."\"";
        } else {
            $details=_('Supplier company set to')." \"".$this->data['Supplier Company Name']."\"";
        }

        $history_data=array(
                          'indirect_object'=>'Supplier Company Name'

                                            ,'details'=>$details
                                                       ,'note'=>$note
                                                               ,'action'=>'edited'
                      );
        $this->add_history($history_data);

    }


if($this->associated){
 $note=_('Company name changed');
            $details=_('Company')." ".$company->data['Company Name']." (".$company->get_formated_id_link().") "._('associated with Supplier:')." ".$this->data['Supplier Name']." (".$this->get_formated_id_link().")";
            $history_data=array(
                              'indirect_object'=>'Supplier Name'
                                                ,'details'=>$details
                                                           ,'note'=>$note
                                                                   ,'action'=>'edited',
                                                                    'deep'=>2
                          );
            $this->add_history($history_data,true);
}

$this->update_contact($company->data['Company Main Contact Key']);

}





/* Method: add_tel
  Add/Update an telecom to the Supplier
*/
   function add_tel($data,$args='principal'){
     
     $principal=false;
     if(preg_match('/not? principal/',$args) ){
       $principal=false;
     }elseif( preg_match('/principal/',$args)){
       $principal=true;
     }

   

   
      if(is_numeric($data)){
	$tmp=$data;
	unset($data);
	$data['Telecom Key']=$tmp;
      }
      
      if(isset($data['Telecom Key'])){
	$telecom=new Telecom('id',$data['Telecom Key']);
      }

      if(!isset($data['Telecom Type'])  or $data['Telecom Type']!='Contact Fax' )
	$data['Telecom Type']='Contact Telephone';



      if($data['Telecom Type']=='Contact Telephone'){
	$field='Supplier Main Telephone';
	$field_key='Supplier Main Telephone Key';
	$field_plain='Supplier Main Plain Telephone';
	$old_principal_key=$this->data['Supplier Main Telephone Key'];
	$old_value=$this->data['Supplier Main Telephone']." (Id:".$this->data['Supplier Main Telephone Key'].")";
      }else{
	$field='Supplier Main FAX';
	$field_key='Supplier Main FAX Key';
	$field_plain='Supplier Main Plain FAX';
	$old_principal_key=$this->data['Supplier Main FAX Key'];
	$old_value=$this->data['Supplier Main FAX']." (Id:".$this->data['Supplier Main FAX Key'].")";
      }

	
      
      if($telecom->id){
	
	//	print "$principal $old_principal_key ".$telecom->id."  \n";

	
	if($principal and $old_principal_key!=$telecom->id){
	  $sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='Supplier' and  `Subject Key`=%d  ",
		       $this->id
		       ,$telecom->id
		       );
	  mysql_query($sql);
	  
	  $sql=sprintf("update `Supplier Dimension` set `%s`=%s , `%s`=%d  , `%s`=%s  where `Supplier Key`=%d"
		       ,$field
		       ,prepare_mysql($telecom->display('html'))
		       ,$field_key
		       ,$telecom->id
		       ,$field_plain
		       ,prepare_mysql($telecom->display('plain'))
		       ,$this->id
		      );
	  mysql_query($sql);
	  $history_data=array(
			      'note'=>$field." "._('Changed')
			      ,'details'=>$field." "._('changed')." "
			      .$old_value." -> ".$telecom->display('html')
			      ." (Id:"
			      .$telecom->id
			      .")"
			      ,'action'=>'created'
			      );
	  if(!$this->new)
	    $this->add_history($history_data);
	 
	  
	}

	
	
	$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`) values (%d,%d,'Supplier',%s,%s)  ON DUPLICATE KEY UPDATE `Telecom Type`=%s ,`Is Main`=%s  "
		     ,$telecom->id
		     ,$this->id
		     ,prepare_mysql($data['Telecom Type'])
		     ,prepare_mysql($principal?'Yes':'No')
		     ,prepare_mysql($data['Telecom Type'])
		     ,prepare_mysql($principal?'Yes':'No')
		     );
	mysql_query($sql);
	


	


      }

      
      



   }


 /*
  function:update_contact
  */
function update_contact($contact_key=false) {


$this->associated=false;
    if (!$contact_key)
        return;
    $contact=new contact($contact_key);
    if (!$contact->id) {
        $this->msg='contact not found';
        return;

    }


    $old_contact_key=$this->data['Supplier Main Contact Key'];

    if ($old_contact_key  and $old_contact_key!=$contact_key   ) {
        $this->remove_contact();
    }
    if($old_contact_key!=$contact_key){
    $sql=sprintf("insert into `Contact Bridge` values (%d,'Supplier',%d,'Yes','Yes')",
                 $contact->id,
                 $this->id
                );
    mysql_query($sql);
    if(mysql_affected_rows()){
    $this->associated=true;
    
    }
    
    }

    $old_name=$this->data['Supplier Main Contact Name'];
    if ($old_name!=$contact->display('name')) {


       
        $this->data['Supplier Main Contact Key']=$contact->id;
        $this->data['Supplier Main Contact Name']=$contact->display('name');
        $sql=sprintf("update `Supplier Dimension` set `Supplier Main Contact Key`=%d,`Supplier Main Contact Name`=%s where `Supplier Key`=%d"

                     ,$this->data['Supplier Main Contact Key']
                     ,prepare_mysql($this->data['Supplier Main Contact Name'])
                     ,$this->id
                    );
        mysql_query($sql);
//print $sql;


        $this->updated=true;






        $note=_('Supplier contact name changed');
        if ($old_contact_key) {
            $details=_('Supplier contact name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Supplier Main Contact Name']."\"";
        } else {
            $details=_('Supplier contact set to')." \"".$this->data['Supplier Main Contact Name']."\"";
        }

        $history_data=array(
                          'indirect_object'=>'Supplier Main Contact Name'

                                            ,'details'=>$details
                                                       ,'note'=>$note
                                                               ,'action'=>'edited'
                      );
        $this->add_history($history_data);

    }


if($this->associated){
 $note=_('Contact name changed');
            $details=_('Contact')." ".$contact->display('name')." (".$contact->get_formated_id_link().") "._('associated with Supplier:')." ".$this->data['Supplier Name']." (".$this->get_formated_id_link().")";
            $history_data=array(
                              'indirect_object'=>'Supplier Name'
                                                ,'details'=>$details
                                                           ,'note'=>$note
                                                                   ,'action'=>'edited',
                                                                    'deep'=>2
                          );
            $this->add_history($history_data,true);
}

}





function remove_company($company_key=false){

   
    if(!$company_key){
     $company_key=$this->data['Supplier Company Key'];
   }
   
   
   $company=new company($company_key);
   if(!$company->id){
     $this->error=true;
     $this->msg='Wrong company key when trying to remove it';
     $this->msg_updated='Wrong company key when trying to remove it';
   }

   $company->set_scope('Supplier',$this->id);
   if( $company->associated_with_scope){
     
     $sql=sprintf("delete `Company Bridge`  where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Company Key`=%d",
		  $this->id
		  
		  ,$this->data['Supplier Company Key']
		  );
     mysql_query($sql);
     
     if($company->id==$this->data['Supplier Company Key']){
       $sql=sprintf("update `Supplier Dimension` set `Supplier Company Name`='' , `Supplier Company Key`=''  where `Supplier Key`=%d"
		    ,$this->id
		    );
       
       mysql_query($sql);
       if($this->data['Supplier Type']=='Company'){
         $sql=sprintf("update `Supplier Dimension` set `Supplier Name`='' , `Supplier File As`=''  where `Supplier Key`=%d"
		    ,$this->id
		    );
       
       mysql_query($sql);
       
       }
       
       
     }
   }
 }

 function update_telephone($telecom_key) {

        $old_telecom_key=$this->data['Supplier Main Telephone Key'];

        $telecom=new Telecom($telecom_key);
        if (!$telecom->id) {
            $this->error=true;
            $this->msg='Telecom not found';
            $this->msg_updated.=',Telecom not found';
            return;
        }
        $old_value=$this->data['Supplier Main Telephone'];
        $sql=sprintf("update `Supplier Dimension` set `Supplier Main Telephone`=%s ,`Supplier Main Plain Telephone`=%s  ,`Supplier Main Telephone Key`=%d where `Supplier Key`=%d "
                     ,prepare_mysql($telecom->display('xhtml'))
                     ,prepare_mysql($telecom->display('plain'))
                     ,$telecom->id
                     ,$this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()) {

            $this->updated;
            if ($old_value!=$telecom->display('xhtml'))
                $history_data=array(
                                  'indirect_object'=>'Supplier Main Telephone'
                                                    ,'old_value'=>$old_value
                                                                 ,'new_value'=>$telecom->display('xhtml')
                              );
            $this->add_history($history_data);
        }

    }

    function update_fax($telecom_key) {


        $old_telecom_key=$this->data['Supplier Main FAX Key'];

        $telecom=new Telecom($telecom_key);
        if (!$telecom->id) {
            $this->error=true;
            $this->msg='Telecom not found';
            $this->msg_updated.=',Telecom not found';
            return;
        }
        $old_value=$this->data['Supplier Main FAX'];
        $sql=sprintf("update `Supplier Dimension` set `Supplier Main FAX`=%s ,`Supplier Main Plain FAX`=%s  ,`Supplier Main Plain FAX`=%d where `Supplier Key`=%d "
                     ,prepare_mysql($telecom->display('xhtml'))
                     ,prepare_mysql($telecom->display('plain'))
                     ,$telecom->id
                     ,$this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()) {
            $this->updated;
            if ($old_value!=$telecom->display('xhtml'))
                $history_data=array(
                                  'indirect_object'=>'Supplier Main FAX'
                                                    ,'old_value'=>$old_value
                                                                 ,'new_value'=>$telecom->display('xhtml')
                              );
            $this->add_history($history_data);
        }

    }





}




?>